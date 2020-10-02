<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Auth;
use DB;
use Illuminate\Support\MessageBag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return view('home');
        //$admin = Auth::user();
    }

    public function open_login_page(Request $request)
    {
        if(Auth::guard("admin")->check()) {
            return redirect("admin/index");
        }

        return view("admin/pages/login");
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $password = $request->password;

        $admin_user = DB::table('admins')->where(['email'=>$email])->first();
//        if (Auth::attempt(['email' => $email, 'password' => $password])) {
        if (empty($admin_user) || ! Hash::check($password, $admin_user->password)) {
            return back()->with('error','The email or password is wrong');
        }
        else {
            Auth::guard("admin")->loginUsingId($admin_user->id);
            return redirect('admin/index');
        }

    }

    public function logout(Request $request)
    {
        session()->flush();
        session()->regenerate();
        return redirect(route('admin-login'))->with("success","Logged out successfully");
    }
}
