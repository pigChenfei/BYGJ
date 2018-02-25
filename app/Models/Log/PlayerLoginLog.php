<?php
namespace App\Models\Log;

use App\Jobs\UpdateLogModelIpAddressQueue;
use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PlayerGameAccount;
use App\Models\Player;

/**
 * Class PlayerLoginLog
 *
 * @package App\Models\Log
 * @version March 9, 2017, 10:03 am UTC
 * @property int $log_id
 * @property int $player_id 会员id
 * @property string $login_ip 登录ip
 * @property string $login_domain 登录域名
 * @property string $login_time 登录时间
 * @property string $login_location 登录地点
 * @property int $carrier_id 运营商id
 *           @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereLogId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereLoginDomain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereLoginIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereLoginLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereLoginTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog wherePlayerId($value)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereUpdatedAt($value)
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog byCreatedTimeRange($startTime, $endTime)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog orderByCreatedTime($type)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Log\PlayerLoginLog whereCarrierId($value)
 */
class PlayerLoginLog extends Model
{

    protected static function boot()
    {
        parent::boot();
        PlayerLoginLog::created(function ($log) {
            dispatch((new UpdateLogModelIpAddressQueue($log, 'login_ip', 'login_location'))->onQueue('low'));
        });
    }

    public $table = 'log_player_login';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    protected $primaryKey = 'log_id';

    public $fillable = [
        'player_id',
        'login_ip',
        'login_domain',
        'login_time',
        'login_location'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'log_id' => 'integer',
        'player_id' => 'integer',
        'login_ip' => 'string',
        'login_domain' => 'string',
        'login_location' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public function scopeByCreatedTimeRange(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('created_at', [
            $startTime,
            $endTime
        ]);
    }

    public function playerAccountInfo()
    {
        return $this->belongsTo(PlayerGameAccount::class, 'player_id', 'player_id');
    }

    public function scopeOrderByCreatedTime(Builder $query, $type)
    {
        return $query->orderBy('created_at', $type);
    }

    // 查询活跃会员
    public function scopeLoginTime(Builder $query, $startTime, $endTime)
    {
        return $query->whereBetween('login_time', [
            $startTime,
            $endTime
        ]);
    }
}
