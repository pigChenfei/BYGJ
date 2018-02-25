<?php

namespace App\Models\PlayerNews;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\CarrierPlayerNews;
use App\Models\Player;


/**
 * Class PlayerNewsRelation
 * @package App\Models\PlayerNews
 * @version May 8, 2017, 5:58 pm CST
 */
class PlayerNewsRelation extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_player_news_relation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'player_id',
        'player_news_id',
        'player_view_status',
        'player_delete_status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
        'player_news_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function carrierPlayerNews(){
        return $this->belongsTo(CarrierPlayerNews::class,'player_news_id','id');
    }
    
    public function player(){
        return $this->belongsTo(Player::class,'player_id','player_id');
    }

}
