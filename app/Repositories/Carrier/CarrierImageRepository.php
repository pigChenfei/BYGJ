<?php

namespace App\Repositories\Carrier;

use App\Models\Image\CarrierImage;
use InfyOm\Generator\Common\BaseRepository;

class CarrierImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'carrier_id',
        'uploaded_user_id',
        'image_path',
        'image_category',
        'image_size',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CarrierImage::class;
    }
}
