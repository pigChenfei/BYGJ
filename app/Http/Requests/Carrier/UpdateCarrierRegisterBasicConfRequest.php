<?php

namespace App\Http\Requests\Carrier;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Conf\CarrierRegisterBasicConf;
use Illuminate\Validation\Validator;

class UpdateCarrierRegisterBasicConfRequest extends FormRequest
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
        'setting_register_basic_info' => [

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
        $requestType = $this->get('update_type', 'setting_register_basic_info');
        $type = [
            'update_type' => 'required'
        ];
        if (!in_array($this->get('update_type'), array_keys($this->rules))) {
            return $type;
        }
        return array_merge($type, $this->rules[$requestType]);
    }
}
