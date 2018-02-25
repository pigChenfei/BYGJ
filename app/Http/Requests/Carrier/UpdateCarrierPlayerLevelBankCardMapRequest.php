<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 17/3/6
 * Time: 下午1:00
 */

namespace App\Http\Requests\Carrier;


use App\Models\CarrierPayChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCarrierPlayerLevelBankCardMapRequest extends FormRequest
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

    protected function getValidatorInstance()
    {
        $validator =  parent::getValidatorInstance();
        $validator->after(function() use ($validator){
            $bank = $this->get('selected_bank',[]);
            if($bank){
                $count = CarrierPayChannel::where(['status' => CarrierPayChannel::STATUS_AVAILABLE,'carrier_id' => \Auth::user()->carrier_id])->whereIn('id',$bank)->count();
                if($count != count($bank)){
                    $validator->errors()->add('selected_bank','银行选择数据非法!');
                }
                return;
            }
        });
        return $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player_level_id' => 'required|exists:inf_carrier_player_level,id,carrier_id'.','.Auth::user()->carrier_id
        ];
    }

    public function attributes()
    {
        return [
            'player_level_id'   => '会员等级'
        ];
    }

}