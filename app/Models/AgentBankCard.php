<?php

namespace App\Models;

use App\Models\CarrierAgentUser;
use App\Models\Def\BankType;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AgentBankCard
 * @package App\Models\Carrier
 * @version April 20, 2017, 6:14 pm CST
 */
class AgentBankCard extends Model
{
//    use SoftDeletes;

    public $table = 'inf_agent_bank_cards';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE  = 0;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'carrier_id',
        'agent_id',
        'card_account',
        'card_type',
        'card_owner_name',
        'card_birth_place',
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
        'agent_id' => 'integer',
        'card_account' => 'string',
        'card_type' => 'integer',
        'card_owner_name' => 'string',
        'card_birth_place' => 'string',
        'status' => 'boolean'
    ];

    public static $requestAttributes = [
        'card_account'     => '取款账号',
        'card_owner_name' => '开户人',
        'card_type' => '银行卡类型',
        'card_birth_place' => '分行名称'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public static function statusMeta(){
        return [
            self::STATUS_UNAVAILABLE => '无效',
            self::STATUS_AVAILABLE  => '有效'
        ];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agentUser()
    {
        return $this->belongsTo(CarrierAgentUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function bankType()
    {
        return $this->belongsTo(BankType::class,'card_type','type_id');
    }
}
