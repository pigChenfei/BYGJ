<?php

namespace App\Models\Conf;

use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarrierWebBannerConf
 *
 * @package App\Models\Conf
 * @version March 15, 2017, 5:40 am UTC
 * @property int $banner_id
 * @property int $carrier_id 所属运营商
 * @property string $banner_name Banner名称
 * @property int $banner_image_id 网站图片id
 * @property int $sort 排序
 * @mixin \Eloquent
 * @property int $banner_belong_page 所属页面
 * 1 '首页',
 * 2 '真人娱乐页',
 * 3 '彩票页面',
 * 4 '电子游戏页',
 * 5 '体育游戏页',
 * 6 '优惠活动页',
 * 7 '帮助页',
 * 8 ‘合营代理页'
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereBannerBelongPage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereBannerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereBannerImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereBannerName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Conf\CarrierWebBannerConf whereUpdatedAt($value)
 */
class CarrierWebBannerConf extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'conf_carrier_web_banners';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'banner_name',
        'banner_image_id',
        'banner_belong_page',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'banner_id' => 'integer',
        'carrier_id' => 'integer',
        'banner_name' => 'string',
        'banner_image_id' => 'integer',
        'banner_belong_page' => 'integer',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
