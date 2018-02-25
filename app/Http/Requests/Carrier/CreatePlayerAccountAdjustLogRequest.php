<?php

namespace App\Http\Requests\Carrier;

use App\Http\Controllers\Carrier\CarrierMetaDataController;
use App\Models\Carrier;
use App\Models\Log\PlayerAccountLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Log\PlayerAccountAdjustLog;

class CreatePlayerAccountAdjustLogRequest extends FormRequest
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
        //游戏平台
        $carrier = Carrier::authCarrier()->with(['mapGamePlats.gamePlat','carrierPayChannels' => function($query){
            $query->available();
        }])->first();
        $gamePlatIds = $carrier->mapGamePlats->map(function($element){
            return $element->gamePlat->game_plat_id;
        })->toArray();
        $payChannels = $carrier->carrierPayChannels->map(function ($element){
            return $element->id;
        })->toArray();
        return array_merge(PlayerAccountAdjustLog::rules(),[
            'adjust_type'    => 'required|in:'.implode(',',array_keys(PlayerAccountLog::fundTypeMeta())),
            'adjust_is_plus' => 'required|in:0,1',
            'amount' => 'required|numeric|min:0|max:99999999.99',
            //取款流水限额
            'withdraw_limit_amount' => 'required|numeric|min:0|max:99999999.99',
            //取款流水限平台
            'bet_flow_game_plats.*' => 'in:'.implode(',',$gamePlatIds),
            'amount_record_pay_channel'  => 'in:'.implode(',',$payChannels),
            'remark' => 'max:125'
        ]);
    }
}
