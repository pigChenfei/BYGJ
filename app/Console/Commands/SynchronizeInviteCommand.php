<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/12/7
 * Time: 13:31
 */

namespace App\Console\Commands;
use App\Jobs\SendReminderEmail;
use App\Models\System\ReminderEmail;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Vendor\GameGateway\Gateway\Exception\GameGateWaySynchronizeDBException;
use Carbon\Carbon;
use Illuminate\Console\Command;


class SynchronizeInviteCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronizeData:invite';

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
           $carrierInvitePlayerConfs = CarrierInvitePlayerConf::all();
           foreach($carrierInvitePlayerConfs as $carrierInvitePlayerConf)
           {
                //\WLog::info('------------------------邀请奖励处理'.$carrierInvitePlayerConf->id'.--');
           }

        }catch (\Exception $e){
            
        }
        \WLog::info('------------------------END------------------------');
    }
}