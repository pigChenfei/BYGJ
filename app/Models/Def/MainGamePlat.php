<?php

namespace App\Models\Def;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CarrierScope;
use App\Models\PlayerGameAccount;

/**
 * App\Models\Def\MainGamePlat
 *
 * @property int $main_game_plat_id
 * @property string $main_game_plat_code 主平台代码
 * @property string $main_game_plat_name 主游戏平台名称
 * @property bool $status 游戏主平台状态 1 正常  0关闭
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @mixin \Eloquent
 */
class MainGamePlat extends Model
{
    const STATUS_AVAILABLE = 1;
    const STATUS_CLOSED    = 0;
    const BBIN = 'bbin';
    const SUNBET = 'sunbet';
    const MG = 'mg';
    const AG = 'ag';
    const PT = 'pt';
    const MA = 'main';
    const ONWORKS = 'onworks';
    const GD = 'gd';
    const TGP = 'tgp';
    const VR ='vr';
    const TTG ='ttg';
    const MT ='mt';
    const PNG ='png';

    public $table = 'def_main_game_plats';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'main_game_plat_id';

    public $fillable = [
        'main_game_plat_name',
        'status',
        'main_game_plat_code',
        'account_pre',
    ];

    /**
     * 主游戏平台代码
     * @var array
     */
    public static $gamePlatCode = [
        self::MG,
        self::AG,
        self::PT,
        self::BBIN,
        self::SUNBET,
        self::ONWORKS,
        self::VR,
        self::GD,
        self::TGP,
        self::TTG,
        self::MT,
        self::PNG
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'main_game_plat_id' => 'integer',
        'main_game_plat_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @param Builder $query
     */
    public function scopeActive(Builder $query){
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function PlayerGameAccount(){
        return $this->hasMany(PlayerGameAccount::class, 'main_game_plat_id', 'main_game_plat_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function gamePlats()
    {
        return $this->hasMany(GamePlat::class,'main_game_plat_id','main_game_plat_id');
    }

}
