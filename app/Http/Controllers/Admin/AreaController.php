<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Image;
use Carbon\Carbon;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showAreaForm()
    {
    	$get_city = DB::table('cities')
				 ->where('cities.is_deleted',0)
				 ->get();
        return view('admin.area.area',compact('get_city'));
    }


    //insert area

    public function createArea(Request $request){

    	$messages = [
		    'city_no.required' => 'Please select a city',
		  ];
    	$validatedData = $request->validate([
    		'city_no' => 'required|not_in:0',
        'area_name' => 'required',
        'area_slug' => 'required'
	    ], $messages);
	    $data =array();
	    $data['city_no']= $request->city_no;
			$data['area_name']= $request->area_name;
			$data['area_slug']= $request->area_slug;
			$data['created_by']= Auth::guard('admin')->user()->admin_no;
			$data['created_at']= Carbon::now()->toDatetimeString();
			
			$insert=DB::table('areas')->insert($data);	

			if($insert){
	        $notification=array(
	            'messege'=>'Area created successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('area.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Area creation failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //all areas
    public function getAllAreas(){
    	$areas = DB::table('areas')
    					->leftJoin('cities','areas.city_no','cities.city_no')
    					->leftJoin('admins','areas.created_by','admins.admin_no')
    					->select('areas.*','cities.city_name','admins.admin_name')
    					->where('areas.is_deleted',0)
    					->orderBy('areas.area_no','DESC')
    					->get();
    	return view('admin.area.all-area',compact('areas'));
    }


  //delete area
    public function deleteArea($id){
    	$data =array();
			$data['is_deleted']= 1;
			$delete_area = DB::table('areas')->where('area_no',$id)->update($data);
    	if($delete_area){
    		$notification=array(
	            'messege'=>'Area deleted successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('area.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Area delete failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //show update ara form

    public function getUpdateAreaForm($id){
    	$get_city = DB::table('cities')->where('cities.is_deleted',0)->get();
    	$area = DB::table('areas')->where('area_no',$id)->first();
    	return view('admin.area.edit-area',compact('get_city','area'));
    }


  // update area

    public function UpdateArea(Request $request,$id){
    	$messages = [
		    'city_no.required' => 'Please select a city',
		  ];

    	$validatedData = $request->validate([
	        'area_name' => 'required',
	        'area_slug' => 'required'
	    ],$messages);
	    $data =array();
	    $data['city_no']= $request->city_no;
			$data['area_name']= $request->area_name;
			$data['area_slug']= $request->area_slug;
			$data['created_by']= Auth::guard('admin')->user()->admin_no;
			$data['created_at']= Carbon::now()->toDatetimeString();

		$update_area =DB::table('areas')->where('area_no',$id)->update($data);
		if($update_area){
	        $notification=array(
	            'messege'=>'Area updated successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('area.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Area update failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }
}
