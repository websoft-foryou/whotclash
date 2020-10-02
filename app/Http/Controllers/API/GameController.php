<?php

namespace App\Http\Controllers\API;

use App\User;
use Auth;
use Validator;
use App\BankDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\PlayGame;
use App\GameSetting;

use DB;

class GameController extends BaseController
{
   public function start_game(Request $request){
   		$Validator = Validator::make($request->all(),
   					[
   						"users_id" 		=> "required",
   						"game_type" 	=> "required|in:2_player,4_player",
   						"game_mode" 	=> "required|in:classic,elimination",
   						"bet_amount"	=> "required|integer"
   					]);

   		if($Validator->fails()){
   			return response()->json(["message" => $Validator->errors()->first()],400);
   		}

   		$insert_data = array();

   		$users = $request->get("users_id");
   		if($users){
   			$expl = explode(",",$users);
   			if($expl && count($expl) > 0 && !empty($expl[0])){

   				$name = ["first","second","third","fourth"];

   				foreach($expl as $key => $value) {
   					$insert_data["user_".$name[$key]] = $value;
   				}
   			}
   		}



   		$insert_data["game_type"] = $request->get("game_type");
   		$insert_data["game_mode"] = $request->get("game_mode");
   		$insert_data["bet_amount"] = $request->get("bet_amount");
   		$insert_data["friend_mode"] = ($request->get("friend_mode") == '' || $request->get("friend_mode") == null) ? 0 : $request->get("friend_mode");

   		$PlayGame = PlayGame::create($insert_data);

   		if($PlayGame){
   			return response()->json(["message" => "Game started successfully","data" => $PlayGame]);
   		}else {
   			return response()->json(["message" => "Unable to start game, please try after sometime"],400);
   		}
   	}

   	public function end_game(Request $request){
   		$Validator = Validator::make($request->all(),
   			[
   				"game_id" 		 => "required",
   				"winner"       => "required|integer"
   			]
   		);

   		if($Validator->fails()){
   			return response()->json(["message" => $Validator->errors()->first()],406);
   		}

   		$PlayGame = PlayGame::find($request->get("game_id"));
   		$PlayGame->winner_user = $request->get("winner");
       
      if ($request->get("winamount") != '' && $request->get("winamount") != null) {
        $PlayGame->win_amount = $request->get("winamount");
      }

   		if($PlayGame->save()){
   			return response()->json(["message" => "Game ended successfully"]);
   		}
     }
     
     public function get_game_setting() {
      $user = Auth::user();
      if(!empty($user)) {
          if($user->block == '1') {
            $this->responseError("Your account has been blocked by admin.",400);
          }
          $GameSettings = GameSetting::get();
          $res = array();
          foreach ($GameSettings as $key => $value) {
            array_push($res, array('key'=>$value->key, 'value'=>$value->value));
          }
          $this->responseSuccess('', $res, 'game_setting');
      }	
      else {
        $this->responseError('Un Authenticated.', 406);
      }
     }

   	public function game_history(Request $request){

   		$user_id = Auth::id();
      
       $game_data["total_game"] = PlayGame::where(function($query) use($user_id){
          $query->where("user_first",$user_id)
              ->Orwhere("user_second",$user_id)
              ->Orwhere("user_third",$user_id)
              ->Orwhere("user_fourth",$user_id);
          })
          ->selectRaw("count(*) as total_game")
          ->first()->total_game;

      $game_data["win_game"]  = PlayGame::where("winner_user",$user_id)
                          ->selectRaw("count(*) as win_game")
                          ->first()->win_game;

   		$get_history = PlayGame::where(function($query) use($user_id){
   			$query->where("user_first",$user_id)
   					->Orwhere("user_second",$user_id)
   					->Orwhere("user_third",$user_id)
   					->Orwhere("user_fourth",$user_id);
   		})
   		->select("play_games.*",DB::Raw("(CASE WHEN winner_user = ".$user_id." THEN 'YES' ELSE 'No' END) as is_winner,IFNULL(play_games.winner_user,0) as winner_user"))
   		->get()
      ->toArray();

      
     
       $new_data = array();
      if($get_history && !empty($get_history) && count($get_history)){
        foreach($get_history as $data){
          $semi_new_data = array();

          $semi_new_data["id"] = $data["id"];
          $semi_new_data["game_type"] = $data["game_type"];
          $semi_new_data["game_mode"] = $data["game_mode"];
         

          $unique_users = array();
          /*
          if($data["user_first"]['id'] !== $user_id && !empty($data["user_first"])){
            array_push($unique_users,$data['user_first']);
          }

          if($data["user_second"]['id'] !== $user_id && !empty($data["user_second"])){
            array_push($unique_users,$data['user_second']);
          }
            */
          //if($data["user_third"]['id'] !== $user_id && !empty($data["user_third"])){
           // array_push($unique_users,$data['user_third']);
          //}

         // if($data["user_fourth"]['id'] !== $user_id && !empty($data["user_fourth"])){
          //  array_push($unique_users,$data['user_fourth']);
          //}

          if(isset($unique_users[0])){
            $semi_new_data['user_first'] = $unique_users[0];
          }
          if(isset($unique_users[1])){
            $semi_new_data['user_second'] = $unique_users[1];
          }
          if(isset($unique_users[2])){
            $semi_new_data['user_third'] = $unique_users[2];
          }
          $semi_new_data["bet_amount"] = $data["bet_amount"];
          $semi_new_data["winner_user"] = $data["winner_user"];
          $semi_new_data["created_at"] = $data["created_at"];
          $semi_new_data["updated_at"] = $data["updated_at"];
          $semi_new_data["is_winner"] = $data["is_winner"];

          array_push($new_data, $semi_new_data);
        }
      }

   		return response()->json(["message" => "Game history get successfully","data" => $new_data,"game_data" => $game_data]);  

   	}

}

/*
1) Start Game
url/start_game (post)
->header token 
->input {
		users_id (comma separate 2 or 4)
		game_mode (classic,elimination)
		game_type (2_player,4_player)
		bet_amount(int)
	}

	result 

	{
    "message": "Game started successfully",
    "data": {
        "user_first": {
            "id": 20,
            "name": "John",
            "email": "john@yopmail.com"
        },
        "user_second": {
            "id": 21,
            "name": "Logic",
            "email": "logic@yopmail.com"
        },
        "user_third": {
            "id": 1,
            "name": "Inder Singh",
            "email": "inders@yopmail.com"
        },
        "user_fourth": {
            "id": 23,
            "name": "Aa",
            "email": "11@11.com"
        },
        "game_type": "4_player",
        "game_mode": "classic",
        "bet_amount": "30",
        "updated_at": "2019-09-10 12:59:25",
        "created_at": "2019-09-10 12:59:25",
        "id": 13
    }
}
}

2) End Game
url/end_game (post)
->header token 
->input {
	game_id,
	winner_user_id
}

Result -> message : game ended successfully

3) Game History
url/game_history (get)
->header token 
{
    {
    "message": "Game history get successfully",
    "data": [
        {
            "id": 13,
            "game_type": "4_player",
            "game_mode": "classic",
            "user_first": {
                "id": 20,
                "name": "John",
                "email": "john@yopmail.com"
            },
            "user_second": {
                "id": 21,
                "name": "Logic",
                "email": "logic@yopmail.com"
            },
            "user_third": {
                "id": 1,
                "name": "Inder Singh",
                "email": "inders@yopmail.com"
            },
            "user_fourth": {
                "id": 23,
                "name": "Aa",
                "email": "11@11.com"
            },
            "bet_amount": 30,
            "winner_user": 0,
            "created_at": "2019-09-10 12:59:25",
            "updated_at": "2019-09-10 12:59:25",
            "is_winner": "No"
        }
    ]
}
}

*/
