<?php
namespace App\Http\Controllers\Carrier\Test;

use App\Console\Schedules\CarrierWinLoseStasticsSchedule;
use App\Exceptions\PlayerAccountException;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Helpers\Caches\RouteCacheHelper;
use App\Helpers\Privileges\PrivilegeHelper;
use App\Http\Controllers\AppBaseController;
use App\Jobs\GameWinLoseStasticsJob;
use App\Jobs\PlayerBetFlowHandle;
use App\Jobs\PlayerRebateFinancialFlowHandle;
use App\Jobs\SendReminderEmail;
use App\Models\CarrierActivity;
use App\Models\CarrierPayChannel;
use App\Models\CarrierPlayerLevel;
use App\Models\Def\Game;
use App\Models\Def\PayChannel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerRebateFinancialFlow;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Models\System\ReminderEmail;
use App\Notifications\CarrierPlayerDeposit;
use App\Notifications\CarrierPlayerDepositNotification;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\GameGateway\Gateway\GameGatewaySearchCondition;
use App\Vendor\Pay\Gateway\PayOrderRuntime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class DemoB extends \Eloquent
{
}

class DemoA extends \Eloquent
{

    public $property;

    public $propertyB;

    public function __construct()
    {
        parent::__construct();
        $this->propertyB = new DemoB();
    }
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/11
 * Time: 10:15
 */
class TestGameGatewayController extends AppBaseController
{

    /**
     *
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $player;

    /**
     *
     * @var GameGatewayRunTime
     */
    public $gameRunTime;

    /**
     * TestGameGatewayController constructor.
     */
    public function __construct()
    {
        $this->player = Player::findOrFail(1);
        ;
        $this->gameRunTime = new GameGatewayRunTime('pt', GameGatewayRunTime::PRODUCTION);
    }

    public static function routeList()
    {
        \Route::get('gameTest', 'Test\TestGameGatewayController@index')->name('gameTest');
        \Route::get('gameFlow', 'Test\TestGameGatewayController@gameFlow')->name('gameFlow');
        \Route::get('playerAccount', 'Test\TestGameGatewayController@playerAccount')->name('playerAccount');
        \Route::get('loginGame', 'Test\TestGameGatewayController@loginGame')->name('loginGame');
        \Route::get('userIsOnline', 'Test\TestGameGatewayController@userIsOnline')->name('userIsOnline');
        \Route::get('depositToPlayerGameAccount', 'Test\TestGameGatewayController@depositToPlayerGameAccount')->name('depositToPlayerGameAccount');
        \Route::get('withDrawFromPlayerGameAccount', 'Test\TestGameGatewayController@withDrawFromPlayerGameAccount')->name('withDrawFromPlayerGameAccount');
        \Route::get('synchronizeGameFlowToDB', 'Test\TestGameGatewayController@synchronizeGameFlowToDB')->name('synchronizeGameFlowToDB');
    }

    public function index(Request $request)
    {
        $activity = CarrierActivity::findOrFail(1);
        $activity->checkUserCanApplyActivity(1, '127.0.0.1');
        
        // dd(Carbon::today()->toDateString());
        // $player = Player::firstOrFail();
        // $bankCard = $player->bankCards->first();
        // $payChannel = CarrierPayChannel::findOrFail(7);
        // try{
        // $order = null;
        // \DB::transaction(function() use ($player,$payChannel,$bankCard,&$order,$request){
        // $payOrder = new PayOrderRuntime($player,$payChannel,100,$bankCard);
        // $activity = CarrierActivity::findOrFail(1);
        // $activity->checkUserCanApplyActivity($player->player_id, $request->ip());
        // $order = $payOrder->createOrder(Carbon::now()->toDateTimeString(),PlayerDepositPayLog::OFFLINE_TRANSFER_ATM);
        // $order->payOrder->carrier_activity_id = $activity->id;
        // $order->payOrder->update();
        // });
        // dump($order);
        // }catch (\Exception $e){
        // dump($e->getMessage());
        // }
        // $record = new GameGatewayBetFlowRecord();
        // $record->availableBet = 1;
        // $record->balance = 2;
        // $record->gameCode = 'zcjb';
        // $record->code = 1234123123213;
        // $record->currentBet = 0;
        // $record->isBetAvailable = true;
        // $record->playerName = 'WIN_1_8_TEST1';
        // $record->bet = 1;
        // $record->playerOrBanker = 0;
        // $record->win = 0;
        // $record->playerBetFlowDBId = 100;
        // $handle = new PlayerRebateFinancialFlowHandle([$record]);
        // $handle->handle();
        return view('Test.index');
    }

    /**
     * 同步投注记录到数据库
     */
    public function synchronizeGameFlowToDB()
    {
        try {
            $this->gameRunTime->synchronizeGameFlowToDB($this->player);
            dd('同步成功');
        } catch (\Exception $e) {
            \Log::error('====>同步投注记录失败', [
                'data' => $e->getMessage()
            ]);
            throw $e;
            dd($e->getMessage());
        }
    }

    /**
     * 获取游戏投注流水记录
     */
    public function gameFlow()
    {
        try {
            $searchCondition = new GameGatewaySearchCondition();
            $gameFlow = $this->gameRunTime->gameFlow($searchCondition, $this->player);
            
            dd($gameFlow);
        } catch (\Exception $e) {
            \Log::error('====>获取投注记录失败', [
                'data' => $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }

    /**
     * 获取会员游戏账户
     */
    public function playerAccount()
    {
        try {
            $playerAccount = $this->gameRunTime->getPlayerAccount($this->player);
            dd($playerAccount);
        } catch (\Exception $e) {
            \Log::error('====>获取会员账户失败', [
                'data' => $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }

    /**
     * 登录游戏
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function loginGame(Request $request)
    {
        try {
            return view('Web.game_login')->with('playerLoginPageEntity', $this->gameRunTime->loginPageEntity($this->player, 'zcjb'));
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 判断会员是否在线
     */
    public function userIsOnline()
    {
        try {
            $result = $this->gameRunTime->isPlayerOnline($this->player);
            dd($result);
        } catch (\Exception $e) {
            \Log::error('====>判断会员是否在线失败', [
                'data' => $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }

    /**
     * 存款到游戏账户
     */
    public function depositToPlayerGameAccount()
    {
        try {
            $result = $this->gameRunTime->depositToPlayerGameAccount($this->player, 50);
            dd($result);
        } catch (\Exception $e) {
            \Log::error('====>会员存款到游戏账户失败', [
                'data' => $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }

    /**
     * 从游戏账户取款
     */
    public function withDrawFromPlayerGameAccount()
    {
        try {
            $result = $this->gameRunTime->withDrawFromPlayerGameAccount($this->player, 0.1);
            dd($result);
        } catch (\Exception $e) {
            \Log::error('====>会员从游戏账户取款失败', [
                'data' => $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }
}