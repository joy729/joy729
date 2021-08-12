<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use Image;
use Carbon\Carbon;

class userDashboardController extends Controller
{	

	public function __construct(){
    	$this->middleware('auth:web');
    }

	//show dashboard
	public function userDashboard(){
    $user_no= Auth::guard('web')->user()->user_no;
    //vendor
    $count_vendor_new_order = DB::table('order_master')
                            ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                            ->distinct('order_details.order_master_no')
                            ->where([
                                ['order_master.order_status_no',1],
                                ['order_details.vendor_no',$user_no]
                              ])
                            ->count();

    $count_vendor_delivered_order = DB::table('order_master')
                            ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                            ->distinct('order_details.order_master_no')
                            ->where([
                                ['order_master.order_status_no',5],
                                ['order_details.vendor_no',$user_no]
                              ])
                            ->count();

    $count_vendor_product = DB::table('products')
                ->where([
                    ['is_deleted',0],
                    ['is_approved',1],
                    ['user_no',$user_no]
                  ])
              ->count();

    $count_vendor_total_order = DB::table('order_master')
                            ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                            ->distinct('order_details.order_master_no')
                            ->where([
                                ['order_details.vendor_no',$user_no]
                              ])
                            ->count();

    $vendor_order_list = DB::table('order_master')
                            ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                            ->leftJoin('cities','order_master.city_no','cities.city_no')
                            ->leftJoin('areas','order_master.area_no','areas.area_no')
                            ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                            ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                            ->distinct('order_details.order_master_no')
                            ->where([
                                ['order_details.vendor_no',$user_no]
                              ])
                            ->orderBy('order_master.order_master_no', 'DESC')
                            ->get();
    
    //user
    //order_status_no : (pending=1,approved= 2,processing =2,shipping =3,delivered=4,cancel=5)
      $user_c_pend_order = DB::table('order_master')
                ->where([
                    ['order_status_no',1],
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_c_proc_order = DB::table('order_master')
                ->where([
                    ['order_status_no',3],
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_c_comp_order = DB::table('order_master')
                ->where([
                    ['order_status_no',5],
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_c_canc_order = DB::table('order_master')
                ->where([
                    ['order_status_no',6],
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_total_order = DB::table('order_master')
                ->where([
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_order_list = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                  ->where([
                    ['order_master.user_no',$user_no]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();
    	
		return view('website.dashboard.dashboard',compact('count_vendor_new_order','count_vendor_delivered_order','count_vendor_product','count_vendor_total_order','vendor_order_list','user_c_pend_order','user_c_proc_order','user_c_comp_order','user_c_canc_order','user_total_order','user_order_list'));
	}
	

	
  // user order details

  public function myOrderDlt($order_id,$id){
    $order_dlt = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                  ->where([
                    ['order_master.order_master_no',$id]
                  ])
                ->first();
    return view('website.dashboard.order-details',compact('order_dlt'));
  }

  //vendor all orders

  public function vendorAllProducts(){
    $user_no= Auth::guard('web')->user()->user_no;
    $count_all_products = DB::table('products')
                  ->where([
                    ['products.is_deleted',0],
                    ['products.user_no',$user_no]
                  ])
                ->count();
    $all_products = DB::table('products')
                  ->leftJoin('categories','products.category_no','categories.category_no')
                  ->leftJoin('subcategories','products.subcategory_no','subcategories.subcategory_no')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->select('products.*','categories.category_name','subcategories.sub_category_name','units.unit_name')
                  ->where([
                    ['products.is_deleted',0],
                    ['products.user_no',$user_no]
                  ])
                  ->orderBy('products.product_no', 'DESC')
                ->get();
        return view('website.dashboard.vendor-all-products',compact('count_all_products','all_products'));
  }

  //all orders for user & vendor

  public function myAllOrders(){
    $user_no= Auth::guard('web')->user()->user_no;

    $user_total_order = DB::table('order_master')
                ->where([
                    ['user_no',$user_no]
                  ])
              ->count();
      $user_order_list = DB::table('order_master')
                  ->leftJoin('cities','order_master.city_no','cities.city_no')
                  ->leftJoin('areas','order_master.area_no','areas.area_no')
                  ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                  ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                  ->where([
                    ['order_master.user_no',$user_no]
                  ])
                  ->orderBy('order_master.order_master_no', 'DESC')
                ->get();

    $count_vendor_total_order = DB::table('order_master')
                            ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                            ->distinct('order_details.order_master_no')
                            ->where([
                                ['order_details.vendor_no',$user_no]
                              ])
                            ->count();

    $vendor_order_list = DB::table('order_master')
                      ->leftJoin('order_details','order_master.order_master_no','order_details.order_master_no')
                      ->leftJoin('cities','order_master.city_no','cities.city_no')
                      ->leftJoin('areas','order_master.area_no','areas.area_no')
                      ->leftJoin('order_stauts','order_master.order_status_no','order_stauts.order_status_no')
                      ->select('order_master.*','cities.city_name','areas.area_name','order_stauts.order_status')
                      ->distinct('order_details.order_master_no')
                      ->where([
                          ['order_details.vendor_no',$user_no]
                        ])
                      ->orderBy('order_master.order_master_no', 'DESC')
                      ->get();
    return view('website.dashboard.my-all-orders',compact('user_total_order','user_order_list','count_vendor_total_order','vendor_order_list'));
  }

    //product upload
    public function productUpForm(){
      $get_Cat = DB::table('categories')
                 ->where('categories.is_deleted',0)
                 ->get();
      $get_subCat = DB::table('subcategories')
                 ->where('subcategories.is_deleted',0)
                 ->get();
      $get_unit = DB::table('units')->get();
      return view ('website.dashboard.product',compact('get_Cat','get_subCat','get_unit'));
    }

    public function productUpload(Request $request){
        $data =array();
        $data['product_title']= $request->product_title;
        $data['product_slug']= $request->product_slug;
        $data['category_no']= $request->category_no;
        $data['subcategory_no']= $request->subcategory_no;
        $data['unit_price']= $request->unit_price;
        $data['unit_no']= $request->unit_no;
        $data['unit_quantity']= $request->unit_quantity;
        $data['product_details']= $request->product_details;
        $data['product_additional_info']= $request->product_additional_info;
        $data['user_no']= Auth::guard('web')->user()->user_no;
        $data['created_by']= Auth::guard('web')->user()->user_no;
        $data['product_image']= "";
        $data['product_image2']= "";
        $data['created_at']= Carbon::now()->toDatetimeString();

        if($request->hasFile('product_image')){
          $image=$request->file('product_image');
          $random = mt_rand(1000000, 9999999);
              $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
              Image::make($image)->resize(600,600)->save('public/uploads/'.$imageName);
              $data['product_image'] = $imageName;
        }else{
          $data['product_image']= "no-product.png";
        }

        if($request->hasFile('product_image2')){
          $image=$request->file('product_image2');
          $random = mt_rand(1000000, 9999999);
              $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
              Image::make($image)->resize(600,600)->save('public/uploads/'.$imageName);
              $data['product_image2'] = $imageName;
        }else{
          $data['product_image2']= "no-product.png";
        }

        $insert=DB::table('products')->insert($data); 

        if($insert){
            $notification=array(
                'messege'=>'প্রোডাক্ট আপলোড সফল হয়েছে।',
                'alert-type'=>'success'
            );
            return Redirect()->back()->with($notification);
         }else{
            $notification=array(
                'messege'=>'প্রোডাক্ট ব্যর্থ হয়েছে।',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }
}
