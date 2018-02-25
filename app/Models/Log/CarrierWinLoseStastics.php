<?php

namespace App\Models\Log;

use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierWinLoseStastics
 *
 * @package App\Models\Log
 * @version May 2, 2017, 10:13 pm CST
 * @property int $id
 * @property int $carrier_id
 * @property int $register_count 注册数
 * @property int $login_count 登录数
 * @property float $deposit_amount 存款额
 * @property float $first_deposit_amount 首存额
 * @property int $deposit_count 存款数
 * @property int $first_deposit_count 首存数
 * @property float $withdraw_amount 取款额
 * @property float $winlose_amount 公司输赢
 * @property float $bonus_amount 红利
 * @property float $rebate_financial_flow_amount 洗码
 * @property float $deposit_benefit_amount 存款优惠
 * @property float $carrier_income 公司收入
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $deleted_at
 * @mixin \Eloquent
 */
class CarrierWinLoseStastics extends Model
{
    use SoftDeletes;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'log_carrier_win_lose_stastics';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'register_count',
        'login_count',
        'deposit_amount',
        'first_deposit_amount',
        'deposit_count',
        'first_deposit_count',
        'withdraw_amount',
        'winlose_amount',
        'bonus_amount',
        'rebate_financial_flow_amount',
        'deposit_benefit_amount',
        'carrier_income'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'carrier_id' => 'integer',
        'id' => 'integer',
        'register_count' => 'integer',
        'login_count' => 'integer',
        'deposit_count' => 'integer',
        'first_deposit_count' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
