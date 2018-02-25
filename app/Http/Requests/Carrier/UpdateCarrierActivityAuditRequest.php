<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarrierActivityAuditRequest extends FormRequest
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
        return [
            'passed' => 'required|in:0,1',
            'adjust_is_plus' => 'required_if:passed,1|in:0,1',
            'amount' => 'numeric|required_if:passed,1|min:0',
            'player_id' => 'required_if:passed,1|exists:inf_carrier_activity_audit,player_id,carrier_id,'.\WinwinAuth::carrierUser()->carrier_id,
            'withdraw_limit_amount' => 'required_if:passed,1|numeric|min:0',
            'remark' => 'max:255'
        ];
    }
}
