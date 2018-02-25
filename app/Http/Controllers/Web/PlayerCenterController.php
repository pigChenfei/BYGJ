<?php
namespace App\Http\Controllers\Web;

use App\Helpers\IP\RealIpHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Web\UpdatePlayerRequest;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use App\Models\Conf\CarrierWithdrawConf;
use App\Models\Def\BankType;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\PlayerBankCard;
use App\Models\PlayerNews\PlayerNewsRelation;
use App\Scopes\PlayerScope;
use App\Vendor\GameGateway\PT\PTGameGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\CarrierPlayerNews;
use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;
use App\Models\Def\GamePlat;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Log\PlayerInviteRewardLog;
use App\Models\CarrierPayChannel;
use App\Models\CarrierPlayerLevel;
use App\Models\Def\PayChannelType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class PlayerCenterController extends AppBaseController
{

    /**
     * 会员中心
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accountSecurity()
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        
        // 根据会员ID获得会员游戏平台账号表数据和其对应的主平台
        $playerAccounts = Player::with('gameAccounts.mainGamePlat')->find($player_id);
        if (empty($playerAccounts)) {
            return $this->renderNotFoundPage();
        }
        // 过滤掉其他平台,只获得PT
        $gameAccount = $playerAccounts->gameAccounts->filter(
            function ($element) {
                return $element->mainGamePlat->main_game_plat_code == PTGameGateway::getMainGamePlatCode();
            })->first();
        
        $player = Player::with('registerConf')->where('player_id', $player_id)->first();
        
        if ($this->isMobile()) {
            return \WTemplate::accountSecurity('m')->with(
                [
                    'player' => $player,
                    'gameAccount' => $gameAccount
                ]);
        } else {
            return \WTemplate::accountSecurity()->with(
                [
                    'player' => $player,
                    'gameAccount' => $gameAccount
                ]);
        }
    }

    public function accountInfo(Request $request)
    {
        $player = \WinwinAuth::memberUser();
        return \WTemplate::accountInfo()->with(compact('player'));
    }

    /**
     * 完善个人信息
     *
     * @param Request $request
     */
    public function perfectUserInformation(UpdatePlayerRequest $request)
    {
        $player = Player::where('player_id', $request->get('player_id'))->first();
        // $redis = Redis::connection();
        if ($player) {
            // if ($request->has('code')){
            // $code = $redis->get($request->get('email') . 'code');
            // if ($code != $request->input('code')) { // 邮箱验证码不正确
            // return $this->sendErrorResponse(['field'=>'code','message'=>'邮箱验证码错误'], 500);
            // }
            // }
            if (empty($player->real_name)) {
                $player->real_name = $request->get('real_name');
            }
            // if (empty($player->email)){
            // $player->email = $request->get('email');
            // }
            if (empty($player->mobile)) {
                $player->mobile = $request->get('mobile');
            }
            
            if (empty($player->qq_account)) {
                $player->qq_account = $request->get('qq_account');
            }
            
            if (empty($player->wechat)) {
                $player->wechat = $request->get('wechat_account');
            }
            
            if ($request->get('sex')) {
                $player->sex = $request->get('sex') == '男' ? 0 : 1;
            }
            $player->birthday = $request->get('birthday');
            /*
             * $player->qq_account = $request->get('qq_account');
             * $player->wechat = $request->get('wechat');
             * $player->consignee= $request->get('consignee');
             * $player->delivery_address = $request->get('delivery_address');
             */
            $result = $player->save();
            if ($result) {
                // $redis->set($request->get('email') . 'code', null);
                return $this->sendSuccessResponse();
            } else {
                return $this->sendErrorResponse('信息保存失败', 404);
            }
        } else {
            return $this->sendErrorResponse('当前用户不存在', 404);
        }
    }

    /**
     * 财务中心
     *
     * @return \View
     */
    public function financeCenter()
    {
        return \WTemplate::financeCenter();
    }

    /**
     * 会员余额取款
     */
    public function balance()
    {
        // 查询玩家完成流水数据,并检查是否完成流水
        $complete = 0;
        $unfinished = 0;
        $withDrawFlowRecords = PlayerWithdrawFlowLimitLog::unfinished()->with('limitGamePlats')->get();
        if (head($withDrawFlowRecords)) {
            $withDrawFlowRecordCounts = PlayerWithdrawFlowLimitLog::unfinished()->count();
            if ($withDrawFlowRecordCounts > 0) {
                $complete = 0;
                $unfinished = 0;
                foreach ($withDrawFlowRecords as $item) {
                    $complete += ($item->complete_limit_amount);
                    $unfinished += ($item->limit_amount - $item->complete_limit_amount);
                }
            }
        }
        return \WTemplate::balance()->with(
            [
                'complete' => $complete,
                'unfinished' => $unfinished
            ]);
    }

    /**
     * 会员取款
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdrawMoney(Request $request)
    {
        // 当前运营商取款设置
        $carrierWithdrawConf = CarrierWithdrawConf::where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        // 查找默认银行取款渠道
        $banks = BankType::all();
        // 查询当前玩家的取款账号
        $player_id = \WinwinAuth::memberUser()->player_id;
        // 查询玩家完成流水数据,并检查是否完成流水
        $prompt_messages = '';
        $unfinished = 0;
        $complete = PlayerWithdrawFlowLimitLog::finished()->with('limitGamePlats')->sum('limit_amount') ?? 0;
        $withDrawFlowRecords = PlayerWithdrawFlowLimitLog::unfinished()->with('limitGamePlats')->get();
        if (head($withDrawFlowRecords)) {
            $withDrawFlowRecordCounts = PlayerWithdrawFlowLimitLog::unfinished()->count();
            if ($withDrawFlowRecordCounts > 0) {
                foreach ($withDrawFlowRecords as $item) {
                    $complete += ($item->complete_limit_amount);
                    $unfinished += ($item->limit_amount - $item->complete_limit_amount);
                }
                $prompt_messages .= ' 完成流水:' . $complete . '   未完成流水:' . $unfinished;
            } else {
                $prompt_messages = '已完成流水';
            }
        } else {
            $prompt_messages = '当前无流水数据';
        }
        
        // 当前玩家所有的取款银行卡
        $playerBankCards = PlayerBankCard::with('bankType')->get();
        // 处理银行卡号 *
        foreach ($playerBankCards as $playerBankCard) {
            if ($playerBankCard->card_account) {
                $playerBankCard->card_account = PlayerBankCard::replaceStar($playerBankCard->card_account, 4, 11);
            }
        }
        
        if ($this->isMobile()) {
            $str = '';
            $arr = array();
            foreach ($playerBankCards as $playerBankCard) {
                $str .= "'" . $playerBankCard->bankType->bank_name . "(" . substr($playerBankCard->card_account, - 4) .
                     ")',";
                $arr[$playerBankCard->card_id] = $playerBankCard->bankType->bank_name . "(" .
                     substr($playerBankCard->card_account, - 4) . ")";
            }
            $str = rtrim($str, ',');
            return \WTemplate::withdrawMoneyPage('m')->with(
                [
                    'banks' => $banks,
                    'playerBankCards' => $playerBankCards,
                    'carrierWithdrawConf' => $carrierWithdrawConf,
                    'withDrawFlowRecords' => $withDrawFlowRecords,
                    'prompt_messages' => $prompt_messages,
                    'complete' => $complete,
                    'unfinished' => $unfinished,
                    'str' => $str,
                    'arr' => json_encode($arr)
                ]);
        } else {
            return \WTemplate::withdrawMoneyPage()->with(
                [
                    'banks' => $banks,
                    'playerBankCards' => $playerBankCards,
                    'carrierWithdrawConf' => $carrierWithdrawConf,
                    'withDrawFlowRecords' => $withDrawFlowRecords,
                    'prompt_messages' => $prompt_messages,
                    'complete' => $complete,
                    'unfinished' => $unfinished
                ]);
        }
    }

    /* 根据站内信id删除站内信 add by tlt */
    public function delSms(Request $request)
    {
        $sms_id = $request->input('sms_id', '');
        if (is_array($sms_id)) { // 批量删除站内信
            \DB::beginTransaction();
            foreach ($sms_id as $id) {
                $del = PlayerNewsRelation::where('id', $id)->where('player_id', \WinwinAuth::memberUser()->id)->update(
                    [
                        'player_delete_status' => 1
                    ]); // state 0:正常 ；1：删除
                if ($del === false) { // 其中一条删除失败
                    \DB::rollBack();
                    return $this->sendErrorResponse('删除失败，请重试');
                }
            }
            \DB::commit();
            return $this->sendResponse('删除成功');
        } else { // 单个删除站内信
            $del = PlayerNewsRelation::where('id', $sms_id)->where('player_id', \WinwinAuth::memberUser()->id)->update(
                [
                    'player_delete_status' => 1
                ]); // state 0:正常 ；1：删除
            if ($del === false) { // 删除文件失败
                return $this->sendErrorResponse('删除失败，请重试');
            }
            return $this->sendResponse('删除成功');
        }
    }

    /* 手机版推荐好友 */
    public function mobilefriends()
    {
        return \WTemplate::mobilefriends();
    }

    /* 根据站内信id读取站内信 add by tlt */
    public function readSms(Request $request)
    {
        $sms_id = $request->input('sms_id', '');
        if (empty($sms_id)) {
            return $this->sendResponse('找不到该条站内信');
        }
        $read = PlayerNewsRelation::with('carrierPlayerNews')->where('player_delete_status', 0)
            ->where('player_id', \WinwinAuth::memberUser()->id)
            ->find($sms_id);
        if ($read['player_view_status'] == 0) { // 表示未读
            $read->update([
                'player_view_status' => 1
            ]);
        }
        return $this->sendResponse($read);
    }

    /**
     * 站内信
     *
     * @param Request $request
     * @return \View
     */
    public function smsSubscriptions()
    {
        $stationLetterList = PlayerNewsRelation::with('carrierPlayerNews')->where('player_delete_status', 0)
            ->where('carrier_id', \WinwinAuth::currentWebCarrier()->id)
            ->where('player_id', \WinwinAuth::memberUser()->id)
            ->orderBy('player_view_status', 'asc')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $str = "[";
        foreach ($stationLetterList as $v) {
            $statue = empty($v->player_view_status) ? 'true' : 'false';
            $str .= "{title:'" . $v->carrierPlayerNews->title . "',isnew:" . $statue . ",time:'" . $v->created_at .
                 "',content:'" . $v->carrierPlayerNews->remark . "',id:" . $v->id . "},";
        }
        $str = rtrim($str, ',') . ']';
        
        if ($this->isMobile()) {
            return \WTemplate::smsSubscriptions('m')->with('stationLetterList', $stationLetterList)->with('str', $str);
        } else {
            return \WTemplate::smsSubscriptions()->with('stationLetterList', $stationLetterList);
        }
    }

    /**
     * 申请优惠
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applyForDiscount()
    {
        $activityList = CarrierActivity::where('status', CarrierActivity::STATUS_SHELVES)->where('is_website_display',
            CarrierActivity::WEBSITE_DISPLAY_IS)
            ->with(
            [
                'activityAudit' => function ($query) {
                    $query->waitingAudit();
                }
            ])
            ->with('imageInfo')
            ->get();
        // 获取玩家已经参与的活动
        $activityList->each(
            function (CarrierActivity $activity) {
                if ($activity->activityAudit) {
                    $activity->canJoin = FALSE;
                    return;
                }
                $maxJoinTimes = $activity->apply_times;
                if ($maxJoinTimes != CarrierActivity::APPLY_TIMES_INFINITE) {
                    $activityAuditBuilder = CarrierActivityAudit::hasAudited()->byActivity($activity->id);
                    $activityApplyTimes = 0;
                    if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_EVERYDAY_ONCE) {
                        $activityApplyTimes = $activityAuditBuilder->joinedToday()
                            ->count();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_MONTHLY_ONCE) {
                        $activityApplyTimes = $activityAuditBuilder->joinedThisMonth()
                            ->count();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_WEEKLY_ONCE) {
                        $activityApplyTimes = $activityAuditBuilder->joinedThisWeek()
                            ->count();
                    } else if ($maxJoinTimes == CarrierActivity::APPLY_TIMES_PERMANENT_ONCE) {
                        $activityApplyTimes = $activityAuditBuilder->count();
                    }
                    if ($activityApplyTimes >= 1) {
                        $activity->canJoin = FALSE;
                        return;
                    }
                }
                $activity->canJoin = TRUE;
            });
        
        return \WTemplate::applyForDiscount()->with('activityList', $activityList);
    }

    /**
     * 参加优惠活动
     */
    public function applyParticipate(Request $request)
    {
        $activity = CarrierActivity::findOrFail($request->get('act_id'));
        $loginPlayerId = \WinwinAuth::memberUser()->player_id;
        // 检测是否是主动申请
        if ($activity->is_active_apply == false) {
            return $this->sendErrorResponse('该活动不需要主动申请');
        }
        try {
            $activity->checkUserCanApplyActivity($loginPlayerId, RealIpHelper::getIp());
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
        $carrierActivityAudit = new CarrierActivityAudit();
        $carrierActivityAudit->act_id = $activity->id;
        $carrierActivityAudit->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $carrierActivityAudit->player_id = $loginPlayerId;
        $carrierActivityAudit->status = CarrierActivityAudit::STATUS_AUDIT;
        $carrierActivityAudit->ip = RealIpHelper::getIp();
        $carrierActivityAudit->save();
        return $this->sendResponse([], '参与成功,请等待客服审核');
    }

    /**
     * 财务报表
     *
     * @return \View
     */
    public function financeStatistics()
    {
        return \WTemplate::financeStatistics();
    }

    /**
     * 财务报表筛选
     *
     * @return \View
     */
    public function selectTab()
    {
        return \WTemplate::selectTab();
    }

    /**
     * 取款记录
     *
     * @return \View
     */
    public function withdrawRecords()
    {
        return \WTemplate::withdrawRecords();
    }

    /**
     * 优惠记录
     *
     * @param $request
     * @return \View
     */
    public function discountRecords(Request $request)
    {
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        $t = $request->get('t', '');
        $start_time = '';
        $end_time = '';
        
        $time = time();
        if (! empty($t)) {
            if ($t == 1) {
                $start_time = date("Y-m-d", $time) . ' 00:00:00';
            } else if ($t == 2) {
                $start_time = date('Y-m-d H:i:s', strtotime('-1 sunday', time()));
            } else if ($t == 3) {
                $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m', time()) . '-01 00:00:00'));
            }
            $end_time = date("Y-m-d", $time) . ' 23:59:59';
        }
        
        $start_time = empty($start_time) ? $request->get('start_time', '') : $start_time;
        $end_time = empty($end_time) ? $request->get('end_time', '') : $end_time;
        
        $status = $request->get('status', '');
        $carrierActivityAudit = CarrierActivityAudit::where('player_id', \WinwinAuth::memberUser()->player_id)->with(
            [
                'activity' => function ($query) {
                    $query->active()
                        ->with(
                        [
                            'actType',
                            'activityWithdrawFlowLimitGamePlats.gamePlat'
                        ]);
                }
            ]);
        $parameter = array(
            'status' => $status,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        if ($status) {
            $carrierActivityAudit = $carrierActivityAudit->where('status', $status);
        }
        if ($start_time) {
            $carrierActivityAudit = $carrierActivityAudit->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $carrierActivityAudit = $carrierActivityAudit->whereDate('created_at', '<=', $end_time);
        }
        $carrierActivityAudit = $carrierActivityAudit->orderBy('created_at', 'DESC')->paginate($perPage);
        
        $carrierActivityStatus = CarrierActivityAudit::statusMeta();
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::discountLists()->with(
                    [
                        'carrierActivityAudit' => $carrierActivityAudit
                    ]);
            }
            return \WTemplate::discountRecords()->with(
                [
                    'carrierActivityAudit' => $carrierActivityAudit,
                    'carrierActivityStatus' => $carrierActivityStatus
                ]);
        }
        if ($this->isMobile()) {
            $str = '';
            foreach ($carrierActivityAudit as $item) {
                $str .= "{'优惠编号':'" . $item->id . "','优惠名称':'" . $item->activity->name . "','优惠类型':'" .
                     $item->activity->actType->type_name . "','红利金额':'" . $item->process_bonus_amount . "','审核时间':'" .
                     $item->updated_at . "','流水要求':'" . $item->process_withdraw_flow_limit . "','备注':'" . $item->remark .
                     "','状态':'" . $item->statusMeta()[$item->status] . "'}";
            }
            $str = rtrim($str, ',');
            
            return \WTemplate::discountRecords('m')->with(
                [
                    'carrierActivityAudit' => $carrierActivityAudit,
                    'carrierActivityStatus' => $carrierActivityStatus,
                    'parameter' => $parameter,
                    'str' => $str
                ]);
        } else {
            return \WTemplate::discountRecords()->with(
                [
                    'carrierActivityAudit' => $carrierActivityAudit,
                    'carrierActivityStatus' => $carrierActivityStatus,
                    'parameter' => $parameter
                ]);
        }
    }

    /**
     * 投注记录
     *
     * @return \View
     */
    public function bettingRecords(Request $request)
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        $t = $request->get('t', '');
        $start_time = '';
        $end_time = '';
        
        $time = time();
        if (! empty($t)) {
            if ($t == 1) {
                $start_time = date("Y-m-d", $time) . ' 00:00:00';
            } else if ($t == 2) {
                $start_time = date('Y-m-d H:i:s', strtotime('-1 sunday', time()));
            } else if ($t == 3) {
                $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m', time()) . '-01 00:00:00'));
            }
            $end_time = date("Y-m-d", $time) . ' 23:59:59';
        }
        
        $start_time = empty($start_time) ? $request->get('start_time', '') : $start_time;
        $end_time = empty($end_time) ? $request->get('end_time', '') : $end_time;
        $game_plat_id = $request->get('game_plat_id', '');
        $parameter = array(
            'game_plat_id' => $game_plat_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        
        $betFlowLogs = PlayerBetFlowLog::where('player_id', $player_id)->with('gamePlat')->selectRaw(
            'game_plat_id,count(id) as count,sum(bet_amount) as bet_water,sum(available_bet_amount) as effective_bet ,sum(company_payout_amount) as payout,sum(company_win_amount) as income');
        if (isset($game_plat_id) && $game_plat_id != '') {
            $betFlowLogs = $betFlowLogs->where('game_plat_id', $game_plat_id);
        }
        if ($start_time) {
            $betFlowLogs = $betFlowLogs->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $betFlowLogs = $betFlowLogs->whereDate('created_at', '<=', $end_time);
        }
        $betFlowLogs = $betFlowLogs->groupBy('game_plat_id')->paginate($perPage);
        $gamePlat = GamePlat::all();
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::bettingLists()->with('betFlowLogs', $betFlowLogs);
            }
            return \WTemplate::bettingRecords()->with(
                [
                    'betFlowLogs' => $betFlowLogs,
                    'gamePlat' => $gamePlat
                ]);
        }
        if ($this->isMobile()) {
            $str = '';
            foreach ($betFlowLogs as $item) {
                $str .= "{'游戏平台':'" . $item->gamePlat->game_plat_name . "','投注次数':'" . $item->count . "','投注额':'" .
                     $item->bet_water . "','有效投注额':'" . $item->effective_bet . "','派彩金额':'" . $item->payout . "','总输赢':'" .
                     $item->income . "','link':{'title':'投注详情','href':'" . route('players.bettingDetails',
                        [
                            'gamePlatId' => $item->game_plat_id
                        ]) . "'}},";
            }
            
            $str = rtrim($str, ',');
            
            return \WTemplate::bettingRecords('m')->with(
                [
                    'betFlowLogs' => $betFlowLogs,
                    'gamePlat' => $gamePlat,
                    'parameter' => $parameter,
                    'str' => $str
                ]);
        } else {
            return \WTemplate::bettingRecords()->with(
                [
                    'betFlowLogs' => $betFlowLogs,
                    'gamePlat' => $gamePlat,
                    'parameter' => $parameter
                ]);
        }
    }

    /**
     * 投注详情
     *
     * @param Request $request
     * @return \View
     */
    public function bettingDetails(Request $request)
    {
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        $gamePlatId = $request->get('gamePlatId');
        $start_time = $request->get('betting_detail_start', '');
        $end_time = $request->get('betting_detail_end', '');
        if (empty($start_time)) {
            $start_time = "2000-01-01 00:00:00";
        }
        if (empty($end_time)) {
            $end_time = date('Y-m-d H:i:s');
        }
        $betFlowDetails = PlayerBetFlowLog::where('game_plat_id', $gamePlatId)->with('game')
            ->whereBetween('created_at', [
            $start_time,
            $end_time
        ])
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
        
        $items = $betFlowDetails->items();
        array_walk($items,
            function ($item) {
                $item->bet_content = is_null($item->gamePlat) ? '' : $item->gamePlat->game_plat_name;
            });
        
        $data = [
            'betting_detail_start' => $start_time,
            'betting_detail_end' => $end_time,
            'gamePlatId' => $gamePlatId
        ];
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::bettingDetailLists()->with(
                    [
                        'betFlowDetails' => $betFlowDetails,
                        'data' => $data
                    ]);
            }
            if ($this->isMobile()) {
                $str = '';
                foreach ($betFlowDetails as $item) {
                    $tempvar = is_null($item->game) ? $item->bet_content : $item->game->game_name;
                    $sy = 0 - $item->company_win_amount;
                    $str .= "{'游戏局号':'" . $item->game_flow_code . "','游戏名称':'" . $tempvar . "','投注内容':'" .
                         $item->bet_content . "','下注金额':'" . $item->bet_amount . "','有效投注额':'" .
                         $item->available_bet_amount . "','派彩金额':'" . $item->company_payout_amount . "','输赢':'" . $sy .
                         "','投注时间':'" . $item->created_at . "'},";
                }
                $str = '[' . rtrim($str, ',') . ']';
                
                return json_encode(
                    array(
                        'data' => $str,
                        'success' => true
                    ));
            } else {
                return \WTemplate::bettingDetails()->with(
                    [
                        'betFlowDetails' => $betFlowDetails,
                        'data' => $data
                    ]);
            }
        }
        if ($this->isMobile()) {
            $str = '';
            foreach ($betFlowDetails as $item) {
                $tempvar = is_null($item->game) ? $item->bet_content : $item->game->game_name;
                $sy = 0 - $item->company_win_amount;
                $str .= "{'游戏局号':'" . $item->game_flow_code . "','游戏名称':'" . $tempvar . "','投注内容':'" . $item->bet_content .
                     "','下注金额':'" . $item->bet_amount . "','有效投注额':'" . $item->available_bet_amount . "','派彩金额':'" .
                     $item->company_payout_amount . "','输赢':'" . $sy . "','投注时间':'" . $item->created_at . "'},";
            }
            $str = rtrim($str, ',');
            return \WTemplate::bettingDetails('m')->with(
                [
                    'betFlowDetails' => $betFlowDetails,
                    'data' => $data,
                    'str' => $str
                ]);
        } else {
            return \WTemplate::bettingDetails()->with(
                [
                    'betFlowDetails' => $betFlowDetails,
                    'data' => $data
                ]);
        }
    }

    /**
     * 站内短信
     *
     * @return \View
     */
    public function messageInStation()
    {
        return \WTemplate::messageInStation();
    }

    /**
     * 推荐好友
     *
     * @return \View
     */
    public function friendRecommends()
    {
        return \WTemplate::friendRecommends();
    }

    /**
     * 我要推荐
     *
     * @return \View
     */
    public function myRecommends()
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::where('player_id', $player_id)->first();
        // 获取邀请会员数量
        $player->invite_player_count = Player::where('recommend_player_id', $player_id)->count();
        // 累计获得奖金
        $player->totalBonus = PlayerInviteRewardLog::where('player_id', $player_id)->sum('reward_amount');
        
        if ($this->isMobile()) {
            return \WTemplate::myRecommends('m')->with('player', $player);
        } else {
            return \WTemplate::myRecommends()->with('player', $player);
        }
    }

    /**
     * 我的下线
     *
     * @param integer $status 1本周 2本月
     * @param $request
     * @return \View
     */
    public function selectmyReferrals(Request $request)
    {
        return \WTemplate::selectmyReferrals();
    }

    /**
     * 我的下线
     *
     * @param integer $status 1本周 2本月
     * @param $request
     * @return \View
     */
    public function myReferrals(Request $request)
    {
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        $start_time = $request->get('start_time', '');
        $end_time = $request->get('end_time', '');
        $parameter = array(
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        $player = Player::where('recommend_player_id', \WinwinAuth::memberUser()->player_id)->with('loginLogs');
        
        if ($start_time) {
            $player = $player->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $player = $player->whereDate('created_at', '<=', $end_time);
        }
        $player = $player->orderBy('created_at', 'DESC')->paginate($perPage);
        // dd($player->render());
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::myReferralLists()->with('player', $player);
            }
            return \WTemplate::myReferrals()->with('player', $player);
        }
        if ($this->isMobile()) {
            
            $str = '';
            foreach ($player as $play) {
                $str .= "{'会员账号':'" . $play->user_name . "','登录次数':'" . $play->loginLogs->count() . "','最后登录时间':'" .
                     $play->login_at . "','注册时间':'" . $play->created_at . "'},";
            }
            $str = rtrim($str, ',');
            return \WTemplate::myReferrals('m')->with(
                [
                    'player' => $player,
                    'parameter' => $parameter,
                    'str' => $str
                ]);
        } else {
            return \WTemplate::myReferrals()->with(
                [
                    'player' => $player,
                    'parameter' => $parameter
                ]);
        }
    }

    public function selectAccountStatistics(Request $request)
    {
        return \WTemplate::selectAccountStatistics();
    }

    /**
     * 银行卡管理
     */
    public function bankcardManager(Request $request)
    {
        if ($this->isMobile()) {
            // 当前玩家所有的取款银行卡
            $playerBankCards = PlayerBankCard::with('bankType')->get();
            $str = '';
            foreach ($playerBankCards as $playerBankCard) {
                if ($playerBankCard->card_account) {
                    $playerBankCard->card_account = PlayerBankCard::replaceStar($playerBankCard->card_account, 4, 11);
                }
                
                $str .= "{bankname:'" . $playerBankCard->bankType->bank_name . "',cardtype:'储蓄卡',bankcode:'nongye',_id:" .
                     $playerBankCard->card_id . ",cardmum:'" . $playerBankCard->card_account . "'},";
            }
            $str = rtrim($str, ',');
            
            return \WTemplate::bankcardManager('m')->with('str', $str);
        }
    }

    /**
     * 添加银行卡
     */
    public function addBankcard(Request $request)
    {
        if ($this->isMobile()) {
            return \WTemplate::addBankcard('m');
        }
    }

    /**
     * 账目统计
     *
     * @param Request $request
     * @return \View
     */
    public function accountStatistics(Request $request)
    {
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        // H5时间处理
        $tabletype = $request->get('tabletype', '');
        $t = $request->get('t', '');
        $start_time = '';
        $end_time = '';
        
        $time = time();
        if (! empty($t)) {
            if ($t == 1) {
                $start_time = date("Y-m-d", $time) . ' 00:00:00';
            } else if ($t == 2) {
                $start_time = date('Y-m-d H:i:s', strtotime('-1 sunday', time()));
            } else if ($t == 3) {
                $start_time = date('Y-m-d H:i:s', strtotime(date('Y-m', time()) . '-01 00:00:00'));
            }
            $end_time = date("Y-m-d", $time) . ' 23:59:59';
        }
        $start_time = empty($start_time) ? Carbon::now()->startOfMonth() : $start_time;
        $end_time = empty($end_time) ? Carbon::now()->endOfMonth() : $end_time;
        if (empty($start_time)) {
            $start_time = "2000-01-01 00:00:00";
        }
        if (empty($end_time)) {
            $end_time = Carbon::now();
        }
        
        $recommentdPlayer = Player::where('recommend_player_id', \WinwinAuth::memberUser()->player_id)->with(
            [
                'betFlowLogs' => function ($query) use ($start_time, $end_time) {
                    $query->between($start_time, $end_time);
                }
            ])
            ->with(
            [
                'depositLogs' => function ($query) use ($start_time, $end_time) {
                    $query->between($start_time, $end_time);
                }
            ])
            ->whereBetween('created_at', [
            $start_time,
            $end_time
        ])
            ->orderBy('login_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
        
        $statisticTotal = $this->statisticTotal($start_time, $end_time);
        $parameter = array(
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::accountStatisticLists()->with('recommentdPlayer', $recommentdPlayer);
            }
            return \WTemplate::accountStatistics()->with(
                [
                    'recommentdPlayer' => $recommentdPlayer,
                    'statisticTotal' => $statisticTotal
                ]);
        }
        if ($this->isMobile()) {
            $stotal = "{'总会员数':" . $statisticTotal->totalMembers . ",'有效会员数':" . $statisticTotal->availableMembers .
                 ",'总存款额':" . $statisticTotal->totalDepositAmount . ",'总投注额':" . $statisticTotal->totalBetAmount .
                 ",'总有额投注额':" . $statisticTotal->availableTotalBetAmount . ",'奖金':" . $statisticTotal->totalBonus .
                 ",'link':{title:'详情',href:'" . route('players.statisticDetails') . "?start_time=" . $start_time .
                 "&end_time=" . $end_time . "'}}";
            $mtotal = '';
            foreach ($recommentdPlayer as $item) {
                $mtotal .= "{'会员帐号':'" . $item->user_name . "','总存款额':'" . $item->depositLogs->sum('amount') .
                     "','总存款额':'" . $item->betFlowLogs->sum('bet_amount') . "','有效投注额':'" .
                     $item->betFlowLogs->sum('available_bet_amount') . "','最后登录时间':'" . $item->login_at . "','注册时间':'" .
                     $item->created_at . "'},";
            }
            $mtotal = rtrim($mtotal, ',');
            return \WTemplate::accountStatistics('m')->with(
                [
                    'recommentdPlayer' => $recommentdPlayer,
                    'statisticTotal' => $statisticTotal,
                    'parameter' => $parameter,
                    'tabletype' => $tabletype,
                    'stotal' => $stotal,
                    'starttime' => $start_time,
                    'endtime' => $end_time,
                    'mtotal' => $mtotal
                ]);
        } else {
            return \WTemplate::accountStatistics()->with(
                [
                    'recommentdPlayer' => $recommentdPlayer,
                    'statisticTotal' => $statisticTotal,
                    'parameter' => $parameter
                ]);
        }
    }

    public function statisticDetails(Request $request)
    {
        $type = $request->get('type', '');
        $perPage = $request->get('perPage', 10);
        $start_time = $request->get('start_time', '');
        $end_time = $request->get('end_time', '');
        
        if (empty($start_time)) {
            $start_time = "2000-01-01 00:00:00";
        }
        if (empty($end_time)) {
            $end_time = Carbon::now();
        }
        
        $statisticDetails = PlayerInviteRewardLog::where('player_id', \WinwinAuth::memberUser()->player_id)->whereBetween(
            'created_at', [
                $start_time,
                $end_time
            ])
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
        
        foreach ($statisticDetails as $k => $player) {
            if ($player->reward_related_player) {
                $player_id = $player->reward_related_player;
            } else {
                $player_id = $player->player_id;
            }
            $username = Player::where('player_id', $player_id)->value('user_name');
            $statisticDetails[$k]->user_name = $username;
        }
        $parameter = array(
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::accountStatisticDetailLists()->with('statisticDetails', $statisticDetails);
            } else {
                return \WTemplate::accountStatisticDetails()->with('statisticDetails', $statisticDetails);
            }
        }
        if ($this->isMobile()) {
            $str = '';
            foreach ($statisticDetails->items() as $detail) {
                $str .= "{'会员账号':'" . $detail->user_name . "','存款额':'" . $detail->related_player_deposit_amount .
                     "','投注总额':'" . $detail->related_player_bet_amount . "','有效投注额':'" .
                     $detail->related_player_validate_bet_amount . "','结算类型':'" .
                     $detail->rewardType()[$detail->reward_type] . "','结算金额':'" . $detail->reward_amount . "','结算时间':'" .
                     $detail->created_at . "'},";
            }
            $str = rtrim($str, ',');
            return \WTemplate::accountStatisticDetails('m')->with(
                [
                    'statisticDetails' => $statisticDetails,
                    'parameter' => $parameter,
                    'str' => $str
                ]);
        } else {
            return \WTemplate::accountStatisticDetails()->with(
                [
                    'statisticDetails' => $statisticDetails,
                    'parameter' => $parameter
                ]);
        }
    }

    /**
     * 账目总计
     *
     * @param $start_time
     * @param $end_time
     * @return object
     */
    // ->with('betFlowLogs')
    // ->with('depositLogs')
    public function statisticTotal($start_time, $end_time)
    {
        if (! empty($start_time)) {
            $start_time = "2000-01-01 00:00:00";
        }
        if (! empty($end_time)) {
            $end_time = Carbon::now();
        }
        $recommendPlayer = Player::where('recommend_player_id', \WinwinAuth::memberUser()->player_id)->whereBetween(
            'created_at', [
                $start_time,
                $end_time
            ])->get([
            'player_id'
        ]);
        // 运营商有效会员配置
        $conf = CarrierInvitePlayerConf::first();
        // 总的奖金
        $totalBonus = PlayerInviteRewardLog::withoutGlobalScope(PlayerScope::class)->where('player_id',
            \WinwinAuth::memberUser()->player_id)
            ->whereBetween('created_at', [
            $start_time,
            $end_time
        ])
            ->sum('reward_amount');
        $statistic = (object) array();
        // 总会员数
        $statistic->totalMembers = count($recommendPlayer);
        // 奖金
        $statistic->totalBonus = $totalBonus;
        // 有效会员
        $statistic->availableMembers = 0;
        // 总存款额
        $statistic->totalDepositAmount = 0;
        // 总投注额
        $statistic->totalBetAmount = 0;
        // 总有效投注额
        $statistic->availableTotalBetAmount = 0;
        
        // $availableBetAmount = PlayerBetFlowLog::withoutGlobalScope(PlayerScope::class)->get();
        // dump($availableBetAmount);
        
        $data = Player::with(
            [
                'betFlowLogs' => function ($query) {
                    $query->withoutGlobalScope(PlayerScope::class);
                },
                'depositLogs' => function ($query) {
                    $query->withoutGlobalScope(PlayerScope::class);
                }
            ])->whereIn('player_id',
            $recommendPlayer->map(
                function (Player $element) {
                    return $element->player_id;
                })
                ->toArray())
            ->get();
        
        foreach ($data as $item) {
            if (head($item->betFlowLogs)) {
                $statistic->totalBetAmount += ($item->betFlowLogs->sum('bet_amount'));
                $statistic->availableTotalBetAmount += ($item->betFlowLogs->sum('available_bet_amount'));
            }
            if (head($item->depositLogs)) {
                $statistic->totalDepositAmount += ($item->depositLogs->sum('amount'));
            }
            if (($statistic->totalDepositAmount >= $conf->invalid_player_deposit_amount) &&
                 ($statistic->availableTotalBetAmount >= $conf->invalid_player_bet_amount)) {
                $statistic->availableMembers += 1;
            }
        }
        // foreach($recommendPlayer as $value) {//->between($start_time, $end_time)
        // $availableBetAmount = PlayerBetFlowLog::where('player_id', $value->player_id)->sum('available_bet_amount');
        // $betAmount = PlayerBetFlowLog::where('player_id', $value->player_id)->sum('bet_amount');
        // $depositAmount = PlayerDepositPayLog::where('player_id', $value->player_id)->sum('amount');
        //
        // if (($depositAmount >= $conf->invalid_player_deposit_amount) && ($availableBetAmount >= $conf->invalid_player_bet_amount)) {
        // $statistic->availableMembers += 1;
        // }
        //
        // $statistic->totalDepositAmount += $depositAmount;
        // $statistic->totalBetAmount += $betAmount;
        // $statistic->availableTotalBetAmount += $availableBetAmount;
        // }
        return $statistic;
    }

    /**
     * 退出登录
     *
     * @return \\Redirector
     */
    public function logout()
    {
        \WinwinAuth::memberAuth()->logout();
        return redirect(route('/'));
    }

    // 手机端钱包中心
    public function purseSecurity()
    {
        $playerLevelId = \WinwinAuth::memberUser()->player_level_id;
        
        $PlayerLevel = CarrierPlayerLevel::find($playerLevelId)->with(
            [
                'bankCardMap' => function ($query) {
                    $query->with(
                        [
                            'carrierBankCards' => function ($query) {
                                $query->available();
                            }
                        ]);
                }
            ])
            ->first();
        $balance = \WinwinAuth::memberUser()->main_account_amount;
        $onlinePayList = array();
        $otherPayList = array();
        // 根据玩家等级查找银行卡支付渠道
        foreach ($PlayerLevel->bankCardMap as $map) {
            if ($map->carrierBankCards && $map->carrierBankCards->use_purpose == 1 && in_array($map->carrierBankCards->show, [1,3])) {
                // 第三方在线支付(绑定第三方)
                $onlinePay = CarrierPayChannel::where('id', $map->carrierBankCards->id)->with(
                    'bindedThirdPartGateway.defPayChannel.payChannelType')->first();
                if ($onlinePay->bindedThirdPartGateway &&
                     $onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->isThirdPartPay()) {
                    // if ($onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->isMobile())
                    $onlinePayList[$onlinePay->bindedThirdPartGateway->defPayChannel->payChannelType->id][] = $onlinePay;
                    // 其他支付(未绑定第三方)
                } else {
                    $otherPay = CarrierPayChannel::where('id', $map->carrierBankCards->id)->with(
                        'PayChannel.payChannelType')->where('use_purpose', 1)->whereIn('show',[1,3])->first();
                    $otherPayList[$otherPay->PayChannel->payChannelType->id][] = $otherPay;
                }
            }
        }
        
        // 活动列表
        $carrierActivityList = CarrierActivity::active()->where('is_deposit_display',
            CarrierActivity::DEPOSIT_DISPLAY_IS)->get();
        
        $array = [
            'onlinePayList' => $onlinePayList,
            'otherPayList' => $otherPayList,
            'carrierActivityList' => $carrierActivityList
        ];
        
        return view('Web.mobile.player_centers.finance_center.index', compact('balance'))->with($array);
    }

    public function depositTypePage($payChannelTypeId, $carrierPayChannelId)
    {
        // 获取银行卡号信息
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::where('player_id', $player_id)->with(
            [
                'bankCards' => function ($query) {
                    $query->active()
                        ->with('bankType');
                }
            ])
            ->get();
        
        // 银行列表
        $bankList = BankType::all();
        
        // 活动列表
        $carrierActivityList = CarrierActivity::active()->where('is_deposit_display',
            CarrierActivity::DEPOSIT_DISPLAY_IS)->get();
        $events = array();
        foreach ($carrierActivityList as $cal) {
            $events[$cal->id] = $cal->name;
        }
        $otherPay = CarrierPayChannel::where('id', $carrierPayChannelId)->with('PayChannel.payChannelType')->first();
        
        $other = [
            'otherPay' => $otherPay,
            'carrierActivityList' => collect($events)
        ];
        
        $onlinePay = CarrierPayChannel::with('payChannel')->where('id', $carrierPayChannelId)->first();
        $online = [
            'onlinePay' => $onlinePay,
            'background' => $onlinePay->payChannel->icon_path_url
        ];
        if ($payChannelTypeId == PayChannelType::ONLINE_PAY) {
            $bankListPre = $onlinePay->payChannel->channel_code;
            $online['bankList'] = collect(config('banklist.' . $bankListPre . '.online'));
            $online['gateway'] = $onlinePay->payChannel->channel_code === 'NPAY' ? 'bank' : '';
            $online = array_merge($other, $online);
            return view('Web.mobile.player_centers.finance_center.online_pay')->with($online);
        } elseif ($payChannelTypeId == PayChannelType::SCAN_CODE_PAY) {
            
            return \WTemplate::scanCodeDeposit();
        } elseif ($payChannelTypeId == PayChannelType::BANK_TRANSFER_PAY) {
            $online['player'] = $player;
            $online['bankList'] = $bankList;
            $online['transferType'] = PlayerDepositPayLog::onlineTransferType();
            $bankTransfer = array_merge($other, $online);
            return \WTemplate::bankTransferDeposit()->with($bankTransfer);
        } elseif ($payChannelTypeId == PayChannelType::SCAN_CODE_COMPANY_PAY) {
            $scanTransfer['gateway'] = '';
            $other = array_merge($other, $online);
            
            return \WTemplate::onlineScanDeposit()->with($other);
        } elseif ($payChannelTypeId == PayChannelType::POINT_CARD_PAY) {
            
            return \WTemplate::pointCardDeposit()->with($other);
        } elseif ($payChannelTypeId == PayChannelType::ONLINE_OR_SCAN_PAY) {
            
            $bankListPre = $onlinePay->payChannel->channel_code;
            $scan = config('banklist.' . $bankListPre . '.scan');
            $online['gateway'] = array_first(array_flip($scan),
                function ($key, $val) {
                    return $val;
                });
            $online['scan'] = collect($scan);
        } elseif ($payChannelTypeId == PayChannelType::ONLINE_H5) {
            $bankListPre = $onlinePay->payChannel->channel_code;
            $scan = config('banklist.' . $bankListPre . '.h5');
            $online['gateway'] = array_first(array_flip($scan),
                function ($key, $val) {
                    return $val;
                });
            $online['scan'] = collect($scan);
        } else {
            return $this->sendNotFoundResponse();
        }
        
        $online = array_merge($other, $online);
        return view('Web.mobile.player_centers.finance_center.scan_pay')->with($online);
    }

    public function bindEmail(Request $request)
    {
        $emailConf = CarrierPasswordRecoverySiteConf::first();
        if (empty($emailConf) || empty($emailConf->is_open_email_send_function) || empty($emailConf->smtp_username)) {
            return $this->sendErrorResponse('对不起，该运营商未开启配置邮箱验证功能，请联系运营商', 500);
        }
        $verifiCode = createRand(10); // 生成随机码
        $email = $request->input('email', ''); // 收件人邮箱
        $payerInfo = Player::where('email', $email)->first();
        
        if (empty($email)) {
            return $this->sendErrorResponse('邮箱不能为空', 500);
        }
        if (! empty($payerInfo)) {
            return $this->sendErrorResponse('邮箱已存在', 500);
        }
        if ($request->has('type') && $request->get('type') == 'player') {
            return $this->sendResponse($email);
        }
        $url = route('homes.bindEmail', [
            'bindEmail' => $verifiCode
        ]);
        
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
            Mail::send('email.bindEmail', [
                'code' => $url
            ],
                function ($message) use ($email, $emailConf) {
                    $message->from($emailConf->smtp_username, $emailConf->mail_sender)
                        ->to($email)
                        ->subject('绑定邮箱');
                });
            // 重置原邮件配置
            Mail::setSwiftMailer($backup);
            
            // 保存邮件验证码
            $redis = Redis::connection();
            $redis->set($verifiCode,
                \WinwinAuth::memberUser()->user_name . '-' . \WinwinAuth::memberUser()->carrier_id . '-' . $email . '-' .
                     'player');
            return $this->sendResponse($email);
        } catch (\Exception $e) {
            \WLog::error('======>邮件发送失败:' . $e->getMessage());
            return $this->sendErrorResponse('发送失败，请重试');
        }
    }
}
