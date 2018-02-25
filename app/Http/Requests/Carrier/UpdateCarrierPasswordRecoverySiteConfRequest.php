<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use Illuminate\Validation\Validator;

class UpdateCarrierPasswordRecoverySiteConfRequest extends FormRequest
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

    private $rules = [
        'setting_password_recovery_info' => [
            'is_open_email_send_function'=>'boolean|required',
            'smtp_server'=>'string|required',
            'smtp_service_port'=>'integer|required',
            'mail_sender'=>'string|required',
            'smtp_username'=>'string|required',
            'smtp_password'=>'alpha_num|required',
        ],
    ];

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function () use ($validator) {
            if ($this->get('update_type') == null) {
                $validator->errors()->add('update_type', '非法请求');
                return;
            }
            if (!in_array($this->get('update_type'), array_keys($this->rules))) {
                $validator->errors()->add('update_type', '非法请求');
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
        $requestType = $this->get('update_type', 'setting_password_recovery_info');
        $type = [
            'update_type' => 'required'
        ];
        if (!in_array($this->get('update_type'), array_keys($this->rules))) {
            return $type;
        }
        return array_merge($type, $this->rules[$requestType]);
    }

    public function attributes()
    {
        return [
            'is_open_email_send_function' => '是否启用邮件发送功能',
            'smtp_server' => 'smtp服务器',
            'smtp_service_port' => 'smtp服务器端口',
            'mail_sender' => '邮件发送人',
            'smtp_username' => 'smtp账号',
            'smtp_password' => 'smtp密码',
        ];
    }
}
