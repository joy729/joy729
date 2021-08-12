<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showCategoryForm()
    {
        return view('admin.category.category');
    }


    //insert category

    public function createCategory(Request $request){
    	$validatedData = $request->validate([
	        'category_name' => 'required',
	        'category_slug' => 'required',
	        'category_image' => 'mimes:jpeg,jpg,png,PNG|max:2048',
	    ]);
	    $data =array();
		$data['category_name']= $request->category_name;
		$data['category_slug']= $request->category_slug;
		$data['created_by']= Auth::guard('admin')->user()->admin_no;
		$data['category_image']= '';
		$data['created_at']= Carbon::now()->toDatetimeString();

		if($request->hasFile('category_image')){
			$image=$request->file('category_image');
			$random = mt_rand(1000000, 9999999);
	        $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
	        Image::make($image)->resize(540,240)->save('public/uploads/'.$imageName);
	        $data['category_image'] = $imageName;
		}else{
			$data['category_image']= "default.png";
		}
		$insert=DB::table('categories')->insert($data);	

		if($insert){
	        $notification=array(
	            'messege'=>'Category created successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('category.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Category creation failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //all categories
    public function getAllCategories(){
    	$categories = DB::table('categories')
    	->leftJoin('admins','categories.created_by','admins.admin_no')
    	->select('categories.*','admins.admin_name')
    	->where('categories.is_deleted',0)
    	->orderBy('categories.category_no','DESC')
    	->get();
    	return view('admin.category.all-category',compact('categories'));
    }


    //delete category
    public function deleteCategory($id){
    	$data =array();
		$data['is_deleted']= 1;

    	$delete_cat = DB::table('categories')->where('category_no',$id)->update($data);
    	if($delete_cat){
    		$notification=array(
	            'messege'=>'Category deleted successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('category.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Category delete failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //show update category form

    public function getUpdateCategoryForm($id){
    	$category = DB::table('categories')->where('category_no',$id)->first();
    	return view('admin.category.edit-category',compact('category'));
    }


    //update category

    public function UpdateCategory(Request $request,$id){

    	$validatedData = $request->validate([
	        'category_name' => 'required',
	        'category_slug' => 'required'
	    ]);
	    $data = array();
		$data['category_name']= $request->category_name;
		$data['category_slug']= $request->category_slug;
		//$data['category_image']= '';
		$data['updated_at']= Carbon::now()->toDatetimeString();
		if($request->hasFile('category_image')){
			$image=$request->file('category_image');
			$random = mt_rand(1000000, 9999999);
	        $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
	        Image::make($image)->resize(540,240)->save('public/uploads/'.$imageName);
	        $data['category_image'] = $imageName;
		}else{
			$data['category_image']= $request->category_image;
		}
		$update_category = DB::table('categories')->where('category_no',$id)->update($data);
	    if($update_category){
	        $notification=array(
	            'messege'=>'Category updated successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('category.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Category update failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }
}
