<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatePayTypeRequest extends FormRequest
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
            'type_name' => 'required',
            'parent_id' => 'required',
            'sort' => 'required|integer|min:0'
        ];
    }

    public function attributes()
    {
        return [
            'type_name' => '支付类型名称',
            'sort' => '排序',
            'parent_id' => '所属分类',
        ];
    }
}
