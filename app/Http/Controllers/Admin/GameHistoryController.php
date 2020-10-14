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

    public function play_history(Request $request)
    {
        $data['page_title'] = 'Game History';
        $data['page_link_string'] = ['Game history'=>'', 'Play history'=>''];

        if ($request->isMethod('GET')) {
            $play_data = DB::select("
                SELECT PG.*, U1.name user1, U1.id uid1, U2.name user2, U2.id uid2, U3.name user3, U3.id uid3, U4.name user4, U4.id uid4 FROM play_games PG
                    LEFT JOIN users U1 ON PG.user_first=U1.id
                    LEFT JOIN users U2 ON PG.user_second=U2.id
                    LEFT JOIN users U3 ON PG.user_third=U3.id
                    LEFT JOIN users U4 ON PG.user_fourth=U4.id
                ORDER BY PG.id DESC
            ");
            $data['play_data'] = $play_data;
        }

        if ($request->isMethod('POST')) {
            $date_from = $request->playdate_from;
            $date_to = $request->playdate_to;
            $play_data = DB::select("
                SELECT PG.*, U1.name user1, U1.id uid1, U2.name user2, U2.id uid2, U3.name user3, U3.id uid3, U4.name user4, U4.id uid4
                FROM (SELECT * FROM play_games WHERE updated_at BETWEEN '$date_from' AND '$date_to') PG
                    LEFT JOIN users U1 ON PG.user_first=U1.id
                    LEFT JOIN users U2 ON PG.user_second=U2.id
                    LEFT JOIN users U3 ON PG.user_third=U3.id
                    LEFT JOIN users U4 ON PG.user_fourth=U4.id
                ORDER BY PG.id DESC
            ");
            $data['playdate_from'] = $date_from;
            $data['playdate_to'] = $date_to;
            $data['play_data'] = $play_data;
        }




        $transaction_data = DB::select("
            SELECT transaction_date, SUM(in_coins) in_coins, SUM(out_coins) out_coins, SUM(in_cash) in_cash, SUM(out_cash) out_cash FROM (
                SELECT created_at transaction_date, coins in_coins, 0 out_coins, 0 in_cash, 0 out_cash FROM add_coin_logs
                UNION ALL
                SELECT updated_at transaction_date, coins in_coins, 0 out_coins, 0 in_cash, 0 out_cash FROM transactions WHERE status='Approved'
                UNION ALL
                SELECT created_at transaction_date, 0 in_coins, bet_amount out_coins, 0 in_cash, 0 out_cash FROM play_games
                UNION ALL
                SELECT updated_at transaction_date, 0 in_coins, 0 out_coins, win_amount in_cash, 0 out_cash FROM play_games
                UNION ALL
                SELECT updated_at transaction_date, 0 in_coins, 0 out_coins, 0 in_cash, coins out_cash FROM withdraw_coins
            ) T GROUP BY LEFT(transaction_date, 10) ORDER BY transaction_date
        ");

        $data['transaction_date_from'] = '';
        $data['transaction_date_to'] = '';
        $data['transaction_data'] = $transaction_data;


        return view('admin.pages.play_history')->with($data);
    }

    public function view_history(Request $request)
    {
        $data['page_title'] = 'Game History';
        $data['page_link_string'] = ['Game history'=>''];

        $date_from = $request->playdate_from == '' ? '2000-01-01' : $request->playdate_from;
        $date_to = $request->playdate_to == '' ? date('Y-m-d') : $request->playdate_to;

        $game_data = DB::select("
            SELECT PG.*, U1.name user1, U1.id uid1, U2.name user2, U2.id uid2, U3.name user3, U3.id uid3, U4.name user4, U4.id uid4
            FROM (SELECT * FROM play_games WHERE updated_at BETWEEN '$date_from' AND '$date_to') PG
                LEFT JOIN users U1 ON PG.user_first=U1.id
                LEFT JOIN users U2 ON PG.user_second=U2.id
                LEFT JOIN users U3 ON PG.user_third=U3.id
                LEFT JOIN users U4 ON PG.user_fourth=U4.id
            ORDER BY PG.id DESC
        ");
        $data['playdate_from'] = $request->playdate_from;
        $data['playdate_to'] = $request->playdate_to;
        $data['game_data'] = $game_data;

        $date_from = $request->transaction_date_from == '' ? '2000-01-01' : $request->transaction_date_from;
        $date_to = $request->transaction_date_to == '' ? date('Y-m-d') : $request->transaction_date_to;
        $transaction_data = DB::select("
            SELECT transaction_date, SUM(in_coins) in_coins, SUM(out_coins) out_coins, SUM(in_cash) in_cash, SUM(out_cash) out_cash FROM (
                SELECT created_at transaction_date, coins in_coins, 0 out_coins, 0 in_cash, 0 out_cash FROM add_coin_logs WHERE created_at BETWEEN '$date_from' AND '$date_to'
                UNION ALL
                SELECT updated_at transaction_date, coins in_coins, 0 out_coins, 0 in_cash, 0 out_cash FROM transactions WHERE status='Approved' AND updated_at BETWEEN '$date_from' AND '$date_to'
                UNION ALL
                SELECT created_at transaction_date, 0 in_coins, bet_amount out_coins, 0 in_cash, 0 out_cash FROM play_games WHERE created_at BETWEEN '$date_from' AND '$date_to'
                UNION ALL
                SELECT updated_at transaction_date, 0 in_coins, 0 out_coins, win_amount in_cash, 0 out_cash FROM play_games WHERE updated_at BETWEEN '$date_from' AND '$date_to'
                UNION ALL
                SELECT updated_at transaction_date, 0 in_coins, 0 out_coins, 0 in_cash, coins out_cash FROM withdraw_coins WHERE updated_at BETWEEN '$date_from' AND '$date_to'
            ) T GROUP BY LEFT(transaction_date, 10) ORDER BY transaction_date
        ");
        $data['transaction_date_from'] = $request->transaction_date_from;
        $data['transaction_date_to'] = $request->transaction_date_to;
        $data['transaction_data'] = $transaction_data;
        return view('admin.pages.game_history')->with($data);
    }


}
