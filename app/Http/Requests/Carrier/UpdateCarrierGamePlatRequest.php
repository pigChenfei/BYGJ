<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Map\CarrierGamePlat;

class UpdateCarrierGamePlatRequest extends FormRequest
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
        $id = \Route::current()->parameter('carrierGamePlat');
        $carrier_id = \Auth::user()->carrier_id;
        return CarrierGamePlat::updateRules($carrier_id,$id);
    }
    public function attributes()
    {
        return CarrierGamePlat::$requestAttributes;
    }
}
