<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    //pending orders

    public function getPendingProduct(){
    	$pending_product = DB::table('products')
                  ->leftJoin('categories','products.category_no','categories.category_no')
                  ->leftJoin('subcategories','products.subcategory_no','subcategories.subcategory_no')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.created_by','users.user_no')
                  ->select('products.*','categories.category_name','subcategories.sub_category_name','units.unit_name','users.name','users.phone','users.company_name')
                  ->where([
                    ['products.is_approved',0],
                    ['products.is_deleted',0]
                  ])
                ->orderBy('products.product_no','DESC')
                ->get();
        return view('admin.product.pending-product',compact('pending_product'));
    }

    //approved orders

    public function getApprovedProduct(){
      $approved_product = DB::table('products')
                  ->leftJoin('categories','products.category_no','categories.category_no')
                  ->leftJoin('subcategories','products.subcategory_no','subcategories.subcategory_no')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.created_by','users.user_no')
                  ->select('products.*','categories.category_name','subcategories.sub_category_name','units.unit_name','users.name','users.phone','users.company_name')
                  ->where([
                    ['products.is_approved',1],
                    ['products.is_deleted',0]
                  ])
                  ->orderBy('products.product_no','DESC')
                ->get();
        return view('admin.product.approved-product',compact('approved_product'));
    }

    //deleted orders

    public function getDeletedProduct(){
      $deleted_product = DB::table('products')
                  ->leftJoin('categories','products.category_no','categories.category_no')
                  ->leftJoin('subcategories','products.subcategory_no','subcategories.subcategory_no')
                  ->leftJoin('units','products.unit_no','units.unit_no')
                  ->leftJoin('users','products.created_by','users.user_no')
                  ->select('products.*','categories.category_name','subcategories.sub_category_name','units.unit_name','users.name','users.phone','users.company_name')
                  ->where([
                    ['products.is_deleted',1]
                  ])
                  ->orderBy('products.product_no','DESC')
                ->get();
        return view('admin.product.deleted-product',compact('deleted_product'));
    }
    //approve products
    public function approveProduct($id){
        
        $data =array();
	    $data['is_approved'] = 1;
        $approve = DB::table('products')->where('product_no',$id)->update($data);


        if($approve){
            $notification=array(
                'messege'=>'Product approved successfully',
                'alert-type'=>'success'
            );
            return Redirect()->route('pendingProduct')->with($notification);
         }else{
            $notification=array(
                'messege'=>'Product approval failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    	
    }


    //delete product
    public function deleteProduct($id){
      $data =array();
      $data['is_deleted']= 1;

      $delete_product = DB::table('products')->where('product_no',$id)->update($data);
      if($delete_product){
        $notification=array(
              'messege'=>'Product deleted successfully',
              'alert-type'=>'success'
          );
          return Redirect()->back()->with($notification);
       }else{
          $notification=array(
              'messege'=>'Product delete failed',
              'alert-type'=>'error'
          );
          return Redirect()->back()->with($notification);
       }
    }
}
