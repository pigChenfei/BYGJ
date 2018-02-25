<?php

namespace App\Http\Requests\Web;

use App\Models\PlayerBankCard;
use Illuminate\Foundation\Http\FormRequest;


class CreatePlayerBankCardRequest extends FormRequest
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
            'card_account' => 'string|min:16|max:19|required|unique:inf_player_bank_cards,card_account,NULL,card_id',
            'card_owner_name' =>  'string|min:2|max:5|required',
            'card_type' => 'integer|required',
            'card_birth_place' => 'string|min:6|max:50|required',
         ];
    }

    public function  attributes()
    {
        return PlayerBankCard::$requestAttributes;
    }

}
