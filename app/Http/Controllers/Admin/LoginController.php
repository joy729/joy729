<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Admin;

class LoginController extends Controller
{
    use AuthenticatesUsers;

     public function __construct()
    {
        $this->middleware('guest:admin')->except('logout', 'logoutAdmin');
    }

    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'admin_email'   => 'required|email',
            'password' => 'required|min:6'
        ]);



        if (Auth::guard('admin')->attempt(['admin_email' => $request->admin_email, 'password' => $request->password, 'is_active' => 1,], $request->get('remember'))) {

            return redirect()->intended('/admin/dashboard');
        }
        return back()->withInput($request->only('admin_email', 'remember'));
    }

    public function logoutAdmin(Request $request){
        Auth::guard('admin')->logout();
        return redirect()->intended('/admin');

    }
}
