<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function __construct(){
    	$this->middleware('auth:admin');
    }

    public function showAdminDashboard(){
    	$count_product = DB::table('products')->where('is_deleted',0)->count();
    	$pending_order = DB::table('order_master')
                      ->where([
                        ['order_status_no',1],
                        ['is_deleted',0]
                      ])
                      ->count();
    	$processing_order = DB::table('order_master')
                        ->where([
                          ['order_status_no',3],
                          ['is_deleted',0]
                        ])
                        ->count();
    	$completed_order = DB::table('order_master')
                        ->where([
                          ['order_status_no',5],
                          ['is_deleted',0]
                        ])
                        ->count();
    	$totalVendor = DB::table('users')
                    ->where([
                          ['is_approved',1],
                          ['is_active',1],
                          ['user_type',2]
                        ])
                    ->count();
    	$totalCustomer = DB::table('users')
                    ->where([
                          ['is_approved',1],
                          ['is_active',1],
                          ['user_type',1]
                        ])
                    ->count();
      $total_sale = DB::table('order_master')->where([
                        ['is_deleted',0]
                      ])->sum('total_amount');
      $total_due = DB::table('order_master')->where([
                        ['is_deleted',0]
                      ])->sum('due_amount');

    	$orderList = DB::table('order_master')
                 ->leftJoin('users','order_master.user_no','users.user_no')
                 ->select('order_master.*','users.name','users.phone')
                 ->where([
                    ['order_master.is_deleted',0],
                    ['order_master.order_status_no',1]
                  ])
                 ->orderBy('order_master.order_master_no', 'DESC')
                 ->take(8)
                 ->get();
    	return view('admin.dashboard',compact('count_product','pending_order','processing_order','completed_order','totalVendor','totalCustomer','orderList','total_sale','total_due'));
    }
}
