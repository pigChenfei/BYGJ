<?php

namespace App\Repositories\Carrier;

use InfyOm\Generator\Common\BaseRepository;
//use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Map\CarrierGame;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CarrierGameRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CarrierGameRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CarrierGame::class;
    }

}
