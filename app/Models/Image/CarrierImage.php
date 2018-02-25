<?php

namespace App\Models\Image;

use App\Scopes\CarrierScope;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CarrierImage
 *
 * @package App\Models\Image
 * @version March 10, 2017, 6:28 am UTC
 * @property int $id
 * @property int $carrier_id 所属运营商id
 * @property int $uploaded_user_id 上传用户id
 * @property string $image_path 图片路径
 * @property int $image_category 图片所属类别
 * @property \Carbon\Carbon $created_at 创建时间
 * @property string $image_size 图片大小
 * @property string $remark 备注
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage byImageCategory($categoryId)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage orderById($orderType)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereImageCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereImagePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereImageSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereRemark($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Image\CarrierImage whereUploadedUserId($value)
 */
class CarrierImage extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_carrier_images';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'carrier_id',
        'uploaded_user_id',
        'image_path',
        'image_category',
        'image_size',
        'url_type',
        'url_link',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'uploaded_user_id' => 'integer',
        'image_path' => 'string',
        'image_category' => 'integer',
        'image_size' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'file' => 'required|image|max:1024',
        'image_category' => 'required|exists:inf_carrier_image_category,id'
    ];

    public function imageAsset(){
        return asset(\Storage::url('carrier/'.$this->image_path));
    }

    public function imageCategory(){
        return $this->belongsTo(CarrierImageCategory::class,'image_category','id');
    }

    public function scopeByImageCategory(Builder $query,$categoryId){
        return $query->where('image_category',$categoryId);
    }

    public function scopeOrderById(Builder $query,$orderType){
        return $query->orderBy('id',$orderType);
    }

    
}
