<?php

namespace App\Repositories\Admin;

use App\Models\Def\Game;
use InfyOm\Generator\Common\BaseRepository;

class GameRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'game_plat_id',
        'english_game_name',
        'game_name',
        'game_code',
        'game_lines',
        'game_icon_path',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Game::class;
    }
}
