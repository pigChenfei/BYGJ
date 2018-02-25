<?php

namespace App\Jobs;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * 玩家升级队列处理
 * Class PlayerUpgradeLevelHandle
 * @package App\Jobs
 */
class PlayerUpgradeLevelHandle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Player
     */
    private $player;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $carrier = CarrierInfoCacheHelper::getCachedCarrierInfoByCarrierId($this->player->carrier_id);
        $playerLevels = CarrierInfoCacheHelper::getCachedAllActivePlayerLevelInfo($carrier);
        try{
            foreach ($playerLevels as $playerLevel){
                if($this->player->player_level_id  != $playerLevel->id){
                    $canUpgrade = $playerLevel->playerCanUpgradeLevel($this->player->player_id);
                    if($canUpgrade){
                        $this->player->player_level_id = $playerLevel->id;
                        $this->player->update();
                    }
//                    \WLog::info('检测玩家'.$this->player->user_name.'升级结束. 是否能够升级:'.($canUpgrade ? '是' : '否'));
                }
            }
        }catch (\Exception $e){
            \WLog::error('检测玩家'.$this->player->user_name.'是否能够升级失败 原因:'.$e->getMessage().' code:'.$e->getFile());
        }
    }
}
