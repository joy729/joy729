<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class SupportController extends Controller
{
    public function __construct(){
	  	$this->middleware('auth:web');
	  }

	  public function support(){
	  	return view('website.support');
	  }

	  public function getAllSuports(Request $request){
	  	$count_support = DB::table("support")
							        ->where([
							            ['user_no',$request->user_no],
							            ['is_deleted',0],
							        ])
							        ->count();
			if ($count_support<1) {
				$data=array();
				$data['user_no'] = $request->user_no;
				$data['support_title'] = Auth::guard('web')->user()->name ." ". "-support-"." ".date("m.d.Y");
				$data['created_at']= Carbon::now()->toDatetimeString();
		 		$insert=DB::table('support')->insert($data);	
			}else{
				$get_support = DB::table("support")
							        ->where([
							            ['user_no',$request->user_no],
							            ['is_deleted',0],
							        ])
							        ->first();
				$support_no = $get_support->support_no;
				$get_all_supports = DB::table("support_dtl")
							        ->where([
							            ['support_dtl.support_no',$support_no],
							            ['support_dtl.is_deleted',0]
							        ])
							        ->get();
				
				$html = "";
				$html .="<input type='hidden' id='support_no' value='".$support_no."'>";
				foreach($get_all_supports as $txt){
					if ($txt->sender_type==1) {
						$html .="<div class='receiver'><p class='receiver-text'>".$txt->support_text . " <br> <span clas='support-date'>". $txt->created_at . " </span></p></div>";
					}elseif ($txt->sender_type==2) {
						$html .="<div class='sender'><p class='sender-text'>".$txt->support_text . " <br> <span clas='support-date'>". $txt->created_at . " </span></p></div>";
					}

				}
				
			}
			return response()->json($html);
	  }


	  //save suppoort

	  public function saveSupportTxt(Request $request){
	  	$data=array();
	  	$data['support_no'] = $request->support_no;
	  	$data['support_text'] = $request->support_text;
	  	$data['sender_type'] = 1;
	  	$data['created_at']= Carbon::now()->toDatetimeString();
	  	$insert=DB::table('support_dtl')->insert($data);
	  	return response()->json($insert);
	  }
}
