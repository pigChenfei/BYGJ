<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlatRequest extends FormRequest
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
            'main_game_plat_name' => 'required',
            'main_game_plat_code' => 'required',
            'status' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'main_game_plat_name' => '主游戏平台名称',
            'main_game_plat_code' => '主平台代码',
            'status' => '状态',
        ];
    }
}
