<?php

namespace App\Models;

use App\Exceptions\CarrierRuntimeException;
use App\Models\Activity\CarrierActivityAgentUser;
use App\Models\Activity\CarrierActivityAmphotericGamePlat;
use App\Models\Activity\CarrierActivityFlowLimitedPlatform;
use App\Models\Activity\CarrierActivityPlayerLevel;
use App\Models\Image\CarrierImage;
use Eloquent as Model;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawLog;
use \Carbon\Carbon;
use Illuminate\Http\Request;


/**
 * App\Models\CarrierActivity
 *
 * @property int $id
 * @property int $carrier_id 运营商ID
 * @property int $act_type_id 优惠活动类型ID
 * @property string $name 活动名称
 * @property int $sort 排序
 * @property bool $status 活动状态 1 上架 0下架
 * @property int $current_deposit_amount 当前存款额
 * @property bool $bonuses_type 红利(返奖)类型 1百分比 2固定金额
 * @property string $rebate_financial_bonuses_step_rate_json 红利类型阶梯比例 josn
 * @property bool $flow_want_pattern 流水要求模式
 * @property bool $apply_times 玩家申请次数
 * @property bool $censor_way 审查方式 1手动，2自动
 * @property int $ip_times 同一IP限制参与次数
 * @property int $image_id 活动图片ID 从公用图片库调用
 * @property bool $is_deposit_display 是否显示在存款界面 1是 0否
 * @property bool $is_website_display 网站前台是否显示1是 0否
 * @property int $mutex_parent_id 互斥活动(不能与某个活动同时参与)
 * @property bool $is_bet_amount_enjoy_flow 活动期间内的投注额是否享受反水  1是 0不是
 * @property string $apply_rule_string 申请规则
 * @property string $content_file_path 活动内容文件目录
 * @property bool $is_active_apply 是否主动申请 1是 0不是
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $join_times 参与次数
 * @property int $join_player_count 参与人数
 * @property float $join_deposit_amount 存款总额
 * @property float $join_bonus_amount 参与红利总额
 * @property-read \App\Models\CarrierActivityType $actType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity\CarrierActivityFlowLimitedPlatform[] $activityWithdrawFlowLimitGamePlats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity\CarrierActivityAmphotericGamePlat[] $amphotericWinLoseGamePlats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity\CarrierActivityAgentUser[] $canJoinAgents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity\CarrierActivityPlayerLevel[] $canJoinPlayerLevels
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivity active()
 * @mixin \Eloquent
 * @property-read \App\Models\CarrierActivityAudit $activityAudit
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CarrierActivity autoApply()
 */
class CarrierActivity extends Model
{

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new CarrierScope());
    }
    /**
     *申请次数 不限制
     * times
     */
    const APPLY_TIMES_INFINITE = 0;
    
    /**
     *申请次数 永久一次
     * Permanent once
     */
    const APPLY_TIMES_PERMANENT_ONCE = 1;
    
    /**
     *申请次数 每日一次
     * everyday once
     */
    const APPLY_TIMES_EVERYDAY_ONCE = 2;
    
    /**
     *申请次数 每周一次
     * weekly once
     */
    const APPLY_TIMES_WEEKLY_ONCE = 3;
    
    /**
     *申请次数 每月一次
     * monthly once
     */
    const APPLY_TIMES_MONTHLY_ONCE = 4;
    
    /**
     *红利(返奖)类型 1百分比
     * bonuses_type Percentage
     */
    const BONUSER_TYPE_PERCENTAGE = 1;
    
    /**
     *红利(返奖)类型 2固定金额
     * bonuses_type Fixed amount
     */
    const BONUSER_TYPE_FIXED_AMOUNT = 2;
    
    /**
     *红利(返奖)类型positive_negative_earnings_percentage_fields')<!--昨日正负盈利-->
     * bonuses_type Fixed amount
     */
    const BONUSER_TYPE_POSITVE = 3;
    /**
     * betting_amount_fixed_fields')<!--昨日投注额固定红利-->
     */
    const BONUSER_TYPE_BETTING = 4;
    /**
     * member_level_percentage_fields')<!--会员等级存送百分比-->
     */
    const BONUSER_TYPE_MEMBER_LEVEL = 5;
    
    /**
     *审查方式 手动
     * censor_way Manual
     */
    const CENSOR_WAY_MANUAL = 1;
    
    /**
     *审查方式 自动
     * censor_way accord
     */
    const CENSOR_WAY_ACCORD = 2;
    
    /**
     * 是否主动申请
     * is_active_apply
     * applystatus  不是
     */
    const ACTIVE_APPLY_NOT=0;
    /**
     * 是否主动申请
     * applystatus 是
     */
    const ACTIVE_APPLY_IS=1;
    
    /**
     *流水要求模式 红利
     * flow_want_pattern bonus
     */
    
    const FLOW_WANT_PATTERN_BONUS = 1;
    
    /**
     *流水要求模式 存款
     * flow_want_pattern deposit
     */
    const FLOW_WANT_PATTERN_DEPOSIT = 2;
    
    /**
     *流水要求模式 存款+红利
     * flow_want_pattern deposit
     */
    const FLOW_WANT_PATTERN_BONUS_DEPOSIT = 3;
    
    /**
     *是否显示在存款界面 1是
     * is_deposit_display
     */
    const DEPOSIT_DISPLAY_IS = 1;
    
    /**
     *是否显示在存款界面 0不是
     * deposit_status
     */
    const DEPOSIT_DISPLAY_NOT = 0;
    
    /**
     *活动期间内的投注额是否享受洗码 1是
     * is_bet_amount_enjoy_flow
     */
    const BET_AMOUNT_ENJOY_FLOW_IS = 1;
    
    /**
     *活动期间内的投注额是否享受洗码 0不是
     * is_bet_amount_enjoy_flow
     */
    const BET_AMOUNT_ENJOY_FLOW_NOT = 0;
    
    /**
     * 网站前台是否显示 1是
     * is_website_display
     */
    const WEBSITE_DISPLAY_IS = 1;
    /**
     * 网站前台是否显示 0不是
     * is_website_display
     */
    const WEBSITE_DISPLAY_NOT = 0;
    
    /**
     *活动已上架
     * status
     */
    const STATUS_SHELVES = 1;
    /**
     *活动已下架
     * status
     */
    const STATUS_FROM = 2;
    
    public $table = 'inf_carrier_activity';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carrier_id',
        'act_type_id',
        'name',
        'sort',
        'status',
        'current_deposit_amount',
        'bonuses_type',
        'rebate_financial_bonuses_step_rate_json',
        'flow_want_pattern',
        'apply_times',
        'censor_way',
        'ip_times',
        'image_id',
        'is_deposit_display',
        'is_website_display',
        'mutex_parent_id',
        'is_bet_amount_enjoy_flow',
        'apply_rule_string',
        'content_file_path',
        'is_active_apply'
    ];

    const BEGIN_OF_TODAY = 'BEGIN_OF_TODAY';//当天的开始
    const END_OF_TODAY = 'END_OF_TODAY';//当天的结束
    const BEGIN_OF_THIS_WEEK = 'BEGIN_OF_THIS_WEEK';//本周开始
    const END_OF_THIS_WEEK = 'END_OF_THIS_WEEK';//本周结束
    const BEGIN_OF_LAST_WEEK = 'BEGIN_OF_LAST_WEEK';//上周开始
    const END_OF_LAST_WEEK = 'END_OF_LAST_WEEK';//上周结束
    const BEGIN_OF_YESTERDAY = 'BEGIN_OF_YESTERDAY';//昨日开始
    const END_OF_YESTERDAY = 'END_OF_YESTERDAY';//今日结束
    public static function dateTimeTypes()
    {
        return [
            self::BEGIN_OF_TODAY => Carbon::now()->startOfDay()->toDateTimeString(),//当天的开始
            self::END_OF_TODAY =>  Carbon::now()->endOfDay()->toDateTimeString(),//当天的结束
            self::BEGIN_OF_YESTERDAY => Carbon::yesterday()->startOfDay()->toDateTimeString(),//昨日开始
            self::END_OF_YESTERDAY => Carbon::yesterday()->endOfDay()->toDateTimeString(),//昨日结束
            self::BEGIN_OF_THIS_WEEK => Carbon::now()->startOfWeek()->toDateTimeString(),//本周开始
            self::END_OF_THIS_WEEK => Carbon::now()->endOfWeek()->toDateTimeString(),//本周结束
            self::BEGIN_OF_LAST_WEEK => Carbon::now()->subWeek(1)->startOfWeek()->toDateTimeString(),//上周开始
            self::END_OF_LAST_WEEK => Carbon::now()->subWeek(1)->endOfWeek()->toDateTimeString(),//上周结束
        ];
    }
    
    public static $levelUpdateSqlRules = [
        //用户首次存款额
        '$userFirstDeposit' => '(SELECT `amount` FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND (`status` = \''.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.'\') ORDER BY `created_at` LIMIT 1)',
        //今日首次存款额
        '$todayFirstDeposit' => '(SELECT `amount` FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_TODAY.'\' and `created_at` <= \''.CarrierActivity::END_OF_TODAY.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.') ORDER BY `created_at` LIMIT 1)',
        //本周首次存款额
        '$thisWeekFirstDeposit' => '(SELECT `amount` FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_THIS_WEEK.'\' and `created_at` <= \''.CarrierActivity::END_OF_THIS_WEEK.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.') ORDER BY `created_at` LIMIT 1)',
        //总账户余额
        '$accountRemain' => '(SELECT COALESCE(SUM(`main_account_amount`),0) FROM `inf_player` WHERE `player_id` = $player_id)',
        //今日存款额
        '$todayDepositAmount' => '(SELECT COALESCE(SUM(`amount`),0) FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_TODAY.'\' and `created_at` <= \''.CarrierActivity::END_OF_TODAY.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.'))',
        //今日取款额
        '$todayWithdrawAmount' => '(SELECT COALESCE(SUM(`apply_amount`),0) FROM `log_player_withdraw` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_TODAY.'\' and `created_at` <= \''.CarrierActivity::END_OF_TODAY.'\' AND (`status` = '.PlayerWithdrawLog::STATUS_PAYED_OUT.'))',
        //昨日存款额
        '$yesterdayDepositAmount' => '(SELECT COALESCE(SUM(`amount`),0) FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_YESTERDAY.'\' and `created_at` <= \''.CarrierActivity::END_OF_YESTERDAY.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.'))',
        //昨日取款额
        '$yesterdayWithdrawAmount' => '(SELECT COALESCE(SUM(`apply_amount`),0) FROM `log_player_withdraw` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_YESTERDAY.'\' and `created_at` <= \''.CarrierActivity::END_OF_YESTERDAY.'\' AND (`status` = '.PlayerWithdrawLog::STATUS_PAYED_OUT.'))',
        //本周存款额
        '$thisWeekDepositAmount' => '(SELECT COALESCE(SUM(`amount`),0) FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_THIS_WEEK.'\' and `created_at` <= \''.CarrierActivity::END_OF_THIS_WEEK.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.'))',
        //本周取款额
        '$thisWeekWithdrawAmount' => '(SELECT COALESCE(SUM(`apply_amount`),0) FROM `log_player_withdraw` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_THIS_WEEK.'\' and `created_at` <= \''.CarrierActivity::END_OF_THIS_WEEK.'\' AND(`status` = '.PlayerWithdrawLog::STATUS_PAYED_OUT.'))',
        //上周存款额
        '$lastWeekDepositAmount' => '(SELECT COALESCE(SUM(`amount`),0) FROM `log_player_deposit_pay` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_LAST_WEEK.'\' and `created_at` <= \''.CarrierActivity::END_OF_LAST_WEEK.'\' AND (`status` = '.PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED.'))',
        //上周取款额
        '$lastWeekWithdrawAmount' => '(SELECT COALESCE(SUM(`apply_amount`),0) FROM `log_player_withdraw` WHERE `player_id` = $player_id AND `created_at` >= \''.CarrierActivity::BEGIN_OF_LAST_WEEK.'\' and `created_at` <= \''.CarrierActivity::END_OF_LAST_WEEK.'\' AND(`status` = '.PlayerWithdrawLog::STATUS_PAYED_OUT.'))',
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carrier_id' => 'integer',
        'act_type_id' => 'integer',
        'name' => 'string',
        'sort' => 'integer',
        'current_deposit_amount' => 'integer',
        'rebate_financial_bonuses_step_rate_json' => 'string',
        'ip_times' => 'integer',
        'image_id' => 'integer',
        'mutex_parent_id' => 'integer',
        'apply_rule_string' => 'string',
        'content_file_path' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:90|min:6',
        'act_type_id' => 'min:0|integer|required',
        'image_id' => 'min:0|integer|required',
        'ip_times' => 'required|integer|min:0',
        'sort' => 'required|integer|min:1',
    ];

    public static $requestAttributes = [
        'name' => '活动名称',
        'act_type_id' => '活动类型',
        'image_id' => '活动图片',
        'ip_times' => '相同ip限制次数',
        'sort' => '排序',
    ];


    /**
     * 判断玩家是否可以参与活动
     * @param $player_id
     * @param $canJoinSqlRuleString
     * @return bool
     * @throws \Exception
     */
    public static function checkUserCanJoinActivity($player_id, $canJoinSqlRuleString){
        try{
            $sqlRules = self::$levelUpdateSqlRules;
            array_walk($sqlRules,function (&$element) use ($player_id){
                $element = preg_replace_array('/(\$player\_id)/',[$player_id],$element);
            });
            $sqlRules = array_merge($sqlRules,['$player_id'=>$player_id]);
            $canJoinSqlRuleString = preg_replace('/(select|delete|database|show|drop|update|set|create|from|\*|\'|all|where)/','',$canJoinSqlRuleString);
            foreach ($sqlRules as $field => $sql){
               $canJoinSqlRuleString = preg_replace('/(\\'.$field.')/',$sql,$canJoinSqlRuleString);
            }
            //日期替换
            foreach(self::dateTimeTypes() as $k => $date){
                $canJoinSqlRuleString = preg_replace('/('.$k.')/', $date, $canJoinSqlRuleString);
            }
            $executeSql = 'SELECT COUNT(1) AS `SUM` FROM `inf_player` WHERE `player_id` = '.$player_id.' AND '.$canJoinSqlRuleString;
//            \WLog::info("活动申请规则检测sql:".$executeSql);
            $sum = \DB::select($executeSql);
            return $sum ? ($sum[0]->SUM ? TRUE : FALSE ): FALSE;
        }catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * 判断玩家是否可以申请活动
     * @param $loginPlayerId
     * @param $requestIp
     */
    public function checkUserCanApplyActivity($loginPlayerId, $requestIp){
        $player = Player::findOrFail($loginPlayerId);
        //是否已下架
        if($this->status == CarrierActivity::STATUS_FROM){
            throw new CarrierRuntimeException('该活动已下架');
        }
        //活动所属代理用户
        $activityAgentUsers = $this->canJoinAgents;
        if($activityAgentUsers->count() > 0){
            $canJoinAgentCount = $activityAgentUsers->filter(function(CarrierActivityAgentUser $element) use ($player){
                return $element->agent_user_id == $player->agent_id;
            })->count();
            if($canJoinAgentCount == 0){
                throw new CarrierRuntimeException('您不在该活动的参与代理商范围内');
            }
        }
        //检测活动是否是属于会员的代理可以参与
        $activityPlayerLevels = $this->canJoinPlayerLevels;
        if($activityPlayerLevels->count() > 0){
            $canJoinPlayerLevelCount = $activityPlayerLevels->filter(function(CarrierActivityPlayerLevel $element) use ($player){
                return $element->player_level_id == $player->player_level_id;
            })->count();
            if($canJoinPlayerLevelCount == 0){
                throw new CarrierRuntimeException('您不在该活动的参与会员等级范围内');
            }
        }
        //检测同一ip是否超过申请次数
        $maxJoinTimesPerIP = $this->ip_times;
        $activityAuditBuilder = CarrierActivityAudit::where('player_id', $loginPlayerId)->byActivity($this->id);
        if($maxJoinTimesPerIP > 0){
            $activityApplyTimes = $activityAuditBuilder->where('ip', $requestIp)->count();
            if($activityApplyTimes >= $maxJoinTimesPerIP){
                throw new CarrierRuntimeException('对不起,您参与次数过多');
            }
        }
        //检测是否超过最大参与次数
        $maxJoinTimes = $this->apply_times;
        if($maxJoinTimes != CarrierActivity::APPLY_TIMES_INFINITE){
            $activityApplyTimes = 0;
            if($maxJoinTimes == CarrierActivity::APPLY_TIMES_EVERYDAY_ONCE){
                $activityApplyTimes = $activityAuditBuilder->joinedToday()->count();
            }
            else if($maxJoinTimes == CarrierActivity::APPLY_TIMES_MONTHLY_ONCE){
                $activityApplyTimes = $activityAuditBuilder->joinedThisMonth()->count();
            }
            else if($maxJoinTimes == CarrierActivity::APPLY_TIMES_WEEKLY_ONCE){
                $activityApplyTimes = $activityAuditBuilder->joinedThisWeek()->count();
            }
            else if($maxJoinTimes == CarrierActivity::APPLY_TIMES_PERMANENT_ONCE){
                $activityApplyTimes = $activityAuditBuilder->count();
            }
            if($activityApplyTimes >= 1){
                throw new CarrierRuntimeException('对不起,您参与的次数过多');
            }
        }
        //检测是否有互斥互动参与过
        if($mutexId= $this->mutex_parent_id){
            $mutexActivityHasJoined = CarrierActivityAudit::where('player_id',$loginPlayerId)->byActivity($mutexId)->count() > 0;
            if($mutexActivityHasJoined){
                throw new CarrierRuntimeException('对不起,您参与过该活动的互斥活动,两则不能同时参与');
            }
        }
        $ruleJson = $this->apply_rule_string;
        //检测是否满足参与的条件
        if($ruleJson){
            $ruleSql = json_decode($ruleJson,true)[0];
            $canJoin = CarrierActivity::checkUserCanJoinActivity($loginPlayerId,$ruleSql);
            if(!$canJoin){
                throw new CarrierRuntimeException('对不起,您不符合参与条件');
            }
        }
    }

    /**
     * 活动内容
     * @return null|string
     */
    public function act_content(){
        if($this->content_file_path && \Storage::disk('carrier')->exists($this->content_file_path)){
            return \Storage::disk('carrier')->get($this->content_file_path);
        }
        return NULL;
    }

    /**
     * 有效活动
     * @param Builder $query
     * @return $this
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_SHELVES);
    }

    public static function createRules($current_carrier_id){
        return array_merge(self::$rules,[
            'name' => 'required|max:30|min:2|unique:inf_carrier_activity,name,NULL,id,carrier_id,'.$current_carrier_id,
        ]);
    }

    public static function updateRules($current_carrier_id,$except_id){
        return array_merge(self::$rules,[
            'name' => 'required|max:30|min:2|unique:inf_carrier_activity,name,'.$except_id.',id,carrier_id,'.$current_carrier_id,
        ]);
    }

    /**
     *会员申请次数数据列表
     * 
     */
    public static function applyNumberMeta(){
        return [
            self::APPLY_TIMES_INFINITE => '不限制',
            self::APPLY_TIMES_EVERYDAY_ONCE => '每日一次',
            self::APPLY_TIMES_WEEKLY_ONCE => '每周一次',
            self::APPLY_TIMES_MONTHLY_ONCE => '每月一次',
            self::APPLY_TIMES_PERMANENT_ONCE => '永久一次'
        ];
    }
    
    /**
     *红利(返奖)类型 数据列表
     * bonuses_type
     */
    public static function bonusesTypeMeta(){
        return [
            self::BONUSER_TYPE_PERCENTAGE => '存送百分比',
            self::BONUSER_TYPE_FIXED_AMOUNT => '存送固定红利',
            self::BONUSER_TYPE_POSITVE => '昨日正负盈利百分比',
            self::BONUSER_TYPE_BETTING => '昨日投注额固定红利',
            self::BONUSER_TYPE_MEMBER_LEVEL => '会员等级存送百分比',
        ];
    }
    
    /**
     *审查方式 数据列表
     * censor_way
     */
    public static function censorWayMeta(){
        return [
            self::CENSOR_WAY_MANUAL => '手动审核',
            self::CENSOR_WAY_ACCORD => '自动审核'
        ];
    }
    /**
     *是否主动申请 数据列表
     * censor_way
     */
    public static function applystatusMeta(){
        return [
            self::ACTIVE_APPLY_IS => '是',
            self::ACTIVE_APPLY_NOT => '否'
        ];
    }
    
    /**
     *流水要求模式 数据列表
     * flow_want_pattern
     */
    public static function flowWantPatternMeta(){
        return [
            self::FLOW_WANT_PATTERN_BONUS_DEPOSIT => '存款+红利',
            self::FLOW_WANT_PATTERN_DEPOSIT => '存款',
            self::FLOW_WANT_PATTERN_BONUS => '红利',
        ];
    }
    
    /**
     *是否显示在存款界面 数据列表
     * deposit_status
     */
    public static function depositStatusMeta(){
        return [
            self::DEPOSIT_DISPLAY_NOT => '否',
            self::DEPOSIT_DISPLAY_IS => '是'
        ];
    }
    
    /**
     *网站前台是否显示 1是 0否 数据列表
     * website_status
     */
    public static function websiteStatusMeta(){
        return [
            self::WEBSITE_DISPLAY_IS => '是',
            self::WEBSITE_DISPLAY_NOT => '否'
        ];
    }
    
    /**
     *活动期间内的投注额是否享受洗码 数据列表
     * betting_amount_enjoy_flow
     */
    public static function bettingAmountEnjoyFlowMeta(){
        return [
            self::BET_AMOUNT_ENJOY_FLOW_NOT => '否',
            self::BET_AMOUNT_ENJOY_FLOW_IS => '是'
        ];
    }
    
    /**
     *活动状态 数据列表
     * status
     */
    public static function statusMeta(){
        return [
            self::STATUS_FROM => '已下架',
            self::STATUS_SHELVES => '已上架',
        ];
    }

    public function scopeAutoApply(Builder $query){
        return $query->where('is_active_apply' , false);
    }
    
    /**
     * 关联代理类型数据
     * @return type
     */
    public function actType(){
        return $this->hasOne(CarrierActivityType::class,'id','act_type_id');
    }

    public function activityAudit(){
        return $this->hasOne(CarrierActivityAudit::class,'act_id','id');
    }

    public function activityWithdrawFlowLimitGamePlats(){
        return $this->hasMany(CarrierActivityFlowLimitedPlatform::class,'act_id','id');
    }

    public function amphotericWinLoseGamePlats(){
        return $this->hasMany(CarrierActivityAmphotericGamePlat::class,'act_id','id');
    }

    public function canJoinAgents(){
        return $this->hasMany(CarrierActivityAgentUser::class,'act_id','id');
    }

    public function canJoinPlayerLevels(){
        return $this->hasMany(CarrierActivityPlayerLevel::class,'act_id','id');
    }

    /**活动图片信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function imageInfo(){
        return $this->hasOne(CarrierImage::class,'id','image_id');
    }

    public function resultPremiumStr(){
        $str = "";
        if($this->apply_rule_string){
            $arr = json_decode($this->apply_rule_string, true)[1];
            foreach ($arr['totalInputStack'] as $v){
                if(isset($v['content']) && $v['content'] != 'undefined'){
                    $str .= $v['content']." ";
                } else if(isset($arr['viewMeta']['ruleType'][$v])){
                    $str .= $arr['viewMeta']['ruleType'][$v] ." ";
                }else{
                    $str .= $v." ";
                }
            }
        }
        return $str;
    }
}
