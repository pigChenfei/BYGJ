<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
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
            'channel_name' => 'required',
            'channel_code' => 'required',
            'pay_channel_type_id' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'channel_name' => '支付名称',
            'channel_code' => '支付代码',
            'pay_channel_type_id' => '支付类型'
        ];
    }
}
