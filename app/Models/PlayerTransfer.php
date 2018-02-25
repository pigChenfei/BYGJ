<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Def\MainGamePlat;

/**
 * App\Models\PlayerTransfer
 *
 * @author Joker
 *        
 * @method static \Illuminate\Database\Query\Builder|\App\Models\PlayerTransfer transferUnknown()
 */
class PlayerTransfer extends Model
{

    public $table = 'inf_player_transfer';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const STATE_UNDO = 0;

    const STATE_AUTO_UNKOWN = 2;

    const STATE_UNKOWN = 4;

    protected $primaryKey = 'id';

    public $fillable = [
        'transid',
        'carrier_id',
        'operator_id',
        'player_id',
        'main_game_plats_id',
        'state',
        'money',
        'direction',
        'operatored_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'transid' => 'integer',
        'player_id' => 'integer',
        'main_game_plats_id' => 'integer',
        'state' => 'integer',
        'money' => 'numeric',
        'direction' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    // 玩家
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }

    public function mainGamePlat()
    {
        return $this->belongsTo(MainGamePlat::class, 'main_game_plats_id', 'main_game_plat_id');
    }

    /**
     * 对应运营商
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }

    /**
     * 对应操作人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrierOperator()
    {
        return $this->hasOne(CarrierUser::class, 'id', 'operator_id');
    }

    public function scopeTransferUnknown(Builder $query)
    {
        return $query->where('state', self::STATE_UNDO);
    }

    /**
     * *
     * 获取异常转账单号信息
     */
    public function getTransferUnknownProcess($start_time = '', $end_time = '', $search_content = '')
    {
        var_dump($start_time);
        if ($start_time && $end_time && $search_content) {
            $res = \DB::table('inf_player_transfer')->join('inf_player', 'inf_player_transfer.player_id', '=', 'inf_player.player_id')
                    /* ->where('state',2)
                     ->orWhere('state',4)*/
                    ->select('inf_player_transfer.*', 'inf_player.user_name', 'inf_player.real_name')
                ->whereDate('created_at', $start_time)
                    /*->where('created_at','<',$end_time)*/
                    ->where('user_name', 'like', "%" . $search_content . "%")
                ->orWhere('real_name', 'like', "%" . $search_content . "%")
                ->orderBy('created_at')
                ->paginate();
            return $res;
        }
        $res = \DB::table('inf_player_transfer')->join('inf_player', 'inf_player_transfer.player_id', '=', 'inf_player.player_id')
                /* ->where('state',2)
                 ->orWhere('state',4)*/
                ->select('inf_player_transfer.*', 'inf_player.user_name', 'inf_player.real_name')
            ->where('user_name', 'like', "%" . $search_content . "%")
            ->orWhere('real_name', 'like', "%" . $search_content . "%")
            ->paginate();
        return $res;
    }
}
