<?php
namespace App\Models;

use App\Entities\CacheConstantPrefixDefine;
use App\Exceptions\PlayerAccountException;
use App\Models\Conf\CarrierDashLoginConf;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerInviteRewardLog;
use App\Models\Log\PlayerLoginLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Models\PlayerBankCard;
use App\Scopes\CarrierAgentScope;
use App\Scopes\CarrierScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Auth;

/**
 * App\Models\Player
 *
 * @property int $player_id
 * @property string $user_name 账号
 * @property string $mobile 手机号码(登录账号用)
 * @property string $real_name
 * @property string $password 用户登录密码
 * @property string $pay_password 支付密码(可以通过运营商设置用户是否需要支付密码)
 * @property string $email 邮箱登录账号用
 * @property string $wechat 微信
 * @property string $consignee 收货人
 * @property int $sex 性别:0男,1女
 * @property string $delivery_address 收货地址
 * @property int $agent_id 代理商ID
 * @property bool $is_agent_recommend 是否是代理推荐玩家
 * @property int $recommend_player_id 推荐玩家ID
 * @property int $carrier_id 所属运营商id
 * @property float $total_win_loss 总输赢, 不需要手动更改. trigger自动维护
 * @property int $score 用户积分
 * @property mixed $main_account_amount 主账户余额
 * @property float $frozen_main_account_amount 冻结余额
 * @property string $login_ip 登录ip
 * @property int $player_level_id 玩家等级id
 * @property bool $is_online 是否在线 0不在线 1 在线
 * @property bool $user_status 用户状态:0 表示锁定(某段时间后可以重试登录) 1表示正常 2表示关闭(用户不能再登录)
 * @property bool $password_wrong_times 密码输错次数 根据此值会设置用户是否自动锁定
 * @property string $password_wrong_time 密码输入错误上次输错时间
 * @property string $login_domain 登录域名
 * @property string $referral_code 邀请码
 * @property string $qq_account qq号
 * @property string $birthday 出生日期
 * @property string $register_ip 注册ip
 * @property string $login_at 登录时间
 * @property \Carbon\Carbon $created_at 注册时间
 * @property string $deleted_at 软删除
 * @property \Carbon\Carbon $updated_at
 * @property string $remark 备注
 * @property string $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerAccountLog[] $accountLogs
 * @property-read \App\Models\CarrierAgentUser $agent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerBankCard[] $bankCards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerBetFlowLog[] $betFlowLogs
 * @property-read \App\Models\Carrier $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerDepositPayLog[] $depositLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PlayerGameAccount[] $gameAccounts
 * @property-read mixed $id
 * @property-read \App\Models\Player $invitedPlayer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerInviteRewardLog[] $invitedRewardLog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerLoginLog[] $loginLogs
 * @property-read \App\Models\CarrierPlayerLevel $playerLevel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Player[] $recommendPlayer
 * @property-read \App\Models\Conf\CarrierDashLoginConf $registerConf
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log\PlayerInviteRewardLog[] $wasInvitedRewardLog
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Player active()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Player idBetween($idSmaller, $idLarger)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Player online()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Player wasInvited()
 * @mixin \Eloquent
 */
class Player extends Auth
{

    /**
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CarrierScope());
        // 更新玩家信息时需要清空注册的缓存
        Player::updated(
            function (Player $player) {
                PlayerGameAccount::byPlayerId($player->player_id)->each(
                    function (PlayerGameAccount $gameAccount) {
                        \Cache::forget(
                            CacheConstantPrefixDefine::PLAYER_GAME_ACCOUNT_INFO_PREFIX . $gameAccount->account_user_name);
                    });
            });
    }
    
    use SoftDeletes;

    /**
     * 在线
     */
    const ONLINE_ON = 1;

    /**
     * 不在线
     */
    const ONLINE_OFF = 0;

    /**
     * 用户被锁定
     */
    const USER_STATUS_LOCKED = 0;

    /**
     * 用户状态正常
     */
    const USER_STATUS_OK = 1;

    /**
     * 用户账户被关闭
     */
    const USER_STATUS_CLOSED = 2;

    /**
     * 性别:男
     */
    const SEX_MAN = 0;

    /**
     * 性别:女
     */
    const SEX_WOMAN = 1;

    /**
     *
     * @var string
     */
    public $table = 'inf_player';

    /**
     *
     * @var integer
     */
    const CREATED_AT = 'created_at';

    /**
     *
     * @var integer
     */
    const UPDATED_AT = 'updated_at';

    /**
     *
     * @var integer
     */
    protected $primaryKey = 'player_id';

    /**
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pay_password',
        'password_wrong_times',
        'password_wrong_time'
    ];

    /**
     *
     * @var array
     */
    public $fillable = [
        'user_name',
        'wechat',
        'consignee',
        'sex',
        'delivery_address',
        'mobile',
        'real_name',
        'password',
        'pay_password',
        'email',
        'main_account_amount',
        'recommend_player_id',
        'login_ip',
        'carrier_id',
        'agent_id',
        'player_level_id',
        'user_status',
        'login_domain',
        'referral_code',
        'recommend_url',
        'qq_account',
        'birthday',
        'register_ip',
        'login_at',
        'remark',
        'mail_verification_code',
        'total_win_loss',
        'frozen_main_account_amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'player_id' => 'integer',
        'user_name' => 'string',
        'mobile' => 'string',
        'real_name' => 'string',
        'password' => 'string',
        'pay_password' => 'string',
        'email' => 'string',
        'consignee' => 'string',
        'sex' => 'integer',
        'delivery_address' => 'string',
        'score' => 'integer',
        'recommend_player_id' => 'integer',
        'login_ip' => 'string',
        'agent_id' => 'integer',
        'carrier_id' => 'integer',
        'player_level_id' => 'integer',
        'login_domain' => 'string',
        'qq_account' => 'string',
        'wechat' => 'string',
        'register_ip' => 'string',
        'remark' => 'string',
        'recommend_url' => 'string',
        'main_account_amount' => 'numeric',
        'frozen_main_account_amount' => 'numeric',
        'total_win_loss' => 'numeric',
        'mail_verification_code'
    ];

    /**
     *
     * @return mixed
     */
    public static function allPlayers()
    {
        return self::where('user_status', self::USER_STATUS_OK)->get(
            [
                'player_id',
                'user_name',
                'real_name'
            ]);
    }

    private static function CURLQueryString($url)
    {
        // 设置附加HTTP头
        $addHead = array(
            "Content-type:application/json"
        );
        // 初始化curl
        $curl_obj = curl_init();
        // 设置网址
        curl_setopt($curl_obj, CURLOPT_URL, $url);
        // 附加Head内容
        curl_setopt($curl_obj, CURLOPT_HTTPHEADER, $addHead);
        // 是否输出返回头信息
        curl_setopt($curl_obj, CURLOPT_HEADER, 0);
        // 将curl_exec的结果返回
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
        // 设置超时时间
        curl_setopt($curl_obj, CURLOPT_TIMEOUT, 8);
        // 执行
        $result = curl_exec($curl_obj);
        // 关闭curl回话
        curl_close($curl_obj);
        return $result;
    }

    // 处理返回结果
    private static function doWithResult($result, $field)
    {
        $result = json_decode($result, true);
        return isset($result[0][$field]) ? $result[0][$field] : '';
    }

    public static function getShortUrl($url)
    {
        $apiKey = '3271760578';
        $apiUrl = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . $apiKey . '&url_long=' . $url;
        $result = self::CURLQueryString($apiUrl);
        return self::doWithResult($result, 'url_short');
    }

    /**
     * 在线用户
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnline(Builder $query)
    {
        return $query->where('is_online', true);
    }

    public function scopeWasInvited(Builder $query)
    {
        return $query->where('recommend_player_id', '!=', null);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('user_status', self::USER_STATUS_OK);
    }

    public static function lastPlayerId()
    {
        return self::max('player_id');
    }

    public function scopeIdBetween(Builder $query, $idSmaller, $idLarger)
    {
        return $query->where('player_id', '>=', $idSmaller)->where('player_id', '<', $idLarger);
    }

    // 代理会员人数
    public function scopeAgentMemberSum(Builder $query, $agent_id)
    {
        return $query->where('agent_id', $agent_id);
    }

    // 代理新增会员人数
    public function scopeAgentNewMemberSum(Builder $query, $agent_id, $start_time, $end_time)
    {
        return $query->where('agent_id', $agent_id)->whereBetween('created_at',
            [
                $start_time,
                $end_time
            ]);
    }

    /**
     * 游戏账户账号, 运营商id + 代理商id + 'Z' + 用户账号
     *
     * @return string
     */
    public function gameAccountUserName()
    {
        // return $this->carrier_id.$this->user_name; //modify by tlt
        return $this->carrier_id . "Z" . $this->user_name; // add by tlt
    }

    /**
     *
     * @return bool
     * @throws \Exception
     */
    public function checkActive()
    {
        if ($this->user_status == self::USER_STATUS_CLOSED) {
            throw new PlayerAccountException('账户已关闭');
        }
        try {
            $this->carrier->checkActive();
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 用户是否被禁用
     *
     * @return bool
     */
    public function isActive()
    {
        try {
            $this->checkActive();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     *
     * @return bool
     * @throws \Exception
     */
    public function checkLocked()
    {
        try {
            $this->checkActive();
            if ($this->user_status == self::USER_STATUS_LOCKED) {
                throw new PlayerAccountException('账户被锁定');
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 用户账户是否被锁定
     *
     * @return bool
     */
    public function isLocked()
    {
        try {
            $this->checkLocked();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 生成邀请码
     *
     * @return string
     */
    public static function generateReferralCode()
    {
        $randStr = str_shuffle('abcdefghijklmnopqrstuvwxyz');
        $rand = 'm' . substr($randStr, 0, 5);
        $result = self::where('referral_code', $rand)->first();
        if ($result) {
            return self::generateReferralCode();
        }
        return $rand;
    }

    /**
     * 判断主账户如果减掉部分资金后是否够用
     *
     * @param float $amount
     * @return bool
     */
    public function isRemainAccountEnough($amount = 0.0)
    {
        return $this->main_account_amount - $amount >= 0;
    }

    /**
     *
     * @return array
     */
    public static function onlineMeta()
    {
        return [
            self::ONLINE_ON => '在线',
            self::ONLINE_OFF => '不在线'
        ];
    }

    /**
     *
     * @return array
     */
    public static function userSex()
    {
        return [
            self::SEX_MAN => '男',
            self::SEX_WOMAN => '女'
        ];
    }

    /**
     *
     * @return array
     */
    public static function userStatusMeta()
    {
        return [
            self::USER_STATUS_OK => '正常',
            self::USER_STATUS_LOCKED => '锁定',
            self::USER_STATUS_CLOSED => '关闭'
        ];
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_name' => 'required|min:4|max:11',
        'password' => 'required|min:6|max:16|confirm'
        // 'confirm_password' => 'required|min:6|max:16|same:password',
        // 'referral_code' => 'string:nullable',
    ];

    /**
     * 请求属性名称
     *
     * @var array
     */
    public static $requestAttributes = [
        'user_name' => '账户',
        'password' => '密码',
        'confirm_password' => '确认密码',
        'real_name' => '真实姓名',
        'mobile' => '手机号码',
        'email' => '邮箱',
        'sex' => '性别',
        'birthday' => '生日',
        'qq_account' => 'QQ号码',
        'wechat' => '微信',
        'consignee' => '收货人',
        'delivery_address' => '收货地址'
    ];

    /**
     * 所属代理
     *
     * @return mixed
     */
    public function agent()
    {
        return $this->belongsTo(CarrierAgentUser::class, 'agent_id', 'id');
    }

    /**
     * 所属会员等级
     *
     * @return mixed
     */
    public function playerLevel()
    {
        return $this->belongsTo(CarrierPlayerLevel::class, 'player_level_id', 'id');
    }

    /**
     * 拥有的银行卡
     *
     * @return mixed
     */
    public function bankCards()
    {
        return $this->hasMany(PlayerBankCard::class, 'player_id', 'player_id');
    }

    /**
     * 登录日志
     *
     * @return mixed
     */
    public function loginLogs()
    {
        return $this->hasMany(PlayerLoginLog::class, 'player_id', 'player_id');
    }

    /**
     * 游戏账户
     *
     * @return mixed
     */
    public function gameAccounts()
    {
        return $this->hasMany(PlayerGameAccount::class, 'player_id', 'player_id');
    }

    /**
     * 所属运营商
     *
     * @return mixed
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id', 'id');
    }

    /**
     * 被邀请会员
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitedPlayer()
    {
        return $this->belongsTo(Player::class, 'recommend_player_id', 'player_id');
    }

    /**
     * 邀请会员
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recommendPlayer()
    {
        return $this->hasMany(Player::class, 'recommend_player_id', 'player_id');
    }

    /**
     * 存款日志
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function depositLogs()
    {
        return $this->hasMany(PlayerDepositPayLog::class, 'player_id', 'player_id');
    }

    /**
     * 取款记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawLogs()
    {
        return $this->hasMany(PlayerWithdrawLog::class, 'player_id', 'player_id');
    }

    /**
     * 投注流水日志
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function betFlowLogs()
    {
        return $this->hasMany(PlayerBetFlowLog::class, 'player_id', 'player_id');
    }

    /**
     * 主账户日志
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountLogs()
    {
        return $this->hasMany(PlayerAccountLog::class, 'player_id', 'player_id');
    }

    /**
     * 运营商系统配置
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registerConf()
    {
        return $this->hasOne(CarrierDashLoginConf::class, 'carrier_id', 'carrier_id');
    }

    public function invitedRewardLog()
    {
        return $this->hasMany(PlayerInviteRewardLog::class, 'player_id', 'player_id');
    }

    public function wasInvitedRewardLog()
    {
        return $this->hasMany(PlayerInviteRewardLog::class, 'reward_related_player', 'player_id');
    }

    public function getIdAttribute()
    {
        return $this->player_id;
    }

    public function getZhuId()
    {
        return $this->player_id;
    }
}
