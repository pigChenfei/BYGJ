<?php
namespace App\Console\Commands;

use App\Jobs\PlayerBetFlowHandle;
use App\Jobs\PlayerBetFlowSynchronizeFailHandle;
use App\Jobs\PlayerRebateFinancialFlowHandle;
use App\Jobs\SendReminderEmail;
use App\Models\System\ReminderEmail;
use App\Models\CarrierPayChannel;
use App\Models\Log\PlayerLoginLog;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Models\Log\PlayerBetFlowLog;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use App\Vendor\Pay\Gateway\PayOrderRuntime;
use App\Vendor\GameGateway\Bbin\BBin;
use App\Vendor\GameGateway\OneWorks\OneWorks;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\AppBaseController;

class SynchronizeBBinLaunchGameFlowCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronizeGameData:bbinlaunch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * BBin电子
     *
     * @return mixed
     */
    public function handle()
    {
        $time = time() - 43200;
        $subgamekind = array(
            1,
            2,
            3,
            5
        );
        $param = array(
            'keyb' => BBin::BetRecordKeyB,
            'uppername' => BBin::Uppername,
            'website' => BBin::Website,
            'front' => '1',
            'after' => '7',
            'page' => '1',
            'gamekind' => 5
        );
        foreach ($subgamekind as $v1) {
            $param['subgamekind'] = $v1;
            $param = $this->timeInterval($param, $time, 5);
            $this->bbinMorePage($param, 'bbinLaunch');
        }
    }

    // 多页抓取
    private function bbinMorePage($param, $fun)
    {
        $bin = new BBin();
        do {
            $result = '';
            do {
                $result = $bin->remoteApi(BBin::API_BetRecord, $param, 0, 0);
            } while (! $result['result']);
            if ($result['pagination']['Page'] != $result['pagination']['TotalPage']) {
                $param['page'] = $param['page'] + 1;
            }
            // 写入数据
            $this->$fun($result);
        } while ($result['pagination']['Page'] < $result['pagination']['TotalPage']);
    }

    // @interval 分钟数抓取多长的订单
    // 抓取间隔规则
    private function timeInterval($param, $time, $interval)
    {
        $minutetime = $interval * 60;
        $dawn = strtotime(date('Y-m-d', $time));
        if ($time - $dawn < $minutetime / 2) {
            // 如果在0：05之前只查前一天数据
            $param['rounddate'] = date("Y/m/d", $time - $minutetime);
            $param['starttime'] = date("H:i:s", $dawn - $minutetime / 2);
            $param['endtime'] = date("H:i:s", $dawn - 1);
        } else if ($time - $dawn >= $minutetime / 2 && $time - $dawn < $minutetime) {
            // 如果在0：05至0:10时查当天所有数据
            $param['rounddate'] = date("Y/m/d", $time);
        } else {
            // 如果在0:10分之后查前10分钟数据
            $param['rounddate'] = date("Y/m/d", $time);
            $param['starttime'] = date("H:i:s", $time - $minutetime);
            $param['endtime'] = date("H:i:s", $time);
        }
        return $param;
    }

    // BB电子处理
    public function bbinLaunch($result)
    {
        \WLog::info("======> bbin launch 获取数据成功", $result);
        if ($result['result']) {
            foreach ($result['data'] as $key => $vaule) {
                $isexit = PlayerBetFlowLog::where('game_flow_code', $vaule['WagersID'])->where('game_plat_id', 9)->first();
                if (is_null($isexit)) {
                    $playerBetFlowLog = new PlayerBetFlowLog();
                    $playerGameAccount = PlayerGameAccount::where('account_user_name', $vaule['UserName'])->first();
                    $playerBetFlowLog->player_id = $playerGameAccount->player_id;
                    $player = Player::where('player_id', $playerGameAccount->player_id)->first();
                    $playerBetFlowLog->carrier_id = $player->carrier_id;
                    
                    $playerBetFlowLog->game_type = $vaule['GameType'];
                    $playerBetFlowLog->game_id = 0;
                    $playerBetFlowLog->game_plat_id = 7;
                    $playerBetFlowLog->game_flow_code = $vaule['WagersID'];
                    $playerBetFlowLog->player_or_banker = 0;
                    
                    $playerBetFlowLog->bet_amount = $vaule['BetAmount'];
                    $playerBetFlowLog->available_bet_amount = $vaule['Commissionable'];
                    $playerBetFlowLog->company_payout_amount = $vaule['Payoff'] < 0 ? 0 : $vaule['Payoff'];
                    $playerBetFlowLog->bet_flow_available = $vaule['Commissionable'] ? 1 : 0;
                    $playerBetFlowLog->bet_info = '';
                    
                    if ($vaule['Result'] == '1' || $vaule['Result'] == '200') {
                        $playerBetFlowLog->game_status = 1;
                        $playerBetFlowLog->company_win_amount = $vaule['Payoff'] < 0 ? abs($vaule['Payoff']) : - $vaule['Payoff'];
                    } else if ($vaule['Result'] == '-1') {
                        $playerBetFlowLog->game_status = 2;
                        $playerBetFlowLog->company_win_amount = 0;
                    } else {
                        $playerBetFlowLog->game_status = 0;
                        $playerBetFlowLog->company_win_amount = 0;
                    }
                    try {
                        $playerBetFlowLog->save();
                    } catch (\PDOException $e) {
                        \WLog::error("======> bbin launch insert 同步数据异常 " . $e->getMessage());
                    }
                } else {
                    $isexit->available_bet_amount = $vaule['Commissionable'];
                    $isexit->company_payout_amount = $vaule['Payoff'];
                    $isexit->bet_flow_available = $vaule['Commissionable'] ? 1 : 0;
                    $isexit->bet_info = '';
                    if ($vaule['Result'] == '1' || $vaule['Result'] == '200') {
                        $isexit->game_status = 1;
                        $isexit->company_win_amount = $vaule['Payoff'] < 0 ? abs($vaule['Payoff']) : - $vaule['Payoff'];
                    } else if ($vaule['Result'] == '-1') {
                        $isexit->game_status = 2;
                        $isexit->company_win_amount = 0;
                    } else {
                        $isexit->game_status = 0;
                        $isexit->company_win_amount = 0;
                    }
                    try {
                        $isexit->save();
                    } catch (\PDOException $e) {
                        \WLog::error("======> bbin launch update 同步数据异常 " . $e->getMessage());
                    }
                }
            }
        }
    }
}
