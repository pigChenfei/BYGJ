<?php

namespace App\Repositories\Carrier;

use App\Models\Image\CarrierImageCategory;
use InfyOm\Generator\Common\BaseRepository;

class CarrierImageCategoryRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_name',
        'carrier_id',
        'parent_category_id',
        'created_user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierImageCategory::class;
    }
}
