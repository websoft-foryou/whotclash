<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayGame extends Model
{
    protected $table = "play_games";

    protected $fillable = [
    	"user_first","user_second","user_third","user_fourth","game_type","game_mode","bet_amount","winner_user","win_amount", "friend_mode"
  	];

    public function getUserFirstAttribute(){
    	return User::whereId($this->attributes["user_first"])->select("id","name","email")->first();
	}

	public function getUserSecondAttribute(){
    	return User::whereId($this->attributes["user_second"])->select("id","name","email")->first();
	}

	public function getUserThirdAttribute(){
    	return User::whereId($this->attributes["user_third"])->select("id","name","email")->first();
	}

	public function getUserFourthAttribute(){
    	return User::whereId($this->attributes["user_fourth"])->select("id","name","email")->first();
	}

    public function getWinnerUserAttribute(){
        return User::whereId($this->attributes["winner_user"])->select("id","name","email")->first();
    }
}
