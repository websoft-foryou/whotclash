<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use Auth;
use DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        Auth::user();
    }

    public function open_change_password_page(Request $request)
    {
        $data['page_title'] = 'Change Password';
        $data['page_link_string'] = ['Change password'=>''];

        return view('admin.pages.change_password')->with($data);
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|max:30|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user_data = DB::table('users')->where('email', 'admin@gmail.com')->first();
        if (empty($user_data))
            return back()->with('error','The admin user is not exist. Admin email address have to adminn@gmail.com');
        else {
            if (! Hash::check($request->current_password, $user_data->password))
                return back()->with('error','The current password is not correct!');
            else {
                DB::table('users')->where('email', 'admin@gmail.com')->update(['password'=>Hash::make($request->new_password)]);
                return back()->with('success','Saved successfully!');
            }
        }

    }
}
