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




class SynchronizeBBinBallGameFlowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronizeGameData:bbinball';

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
     * BBin体育
     *
     * @return mixed
     */
    public function handle()
    {
        $time=time()-43200;

        $param=array(
            'keyb'=>BBin::BetRecordKeyB,
            'uppername'=>BBin::Uppername,
            'website'=>BBin::Website,
            'front'=>'1',
            'after'=>'7',
            'page'=>'1',
            'gamekind'=>1,
            'rounddate'=>date("Y/m/d",$time)
        );
            $this->bbinMorePage($param,'bbinBall');
    }

    //多页抓取
    private function bbinMorePage($param,$fun)
    {
        $bin = new BBin();
        do
        {
            $result='';
            do
            {
                $result=$bin->remoteApi(BBin::API_BetRecord,$param,0,0);
            }while(!$result['result']);
            if($result['pagination']['Page']!=$result['pagination']['TotalPage'])
            {
                $param['page']=$param['page']+1;
            }

            //写入数据
            $this->$fun($result);
        }while($result['pagination']['Page']<$result['pagination']['TotalPage']);
    }

    //BB体育处理
    public function bbinBall($result)
    {
        if($result['result'])
        {
            foreach($result['data'] as $key=>$vaule)
            {
                $isexit = PlayerBetFlowLog::where('game_flow_code',$vaule['WagersID'])->where('game_plat_id',7)->first();
                if(is_null($isexit))
                {
                    $playerBetFlowLog=new PlayerBetFlowLog();
                    $playerGameAccount = PlayerGameAccount::where('account_user_name',$vaule['UserName'])->first();
                    $playerBetFlowLog->player_id=$playerGameAccount->player_id;
                    $player = Player::where('player_id',$playerGameAccount->player_id)->first();
                    $playerBetFlowLog->carrier_id=$player->carrier_id;

                    $playerBetFlowLog->game_type                =$vaule['GameType'];
                    $playerBetFlowLog->game_id                  =0;
                    $playerBetFlowLog->game_plat_id             =7;
                    $playerBetFlowLog->game_flow_code           =$vaule['WagersID'];
                    $playerBetFlowLog->player_or_banker         =0;
                    
                    $playerBetFlowLog->bet_amount               = $vaule['BetAmount'];
                    $playerBetFlowLog->available_bet_amount     = $vaule['Commissionable'];
                    $playerBetFlowLog->company_payout_amount    = $vaule['Payoff'];
                    $playerBetFlowLog->bet_flow_available       =$vaule['Commissionable']?1:0;
                    $playerBetFlowLog->bet_info                 ='';

                    if($vaule['Result']=='W'||$vaule['Result']=='L'||$vaule['Result']=='LW'||$vaule['Result']=='0')
                    {
                        $playerBetFlowLog->game_status=1;
                        $playerBetFlowLog->company_win_amount=$playerBetFlowLog->bet_amount-$playerBetFlowLog->company_payout_amount ;
                    }
                    else if($vaule['Result']=='F'||$vaule['Result']=='D'||$vaule['Result']=='C')
                    {
                        $playerBetFlowLog->game_status=2;
                        $playerBetFlowLog->company_win_amount=0;
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
                    $isexit->available_bet_amount     = $vaule['Commissionable'];
                    $isexit->company_payout_amount  = $vaule['Payoff'];
                    $isexit->bet_flow_available       =$vaule['Commissionable']?1:0;
                    $isexit->bet_info                 ='';
                    if($vaule['Result']=='W'||$vaule['Result']=='L'||$vaule['Result']=='LW'||$vaule['Result']=='0')
                    {
                        $isexit->game_status=1;
                        $isexit->company_win_amount=$isexit->bet_amount-$isexit->company_payout_amount ;
                    }
                    else if($vaule['Result']=='F'||$vaule['Result']=='D'||$vaule['Result']=='C')
                    {
                        $isexit->game_status=2;
                        $isexit->company_win_amount=0;
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
