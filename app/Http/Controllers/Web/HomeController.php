<?php
namespace App\Http\Controllers\Web;

use App\Exceptions\PlayerAccountException;
use App\Helpers\IP\RealIpHelper;
use App\Http\Requests\Web\PlayerLoginRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Carrier;
use App\Models\CarrierActivity;
use App\Models\CarrierActivityType;
use App\Models\CarrierAgentDomain;
use App\Models\CarrierAgentUser;
use App\Models\CarrierBackUpDomain;
use App\Models\CarrierPlayerLevel;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Image\CarrierImage;
use App\Models\Map\CarrierGame;
use App\Models\Player;
use App\Models\Def\Game;
use App\Repositories\Member\PlayerRepository;
use App\Repositories\Web\PlayerLoginRepository;
use App\Http\Requests\Web\CreatePlayerRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Conf\PlayerRegisterConf;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;
use Illuminate\Support\Facades\Mail;
use App\Models\CarrierActivityAudit;

class HomeController extends AppBaseController
{

    private $playerLoginRepository;

    public $carrierWebConf;

    public function __construct(PlayerLoginRepository $playerLoginRepository)
    {
        $this->playerLoginRepository = $playerLoginRepository;
        $conf = CarrierWebSiteConf::first();
        if (! $conf) {
            $conf = new CarrierWebSiteConf();
        }
        $this->carrierWebConf = $conf;
    }

    /**
     * 首界面
     *
     * @return \View
     */
    public function index()
    {
        if ($this->isMobile()) {
            $games = CarrierGame::where('map_carrier_games.status', 1)->with('game')
                ->whereHas('game',
                function ($query) {
                    
                    $query->where('def_games.status', 1)
                        ->where('is_wap', 1)
                        ->where('is_recommend', 1);
                })
                ->limit(20)
                ->get();
            $games->map(
                function (CarrierGame $carrierGame) {
                    if (\WinwinAuth::memberUser()) {
                        $carrierGame->collect_info = $carrierGame->collect($carrierGame->id,
                            \WinwinAuth::memberUser()->player_id);
                    } else {
                        $carrierGame->collect_info = 0;
                    }
                });
            // 首页轮播图
            $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
                function ($query) {
                    $query->where('category_name', '移动端首页');
                })
                ->get();
            return \WTemplate::homePage('m')->with(compact('images', 'games'));
        } else {
            // 首页轮播图
            $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
                function ($query) {
                    $query->where('category_name', '首页');
                })
                ->get();
            return \WTemplate::homePage()->with(compact('images'));
        }
    }

    /**
     * 是否登录弹窗返回json
     *
     * @return \View
     */
    public function isonline()
    {
        $memberUser = \WinwinAuth::memberUser();
        if (is_null($memberUser)) {
            return $this->sendErrorResponse('用户未登录', 404);
        } else {
            return $this->sendSuccessResponse('用户已登录');
        }
    }

    /**
     * 注册
     * @retrun \View
     */
    public function moblieRegister()
    {
        return \WTemplate::moblieRegister();
    }

    /**
     * 登录
     * @retrun \View
     */
    public function mobileLogin()
    {
        return \WTemplate::moblieLogin();
    }

    /**
     * 手机忘记密码发送验证码
     * @retrun \View
     */
    public function moblieForget(Request $request)
    {
        $emailConf = CarrierPasswordRecoverySiteConf::first();
        if (empty($emailConf) || empty($emailConf->is_open_email_send_function)) {
            return $this->sendErrorResponse('对不起，该运营商未开启邮箱验证功能', 500);
        }
        
        $exist = $request->input('exist', 0); // 邮箱存在判断
        $email = $request->input('email', 0); // 邮箱账号
        $info = $request->input('info', 'player'); // 忘记密码者类型
        $type = $request->input('type', 'forget_url'); // 信件类型链接模板
        
        if (empty($email)) {
            return $this->sendErrorResponse('邮箱不能为空', 500);
        }
        
        if ($info == 'player') {
            $playerInfo = Player::where('email', $email)->first();
        } elseif ($info == 'agent') {
            $playerInfo = CarrierAgentUser::where('email', $email)->first();
        }
        if ($exist) {
            if (empty($playerInfo)) {
                return $this->sendErrorResponse('邮箱不存在', 500);
            }
        }
        
        try {
            $v_code = bcrypt($playerInfo->getZhuId() . $email);
            $verifiCode = url('/homes.mobileForgetVerify') . '?forget_code=' . $v_code;
            // 邮件配置
            $backup = Mail::getSwiftMailer();
            $transport = \Swift_SmtpTransport::newInstance($emailConf->smtp_server, $emailConf->smtp_service_port,
                $emailConf->smtp_encryption);
            $transport->setUsername($emailConf->smtp_username);
            $transport->setPassword($emailConf->smtp_password);
            $gmail = new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
            // 发送邮件
            Mail::send('email.' . $type, [
                'code' => $verifiCode
            ],
                function ($message) use ($email, $emailConf) {
                    $message->from($emailConf->smtp_username, $emailConf->mail_sender)
                        ->to($email)
                        ->subject('邮箱验证');
                });
            // 重置原邮件配置
            Mail::setSwiftMailer($backup);
            
            // 保存邮件验证码
            $redis = Redis::connection();
            $redis->set($v_code . 'code', $email);
            $redis->expire($v_code . 'code', 300);
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            \WLog::error('======>邮件发送失败:' . $e->getMessage());
        }
    }

    /**
     * 忘记密码成功页面
     * @retrun \View
     */
    public function mobileForgetPage(Request $request)
    {
        $email = $request->input('email', 0); // 邮箱账号
        return view('Web.mobile.login_registers.forget', compact('email'));
    }

    /**
     * 校验忘记密码
     * @retrun \View
     */
    public function mobileForgetVerify(Request $request)
    {
        $v_code = $request->input('forget_code', '');
        if ($request->ajax()) {
            $password = $request->input('password', '');
            $info = $request->input('info', 'player');
            // 从redis 中查询游戏验证码
            $redis = Redis::connection();
            $email = $redis->get($v_code . 'code');
            if (empty($email)) { // 邮箱验证码不正确
                return $this->sendErrorResponse('校验失败，参数错误', 500);
            }
            $data['password'] = bcrypt($password);
            // 保存邮件验证码
            if ($info == 'player') {
                $update = Player::where('email', $email)->update($data);
            } elseif ($info == 'agent') {
                $update = CarrierAgentUser::where('email', $email)->update($data);
            }
            if ($update == false) {
                return $this->sendErrorResponse('修改密码失败，请重试', 500);
            }
            // 清空redis中的验证码
            $redis->set($v_code . 'code', null);
            return $this->sendSuccessResponse();
        }
        return view('Web.mobile.login_registers.forget_reset', compact('v_code'));
    }

    /**
     * 联系客服,关于我们
     * @retrun \View
     */
    public function contactCustomer(Request $request)
    {
        $type = $request->input('type');
        $head = '';
        $array = [
            'contact',
            'about',
            'common',
            'duty'
        ];
        if (! in_array($type, $array)) {
            abort(404);
        }
        if ($type == 'contact') {
            $head = '联系我们';
            $webConf = $this->carrierWebConf->mobile_contact();
        } elseif ($type == 'about') {
            $head = '关于我们';
            $webConf = $this->carrierWebConf->mobile_about();
        }
        
        if (! $this->isMobile()) {
            if ($request->input('type') == 'about') {
                
                $webConf = $this->carrierWebConf->about_us();
                return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.contactCustomer',
                    compact('webConf'));
            } else if ($request->input('type') == 'common') {
                $webConf = $this->carrierWebConf->common_question();
                return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.commonCustomer', compact('webConf'));
            } else if ($request->input('type') == 'duty') {
                $webConf = $this->carrierWebConf->duty();
                return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.duty', compact('webConf'));
            }
        } else {
            return view('Web.mobile.about_us.contact', compact('webConf', 'head'));
        }
    }

    /**
     * 登录处理
     */
    public function login(PlayerLoginRequest $request)
    {
        // TODO 登录失败锁定逻辑未完成 验证码缺少 限频处理
        // if(\App::environment() != 'local'){
        // if(!\Captcha::check($request->get('loginVericode'))){
        // return $this->sendErrorResponse(['fields'=>'loginVericode', 'message'=>'验证码输入错误'],403);
        // }
        // }
        if ($request->has('TxloginVericode')) {
            if (! \Captcha::check($request->input('TxloginVericode'))) {
                return $this->sendErrorResponse('验证码输入错误', 403);
            }
        }
        $user_name = $request->get('user_name');
        $player = $this->playerLoginRepository->findWhere([
            'user_name' => $user_name
        ], [
            '*'
        ])->first();
        if ($player) {
            try {
                $player->checkLocked() && $player->carrier->checkIsAllowUserLogin();
                if (\Hash::check($request->get('password'), $player->password) == true) {
                    \WinwinAuth::memberAuth()->loginUsingId($player->player_id);
                    $this->playerLoginRepository->update(
                        [
                            'login_at' => Carbon::now()
                        ], $player->player_id);
                    return $this->sendSuccessResponse(route('players.account-security'));
                } else {
                    return $this->sendErrorResponse('账户或密码错误', 403);
                }
            } catch (PlayerAccountException $e) {
                return $this->sendErrorResponse($e->getMessage(), 403);
            } catch (\Exception $e) {
                \WLog::error('用户登录失败: error' . $e->getMessage());
                return $this->sendErrorResponse('系统错误', 500);
            }
        } else {
            return $this->sendErrorResponse('账户错误或不存在', 404);
        }
    }

    /**
     * 注册界面
     *
     * @param $request
     * @return \View
     */
    public function registerPage(Request $request)
    {
        $recommend_code = $request->get('recommend_code', '');
        $registerConf = PlayerRegisterConf::where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        $playerAttr = [];
        // 真实姓名
        if ($registerConf->player_realname_conf_status) {
            $playerAttr['real_name'] = 'real_name';
        }
        
        // 出生日期
        if ($registerConf->player_birthday_conf_status) {
            $playerAttr['birthday'] = 'birthday';
        }
        // email
        if ($registerConf->player_email_conf_status) {
            $playerAttr['email'] = 'email';
        }
        // 手机号
        if ($registerConf->player_phone_conf_status) {
            $playerAttr['mobile'] = 'mobile';
        }
        // qq号
        if ($registerConf->player_qq_conf_status) {
            $playerAttr['qq'] = 'qq';
        }
        // 微信
        if ($registerConf->player_wechat_conf_status) {
            $playerAttr['wechat'] = 'wechat';
        }
        /*
         * $playerExtenAttr = [
         * 'referral_code' => 'referral_code',
         * 'verification_code' => 'verification_code'
         * ];
         */
        // $playerAttr = array_merge($playerAttr, $playerExtenAttr);
        return \WTemplate::registerPage()->with(
            [
                'recommend_code' => $recommend_code,
                'conf' => $registerConf,
                'playerAttr' => $playerAttr
            ]);
    }

    /**
     * 注册处理
     *
     * @param CreatePlayer
     * @return \Response
     */
    // CreatePlayerRequest $request
    public function register(CreatePlayerRequest $request, PlayerRepository $playerRepository)
    {
        if (! $this->isMobile()) {
            if (empty($request->get('recommend_player_id'))) {
                if (! \Captcha::check($request->get('verification_code'))) {
                    return $this->sendErrorResponse(
                        [
                            'fields' => 'verification_code',
                            'message' => '验证码输入错误'
                        ], 403);
                }
            }
        }
        // 处理邀请相关referral_code
        
        $input = $request->all();
        if (\WinwinAuth::currentWebCarrier()->dashLoginConf->is_allow_player_register == 0) {
            return $this->sendErrorResponse('禁止注册', 403);
        }
        
        // 获取当前网址,判断是否为代理域名
        $webUrl = $request->header('host');
        // $agentDomain = CarrierAgentDomain::with('agent.agentLevel')->where('website', $webUrl)->first();
        // $carrier = Carrier::retrieveBySiteUrl($webUrl)->first();
        // $carrierDomain = CarrierBackUpDomain::retrieveByDomainName($webUrl)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        $agent = \WinwinAuth::currentWebAgent();
        $defaultAgent = CarrierAgentUser::with('agentLevel')->where('is_default', 1)->first();
        $defaultPlayerLevel = CarrierPlayerLevel::isDefault()->first();
        if ($defaultAgent) {
            $input['agent_id'] = $defaultAgent->id;
            $input['player_level_id'] = $defaultPlayerLevel->id;
        } else {
            $defaultPlayerLevel && $input['player_level_id'] = $defaultPlayerLevel->id;
        }
        if ($agent && empty($carrier)) {
            $input['agent_id'] = $agent->id;
            $input['player_level_id'] = $agent->agentLevel->default_player_level;
        } else {
            if (! empty($input['referral_code'])) {
                // 代理邀请码
                $promotionAgent = CarrierAgentUser::with('agentLevel')->active()
                    ->where('promotion_code', $input['referral_code'])
                    ->first();
                if (! is_null($promotionAgent) && $promotionAgent->id) {
                    $input['agent_id'] = $promotionAgent->id;
                    $input['player_level_id'] = $promotionAgent->agentLevel->id;
                }
                // 判断是否有邀请码,是则根据邀请码获得推荐会员ID
                $promotionPlayer = Player::active()->where('referral_code', $input['referral_code'])->first();
                if (! is_null($promotionPlayer)) {
                    $input['recommend_player_id'] = $promotionPlayer->id;
                }
            }
        }
        
        // 生成随机邀请码
        $input['referral_code'] = Player::generateReferralCode();
        
        // 拼凑长链接,转换为短链接
        $requestUrl = $request->header('origin');
        $recommendUrl = $requestUrl . '/homes.registerPage' . '?recommend_code=' . $input['referral_code'];
        $shortened = Player::getShortUrl($recommendUrl);
        
        // 玩家推荐短链接
        $input['recommend_url'] = $shortened;
        
        // 默认取款密码000000
        $input['pay_password'] = bcrypt('000000');
        
        $input['password'] = bcrypt($request->get('password'));
        
        $input['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
        // 判断是否是推荐用户开户
        if ($request->get('recommend_player_id')) {
            $input['recommend_player_id'] = $request->get('recommend_player_id');
            $this->playerLoginRepository->create($input);
            return $this->sendSuccessResponse();
        }
        try {
            $input['register_ip'] = RealIpHelper::getIp();
            //
            $player = $this->playerLoginRepository->create($input);
            \WinwinAuth::memberAuth()->loginUsingId($player->player_id);
            $this->playerLoginRepository->update([
                'login_at' => Carbon::now()
            ], $player->player_id);
            return $this->sendSuccessResponse(route('players.account-security'));
        } catch (\Exception $e) {
            \WLog::error('会员注册失败:' . $e->getMessage() . ' ' . $e->getFile());
            return $this->sendErrorResponse('注册失败', 403);
        }
    }

    /**
     * 验证码
     *
     * @return \Response
     */
    public function captcha()
    {
        return $this->sendResponse(\Captcha::img(''));
    }

    /*
     * 忘记密码界面
     * @return \View
     */
    public function forgetPassword()
    {
        return \WTemplate::forgetPasswordPage();
    }

    /**
     * **
     * 生成随机四位数字验证码
     * add by tlt
     */
    public function create_rand($length = 4)
    {
        $code = '';
        // 随机生成4位数字字符串
        $pattern = '1234567890';
        for ($i = 0; $i < $length; $i ++) {
            $code .= $pattern{mt_rand(0, 9)};
        }
        return $code;
    }

    /**
     * *
     * 发送邮箱验证码
     * add by tlt
     */
    public function sendEmailVerificode(Request $request)
    {
        $emailConf = CarrierPasswordRecoverySiteConf::first();
        if (empty($emailConf) || empty($emailConf->is_open_email_send_function) || empty($emailConf->smtp_username)) {
            return $this->sendErrorResponse('对不起，该运营商未开启邮箱验证功能，请联系运营商', 500);
        }
        
        $verifiCode = $this->create_rand(4); // 生成四位随机验证码
        $email = $request->input('email', ''); // 收件人邮箱
        $type = $request->input('type', 'verification_code'); // 信件类型
        $info = $request->input('info', 'player'); // 忘记密码者类型
        if ($info == 'player') {
            $payerInfo = Player::where('email', $email)->first();
        } elseif ($info == 'agent') {
            $payerInfo = CarrierAgentUser::where('email', $email)->first();
        }
        if (empty($email)) {
            return $this->sendErrorResponse('邮箱不能为空', 500);
        }
        if ($request->input('yanzheng') == 'yanzheng') {
            if (empty($payerInfo)) {
                return $this->sendErrorResponse('邮箱不存在', 500);
            }
        }
        if ($request->input('yanzheng') == 'yanzhengcui') {
            if (! empty($payerInfo)) {
                return $this->sendErrorResponse('邮箱已存在', 500);
            }
        }
        try {
            // 邮件配置
            $backup = Mail::getSwiftMailer();
            $transport = \Swift_SmtpTransport::newInstance($emailConf->smtp_server, $emailConf->smtp_service_port,
                $emailConf->smtp_encryption);
            $transport->setUsername($emailConf->smtp_username);
            $transport->setPassword($emailConf->smtp_password);
            $gmail = new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
            // 发送邮件
            Mail::send('email.' . $type, [
                'code' => $verifiCode
            ],
                function ($message) use ($email, $emailConf) {
                    $message->from($emailConf->smtp_username, $emailConf->mail_sender)
                        ->to($email)
                        ->subject('邮箱验证码');
                });
            // 重置原邮件配置
            Mail::setSwiftMailer($backup);
            
            // 保存邮件验证码
            $redis = Redis::connection();
            $redis->set($email . 'code', $verifiCode);
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            \WLog::error('======>邮件发送失败:' . $e->getMessage());
        }
    }

    /**
     * *
     * 邮箱验证码修改密码
     * add by tlt
     */
    public function modifyPassword(Request $request)
    {
        $email = $request->input('email', '');
        $verification_code = $request->input('code', '');
        $password = $request->input('password', '');
        $info = $request->input('info', 'player'); // 忘记密码者类型
                                                   // 从redis 中查询游戏验证码
        $redis = Redis::connection();
        $code = $redis->get($email . 'code');
        if ($code != $verification_code) { // 邮箱验证码不正确
            return $this->sendErrorResponse('邮箱验证码错误', 500);
        }
        // 清空redis中的验证码
        $data['password'] = bcrypt($password);
        // 保存邮件验证码
        if ($info == 'player') {
            $update = Player::where('email', $email)->update($data);
        } elseif ($info == 'agent') {
            $update = CarrierAgentUser::where('email', $email)->update($data);
        }
        if ($update == false) {
            return $this->sendErrorResponse('更新密码失败，请重试', 500);
        }
        $redis->set($email . 'code', null);
        return $this->sendSuccessResponse();
    }

    /**
     * 真人娱乐界面
     *
     * @return \View
     */
    public function liveEntertainment()
    {
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '真人娱乐');
            })
            ->get();
        return \WTemplate::liveEntertainmentGamePage()->with(compact('images'));
    }

    /**
     * 老虎机界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    /*
     * public function slotMachine()
     * {
     * return \WTemplate::slotMachinePage();
     * }
     */
    
    /**
     * AG捕鱼界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function agFish()
    {
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '捕鱼游戏');
            })
            ->get();
        return \WTemplate::agFishPage()->with(compact('images'));
    }

    /**
     * 体育投注界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sportsGames()
    {
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '体育投注');
            })
            ->get();
        return \WTemplate::sportsGamesPage()->with(compact('images'));
    }

    /**
     * 彩票投注界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lotteryBetting()
    {
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '彩票投注');
            })
            ->get();
        return \WTemplate::lotteryBettingPage()->with(compact('images'));
    }

    /**
     * 手机版界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mobile()
    {
        return \WTemplate::mobilePage();
    }

    /**
     * 优惠活动详情页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mobileSpecialOffer($id)
    {
        $id = intval($id);
        $activity = CarrierActivity::with("actType", 'imageInfo')->where('is_website_display', 1)
            ->where('status', 1)
            ->where('id', $id)
            ->where('carrier_id', \WinwinAuth::currentWebCarrier()->id)
            ->first();
        
        return \WTemplate::mobileActivityDesc()->with(compact('activity'));
    }

    /**
     * 优惠活动界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function specialOffer(Request $request)
    {
        $type = $request->get('type', '');
        $actType = CarrierActivityType::where(
            [
                'carrier_id' => \WinwinAuth::currentWebCarrier()->id,
                'status' => 1
            ])->get();
        $str = '';
        foreach ($actType as $key => $value) {
            $str .= "'" . trim($value->type_name) . "',";
        }
        $activate = array();
        foreach ($actType as $value) {
            $activate[trim($value->type_name)] = $value->id;
        }
        $activate = json_encode($activate, JSON_UNESCAPED_UNICODE);
        $str = rtrim($str, ',');
        $activityList = CarrierActivity::with("actType", 'imageInfo')->where('is_website_display', 1)
            ->where('status', 1)
            ->where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->orderBy('sort', 'asc');
        if ($type) {
            $activityList = $activityList->where('act_type_id', $type);
        }
        $activityList = $activityList->get();
        
        $activityList->each(
            function (CarrierActivity $activity) {
                if (! empty(\WinwinAuth::memberUser())) {
                    $isJoined = CarrierActivityAudit::where('player_id', \WinwinAuth::memberUser()->player_id)->where(
                        'act_id', $activity->id)
                        ->orderBy('updated_at', 'desc')
                        ->first();
                    if ($isJoined && $isJoined->status == 1) {
                        $activity->canJoin = false;
                        $activity->isApply = true;
                        $activity->waiting = true;
                        return;
                    }
                }
                $maxJoinTimes = $activity->apply_times;
                if ($maxJoinTimes != CarrierActivity::APPLY_TIMES_INFINITE) {
                    $activityAuditBuilder = CarrierActivityAudit::hasAudited()->byActivity($activity->id);
                    $activityApplyTimes = 0;
                    if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_EVERYDAY_ONCE) {
                        $activityAuditBuilder = $activityAuditBuilder->joinedToday();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_MONTHLY_ONCE) {
                        $activityAuditBuilder = $activityAuditBuilder->joinedThisMonth();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_WEEKLY_ONCE) {
                        $activityAuditBuilder = $activityAuditBuilder->joinedThisWeek();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_PERMANENT_ONCE) {
                        // $activityApplyTimes = $activityAuditBuilder->count();
                    }
                    if (! empty(\WinwinAuth::memberUser())) {
                        $activityAuditBuilder = $activityAuditBuilder->where('player_id',
                            \WinwinAuth::memberUser()->player_id);
                    }
                    $activityApplyTimes = $activityAuditBuilder->count();
                    if ($activityApplyTimes >= 1) {
                        $activity->canJoin = false;
                        $activity->isApply = true;
                        $activity->waiting = false;
                        return;
                    }
                }
                $activity->canJoin = true;
                $activity->isApply = false;
                $activity->waiting = false;
            });
        
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory',
            function ($query) {
                $query->where('category_name', '优惠活动');
            })
            ->get();
        if ($request->ajax()) {
            
            if ($this->isMobile()) {
                return \WTemplate::benefitActivityList('m')->with(compact('activityList'));
                ;
            } else {
                return \WTemplate::benefitActivityList()->with(compact('activityList'));
            }
        }
        if ($this->isMobile()) {
            return \WTemplate::benefitActivityPage('m')->with(
                compact('actType', 'images', 'activityList', 'str', 'activate'));
        } else {
            return \WTemplate::benefitActivityPage()->with(compact('actType', 'images', 'activityList'));
        }
    }

    /**
     * 关于我们界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aboutUs()
    {
        return \WTemplate::aboutUsPage();
    }

    /**
     * 联系我们界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contactUs()
    {
        return \WTemplate::contactUsPage();
    }

    /**
     * VIP制度界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function vipSystem(\Request $request)
    {
        return \WTemplate::vipSystemPage();
    }

    /**
     * 常见问题界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FAQ()
    {
        return \WTemplate::questionAndAnswerPage();
    }

    /**
     * 隐私保护界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacyProtection()
    {
        return \WTemplate::privacyProtectionPage();
    }

    /**
     * 博彩责任界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gamblingResponsibility()
    {
        return \WTemplate::gamblingResponsibilityPage();
    }

    /**
     * 服务责任界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function termsOfService()
    {
        return \WTemplate::termsOfServicePage();
    }

    /**
     * 牌照展示界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function licenseDisplay()
    {
        return \WTemplate::licenseDisplayPage();
    }

    /**
     * *
     * 绑定邮箱
     * add by tlt
     */
    public function bindEmail(Request $request)
    {
        $url = $request->input('bindEmail', '');
        // 从redis 中查询游戏验证码
        $redis = Redis::connection();
        $info = $redis->get($url);
        $info = explode('-', $info);
        if (! $url || ! in_array($info[3], [
            'player',
            'agent'
        ])) {
            abort(404);
        }
        $home = '/';
        $center = '/players.account-security';
        if ($info[3] == 'agent') {
            $home = '/agents.index';
            $center = '/agent/admin/agentAccountCenters';
        }
        if (empty($info) || empty($url)) {
            return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.status.bindEmailError',
                compact('home', 'center'));
        }
        if ($info[3] == 'player') {
            
            $playerInfo = Player::where('email', $info[2])->first();
        } else {
            $playerInfo = CarrierAgentUser::where('email', $info[2])->first();
        }
        
        if (! empty($playerInfo)) {
            return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.status.bindEmailError',
                compact('home', 'center'));
        }
        if ($info[3] == 'player') {
            $playerInfo = Player::where('user_name', $info[0])->where('carrier_id', $info[1])->first();
        } else {
            $playerInfo = CarrierAgentUser::where('username', $info[0])->where('carrier_id', $info[1])->first();
        }
        
        if (empty($playerInfo)) { // 邮箱验证码不正确
            return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.status.bindEmailError',
                compact('home', 'center'));
        }
        // 清空redis中的验证码
        try {
            $playerInfo->email = $info[2];
            $playerInfo->save();
        } catch (\Exception $e) {
            \Wlog::error('绑定邮箱失败',
                [
                    'user_name' => $info[0],
                    'error' => $e->getMessage()
                ]);
            return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.status.bindEmailError',
                compact('home', 'center'));
        }
        
        $redis->set($url, null);
        return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.status.bindEmailSuccess',
            compact('home', 'center'));
    }
}
