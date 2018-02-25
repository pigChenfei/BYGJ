<?php

namespace App\Http\Requests\Web;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierBackUpDomain;
use Illuminate\Support\Facades\URL;


class PlayerLoginRequest extends FormRequest
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

        $url = parse_url(\URL::current());
        //$carrier_id = \WinwinAuth::currentWebCarrier()->id;

        return [
            'user_name' => 'required|min:4|max:11',
            'password'  => 'required|min:6|max:16',

        ];


        //dd($carrier_id->site_url);
        //return Player::searchRules($carrier_id = 1);
    }



    public function attributes()
    {
        return Player::$requestAttributes;
    }
}
