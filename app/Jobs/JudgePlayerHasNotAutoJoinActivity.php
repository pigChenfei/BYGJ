<?php
namespace App\Jobs;

use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 判断会员是否有自动参与的优惠活动
 * Class JudgePlayerHasAutoJoinActivity
 *
 * @package App\Jobs
 */
class JudgePlayerHasNotAutoJoinActivity implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var Player
     */
    private $player;

    /**
     *
     * @var string
     */
    private $requestIp;

    private $act_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player, $requestIp, $act_id)
    {
        $this->player = $player;
        $this->requestIp = $requestIp;
        $this->act_id = $act_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = CarrierActivity::active()->where('carrier_id', $this->player->carrier_id)
            ->where('is_active_apply', 1)
            ->find($this->act_id);
        if (!empty($activity)) {
            try {
                $activity->checkUserCanApplyActivity($this->player->player_id, $this->requestIp);
                $carrierActivityAudit = new CarrierActivityAudit();
                $carrierActivityAudit->act_id = $activity->id;
                $carrierActivityAudit->carrier_id = $this->player->carrier_id;
                $carrierActivityAudit->player_id = $this->player->player_id;
                $carrierActivityAudit->status = CarrierActivityAudit::STATUS_AUDIT;
                $carrierActivityAudit->ip = $this->requestIp;
                $carrierActivityAudit->save();
                \WLog::info('该玩家' . $this->player->user_name . '可以自动参与该活动, 活动名称:' . $activity->name);
                if($activity->censor_way == 2){
                    dispatch(new ProcessPlayerAutoAuditActivity($carrierActivityAudit));
                }
            } catch (\Exception $e) {
                \WLog::error(
                    '该玩家' . $this->player->user_name . '不能主动参与该活动,活动名称:' . $activity->name . ' 因为:' .
                         $e->getMessage() . ' file:' . $e->getFile());
            }
        } else {
            \WLog::info('用户主动参加活动不存在',['player_id'=>$this->player->player_id,'act_id'=>$this->act_id]);
        }
    }
}
