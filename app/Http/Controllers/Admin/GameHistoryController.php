<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class GameHistoryController extends Controller
{
    public function __construct()
    {
        Auth::user();
    }

    public function index()
    {
        $data['page_title'] = 'Game History';
        $data['page_link_string'] = ['Game history'=>''];

        $game_data = DB::select("
            SELECT PG.*, U1.name user1, U1.id uid1, U2.name user2, U2.id uid2, U3.name user3, U3.id uid3, U4.name user4, U4.id uid4 FROM play_games PG
                LEFT JOIN users U1 ON PG.user_first=U1.id
                LEFT JOIN users U2 ON PG.user_second=U2.id
                LEFT JOIN users U3 ON PG.user_third=U3.id
                LEFT JOIN users U4 ON PG.user_fourth=U4.id
            WHERE winner_user IS NOT NULL
        ");
        $data['game_data'] = $game_data;

        return view('admin.pages.game_history')->with($data);
    }
}
