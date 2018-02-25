<?php

namespace App\Repositories\Web;

use App\Models\Conf\PlayerRegisterConf;
use InfyOm\Generator\Common\BaseRepository;

class SearchPlayerLoginRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PlayerRegisterConf::class;
    }
}
