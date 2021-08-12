<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    //pending orders

    public function getPendingSeller(){
    	$pending_seller = DB::table('users')
                         ->where([
                            ['users.user_type',2],
                            ['users.is_approved',0],
                          ])
                        ->get();
        return view('admin.seller.pending-seller',compact('pending_seller'));
    }

    
    //approve sellers
    public function approveSeller($id){
        
        $data =array();
	    $data['is_approved'] = 1;
        $approve = DB::table('users')->where('user_no',$id)->update($data);


        if($approve){
            $notification=array(
                'messege'=>'Seller approved successfully',
                'alert-type'=>'success'
            );
            return Redirect()->route('pendingSeller')->with($notification);
         }else{
            $notification=array(
                'messege'=>'Seller approval failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    	
    }


//get approved sellers
    public function getApprovedSeller(){
        $approved_seller = DB::table('users')
                         ->where([
                            ['users.user_type',2],
                            ['users.is_approved',1],
                          ])
                        ->get();
        return view('admin.seller.approved-seller',compact('approved_seller'));
    }


    //inactivate vendor
    public function inactivateVendor($id){
        $data = User::findOrFail($id);
        $data->is_active = 0;
        $inactivate=$data->save();
        if($inactivate){
            $notification=array(
                'messege'=>'Inactivated successfully',
                'alert-type'=>'success'
            );
            return Redirect()->back()->with($notification);
         }else{
            $notification=array(
                'messege'=>'Inactivate failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }


    //activate vendor
    public function ActivateVendor($id){
        $data = User::findOrFail($id);
        $data->is_active = 1;
        $inactivate=$data->save();
        if($inactivate){
            $notification=array(
                'messege'=>'Activated successfully',
                'alert-type'=>'success'
            );
            return Redirect()->back()->with($notification);
         }else{
            $notification=array(
                'messege'=>'Activate failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }
}
