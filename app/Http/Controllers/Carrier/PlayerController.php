<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerDataTable;
use App\Http\Requests\Carrier\CreatePlayerRequest;
use App\Http\Requests\Carrier\UpdatePlayerRequest;
use App\Http\Requests\Web\CreatePlayerBankCardRequest;
use App\Http\Requests\Web\UpdatePlayerBankCardRequest;
use App\Models\Def\BankType;
use App\Models\Log\PlayerAccountLog;
use App\Models\PlayerBankCard;
use App\Models\PlayerGameAccount;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawLog;
use App\Models\Def\MainGamePlat;
use App\Http\Controllers\Web\GameController;
use App\Models\Player;
use App\Repositories\Carrier\PlayerRepository;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\PT\PTGameGateway;
use App\Vendor\GameGateway\Bbin\BBin;
use App\Vendor\GameGateway\OneWorks\OneWorks;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Response;
use Route;

class PlayerController extends AppBaseController
{

    /** @var  PlayerRepository */
    private $playerRepository;

    public static function normalRouteLists()
    {
        Route::get('players.showFinancial/{player_id}', 'PlayerController@showFinancial')->name('players.showFinancial');
        Route::get('players.showTradeLog/{player_id}', 'PlayerController@showTradeLog')->name('players.showTradeLog');
        Route::get('players.showLoginLog/{player_id}', 'PlayerController@showLoginLog')->name('players.showLoginLog');
        Route::get('players.showCheatLog/{player_id}', 'PlayerController@showCheatLog')->name('players.showCheatLog');
        Route::get('players.showRecommendLog/{player_id}', 'PlayerController@showRecommendLog')->name('players.showRecommendLog');
        Route::get('players.showPlayerBonusSettingModal', 'PlayerController@showPlayerBonusSettingModal')->name('players.showPlayerBonusSettingModal');
        Route::get('players.showPlayerMainAccountAmountSettingModal', 'PlayerController@showPlayerMainAccountAmountSettingModal')->name('players.showPlayerMainAccountAmountSettingModal');
        Route::get('players.showPlayerRebateFinancialFlowSettingModal', 'PlayerController@showPlayerRebateFinancialFlowSettingModal')->name('players.showPlayerRebateFinancialFlowSettingModal');
        Route::get('players.showVerifyLoginPasswordModal/{player_id}', 'PlayerController@showVerifyLoginPasswordModal')->name('players.showVerifyLoginPasswordModal');
        Route::get('players.showVerifyPayPasswordModal/{player_id}', 'PlayerController@showVerifyPayPasswordModal')->name('players.showVerifyPayPasswordModal');
        Route::get('players.showPlayerInfoEditModal/{player_id}', 'PlayerController@showPlayerInfoEditModal')->name('players.showPlayerInfoEditModal');
        Route::get('players.showPTGamePasswordChangeModal/{player_id}', 'PlayerController@showPTGamePasswordChangeModal')->name('players.showPTGamePasswordChangeModal');
        Route::get('players.gameManage/{player_id}', 'PlayerController@gameManage')->name('players.gameManage');
        Route::get('players.showBankManage/{player_id}', 'PlayerController@showBankManage')->name('players.showBankManage');
        Route::get('players.tabBankEdit/{card_id}', 'PlayerController@tabBankEdit')->name('players.tabBankEdit');
        Route::post('players.tabBankStore', 'PlayerController@tabBankStore')->name('players.tabBankStore');
        Route::get('players.exportInfoFieldSelect', 'PlayerController@exportInfoFieldSelect')->name('players.exportInfoFieldSelect');
    }

    public static function rbacRouteLists()
    {
        Route::resource('players', 'PlayerController');
        Route::patch('players.updateUserName/{player_id}', 'PlayerController@updateRealName')->name('players.updateUserName');
        Route::patch('players.updateMobile/{player_id}', 'PlayerController@updateTelephone')->name('players.updateMobile');
        Route::patch('players.updateInviteUser/{player_id}', 'PlayerController@updateInviteUser')->name('players.updateInviteUser');
        Route::patch('players.updateLevel/{player_id}', 'PlayerController@updateLevel')->name('players.updateLevel');
        Route::patch('players.updateEmail/{player_id}', 'PlayerController@updateEmail')->name('players.updateEmail');
        Route::patch('players.updateAgent/{player_id}', 'PlayerController@updateAgent')->name('players.updateAgent');
        Route::get('players.queryAndSynchronizePlayerAllGameAccountsToDB/{player_id}', 'PlayerController@queryAndSynchronizePlayerAllGameAccountsToDB')->name('players.queryAndSynchronizePlayerAllGameAccountsToDB');
        Route::get('players.withDrawAllPlayerGameAccounts/{player_id}', 'PlayerController@withDrawAllPlayerGameAccounts')->name('players.withDrawAllPlayerGameAccounts');
        Route::get('players.withDrawPlayerGameAccount/{player_id}/{gamePlat_id}', 'PlayerController@withDrawPlayerGameAccount')->name('players.withDrawPlayerGameAccount');
        Route::get('players.depositPlayerGameAccount/{player_id}/{gamePlat_id}', 'PlayerController@depositPlayerGameAccount')->name('players.depositPlayerGameAccount');
        Route::get('players.switchPlayerGameAccountTransferLockStatus/{player_id}/{gamePlat_id}', 'PlayerController@switchPlayerGameAccountTransferLockStatus')->name('players.switchPlayerGameAccountTransferLockStatus');
        Route::get('players.switchPlayerGameCloseStatus/{player_id}/{gamePlat_id}', 'PlayerController@switchPlayerGameCloseStatus')->name('players.switchPlayerGameCloseStatus');
        Route::patch('players.updatePlayerPTGamePassword/{player_id}', 'PlayerController@updatePlayerPTGamePassword')->name('players.updatePlayerPTGamePassword');
        Route::post('players.kickOutLine/{player_id}', 'PlayerController@kickPlayerOutLine')->name('players.kickOutLine');
        Route::patch('players.updatePlayerLoginPassword/{player_id}', 'PlayerController@updatePlayerLoginPassword')->name('players.updatePlayerLoginPassword');
        Route::patch('players.updatePlayerPayPassword/{player_id}', 'PlayerController@updatePlayerPayPassword')->name('players.updatePlayerPayPassword');
        Route::patch('players.togglePlayerAccountStatus/{player_id}', 'PlayerController@togglePlayerAccountStatus')->name('players.togglePlayerAccountStatus');
        // updateBirthday
        Route::patch('players.updateBirthday/{player_id}', 'PlayerController@updateBirthday')->name('players.updateBirthday');
        Route::patch('players.updateQQ/{player_id}', 'PlayerController@updateQQ')->name('players.updateQQ');
        Route::patch('players.updateWechat/{player_id}', 'PlayerController@updateWechat')->name('players.updateWechat');
        Route::post('players.exportInfo', 'PlayerController@exportInfo')->name('players.exportInfo');
        // 强制退出登录的游戏
        Route::get('players.forcedToLogOut/{player_id}/{gamePlat_id}', 'PlayerController@forcedToLogOut')->name('players.forcedToLogOut');
    }

    public function __construct(PlayerRepository $playerRepo)
    {
        $this->playerRepository = $playerRepo;
    }

    /**
     * Display a listing of the Player.
     *
     * @param PlayerDataTable $playerDataTable
     * @return Response
     */
    public function index(PlayerDataTable $playerDataTable)
    {
        // dd(Route::getRoutes()->getByName('players.showPlayerInfoEditModal'));
        return $playerDataTable->render('Carrier.players.index');
    }

    /**
     * 显示会员编辑模态框
     *
     * @param
     *            $playerId
     * @return mixed
     */
    public function showPlayerInfoEditModal($playerId)
    {
        $player = $this->playerRepository->with([
            'loginLogs' => function ($query) {
                return $query->orderByCreatedTime('desc')
                    ->limit(2);
            }
        ])
            ->findWithoutFail($playerId);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.player_info_edit_modal')->with('player', $player);
    }

    /**
     * 显示修改玩家pt密码模态框
     *
     * @param
     *            $playerId
     * @return $this|\Illuminate\View\View
     */
    public function showPTGamePasswordChangeModal($playerId)
    {
        $player = $this->playerRepository->findWithoutFail($playerId);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.edit_player_pt_password')->with('player', $player);
    }

    /**
     * Show the form for creating a new Player.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.players.create');
    }

    /**
     * Store a newly created Player in storage.
     *
     * @param CreatePlayerRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerRequest $request)
    {
        $input = $request->all();
        
        $player = $this->playerRepository->create($input);
        
        Flash::success('Player saved successfully.');
        
        return redirect(route('Carrier.players.index'));
    }

    /**
     * Display the specified Player.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $player = $this->playerRepository->with([
            'loginLogs' => function ($query) {
                return $query->orderByCreatedTime('desc')
                    ->limit(2);
            }
        ])
            ->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.show')->with('player', $player);
    }

    /**
     * Show the form for editing the specified Player.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.edit')->with('player', $player);
    }

    /**
     * Update the specified Player in storage.
     *
     * @param int $id
     * @param UpdatePlayerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerRequest $request)
    {
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update($request->all(), $id);
        return redirect(route('players.index'));
    }

    /**
     * 更新用户真实姓名
     *
     * @param Request $request
     */
    public function updateRealName($id, Request $request)
    {
        $this->validate($request, [
            'real_name' => 'required|max:10'
        ], [], [
            'real_name' => '姓名'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'real_name' => $request->get('real_name')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateInviteUser($id, Request $request)
    {
        $this->validate($request, [
            'user_id' => [
                'required',
                'exists:inf_player,player_id'
            ]
        ], [], [
            'user_id' => '邀请人'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if ($request->get('user_id') == $id) {
            return $this->sendErrorResponse('邀请人和当前用户不能是同一人');
        }
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'recommend_player_id' => $request->get('user_id')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateTelephone($id, Request $request)
    {
        $this->validate($request, [
            'mobile' => [
                'required',
                'unique:inf_player,mobile,' . $id . ',player_id',
                'regex:/^1[3-9]\d{9}$/'
            ]
        ], [], [
            'mobile' => '手机号码'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'mobile' => $request->get('mobile')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateLevel($id, Request $request)
    {
        $this->validate($request, [
            'level_id' => 'required|exists:inf_carrier_player_level,id,carrier_id,' . \WinwinAuth::carrierUser()->carrier_id
        ], [], [
            'level_id' => '会员等级'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'player_level_id' => $request->get('level_id')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateEmail($id, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:inf_player,email,' . $id . ',player_id|email'
        ], [], [
            'email' => '电子邮件'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'email' => $request->get('email')
        ], $id);
        return $this->sendSuccessResponse();
    }

    public function updateBirthday($id, Request $request)
    {
        $this->validate($request, [
            'birthday' => 'required|date'
        ], [], [
            'birthday' => '出生日期'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'birthday' => $request->get('birthday')
        ], $id);
        return $this->sendSuccessResponse();
    }

    public function updateQQ($id, Request $request)
    {
        $this->validate($request, [
            'qq' => [
                'required',
                'regex:/^[1-9]\d{4,}$/'
            ]
        ], [], [
            'qq' => 'QQ'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'qq_account' => $request->get('qq')
        ], $id);
        return $this->sendSuccessResponse();
    }

    public function updateWechat($id, Request $request)
    {
        $this->validate($request, [
            'wechat' => [
                'required',
                'regex:/^\w{1,}$/'
            ]
        ], [], [
            'wechat' => '微信号'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'wechat' => $request->get('wechat')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateAgent($id, Request $request)
    {
        $this->validate($request, [
            'agent_id' => 'required|exists:inf_agent,id,carrier_id,' . \WinwinAuth::carrierUser()->carrier_id
        ], [], [
            'agent_id' => '代理商'
        ]);
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRepository->update([
            'agent_id' => $request->get('agent_id')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     * Remove the specified Player from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $player = $this->playerRepository->findWithoutFail($id);
        if (empty($player)) {
            return redirect(route('players.index'));
        }
        $this->playerRepository->delete($id);
        return redirect(route('players.index'));
    }

    /**
     * 财务信息展示
     *
     * @param
     *            $id
     */
    public function showFinancial($id, Request $request)
    {
        if ($date = $request->get('dateRange')) {
            $dateSplit = explode(' - ', $date);
            if (count($dateSplit) == 2 && strtotime($dateSplit[0]) && strtotime($dateSplit[1])) {
                $startDate = $dateSplit[0];
                $endDate = $dateSplit[1];
            } else {
                throw new \InvalidArgumentException('date Range is illegal');
            }
        } else {
            $startDate = date('Y-m-d H:i:s', strtotime('-30 day'));
            $endDate = date('Y-m-d H:i:s');
        }
        $player = $this->playerRepository->with([
            'depositLogs' => function ($query) use ($startDate, $endDate) {
                $query->payedSuccessfully()
                    ->byFinishTimeRange($startDate, $endDate);
            },
            'betFlowLogs' => function ($query) use ($startDate, $endDate) {
                $query->byFinishTimeRange($startDate, $endDate);
            },
            'accountLogs' => function ($query) use ($startDate, $endDate) {
                $query->byFinishTimeRange($startDate, $endDate);
            },
            'withdrawLogs' => function ($query) use ($startDate, $endDate) {
                $query->accountOut()
                    ->withdrawSucceedBetween($startDate, $endDate);
            }
        ])
            ->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        // 存款记录
        $depositLogs = $player->depositLogs;
        // 投注记录
        $betFlowLogs = $player->betFlowLogs;
        // 主账户记录
        $accountLogs = $player->accountLogs;
        // 取款记录
        $withdrawLogs = $player->withdrawLogs;
        
        $staticData = [
            'depositCount' => 0,
            'withdrawCount' => 0,
            // 区间存款额统计
            'depositAmount' => 0.00,
            // 取款额统计
            'withdrawAmount' => 0.00,
            // 区间总输赢统计
            'winLoseAmount' => 0.00,
            // 总投注额
            'betAmount' => 0.00,
            // 派彩
            'payoutAmount' => 0.00,
            // 有效投注
            'availableBetAmount' => 0.00,
            // 存款优惠
            'depositBenefitAmount' => 0.00,
            // 手续费
            'feeAmount' => 0.00,
            // 红利
            'bonusAmount' => 0.00,
            // 洗码
            'rebateFinancialFlowAmount' => 0.00
        ];
        
        $currentTime = Carbon::create();
        $depositLogs->each(function (PlayerDepositPayLog $log) use (&$staticData, $currentTime) {
            $staticData['depositAmount'] += $log->amount;
            $staticData['depositCount'] += 1;
            $staticData['depositBenefitAmount'] += $log->benefit_amount;
            $staticData['bonusAmount'] += $log->bonus_amount;
            $staticData['feeAmount'] += $log->fee_amount;
        });
        $betFlowLogs->each(function (PlayerBetFlowLog $log) use (&$staticData, $currentTime) {
            if ($log->bet_flow_available == PlayerBetFlowLog::BET_FLOW_AVAILABLE) {
                $staticData['winLoseAmount'] += $log->company_win_amount;
                $staticData['availableBetAmount'] += $log->available_bet_amount;
            }
            $staticData['payoutAmount'] += $log->company_payout_amount;
            $staticData['betAmount'] += $log->bet_amount;
        });
        $accountLogs->each(function (PlayerAccountLog $log) use (&$staticData, $currentTime) {
            if ($log->fund_type == PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW) {
                $staticData['rebateFinancialFlowAmount'] += $log->amount;
            }
        });
        $withdrawLogs->each(function (PlayerWithdrawLog $log) use (&$staticData, $currentTime) {
            $staticData['withdrawCount'] += 1;
            $staticData['withdrawAmount'] += $log->finally_withdraw_amount;
        });
        if ($request->get('type') == 'detailSearch') {
            return view('Carrier.players.tab_financial_detail_info')->with('player', $player)->with('static', $staticData);
        }
        return view('Carrier.players.tab_financial_info')->with('player', $player)->with('static', $staticData);
    }

    /**
     * 交易信息展示
     *
     * @param
     *            $id
     */
    public function showTradeLog($id, Request $request)
    {
        if ($date = $request->get('dateRange')) {
            $dateSplit = explode(' - ', $date);
            if (count($dateSplit) == 2 && strtotime($dateSplit[0]) && strtotime($dateSplit[1])) {
                $startDate = $dateSplit[0];
                $endDate = $dateSplit[1];
            } else {
                throw new \InvalidArgumentException('date Range is illegal');
            }
        } else {
            $startDate = date('Y-m-d H:i:s', strtotime('-30 day'));
            $endDate = date('Y-m-d H:i:s');
        }
        $player = $this->playerRepository->with([
            'accountLogs' => function ($query) use ($startDate, $endDate) {
                $query->byFinishTimeRange($startDate, $endDate)
                    ->orderByCreatedTime('desc');
                ;
            },
            'accountLogs.serviceUser'
        ])
            ->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        if ($request->get('type') == 'detailSearch') {
            return view('Carrier.players.tab_trade_detail_log')->with('player', $player);
        }
        return view('Carrier.players.tab_trade_log')->with('player', $player);
    }

    /**
     * 游戏管理展示
     *
     * @param
     *            $id
     */
    public function gameManage($id)
    {
        $player = $this->playerRepository->with([
            'gameAccounts.mainGamePlat'
        ])->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.tab_game_info')->with('gameAccounts', $player->gameAccounts)->with('player', $player);
    }

    /**
     * 登录日志查询
     *
     * @param
     *            $id
     */
    public function showLoginLog($id, Request $request)
    {
        if ($date = $request->get('dateRange')) {
            $dateSplit = explode(' - ', $date);
            if (count($dateSplit) == 2 && strtotime($dateSplit[0]) && strtotime($dateSplit[1])) {
                $startDate = $dateSplit[0];
                $endDate = $dateSplit[1];
            } else {
                throw new \InvalidArgumentException('date Range is illegal');
            }
        } else {
            $startDate = date('Y-m-d H:i:s', strtotime('-30 day'));
            $endDate = date('Y-m-d H:i:s');
        }
        $player = $this->playerRepository->with([
            'loginLogs' => function ($query) use ($startDate, $endDate) {
                $query->byCreatedTimeRange($startDate, $endDate)
                    ->orderByCreatedTime('desc');
            }
        ])
            ->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        if ($request->get('type') == 'detailSearch') {
            return view('Carrier.players.tab_login_detail_log')->with('player', $player);
        }
        return view('Carrier.players.tab_login_log')->with('player', $player);
    }

    /**
     * 防套利查询展示
     *
     * @param
     *            $id
     */
    public function showCheatLog($id)
    {
        $player = Player::where('player_id', $id)->first();
        $likePlayer = Player::where(function ($query) use ($player) {
            $query->where('real_name', $player->real_name)
                ->whereNotNull('real_name')
                ->where('real_name', '<>', '')
                ->where('player_id', '<>', $player->player_id);
        })->orwhere(function ($query) use ($player) {
            $query->where('birthday', $player->birthday)
                ->whereNotNull('birthday')
                ->where('birthday', '<>', '')
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('password', $player->password)
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('mobile', $player->mobile)
                ->whereNotNull('mobile')
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('login_ip', $player->login_ip)
                ->whereNotNull('login_ip')
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('register_ip', $player->register_ip)
                ->whereNotNull('register_ip')
                ->where('register_ip', '<>', '')
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('created_at', '>=', date("Y-m-d H:i", strtotime($player->created_at)))
                ->where('created_at', '<', date("Y-m-d H:i", strtotime($player->created_at) + 60))
                ->whereNotNull('created_at')
                ->where('player_id', '<>', $player->player_id);
        })
            ->orwhere(function ($query) use ($player) {
            $query->where('login_at', '>=', date("Y-m-d H:i", strtotime($player->login_at)))
                ->where('login_at', '<', date("Y-m-d H:i", strtotime($player->login_at) + 60))
                ->whereNotNull('login_at')
                ->where('player_id', '<>', $player->player_id);
        })
            ->get();
        
        return view('Carrier.players.tab_cheat_check')->with('likePlayers', $likePlayer)->with('player', $player);
    }

    /**
     * 好友邀请记录展示
     *
     * @param
     *            $id
     */
    public function showRecommendLog($id)
    {
        $likePlayer = Player::where('recommend_player_id', $id)->get();
        return view('Carrier.players.tab_friend_recommend_log')->with('likePlayers', $likePlayer);
    }

    /**
     * 银行卡管理
     *
     * @param
     *            $id
     * @return $this|Response
     */
    public function showBankManage($id)
    {
        $bankCards = PlayerBankCard::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->where('player_id', $id)->get();;
        if (empty($bankCards)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.tab_bank_manage')->with('bankCards', $bankCards);
    }

    /**
     * 编辑银行卡
     *
     * @param
     *            $id
     * @return $this|Response
     */
    public function tabBankEdit($id)
    {
         $bankCard = PlayerBankCard::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with(['player'])->find($id);
         if (empty($bankCard)) {
            return $this->renderNotFoundPage();
         }
        $banks = BankType::all();
        return view('Carrier.players.tab_bank_edit', compact('bankCard','banks'));
    }
    //修改银行卡保存
    public function tabBankStore(UpdatePlayerBankCardRequest $request)
    {
        $bankCard = PlayerBankCard::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with(['player'])->find($request->get('card_id'));
        if (empty($bankCard)) {
            return $this->renderNotFoundPage();
        }
        $bankCard->card_owner_name = $request->get('card_owner_name');
        $bankCard->card_account = $request->get('card_account');
        $bankCard->card_type = $request->get('card_type');
        $bankCard->card_birth_place = $request->get('card_birth_place');

        if ($bankCard->card_owner_name != $bankCard->player->real_name) {
            return $this->sendErrorResponse(
                [
                    'field' => 'card_owner_name',
                    'message' => '开户人姓名与账号真实姓名不一致'
                ], 404);
        }

        $bankCard->save();
        return $this->sendSuccessResponse('操作成功');
    }
    /**
     * 同步会员游戏账户余额
     *
     * @param
     *            $id
     * @return Response
     */
    public function queryAndSynchronizePlayerAllGameAccountsToDB($id)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $playerGameAccounts = $player->gameAccounts;
        if (! $playerGameAccounts) {
            return $this->sendSuccessResponse();
        }
        try {
            foreach ($playerGameAccounts as $playerGameAccount) {
                /*
                 * $gameRunTime = new GameGatewayRunTime($playerGameAccount->mainGamePlat->main_game_plat_code);
                 * $gameRunTime->synchronizePlayerAccountInfoToDB($player);
                 */
                $game = new GameController();
                try {
                    \DB::beginTransaction();
                    // 收回MG平台游戏账号余额 add by tlt
                    $main_game_plat_code = $playerGameAccount->mainGamePlat->main_game_plat_code;
                    \WLog::info("游戏平台id -----》》》main_game_plat_code =");
                    if ($main_game_plat_code == MainGamePlat::MG) { // MG平台
                        \WLog::info("MG,一键查询游戏余额-->> start -----");
                        $mgAmount = $game->getMGBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                        \WLog::info("MG,一键查询游戏余额-->> ptAmount=$mgAmount");
                        // $game ->MGDebit("DEBIT",$mgAmount,$playerGameAccount->account_user_name) ;
                    }
                    
                    // 收回PT平台游戏账号余额 add by tlt
                    if ($main_game_plat_code == MainGamePlat::PT) { // PT平台
                        $ptAmount = $game->getPTBalance($playerGameAccount); // 获取当前主账号余额
                        \WLog::info("PT,一键查询游戏余额-->> ptAmount=$ptAmount");
                        // $game ->PTWithdraw($playerGameAccount,$ptAmount) ;
                    }
                    
                    // 收回SUNBET平台游戏账号余额 add by tlt
                    if ($main_game_plat_code == MainGamePlat::SUNBET) { // SUNBET平台
                        $sbAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                        \WLog::info("SUNBET,一键查询游戏余额-->> ptAmount=$sbAmount");
                        // $game ->SBDebit($sbAmount,$playerGameAccount->account_user_name) ;
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::GD) {
                        $gdAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                        \WLog::info("SUNBET,一键查询游戏余额-->> ptAmount=$gdAmount");
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::TGP) {
                        $tgpAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                        \WLog::info("SUNBET,一键查询游戏余额-->> ptAmount=$tgpAmount");
                    }
                    // 收回BBIN平台游戏账号余额
                    if ($main_game_plat_code == MainGamePlat::BBIN) { // 波音平台
                        $game->bbinCheckUsrBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::ONWORKS) // 沙巴平台
{
                        $game->onworksCheckUserBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::VR) // VR平台
{
                        $game->vrCheckUserBalance($playerGameAccount->account_user_name);
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::TTG) {
                        $game->ttgCheckUserBalance($playerGameAccount->account_user_name);
                    }
                    
                    if ($main_game_plat_code == MainGamePlat::PNG) {
                        $game->pngCheckUserBalance($playerGameAccount->account_user_name);
                    }
                    \DB::commit();
                    unset($game);
                } catch (\Exception $e) {
                    throw $e;
                    \DB::rollBack();
                }
            }
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 一键转出会员所有游戏余额
     *
     * @param
     *            $id
     */
    public function withDrawAllPlayerGameAccounts($id)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $playerGameAccounts = $player->gameAccounts;
        if (! $playerGameAccounts) {
            return $this->sendSuccessResponse();
        }
        try {
            foreach ($playerGameAccounts as $playerGameAccount) {
                $amount = $playerGameAccount->amount;
                $game = new GameController();
                if ($amount > 0) {
                    /*
                     * $gameRunTime = new GameGatewayRunTime($playerGameAccount->mainGamePlat->main_game_plat_code);
                     * $gameRunTime->withDrawFromPlayerGameAccount($player, $amount);
                     */
                    try {
                        \DB::beginTransaction();
                        // 收回MG平台游戏账号余额 add by tlt
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::MG) { // MG平台
                            $mgAmount = $game->getMGBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->MGDebit("DEBIT", $mgAmount, $playerGameAccount->account_user_name);
                        }
                        
                        // 收回PT平台游戏账号余额 add by tlt
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::PT) { // PT平台
                            $ptAmount = $game->getPTBalance($playerGameAccount); // 获取当前主账号余额
                            \WLog::info("PT,游戏账号余额一键转入主账号-->> ptAmount=$ptAmount");
                            $game->PTWithdraw($playerGameAccount, $ptAmount);
                        }
                        
                        // 收回SUNBET平台游戏账号余额 add by tlt
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::SUNBET) { // SUNBET平台
                            $sbAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $playerGameAccount->account_user_name);
                        }
                        
                        // 收回TGP平台游戏账号余额 add by tlt
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::TGP) {
                            $sbAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $playerGameAccount->account_user_name, 'TGP');
                        }
                        
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::GD) {
                            $sbAmount = $game->getSBBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $playerGameAccount->account_user_name, 'GD');
                        }
                        
                        // 收回波音平台游戏帐号余额
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::TTG) {
                            $ttgAmount = $game->ttgCheckUserBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->ttgTransfer($playerGameAccount->mainGamePlat->main_game_plat_code, $ttgAmount, 'out');
                        }
                        
                        // 收回VR平台游戏帐号余额
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::VR) {
                            $vrAmount = $game->vrCheckUserBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->vrTransfer($playerGameAccount->mainGamePlat->main_game_plat_code, $vrAmount, 'out');
                        }
                        
                        // 收回PNG平台游戏帐号余额
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::PNG) {
                            $pngAmount = $game->pngCheckUserBalance($playerGameAccount->account_user_name);
                            $game->pngTransfer($playerGameAccount->mainGamePlat->main_game_plat_code, $vrAmount, 'out');
                        }
                        // 收回波音平台游戏帐号余额
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::BBIN) {
                            $bbinAmount = $game->bbinCheckUsrBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->Transfer($playerGameAccount->mainGamePlat->main_game_plat_code, $bbinAmount, 'out');
                        }
                        // 收回沙巴平台游戏帐号余额
                        
                        if ($playerGameAccount->mainGamePlat->main_game_plat_code == MainGamePlat::ONWORKS) {
                            $onworksAmount = $game->onworksCheckUserBalance($playerGameAccount->account_user_name); // 获取当前游戏账号余额
                            $game->Transfer($playerGameAccount->mainGamePlat->main_game_plat_code, $onworksAmount, 'out');
                        }
                        
                        \DB::commit();
                        unset($game);
                    } catch (\Exception $e) {
                        throw $e;
                        \DB::rollBack();
                    }
                }
            }
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 修改会员PT游戏密码
     *
     * @param
     *            $id
     */
    public function updatePlayerPTGamePassword($id, Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6'
        ]);
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) {
            return $element->mainGamePlat->main_game_plat_code == PTGameGateway::getMainGamePlatCode();
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无PT游戏账户');
        }
        try {
            $gameRunTime = new GameGatewayRunTime(PTGameGateway::getMainGamePlatCode());
            $gameRunTime->updatePTPlayerPassword($player, $request->get('password'));
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 一键转出会员游戏账户余额至主账户
     *
     * @param
     *            $id
     * @param
     *            $gamePlatCode
     */
    public function withDrawPlayerGameAccount($id, $gamePlatId)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) use ($gamePlatId) {
            return $element->mainGamePlat->main_game_plat_id == $gamePlatId;
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无此游戏账户');
        }
        
        try {
            $amount = $gameAccount->amount;
            
            $mainGameplat = MainGamePlat::where('main_game_plat_id', $gamePlatId)->first();
            
            if ($amount > 0) {
                // $gameRunTime = new GameGatewayRunTime($gameAccount->mainGamePlat->main_game_plat_code);
                // $gameRunTime->withDrawFromPlayerGameAccount($player, $amount);
                $game = new GameController();
                // ONWORKS或者BBIN，游戏账号余额一键转入主账号
                if ((MainGamePlat::ONWORKS == $mainGameplat->main_game_plat_code) or (MainGamePlat::BBIN == $mainGameplat->main_game_plat_code) or (MainGamePlat::TTG == $mainGameplat->main_game_plat_code) or (MainGamePlat::VR == $mainGameplat->main_game_plat_code) or (MainGamePlat::PNG == $mainGameplat->main_game_plat_code)) {
                    
                    $money = $game->checkBalance($gameAccount->account_user_name, $gamePlatId);
                    if ($money) {
                        $game->Transfer($mainGameplat->main_game_plat_code, intval($money), 'OUT');
                    }
                }
                
                // MG平台,游戏账号余额一键转入主账号 add by tlt
                if (MainGamePlat::MG == $mainGameplat->main_game_plat_code) { // MG平台转账
                    $mgAmount = $game->getMGBalance($gameAccount->account_user_name); // 获取当前游戏账号余额
                    \WLog::info("MG平台,游戏账号余额一键转入主账号 add by tlt,金额：" . $mgAmount);
                    // var_dump($mgAmount) ;
                    // return ;
                    $game->MGDebit("DEBIT", $mgAmount, $gameAccount->account_user_name);
                }
                
                // Sunbet平台,游戏账号余额一键转入主账号 add by tlt
                if (MainGamePlat::SUNBET == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $sbAmount = $game->getSBBalance($gameAccount->account_user_name); // 获取当前游戏账号余额
                                                                                      // var_dump($sbAmount) ;
                    $game->SBDebit($sbAmount, $gameAccount->account_user_name);
                    // return ;
                }
                
                // GD平台,游戏账号余额一键转入主账号 add by tlt
                if (MainGamePlat::GD == $mainGameplat->main_game_plat_code) {
                    $gdAmount = $game->getSBBalance($gameAccount->account_user_name); // 获取当前游戏账号余额
                                                                                      // var_dump($sbAmount) ;
                    $game->SBDebit($gdAmount, $gameAccount->account_user_name, 'GD');
                }
                
                // TGP平台，游戏帐号余额一键转入主账号 add by tlt
                if (MainGamePlat::TGP == $mainGameplat->main_game_plat_code) {
                    $tgpAmount = $game->getSBBalance($gameAccount->account_user_name); // 获取当前游戏账号余额
                                                                                       // var_dump($sbAmount) ;
                    $game->SBDebit($tgpAmount, $gameAccount->account_user_name, 'TGP');
                }
                
                // PT,游戏账号余额一键转入主账号 add by tlt
                if (MainGamePlat::PT == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $ptAmount = $game->getPTBalance($gameAccount); // 获取当前主账号余额
                    \WLog::info("PT,游戏账号余额一键转入主账号-->> ptAmount=$ptAmount");
                    $game->PTWithdraw($gameAccount, $ptAmount);
                }
            }
            
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 一键将所有主账户余额转入会员游戏账户
     *
     * @param
     *            $id
     * @param
     *            $gamePlatCode
     */
    public function depositPlayerGameAccount($id, $gamePlatId)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) use ($gamePlatId) {
            return $element->mainGamePlat->main_game_plat_id == $gamePlatId;
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无此游戏账户');
        }
        
        $mainGameplat = MainGamePlat::where('main_game_plat_id', $gamePlatId)->first();
        
        // 根据玩家id和平台id获取游戏账号 add by tlt
        
        try {
            if ($player->main_account_amount > 0) {
                $game = new GameController();
                // ONWORKS或者BBIN，主账号余额一键转入游戏账号
                if ((MainGamePlat::ONWORKS == $mainGameplat->main_game_plat_code) or (MainGamePlat::BBIN == $mainGameplat->main_game_plat_code) or (MainGamePlat::VR == $mainGameplat->main_game_plat_code) or (MainGamePlat::TTG == $mainGameplat->main_game_plat_code) or (MainGamePlat::PNG == $mainGameplat->main_game_plat_code)) {
                    $game->Transfer($mainGameplat->main_game_plat_code, $player->main_account_amount, 'IN');
                }
                
                // MG平台,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::MG == $mainGameplat->main_game_plat_code) { // MG平台转账
                    $mgAmount = $player->main_account_amount; // 获取当前主账号余额
                                                              // var_dump($mgAmount) ;
                    $game->MGCredit("CREDIT", $mgAmount, $gameAccount->account_user_name);
                }
                
                // Sunbet,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::SUNBET == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $sbAmount = $player->main_account_amount; // 获取当前主账号余额
                    $game->SBCredit($sbAmount, $gameAccount->account_user_name);
                }
                
                // GD,主帐号余额一键转入游戏帐号
                if (MainGamePlat::GD == $mainGameplat->main_game_plat_code) {
                    $gdAmount = $player->main_account_amount; // 获取当前主账号余额
                    $game->SBCredit($gdAmount, $gameAccount->account_user_name, 'GD');
                }
                
                // GD,主帐号余额一键转入游戏帐号
                if (MainGamePlat::TGP == $mainGameplat->main_game_plat_code) {
                    $tgpAmount = $player->main_account_amount; // 获取当前主账号余额
                    $game->SBCredit($tgpAmount, $gameAccount->account_user_name, 'TGP');
                }
                
                // PT,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::PT == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $ptAmount = $player->main_account_amount; // 获取当前主账号余额
                    $game->PTDeposit($gameAccount, $ptAmount);
                }
            }
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 将会员转账锁定
     *
     * @param
     *            $id
     * @param
     *            $gamePlatCode
     */
    public function switchPlayerGameAccountTransferLockStatus($id, $gamePlatId)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) use ($gamePlatId) {
            return $element->mainGamePlat->main_game_plat_id == $gamePlatId;
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无此游戏账户');
        }
        $gameAccount->is_locked = ! $gameAccount->is_locked;
        try {
            $gameAccount->save();
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 将会员游戏关闭
     *
     * @param
     *            $id
     * @param
     *            $gamePlatCode
     */
    public function switchPlayerGameCloseStatus($id, $gamePlatId)
    {
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) use ($gamePlatId) {
            return $element->mainGamePlat->main_game_plat_id == $gamePlatId;
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无此游戏账户');
        }
        $gameAccount->is_need_repair = ! $gameAccount->is_need_repair;
        try {
            $gameAccount->save();
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /* 强制退出登录的游戏账号 add by tlt */
    public function forcedToLogOut($id, $gamePlatId)
    {
        /*
         * var_dump("forcedToLogOut") ;
         * return 11 ;
         */
        $player = $this->playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $gameAccount = $player->gameAccounts->filter(function ($element) use ($gamePlatId) {
            return $element->mainGamePlat->main_game_plat_id == $gamePlatId;
        })->first();
        if (! $gameAccount) {
            return $this->sendErrorResponse('该会员无此游戏账户');
        }
        
        try {
            $amount = $gameAccount->amount;
            
            $mainGameplat = MainGamePlat::where('main_game_plat_id', $gamePlatId)->first();
            
            if ($amount > 0) {
                // $gameRunTime = new GameGatewayRunTime($gameAccount->mainGamePlat->main_game_plat_code);
                // $gameRunTime->withDrawFromPlayerGameAccount($player, $amount);
                $game = new GameController();
                // BBIN，游戏账号退出登录
                if (MainGamePlat::BBIN == $mainGameplat->main_game_plat_code) {
                    $bin = new BBin();
                    $param = array(
                        'keyb' => BBin::LogoutKeyB,
                        'username' => $gameAccount->account_user_name,
                        'website' => BBin::Website,
                        'front' => '4',
                        'after' => '6'
                    );
                    $bin->remoteApi(BBin::API_Logout, $param, 0);
                }
                
                // ONWORKS，游戏账号退出登录
                if (MainGamePlat::ONWORKS == $mainGameplat->main_game_plat_code) {
                    $oneworks = new OneWorks();
                    $param = array(
                        'OpCode' => OneWorks::OPCODE,
                        'Playername' => $gameAccount->account_user_name
                    );
                    $result = $oneworks->remoteApi(OneWorks::API_KICKUSER, $param, 1);
                }
                
                // MG平台,由于MG没有登出接口，这里先注释掉,游戏账号退出登录 add by tlt
                /*
                 * if (MainGamePlat::MG == $mainGameplat->main_game_plat_code) { //MG平台转账
                 * $mgAmount = $game ->getMGBalance($gameAccount->account_user_name) ; //获取当前游戏账号余额
                 * \WLog::info("MG平台,游戏账号余额一键转入主账号 add by tlt,金额：".$mgAmount) ;
                 * //var_dump($mgAmount) ;
                 * //return ;
                 * $game ->MGDebit("DEBIT",$mgAmount,$gameAccount->account_user_name) ;
                 * $game ->logoutMG() ;
                 * }
                 */
                
                // Sunbet平台,游戏账号退出登录 add by tlt
                if (MainGamePlat::SUNBET == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $game->logoutSB($gameAccount->account_user_name);
                }
                
                // PT 游戏账号退出登录 add by tlt
                if (MainGamePlat::PT == $mainGameplat->main_game_plat_code) { // Sunbet平台转账
                    $game->logoutPT($gameAccount);
                }
            }
            
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 将会员强制踢下线
     *
     * @param
     *            $id
     * @return Response
     */
    // 即为踢出会员中心 modify by tlt
    public function kickPlayerOutLine($id)
    {
        $player = $this->playerRepository->with('gameAccounts')->findWithoutFail($id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $player->login_domain = null;
        $player->login_ip = null;
        $player->login_at = null;
        $player->is_online = false;
        // $player->save();
        /*
         * $playeraccount =PlayerGameAccount::where('player_id',$id)->get()->toArray();
         * foreach($playeraccount as $value)
         * {
         * if($value['main_game_plat_id']==1)
         * {
         * $bin=new BBin();
         * $param=array(
         * 'keyb'=>BBin::LogoutKeyB,
         * 'username'=>$value['account_user_name'],
         * 'website'=>BBin::Website,
         * 'front'=>'4',
         * 'after'=>'6'
         * );
         * $bin->remoteApi(BBin::API_Logout,$param,0);
         * }
         * else if($value['main_game_plat_id']==2)
         * {
         *
         * }
         * else if($value['main_game_plat_id']==3)
         * {
         *
         * }
         * else if($value['main_game_plat_id']==4)
         * {
         * $gameRunTime = new GameGatewayRunTime('pt');
         * $gameRunTime->logout($player);
         * }
         * else if($value['main_game_plat_id']==5)
         * {
         *
         * }
         * else if($value['main_game_plat_id']==8)
         * {
         * $oneworks =new OneWorks();
         * $param=array(
         * 'OpCode'=>OneWorks::OPCODE,
         * 'Playername'=>$value['account_user_name']
         * );
         * $result = $oneworks->remoteApi(OneWorks::API_KICKUSER,$param,1);
         * }
         * }
         */
        try {
            $player->save();
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 显示调整余额界面
     *
     * @param Request $request
     * @return $this|Response
     */
    public function showPlayerMainAccountAmountSettingModal(Request $request)
    {
        $this->validate($request, [
            'player_id' => 'required|integer'
        ], [], [
            'player_id' => '用户'
        ]);
        $player = $this->playerRepository->with([
            'carrier.carrierPayChannels' => function ($query) {
                $query->available();
            },
            'carrier.carrierPayChannels.payChannel.payChannelType',
            'carrier.mapGamePlats.gamePlat'
        ])
            ->findWithoutFail($request->get('player_id'));
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.player_account_adjust_logs.remain_account_edit')->with('player', $player);
    }

    /**
     * 显示调整红利界面
     *
     * @param Request $request
     * @return $this|Response
     */
    public function showPlayerBonusSettingModal(Request $request)
    {
        $this->validate($request, [
            'player_id' => 'required|integer'
        ], [], [
            'player_id' => '用户'
        ]);
        $player = $this->playerRepository->with([
            'carrier.mapGamePlats.gamePlat'
        ])->findWithoutFail($request->get('player_id'));
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.player_account_adjust_logs.bonus_edit')->with('player', $player);
    }

    /**
     * 显示调整返利界面
     *
     * @param Request $request
     * @return $this|Response
     */
    public function showPlayerRebateFinancialFlowSettingModal(Request $request)
    {
        $this->validate($request, [
            'player_id' => 'required|integer'
        ], [], [
            'player_id' => '用户'
        ]);
        $player = $this->playerRepository->with([
            'carrier.mapGamePlats.gamePlat'
        ])->findWithoutFail($request->get('player_id'));
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.player_account_adjust_logs.rebate_financial_flow_edit')->with('player', $player);
    }

    /**
     * 显示修改密码模态框
     *
     * @param Request $request
     */
    public function showVerifyLoginPasswordModal($player_id)
    {
        $player = $this->playerRepository->findWithoutFail($player_id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.edit_player_password')->with('player', $player);
    }

    /**
     * 修改用户密码
     *
     * @param Request $request
     */
    public function updatePlayerLoginPassword($player_id, Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'password_repeat' => 'required|same:password'
        ], [
            'password_repeat.same' => '两次密码输入不一致'
        ]);
        $player = $this->playerRepository->findWithoutFail($player_id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $player->password = bcrypt($request->get('password'));
        $player->update();
        return $this->sendSuccessResponse();
    }

    /**
     * 显示修改取款密码模态框
     *
     * @param Request $request
     */
    public function showVerifyPayPasswordModal($player_id)
    {
        $player = $this->playerRepository->findWithoutFail($player_id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.players.edit_player_pay_password')->with('player', $player);
    }

    /**
     * 修改用户取款密码
     *
     * @param Request $request
     */
    public function updatePlayerPayPassword($player_id, Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'password_repeat' => 'required|same:password'
        ], [
            'password_repeat.same' => '两次密码输入不一致'
        ]);
        $player = $this->playerRepository->findWithoutFail($player_id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $player->pay_password = bcrypt($request->get('password'));
        $player->update();
        return $this->sendSuccessResponse();
    }

    /**
     * 切换会员账户状态, 如果是正常,那么将会置为关闭, 如果是其他状态, 那么将会置为正常
     *
     * @param
     *            $player_id
     * @return Response
     */
    public function togglePlayerAccountStatus($player_id)
    {
        $player = $this->playerRepository->findWithoutFail($player_id);
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        $player->user_status = $player->user_status == Player::USER_STATUS_OK ? Player::USER_STATUS_CLOSED : Player::USER_STATUS_OK;
        $player->update();
        return $this->sendSuccessResponse();
    }

    /**
     * 到处会员信息字段选择
     */
    public function exportInfoFieldSelect()
    {
        return view('Carrier.players.export_player_field');
    }

    public function exportInfo(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'in:on',
            'real_name' => 'in:on',
            'mobile' => 'in:on',
            'email' => 'in:on',
            'qq_account' => 'in:on',
            'wechat' => 'in:on',
            'player_level_id' => 'in:on',
            'recommend_player_id' => 'in:on',
            'agent_id' => 'in:on',
            'main_account_amount' => 'in:on',
            'total_win_loss' => 'in:on',
            'deposit_amount' => 'in:on',
            'withdraw_amount' => 'in:on'
        ]);
        $fields = [
            'P.user_name'
        ];
        $builder = \DB::table(\DB::raw('inf_player P'));
        $displayRowTitles = [
            'user_name' => '用户名'
        ];
        if ($request->get('real_name')) {
            $fields[] = 'P.real_name';
            $displayRowTitles['real_name'] = '真实姓名';
        }
        if ($request->get('mobile')) {
            $fields[] = 'P.mobile';
            $displayRowTitles['mobile'] = '联系方式';
        }
        if ($request->get('email')) {
            $fields[] = 'P.email';
            $displayRowTitles['email'] = '电子邮件';
        }
        if ($request->get('qq_account')) {
            $fields[] = 'P.qq_account';
            $displayRowTitles['qq_account'] = 'QQ';
        }
        if ($request->get('wechat')) {
            $fields[] = 'P.wechat';
            $displayRowTitles['wechat'] = '微信';
        }
        if ($request->get('main_account_amount')) {
            $fields[] = 'P.main_account_amount';
            $displayRowTitles['main_account_amount'] = '账户余额';
        }
        if ($request->get('total_win_loss')) {
            $fields[] = 'P.total_win_loss';
            $displayRowTitles['total_win_loss'] = '总输赢';
        }
        if ($request->get('player_level_id')) {
            $builder->leftJoin(\DB::raw('inf_carrier_player_level L'), 'L.id', '=', 'P.player_level_id');
            $fields[] = 'L.level_name';
            $displayRowTitles['level_name'] = '会员等级';
        }
        if ($request->get('recommend_player_id')) {
            $builder->leftJoin(\DB::raw('inf_player R'), 'R.player_id', '=', 'P.recommend_player_id');
            $fields[] = \DB::raw('R.user_name as recommend_user_name');
            $fields[] = \DB::raw('R.real_name as recommend_real_name');
            $displayRowTitles['recommend_user_name'] = '推荐人用户名';
            $displayRowTitles['recommend_real_name'] = '推荐人名称';
        }
        if ($request->get('agent_id')) {
            $builder->leftJoin(\DB::raw('inf_agent A'), 'A.id', '=', 'P.agent_id');
            $fields[] = \DB::raw("CASE A.is_default WHEN 1 THEN '系统代理' ELSE A.username END as agent_user_name");
            $fields[] = \DB::raw("CASE A.is_default WHEN 1 THEN '系统代理' ELSE A.realname END as agent_real_name");
            $displayRowTitles['agent_user_name'] = '代理用户名';
            $displayRowTitles['agent_real_name'] = '代理名称';
        }
        if ($request->get('deposit_amount')) {
            $builder->leftJoin(\DB::raw("(SELECT
                                            SUM(amount) AS amount ,
                                            player_id ,
                                            `status`
                                        FROM
                                            log_player_deposit_pay
                                        GROUP BY
                                            player_id,`status`
                                        HAVING
                                            `status` = '1') D"), 'P.player_id', '=', 'D.player_id');
            $fields[] = \DB::raw('D.amount as depositAmount');
            $displayRowTitles['depositAmount'] = '存款额';
        }
        if ($request->get('withdraw_amount')) {
            $builder->leftJoin(\DB::raw("(SELECT
                                                SUM(finally_withdraw_amount) AS finally_withdraw_amount ,
                                                player_id ,
                                                `status`
                                            FROM
                                                log_player_withdraw
                                            GROUP BY
                                                player_id,`status`
                                            HAVING
                                                `status` = '1'
                                        ) W "), 'P.player_id', '=', 'W.player_id');
            $fields[] = \DB::raw('W.finally_withdraw_amount as withdrawAmount');
            $displayRowTitles['withdrawAmount'] = '取款额';
        }
        $result = $builder->select($fields)
            ->orderBy('P.player_id')
            ->get();
        $fileName = Carbon::now()->timestamp . rand(1000, 9999);
        \Excel::create($fileName, function (LaravelExcelWriter $excel) use ($displayRowTitles, $result) {
            $excel->sheet('会员基本信息', function ($sheet) use ($displayRowTitles, $result) {
                $array = [
                    array_values($displayRowTitles)
                ];
                $result->each(function ($item) use ($displayRowTitles, &$array) {
                    $data = [];
                    foreach ($displayRowTitles as $field => $value) {
                        $data[] = $item->$field;
                    }
                    $array[] = $data;
                });
                $sheet->fromArray($array);
            });
        })->store('xls', storage_path('app/public/user_export'));
        return self::sendResponse(asset('storage/user_export/' . $fileName . '.xls'));
    }
}
