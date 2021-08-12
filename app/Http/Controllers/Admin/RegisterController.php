<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Image;
use Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:admins',
            'user_role' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdminRegisterForm()
    {
        return view('admin.admin-reg');
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createAdmin(Request $request)
    {
        $this->validator($request->all())->validate();
        $admin = new Admin;
        $admin->admin_name = $request->admin_name;
        $admin->admin_email = $request->admin_email;
        $admin->admin_phone = $request->admin_phone;
        $admin->admin_address = $request->admin_address;
        $admin->user_role = $request->user_role;
        $admin->password = Hash::make($request->password);

        if($request->hasFile('admin_photo')){
            $image=$request->file('admin_photo');
            $random = mt_rand(1000000, 9999999);
            $imageName='img_'.$random.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(100,150)->save('public/uploads/'.$imageName);
            $admin['admin_photo'] = $imageName;
        }else{
            $admin['admin_photo']= "default-user.png";
        }

        /*Admin::create([
            'admin_name' => $request->admin_name,
            'admin_email' => $request->admin_email,
            'admin_phone' => $request->admin_phone,
            'admin_address' => $request->admin_address,
            'role_type' => $request->role_type,
            'password' => Hash::make($request->password),
        ]);*/
        $insert=$admin->save();
        if($insert){
            $notification=array(
                'messege'=>'Admin User Created successfully',
                'alert-type'=>'success'
            );
            return Redirect()->route('admin.all')->with($notification);
         }else{
            $notification=array(
                'messege'=>'Admin User Creation failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }

    protected function getAllAdmins(){
        $get_admin = DB::table('admins')->get();
        return view('admin.all-admin',compact('get_admin'));
    }


    //inactivate admin
    public function inactivateAdmin($id){
        $admin = Admin::findOrFail($id);
        $admin->is_active = 0;
        $inactivate=$admin->save();
        if($inactivate){
            $notification=array(
                'messege'=>'Inactivated successfully',
                'alert-type'=>'success'
            );
            return Redirect()->route('admin.all')->with($notification);
         }else{
            $notification=array(
                'messege'=>'Inactivate failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }


    //activate admin
    public function ActivateAdmin($id){
        $admin = Admin::findOrFail($id);
        $admin->is_active = 1;
        $inactivate=$admin->save();
        if($inactivate){
            $notification=array(
                'messege'=>'Activated successfully',
                'alert-type'=>'success'
            );
            return Redirect()->route('admin.all')->with($notification);
         }else{
            $notification=array(
                'messege'=>'Activate failed',
                'alert-type'=>'error'
            );
            return Redirect()->back()->with($notification);
         }
    }
}