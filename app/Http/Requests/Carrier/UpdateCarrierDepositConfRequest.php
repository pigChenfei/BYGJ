<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Conf\CarrierDepositConf;
use Illuminate\Validation\Validator;

class UpdateCarrierDepositConfRequest extends FormRequest
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
        'deposit_info' => [
            'is_allow_player_deposit'=>'boolean|required',
            'is_allow_agent_deposit'=>'boolean|required',
            'is_allow_third_part_deposit_auto_arrival'=>'boolean|required',
            'unreview_deposit_record_limit'=>'integer|min:1|max:20000|required',
            'third_part_deposit_is_open'=>'boolean|required',
            'company_deposit_is_open'=>'boolean|required',
            'is_allow_company_deposit_auto_arrival'=>'boolean|required',
            'virtual_card_deposit_is_open'=>'boolean|required',
            'is_allow_virtual_card_deposit_auto_arrival'=>'boolean|required',
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
        $requestType = $this->get('update_type', 'deposit_info');
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
            'is_allow_palyer_deposit' => '会员是否允许存款',
            'is_allow_agent_deposit' => '会员是否允许存款',
            'is_allow_third_part_deposit_auto_arrival' => '三方存款是否允许自动到账',
            'unreview_deposit_record_limit' => '允许未审核存款条数',
            'third_part_deposit_is_open' => '三方存款是否开启',
            'company_deposit_is_open' => '公司存款是否开启',
            'is_allow_company_deposit_auto_arrival' => '公司存款是否自动到账',
            'virtual_card_deposit_is_open' => '点卡存款是否开启',
            'is_allow_virtual_card_deposit_auto_arrival' => '点卡存款是否自动到账',
        ];
    }
}
