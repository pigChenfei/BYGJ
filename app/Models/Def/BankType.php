<?php

namespace App\Models\Def;

use Eloquent as Model;
/**
 * Class BankType
 *
 * @package App\Models
 * @version March 2, 2017, 11:25 am UTC
 * @property int $type_id
 * @property string $bank_name 银行卡名称 如 中国农业银行,微信
 * @property bool $bank_type 银行类型
 * 1   传统银行 如:中国农业银行
 * 2  第三方支付 如:微信
 * 3  网络银行 如:网商银行
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankType whereBankName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankType whereBankType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankType whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BankType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BankType extends Model
{

    public $table = 'def_bank_types';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'bank_name',
        'bank_type',
        'wap_icon',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
        'bank_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static function bankTypeMeta(){
        return [ 1 => '银行' , 2 => '三方支付' , 3 => '网络银行'];
    }

    
}
