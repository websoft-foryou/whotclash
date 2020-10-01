<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class UserManagementController extends Controller
{
    public function __construct()
    {
        Auth::user();
    }
    public function index()
    {
        $data['page_title'] = 'User Management';
        $data['page_link_string'] = ['User management'=>''];

        $user_data = DB::table('users')->where(['status'=> 1, 'remove'=>0])->orderBy('id')->get();
        $data['user_data'] = $user_data;

        return view('admin.pages.user_management')->with($data);
    }

    public function update(Request $request)
    {
        $user_id = $request->data_id;
        DB::table('users')->where('id', $user_id)->update(['name'=>$request->user_name, 'birthday'=>$request->birthday, 'gender'=>$request->gender, 'phone'=>$request->phone]);
        return redirect()->back()->with('success', 'Saved successfully');
    }

    public function block(Request $request)
    {
        $user_id = $request->data_id;
        DB::table('users')->where('id', $user_id)->update(['block'=> 1]);
        return redirect()->back()->with('success', 'Blocked successfully');
    }

    public function remove(Request $request)
    {
        $user_id = $request->data_id;
        DB::table('users')->where('id', $user_id)->update(['remove'=>1]);
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function game_history(Request $request)
    {
        $data['page_title'] = 'User Management';
        $data['page_link_string'] = ['User management'=>'', 'User game history'=>''];

        $user_id = $request->data_id;
        $game_data = DB::select("
            SELECT PG.*, U1.name user1, U1.id uid1, U2.name user2, U2.id uid2, U3.name user3, U3.id uid3, U4.name user4, U4.id uid4 FROM play_games PG
                LEFT JOIN users U1 ON PG.user_first=U1.id
                LEFT JOIN users U2 ON PG.user_second=U2.id
                LEFT JOIN users U3 ON PG.user_third=U3.id
                LEFT JOIN users U4 ON PG.user_fourth=U4.id
            WHERE winner_user IS NOT NULL AND (PG.user_first=:user_id1 OR PG.user_second=:user_id2 OR PG.user_third=:user_id3 OR PG.user_fourth=:user_id4)
        ", ['user_id1'=>$user_id, 'user_id2'=>$user_id, 'user_id3'=>$user_id, 'user_id4'=>$user_id]);
        $data['game_data'] = $game_data;

        return view('admin.pages.game_history')->with($data);
    }
}
