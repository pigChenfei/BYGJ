<?php
/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/17
 * Time: 下午2:05
 */
namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\Def\GamePlat;
use App\Models\Image\CarrierImage;
use App\Models\Player;
use App\Models\PlayerCollect;
use App\Vendor\GameGateway\PT\PTGameGateway;
use App\Vendor\GameGateway\Bbin\BBin;
use App\Vendor\GameGateway\OneWorks\OneWorks;
use App\Models\Map\CarrierGame;
use App\Models\PlayerGameAccount;
use App\Models\Def\MainGamePlat;
use App\Models\Def\Game;
use App\Models\Carrier;
use App\Vendor\GameGateway\Sunbet\Sunbet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Vendor\GameGateway\MGEUR_API\MGEUR_API;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use APP\Services\WinwinWebTemplateService;
use App\Models\PlayerTransfer;
use App\Vendor\Game\VR;
use App\Vendor\Game\TTG;
use App\Vendor\Game\PNG;

class GameController extends AppBaseController
{

    /**
     * 生成随机字符串
     */
    public function generate_value($length = 8)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY0123456789';
        $value = '';
        for ($i = 0; $i < $length; $i ++) {
            $value .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $value;
    }

    /**
     * PT登录跳转
     *
     * @param
     *            $ptGameCode
     * @return mixed
     */
    public function loginPTGame($ptGameCode)
    {
        // http://cache.download.banner.greenjade88.com/casinoclient.html?language=ZH-CN&game=
        $ptGameGateway = new PTGameGateway();
        try {
            $gameAccount = $ptGameGateway->getplayeraccount(\WinwinAuth::memberUser());
            $pageEntity = $ptGameGateway->loginPageEntity($gameAccount, $ptGameCode);
            
            if ($this->isMobile()) {
                return view('Web.game_H5login')->with('playerLoginPageEntity', $pageEntity);
            } else {
                return view('Web.game_login')->with('playerLoginPageEntity', $pageEntity);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /* PT存款 add by tlt 主账号转到游戏账号 */
    public function PTDeposit($playerGameAccount = null, $amount = 1.00)
    {
        $PTGameGatewayObj = new PTGameGateway();
        // $amount = 1.00 ; //存款金额
        if (empty($playerGameAccount)) {
            $gameAccount = $PTGameGatewayObj->getPlayerAccount(\WinwinAuth::memberUser());
        } else {
            $gameAccount = $playerGameAccount;
        }
        try {
            // $gameAccount = $PTGameGatewayObj ->getPlayerAccount($user);
            $resData = $PTGameGatewayObj->depositToPlayerGameAccount($gameAccount, $amount);
        } catch (\Exception $e) {
            throw $e; // 抛出异常
        }
        return $resData;
    }

    /* PT取款 add by tlt 游戏账号转到主账号 */
    public function PTWithdraw($playerGameAccount = null, $amount = 1.00)
    {
        $PTGameGatewayObj = new PTGameGateway();
        // $amount = 1.00 ; //取款金额
        if (empty($playerGameAccount)) {
            $gameAccount = $PTGameGatewayObj->getPlayerAccount(\WinwinAuth::memberUser());
        } else {
            $gameAccount = $playerGameAccount;
        }
        \WLog::info("PTWithdraw --->> amount=$amount");
        try {
            
            $resData = $PTGameGatewayObj->withdrawFromPlayerGameAccount($gameAccount, $amount);
        } catch (\Exception $e) {
            throw $e; // 抛出异常
        }
        return $resData;
        // var_dump($resData) ;
    }

    /* PT查询账号余额 add by tlt */
    public function getPTBalance($playerGameAccount = null)
    {
        $PTGameGatewayObj = new PTGameGateway();
        if (empty($playerGameAccount)) {
            $gameAccount = $PTGameGatewayObj->getPlayerAccount(\WinwinAuth::memberUser());
        } else {
            $gameAccount = $playerGameAccount;
        }
        try {
            // $gameAccount = $PTGameGatewayObj ->getPlayerAccount($user);
            $resData = $PTGameGatewayObj->fetchPlayerBalanceInfo($gameAccount);
        } catch (\Exception $e) {
            throw $e; // 抛出异常
        }
        // var_dump($resData) ;
        return $resData;
    }

    /* PT 游戏账号退出登录 add by tlt */
    public function logoutPT(PlayerGameAccount $gameAccount = null)
    {
        $PTGameGatewayObj = new PTGameGateway();
        return $PTGameGatewayObj->logout($gameAccount);
    }

    /* Sunbet 游戏账号退出登录 add by tlt */
    public function logoutSB($playerGameAccount = null)
    {
        $SBObj = new Sunbet();
        return $SBObj->deauthorize($playerGameAccount);
    }

    /* MG 游戏账号退出登录 add by tlt */
    public function logoutMG()
    {
        $MGObj = new MGEUR_API();
    }

    /**
     * 导航栏老虎机
     *
     * @return mixed
     */
    public function slotMachine(Request $request)
    {
        $pageSize = 20;
        $main_game_plat_array = \WTemplate::gamePlat([
            'game_plat_name',
            'like',
            '%电子游戏%'
        ]);
        
        $game_mcategory_array = config('constants.game_mcategory');
        $game_lines_array = config('constants.game_lines');
        $template = \WinwinAuth::currentWebCarrier()->template;
        $ptGameList = CarrierGame::where('map_carrier_games.status', 1)->with('game.gamePlat')->whereHas('game',
            function ($query) {
                if (is_wap_mobile()) {
                    $query->where('is_wap', 1)
                        ->where('def_games.status', 1);
                } else {
                    $query->where('flashcode', 1)
                        ->where('def_games.status', 1);
                }
            });
        // dump($ptGameList->paginate($pageSize));die;
        // 推荐游戏
        $is_recommend = $request->get('is_recommend', 0);
        if ($is_recommend) {
            $ptGameList = $ptGameList->whereHas('game', function ($query) use ($is_recommend) {
                $query->where('is_recommend', $is_recommend);
            });
        }
        // 最新游戏
        $is_new = $request->get('is_new', 0);
        if ($is_new) {
            $ptGameList = $ptGameList->orderBy('map_carrier_games.updated_at', 'desc');
        }
        // 我的游戏
        $is_mine = $request->get('is_mine', 0);
        if ($is_mine) {
            $ptGameList = $ptGameList->whereExists(
                function ($query) {
                    $query->select(\DB::raw(1))
                        ->from('inf_player_collect')
                        ->whereRaw('inf_player_collect.map_carrier_game_id = map_carrier_games.id and inf_player_collect.player_id = ' . \WinwinAuth::memberUser()->player_id);
                });
        }
        // 名字搜索
        $ptGameName = $request->get('gameName', '');
        if ($ptGameName) {
            $ptGameList = $ptGameList->whereHas('game',
                function ($query) use ($ptGameName) {
                    $query->where('game_name', 'like', '%' . $ptGameName . '%');
                });
        }
        // 游戏平台搜索
        $main_game_plat = $request->get('main_game_plat', 0);
        if ($main_game_plat) {
            $ptGameList = $ptGameList->whereHas('game',
                function ($query) use ($main_game_plat) {
                    $query->where('main_game_plat_id', $main_game_plat);
                });
        }
        // 游戏类型搜索
        $game_mcategory = $request->get('game_mcategory', 0);
        if ($game_mcategory) {
            $ptGameList = $ptGameList->whereHas('game',
                function ($query) use ($game_mcategory) {
                    $query->where('game_mcategory', $game_mcategory);
                });
        }
        // 游戏线路搜索
        $game_lines = $request->get('game_lines', 0);
        if ($game_lines) {
            $ptGameList = $ptGameList->whereHas('game', function ($query) use ($game_lines) {
                $query->where('game_lines', $game_lines);
            });
        }
        // 奖金池搜索
        $gold_pool = $request->get('gold_pool', 0);
        if ($gold_pool) {
            $ptGameList = $ptGameList->whereHas('game', function ($query) use ($gold_pool) {
                $query->where('gold_pool', '!=', 0.00);
            });
        }
        // 试玩
        $is_demo = $request->get('is_demo', 0);
        if ($is_demo) {
            $ptGameList = $ptGameList->whereHas('game', function ($query) use ($is_demo) {
                $query->where('is_demo', 1);
            });
        }
        
        // 排序
        $sort = array();
        $sort_popular = $sort_reward = 99;
        $sort_popular_get = $request->get('sort_popular', 0);
        if ($sort_popular_get == 99) {
            $sort['game_popularity'] = 'desc';
            $sort_popular = 1;
        } elseif ($sort_popular_get == 1) {
            $sort['game_popularity'] = 'asc';
            $sort_popular = 2;
        } elseif ($sort_popular_get == 2) {
            $sort_popular = 99;
        }
        $sort_reward_get = $request->get('sort_reward', 0);
        if ($sort_reward_get == 99) {
            $sort['return_award_rate'] = 'desc';
            $sort_reward = 1;
        } elseif ($sort_reward_get == 1) {
            $sort['return_award_rate'] = 'asc';
            $sort_reward = 2;
        } elseif ($sort_reward_get == 2) {
            $sort_reward = 99;
        }
        if ($sort) {
            foreach ($sort as $k => $v) {
                $ptGameList = $ptGameList->leftJoin('def_games', 'map_carrier_games.game_id', '=', 'def_games.game_id')->orderBy('def_games.' . $k, $v);
            }
        }
        $ptGameList = $ptGameList->paginate($pageSize);
        // dump($ptGameList);die;
        
        if ($request->ajax()) {
            if (is_wap_mobile()) {
                $ptGameList->map(
                    function (CarrierGame $carrierGame) {
                        if (\WinwinAuth::memberUser()) {
                            $carrierGame->collect_info = $carrierGame->collect($carrierGame->id, \WinwinAuth::memberUser()->player_id);
                        } else {
                            $carrierGame->collect_info = 0;
                        }
                    });
                // dump($ptGameList);
                return $this->sendResponse($ptGameList);
            } else {
                return \WTemplate::slotMachineList()->with('ptGameList', $ptGameList);
            }
        }
        // 首页轮播图
        $images = CarrierImage::with('imageCategory')->whereHas('imageCategory', function ($query) {
            $query->where('category_name', '电子游艺');
        })
            ->get();
        if ($this->isMobile()) {
            return \WTemplate::slotMachinePage('m')->with(
                compact('main_game_plat_array', 'ptGameList', 'game_mcategory_array', 'game_lines_array', 'sort_popular', 'sort_reward', 'images'));
        } else {
            return \WTemplate::slotMachinePage()->with(
                compact('main_game_plat_array', 'ptGameList', 'game_mcategory_array', 'game_lines_array', 'sort_popular', 'sort_reward', 'images'));
        }
    }

    // 进入电子游艺试玩
    /**
     * @game_id 游戏id
     */
    public function joinDemoElectronicGame($game_id)
    {
        $isRole = CarrierGame::where('game_id', intval($game_id))->where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        if ($isRole) {
            $game = Game::with([
                'mainGamePlat'
            ])->where('game_id', $game_id)
                ->first()
                ->toArray();
            
            if (MainGamePlat::BBIN == $game['main_game_plat']['main_game_plat_code']) {
                echo '<script> window.location="http://777.bbingames.net/";</script>';
            } else if (MainGamePlat::PT == $game['main_game_plat']['main_game_plat_code']) {
                echo '<script> window.location="http://cache.download.banner.greenjade88.com/casinoclient.html?language=en&game=' . $game['game_type'] .
                     '&mode=offline&currency=cny"</script>';
            } else if (MainGamePlat::MG == $game['main_game_plat']['main_game_plat_code']) {
                $gameInfo = Game::where('game_id', $game_id)->first();
                $gameCode = $gameInfo->game_type;
                $this->launchItem($gameCode, '1001', false);
            }
        } else {
            return $this->sendErrorResponse('未找到页面...', 404);
        }
    }

    // 进入电子游艺
    /**
     * @game_id 游戏id
     */
    public function joinElectronicGame($game_id)
    {
        $isRole = CarrierGame::where('game_id', intval($game_id))->where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->first();
        if ($isRole) {
            $game = Game::with([
                'mainGamePlat'
            ])->where('game_id', $game_id)->first();
            $game->increment('game_popularity');
            $game = $game->toArray();
            
            // 查询相关用户名是否存在BBin平台有注册
            $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $game['main_game_plat']['main_game_plat_id'])->where('player_id',
                \WinwinAuth::memberUser()->player_id)->first();
            $accountUserName = '';
            
            if (MainGamePlat::BBIN == $game['main_game_plat']['main_game_plat_code']) {
                if (is_null($playerGameAccount)) {
                    $accountUserName = $this->gamePlatReg(MainGamePlat::BBIN);
                }
                $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
                if ($this->isMobile()) {
                    $this->bbinForwardGameH5By5($game, $accountUserName);
                } else {
                    $this->bbinForwardGameH5($game, $accountUserName);
                }
            }
            
            $gameInfo = Game::where('game_id', $game_id)->first();
            $gameCode = $gameInfo->game_type;
            /* add by tlt start ***** */
            if (MainGamePlat::PT == $game['main_game_plat']['main_game_plat_code']) { // 进入PT电游游戏
                                                                                      // var_dump("PT".$gameCode) ;
                                                                                      // 这里PT进入PT游戏过程中有html页面加载，所以需要return返回,否则html页面不能被执行
                return $this->loginPTGame($gameCode);
            }
            if (MainGamePlat::MG == $game['main_game_plat']['main_game_plat_code']) { // 进入MG电游游戏
                $this->launchItem($gameCode, '1002');
            }
            
            if (MainGamePlat::SUNBET == $game['main_game_plat']['main_game_plat_code']) { // 进入TGP电游游戏，TGP在sunbet平台下
                $this->gameLauncher('SB', $gameCode);
            }
            
            if (MainGamePlat::TGP == $game['main_game_plat']['main_game_plat_code']) {
                $this->gameLauncher('TGP', $gameCode);
            }
            if (MainGamePlat::TTG == $game['main_game_plat']['main_game_plat_code']) {
                if (is_null($playerGameAccount)) {
                    $accountUserName = $this->gamePlatReg(MainGamePlat::TTG);
                }
                $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
                $this->ttgplaygameH5($game, $accountUserName);
            }
            if (MainGamePlat::PNG == $game['main_game_plat']['main_game_plat_code']) {
                if (is_null($playerGameAccount)) {
                    $accountUserName = $this->gamePlatReg(MainGamePlat::PNG);
                }
                $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
                $this->pngplaygameH5($game, $accountUserName);
            }
        } else {
            return $this->sendErrorResponse('未找到页面...', 404);
        }
        $game = Game::with([
            'mainGamePlat'
        ])->where('game_id', $game_id)->first();
        $game->increment('game_popularity');
        $game = $game->toArray();
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $game['main_game_plat']['main_game_plat_id'])->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $accountUserName = '';
        
        if (MainGamePlat::BBIN == $game['main_game_plat']['main_game_plat_code']) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::BBIN);
            }
            $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
            if ($this->isMobile()) {
                $this->bbinForwardGameH5By5($game, $accountUserName);
            } else {
                $this->bbinForwardGameH5($game, $accountUserName);
            }
        }
        
        $gameInfo = Game::where('game_id', $game_id)->first();
        $gameCode = $gameInfo->game_type;
        /* add by tlt start ***** */
        if (MainGamePlat::PT == $game['main_game_plat']['main_game_plat_code']) { // 进入PT电游游戏
                                                                                  // var_dump("PT".$gameCode) ;
                                                                                  // 这里PT进入PT游戏过程中有html页面加载，所以需要return返回,否则html页面不能被执行
            return $this->loginPTGame($gameCode);
        }
        if (MainGamePlat::MG == $game['main_game_plat']['main_game_plat_code']) { // 进入MG电游游戏
            $this->launchItem($gameCode, '1002');
        }
        
        if (MainGamePlat::SUNBET == $game['main_game_plat']['main_game_plat_code']) { // 进入TGP电游游戏，TGP在sunbet平台下
            $this->gameLauncher('SB', $gameCode);
        }
        
        if (MainGamePlat::TGP == $game['main_game_plat']['main_game_plat_code']) {
            $this->gameLauncher('TGP', $gameCode);
        }
        if (MainGamePlat::TTG == $game['main_game_plat']['main_game_plat_code']) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::TTG);
            }
            $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
            $this->ttgplaygameH5($game, $accountUserName);
        }
        if (MainGamePlat::PNG == $game['main_game_plat']['main_game_plat_code']) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::PNG);
            }
            $accountUserName = empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName;
            $this->pngplaygameH5($game, $accountUserName);
        }
        /* add by tlt end ***** */
    }

    // BBIN注册
    private function bbinCreateMember()
    {
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::BBIN)->first();
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $newPlayerGameAccount = new PlayerGameAccount();
        $bin = new BBin();
        do {
            $param = array(
                'keyb' => BBin::CreateMemberKeyB,
                'username' => $mainGamePlat->account_pre . $carrier->id . 'Z' . $this->generate_value(6),
                'uppername' => BBin::Uppername,
                'website' => BBin::Website,
                'front' => '5',
                'after' => '2'
            );
            $result = $bin->remoteApi(BBin::API_CreateMember, $param, 0);
        } while (! $result['result']);
        
        $newPlayerGameAccount->account_user_name = $param['username'];
        $newPlayerGameAccount->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $newPlayerGameAccount->player_id = \WinwinAuth::memberUser()->player_id;
        
        $newPlayerGameAccount->save();
        return $newPlayerGameAccount->account_user_name;
    }

    private function pngCreateMember()
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::PNG)->first();
        
        // 查询相关用户名是否存在VR平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $accountusername = false;
        $newPlayerGameAccount = new PlayerGameAccount();
        $png = new PNG();
        $output = $png->createMember($mainGamePlat->account_pre . $carrier->id);
        $png->login($output['username']);
        $accountusername = $output['username'];
        $newPlayerGameAccount->account_user_name = $accountusername;
        // 入库操作
        $newPlayerGameAccount->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $newPlayerGameAccount->player_id = \WinwinAuth::memberUser()->player_id;
        
        $newPlayerGameAccount->save();
        return $newPlayerGameAccount->account_user_name;
    }

    private functIon ttgCreateMember()
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::TTG)->first();
        
        // 查询相关用户名是否存在VR平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $accountusername = false;
        $newPlayerGameAccount = new PlayerGameAccount();
        $ttg = new TTG();
        $output = $ttg->createMember($mainGamePlat->account_pre . $carrier->id);
        $accountusername = $output['username'];
        $newPlayerGameAccount->account_user_name = $accountusername;
        // 入库操作
        $newPlayerGameAccount->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $newPlayerGameAccount->player_id = \WinwinAuth::memberUser()->player_id;
        
        $newPlayerGameAccount->save();
        return $newPlayerGameAccount->account_user_name;
    }

    private function vrCreateMember()
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::VR)->first();
        
        // 查询相关用户名是否存在VR平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $accountusername = false;
        $newPlayerGameAccount = new PlayerGameAccount();
        $vr = new VR();
        do {
            $accountusername = $vr->createMember($mainGamePlat->account_pre . $carrier->id . 'Z' . $this->generate_value(6));
        } while (! $accountusername);
        
        $newPlayerGameAccount->account_user_name = $accountusername;
        // 入库操作
        $newPlayerGameAccount->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $newPlayerGameAccount->player_id = \WinwinAuth::memberUser()->player_id;
        
        $newPlayerGameAccount->save();
        return $newPlayerGameAccount->account_user_name;
    }

    // OnWork注册
    private function onWorksCreateMember()
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::ONWORKS)->first();
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $newPlayerGameAccount = new PlayerGameAccount();
        // 注册沙巴帐号
        $oneworks = new OneWorks();
        do {
            $param = array(
                'OpCode' => OneWorks::OPCODE,
                'PlayerName' => $mainGamePlat->account_pre . $carrier->id . 'Z' . $this->generate_value(6),
                'OddsType' => '2',
                'MaxTransfer' => '500000',
                'MinTransfer' => '1'
            );
            $result = $oneworks->remoteApi(OneWorks::API_CREATEMEMBER, $param, 1);
        } while ($result['error_code']);
        $newPlayerGameAccount->account_user_name = $param['PlayerName'];
        // 入库操作
        $newPlayerGameAccount->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $newPlayerGameAccount->player_id = \WinwinAuth::memberUser()->player_id;
        
        $newPlayerGameAccount->save();
        return $newPlayerGameAccount->account_user_name;
    }

    // 游戏平台注册
    /**
     * @gameplat 游戏平台 例如 MainGamePlat::BBIN
     *
     * @return 返回帐号名
     */
    public function gamePlatReg($gameplat)
    {
        // 获取相关平台数据
        if ($gameplat == MainGamePlat::BBIN) {
            return $this->bbinCreateMember();
        } else if ($gameplat == MainGamePlat::ONWORKS) {
            return $this->onWorksCreateMember();
        } else if ($gameplat == MainGamePlat::VR) {
            return $this->vrCreateMember();
        } else if ($gameplat == MainGamePlat::TTG) {
            return $this->ttgCreateMember();
        } else if ($gameplat == MainGamePlat::PNG) {
            return $this->pngCreateMember();
        }
    }

    public function loginVRHall(Request $request)
    {
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::VR)->first();
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        
        $carrier = \WinwinAuth::currentWebCarrier();
        $vr = new VR();
        $accountUserName = '';
        
        if (is_null($playerGameAccount)) {
            $accountUserName = $this->gamePlatReg(MainGamePlat::VR);
        } else {
            $accountUserName = $playerGameAccount->account_user_name;
        }
        $vr->login($accountUserName);
    }

    // 进入BBin大厅
    public function loginBBinHall($providercode)
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::BBIN)->first();
        // var_dump($mainGamePlat) ;return ;
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $accountUserName = '';
        $carrier = \WinwinAuth::currentWebCarrier();
        
        $bin = new BBin();
        
        if (is_null($playerGameAccount)) {
            $accountUserName = $this->gamePlatReg(MainGamePlat::BBIN);
        }
        
        // 登录平台
        $param = array(
            'keyb' => BBin::LoginKeyB,
            'front' => '8',
            'after' => '1',
            'page_site' => $providercode,
            'username' => empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_Login, $param, 1);
    }

    // 进入沙巴大厅
    public function loginOneWorkHall(Request $request)
    {
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::ONWORKS)->first();
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $accountUserName = '';
        $carrier = \WinwinAuth::currentWebCarrier();
        
        if (is_null($playerGameAccount)) {
            $accountUserName = $this->gamePlatReg(MainGamePlat::ONWORKS);
        }
        $oneworks = new OneWorks();
        $param = array(
            'OpCode' => OneWorks::OPCODE,
            'PlayerName' => empty($accountUserName) ? $playerGameAccount->account_user_name : $accountUserName
        );
        $json = $oneworks->remoteApi(OneWorks::API_LOGIN, $param, 1);
        \Log::info('进入沙巴体育', [
            '返回数据' => $json,
            '请求数据' => $param
        ]);
        unset($param);
        if (isset($json['sessionToken'])) {
            $oneworks = new OneWorks();
            $param = array(
                'g' => $json['sessionToken'],
                'lang' => 'cs',
                'OType' => '2'
            );
            if ($this->isMobile()) {
                $oneworks->joinH5Player($param, $oneworks->API_MKI());
            } else {
                $oneworks->joinPlayer($param, $oneworks->API_MKI());
            }
        } else {
            abort(404);
        }
    }

    public function ttgplaygameH5($game, $accountUserName)
    {
        $ttg = new TTG();
        $token = $ttg->login($accountUserName);
        $ttg->playgameH5($token, $game['game_type'], 0, $game['game_code']);
    }

    public function pngplaygameH5($game, $accountUserName)
    {
        $png = new PNG();
        if ($this->isMobile()) {
            $png->playmobilegameH5($accountUserName, $game['game_code']);
        } else {
            $png->playgameH5($accountUserName, $game['game_code']);
        }
    }

    public function bbinForwardGameH5By5($game, $accountUserName)
    {
        // login2登录
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::Login2KeyB,
            'front' => '8',
            'after' => '1',
            'username' => $accountUserName,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_Login2, $param, 0);
        
        // H5游戏登录,ForwardGameH5By5 电子H5 游戏,ForwardGameH5By30 捕鱼达人,ForwardGameH5By38 捕鱼大师
        $bin = new BBin();
        unset($param);
        $param = array(
            'front' => '7',
            'after' => '1',
            'username' => $accountUserName,
            'uppername' => BBin::Uppername,
            'gametype' => $game['game_type'],
            'website' => BBin::Website
        );
        if (2 != $game['game_mcategory']) {
            $param['keyb'] = BBin::PlayGameByH5KeyB;
            $bin->remoteApi(BBin::API_ForwardGameH5By5, $param, 1);
        } else {
            $param['uppername'] = BBin::Uppername;
            if (2 == $game['sub_game_kind']) {
                // 捕鱼达人
                $param['keyb'] = BBin::ForwardGameH5By30KeyB;
                $bin->remoteApi(BBin::API_ForwardGameH5By30, $param, 1);
            } else {
                // 捕鱼大师
                $param['keyb'] = BBin::ForwardGameH5By38KeyB;
                $bin->remoteApi(BBin::API_ForwardGameH5By38, $param, 1);
            }
        }
    }

    // 进入BBin电子游戏H5
    public function bbinForwardGameH5($game, $accountUserName)
    {
        // login2登录
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::Login2KeyB,
            'front' => '8',
            'after' => '1',
            'username' => $accountUserName,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_Login2, $param, 0);
        
        // H5游戏登录,ForwardGameH5By5 电子H5 游戏,ForwardGameH5By30 捕鱼达人,ForwardGameH5By38 捕鱼大师
        $bin = new BBin();
        unset($param);
        $param = array(
            'front' => '7',
            'after' => '1',
            'username' => $accountUserName,
            'gametype' => $game['game_type'],
            'website' => BBin::Website
        );
        if (2 != $game['game_mcategory']) {
            $param['keyb'] = BBin::PlayGameByH5KeyB;
            $param['gamekind'] = $game['game_kind'];
            $bin->remoteApi(BBin::API_PlayGameByH5, $param, 1);
        } else {
            $param['uppername'] = BBin::Uppername;
            if (2 == $game['sub_game_kind']) {
                // 捕鱼达人
                $param['keyb'] = BBin::ForwardGameH5By30KeyB;
                $bin->remoteApi(BBin::API_ForwardGameH5By30, $param, 1);
            } else {
                // 捕鱼大师
                $param['keyb'] = BBin::ForwardGameH5By38KeyB;
                $bin->remoteApi(BBin::API_ForwardGameH5By38, $param, 1);
            }
        }
    }

    // 转帐
    public function Transfer($main_game_plat, $money, $direction)
    {
        if (\WinwinAuth::currentWebCarrier()->remain_quota < $money && strtolower($direction) == 'in') {
            echo '运营商积分不足，不能充值';
            exit();
        }
        
        // 获取相关平台数据
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', $main_game_plat)->first();
        
        $money = intval($money);
        
        // 查询相关用户名是否存在BBin平台有注册
        $playerGameAccount = PlayerGameAccount::where('main_game_plat_id', $mainGamePlat->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        $accountUserName = '';
        $amount = 0;
        // BBIN平台
        if (MainGamePlat::BBIN == $main_game_plat) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::BBIN);
            } else {
                $accountUserName = $playerGameAccount->account_user_name;
                $amount = \WinwinAuth::memberUser()->main_account_amount;
            }
            
            $this->bbinTransfer($accountUserName, $money, $direction);
        }
        // 沙巴平台
        if (MainGamePlat::ONWORKS == $main_game_plat) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::ONWORKS);
            } else {
                $accountUserName = $playerGameAccount->account_user_name;
                $amount = \WinwinAuth::memberUser()->main_account_amount;
            }
            $this->onworksTransfer($accountUserName, $money, $direction);
        }
        
        // VR平台
        if (MainGamePlat::VR == $main_game_plat) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::VR);
            } else {
                $accountUserName = $playerGameAccount->account_user_name;
                $amount = \WinwinAuth::memberUser()->main_account_amount;
            }
            $this->vrTransfer($accountUserName, $money, $direction);
        }
        
        // TTG平台
        if (MainGamePlat::TTG == $main_game_plat) {
            
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::TTG);
            } else {
                $accountUserName = $playerGameAccount->account_user_name;
                $amount = \WinwinAuth::memberUser()->main_account_amount;
            }
            $this->ttgTransfer($accountUserName, $money, $direction);
        }
        
        // PNG平台
        if (MainGamePlat::PNG == $main_game_plat) {
            if (is_null($playerGameAccount)) {
                $accountUserName = $this->gamePlatReg(MainGamePlat::PNG);
            } else {
                $accountUserName = $playerGameAccount->account_user_name;
                $amount = \WinwinAuth::memberUser()->main_account_amount;
            }
            $this->pngTransfer($accountUserName, $money, $direction);
        }
    }

    public function bbinCheckUsrBalance($accountusername)
    {
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::CheckUsrBalanceKeyB,
            'front' => '9',
            'after' => '6',
            'username' => $accountusername,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $plat = $bin->remoteApi(BBin::API_CheckUsrBalance, $param, 0);
        
        if (isset($plat['result']) && $plat['result'] == 1) {
            $playGameAccount = PlayerGameAccount::where('account_user_name', $accountusername)->first();
            $totalBalance = $plat['data'][0]['TotalBalance'];
            $playGameAccount->amount = $totalBalance;
            $playGameAccount->save();
            
            return $totalBalance;
        } else {
            return false;
        }
    }

    public function vrCheckUserBalance($accountusername)
    {
        $vr = new VR();
        $result = $vr->checkBalance($accountusername);
        
        if (isset($result) && isset($result['balance'])) {
            $playGameAccount = PlayerGameAccount::where('account_user_name', $accountusername)->first();
            
            $totalBalance = $result['balance'] == - 1 ? 0 : $result['balance'];
            $playGameAccount->amount = $totalBalance;
            $playGameAccount->save();
            return $totalBalance;
        } else {
            return false;
        }
    }

    public function ttgCheckUserBalance($accountusername)
    {
        $ttg = new TTG();
        $result = $ttg->checkBalance($accountusername);
        $playGameAccount = PlayerGameAccount::where('account_user_name', $accountusername)->first();
        $playGameAccount->amount = $result;
        $playGameAccount->save();
        return $result;
    }

    public function pngCheckUserBalance($accountusername)
    {
        $png = new PNG();
        $result = $png->checkBalance($accountusername);
        $playGameAccount = PlayerGameAccount::where('account_user_name', $accountusername)->first();
        $playGameAccount->amount = $result;
        $playGameAccount->save();
        return $result;
    }

    public function onworksCheckUserBalance($accountusername)
    {
        $oneworks = new OneWorks();
        $param = array(
            'OpCode' => OneWorks::OPCODE,
            'PlayerName' => $accountusername
        );
        $json = $oneworks->remoteApi(OneWorks::API_CHECKUSERBALANCE, $param, 1);
        if (isset($json['error_code']) && $json['error_code'] == 0) {
            $totalBalance = $json['Data'][0]['balance'];
            $playGameAccount = PlayerGameAccount::where('account_user_name', $accountusername)->first();
            $playGameAccount->amount = $totalBalance;
            $playGameAccount->save();
            return $totalBalance;
        } else {
            return false;
        }
    }

    private function playaccountlogSave($param)
    {
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', $param['main'])->first();
        
        $playaccountlog = new PlayerAccountLog();
        $playaccountlog->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $playaccountlog->player_id = \WinwinAuth::memberUser()->player_id;
        $playaccountlog->main_game_plat_id = $mainGamePlat->main_game_plat_id;
        $playaccountlog->amount = $param['money'];
        $playaccountlog->fund_type = $param['fund_type'];
        $playaccountlog->operator_reviewer_id = $param['operator_id'];
        
        if ($param['tranfrom'] == 'bbin') {
            $tranfrom = '波音平台';
        } else if ($param['tranfrom'] == 'main') {
            $tranfrom = '主帐号';
        } else if ($param['tranfrom'] == 'sunbet') {
            $tranfrom = '申博平台';
        } else if ($param['tranfrom'] == 'onworks') {
            $tranfrom = '沙巴平台';
        } else if ($param['tranfrom'] == 'vr') {
            $tranfrom = 'VR彩票';
        } else {
            $tranfrom = strtoupper($param['tranfrom']) . '平台';
        }
        
        if ($param['tranto'] == 'bbin') {
            $tranto = '波音平台';
        } else if ($param['tranto'] == 'main') {
            $tranto = '主帐号';
        } else if ($param['tranto'] == 'sunbet') {
            $tranto = '申博平台';
        } else if ($param['tranto'] == 'onworks') {
            $tranto = '沙巴平台';
        } else if ($param['tranto'] == 'vr') {
            $tranto = 'VR彩票';
        } else {
            $tranto = strtoupper($param['tranto']) . '平台';
        }
        
        $playaccountlog->fund_source = $tranfrom . ' 转到 ' . $tranto;
        $playaccountlog->remark = '主账户原余额： ' . $param['preMainAccount'] . ', 现余额： ' . $param['afterMainAccount'] . ' ;游戏平台原余额: ' . $param['prePlatAccount'] . ', 现余额: ' .
             $param['afterPlatAccount'];
        $playaccountlog->save();
    }

    private function playerTransferSave($param)
    {
        $mainGamePlat = MainGamePlat::where('main_game_plat_code', $param['main'])->first();
        $playerTransfer = new PlayerTransfer();
        $playerTransfer->transid = $param['OpTransId'];
        $playerTransfer->player_id = \WinwinAuth::memberUser()->player_id;
        $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
        $playerTransfer->main_game_plats_id = $mainGamePlat->main_game_plat_id;
        $playerTransfer->money = $param['money'];
        $playerTransfer->direction = $param['direction'];
        $playerTransfer->state = $param['state'];
        $playerTransfer->save();
    }

    public function pngTransfer($accountusername, $money, $direction)
    {
        \DB::beginTransaction();
        try {
            
            $currplayer = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            $preMainAccount = $currplayer->main_account_amount;
            $prePlatAccount = $this->pngCheckUserBalance($accountusername);
            if (strtoupper($direction) == 'IN') {
                if ($preMainAccount >= $money && $money > 0) {
                    $png = new PNG();
                    $result = $png->transferIn($accountusername, $money);
                    if ($result['flag'] === true) {
                        $playaccountlogarr = array(
                            'main' => 'png',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'png'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 1,
                            'main' => 'png',
                            'OpTransId' => $result['transferOrderid']
                        );
                        
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['flag'] == 'unknown') {
                        // 未知
                        $playaccountlogarr = array(
                            'main' => 'png',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'png'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 0,
                            'main' => 'png',
                            'OpTransId' => $result['transferOrderid']
                        );
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['flag'] === false) {
                        // 失败
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 2,
                            'main' => 'png',
                            'OpTransId' => $result['transferOrderid']
                        );
                        $this->playerTransferSave($playertranarr);
                    }
                } else {
                    echo '余额不足111';
                    exit();
                }
            } else {
                $png = new PNG();
                $result = $png->transferOut($accountusername, $money);
                if ($result['flag'] === true) {
                    $playaccountlogarr = array(
                        'main' => 'png',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'png',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 1,
                        'main' => 'png',
                        'OpTransId' => $result['transferOrderid']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['flag'] == 'unknown') {
                    // 未知
                    $playaccountlogarr = array(
                        'main' => 'png',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'ttg',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 0,
                        'main' => 'png',
                        'OpTransId' => $result['transferOrderid']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['flag'] === false) {
                    // 失败
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 2,
                        'main' => 'png',
                        'OpTransId' => $result['transactionOrderId']
                    );
                    $this->playerTransferSave($playertranarr);
                }
            }
            $this->pngCheckUserBalance($accountusername);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::info([
                'key' => $e->getMessage()
            ]);
        }
    }

    public function ttgTransfer($accountusername, $money, $direction)
    {
        \DB::beginTransaction();
        try {
            
            $currplayer = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            $preMainAccount = $currplayer->main_account_amount;
            
            $prePlatAccount = $this->ttgCheckUserBalance($accountusername);
            
            if (strtoupper($direction) == 'IN') {
                if ($preMainAccount >= $money && $money > 0) {
                    $ttg = new TTG();
                    $result = $ttg->transferIn($accountusername, $money);
                    if ($result['success'] === true) {
                        $playaccountlogarr = array(
                            'main' => 'ttg',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'ttg'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 1,
                            'main' => 'ttg',
                            'OpTransId' => $result['transactionOrderId']
                        );
                        
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['success'] == 'unknown') {
                        // 未知
                        $playaccountlogarr = array(
                            'main' => 'ttg',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'ttg'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 0,
                            'main' => 'ttg',
                            'OpTransId' => $result['transactionOrderId']
                        );
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['success'] === false) {
                        // 失败
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 2,
                            'main' => 'ttg',
                            'OpTransId' => $result['transactionOrderId']
                        );
                        $this->playerTransferSave($playertranarr);
                    }
                } else {
                    echo '余额不足111';
                    exit();
                }
            } else {
                $ttg = new TTG();
                $result = $ttg->transferOut($accountusername, $money);
                if ($result['success'] === true) {
                    $playaccountlogarr = array(
                        'main' => 'ttg',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'ttg',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 1,
                        'main' => 'ttg',
                        'OpTransId' => $result['transactionOrderId']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['success'] == 'unknown') {
                    // 未知
                    $playaccountlogarr = array(
                        'main' => 'ttg',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'ttg',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 0,
                        'main' => 'ttg',
                        'OpTransId' => $result['transactionOrderId']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['success'] === false) {
                    // 失败
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 2,
                        'main' => 'ttg',
                        'OpTransId' => $result['transactionOrderId']
                    );
                    $this->playerTransferSave($playertranarr);
                }
            }
            $this->ttgCheckUserBalance($accountusername);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::info([
                'key' => $e->getMessage()
            ]);
        }
    }

    public function vrTransfer($accountusername, $money, $direction)
    {
        \DB::beginTransaction();
        try {
            
            $currplayer = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            $preMainAccount = $currplayer->main_account_amount;
            
            $prePlatAccount = $this->vrCheckUserBalance($accountusername);
            
            if (strtoupper($direction) == 'IN') {
                if ($preMainAccount >= $money && $money > 0) {
                    $vr = new VR();
                    $result = $vr->transferIn($accountusername, $money);
                    
                    if ($result['result'] === true) {
                        $playaccountlogarr = array(
                            'main' => 'vr',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'vr'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 1,
                            'main' => 'vr',
                            'OpTransId' => $result['ordernumber']
                        );
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['result'] == 'unknown') {
                        // 未知
                        $playaccountlogarr = array(
                            'main' => 'vr',
                            'money' => $money,
                            'fund_type' => 5,
                            'operator_id' => NULL,
                            'preMainAccount' => $preMainAccount,
                            'prePlatAccount' => $prePlatAccount,
                            'afterMainAccount' => $preMainAccount - $money,
                            'afterPlatAccount' => $prePlatAccount + $money,
                            'tranfrom' => 'main',
                            'tranto' => 'vr'
                        );
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 0,
                            'main' => 'vr',
                            'OpTransId' => $result['ordernumber']
                        );
                        $this->playaccountlogSave($playaccountlogarr);
                        $this->playerTransferSave($playertranarr);
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $preMainAccount - $money
                        ));
                    } else if ($result['result'] === false) {
                        // 失败
                        $playertranarr = array(
                            'money' => $money,
                            'direction' => 1,
                            'state' => 2,
                            'main' => 'vr',
                            'OpTransId' => $result['ordernumber']
                        );
                        $this->playerTransferSave($playertranarr);
                    }
                } else {
                    echo '余额不足111';
                    exit();
                }
            } else {
                $vr = new VR();
                $result = $vr->transferOut($accountusername, $money);
                if ($result['result'] === true) {
                    $playaccountlogarr = array(
                        'main' => 'vr',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'vr',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 1,
                        'main' => 'vr',
                        'OpTransId' => $result['ordernumber']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['result'] == 'unknown') {
                    // 未知
                    $playaccountlogarr = array(
                        'main' => 'vr',
                        'money' => $money,
                        'fund_type' => 5,
                        'operator_id' => NULL,
                        'preMainAccount' => $preMainAccount,
                        'prePlatAccount' => $prePlatAccount,
                        'afterMainAccount' => $preMainAccount + $money,
                        'afterPlatAccount' => $prePlatAccount - $money,
                        'tranfrom' => 'vr',
                        'tranto' => 'main'
                    );
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 0,
                        'main' => 'vr',
                        'OpTransId' => $result['ordernumber']
                    );
                    $this->playaccountlogSave($playaccountlogarr);
                    $this->playerTransferSave($playertranarr);
                    
                    $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                    \WinwinAuth::currentWebCarrier()->update(array(
                        'remain_quota' => $carrierQuota + $money
                    ));
                    \WinwinAuth::memberuser()->update(array(
                        'main_account_amount' => $preMainAccount + $money
                    ));
                } else if ($result['result'] === false) {
                    // 失败
                    $playertranarr = array(
                        'money' => $money,
                        'direction' => 2,
                        'state' => 2,
                        'main' => 'vr',
                        'OpTransId' => $result['ordernumber']
                    );
                    $this->playerTransferSave($playertranarr);
                }
            }
            $this->vrCheckUserBalance($accountusername);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::info([
                'key' => $e->getMessage()
            ]);
        }
    }

    public function onworksTransfer($accountusername, $money, $direction)
    {
        \DB::beginTransaction();
        try {
            // 获取相关平台数据
            $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::ONWORKS)->first();
            // 写日志表
            $playaccountlog = new PlayerAccountLog();
            $playaccountlog->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $playaccountlog->player_id = \WinwinAuth::memberUser()->player_id;
            $playaccountlog->main_game_plat_id = $mainGamePlat->main_game_plat_id;
            $playaccountlog->amount = $money;
            $playaccountlog->fund_type = 5;
            $playaccountlog->operator_reviewer_id = NULL;
            
            $currplayer = Player::where('player_id', $playaccountlog->player_id)->first();
            
            $preMainAccount = $currplayer->main_account_amount;
            $prePlatAccount = $this->onworksCheckUserBalance($accountusername);
            $afterMainAccount = '';
            $afterPlatAccount = '';
            
            if (strtoupper($direction) == 'IN') {
                if ($preMainAccount >= $money && $money > 0) {
                    $oneworks = new OneWorks();
                    $OpTransId = time() . mt_rand(10000000, 99999999);
                    $param = array(
                        'OpCode' => OneWorks::OPCODE,
                        'PlayerName' => $accountusername,
                        'amount' => $money,
                        'OpTransId' => $OpTransId,
                        'direction' => 1
                    );
                    $playerTransfer = new PlayerTransfer();
                    $playerTransfer->transid = $OpTransId;
                    $playerTransfer->player_id = \WinwinAuth::memberUser()->player_id;
                    $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                    $playerTransfer->main_game_plats_id = $mainGamePlat->main_game_plat_id;
                    $playerTransfer->money = $money;
                    $playerTransfer->direction = 1;
                    
                    $transferResult = $oneworks->remoteApi(OneWorks::API_FUNDTRANSFER, $param, 1);
                    
                    if (isset($transferResult['error_code']) && $transferResult['error_code'] == 0) {
                        $playerTransfer->state = 1;
                        $afterMainAccount = $preMainAccount - $money;
                        $afterPlatAccount = $prePlatAccount + $money;
                        
                        $playaccountlog->fund_source = '主账户 转到 沙巴平台';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        $playaccountlog->save();
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        $this->onworksCheckUserBalance($accountusername);
                        
                        $playaccountlog->save();
                        // 更改用户表数据
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                    } else if (! $transferResult) {
                        $afterMainAccount = $preMainAccount - $money;
                        $afterPlatAccount = $prePlatAccount + $money;
                        
                        $playaccountlog->fund_source = '主账户 转到 沙巴平台';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        $playaccountlog->save();
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        $this->onworksCheckUserBalance($accountusername);
                        
                        $playaccountlog->save();
                        // 更改用户表数据
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                    } else {
                        $playerTransfer->state = 2;
                    }
                    $playerTransfer->save();
                } else {
                    echo '余额不足111';
                    exit();
                }
            } else {
                if ($prePlatAccount >= $money && $money > 0) {
                    $oneworks = new OneWorks();
                    $OpTransId = time() . mt_rand(10000000, 99999999);
                    $param = array(
                        'OpCode' => OneWorks::OPCODE,
                        'PlayerName' => $accountusername,
                        'amount' => $money,
                        'OpTransId' => $OpTransId,
                        'direction' => 0
                    );
                    
                    $playerTransfer = new PlayerTransfer();
                    $playerTransfer->transid = $OpTransId;
                    $playerTransfer->player_id = \WinwinAuth::memberUser()->player_id;
                    $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                    $playerTransfer->main_game_plats_id = $mainGamePlat->main_game_plat_id;
                    $playerTransfer->money = $money;
                    $playerTransfer->direction = 2;
                    
                    $playerTransfer->save();
                    
                    $oneworksKick = new OneWorks();
                    $oneworksKick->remoteApi(OneWorks::API_KICKUSER,
                        array(
                            'OpCode' => OneWorks::OPCODE,
                            'Playername' => $accountusername
                        ), 1);
                    $transferResult = $oneworks->remoteApi(OneWorks::API_FUNDTRANSFER, $param, 1);
                    
                    if ($transferResult['error_code'] == 0) {
                        $playerTransfer->state = 1;
                    } else {
                        $playerTransfer->state = 2;
                    }
                    $playerTransfer->save();
                } else {
                    echo '平台余额不足222';
                    exit();
                }
                
                $afterMainAccount = $preMainAccount + $money;
                $afterPlatAccount = $prePlatAccount - $money;
                
                $playaccountlog->fund_source = '沙巴平台 转到 主账户';
                $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                \WinwinAuth::currentWebCarrier()->update(array(
                    'remain_quota' => $carrierQuota + $money
                ));
                $this->onworksCheckUserBalance($accountusername);
                
                $playaccountlog->save();
                // 更改用户表数据
                \WinwinAuth::memberuser()->update(array(
                    'main_account_amount' => $afterMainAccount
                ));
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
        }
    }

    public function checkBalance($accountusername, $gamePlatId)
    {
        if ($gamePlatId == 1) {
            $prePlatAccount = $this->bbinCheckUsrBalance($accountusername);
        } else if ($gamePlatId == 8) {
            $prePlatAccount = $this->onworksCheckUserBalance($accountusername);
        } else if ($gamePlatId == 9) {
            $prePlatAccount = $this->vrCheckUserBalance($accountusername);
        } else if ($gamePlatId == 12) {
            $prePlatAccount = $this->ttgCheckUserBalance($accountusername);
        } else if ($gamePlatId == 14) {
            $prePlatAccount = $this->pngCheckUserBalance($accountusername);
        }
        return $prePlatAccount;
    }

    public function bbinTransfer($accountusername, $money, $direction)
    {
        \DB::beginTransaction();
        try {
            // 获取相关平台数据
            $mainGamePlat = MainGamePlat::where('main_game_plat_code', MainGamePlat::BBIN)->first();
            
            // 写日志表
            $playaccountlog = new PlayerAccountLog();
            $playaccountlog->carrier_id = \WinwinAuth::currentWebCarrier()->id;
            $playaccountlog->player_id = \WinwinAuth::memberUser()->player_id;
            $playaccountlog->main_game_plat_id = $mainGamePlat->main_game_plat_id;
            $playaccountlog->amount = $money;
            $playaccountlog->fund_type = 5;
            $playaccountlog->operator_reviewer_id = NULL;
            
            $currplayer = Player::where('player_id', $playaccountlog->player_id)->first();
            $preMainAccount = $currplayer->main_account_amount;
            
            $prePlatAccount = $this->bbinCheckUsrBalance($accountusername);
            $afterMainAccount = '';
            $afterPlatAccount = '';
            if (strtoupper($direction) == 'IN') {
                if ($preMainAccount >= $money && $money > 0) {
                    $bin = new BBin();
                    $remitno = time() . mt_rand(10000000, 99999999);
                    $param = array(
                        'keyb' => BBin::TransferKeyB,
                        'front' => '2',
                        'after' => '7',
                        'username' => $accountusername,
                        'remit' => intval($money),
                        'remitno' => $remitno,
                        'action' => strtoupper($direction),
                        'uppername' => BBin::Uppername,
                        'website' => BBin::Website
                    );
                    
                    $playerTransfer = new PlayerTransfer();
                    $playerTransfer->transid = $remitno;
                    $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                    $playerTransfer->player_id = \WinwinAuth::memberUser()->player_id;
                    $playerTransfer->main_game_plats_id = $mainGamePlat->main_game_plat_id;
                    $playerTransfer->money = intval($money);
                    $playerTransfer->direction = 1;
                    $transferResult = $bin->remoteApi(BBin::API_Transfer, $param, 0);
                    
                    if (isset($transferResult['result']) && $transferResult['result'] == 'True') {
                        $playerTransfer->state = 1;
                        $afterMainAccount = $preMainAccount - $money;
                        $afterPlatAccount = $prePlatAccount + $money;
                        
                        $playaccountlog->fund_source = '主账户 转到 BBIN平台';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        $this->bbinCheckUsrBalance($accountusername);
                        $playaccountlog->save();
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                    } else if (! $transferResult) {
                        $afterMainAccount = $preMainAccount - $money;
                        $afterPlatAccount = $prePlatAccount + $money;
                        
                        $playaccountlog->fund_source = '主账户 转到 BBIN平台';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota - $money
                        ));
                        $this->bbinCheckUsrBalance($accountusername);
                        $playaccountlog->save();
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                    } else {
                        $playerTransfer->state = 2;
                    }
                    $playerTransfer->save();
                } else {
                    echo '余额不足';
                    exit();
                }
            } else {
                if ($prePlatAccount >= $money) {
                    $bin = new BBin();
                    $remitno = time() . mt_rand(10000000, 99999999);
                    $param = array(
                        'keyb' => BBin::TransferKeyB,
                        'front' => '2',
                        'after' => '7',
                        'username' => $accountusername,
                        'remit' => $money,
                        'remitno' => $remitno,
                        'action' => strtoupper($direction),
                        'uppername' => BBin::Uppername,
                        'website' => BBin::Website
                    );
                    $playerTransfer = new PlayerTransfer();
                    $playerTransfer->carrier_id = \WinwinAuth::currentWebCarrier()->id;
                    $playerTransfer->transid = $remitno;
                    $playerTransfer->player_id = \WinwinAuth::memberUser()->player_id;
                    $playerTransfer->main_game_plats_id = $mainGamePlat->main_game_plat_id;
                    $playerTransfer->money = intval($money);
                    $playerTransfer->direction = 2;
                    // 先踢线
                    $kickBBin = new BBin();
                    $a = $kickBBin->remoteApi(BBin::API_Logout,
                        array(
                            'keyb' => BBin::LogoutKeyB,
                            'username' => $accountusername,
                            'website' => BBin::Website,
                            'front' => 4,
                            'after' => 6
                        ), 0);
                    $transferResult = $bin->remoteApi(BBin::API_Transfer, $param, 0);
                    if (isset($transferResult['result']) && $transferResult['result'] == 'True') {
                        $playerTransfer->state = 1;
                        $afterMainAccount = $preMainAccount + $money;
                        $afterPlatAccount = $prePlatAccount - $money;
                        
                        $playaccountlog->fund_source = 'BBIN平台 转到 主账户';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota + $money
                        ));
                        $this->bbinCheckUsrBalance($accountusername);
                        $playaccountlog->save();
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                    } else if (! $transferResult['result']) {
                        $afterMainAccount = $preMainAccount + $money;
                        $afterPlatAccount = $prePlatAccount - $money;
                        
                        $playaccountlog->fund_source = 'BBIN平台 转到 主账户';
                        $playaccountlog->remark = '主账户原余额： ' . $preMainAccount . ', 现余额： ' . $afterMainAccount . ' ;游戏平台原余额: ' . $prePlatAccount . ', 现余额: ' . $afterPlatAccount;
                        $carrierQuota = \WinwinAuth::currentWebCarrier()->remain_quota;
                        \WinwinAuth::currentWebCarrier()->update(array(
                            'remain_quota' => $carrierQuota + $money
                        ));
                        $this->bbinCheckUsrBalance($accountusername);
                        $playaccountlog->save();
                        \WinwinAuth::memberuser()->update(array(
                            'main_account_amount' => $afterMainAccount
                        ));
                        $this->bbinCheckUsrBalance($accountusername);
                    } else {
                        $playerTransfer->state = 2;
                        $this->bbinCheckUsrBalance($accountusername);
                    }
                    $playerTransfer->save();
                } else {
                    echo '平台余额不足';
                    exit();
                }
            }
            
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
        }
    }

    public function loginBBinGame(Request $request)
    {
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::Login2KeyB,
            'front' => '8',
            'after' => '1',
            'username' => 's74110s',
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_Login2, $param, 0);
    }

    public function playBBinGameByH5(Request $request)
    {
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::PlayGameByH5KeyB,
            'front' => '7',
            'after' => '1',
            'username' => 's74110s',
            'gamekind' => 3,
            'gametype' => 3001,
            'gamecode' => 1,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_PlayGameByH5, $param, 1);
    }

    public function loginPlat(Request $request)
    {
        $bin = new BBin();
        $param = array(
            'keyb' => BBin::LoginKeyB,
            'front' => '8',
            'after' => '1',
            'username' => 's74110s',
            'uppername' => BBin::Uppername,
            'website' => BBin::Website
        );
        $bin->remoteApi(BBin::API_Login, $param, 1);
    }

    public function pngRegister(Request $request)
    {
        $png = new PNG();
        $param = array(
            'username' => 'TT_s740s7',
            'password' => 'x349200'
        );
        $png->remoteApi(PNG::API_REGISTER, $param);
    }

    public function pngPlayerActive(Request $request)
    {
        $png = new PNG();
        $param = array(
            'username' => 'TT_s740s7'
        );
        $png->remoteApi(PNG::API_ACTIVE, $param);
    }

    public function pngGameOpen(Request $request)
    {
        $png = new PNG();
        $param = array(
            'username' => 'TT_s740s7',
            'game_code' => 'luckydiamonds',
            'lang' => 'zh-cn'
        );
        $arr = $png->remoteApi(PNG::API_OPEN, $param);
        $ticket = $arr['ticket'];
        $param['ticket'] = $ticket;
        $png->joinPlayer($param);
    }

    public function oneWorkCreateMember(Request $request)
    {
        $oneworks = new OneWorks();
        $param = array(
            'OpCode' => OneWorks::OPCODE,
            'PlayerName' => 's74110s',
            'OddsType' => '1',
            'MaxTransfer' => '1000',
            'MinTransfer' => '100'
        );
        $oneworks->remoteApi(OneWorks::API_CREATEMEMBER, $param, 1);
    }

    public function onWorkLogin(Request $request)
    {
        $oneworks = new OneWorks();
        $param = array(
            'OpCode' => OneWorks::OPCODE,
            'PlayerName' => 's74110s'
        );
        $json = $oneworks->remoteApi(OneWorks::API_LOGIN, $param, 1);
        
        unset($param);
        $oneworks = new OneWorks();
        $param = array(
            'g' => $json['sessionToken'],
            'lang' => 'en'
        );
        $oneworks->joinPlayer($param, $oneworks->API_MKI());
    }

    /* 根据用户名和密码信息获取token add by tlt 接口测试 */
    public function authenticate()
    {
        // var_dump('aaaaatttt') ;return ;
        $MGEUR = new MGEUR_API();
        $MGEUR->login();
    }

    /* 创建玩家 add by tlt 接口测试 */
    public function createMember()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->createMember();
    }

    /* 更新密码 add by tlt 接口测试 */
    public function updatePassword()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->updatePassword();
    }

    /* 依照 ID 或 REF 取得账号信息 add by tlt 接口测试 */
    public function getAccount()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->getAccount();
    }

    /* 取得目前所有玩家 add by tlt 接口测试 */
    public function listChildAccounts()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->listChildAccounts();
    }

    /* 转账（转入,主账号转到游戏账号） add by tlt 接口测试 */
    public function MGCredit($type = "CREDIT", $amount = 1.00, $playerAccount = '')
    {
        $MGEUR = new MGEUR_API();
        /*
         * $type = "CREDIT" ;
         * $amount = 1.00 ;
         */
        return $MGEUR->createTransaction($type, $amount, $playerAccount);
    }

    /* 转账（转出，游戏账号转到主账号） add by tlt 接口测试 */
    public function MGDebit($type = "DEBIT", $amount = 1.00, $playerAccount = '')
    {
        $MGEUR = new MGEUR_API();
        /*
         * $type = "DEBIT" ;
         * $amount = 1.00 ;
         */
        return $MGEUR->createTransaction($type, $amount, $playerAccount);
    }

    /* 查询转帐 add by tlt 接口测试 */
    public function verifyTransaction()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->verifyTransaction();
    }

    /* 查询余额 add by tlt 接口测试 */
    public function getMGBalance($playerAccount = 'TTC2ZSGOasq')
    {
        $MGEUR = new MGEUR_API();
        $balance = $MGEUR->getBalance($playerAccount);
        // var_dump($balance) ;
        return $balance;
    }

    /* 执行游戏 add by tlt 接口测试 */
    public function launchItem($item_id, $app_id, $flag = true)
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->launchItem($item_id, $app_id, $flag);
    }

    /*
     * 产生游戏注单详情的连结 add by tlt 测试接口
     * 使用此界面取得单注游戏的详细记录
     */
    public function getGameNoteDetailLink()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->getGameNoteDetailLink();
    }

    /* 获取游戏与转帐记录 add by tlt 测试接口 */
    public function getGamesAndTransferRecords()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->getGamesAndTransferRecords();
    }

    /* 修改账号参数 (账号禁用、启用) add by tlt 接口测试 */
    public function editAccount()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->editAccount();
    }

    /* 游戏纪录 (依照 roundId 分类) add by tlt 接口测试 */
    public function getGamesRecord()
    {
        $MGEUR = new MGEUR_API();
        $MGEUR->getGamesRecord();
    }

    /**
     * ************* 下面是Sunset平台接口测试 add by tlt start **************
     */
    /* API调用的OAuth授权 */
    public function Oauth()
    {
        $Sunbet = new Sunbet();
        $Sunbet->Oauth();
    }

    /* ​游戏大厅端点 测试Sunbet平台 add by tlt 未测试 */
    public function gamesLobbyPoints()
    {
        $Sunbet = new Sunbet();
        $Sunbet->gamesLobbyPoints();
    }

    /* 玩家身份验证 测试Sunbet平台 add by tlt */
    public function playerAuthorize()
    {
        $Sunbet = new Sunbet();
        $Sunbet->authorize();
    }

    /* 玩家授权取消 测试Sunbet平台 add by tlt */
    public function playerDeauthorize()
    {
        $Sunbet = new Sunbet();
        $Sunbet->deauthorize();
    }

    /* ​取得（某个玩家的）余额​ ​(Wallet​ ​Balance) 测试Sunbet平台 add by tlt */
    public function getSBBalance($playerAccount = '')
    {
        $Sunbet = new Sunbet();
        $balance = $Sunbet->getBalance($playerAccount);
        // var_dump($balance) ;
        return $balance;
    }

    /* 取得多个玩家的余额 测试Sunbet平台 add by tlt */
    /* 返回值：返回以货币为群组的所有玩家总余额。请注意响应是CSV格式 */
    public function getMultipleBalance()
    {
        $Sunbet = new Sunbet();
        $Sunbet->getMultipleBalance();
    }

    /* 获取所有玩家的余额 测试Sunbet平台 add by tlt */
    public function getBalanceList()
    {
        $Sunbet = new Sunbet();
        $Sunbet->getBalanceList();
    }

    /* 电子钱包加钱​ ​(Wallet​ ​Credit) */
    public function SBCredit($amount = 1.00, $playerAccount = '', $gameplat = 'SB')
    {
        $Sunbet = new Sunbet();
        // $amount = 10.00 ;
        return $Sunbet->credit($amount, $playerAccount, $gameplat);
    }

    /*
     * 电子钱包扣钱​ ​(Wallet​ ​Debit) 测试Sunbet平台 add by tlt
     * 运营商调用电子钱包扣钱方法将钱转出TGP电子钱包系统。
     */
    public function SBDebit($amount = 1.00, $playerAccount = '', $gameplat = 'SB')
    {
        $Sunbet = new Sunbet();
        // $amount = 1.00 ;
        return $Sunbet->debit($amount, $playerAccount, $gameplat);
    }

    /* 转账历史​ ​(Transfer​ ​History) 测试Sunbet平台 add by tlt */
    public function transferHistory()
    {
        $Sunbet = new Sunbet();
        $Sunbet->transferHistory();
    }

    /* 投注交易历史​ ​(Bet​ ​Transaction​ ​History) 测试Sunbet平台 add by tlt */
    public function betTransactionHistory()
    {
        $Sunbet = new Sunbet();
        $Sunbet->betTransactionHistory();
    }

    /*
     * 游戏历史​ ​(Game​ ​History) 测试Sunbet平台 add by tlt
     * 此方法用来取得玩家的游戏历史
     */
    public function gameHistory()
    {
        $Sunbet = new Sunbet();
        $Sunbet->gameHistory();
    }

    /*
     * 投注历史​ ​(Bet​ ​History) 测试Sunbet平台 add by tlt
     * 此方法用来取得玩家的游戏历史
     */
    public function betHistory()
    {
        $Sunbet = new Sunbet();
        $Sunbet->betHistory();
    }

    /* ​来自游戏供应商的游戏历史 测试Sunbet平台 add by tlt */
    public function providersHistory()
    {
        $Sunbet = new Sunbet();
        $Sunbet->providersHistory();
    }

    /* 9.​ ​直接启动游戏 start */
    /* 游戏列表API 游戏列表API用于获取特定品牌的游戏列表。 */
    public function getGamesList()
    {
        $Sunbet = new Sunbet();
        $Sunbet->getGamesList();
    }

    /* 游戏启动页 */
    /*
     * 游戏启动页是让运营商启动游戏使用。运营商可以为每个游戏创建游戏启动网址，并使用该网址来启动游
     * 戏。$providercode:供应商平台代码；$code：游戏代码
     */
    public function gameLauncher($providercode, $code)
    {
        $Sunbet = new Sunbet();
        if ($this->isMobile()) {
            $Sunbet->gameH5Launcher($providercode, $code);
        } else {
            $Sunbet->gameLauncher($providercode, $code);
        }
    }

    /**
     * ************* 上面是Sunset平台接口测试 add by tlt end **************
     */
    
    /**
     * ************ 下面是获取电游信息 add by tlt start *************
     */
    /* 获取游戏平台信息 add by tlt */
    public function getGamePlatInfo($game_plat_name)
    {
        $gamePlat = new GamePlat();
        $gamePlatInfo = $gamePlat->where('game_plat_name', $game_plat_name)->first();
        return $gamePlatInfo;
    }

    /* 查询游戏列表 add by tlt */
    public function getGameList($game_plat_name)
    {
        $gamePlatInfo = $this->getGamePlatInfo($game_plat_name);
        $game_plat_id = $gamePlatInfo->game_plat_id;
        $main_game_plat_id = $gamePlatInfo->main_game_plat_id;
        $gameObj = new Game();
        $condition['game_plat_id'] = $game_plat_id;
        $condition['main_game_plat_id'] = $main_game_plat_id;
        // $gameList = $gameObj ->where($condition) ->paginate(10) ;//分页
        $gameList = $gameObj->where($condition)->get(
            array(
                'main_game_plat_id',
                'game_plat_id',
                'game_name',
                'game_type'
            ));
        return $gameList;
    }

    /* 测试输出所有游戏 add by tlt */
    public function printGames($gameList)
    {
        foreach ($gameList as $value) {
            echo "<pre>";
            var_dump($value['game_name'] . "--->>>" . $value['game_type'] . "--->>game_plat_id：" . $value['game_plat_id'] . "--->>main_game_plat_id：" . $value['main_game_plat_id']);
            echo "</pre>";
        }
    }

    /* 获取PT平台下的所有电游信息 add by tlt */
    public function getAllPTElectronicGames()
    {
        $game_plat_name = "PT电子游戏"; // 游戏平台名称
        $gameList = $this->getGameList($game_plat_name);
        
        // 测试输出查询的游戏列表信息
        $this->printGames($gameList);
        
        // return \WTemplate::registerPage()->with(['gameList'=>$gameList]) ;
    }

    /* 获取MG平台下的所有电游信息 add by tlt */
    public function getAllMGElectronicGames()
    {
        $game_plat_name = "MG电子游戏"; // 游戏平台名称
        $gameList = $this->getGameList($game_plat_name);
        
        // 测试输出查询的游戏列表信息
        $this->printGames($gameList);
    }

    /* 获取SB平台下的TGP的所有电游信息 add by tlt */
    public function getAllTGPElectronicGames()
    {
        $game_plat_name = "TGP电子游戏"; // 游戏平台名称
        $gameList = $this->getGameList($game_plat_name);
        
        // 测试输出查询的游戏列表信息
        $this->printGames($gameList);
    }

    /**
     * *************上面是获取电游信息 add by tlt end *************
     */
    
    // MG保存玩家流水单号
    public function synchronizeMGGameFlowToDB()
    {
        $MGEUR = new gamegatewayruntime('mg', GameGatewayRunTime::PRODUCTION);
        $res = $MGEUR->synchronizeGameFlowToDB();
        // var_dump($res) ;
        return $res;
        // echo "synchronizeMGGameFlowToDB" ;
        // var_dump($res) ;
    }

    // 收藏游戏
    public function collectGame(Request $request)
    {
        $action = $request->input('action');
        $carrier_game_id = $request->input('carrier_game_id');
        if ($action) {
            $res = PlayerCollect::firstOrCreate(
                [
                    'map_carrier_game_id' => $carrier_game_id,
                    'player_id' => \WinwinAuth::memberUser()->player_id
                ]);
        } else {
            $res = PlayerCollect::where(
                [
                    'map_carrier_game_id' => $carrier_game_id,
                    'player_id' => \WinwinAuth::memberUser()->player_id
                ])->delete();
        }
        if ($res) {
            return $this->sendResponse('操作成功');
        } else {
            return $this->sendResponse('操作失败，请重试');
        }
    }

    // sunbet保存玩家流水单号
    public function synchronizeSBGameFlowToDB()
    {
        $SB = new gamegatewayruntime('sunbet', GameGatewayRunTime::PRODUCTION);
        $res = $SB->synchronizeGameFlowToDB();
        // echo "synchronizeMGGameFlowToDB" ;
        return $res;
        // var_dump($res) ;
    }
}
