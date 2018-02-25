<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierActivity;
use App\Models\Player;
class CreateCarrierActivityRequest extends FormRequest
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
            $apply_rule_string = $this->get('apply_rule_string');
            $apply_rule_str = json_decode($apply_rule_string,true)[0];
            try{
                CarrierActivity::checkUserCanJoinActivity(Player::first()->player_id,$apply_rule_str);
            }catch (\Exception $e){
                $validator->errors()->add('apply_rule_string', '申请规则不合法');
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
        $id = \Auth::user()->carrier_id;
        return CarrierActivity::createRules($id);
    }
    public function attributes()
    {
        return CarrierActivity::$requestAttributes;
    }
}
