<?php

namespace App\Console\Commands;

use App\Jobs\PlayerBetFlowHandle;
use App\Jobs\PlayerRebateFinancialFlowHandle;
use App\Models\CarrierAgentUser;
use App\Models\CarrierPlayerLevel;
use App\Models\Player;
use App\Models\PlayerGameAccount;
use App\Vendor\GameGateway\Gateway\GameGatewayBetFlowRecord;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fakerUser:run';

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
            $records =  $this->generateBetFlow(1);
            $recordsCopy = $records;
    }


    private function generateBetFlow($count = 100){
        $records = [];
        $i = 0;
        do{
            $flowRecord = new GameGatewayBetFlowRecord();
            $flowRecord->playerName = 'WIN_1_8_TEST1';
            $flowRecord->gameType   = 'Live Games';
            $flowRecord->gameCode = 'bal';
            $flowRecord->bet        = rand(0,20);
            $flowRecord->win        = rand(0,$flowRecord->bet);
            $flowRecord->progressiveBet = 0;
            $flowRecord->progressiveWin = 0;
            $flowRecord->balance    = rand(0,20);
            $flowRecord->currentBet = 0;
            $flowRecord->date       = Carbon::now()->toDateTimeString();
            $flowRecord->code       = rand(11568672964,51568672964);
            $flowRecord->isBetAvailable =  true;
            $flowRecord->availableBet  = $flowRecord->bet;
            $records[] = $flowRecord;
            $i++;
        }while($i < $count);
        return $records;
    }

    private function generatePlayers($count = 100){
        $faker = Factory::create('zh_CN');
        $agents = CarrierAgentUser::with('carrier')->get();
        $playerLevels = CarrierPlayerLevel::all();
        $agent = $agents->random(1);
        $players = Player::all();
        $i = 0;
        do{
            $user = new Player();
            $user->agent_id = $agent->id;
            $user->carrier_id = $agent->carrier_id;
            $user->real_name = $faker->name;
            $user->user_name = $faker->userName;
            $user->mobile = $faker->phoneNumber;
            $user->recommend_player_id = $players->random(1)->player_id;
            $user->register_ip = $faker->ipv4;
            $user->player_level_id = $playerLevels->random(1)->id;
            $user->password = bcrypt('1234567890');
            $user->save();
            $i++;
        }while($i < $count);
    }
}
