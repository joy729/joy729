<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use Carbon\Carbon;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showSubCategoryForm()
    {
    	$get_Cat = DB::table('categories')
				 ->where('categories.is_deleted',0)
				 ->get();
        return view('admin.subcategory.subcategory',compact('get_Cat'));
    }


    //insert category

    public function createSubCategory(Request $request){

    	$messages = [
		    'category_no.required' => 'Please select a category',
		  ];
    	$validatedData = $request->validate([
    		'category_no' => 'required|not_in:0',
	        'sub_category_name' => 'required',
	        'sub_category_slug' => 'required',
	        'sub_category_image' => 'mimes:jpeg,jpg,png,PNG|max:2048',
	    ], $messages);
	    $data =array();
	    $data['category_no']= $request->category_no;
		$data['sub_category_name']= $request->sub_category_name;
		$data['sub_category_slug']= $request->sub_category_slug;
		$data['created_by']= Auth::guard('admin')->user()->admin_no;
		$data['sub_category_image']= '';
		$data['created_at']= Carbon::now()->toDatetimeString();

		if($request->hasFile('sub_category_image')){
			$image=$request->file('sub_category_image');
			$random = mt_rand(1000000, 9999999);
	        $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
	        Image::make($image)->resize(960,800)->save('public/uploads/'.$imageName);
	        $data['sub_category_image'] = $imageName;
		}else{
			$data['sub_category_image']= "default.png";
		}
		$insert=DB::table('subcategories')->insert($data);	

		if($insert){
	        $notification=array(
	            'messege'=>'Subcategory created successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('subcategory.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Subcategory creation failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //all subcategories
    public function getAllSubCategories(){
    	$subcategories = DB::table('subcategories')
    					->leftJoin('categories','subcategories.category_no','categories.category_no')
    					->leftJoin('admins','subcategories.created_by','admins.admin_no')
    					->select('subcategories.*','categories.category_name','admins.admin_name')
    					->where('subcategories.is_deleted',0)
    					->orderBy('subcategories.subcategory_no','DESC')
    					->get();
    	return view('admin.subcategory.all-subcategory',compact('subcategories'));
    }


  //   //delete category
    public function deleteSubCategory($id){
    	$data =array();
			$data['is_deleted']= 1;

    	$delete_subcat = DB::table('subcategories')->where('subcategory_no',$id)->update($data);
    	if($delete_subcat){
    		$notification=array(
	            'messege'=>'Subcategory deleted successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('subcategory.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Subcategory delete failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //show update category form

    public function getUpdateSubCategoryForm($id){
    	$get_Cat = DB::table('categories')->where('categories.is_deleted',0)->get();
    	$subcategory = DB::table('subcategories')->where('subcategory_no',$id)->first();
    	return view('admin.subcategory.edit-subcategory',compact('get_Cat','subcategory'));
    }


  //   //update category

    public function UpdateSubCategory(Request $request,$id){
    	$messages = [
		    'category_no.required' => 'Please select a category',
		  ];

    	$validatedData = $request->validate([
	        'sub_category_name' => 'required',
	        'sub_category_slug' => 'required'
	    ],$messages);
	    $data =array();
	    $data['category_no']= $request->category_no;
		$data['sub_category_name']= $request->sub_category_name;
		$data['sub_category_slug']= $request->sub_category_slug;
		$data['created_by']= Auth::guard('admin')->user()->admin_no;
		$data['sub_category_image']= '';
		$data['created_at']= Carbon::now()->toDatetimeString();

		if($request->hasFile('sub_category_image')){
			$image=$request->file('sub_category_image');
			$random = mt_rand(1000000, 9999999);
	        $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
	        Image::make($image)->resize(960,800)->save('public/uploads/'.$imageName);
	        $data['sub_category_image'] = $imageName;
		}else{
			$data['sub_category_image']= $request->sub_category_image;
		}

		$update_category =DB::table('subcategories')->where('subcategory_no',$id)->update($data);
		if($update_category){
	        $notification=array(
	            'messege'=>'Subcategory updated successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('subcategory.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Subcategory update failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }
}
