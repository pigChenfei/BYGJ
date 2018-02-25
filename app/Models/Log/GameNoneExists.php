<?php
namespace Models\Log;

use Eloquent as Model;

/**
 * 找不到游戏出错时，入库
 *
 * @author Joker
 *        
 */
class GameNoneExists extends Model
{

    public $table = 'log_game_none_exixts';

    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'game_plat',
        'game_flow_code',
        'game_code',
        'game_name',
        'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'game_plat' => 'string',
        'game_flow_code' => 'string',
        'game_code' => 'string',
        'game_name' => 'string',
        'message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
}

