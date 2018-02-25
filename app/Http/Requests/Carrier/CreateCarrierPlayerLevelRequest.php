<?php

namespace App\Http\Requests\Carrier;

use App\Http\Controllers\AppBaseController;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierPlayerLevel;
use \Illuminate\Contracts\Validation\Validator;

class CreateCarrierPlayerLevelRequest extends FormRequest
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
        $id = \Auth::user()->carrier_id;
        return CarrierPlayerLevel::createRules($id);
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
