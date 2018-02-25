<?php

namespace App\Console;

use App\Console\Commands\HandlePlayerInviteRewardCommand;
use App\Console\Commands\HandlePlayerRebateFinancialFlowExpiredCommand;
use App\Console\Commands\PassPlayerRebateFinancialFlowDailyCommand;
use App\Console\Commands\PassPlayerRebateFinancialFlowWeekCommand;
use App\Console\Commands\SynchronizePTGameFlowCommand;
use App\Console\Commands\SynchronizeMGGameFlowCommand;
use App\Console\Commands\SynchronizeSunBetGameFlowCommand;
use App\Console\Commands\SynchronizeBBinBallGameFlowCommand;
use App\Console\Commands\SynchronizeBBinLaunchGameFlowCommand;
use App\Console\Commands\SynchronizeBBinLiveGameFlowCommand;
use App\Console\Commands\SynchronizeBBinLtlotteryGameFlowCommand;
use App\Console\Commands\SynchronizeOnworksGameFlowCommand;
use App\Console\Commands\SynchronizeVRGameFlowCommand;
use App\Console\Commands\SynchronizePNGGameFlowCommand;
use App\Console\Commands\SynchronizeTTGGameFlowCommand;
use App\Console\Commands\TestCommand;
use App\Console\Schedules\CarrierWinLoseStasticsSchedule;
use App\Entities\CacheConstantPrefixDefine;
use App\Jobs\SendReminderEmail;
use App\Models\Player;
use App\Models\System\ReminderEmail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        HandlePlayerInviteRewardCommand::class,
        SynchronizePTGameFlowCommand::class,
        SynchronizeMGGameFlowCommand::class,
        SynchronizeSunBetGameFlowCommand::class,
        SynchronizeBBinBallGameFlowCommand::class,
        SynchronizeBBinLaunchGameFlowCommand::class,
        SynchronizeBBinLiveGameFlowCommand::class,
        SynchronizeBBinLtlotteryGameFlowCommand::class,
        SynchronizeOnworksGameFlowCommand::class,
        SynchronizeVRGameFlowCommand::class,
        SynchronizePNGGameFlowCommand::class,
        SynchronizeTTGGameFlowCommand::class,
        PassPlayerRebateFinancialFlowDailyCommand::class,
        PassPlayerRebateFinancialFlowWeekCommand::class,
        HandlePlayerRebateFinancialFlowExpiredCommand::class,
        TestCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //本地测试环境不做会员游戏同步处理
        //if(\App::environment() != 'local'){
            $schedule->command('synchronizeGameData:pt')->name('synchronizeGameDataPT')->everyMinute();
        //}

        $schedule->call(function (){
            try{
                $player = Player::online()->get();
                if($player->count() > 0){
                    \WLog::info('有'.$player->count().'个用户在线,开始检测登录状态是否过期');
                    $player->each(function (Player $element){
                        if(!\Cache::get(CacheConstantPrefixDefine::MEMBER_USER_ONLINE_REMEMBER_CACHE_PREFIX.$element->player_id)){
                            $element->is_online = false;
                            $element->save();
                        }
                    });
                }else{
                    \WLog::info('无在线用户');
                }
            } catch (\Exception $e){
                dispatch(new SendReminderEmail(new ReminderEmail($e)));
                \WLog::error('====>检测会员登录状态失败',['message' => $e->getMessage()]);
            }
        })->everyMinute()->name('JudgeUserOnline');

        //会员邀请好友奖励定时任务处理
        $schedule->command('playerInviteReward:run')->name('HandleInvitePlayerReward')->daily();

        //统计公司输赢,游戏输赢数据
       $schedule->call(function(){
           (new CarrierWinLoseStasticsSchedule())->run();
       })->everyFiveMinutes()->name('StasticCarrierWinLose');

        //沙巴定时任务
        $schedule->command('synchronizeGameData:onworks')->name('synchronizeGameDataOnwork')->everyFiveMinutes();

        //波音彩票定时抓单
        $schedule->command('synchronizeGameData:bbinltlottery')->name('synchronizeGameDataBBinLtlottery')->everyFiveMinutes();

        //波音真人定时抓单
        $schedule->command('synchronizeGameData:bbinlive')->name('synchronizeGameDataBBinLive')->everyFiveMinutes();

        //波音电子定时抓单
        $schedule->command('synchronizeGameData:bbinlaunch')->name('synchronizeGameDataBBinLaunch')->everyFiveMinutes();

        //波音体育定时抓单
        $schedule->command('synchronizeGameData:bbinball')->name('synchronizeGameDataBBinBall')->everyFiveMinutes();

        /* Sunbet 平台定时抓取注单 add by tlt */
        $schedule->command('synchronizeGameData:sunbet')->name('synchronizeGameDataSB')->everyMinute();

        /* MG 平台定时抓取注单 add by tlt */
        $schedule->command('synchronizeGameData:mg')->name('synchronizeGameDataMG')->everyMinute();

        /* vr 平台定时抓取注单 add by tlt */
        $schedule->command('synchronizeGameData:vr')->name('synchronizeGameDataVR')->everyMinute();

        /* png 平台定时抓取注单 add by tlt */
        $schedule->command('synchronizeGameData:png')->name('synchronizeGameDataPNG')->everyMinute();

        /* ttg 平台定时抓取注单 add by tlt */
        $schedule->command('synchronizeGameData:ttg')->name('synchronizeGameDataTTG')->everyMinute();

        /*洗码反水定时每天*/
//        $schedule->command('passPlayerRebateFinancialFlow:daily')->name('passPlayerRebateFinancialFlowDaily')->daily();
        /*洗码反水定时每周*/
//        $schedule->command('passPlayerRebateFinancialFlow:week')->name('passPlayerRebateFinancialFlowWeek')->weekly();

        /*邀请好友奖励*/
        //$schedule->command('synchronizeData:invite')->name('synchronizeDataInvite')->everyMinute();

        /*处理过期的反水记录*/
        $schedule->command('handlePlayerRebateFinancialFlowExpired')->name('handlePlayerRebateFinancialFlowExpired')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
