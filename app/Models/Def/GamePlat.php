<?php

namespace App\Models\Def;

use Illuminate\Database\Eloquent\Model;
/**
 * Class GamePlat
 *
 * @package App\Models
 * @version March 6, 2017, 8:19 am UTC
 * @property int $game_plat_id
 * @property int $main_game_plat_id 所属主游戏平台id
 * @property string $game_plat_name 游戏平台名称
 * @property string $english_game_plat_name 游戏平台英文名称
 * @property bool $status 状态 1 打开  0关闭
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Def\Game[] $games
 * @property-read \App\Models\Def\MainGamePlat $mainGamePlat
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereEnglishGamePlatName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereGamePlatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereGamePlatName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereMainGamePlatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Def\GamePlat whereUpdatedAt($value)
 */
class GamePlat extends Model
{

    const STATUS_AVAILABLE = 1;
    const STATUS_CLOSED    = 0;

    public $table = 'def_game_plats';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'game_plat_id';

    public $fillable = [
        'main_game_plat_id',
        'game_plat_name',
        'english_game_plat_name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'game_plat_id' => 'integer',
        'main_game_plat_id' => 'integer',
        'game_plat_name' => 'string',
        'english_game_plat_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mainGamePlat()
    {
        return $this->belongsTo(MainGamePlat::class,'main_game_plat_id','main_game_plat_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function games()
    {
        return $this->hasMany(Game::class,'game_plat_id','game_plat_id');
    }
}
