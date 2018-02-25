<?php

namespace App\Http\Requests\Web;

use App\Models\CarrierAgentUser;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierBackUpDomain;
use Illuminate\Support\Facades\URL;


class AgentLoginRequest extends FormRequest
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

        //$url = parse_url(\URL::current());


        return [
            'username' => 'required|min:4|max:11',
            'password'  => 'required|min:6|max:20',

        ];



    }



    public function attributes()
    {
        return CarrierAgentUser::$requestAttributes;
    }
}
