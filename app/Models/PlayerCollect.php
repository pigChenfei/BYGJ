<?php

namespace App\Models;

use App\Models\Map\CarrierGame;
use App\Scopes\PlayerScope;
use Illuminate\Database\Eloquent\Model;

class PlayerCollect extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new PlayerScope());
    }

    public $table = 'inf_player_collect';

    public $fillable = [
        'map_carrier_game_id',
        'player_id',
    ];

    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

    public function carrierGame(){
        return $this->belongsTo(CarrierGame::class,'map_carrier_game_id','id');
    }

}
