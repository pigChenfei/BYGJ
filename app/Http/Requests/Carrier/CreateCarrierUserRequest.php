<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierUser;

class CreateCarrierUserRequest extends FormRequest
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
        return CarrierUser::createRules($id);
    }
    public function attributes()
    {
        return CarrierUser::$requestAttributes;
    }
}
