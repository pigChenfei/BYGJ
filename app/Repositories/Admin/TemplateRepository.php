<?php
namespace App\Repositories\Admin;

use InfyOm\Generator\Common\BaseRepository;
use App\Models\Def\Template;

class TemplateRepository extends BaseRepository
{
    
    /**
     * {@inheritDoc}
     * @see \Prettus\Repository\Eloquent\BaseRepository::model()
     */
    public function model()
    {
        return Template::class;        
    }

}

