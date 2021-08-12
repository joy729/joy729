<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use Carbon\Carbon;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showCategoryForm()
    {
        return view('admin.city.city');
    }


    //insert city

    public function createCity(Request $request){
    	$validatedData = $request->validate([
	        'city_name' => 'required',
	        'city_slug' => 'required'
	    ]);
	    $data =array();
		$data['city_name']= $request->city_name;
		$data['city_slug']= $request->city_slug;
		$data['created_by']= Auth::guard('admin')->user()->admin_no;
		$data['created_at']= Carbon::now()->toDatetimeString();

		$insert=DB::table('cities')->insert($data);	

		if($insert){
	        $notification=array(
	            'messege'=>'City created successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('city.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'City creation failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //all cities
    public function getAllCities(){
    	$cities = DB::table('cities')
					    	->leftJoin('admins','cities.created_by','admins.admin_no')
					    	->select('cities.*','admins.admin_name')
					    	->where('cities.is_deleted',0)
					    	->orderBy('cities.city_no','DESC')
					    	->get();
    	return view('admin.city.all-city',compact('cities'));
    }


    //delete city
    public function deleteCity($id){
    	$data =array();
		$data['is_deleted']= 1;

    	$delete_city = DB::table('cities')->where('city_no',$id)->update($data);
    	if($delete_city){
    		$notification=array(
	            'messege'=>'City deleted successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('city.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'City delete failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //show update city form

    public function getUpdateCityForm($id){
    	$city = DB::table('cities')->where('city_no',$id)->first();
    	return view('admin.city.edit-city',compact('city'));
    }


    //update city

    public function UpdateCity(Request $request,$id){
    	$validatedData = $request->validate([
	        'city_name' => 'required',
	        'city_slug' => 'required'
	    ]);
	    $data =array();
			$data['city_name']= $request->city_name;
			$data['city_slug']= $request->city_slug;
			$data['created_by']= Auth::guard('admin')->user()->admin_no;
			$data['created_at']= Carbon::now()->toDatetimeString();
			$update_city =DB::table('cities')->where('city_no',$id)->update($data);
			if($update_city){
	        $notification=array(
	            'messege'=>'City updated successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('city.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'City update failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }
}
