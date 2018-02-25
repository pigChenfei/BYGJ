<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Log\PlayerWithdrawLog;

class UpdatePlayerWithdrawLogRequest extends FormRequest
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
        return array_merge([
            'status' => 'required|in:'.implode(',',array_keys(PlayerWithdrawLog::statusMeta())),
            'carrier_pay_channel' => 'required|exists:inf_carrier_pay_channel,id,carrier_id,'.\WinwinAuth::carrierUser()->carrier_id,
            'fee_bear_side' => 'required|in:player,agent,company',
            'fee_amount' => 'required|numeric|min:0',
            'remark' => 'max:255',
        ],PlayerWithdrawLog::$rules);
    }

    public function attributes()
    {
        return PlayerWithdrawLog::$requestAttributes;
    }
}
