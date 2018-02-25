<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Player;

class CreatePlayerRequest extends FormRequest
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
        $carrier_id = \WinwinAuth::currentWebCarrier()->id;
        return [
            'user_name' => 'required|min:4|max:11|unique:inf_player,user_name,NULL,player_id,carrier_id,'.$carrier_id,
            'password' =>  'required|min:6|max:20',
            'confirm_password' => 'required|min:6|max:20|same:password',
            'real_name' => 'string|nullable',//|regex:/^[\u4e00-\u9fa5]{2,40}$/
            'email' => "string|nullable",
            'mobile' => 'string|nullable|regex:/^1[3-8]\d{9}$/',
            'qq' => 'string|nullable|regex:/^[1-9][0-9]{4}*$/',
            'wechat' => 'string|nullable',
            'recommend_player_id' => 'integer',
         ];
    }

    public function  attributes()
    {
        return Player::$requestAttributes;
    }

}
