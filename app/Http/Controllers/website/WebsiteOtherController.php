<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WebsiteOtherController extends Controller
{
    //

	public function showCart(){
		$shipping_charge = DB::table('gen_shipping_charge')->first();
		return view ('website.cart',compact('shipping_charge'));
	}
}
