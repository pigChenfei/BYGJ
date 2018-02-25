<?php
namespace App\Http\Controllers\Web;

use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerAccountLog;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWayRuntimeException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Web\GameController;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\Sunbet\Sunbet;
use App\Vendor\GameGateway\MGEUR_API\MGEUR_API;
use App\Models\Map;

class PlayerTransferController extends AppBaseController
{

    /**
     * 账户转账界面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::where('player_id', $player_id)->first();
        
        // $mainGameList = MainGamePlat::active()->get();
        
        $mainGameLists = \DB::select('select * from def_main_game_plats m1 where m1.main_game_plat_id in(select d.main_game_plat_id from map_carrier_game_plats m  left join def_game_plats d on d.game_plat_id=m.game_plat_id   where m.carrier_id=? and m.`status`=1 GROUP BY d.main_game_plat_id)', [
            \WinwinAuth::currentWebCarrier()->id
        ]);
        $playerGameAccount = array();
        foreach ($mainGameLists as $k => $game) {
            
            if ($game->main_game_plat_code != 'ag' && $game->status == 1) {
                $mainGameList = PlayerGameAccount::where('main_game_plat_id', $game->main_game_plat_id)->first();
                if (!$mainGameList) {
                    $mainGameList = new PlayerGameAccount();
                    $mainGameList->account_user_name =  PlayerGameAccount::generateValue(6,$game->account_pre,$player->carrier_id);
                    $mainGameList->main_game_plat_id = $game->main_game_plat_id;
                    $mainGameList->player_id = $player_id;
                    $mainGameList->save();
                }
                $game->amount = $mainGameList->amount;
                $game->account_id = $mainGameList->account_id;
                $playerGameAccount[$k] = $game;
            }
        }
        if ($this->isMobile()) {
            $str = "'主账户',";
            $arr = array(
                'main' => '主账户'
            );
            $platamount = array(
                'main' => $player->main_account_amount
            );
            foreach ($playerGameAccount as $item) {
                if ($item->main_game_plat_name == 'SUNBET') {
                    $arr[$item->main_game_plat_code] = '申博';
                    $str .= "'申博',";
                } else if ($item->main_game_plat_name == 'ONWORKS') {
                    $arr[$item->main_game_plat_code] = '沙巴';
                    $str .= "'沙巴',";
                } else {
                    $arr[$item->main_game_plat_code] = $item->main_game_plat_name;
                    $str .= "'" . $item->main_game_plat_name . "',";
                }
                
                $platamount[$item->main_game_plat_code] = $item->amount;
            }
            $str = rtrim($str, ',');
            return \WTemplate::transferPage('m')->with([
                'str' => $str,
                'platamount' => json_encode($platamount),
                'arr' => json_encode($arr),
                'main_account_amount' => $player->main_account_amount,
                'playerGameAccount' => $playerGameAccount
            ]);
        } else {
            return \WTemplate::transferPage()->with([
                'main_account_amount' => $player->main_account_amount,
                'playerGameAccount' => $playerGameAccount
            ]);
        }
    }

    /* 主账号转入游戏账号,给下面的账号转账使用 add by tlt */
    public function mainAccountToPlayerAccount($playerGameAccountInfo, $amount, $transferTo, $main_game_plat_id = '')
    {
        $game = new GameController();
        switch ($transferTo) {
            case MainGamePlat::MG: // 主账号转到MG游戏账号
                if ($playerGameAccountInfo) {
                    $game->MGCredit("CREDIT", $amount, $playerGameAccountInfo->account_user_name);
                } else {
                    $mg = new MGEUR_API();
                    $mg->createMember();
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::MG)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                    $game->MGCredit("CREDIT", $amount, $playerGameAccountInfo->account_user_name);
                }
                
                break;
            case MainGamePlat::PT: // 主账号转到PT游戏账号
                $game->PTDeposit($playerGameAccountInfo, $amount);
                break;
            case MainGamePlat::SUNBET: // 主账号转到SUNBET游戏账号
                if ($playerGameAccountInfo) {
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name);
                } else {
                    $sunbet = new Sunbet();
                    $sunbet->authorize();
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::SUNBET)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name);
                }
                
                break;
            case MainGamePlat::GD: // 主账号转帐到GD游戏帐号
                if ($playerGameAccountInfo) {
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name, 'GD');
                } else {
                    $sunbet = new Sunbet();
                    $sunbet->authorize('GD');
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::GD)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name, 'GD');
                }
                break;
            case MainGamePlat::TGP: // 主账号转帐到TGP游戏帐号
                if ($playerGameAccountInfo) {
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name, 'TGP');
                } else {
                    $sunbet = new Sunbet();
                    $sunbet->authorize('TGP');
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::TGP)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                    $game->SBCredit($amount, $playerGameAccountInfo->account_user_name, 'TGP');
                }
                break;
            case MainGamePlat::ONWORKS: // 主账号转到ONWORKS游戏账号
            case MainGamePlat::BBIN: // 主账号转到BBIN游戏账号
            case MainGamePlat::VR: // 主帐号转到VR帐号
            case MainGamePlat::TTG: // 主帐号转到TTG帐号
            case MainGamePlat::MT: // 主帐号转到TTG帐号
            case MainGamePlat::PNG: // 主帐号转到TTG帐号
                $game->Transfer($transferTo, $amount, 'IN');
                break;
        }
        unset($game);
    }

    /* 游戏账号转入主账号,给下面的账号转账使用 add by tlt */
    public function playerAccountToMainAccount($transferFrom, $playerGameAccountInfo, $amount, $transferTo, $main_game_plat_id = '')
    {
        $game = new GameController();
        switch ($transferFrom) {
            case MainGamePlat::MG: // 主账号转到MG游戏账号
                
                if (! $playerGameAccountInfo) {
                    $mg = new MGEUR_API();
                    $mg->createMember();
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::MG)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                }
                $mgAmount = $game->getMGBalance($playerGameAccountInfo->account_user_name); // 获取当前游戏账号余额
                if ($amount > $mgAmount) { // 账号余额不足
                    return false;
                }
                $game->MGDebit("DEBIT", $amount, $playerGameAccountInfo->account_user_name);
                
                break;
            case MainGamePlat::PT: // 主账号转到PT游戏账号
                $ptAmount = $game->getPTBalance($playerGameAccountInfo); // 获取当前游戏账号余额
                if ($amount > $ptAmount) { // 账号余额不足
                    return false;
                }
                $game->PTWithdraw($playerGameAccountInfo, $amount);
                break;
            case MainGamePlat::SUNBET: // 主账号转到SUNBET游戏账号
            case MainGamePlat::GD: // 主账号转到GD游戏帐号
            case MainGamePlat::TGP: // 主账号转到TGP游戏帐号
                
                if (! $playerGameAccountInfo) {
                    $sunbet = new Sunbet();
                    if ($transferFrom == MainGamePlat::SUNBET) {
                        $sunbet->authorize();
                    } else if ($transferFrom == MainGamePlat::GD) {
                        $sunbet->authorize('GD');
                    } else if ($transferFrom == MainGamePlat::TGP) {
                        $sunbet->authorize('TGP');
                    }
                    $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                    $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                    $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                    $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                }
                
                $sbAmount = $game->getSBBalance($playerGameAccountInfo->account_user_name); // 获取当前游戏账号余额
                if ($amount > $sbAmount) { // 账号余额不足
                    return false;
                }
                $game->SBDebit($amount, $playerGameAccountInfo->account_user_name);
                break;
            case MainGamePlat::ONWORKS: // 主账号转到ONWORKS游戏账号
            case MainGamePlat::BBIN: // 主账号转到BBIN游戏账号
            case MainGamePlat::VR: // 主账号转到VR游戏账号
            case MainGamePlat::TTG: // 主账号转到VR游戏账号
            case MainGamePlat::PNG: // 主账号转到VR游戏账号
                if (! $playerGameAccountInfo) {
                    $game->gamePlatReg($transferFrom);
                }
                $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                
                $money = $game->checkBalance($playerGameAccountInfo->account_user_name, $main_game_plat_id);
                if (! $money) {
                    return false; // 查询不到数据
                }
                if ($amount > $money) { // 账号余额不足
                    return false;
                }
                
                $game->Transfer($transferFrom, intval($amount), 'OUT');
                break;
        }
        unset($game);
        return true; // 转账成功
    }

    /**
     * 账户转账
     *
     * @param Request $request
     * @return \Response
     */
    public function accountTransfer(Request $request)
    {
        $amount = $request->get('amount');
        $transferFrom = $request->get('transferFrom');
        $transferTo = $request->get('transferTo');
        

//          if(!in_array($transferFrom, ['main', 'pt']) || !in_array($transferTo, ['main', 'pt'])) {
//            return $this->sendErrorResponse('目前不支持主账户和PT转账');
//          }
        if (!$amount || !$transferFrom || !$transferTo){
            return $this->sendErrorResponse('参数错误');
        }
        if($transferFrom == $transferTo){
            return $this->sendErrorResponse('同一平台不能转账');
         }

        
        $player = Player::findOrFail(\WinwinAuth::memberUser()->player_id);
        // TODO 自定义游戏平台code
        
        try {
            /**
             * ********** add by tlt start ************
             */
            // 主账号转入游戏账号 add by tlt
            if ($transferFrom == MainGamePlat::MA && $transferTo != MainGamePlat::MA) {
                $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferTo)->first();
                $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                // 主账号转入游戏账号
                $mainAmount = \WinwinAuth::memberUser()->main_account_amount; // 获取当前主账号余额
                if ($amount > $mainAmount) {
                    return $this->sendErrorResponse('主账号余额不足');
                }
                $this->mainAccountToPlayerAccount($playerGameAccountInfo, $amount, $transferTo, $mainGamePlatInfo->main_game_plat_id);
                return $this->getAmount($transferFrom, $transferTo);
            } else if ($transferFrom != MainGamePlat::MA && $transferTo == MainGamePlat::MA) { // 游戏账号转入主账号 add by tlt
                $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                // 游戏账号转入主账号
                $res = $this->playerAccountToMainAccount($transferFrom, $playerGameAccountInfo, $amount, $transferTo, $mainGamePlatInfo->main_game_plat_id);
                if (! $res) { // 账号余额不足，无法进行转账
                    return $this->sendErrorResponse($transferFrom . '游戏账号余额不足');
                }
                // 转账成功
                return $this->getAmount($transferFrom, $transferTo);
            } else if ($transferFrom == $transferTo) { // 相同平台之间不允许转账
                return $this->sendErrorResponse('同一平台不能转账');
            } else { // 游戏账号转入游戏账号,这里先用主账号作为中转站
                /* 获取第一个游戏账号相关信息 */
                $tfmainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                $tfcondition['player_id'] = \WinwinAuth::memberUser()->player_id;
                $tfcondition['main_game_plat_id'] = $tfmainGamePlatInfo->main_game_plat_id;
                $tfplayerGameAccountInfo = PlayerGameAccount::where($tfcondition)->first();
                // 第一步：tf游戏账号转入主账号
                $res = $this->playerAccountToMainAccount($transferFrom, $tfplayerGameAccountInfo, $amount, $transferTo, $tfmainGamePlatInfo->main_game_plat_id);
                if (! $res) { // 账号余额不足，无法进行转账
                    return $this->sendErrorResponse($transferFrom . '游戏账号余额不足');
                }
                $tomainGamePlatInfo = MainGamePlat::where('main_game_plat_code', $transferTo)->first();
                $tocondition['player_id'] = \WinwinAuth::memberUser()->player_id;
                $tocondition['main_game_plat_id'] = $tomainGamePlatInfo->main_game_plat_id;
                $toplayerGameAccountInfo = PlayerGameAccount::where($tocondition)->first();
                // 第二步：主账号转入to游戏账号
                $this->mainAccountToPlayerAccount($toplayerGameAccountInfo, $amount, $transferTo, $tomainGamePlatInfo->main_game_plat_id);
                return $this->getAmount($transferFrom, $transferTo);
            }
        } catch (\Exception $e) {
            \Log::error('转账异常', [
                $e->getMessage()
            ]);
            return $this->sendErrorResponse('系统错误,稍后再试...', 500);
        }
    }

    /**
     * 金额格式化
     *
     * @param
     *            $amount
     * @return string
     */
    public function amountFormat($amount)
    {
        $amount = number_format($amount, 2, ".", "");
        return $amount;
    }

    /**
     * 返回主账户,游戏账户金额
     *
     * @param
     *            $transferFrom
     * @param
     *            $transferTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAmount($transferFrom, $transferTo, $is_recycel = false)
    {
        $data = [];
        
        if ($transferTo && $transferFrom) {
            if ($transferFrom == MainGamePlat::MA) {
                $transferToCode = MainGamePlat::where('main_game_plat_code', $transferTo)->first();
                $transferToAccount = PlayerGameAccount::where('main_game_plat_id', $transferToCode->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $transferFromAccount = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $data = [
                    'mainAccount' => $transferFromAccount->main_account_amount,
                    'transferFromAccount' => $transferFromAccount->main_account_amount,
                    'transferToAccount' => $transferToAccount->amount
                ];
            } elseif ($transferTo == MainGamePlat::MA) {
                $transferFromCode = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                $transferFromAccount = PlayerGameAccount::where('main_game_plat_id', $transferFromCode->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $transferToAccount = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $data = [
                    'mainAccount' => $transferToAccount->main_account_amount,
                    'transferFromAccount' => $transferFromAccount->amount,
                    'transferToAccount' => $transferToAccount->main_account_amount
                ];
            } else {
                $transferFromCode = MainGamePlat::where('main_game_plat_code', $transferFrom)->first();
                $transferFromAccount = PlayerGameAccount::where('main_game_plat_id', $transferFromCode->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $transferToCode = MainGamePlat::where('main_game_plat_code', $transferTo)->first();
                $transferToAccount = PlayerGameAccount::where('main_game_plat_id', $transferToCode->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $mainAccount = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
                $data = [
                    'mainAccount' => $mainAccount->main_account_amount,
                    'transferFromAccount' => $transferFromAccount->amount,
                    'transferToAccount' => $transferToAccount->amount
                ];
            }
            // 游戏平台金额回收
        } elseif ($is_recycel) {
            $gamePlatAccount = PlayerGameAccount::where('player_id', \WinwinAuth::memberUser()->player_id)->with('mainGamePlat')->get();
            foreach ($gamePlatAccount as $gameAccount) {
                $data['gameAccount'][$gameAccount->mainGamePlat->main_game_plat_code] = $gameAccount->amount;
            }
            $mainAccount = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            $data['mainAccount'] = $mainAccount->main_account_amount;
        }
        
        // dd($data);
        return $this->sendResponse($data);
    }

    /**
     * 转账中心回收
     *
     * @throws \Exception
     */
    public function accountRecycle()
    {
        $player_id = \WinwinAuth::memberUser()->player_id;
        $player = Player::where('player_id', $player_id)->with('gameAccounts.mainGamePlat')->get();
        try {
            // foreach($player as $k=>$value){
            foreach ($player[0]['gameAccounts'] as $k => $value) {
                if ($value->amount > 0) {
                    /*
                     * $gameRunTime = new GameGatewayRunTime($value->gameAccounts[$k]->mainGamePlat->main_game_plat_code, GameGatewayRunTime::PRODUCTION);
                     * $gameRunTime->withDrawFromPlayerGameAccount($value, $value->gameAccounts[$k]->amount);
                     */
                    $game = new GameController();
                    try {
                        \DB::beginTransaction();
                        $main_game_plat_code = $value->mainGamePlat->main_game_plat_code;
                        $playerAccount = $value->account_user_name;
                        // 收回MG平台游戏账号余额 add by tlt
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::MG) { // MG平台
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            $mgAmount = $game->getMGBalance($value->account_user_name); // 获取当前游戏账号余额
                            
                            $game->MGDebit("DEBIT", $mgAmount, $value->account_user_name);
                        }
                        
                        // 收回PT平台游戏账号余额 add by tlt
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::PT) { // PT平台
                            $ptAmount = $game->getPTBalance($value); // 获取当前游戏账号余额
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            
                            $game->PTWithdraw($value, $ptAmount);
                        }
                        
                        // 收回SUNBET平台游戏账号余额 add by tlt
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::SUNBET) { // SUNBET平台
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            $sbAmount = $game->getSBBalance($value->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $value->account_user_name);
                        }
                        
                        // 回收GD游戏金额
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::GD) {
                            $sbAmount = $game->getSBBalance($value->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $value->account_user_name);
                        }
                        
                        // 回收GD游戏金额
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::TGP) {
                            $sbAmount = $game->getSBBalance($value->account_user_name); // 获取当前游戏账号余额
                            $game->SBDebit($sbAmount, $value->account_user_name);
                        }
                        
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::BBIN) {
                            $bbinAmount = $game->bbinCheckUsrBalance($value->account_user_name);
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            if ($bbinAmount) {
                                $game->Transfer(MainGamePlat::BBIN, intval($bbinAmount), 'out');
                            }
                        }
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::ONWORKS) {
                            $onworksAmount = $game->onworksCheckUserBalance($value->account_user_name);
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            if ($onworksAmount) {
                                $game->Transfer(MainGamePlat::ONWORKS, $onworksAmount, 'out');
                            }
                        }
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::VR) {
                            $vrAmount = $game->vrCheckUserBalance($value->account_user_name);
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            if ($vrAmount) {
                                $game->Transfer(MainGamePlat::VR, $vrAmount, 'out');
                            }
                        }
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::TTG) {
                            $ttgAmount = $game->ttgCheckUserBalance($value->account_user_name);
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $playerAccount ");
                            if ($ttgAmount) {
                                $game->Transfer(MainGamePlat::TTG, $ttgAmount, 'out');
                            }
                        }
                        if ($value->mainGamePlat->main_game_plat_code == MainGamePlat::PNG) {
                            $pngAmount = $game->pngCheckUserBalance($value->account_user_name);
                            
                            \WLog::info("一键回收 *** main_game_plat_code=$main_game_plat_code ; playerAccount = $pngAmount ");
                            if ($pngAmount) {
                                $game->Transfer(MainGamePlat::PNG, $pngAmount, 'out');
                            }
                        }
                        \DB::commit();
                        unset($game);
                    } catch (\Exception $e) {
                        \DB::rollBack();
                        throw $e;
                    }
                }
            }
            $res = $this->getAmount(0, 0, true);
            \WLog::info("getAmount=$res");
            return $res;
        } catch (\Exception $e) {
            \WLog::info("end ***** 一键收回异常 ******");
            return $this->sendErrorResponse('系统错误,稍后再试...', 500);
        }
    }

    /**
     * 显示游戏主平台账户金额
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountRefresh(Request $request)
    {
        $accountId = $request->get('accountId'); // 游戏账号id
        $playerGameAccount = '';
        if ($accountId) {
            $playerGameAccount = PlayerGameAccount::where('account_id', $accountId)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            $amount = $playerGameAccount->amount;
        } else {
            $playerMainAccount = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            
            $amount = $playerMainAccount->main_account_amount;
        }
        if (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 1) {
            // bbin
            $game = new GameController();
            $a = $game->bbinCheckUsrBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 2) {
            // mg
            $game = new GameController();
            $a = $game->getMGBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 3) {
            // ag
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 4) {
            // pt
            $game = new GameController();
            $a = $game->getPTBalance($playerGameAccount);
            return $this->sendResponse($a);
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 5) {
            // 申博getSBBalance
            $game = new GameController();
            $a = $game->getSBBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 10) {
            // GD getSBBalance
            $game = new GameController();
            $a = $game->getSBBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } else if (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 11) {
            // 申博getSBBalance
            $game = new GameController();
            $a = $game->getSBBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } elseif (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 8) {
            // 沙巴
            $game = new GameController();
            $a = $game->onworksCheckUserBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } else if (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 9) {
            // VR
            $game = new GameController();
            $a = $game->vrCheckUserBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } else if (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 12) {
            // ttg
            $game = new GameController();
            $a = $game->ttgCheckUserBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        } else if (isset($playerGameAccount->main_game_plat_id) && $playerGameAccount->main_game_plat_id == 14) {
            $game = new GameController();
            $a = $game->pngCheckUserBalance($playerGameAccount->account_user_name);
            return $this->sendResponse($a);
        }
        return $this->sendResponse($amount);
    }

    /**
     * 一键转入
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountTransferOneTouch(Request $request)
    {
        $transferTo = $request->get('transferTo');
        if (empty($transferTo)) {
            return $this->sendErrorResponse('参数异常', 403);
        }
        $player = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        try {
            /*
             * $gameRunTime = new GameGatewayRunTime($transferTo, GameGatewayRunTime::PRODUCTION);
             * $gameRunTime->depositToPlayerGameAccount($player, $player->main_account_amount);
             */
            $transferToCode = MainGamePlat::where('main_game_plat_code', $transferTo)->first();
            $gameAccount = PlayerGameAccount::where('main_game_plat_id', $transferToCode->main_game_plat_id)->where('player_id', \WinwinAuth::memberUser()->player_id)->first();
            if ($player->main_account_amount > 0) {
                $game = new GameController();
                // ONWORKS或者BBIN，主账号余额一键转入游戏账号
                if ((MainGamePlat::ONWORKS == $transferTo) or (MainGamePlat::BBIN == $transferTo) or (MainGamePlat::VR == $transferTo) or (MainGamePlat::TTG == $transferTo) or (MainGamePlat::PNG == $transferTo)) {
                    $game->Transfer($transferTo, $player->main_account_amount, 'IN');
                }
                
                // MG平台,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::MG == $transferTo) { // MG平台转账
                    $mgAmount = $player->main_account_amount; // 获取当前主账号余额
                    if ($gameAccount) {
                        $game->MGCredit("CREDIT", $mgAmount, $gameAccount->account_user_name);
                    } else {
                        $mg = new MGEUR_API();
                        $mg->createMember();
                        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::MG)->first();
                        $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                        $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                        $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                        $game->MGCredit("CREDIT", $amount, $playerGameAccountInfo->account_user_name);
                    }
                }
                
                // Sunbet,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::SUNBET == $transferTo) { // Sunbet平台转账
                    $sbAmount = $player->main_account_amount; // 获取当前主账号余额
                    if ($gameAccount) {
                        $game->SBCredit($sbAmount, $gameAccount->account_user_name);
                    } else {
                        $sunbet = new Sunbet();
                        $sunbet->authorize();
                        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::SUNBET)->first();
                        $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                        $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                        $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                        $game->SBCredit($sbAmount, $playerGameAccountInfo->account_user_name);
                    }
                }
                
                // GD,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::GD == $transferTo) { // Sunbet平台转账
                    $sbAmount = $player->main_account_amount; // 获取当前主账号余额
                    if ($gameAccount) {
                        $game->SBCredit($sbAmount, $gameAccount->account_user_name, 'GD');
                    } else {
                        $sunbet = new Sunbet();
                        $sunbet->authorize('GD');
                        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::GD)->first();
                        $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                        $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                        $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                        $game->SBCredit($sbAmount, $playerGameAccountInfo->account_user_name, 'GD');
                    }
                }
                
                // GD,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::TGP == $transferTo) { // Sunbet平台转账
                    $sbAmount = $player->main_account_amount; // 获取当前主账号余额
                    
                    if ($gameAccount) {
                        $game->SBCredit($sbAmount, $gameAccount->account_user_name, 'TGP');
                    } else {
                        $sunbet = new Sunbet();
                        $sunbet->authorize('TGP');
                        $mainGamePlatInfo = MainGamePlat::where('main_game_plat_code', MainGamePlat::TGP)->first();
                        $condition['player_id'] = \WinwinAuth::memberUser()->player_id;
                        $condition['main_game_plat_id'] = $mainGamePlatInfo->main_game_plat_id;
                        $playerGameAccountInfo = PlayerGameAccount::where($condition)->first();
                        $game->SBCredit($sbAmount, $playerGameAccountInfo->account_user_name, 'TGP');
                    }
                }
                
                // PT,主账号余额一键转入游戏账号 add by tlt
                if (MainGamePlat::PT == $transferTo) { // Sunbet平台转账
                    $ptAmount = $player->main_account_amount; // 获取当前主账号余额
                    $game->PTDeposit($gameAccount, $ptAmount);
                }
            }
            return $this->getAmount(MainGamePlat::MA, $transferTo);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('系统错误,稍后再试...', 403);
        }
    }

    /**
     * 转账记录
     *
     * @param Request $request
     * @return \View
     */
    public function transferRecords(Request $request)
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
        $parameter = array(
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        $accountStatus = PlayerAccountLog::fundTypeMeta();
        $playerAccountLog = PlayerAccountLog::where('player_id', \WinwinAuth::memberUser()->player_id)->where('fund_type', 5);
        
        if ($start_time) {
            $playerAccountLog = $playerAccountLog->whereDate('created_at', '>=', $start_time);
        }
        if ($end_time) {
            $playerAccountLog = $playerAccountLog->whereDate('created_at', '<=', $end_time);
        }
        $playerAccountLog = $playerAccountLog->orderBy('created_at', 'DESC')->paginate($perPage);
        $str = '';
        if ($request->ajax()) {
            if ($type) {
                return \WTemplate::transferLists()->with('playerAccountLog', $playerAccountLog);
            }
            if ($this->isMobile()) {
                foreach ($playerAccountLog as $accountLog) {
                    $str .= "{'转账编号':'" . $accountLog->log_id . "','转账时间':'" . $accountLog->created_at . "','转账明细':'" . $accountLog->fund_source . "','转账金额':'" . $accountLog->amount . "','明细':'" . str_limit($accountLog->remark, 20) . "'},";
                }
                $str = rtrim($str, ',');
                $str = '[' . $str . ']';
                return json_encode(array(
                    'success' => true,
                    'data' => $str
                ));
            } else {
                return \WTemplate::transferRecords()->with('playerAccountLog', $playerAccountLog);
            }
        }
        
        if ($this->isMobile()) {
            foreach ($playerAccountLog as $accountLog) {
                $str .= "{'转账编号':'" . $accountLog->log_id . "','转账时间':'" . $accountLog->created_at . "','转账明细':'" . $accountLog->fund_source . "','转账金额':'" . $accountLog->amount . "','明细':'" . str_limit($accountLog->remark, 20) . "'},";
            }
            $str = rtrim($str, ',');
            return \WTemplate::transferRecords('m')->with([
                'str' => $str,
                'accountStatus' => $accountStatus,
                'playerAccountLog' => $playerAccountLog,
                'parameter' => $parameter
            ]);
        } else {
            return \WTemplate::transferRecords()->with([
                'accountStatus' => $accountStatus,
                'playerAccountLog' => $playerAccountLog,
                'parameter' => $parameter
            ]);
        }
    }

    /**
     * 转账记录删除
     *
     * @param
     *            $id
     * @throws \Exception
     */
    public function transferRecordsDelete($id)
    {
        $result = PlayerAccountLog::where('log_id', $id)->delete();
        if ($result) {
            return $this->sendSuccessResponse(route('players.transferRecords'));
        } else {
            return $this->sendErrorResponse('删除失败');
        }
    }

    /**
     * 转账记录批量删除
     *
     * @param
     *            $log_id
     * @return \Response
     * @throws \Exception
     */
    function transferDropBatch(Request $request)
    {
        if (empty($request->get('transferLogIdArr'))) {
            return $this->sendErrorResponse('选择删除的记录', 403);
        }
        $result = PlayerAccountLog::whereIn('log_id', $request->get('transferLogIdArr'))->delete();
        if ($result) {
            return $this->sendSuccessResponse(route('players.transferRecords'));
        } else {
            return $this->sendErrorResponse('删除失败', 403);
        }
    }
}
