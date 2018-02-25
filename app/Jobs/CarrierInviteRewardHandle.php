<?php
namespace App\Jobs;

use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Log\PlayerInviteRewardLog;
use App\Jobs\PlayerInviteBetRewardHandle;
use App\Jobs\PlayerInviteDepositRewardHandle;
use App\Models\Carrier;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CarrierInviteRewardHandle implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var Player
     */
    private $carrier;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Carrier $carrier)
    {
        $this->carrier = $carrier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invitePlayerConf = $this->getCachedInvitePlayerConf();
        
        if (! $invitePlayerConf) {
            return;
        }
        
        // 投注奖励最后一条
        $playBetlog = PlayerInviteRewardLog::where('carrier_id', $this->carrier->id)->where('reward_type', 2)
            ->orderBy('id', 'desc')
            ->first();
        
        // 存款奖励最后一条
        $playDepositlog = PlayerInviteRewardLog::where('carrier_id', $this->carrier->id)->where('reward_type', 1)
            ->orderBy('id', 'desc')
            ->first();
        
        // 查询运营商下面玩家
        $players = Player::where('carrier_id', $this->carrier->id)->get();
        
        $now = Carbon::now();
        if ($playBetlog) {
            $diffdays = $now->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $playBetlog->created_at));
            if (($invitePlayerConf->bet_reward_settle_period == 1 && $diffdays > 0) || ($invitePlayerConf->bet_reward_settle_period == 7 && ($diffdays % 7 == 0))) {
                // 投注额奖励
                foreach ($players as $player) {
                    \WLog::info('每天计算');
                    dispatch(new PlayerInviteBetRewardHandle($player));
                }
            }
        } else {
            \Wlog::info('进入计算2');
            if (($invitePlayerConf->bet_reward_settle_period == 7 && $now->isMonday()) || $invitePlayerConf->bet_reward_settle_period == 1) {
                foreach ($players as $player) {
                    dispatch(new PlayerInviteBetRewardHandle($player));
                }
            }
        }
        
        if ($playDepositlog) {
            \Wlog::info('进入计算3');
            $diffdays = $now->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $playDepositlog->created_at));
            if (($invitePlayerConf->deposit_reward_settle_period == 1 && $diffdays > 0) || ($invitePlayerConf->deposit_reward_settle_period == 7 && ($diffdays % 7 == 0))) {
                // 存款额奖励
                foreach ($players as $player) {
                    dispatch(new PlayerInviteDepositRewardHandle($player));
                }
            }
        } else {
            \Wlog::info('进入计算4');
            if (($invitePlayerConf->deposit_reward_settle_period == 7 && $now->isMonday()) || $invitePlayerConf->deposit_reward_settle_period == 1) {
                foreach ($players as $player) {
                    dispatch(new PlayerInviteDepositRewardHandle($player));
                }
            }
        }
    }

    /**
     *
     * @return CarrierInvitePlayerConf
     */
    private function getCachedInvitePlayerConf()
    {
        return CarrierInfoCacheHelper::getCachedInvitePlayerConf($this->carrier);
    }
}
