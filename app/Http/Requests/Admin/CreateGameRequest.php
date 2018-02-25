<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Def\Game;

class CreateGameRequest extends FormRequest
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
        return Game::$rules;
    }

    public function messages()
    {
        return [
            'game_plat_id.required' => '所属游戏平台不能为空',
            'game_name.required' => '游戏名称不能为空',
            'game_code.required' => '游戏代码不能为空',
            'main_game_plat_id.required' => '所属游戏主平台不能为空',
            'game_mcategory.required' => 'BB游戏退水分类不能为空',
            'game_lines.required' => '线路不能为空',
            'game_icon_path.required' => '游戏图标路径不能为空'
        ];
    }
}
