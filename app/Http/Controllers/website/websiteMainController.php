<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class websiteMainController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }

    public function index()
    {	
    	$get_cat = DB::table('categories')
                 ->where('categories.is_deleted',0)
                 ->get();
    	$get_subCat = DB::table('subcategories')
                 ->where('subcategories.is_deleted',0)
                 ->get();
      $get_product = DB::table('products')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.user_no','users.user_no')
                  ->select('products.*','units.unit_name','users.name','users.company_name')
                 ->where([
                    ['products.is_deleted',0],
                    ['products.is_approved',1]
                  ])
                 ->orderBy('products.product_no', 'DESC')
                 ->take(8)
                 ->get();
        return view('website.index',compact('get_cat','get_subCat','get_product'));
    }


    //all services
    public function allService(){
      $count_product = DB::table('products')->where('products.is_deleted',0)->where('is_approved',1)->count();
      $get_subCat = DB::table('subcategories')
                 ->where('subcategories.is_deleted',0)
                 ->get();

      $get_product = DB::table('products')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.user_no','users.user_no')
                  ->select('products.*','units.unit_name','users.name','users.company_name')
                 ->where([
                    ['products.is_deleted',0],
                    ['products.is_approved',1]
                  ])
                 ->orderBy('products.product_no', 'DESC')
                 ->get();

        return view('website.shop',compact('get_product','count_product','get_subCat'));
    }



    //category & subcategory wise service
   	public function service($slug,$id){
      $scate_id=DB::table('categories')->where('category_no',$id)->where('category_slug',$slug)->first();
      $subcate_id=DB::table('subcategories')->where('subcategory_no',$id)->where('sub_category_slug',$slug)->first();
      $get_subCat = DB::table('subcategories')
                 ->where('subcategories.is_deleted',0)
                 ->get();
      if( $scate_id){
        $count_product = DB::table('products')->where('products.is_deleted',0)->where('category_no',$scate_id->category_no)->count();
        $get_product = DB::table('products')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.user_no','users.user_no')
                  ->select('products.*','units.unit_name','users.name','users.company_name')
                 ->where([
                    ['products.is_deleted',0],
                    ['products.is_approved',1],
                    ['products.category_no',$scate_id->category_no]
                  ])
                 ->orderBy('products.product_no', 'DESC')
                 ->get();

        return view('website.shop',compact('get_product','count_product','get_subCat'));
     }else{
       $count_product = DB::table('products')->where('products.is_deleted',0)->where('subcategory_no',$subcate_id->subcategory_no)->count();
       
       $get_product = DB::table('products')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.user_no','users.user_no')
                  ->select('products.*','units.unit_name','users.name','users.company_name')
                 ->where([
                    ['products.is_deleted',0],
                    ['products.is_approved',1],
                    ['products.subcategory_no',$subcate_id->subcategory_no]
                  ])
                 ->orderBy('products.product_no', 'DESC')
                 ->get();

      return view('website.shop',compact('get_product','count_product','get_subCat'));
     }
      
   	}

    //service details
    public function serviceDlt($slug,$id){
      $product_dlt = DB::table('products')
                  ->leftJoin('categories','products.category_no','categories.category_no')
                  ->leftJoin('subcategories','products.subcategory_no','subcategories.subcategory_no')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.user_no','users.user_no')
                  ->select('products.*','categories.category_name','categories.category_no','categories.category_slug','subcategories.sub_category_name','subcategories.subcategory_no','subcategories.sub_category_slug','units.unit_name','users.name','users.company_name')
                 ->where([
                    ['products.is_deleted',0],
                    ['products.is_approved',1],
                    ['products.product_no',$id]
                  ])
                 ->first();
      return view('website.product-details',compact('product_dlt'));
    }

}
