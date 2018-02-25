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


class SynchronizeOnworksGameFlowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronizeGameData:onworks';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time=time()-43200;
        $dawn = strtotime(date('Y-m-d',$time));
        $oneworks =new OneWorks();
        $param=array(
            'OpCode'=>OneWorks::OPCODE,
            'StartTime'=>date('Y-m-d', $time),
            'EndTime'=>date('Y-m-d', $time+86400)
        );
        $result = $oneworks->remoteApi(OneWorks::API_GETSPORTBETTINGDETAIL,$param,1);
        $this->onworksBall($result);

        $oneworks =new OneWorks();
        if($dawn>=$time-21600)
        {
            $param=array(
            'OpCode'=>OneWorks::OPCODE,
            'StartTime'=>date('Y-m-d', $time-21600),
            'EndTime'=>date('Y-m-d', $time)
            );
        }  
        $result = $oneworks->remoteApi(OneWorks::API_GETSPORTBETTINGDETAIL,$param,1);
        $this->onworksBall($result);
    }

    //处理沙巴数据
    private function onworksBall($result)
    {
        if(!$result['error_code'])
        {
            foreach($result['Data'] as $vaule)
            {
                $isexit = PlayerBetFlowLog::where('game_flow_code',$vaule['TransId'])->where('game_plat_id',8)->first();
                if(is_null($isexit))
                {
                    $playerBetFlowLog=new PlayerBetFlowLog();
                    $playerGameAccount = PlayerGameAccount::where('account_user_name',$vaule['PlayerName'])->first();
                    $playerBetFlowLog->player_id=$playerGameAccount->player_id;
                    $player = Player::where('player_id',$playerGameAccount->player_id)->first();
                    $playerBetFlowLog->carrier_id=$player->carrier_id;

                    $playerBetFlowLog->game_type                =$vaule['MatchId'];
                    $playerBetFlowLog->game_id                  =0;
                    $playerBetFlowLog->game_plat_id             =8;
                    $playerBetFlowLog->game_flow_code           =$vaule['TransId'];
                    $playerBetFlowLog->player_or_banker         =0;
                    
                    $playerBetFlowLog->bet_amount               = $vaule['Stake'];
                    $playerBetFlowLog->available_bet_amount     = $vaule['Stake'];
                    $playerBetFlowLog->company_payout_amount    = $vaule['WinLoseAmount'];
                    $playerBetFlowLog->bet_flow_available       = 1;
                    $playerBetFlowLog->bet_info                 ='';

                    if($vaule['TicketStatus']=='WIN'||$vaule['TicketStatus']=='LOSE')
                    {
                        $playerBetFlowLog->game_status=1;
                        $playerBetFlowLog->company_win_amount=$playerBetFlowLog->bet_amount-$playerBetFlowLog->company_payout_amount ;
                    }
                    else
                    {
                        $playerBetFlowLog->game_status=0;
                        $playerBetFlowLog->company_win_amount=0;
                    }
                    $playerBetFlowLog->save();
                }
                else
                {
                    $isexit->available_bet_amount     = $vaule['Stake'];
                    $isexit->company_payout_amount    = $vaule['WinLoseAmount'];
                    $isexit->bet_flow_available       = 1;
                    $isexit->bet_info                 ='';
                    if($vaule['TicketStatus']=='WIN'||$vaule['TicketStatus']=='LOSE')
                    {
                        $isexit->game_status=1;
                        $isexit->company_win_amount=$vaule['WinLoseAmount']>0?$vaule['WinLoseAmount']:0;
                    }
                    else
                    {
                        $isexit->game_status=0;
                        $isexit->company_win_amount=0;
                    }
                    $isexit->save();
                }
            }
        }
    }
}
