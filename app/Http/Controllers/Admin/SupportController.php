<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function supportList(){
    	$get_support = DB::table('support')
						    	->leftJoin('users','support.user_no','users.user_no')
						    	->select('support.*','users.name','users.phone','users.profile_picture')
						    	->where('support.is_deleted',0)
						    	->orderBy('support.support_no','DESC')
						    	->get();
			
	  	return view('admin.support.support-list',compact('get_support'));
	  }

	  //single support
	  public function supportForm($support_title,$support_no){
	  	$support_title = $support_title;
	  	$support_no = $support_no;
	  	return view('admin.support.support',compact('support_title','support_no'));
	  }


	  public function getAdminSuports(Request $request){
	  	$data = array();
	  	$support_no = $request->support_no;
			$data['support_admin_no']= Auth::guard('admin')->user()->admin_no;
	  	$update_support = DB::table('support')->where('support_no',$request->$support_no)->update($data);
			
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
			return response()->json($html);
	  }


	  //save suppoort

	  public function savedminSupportTxt(Request $request){
	  	$data=array();
	  	$data['support_no'] = $request->support_no;
	  	$data['support_text'] = $request->support_text;
	  	$data['sender_type'] = 2;
	  	$data['created_at']= Carbon::now()->toDatetimeString();
	  	$insert=DB::table('support_dtl')->insert($data);
	  	return response()->json($insert);
	  }
}
