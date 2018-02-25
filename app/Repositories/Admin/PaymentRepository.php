<?php
namespace App\Repositories\Admin;

use InfyOm\Generator\Common\BaseRepository;
use App\Models\Def\PayChannel;

class PaymentRepository extends BaseRepository
{
    
    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::model()
     */
    public function model()
    {
        return PayChannel::class;        
    }

}

