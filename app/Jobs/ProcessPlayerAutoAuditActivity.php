<?php

namespace App\Jobs;

use App\Exceptions\CarrierRuntimeException;
use App\Models\Activity\ActivityPassReviewBonusFactory\ActivityPassReviewBonusAbstract;
use App\Models\Activity\ActivityPassReviewBonusFactory\ActivityPassReviewBonusFactory;
use App\Models\CarrierActivityAudit;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * 自动审核玩家的优惠活动
 * Class ProcessPlayerAutoAuditActivity
 * @package App\Jobs
 */
class ProcessPlayerAutoAuditActivity implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var CarrierActivityAudit;
     */
    private $auditActivity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CarrierActivityAudit $activity)
    {
        $this->auditActivity = $activity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->auditActivity->activity;
        if($this->auditActivity->status != CarrierActivityAudit::STATUS_AUDIT){
            \WLog::info('该审核记录已经审核过了,不需要再次审核. 活动id:'.$activity->id.' 活动名称:'.$activity->name);
            return;
        }
        $player = $this->auditActivity->player;
        try{
            $carrierFactory = ActivityPassReviewBonusFactory::createFactory($this->auditActivity);
            $carrierFactory->audit_type = ActivityPassReviewBonusAbstract::AUDIT_AUTO;
            \DB::transaction(function () use ($carrierFactory,$player){
                //处理活动的的红利数据
                $carrierFactory->handleBonus($player);
                $this->auditActivity->remark = '系统自动审核';
                $this->auditActivity->status = CarrierActivityAudit::STATUS_ADOPT;
                $this->auditActivity->update();
            });
            \WLog::info('玩家活动自动审核成功: 玩家id'.$player->player_id.' 姓名:'.$player->user_name.' 活动id:'.$activity->id.' 活动名称:'.$activity->name);
        }
        catch (\Exception $e) {
            \WLog::error('玩家活动审核失败:'.$e->getMessage().' '.$e->getFile().' '.$e->getLine(),$e->getTrace());
        }

    }
}
