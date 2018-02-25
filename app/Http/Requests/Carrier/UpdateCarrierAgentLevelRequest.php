<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierAgentLevel;

class UpdateCarrierAgentLevelRequest extends FormRequest
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
        $id = \Route::current()->parameter('carrierAgentLevel');
        $carrier_id = \WinwinAuth::carrierUser()->carrier_id;
        return CarrierAgentLevel::updateRules($carrier_id,$id);
    }
    
    public function attributes()
    {
        return CarrierAgentLevel::$requestAttributes;
    }
    
    public function messages()
    {
        return CarrierAgentLevel::$requestMessages;
    }
    
}
