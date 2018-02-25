<?php

namespace App\Models\Map;

use App\Models\Carrier;
use App\Models\Def\Game;
use App\Models\Def\GamePlat;
use App\Models\Def\MainGamePlat;
use App\Models\PlayerCollect;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Map\CarrierGame
 *
 * @property int $id
 * @property int $carrier_id 所属运营商id
 * @property int $game_id 游戏主账户ID
 * @property string $display_name 游戏显示名称
 * @property int $sort 游戏排序
 * @property int $status 运营商分配的游戏开放状态 1 开放  0关闭
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\Def\Game $game
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Map\CarrierGame open()
 * @mixin \Eloquent
 */
class CarrierGame extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    const STATUS_AVAILABLE = 1;
    const STATUS_CLOSED    = 0;

    public $table = 'map_carrier_games';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_id',
        'game_id',
        'display_name',
        'sort',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'game_id' => 'integer',
        'display_name'=>'string',
        'sort' => 'integer',
        'status' => 'integer'
    ];


    public function isOpen(){
        if($this->game->carrierGamePlat->status == CarrierGamePlat::STATUS_CLOSED){
            return false;
        }
        return $this->status == self::STATUS_AVAILABLE;
    }

    public function scopeOpen(Builder $query){
        return $query->where('status' , self::STATUS_AVAILABLE);
    }

    public function scopeByGameIds(Builder $query,$gameIds){
        return $query->whereIn('game_id',$gameIds);
    }

    public function scopeByCarrierIds(Builder $query, $carrierIds){
        return $query->whereIn('carrier_id',$carrierIds);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'status' => 'boolean',
    ];
    public static $requestAttributes = [
        'display_name' => '前台显示名称',
        'status' => '状态',
        'sort' => '排序'
    ];

    public static function updateRules($current_carrier_id,$id){
        return array_merge(self::$rules,[
            'display_name' => 'required|max:20|unique:map_carrier_games,display_name,'.$id.',id,carrier_id,'.$current_carrier_id,
            'sort'      => 'integer|min:1|max:99|required',
        ]);
    }

    public function game(){
        return $this->hasOne(Game::class,'game_id','game_id');
    }

    public function carrier(){
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }
    public function collect($carrier_id, $player_id){
        $info = PlayerCollect::where(['map_carrier_game_id' => $carrier_id, 'player_id'=> $player_id])->first();
        return $info?1:0;
    }
}
