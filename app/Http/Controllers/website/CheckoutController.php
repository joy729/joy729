<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{
  public function __construct(){
  	$this->middleware('auth:web');
  }

	public function showCheckout(){
		$user_no= Auth::guard('web')->user()->user_no;
		$user_info = DB::table('users')
                 ->where('user_no',$user_no)
                 ->first();

		$get_cities = DB::table('cities')
                 ->where('is_deleted',0)
                 ->get();
    $shipping_charge = DB::table('gen_shipping_charge')->first();
		return view ('website.checkout',compact('get_cities','user_info','shipping_charge'));
	}

	public function getAreas(Request $request){
		$areas = DB::table("areas")
		        ->where([
		            ['city_no',$request->city_no],
		            ['is_deleted',0],
		        ])
		        ->pluck("area_name","area_no");
        return response()->json($areas);
	}



	//save order

	public function placeOrder(Request $request){
		$user_no= Auth::guard('web')->user()->user_no;

		$data =array();
		$order_id ="";
    $count = DB::table('order_master')->count();
    if ($count <=0) {
      $data['order_id'] ='ss-'.'100001';
    }else{
      $last_row = DB::table('order_master')->latest('order_master_no')->first();
      $order_num = $last_row->order_id;
      $order_nums = explode("-", $order_num);
      $data['order_id'] =$order_nums[0] ."-".($order_nums[1]+1);
    }

		$data['city_no'] = $request->city_no;
		$data['area_no'] = $request->area_no;
		$data['order_address'] = $request->order_address;
		$data['payment_type'] = $request->payment_type;
		$data['orderAmount'] = $request->orderAmount;
		$data['delivery_charge'] = $request->delivery_charge;
		$data['total_amount'] = $request->totalAmount;
		$data['paid_amount'] = $request->paid_amount;
		$data['due_amount'] = $request->due_amount;
		$data['transaction_id'] = $request->transaction_id;
		$data['created_at']= Carbon::now()->toDatetimeString();
		$data['user_no']= $user_no;

		$order_master_no = DB::table('order_master')->insertGetId($data);

		$item_title_list = $request->item_title_list;
		$item_title_list = explode(",",$item_title_list);

		$item_qtn_list = $request->item_qtn_list;
		$item_qtn_list = explode(",",$item_qtn_list);

		$item_rate_list = $request->item_rate_list;
		$item_rate_list = explode(",",$item_rate_list);

		$item_price_list = $request->item_price_list;
		$item_price_list = explode(",",$item_price_list);

		$item_product_no_list = $request->item_product_no_list;
		$item_product_no_list = explode(",",$item_product_no_list);

		$row_count = count($item_product_no_list);

		$data2=array();
		for ($i=0;$i<$row_count;$i++) {

			$data2['product_no'] = $item_product_no_list[$i];
			$data2['product_title'] = $item_title_list[$i];
			$data2['item_rate'] = $item_rate_list[$i];
			$data2['order_qtn'] = $item_qtn_list[$i];
			$data2['item_price'] = $item_price_list[$i];
			$data2['order_master_no'] = $order_master_no;
			$data2['created_at']= Carbon::now()->toDatetimeString();
			$product_info = DB::table('products')
                 ->where('product_no',$item_product_no_list[$i])
                 ->first();
      $vendor_no = $product_info->user_no;

      $count_earining_row = DB::table('earning_master')->where('vendor_no',$vendor_no)->count();
      $get_earning_master = DB::table('earning_master')->where('vendor_no',$vendor_no)->first();

      if ($count_earining_row >=1) {
        $data3 =array();
        $data3['total_amount']=$get_earning_master->total_amount + $item_price_list[$i];
        $data3['total_paid_amount']=$get_earning_master->total_paid_amount;
        $data3['total_due_amount']= $get_earning_master->total_due_amount + $item_price_list[$i];
        $data3['updated_at']= Carbon::now()->toDatetimeString();
        $update_earning_master =DB::table('earning_master')->where('vendor_no',$vendor_no)->update($data3);
      }else{
      	$data4 =array();
        $data4['vendor_no']= $vendor_no;
        $data4['total_amount']= $item_price_list[$i];
        $data4['total_paid_amount']= 0;
        $data4['total_due_amount']= $item_price_list[$i];
        $data4['created_at']= Carbon::now()->toDatetimeString();
        $add_earning =DB::table('earning_master')->insert($data4);
      }

      $data2['vendor_no'] = $vendor_no;
		 	$insert=DB::table('order_details')->insert($data2);	
		}

		return $order_master_no;
	}


	//success order

	public function successOrder(){
		$order_dlt = DB::table('order_master')
              ->leftJoin('cities','order_master.city_no','cities.city_no')
              ->leftJoin('areas','order_master.area_no','areas.area_no')
              ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
              ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
							->latest('order_master_no')
							->first();
		return view('website.order-success',compact('order_dlt'));
	}
}
