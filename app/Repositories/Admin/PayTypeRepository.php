<?php
namespace App\Repositories\Admin;

use InfyOm\Generator\Common\BaseRepository;
use App\Models\Def\PayChannelType;

class PayTypeRepository extends BaseRepository
{
    
    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::model()
     */
    public function model()
    {
        return PayChannelType::class;
    }

}

