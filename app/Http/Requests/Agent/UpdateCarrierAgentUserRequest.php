<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierAgentUser;

class UpdateCarrierAgentUserRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $except_id = \WinwinAuth::agentUser()->id;
        $carrier_id = \WinwinAuth::agentUser()->carrier_id;
        return [
          //  'username' => 'required|max:50|unique:inf_agent,username,'.$except_id.',id,carrier_id,'.$carrier_id,
            'realname' => 'string|min:2|max:8|unique:inf_agent,realname,'.$except_id,
            'birthday' => 'date',
            'email' => 'email|unique:inf_agent,email,'.$except_id,
            'mobile' => 'regex:/^1[3-8]\d{9}$/|unique:inf_agent,mobile,'.$except_id,
            'qq' => 'regex:/[1-9][0-9]{4,14}/',
            'wechat' => 'regex:/^[a-zA-Z\d_]{5,}$/',
            'skype' => 'regex:/^[\w]{6,20}$/',
            'promotion_url' => 'string|min:10|max:50',
            'promotion_notion' =>  'string|min:20|max:250'
        ];
    }

    public function attributes()
    {
        return CarrierAgentUser::$requestAttributes;
    }
}
