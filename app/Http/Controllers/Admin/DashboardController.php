<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $admin = Auth::user();

        $data['page_title'] = 'Dashboard';
        $data['page_link_string'] = ['Dashboard'=>''];
        return view('admin.pages.dashboard')->with($data);
    }
}
