<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlatChildRequest extends FormRequest
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
            'game_plat_name' => 'required',
            'main_game_plat_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'game_plat_name' => '游戏平台名称',
            'main_game_plat_id' => '父类选项',
        ];
    }
}
