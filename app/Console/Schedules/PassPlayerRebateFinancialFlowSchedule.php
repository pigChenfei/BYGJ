<?php
/**
 * Created by PhpStorm.
 * User: wugang
 * Date: 2017/4/19
 * Time: 下午9:42
 */

namespace App\Console\Schedules;


use App\Jobs\PassPlayerRebateFinancialFlow;
use App\Models\CarrierPlayerGamePlatRebateFinancialFlow;
use Illuminate\Database\Eloquent\Collection;

class PassPlayerRebateFinancialFlowSchedule
{

    private $settlePeriod;

    public function __construct($settlePeriod)
    {
        $this->settlePeriod = $settlePeriod;
    }


    public function run(){
        //获取当前设置周期的自动返水的游戏平台;
        $gamePlatRebateFlows = CarrierPlayerGamePlatRebateFinancialFlow::with(['carrierGamePlat' => function($query){
            return $query->open();
        },'rebateFinancialFlows' => function($query){
            return $query->unsettled()->earlyThanToday();
        }])->autoRebateFinancial()->settlePeriodType($this->settlePeriod)->get();
        \WLog::info('自动返水的游戏平台数量:'.$gamePlatRebateFlows->count().' 返水周期:'.$this->settlePeriod.'小时');
        //获取这些游戏平台的投注流水
        $flowLogs = $gamePlatRebateFlows->map(function($element){
            return $element->rebateFinancialFlows;
        })->reduce(function(Collection $pre, Collection $next){
                return $pre->merge($next->all());
        },new Collection([]));
        \WLog::info('数据条数:'.$flowLogs->count().' 需要处理的返水数据: ',$flowLogs->toArray());
        if($flowLogs->count() > 0){
            dispatch(new PassPlayerRebateFinancialFlow($flowLogs));
        }
    }

}