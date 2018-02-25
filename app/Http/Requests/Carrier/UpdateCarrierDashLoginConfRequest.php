<?php
namespace App\Http\Requests\Carrier;

use App\Models\Conf\CarrierDashLoginConf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCarrierDashLoginConfRequest extends FormRequest
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
     * 由于网站配置分成了很多块, 所以对不同的区块进行分别验证
     *
     * @var array
     */
    private $rules = [
        'base_info' => [
            'forbidden_login_comment' => 'string|min:10|max:50',
            'carrier_login_failed_count_when_locked' => 'integer|min:1',
            'player_login_failed_count_when_locked' => 'integer|min:1',
            'player_register_forbidden_user_names' => 'string',
            'player_forbidden_login_comment' => 'string|max:100',
            'player_forbidden_register_comment' => 'string|max:100',
            'player_login_failed_locked_time' => 'integer|min:1',
            'agent_login_failed_count_when_locked' => 'integer|min:1',
            'agent_register_forbidden_user_names' => 'string|max:50',
            'agent_forbidden_login_comment' => 'string|max:50',
            'agent_forbidden_register_comment' => 'string|max:50',
            'agent_login_failed_locked_time' => 'integer|min:1'
        ],
        'content_info' => [
            'is_allow_player_login' => 'boolean|required',
            'is_allow_player_register' => 'boolean|required',
            'is_check_exist_player_real_user_name' => 'boolean|required',
            'is_allow_user_withdraw_with_password' => 'boolean|required',
            'is_allow_agent_login' => 'boolean|required',
            'is_allow_agent_register' => 'boolean|required',
            'is_allow_agent_withdraw_with_password' => 'boolean|required',
            'is_check_exist_agent_real_user_name' => 'boolean|required'
        ]
    ];

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            if ($this->get('update_type') == null) {
                $validator->errors()
                    ->add('update_type', '非法请求');
                return;
            }
            if (! in_array($this->get('update_type'), array_keys($this->rules))) {
                $validator->errors()
                    ->add('update_type', '非法请求');
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
        $requestType = $this->get('update_type', 'base_info');
        $type = [
            'update_type' => 'required'
        ];
        if (! in_array($this->get('update_type'), array_keys($this->rules))) {
            return $type;
        }
        return array_merge($type, $this->rules[$requestType]);
    }

    public function attributes()
    {
        return [
            'forbidden_login_comment' => '禁止管理员登录提示原因',
            'carrier_login_failed_count_when_locked' => '管理员登录错误锁定次数',
            'is_allow_player_login' => '是否允许会员登录',
            'is_allow_player_register' => '是否允许会员注册',
            'player_login_failed_count_when_locked' => '会员登录错误几次锁定',
            'player_login_failed_locked_time' => '会员登录错误锁定时间',
            'player_register_forbidden_user_names' => '会员注册限制账号',
            'player_forbidden_login_comment' => '会员禁止登录原因',
            'player_forbidden_register_comment' => '会员禁止注册原因',
            'is_check_exist_player_real_user_name' => '是否检测真实姓名是否同名',
            'is_allow_user_withdraw_with_password' => '是否需要取款密码',
            'is_allow_agent_login' => '是否允许代理登录',
            'is_allow_agent_register' => '是否允许代理注册',
            'agent_login_failed_count_when_locked' => '代理登录错误锁定次数',
            'agent_login_failed_locked_time' => '代理登录错误锁定时间',
            'agent_register_forbidden_user_names' => '代理注册限制账号',
            'agent_forbidden_login_comment' => '代理禁止登录原因',
            'agent_forbidden_register_comment' => '代理禁止注册原因',
            'is_allow_agent_withdraw_with_password' => '是否需要取款密码',
            'is_check_exist_agent_real_user_name' => '是否检测真实姓名是否同名',
            'player_birthday_conf_status' => '会员生日',
            'player_realname_conf_status' => '会员真实姓名',
            'player_email_conf_status' => '会员邮箱',
            'player_phone_conf_status' => '会员手机',
            'player_sex_conf_status' => '会员性别',
            'player_qq_conf_status' => '会员qq',
            'player_wechat_conf_status' => '会员微信',
            'agent_type_conf_status' => '代理类型',
            'agent_realname_conf_status' => '代理真实姓名',
            'agent_birthday_conf_status' => '代理生日',
            'agent_email_conf_status' => '代理邮箱',
            'agent_phone_conf_status' => '代理手机',
            'agent_qq_conf_status' => '代理qq',
            'agent_skype_conf_status' => '代理skype',
            'agent_wechat_conf_status' => '代理邮箱',
            'agent_promotion_mode_conf_status' => '代理推广方式',
            'agent_promotion_url_conf_status' => '代理推广网址',
            'agent_promotion_idea_conf_status' => '代理推广想法'
        ];
    }
}
