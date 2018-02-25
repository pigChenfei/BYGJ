<?php

namespace App\Models\Log;

use Eloquent as Model;
use App\Scopes\CarrierScope;
use App\Models\CarrierActivity;
/**
 * Class PlayerActivityLog
 * @package App\Models\Carrier
 * @version April 18, 2017, 10:12 pm CST
 */
class PlayerActivityLog extends Model
{
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    
    public $table = 'log_player_activity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    /**
     *人工审核
     * @var type   Manual audit
     */
    const HANDLE_WAY_MANUAL_AUDIT = 1;
    /**
     *自动审核
     * @var type   Automatic audit
     */
    const HANDLE_WAY_AUTOMATIC_AUDIT = 2;

    /**
     *status check
     * @var type 
     */
    const STATUS_CHECK_AUDIT = 1;
    const STATUS_ADOPT = 2;
    const STATUS_REFUSE = -1;
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'act_id',
        'carrier_id',
        'player_id',
        'amount',
        'handle_way',
        'status',
        'handle_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'act_id' => 'integer',
        'carrier_id' => 'integer',
        'player_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static function handleWayMeta(){
        return [
            self::HANDLE_WAY_MANUAL_AUDIT => '人工审核',
            self::HANDLE_WAY_AUTOMATIC_AUDIT => '自动审核',
        ];
    }
    
    public static function statusMeta(){
        return [
            self::STATUS_CHECK_AUDIT => '待审核',
            self::STATUS_ADOPT => '通过',
            self::STATUS_REFUSE => '拒绝',
        ];
    }
    
    public function activity(){
        return $this->belongsTo(CarrierActivity::class,'act_id','id');
    }
}
