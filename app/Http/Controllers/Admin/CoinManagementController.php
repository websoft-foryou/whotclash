<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class CoinManagementController extends Controller
{
    public function __construct()
    {
        Auth::user();
    }

    public function index()
    {
        $data['page_title'] = 'Coin Management';
        $data['page_link_string'] = ['Coin management'=>''];

        $user_data = DB::table('users')->where(['status'=> 1, 'remove'=>0])->orderBy('id')->get();
        $data['user_data'] = $user_data;

        return view('admin.pages.coin_management')->with($data);
    }

    public function add_coins(Request $request)
    {
        $user_id = $request->data_id;

        DB::table('add_coin_logs')->insert(['user_id'=>$user_id, 'coins'=>$request->coins, 'created_at'=>date('Y-m-d H:i:s')]);
        $result = DB::update('UPDATE users SET coins = coins + :coins WHERE id=:user_id', ['coins'=>$request->coins, 'user_id'=>$user_id]);
        if ($result)
            return redirect()->back()->with('success', 'Coins added successfully');
        else
            return redirect()->back()->with('error', 'Coins adding is failure. Please try again');
    }

    public function coin_history(Request $request)
    {
        $data['page_title'] = 'Coin Management';
        $data['page_link_string'] = ['Coin management'=>'', 'User Coin History'=>''];

        $user_id = $request->data_id;
        $user_data = DB::table('users')->where('id', $user_id)->first();
        $data['user_name'] = $user_data->name;

        $coin_data = DB::select("
            SELECT coin_date, in_coins, in_type, out_coins, in_cash, out_cash FROM (
                SELECT created_at coin_date, coins in_coins, 'admin' in_type, 0 out_coins, 0 in_cash, 0 out_cash FROM add_coin_logs WHERE user_id='$user_id'
                UNION ALL
                SELECT updated_at coin_date, coins in_coins, 'self' in_type, 0 out_coins, 0 in_cash, 0 out_cash FROM transactions WHERE user_id='$user_id' AND status='Approved'
                UNION ALL
                SELECT created_at coin_date, 0 in_coins, '' in_type, bet_amount out_coins, 0 in_cash, 0 out_cash FROM play_games WHERE user_first='$user_id' OR user_second='$user_id' OR user_third='$user_id' OR user_fourth='$user_id'
                UNION ALL
                SELECT updated_at coin_date, 0 in_coins, '' in_type, 0 out_coins, win_amount in_cash, 0 out_cash FROM play_games WHERE winner_user='$user_id'
                UNION ALL
                SELECT updated_at coin_date, 0 in_coins, '' in_type, 0 out_coins, 0 in_cash, coins out_cash FROM withdraw_coins WHERE user_id='$user_id'
            ) T ORDER BY coin_date
        ");

        $data['coin_data'] = $coin_data;
        return view('admin.pages.coin_history')->with($data);
    }
}
