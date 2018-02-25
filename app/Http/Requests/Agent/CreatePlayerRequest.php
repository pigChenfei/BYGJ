<?php

namespace App\Http\Requests\Agent;

use App\Models\CarrierAgentUser;
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
        $carrier_id = \WinwinAuth::agentUser()->carrier_id;
        return [
            'user_name' => 'required|min:6|max:16|unique:inf_player,user_name,NULL,player_id,carrier_id,'.$carrier_id,
            'password' =>  'required|min:6|max:16',
            'confirm_password' => 'required|min:6|max:16|same:password',
            'agent_id' => 'integer'
         ];
    }

    public function  attributes()
    {
        return Player::$requestAttributes;
    }

}
