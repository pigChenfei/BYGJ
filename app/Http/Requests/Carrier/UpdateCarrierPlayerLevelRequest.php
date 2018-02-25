<?php

namespace App\Http\Requests\Carrier;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierPlayerLevel;

class UpdateCarrierPlayerLevelRequest extends FormRequest
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

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            $upgrade_rule = $this->get('upgrade_rule');
            $upgrade_str = json_decode($upgrade_rule,true)[0];
            try{
                CarrierPlayerLevel::checkUserCanUpdateLevel(Player::first()->player_id,$upgrade_str);
            }catch (\Exception $e){
                $validator->errors()->add('upgrade_rule', '升级规则不合法');
            }
            return;
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
        return CarrierPlayerLevel::updateRules(\WinwinAuth::carrierUser()->carrier_id,\Route::current()->parameter('carrierPlayerLevel'));
    }


    public function attributes()
    {
        return CarrierPlayerLevel::$requestAttributes;
    }

    public function messages()
    {
        return CarrierPlayerLevel::$requestMessages;
    }
}
