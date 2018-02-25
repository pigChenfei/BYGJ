<?php

namespace App\Models\Image;

use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CarrierImageCategory
 *
 * @package App\Models\Image
 * @version March 10, 2017, 6:29 am UTC
 * @property int $id
 * @property string $category_name 图片类别
 * @property int $carrier_id 所属运营商
 * @property int $parent_category_id 上级图片类别id
 * @property \Carbon\Carbon $created_at
 * @property int $created_user_id 创建人员
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image\CarrierImage[] $images
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereCreatedUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereParentCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImageCategory whereUpdatedAt($value)
 */
class CarrierImageCategory extends Model
{

//    protected static function boot(){
//        parent::boot();
//        static::addGlobalScope(new CarrierScope());
//    }

    public $table = 'inf_carrier_image_category';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'category_name',
        'carrier_id',
        'parent_category_id',
        'created_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_name' => 'string',
        'carrier_id' => 'integer',
        'parent_category_id' => 'integer',
        'created_user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static $requestAttribute = [
        'category_name' => '文件类别'
    ];

    public static function updateRules($except_id){
        return [
            'category_name' => 'required|unique:inf_carrier_image_category,category_name,'.$except_id.',id,carrier_id,'.\WinwinAuth::carrierUser()->carrier_id
        ];
    }

    public static function createRules(){
        return [
            'category_name' => 'required|unique:inf_carrier_image_category,category_name,null,id,carrier_id,'.\WinwinAuth::carrierUser()->carrier_id
        ];
    }

    public function images(){
        return $this->hasMany(CarrierImage::class,'image_category','id');
    }

    
}
