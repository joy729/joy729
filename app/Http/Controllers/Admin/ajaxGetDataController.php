<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class ajaxGetDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /*show cat wise subcat using ajax*/

    public function getAjaxSubcat(Request $request)
    {
        $subcats = DB::table("subcategories")
        ->where([
            ['category_no',$request->category_no],
            ['is_deleted',0],
        ])
        ->pluck("sub_category_name","subcategory_no");
        return response()->json($subcats);
    }


    /*get subcat wise service provider*/

    public function getAjaxSP(Request $request)
        {
            $users = DB::table("cat_wise_users")
                ->leftJoin('users','cat_wise_users.user_no','users.user_no')
                ->where("subcategory_no",$request->subcategory_no)
                ->pluck("users.name","users.user_no");
            return response()->json($users);
        }


    //get payment details
    public function getAjaxPaymentDtl(Request $request){
        $due_amount = DB::table("earning_master")
                ->where("vendor_no",$request->vendor_no)
                ->first();
        $data = $due_amount->total_due_amount;
            return $data;
    }
}
