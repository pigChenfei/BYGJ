<?php

namespace App\Models\Map;
use App\Scopes\CarrierScope;
use App\Models\Def\GamePlat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Map\CarrierGamePlat
 *
 * @property int $id
 * @property int $carrier_id
 * @property bool $status 游戏主平台是否开放  1开放  0关闭
 * @property int $game_plat_id 对应的游戏平台id
 * @property int $sort 排序
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Def\GamePlat $gamePlat
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Map\CarrierGamePlat open()
 */
class CarrierGamePlat extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    const STATUS_AVAILABLE = 1;
    const STATUS_CLOSED    = 0;


    public $table = 'map_carrier_game_plats';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'carrier_id',
        'status',
        'game_plat_id',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'game_plat_id' => 'integer',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static $requestAttributes = [
        'status' => '状态',
        'sort' => '排序'
    ];

    public static function updateRules($current_carrier_id,$id){
        return array_merge(self::$rules,[
            'status' => 'boolean|required',
            'sort'      => 'integer|min:1|max:99|required',
        ]);
    }

    public function gamePlat(){
        return $this->hasOne(GamePlat::class,'game_plat_id','game_plat_id');
    }

    public function scopeOpen(Builder $query){
        return $query->where('status' , self::STATUS_AVAILABLE);
    }

    public function scopeByCarrierId(Builder $query, $carrierId){
        return $query->where('carrier_id',$carrierId);
    }

    public function scopeByGamePlats(Builder $query, $gamePlatIds){
        return $query->whereIn('game_plat_id',$gamePlatIds);
    }

    public static function statusMeta(){
        return [
            self::STATUS_AVAILABLE => '正常',
            self::STATUS_CLOSED => '关闭'
        ];
    }
}
