<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\Def\BankType;
use App\Models\Player;
use App\Models\PlayerBankCard;
use App\Repositories\Carrier\PlayerRepository;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\PT\PTGameGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use App\Models\PlayerGameAccount;

/**
 * Created by PhpStorm.
 * User: winwin
 * Date: 2017/3/16
 * Time: 下午10:09
 */
class UserPerfectInformationController extends AppBaseController
{

    // 修改登录密码
    public function resetPassword(Request $request)
    {
        // 查询输入账号是否存在
        $player = Player::where('player_id', $request->get('player_id'))->first();
        if ($player) {
            $email = $request->get('email', '');
            $type = $request->get('type', '');
            if (! empty($email)) {
                if ($player->email != $email) {
                    return $this->sendErrorResponse([
                        'code' => '.enter-phone-test',
                        'message' => '邮箱号与登录账号邮箱不一致'
                    ], 404);
                }
                // 邮箱验证码处理代码
                $redis = Redis::connection();
                $code = $redis->get($email . 'code');
                if ($code != $request->get('code')) {
                    return $this->sendErrorResponse([
                        'code' => '.code',
                        'message' => '邮箱验证码不正确'
                    ], 404);
                }
                if ($type == 'qukuan') {
                    $player->pay_password = Hash::make($request->get('password'));
                } else {
                    $player->password = Hash::make($request->get('password'));
                }
                $player->save();
                $redis->set($email . 'code', null);
                // \WinwinAuth::memberAuth()->logout();
                return $this->sendSuccessResponse();
            } else {
                if ($type == 'qukuan') {
                    if (empty($player->pay_password)) {
                        if ($request->get('old_password') != '000000') {
                            return $this->sendErrorResponse('原密码错误,初始密码为000000!', 404);
                        } else {
                            $player->pay_password = Hash::make($request->get('password'));
                            $player->save();
                            return $this->sendSuccessResponse();
                        }
                    } else {
                        if (\Hash::check($request->get('old_password'), $player->pay_password) == true) {
                            $player->pay_password = Hash::make($request->get('password'));
                            $player->save();
                            return $this->sendSuccessResponse();
                        } else {
                            return $this->sendErrorResponse('原密码错误!', 404);
                        }
                    }
                } else {
                    if (\Hash::check($request->get('old_password'), $player->password) == true) {
                        $player->password = Hash::make($request->get('password'));
                        $player->save();
                        return $this->sendSuccessResponse();
                    } else {
                        return $this->sendErrorResponse('原密码错误!', 404);
                    }
                }
            }
        } else {
            return $this->sendErrorResponse('账号状态异常!', 404);
        }
    }

    // 修改取款密码
    public function resetWithdrawPassword(Request $request)
    {
        // 查询输入账号是否存在
        $player = Player::where('player_id', $request->get('player_id'))->first();
        if ($player->pay_password) {
            if (\Hash::check($request->get('old_password'), $player->pay_password) == true) {
                $player->pay_password = Hash::make($request->get('password'));
                $player->save();
                return $this->sendSuccessResponse();
            } else {
                return $this->sendErrorResponse('密码错误', 404);
            }
        } else {
            return $this->sendErrorResponse('取款密码未设置,请先设置取款密码', 404);
        }
    }

    // 修改PT密码
    public function resetPtPassword(Request $request, PlayerRepository $playerRepository)
    {
        // 根据会员ID获得会员游戏平台账号表数据和其对应的主平台
        $player = $playerRepository->with('gameAccounts.mainGamePlat')->findWithoutFail($request->get('player_id'));
        
        if (empty($player)) {
            return $this->renderNotFoundPage();
        }
        // 过滤掉其他平台,只获得PT
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

    // 修改手机号
    public function resetPhone(Request $request)
    {
        // 查询输入账号是否存在
        $player = Player::where('player_id', \WinwinAuth::memberUser()->player_id)->first();
        if ($player) {
            $email = $request->get('email', '');
            if ($player->email == $email) {
                return $this->sendErrorResponse('邮箱与登录账号邮箱不能一致', 404);
            }
            // 邮箱验证码处理代码
            $redis = Redis::connection();
            $code = $redis->get($email . 'code');
            if ($code != $request->get('code')) {
                return $this->sendErrorResponse([
                    'code' => '.code',
                    'message' => '邮箱验证码不正确'
                ], 404);
            }
            
            $player->email = $email;
            $player->save();
            $redis->set($email . 'code', null);
            \WinwinAuth::memberAuth()->logout();
            return $this->sendSuccessResponse();
        } else {
            return $this->sendErrorResponse('账号状态异常!', 404);
        }
    }

    /**
     * 修改密码
     *
     * @return \View
     */
    public function accountPassword()
    {
        if ($this->isMobile()) {
            return \WTemplate::accountPassword('m')->with('player_id', \WinwinAuth::memberUser()->player_id);
        } else {
            return \WTemplate::accountPassword();
        }
    }

    /**
     * 修改密码选择页面
     *
     * @return \View
     */
    public function selectChangepwd()
    {
        return \WTemplate::selectChangepwd();
    }

    /**
     * 手机号
     *
     * @return \View
     */
    public function accountPhone()
    {
        return \WTemplate::accountPhone();
    }

    /**
     * 取款密码修改
     *
     * @return \View
     */
    public function accountQukuan()
    {
        if ($this->isMobile()) {
            $player_id = \WinwinAuth::memberUser()->player_id;
            return \WTemplate::accountBankCard('m')->with('player_id', $player_id);
        } else {
            return \WTemplate::accountBankCard();
        }
    }

    /**
     * PT客户端密码
     *
     * @return \View
     */
    public function accountPtPassword()
    {
        $playerid = \WinwinAuth::memberUser()->player_id;
        $playergameaccount = PlayerGameAccount::where('player_id', $playerid)->where('main_game_plat_id', 4)->first();
        if (! isset($playergameaccount->account_user_name)) {
            $gameaccount = '';
        } else {
            $gameaccount = $playergameaccount->account_user_name;
        }
        return \WTemplate::accountPtPassword()->with('gameaccount', $gameaccount);
    }

    /**
     * 银行卡管理
     *
     * @return \View
     */
    public function accountBankcard()
    {
        $banks = BankType::all();
        // 当前玩家所有的取款银行卡
        $playerBankCards = PlayerBankCard::with('bankType')->get();
        // 处理银行卡号 *
        foreach ($playerBankCards as $playerBankCard) {
            if ($playerBankCard->card_account) {
                $playerBankCard->card_account = PlayerBankCard::replaceStar($playerBankCard->card_account, 4, 11);
            }
        }
        return view('Web.' . \WinwinAuth::currentWebCarrier()->template . '.player_centers.account.accountBankcard', compact('banks', 'playerBankCards'));
    }
}