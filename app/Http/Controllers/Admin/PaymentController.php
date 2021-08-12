<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showPaymentForm()
    {
    	$getSP = DB::table('earning_master')
  					->leftJoin('users','earning_master.vendor_no','users.user_no')
                ->select('earning_master.*','users.user_no','users.name','users.phone')
                ->where([
                  ['earning_master.is_paid',0]
                ])
                ->get();
      return view('admin.payment.payment',compact('getSP'));
    }


    //insert payment

    public function createPayment(Request $request){
    	$messages = [
    		'vendor_no.required' => "Please select a service provider.",
		    'payment_amount.required' => "Paid Amount can't be empty."
		  ];
    	$validatedData = $request->validate([
    		'vendor_no' => 'required',
        'payment_amount' => 'required'
	    ], $messages);
	    $data =array();
			$data['vendor_no']= $request->vendor_no;
			$data['payment_amount']= $request->payment_amount;
			$data['transaction_id']= $request->transaction_id;
			$data['payment_type']= $request->payment_type;
			$data['paid_by']= Auth::guard('admin')->user()->admin_no;
			$data['created_at']= Carbon::now()->toDatetimeString();

			$data2 =array();
			$get_earning_master = DB::table('earning_master')->where('vendor_no',$request->vendor_no)->first();
			$data2['total_paid_amount']= $get_earning_master->total_paid_amount + $request->payment_amount;
			$data2['total_due_amount']=$get_earning_master->total_due_amount - $request->payment_amount;
				if ($get_earning_master->total_due_amount == $request->payment_amount) {
					$data2['is_paid']= 1;
				}
			$update_earning_master =DB::table('earning_master')->where('vendor_no',$request->vendor_no)->update($data2);

			$data['earning_master_no'] = $get_earning_master->earning_master_no;
			$insert=DB::table('payment_details')->insert($data);	

			if($insert){
	        $notification=array(
	            'messege'=>'Pyament completed successfully',
	            'alert-type'=>'success'
	        );
	        return Redirect()->route('payment.all')->with($notification);
	     }else{
	        $notification=array(
	            'messege'=>'Payment failed',
	            'alert-type'=>'error'
	        );
	        return Redirect()->back()->with($notification);
	     }
    }

    //all payments
    public function getAllPayments(){
    	$payments = DB::table('payment_details')
    						->leftJoin('users','payment_details.vendor_no','users.user_no')
					    	->leftJoin('admins','payment_details.paid_by','admins.admin_no')
					    	->select('payment_details.*','users.name','users.phone','admins.admin_name')
					    	->orderBy('payment_details.payment_no','DESC')
					    	->get();
    	return view('admin.payment.all-payment',compact('payments'));
    }
}
