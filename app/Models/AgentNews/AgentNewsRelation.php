<?php
namespace App\Models\AgentNews;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CarrierScope;
use App\Models\CarrierAgentUser;
use App\Models\CarrierAgentNews;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AgentNewsRelation
 *
 * @package App\Models
 * @version May 9, 2017, 2:19 pm CST
 */
class AgentNewsRelation extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }

    public $table = 'inf_agent_news_relation';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $dates = [
        'deleted_at'
    ];

    public $fillable = [
        'carrier_id',
        'agent_id',
        'agent_news_id',
        'agent_deleted_status',
        'agent_view_status'
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
        'agent_news_id' => 'integer',
        'agent_deleted_status' => 'integer',
        'agent_view_status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function carrierAgentNews()
    {
        return $this->belongsTo(CarrierAgentNews::class, 'agent_news_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(CarrierAgentUser::class, 'agent_id', 'id');
    }

    public function scopeUnViewed(Builder $query)
    {
        return $query->where('agent_view_status', 0);
    }

    public function scopeUnDeleted(Builder $query)
    {
        return $query->where('agent_deleted_status', 0);
    }
}
