<?php
namespace App\Http\Requests\Web;

use App\Models\Conf\CarrierDashLoginConf;
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
        $carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $agentRegisterConf = CarrierDashLoginConf::where('carrier_id', $carrier_id)->first();
        
        if ($agentRegisterConf) {
            if ((($agentRegisterConf->agent_realname_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED) &&
                 ($agentRegisterConf->is_check_exist_agent_real_user_name == 1)) {
                self::$rules = array_merge(self::$rules,
                    [
                        'realname' => 'string|min:2|max:12|unique:inf_agent,realname|required' // /^[\u4e00-\u9fa5]{2,40}$/ ^([\u4e00-\u9fa5]+|([a-zA-Z]+\s?)+)$
                    ]);
            } elseif ((($agentRegisterConf->agent_realname_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED) &&
                 ($agentRegisterConf->is_check_exist_agent_real_user_name == 0)) {
                self::$rules = array_merge(self::$rules, [
                    'realname' => 'string|min:2|max:12|required'
                ]);
            } elseif ((($agentRegisterConf->agent_realname_conf_status & CarrierDashLoginConf::IS_REQUIRED) != CarrierDashLoginConf::IS_REQUIRED) &&
                 ($agentRegisterConf->is_check_exist_agent_real_user_name == 1)) {
                self::$rules = array_merge(self::$rules, [
                    'realname' => 'string|min:2|max:12|unique:inf_agent,realname'
                ]);
            } else {
                self::$rules = array_merge(self::$rules, [
                    'realname' => 'string|min:2|max:12'
                ]);
            }
            
            if (($agentRegisterConf->agent_phone_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED) {
                self::$rules = array_merge(self::$rules, [
                    'mobile' => 'regex:/^1[3-8]\d{9}$/|unique:inf_agent,mobile'
                ]);
            } else {
                self::$rules = array_merge(self::$rules, [
                    'mobile' => 'regex:/^1[3-8]\d{9}$/|unique:inf_agent,mobile'
                ]);
            }
            
            if (($agentRegisterConf->player_email_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED) {
                self::$rules = array_merge(self::$rules, [
                    'email' => 'email|unique:inf_agent,email|required'
                ]);
            } else {
                self::$rules = array_merge(self::$rules, [
                    'email' => 'email|unique:inf_agent,email'
                ]);
            }
            
            // if(($agentRegisterConf->agent_birthday_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED){
            // self::$rules = array_merge(self::$rules,[
            // 'birthday'=>'date|required',
            // ]);
            // }else{
            // self::$rules = array_merge(self::$rules,[
            // 'birthday'=>'date',
            // ]);
            // }
            
            // if(($agentRegisterConf->agent_qq_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED){
            // self::$rules = array_merge(self::$rules,[
            // 'qq'=>'regex:/[1-9][0-9]{4,14}/|required',///[1-9][0-9]{4,14}/
            // ]);
            // }else{
            // self::$rules = array_merge(self::$rules,[
            // 'qq'=>'regex:/[1-9][0-9]{4,14}/',
            // ]);
            // }
            //
            // if(($agentRegisterConf->agent_wechat_conf_status & CarrierDashLoginConf::IS_REQUIRED) == CarrierDashLoginConf::IS_REQUIRED){
            // self::$rules = array_merge(self::$rules,[
            // 'wechat'=>'regex:/^[a-zA-Z\d_]{5,}$/|required',
            // ]);
            // }else{
            // self::$rules = array_merge(self::$rules,[
            // 'wechat'=>'regex:/^[a-zA-Z\d_]{5,}$/',
            // ]);
            // }
            return self::$rules;
        }
        return self::$rules;
    }

    public static $rules = [
        'username' => 'regex:/^([a-z0-9A-Z-_]){4,12}$/i|required',
        'promotion_code' => 'string|min:4|max:6',
        'promotion_url' => 'regex:/^(([^-][a-z0-9A-Z-_]+\.)*)[^-][a-z0-9A-Z-_]+(\.[a-zA-Z]{2,4}){1,2}$/i'
    ];

    public function attributes()
    {
        return CarrierAgentUser::$requestAttributes;
    }
}
