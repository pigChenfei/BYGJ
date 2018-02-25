<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarrierAgentUser;

class CreateCarrierAgentUserRequest extends FormRequest
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
//        //1.当前代理是否支付多级代理
//        $agent = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('agentLevel.commissionAgentConf')->first();
//
//        //3.判断代理类型
//        if ($agent->isCommissionAgent()){
//            //如果是佣金代理,判断当前佣金代理级别
//            $commissionAgentLevel = CarrierAgentUser::getAgentLevel($agent->parent_id);
//            if ($commissionAgentLevel != 1){
//                $agentCommissionConf = CarrierAgentUser::where('id',\WinwinAuth::agentUser()->id)->with('subordinateAgentCommission')->first();
//                $agentCommission = $agentCommissionConf->subordinateAgentCommission->commission_ratio;
//            }else{
//                $agentCommission = $agent->agentLevel->commissionAgentConf->sub_commission_ratio;
//            }
//        }

        $carrier_id = \WinwinAuth::agentUser()->carrier_id;
        return [
            'username' => 'required|max:50|unique:inf_agent,username,NULL,id,carrier_id,'.$carrier_id,
            'password' =>  'required|min:6|max:16',
            'confirm_password' => 'required|min:6|max:16|same:password',
           // 'commission_ratio' => 'required|numeric|min:0|max:'.$agentCommission,
            'agent_level_id' => 'integer',
            'parent_id' => 'integer'
        ];
    }


    public function attributes()
    {
        return CarrierAgentUser::$requestAttributes;
    }
}
