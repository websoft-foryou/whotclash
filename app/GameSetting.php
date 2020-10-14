<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    protected $table = "game_setting";
    Protected $fillable = ["key", "value"];
}
