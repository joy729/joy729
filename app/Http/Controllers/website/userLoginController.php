<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Auth;
use User;
use DB;
use Image;
use Carbon\Carbon;

class userLoginController extends Controller
{
    use AuthenticatesUsers;

     public function __construct()
    {
        $this->middleware('guest:web')->except('logout', 'userLogout');
    }

    function showLoginForm(){
    	return view('website.login');
    }

    public function userLogin(Request $request)
    {
        
       
        if (Auth::guard('web')->attempt(['phone' => $request->phone, 'password' => $request->password, 'is_active' => 1,'is_approved' => 1])) {

            return redirect()->intended('/user/dashboard');
        }else{
            $notification=array(
                'messege'=>'লগইন সফল হয়নি। অনুগ্রহ করে সঠিক মোবাইল নাম্বার,পাসওয়ার্ড দিয়ে পুনরায় চেষ্টা করুন অথবা এডমিনের সাথে যোগাযোগ করুন।',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
        }
    }

    public function userLogout(Request $request){
        Auth::guard('web')->logout();
        return redirect()->intended('/');

    }


    //registration
    function showRegForm(){
        return view('website.register');
    }


    public function userRegistration(Request $request){
        $messages = [
            'phone.unique' => "এই মোবাইল নাম্বার দিয়ে ইতোমধ্যে একাউন্ট খোলা হয়েছে।"
          ];
        $validatedData = $request->validate([
            'phone' => 'unique:users',
        ], $messages);

        $data =array();
        $data['name']= $request->name;
        $data['phone']= $request->phone;
        $data['email']= $request->email;
        $data['user_type']= $request->user_type;
        $data['user_nid']= $request->user_nid;
        $data['company_name']= $request->company_name;
        $data['user_address']= $request->user_address;
        if ($request->user_type ==1) {
            $data['is_approved']= 1;
            $data['is_active'] =1;
        }
        $data['password']= Hash::make($request->password);
        $data['user_nid_photo']= "";
        $data['created_at']= Carbon::now()->toDatetimeString();
        if($request->hasFile('user_nid_photo')){
            $image=$request->file('user_nid_photo');
            $random = mt_rand(1000000, 9999999);
            $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->save('public/uploads/'.$imageName);
            $data['user_nid_photo'] = $imageName;
        }else{
            $data['user_nid_photo']= "default.png";
        }

        $insert=DB::table('users')->insert($data); 

        if($insert){
            $notification=array(
                'messege'=>'রেজিস্ট্রেশন সফল হয়েছে।',
                'alert-type'=>'success'
            );
            return Redirect()->back()->with($notification);
         }else{
            $notification=array(
                'messege'=>'Registration failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }
}
