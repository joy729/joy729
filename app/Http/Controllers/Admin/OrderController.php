<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
class OrderController extends Controller
{
    public function __construct(){
    	$this->middleware('auth:admin');
    }

    //order details
    public function orderDetails($id){
      $order_dlt = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                  ->where([
                    ['order_master.order_master_no',$id]
                  ])
                ->first();
        return view('admin.order.order-details',compact('order_dlt'));
    }

    //pending orders

    public function pendingOrders(){
    	$pending_order = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.order_status_no',1],
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.pending-orders',compact('pending_order'));
    }

    //processing orders

    public function processingOrders(){
    	$pending_order = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.order_status_no',3],
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.processing-orders',compact('pending_order'));
    }

    //delivered orders

    public function completedOrders(){
    	$completedOrder = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.order_status_no',5],
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.completed-orders',compact('completedOrder'));
    }

    //Approved orders

    public function adminApprovedOrders(){
    	$approve_order = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.order_status_no',2],
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.approved-orders',compact('approve_order'));
    }


    //Shipping orders

    public function shippingOrders(){
    	$shipping_order = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.order_status_no',4],
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.shipping-orders',compact('shipping_order'));
    }


    //Deleted orders

    public function adminDeletedOrders(){
      $deleted_order = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.is_deleted',1]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.order.deleted-orders',compact('deleted_order'));
    }

	 	//delete order
	    public function deleteOrder($id){
	    	$data =array();
			  $data['is_deleted']= 1;
        $decline = DB::table('order_master')->where('order_master_no',$id)->update($data);

        if($decline){
	            $notification=array(
	                'messege'=>'Order deleted successfully',
	                'alert-type'=>'success'
	            );
	            return Redirect()->route('order.pending')->with($notification);
	         }else{
	            $notification=array(
	                'messege'=>'Order delete failed',
	                'alert-type'=>'error'
	            );
	            return Redirect()->back()->with($notification);
	         }
	    }  	


	    //show order status form

	    public function orderStatusForm($id){
	    	$order_status = DB::table('order_stauts')->get();
	    	$get_order = DB::table('order_master')->where('order_master_no',$id)->first();
	    	return view('admin.order.change-status',compact('order_status','get_order'));
	    }


	    //change order status

    public function updateOrderStatus(Request $request,$id){
    	$data =array();
	    $data['order_status_no']= $request->order_status_no;
		
      $update_status =DB::table('order_master')->where('order_master_no',$id)->update($data);

		  if($update_status){
	        $notification=array(
	            'messege'=>'Order status updated successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('dashboard')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Order status update failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }


    //show assign sp page

    public function assignSpForm($id){
    	$count = DB::table('cancelded_sp_orders')->count();
    	if ($count >0) {
    		$cancled_sp = DB::table('cancelded_sp_orders')->where('canceled_order_no',$id)->get();
	    	foreach ($cancled_sp as $object){
			   $canceled_user = $object->user_no;
	    	}
    	}else{
    		$canceled_user = "";
    	}
    	
    	$get_sp = DB::table('service_requests')
                  ->leftJoin('cat_wise_users','service_requests.category_no','cat_wise_users.category_no')
                  ->leftJoin('users','cat_wise_users.user_no','users.user_no')
                  ->select('users.name','users.phone','users.user_no')
                  ->where([
                    ['service_requests.canceled_by_customer',0],
                    ['service_requests.order_status_no',1],
                    ['cat_wise_users.is_available',1],
                    ['users.user_type',1],
                    ['cat_wise_users.user_no','!=',$canceled_user]
                  ])
                ->get();
    	$get_order = DB::table('service_requests')->where('service_request_no',$id)->first();
	    return view('admin.order.assign-sp',compact('get_order','get_sp'));
    }


     //assign service providers

    public function assignSP(Request $request,$id){
    	$data =array();
	    $data['assigned_sp_no']= $request->assigned_sp_no;
		  $data['admin_note']= $request->admin_note;
      $data['is_assigned'] = 1;
		  $update_status =DB::table('service_requests')->where('service_request_no',$id)->update($data);

		$data2 =array();
	    $data2['is_available']= 0;
	    $update_status =DB::table('cat_wise_users')->where('user_no',$request->assigned_sp_no)->update($data2);


		if($update_status){
	        $notification=array(
	            'messege'=>'Service provider assigned successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('order.pending')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Service provider assign failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }
}
