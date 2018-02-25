<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/12/7
 * Time: 13:31
 */

namespace App\Console\Commands;
use App\Jobs\PlayerBetFlowHandle;
use App\Jobs\PlayerBetFlowSynchronizeFailHandle;
use App\Jobs\PlayerRebateFinancialFlowHandle;
use App\Jobs\SendReminderEmail;
use App\Models\System\ReminderEmail;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use App\Vendor\GameGateway\Gateway\GameGatewayRunTime;
use Carbon\Carbon;
use Illuminate\Console\Command;


class SynchronizeMGGameFlowCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronizeGameData:mg';

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
        try{
            $gameRunTime = new GameGatewayRunTime('mg',GameGatewayRunTime::PRODUCTION);
            $records = $gameRunTime->synchronizeGameFlowToDB();
//            if(count($records) > 0){
//                $recordsCopy = $records;
//                dispatch(new PlayerBetFlowHandle($records));
//                dispatch(new PlayerRebateFinancialFlowHandle($recordsCopy));
//            }
            \WLog::info('====>同步投注记录成功');
        }catch (GameGateWaySynchronizeDBException $e){
            \WLog::error('====>请求投注数据失败,放入失败队列60秒过后处理',[
                'message' => $e->getMessage(),
                'start_date' => $e->searchCondition->start_date,
                'end_date' => $e->searchCondition->end_date
            ]);
//            dispatch((new PlayerBetFlowSynchronizeFailHandle($e->gameGateWay, $e->searchCondition))
//                ->delay(Carbon::now()->addSeconds(60))
//            );
            //dispatch(new SendReminderEmail(new ReminderEmail($e)));
        }catch (\Exception $e){
            dispatch(new SendReminderEmail(new ReminderEmail($e)));
            \WLog::error('====>同步投注记录失败',['message' => $e->getMessage()]);
        }
        \WLog::info('------------------------END------------------------');
    }
}