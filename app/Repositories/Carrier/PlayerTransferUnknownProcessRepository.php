<?php
namespace App\Repositories\Carrier;

use App\Models\PlayerTransfer;
use InfyOm\Generator\Common\BaseRepository;

class PlayerTransferUnknownProcessRepository extends BaseRepository
{

    /**
     *
     * @var array
     */
    protected $fieldSearchable = [
        'transid',
        'player_id',
        'main_game_plats_id',
        'state',
        'money',
        'direction'
    ];

    /**
     * Configure the Model
     */
    public function model()
    {
        return PlayerTransfer::class;
    }
}
