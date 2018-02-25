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
class JudgePlayerHasAutoJoinActivity implements ShouldQueue
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player, $requestIp)
    {
        $this->player = $player;
        $this->requestIp = $requestIp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activities = CarrierActivity::active()->where('carrier_id', $this->player->carrier_id)
            ->autoApply()
            ->get();
        \WLog::info('开始检测玩家' . $this->player->user_name . '是否可以自动参与活动');
        if ($activities->count() > 0) {
            $activities->each(
                function (CarrierActivity $activity) {
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
                        if ($activity->censor_way == 2){
                            dispatch(new ProcessPlayerAutoAuditActivity($carrierActivityAudit));
                        }
                    } catch (\Exception $e) {
                        \WLog::error(
                            '该玩家' . $this->player->user_name . '不能自动参与该活动,活动名称:' . $activity->name . ' 因为:' .
                                 $e->getMessage() . ' file:' . $e->getFile());
                    }
                });
        } else {
            \WLog::info('没有可以自动参与的活动');
        }
    }
}
