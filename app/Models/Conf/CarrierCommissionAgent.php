<?php

namespace App\Models\Conf;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
/**
 * Class CarrierCommissionAgent
 *
 * @package App\Models\Conf
 * @version April 13, 2017, 2:27 pm CST
 * @property int $id
 * @property int $carrier_id 运营商id
 * @property int $agent_level_id 代理类型名称ID
 * @property float $deposit_fee_undertake_ratio 存款手续费承担比例
 * @property int $deposit_fee_undertake_max 存款手续费承担上限
 * @property float $deposit_preferential_undertake_ratio 代理存款优惠承担比例 0不承担
 * @property int $deposit_preferential_undertake_max 代理存款优惠最高承担金额  0表示无上限
 * @property float $rebate_financial_flow_undertake_ratio 承担返水比例 0表示无上限
 * @property int $rebate_financial_flow_undertake_max 返水承担上线 0无上限
 * @property float $bonus_undertake_ratio 红利承担比例 0无上限
 * @property int $bonus_undertake_max 红利承担上限  0表示无上限
 * @property int $available_member_monthly_bet_amount 有效会员当月投注额
 * @property int $available_member_count 代理佣金有效会员总数
 * @property int $max_commission_amount_per_time 总佣金单次限额
 * @property float $commission_ratio 总佣金比例
 * @property string $commission_step_ratio 总佣金阶梯比例， json格式： 格式待确定
 * @property float $sub_commission_ratio 下级代理佣金提成比例
 * @property \Carbon\Carbon $updated_at 修改时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property-read \App\Models\Conf\CarrierCommissionAgent $flowStepRateFormatArray
 * @mixin \Eloquent
 */
class CarrierCommissionAgent extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_commission_agent';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_level_id',
        'deposit_fee_undertake_ratio',
        'deposit_fee_undertake_max',
        'deposit_preferential_undertake_ratio',
        'deposit_preferential_undertake_max',
        'rebate_financial_flow_undertake_ratio',
        'rebate_financial_flow_undertake_max',
        'bonus_undertake_ratio',
        'bonus_undertake_max',
        'available_member_monthly_bet_amount',
        'available_member_count',
        'max_commission_amount_per_time',
        'commission_ratio',
        'commission_step_ratio',
        'is_multi_commission_agent',
        'sub_commission_ratio'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'agent_level_id' => 'integer',
        'deposit_fee_undertake_max' => 'integer',
        'deposit_preferential_undertake_max' => 'integer',
        'rebate_financial_flow_undertake_max' => 'integer',
        'bonus_undertake_max' => 'integer',
        'available_member_monthly_bet_amount' => 'integer',
        'available_member_count' => 'integer',
        'max_commission_amount_per_time' => 'integer',
        'commission_step_ratio' => 'string',
    ];
    
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'deposit_fee_undertake_ratio' => 'required|numeric|min:0|max:100',
        'deposit_fee_undertake_max' => 'required|numeric|min:0',
        'deposit_preferential_undertake_ratio' => 'required|numeric|min:0|max:100',
        'deposit_preferential_undertake_max' => 'required|numeric|min:0',
        'rebate_financial_flow_undertake_ratio' => 'required|numeric|min:0|max:100',
        'rebate_financial_flow_undertake_max' => 'required|numeric|min:0',
        'bonus_undertake_ratio' => 'required|numeric|min:0|max:100',
        'bonus_undertake_max' => 'required|numeric|min:0',
        'available_member_monthly_bet_amount' => 'required|numeric|min:0',
        'commission_ratio' => 'required|numeric|min:0|max:100',
        'commission_step_ratio' => 'validateFlowStepRateJson'
    ];

    public static $requestAttributes = [
        'deposit_fee_undertake_ratio' => '存款手续费承担比例',
        'deposit_fee_undertake_max' => '存款手续费承担上限',
        'deposit_preferential_undertake_ratio' => '代理存款优惠承担比例',
        'deposit_preferential_undertake_max' => '代理存款优惠最高承担金额',
        'rebate_financial_flow_undertake_ratio' => '承担洗码比例',
        'rebate_financial_flow_undertake_max' => '洗码承担上线',
        'bonus_undertake_ratio' => '红利承担比例',
        'bonus_undertake_max' => '红利承担上限',
        'commission_ratio' => '总佣金比例',
    ];

    public function flowStepRateFormatString(){
        $array = json_decode($this->commission_step_ratio,true);
        if($array){
            $formatArray = array_map(function($element){
                return '['.$element['flowAmount'].','.$element['flowRate'].'%]';
            },$array);
            return implode(' => ',$formatArray);
        }
        return '';
    }
    
    /**
     * 验证阶梯总佣金json
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return mixed
     */
    public function validateFlowStepRateJson($attribute, $value, $parameters, $validator){
        if(!$value){
            return $validator;
        }
        $valueArray = json_decode($value,true);
        if(!$valueArray){
            $validator->errors()->add($attribute,'阶梯流水数据不能为空');
            return $validator;
        }
        foreach($valueArray as $value){
            if(!isset($value['flowAmount']) || !isset($value['flowRate'])){
                $validator->errors()->add($attribute,'阶梯流水数据不完整');
                return $validator;
            }
            if($value['flowAmount'] < 0){
                $validator->errors()->add($attribute,'有不合法的有效流水');
                return $validator;
            }
            if($value['flowRate'] < 0 || $value['flowRate'] > 100){
                $validator->errors()->add($attribute,'有不合法的洗码比例');
                return $validator;
            }
        }
        return $validator;
    }
    
    public function flowStepRateFormatArray(){
        $array = json_decode($this->commission_step_ratio,true);
        return $array ?: null;
    }

}
