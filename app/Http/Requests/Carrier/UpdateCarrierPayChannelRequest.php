<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierPayChannel;

class UpdateCarrierPayChannelRequest extends FormRequest
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
        $id = \Route::current()->parameter('carrierPayChannels');
        $carrier_id = \Auth::user()->carrier_id;
        return CarrierPayChannel::updateRules($carrier_id,$id);
    }

    public function attributes()
    {
        return CarrierPayChannel::$requestAttributes;
    }
}
