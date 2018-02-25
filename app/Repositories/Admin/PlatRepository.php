<?php
namespace App\Repositories\Admin;

use App\Models\Def\MainGamePlat;
use InfyOm\Generator\Common\BaseRepository;

class PlatRepository extends BaseRepository
{
    
    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::model()
     */
    public function model()
    {
        return MainGamePlat::class;
    }

}

