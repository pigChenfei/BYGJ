<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AgentBankCard;

class CreateAgentBankCardRequest extends FormRequest
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
        //$carrier_id = \WinwinAuth::agentUser()->carrier_id;
        return [
            'card_owner_name' =>  'string|min:2|max:5|required',
            'card_account' => 'string|min:16|max:19|required|unique:inf_player_bank_cards,card_account,NULL,card_id',
            'card_type' => 'integer|required',
            'card_birth_place' => 'string|min:6|max:50|required',
        ];
    }

    public function  attributes()
    {
        return AgentBankCard::$requestAttributes;
    }
}
