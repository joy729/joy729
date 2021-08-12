<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ReportController extends Controller
{
    public function __construct(){
    	$this->middleware('auth:admin');
    }


    //order report

    public function orderReport(){
    	$orders = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->leftJoin('users','order_master.user_no','users.user_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status','users.name','users.phone')
                  ->where([
                    ['order_master.is_deleted',0]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
        return view('admin.report.order-report',compact('orders'));
    }

    
    //sp earning report
    public function vendorAccountReport(){
    	$spearning = DB::table('earning_master')
              ->leftJoin('users','earning_master.vendor_no','users.user_no')
                ->select('earning_master.*','users.name','users.phone')
                ->get();
      return view('admin.report.vendor-account-report',compact('spearning'));
    }


    //payments report
    public function paymentReport(){
    	$payments = DB::table('payment_details')
    						->leftJoin('users','payment_details.vendor_no','users.user_no')
					    	->leftJoin('admins','payment_details.paid_by','admins.admin_no')
					    	->select('payment_details.*','users.name','users.phone','admins.admin_name')
					    	->orderBy('payment_details.payment_no','DESC')
					    	->get();
    	return view('admin.report.payment-report',compact('payments'));
   }

   //due report
    public function dueReport(){
    	$due = DB::table('order_master')
              ->leftJoin('users','order_master.user_no','users.user_no')
                ->select('order_master.*','users.name','users.phone')
               ->where([
                  ['order_master.is_paid',0]
                ])
                ->get();
      return view('admin.report.due-report',compact('due'));
    }
}
