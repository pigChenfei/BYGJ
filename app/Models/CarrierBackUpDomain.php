<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\CarrierBackUpDomain
 *
 * @property int $id
 * @property int $carrier_id
 * @property string $domain 域名
 * @property bool $status 1 可用  0 不可用
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Carrier $carrier
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain retrieveByDomainName($domainName)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereDomain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierBackUpDomain whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierBackUpDomain extends Model
{

    public $table = 'inf_carrier_back_up_domain';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'carrier_id',
        'domain',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'domain' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function scopeRetrieveByDomainName(Builder $query, $domainName){
        return $query->where('domain',  $domainName);
    }


    public function carrier(){
        return $this->belongsTo(Carrier::class,'carrier_id','id');
    }

    
}
