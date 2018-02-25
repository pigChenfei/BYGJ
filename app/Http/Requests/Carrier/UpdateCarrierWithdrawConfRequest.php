<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Conf\CarrierWithdrawConf;
use Illuminate\Validation\Validator;

class UpdateCarrierWithdrawConfRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    private $rules = [
        'withdraw_info' => [
            'is_allow_player_withdraw'=>'boolean|required',
            'is_allow_player_withdraw_decimal'=>'boolean|required',
            'player_day_withdraw_success_limit_count'=>'integer|min:1|required',
            'player_day_withdraw_max_sum'=>'integer|min:1|required',
            'player_once_withdraw_max_sum'=>'integer|min:1|required',
            'player_once_withdraw_min_sum'=>'integer|min:1|required',
            'is_display_flow_water_check'=>'boolean|required',
            'is_open_risk_management_check'=>'boolean|required',
            'is_allow_agent_withdraw'=>'boolean|required',
            'is_allow_agent_withdraw_decimal'=>'boolean|required',
            'agent_day_withdraw_success_limit_count'=>'integer',
            'agent_day_withdraw_max_sum'=>'integer|min:1|required',
            'agent_once_withdraw_max_sum'=>'integer|min:1|required',
            'agent_once_withdraw_min_sum'=>'integer|min:1|required',
        ],
    ];

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            if ($this->get('update_type') == null) {
                $validator->errors()->add('update_type', '非法请求');
                return;
            }
            if (!in_array($this->get('update_type'), array_keys($this->rules))) {
                $validator->errors()->add('update_type', '非法请求');
                return;
            }
        });
        return $validator;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $requestType = $this->get('update_type', 'withdraw_info');
        $type = [
            'update_type' => 'required'
        ];
        if (!in_array($this->get('update_type'), array_keys($this->rules))) {
            return $type;
        }
        return array_merge($type, $this->rules[$requestType]);
    }
    public function attributes()
    {
        return [
            'is_allow_player_withdraw' => '是否允许会员取款',
            'is_allow_player_withdraw_decimal' => '是否允许会员取款小数',
            'player_day_withdraw_success_limit_count' => '会员单日取款成功限制次数',
            'player_day_withdraw_max_sum' => '会员单日取款最大金额',
            'player_once_withdraw_max_sum' => '会员单次取款最大金额',
            'player_once_withdraw_min_sum' => '会员单次取款最小金额',
            'is_display_flow_water_check' => '是否显示流水提示',
            'is_open_risk_management_check' => '是否开启风控审核',
            'is_allow_agent_withdraw' => '是否允许代理取款',
            'is_allow_agent_withdraw_decimal' => '是否允许取款小数',
            'agent_day_withdraw_success_limit_count' => '代理单日取款成功限制次数',
            'agent_day_withdraw_max_sum' => '代理单日取款最大金额',
            'agent_once_withdraw_max_sum' => '代理单次取款最大金额',
            'agent_once_withdraw_min_sum' => '代理单次取款最小金额',
        ];
    }
}
