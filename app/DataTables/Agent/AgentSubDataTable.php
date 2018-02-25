<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\CarrierAgentLevel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Player;
use Carbon\Carbon;
use App\Models\CarrierAgentUser;

class AgentSubDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables
            ->eloquent($this->query())
            ->addColumn('type_name',function(CarrierAgentUser $agentlevel){
                return CarrierAgentLevel::typeMeta()[$agentlevel['agentLevel']->type];
            })
            //存款
            ->addColumn('deposit_total',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //取款
            ->addColumn('withdraw_total',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->accountLogs->map(function(PlayerAccountLog $log){
                        return $log->amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //有效投注额
            ->addColumn('availableBetAmount',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->betFlowLogs->map(function(PlayerBetFlowLog $log){
                        return $log->bet_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //存款优惠
            ->addColumn('depositBenefitAmount',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->benefit_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //红利
            ->addColumn('bonusAmount',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->bonus_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //洗码
            ->addColumn('rebateFinancialFlowAmount',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->accountLogs->map(function(PlayerAccountLog $log){
                        if($log->fund_type == PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW){
                            $log->amount += $log->amount;
                        }
                        return $log->amount = 0.00;
                    })->reduce(function($pre,$next){ return $pre + $next; });
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //子代理公司输赢
            ->addColumn('winlose_total',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->total_win_loss;
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //子代理佣金
//            ->addColumn('commission_total',function(CarrierAgentUser $agentUser){
//                //佣金代理
//                if($agentUser->agentLevel->type = CarrierAgentLevel::COMMISSION_AGETN){
//                    //按照阶梯比例计算;
//                    if($agentUser->agentLevel->commissionAgentConf != null)
//                    {
//                        $commission_array = json_decode($agentUser->agentLevel->commissionAgentConf->commission_step_ratio,true);
//                        if($commission_array){
//                                return $agentUser->players->map(function(Player $player){
//                                    return $player->total_win_loss;
//                                })->reduce(function($pre,$next){ return $pre + $next; });
////                                $formatArray = array_map(function($element){
////                                    if(($player->total_win_loss) >= $element['flowAmount']){
////                                        return $player->commission_total = ($player->total_win_loss) * $element['flowRate'];
////                                    }
////                                },$commission_array);
//                        }else{
//                            //否则按照总佣金比例来执行;
//                            return $agentUser->players->map(function(Player $player){
//                                return $player->commission_total = $player->total_win_loss * $agentUser->agentLevel->commissionAgentConf->commission_ratio;
//                            })->reduce(function($pre,$next){ return $pre + $next; });
//                        }
//                    }
//                }else if($agentUser->agentLevel->type = CarrierAgentLevel::REBATE_FINANCIAL_FLOW_AGENT){
//                    //占成代理
//                    return $agentUser->players->map(function(Player $player){
//                        return $commission_total = $player->total_win_loss * $agentUser->agentLevel->costTakeAgentConf->cost_take_ration * 0.01;
//                    })->reduce(function($pre,$next){ return $pre + $next; });
//                }
//                
//            })
            //获得子代理佣金提成
            ->addColumn('sub_agent_commission_total',function(CarrierAgentUser $agentUser){
                return $agentUser->players->map(function(Player $player){
                    return $player->total_win_loss;
                })->reduce(function($pre,$next){ return $pre + $next; });
            });
        return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
//        $carrierAgentUsers = CarrierAgentUser::with('getAgentLevel')->select("*")->where('parent_id','=',\WinwinAuth::agentUser()->id);
        $carrierAgentUsers = CarrierAgentUser::with(['agentLevel','agentLevel.commissionAgentConf','agentLevel.costTakeAgentConf','agentLevel.rebateFinancialFlowAgentBaseConf','players.betFlowLogs'=>function($query){
            $query->betFlowAvailable();
        },'players.accountLogs','players.depositLogs' => function($query){
            $query->payedSuccessfully();
        }])->select("*")->where('parent_id','=',\WinwinAuth::agentUser()->id);
        return $this->applyScopes($carrierAgentUsers);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax([
                'data' => \Config::get('datatables.ajax.data')
            ])
            ->parameters([
                    'paging' => true,
                    'searching' => false,
                    'ordering' => false,
                    'info' => true,
                    'dom' => 'Bfrtipl',
                    'scrollX' => false,
                    'buttons' => [
                    ],
                    'language' => \Config::get('datatables.language'),
                    'drawCallback' => 'function(){
                    var api = this.api();
            　　    var startIndex= api.context[0]._iDisplayStart;
                　　api.column(0).nodes().each(function(cell, i) {
                　　　　cell.innerHTML = startIndex + i + 1;
                　　});
                    
                }']
            );
    }


    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['name' => 'id', 'data' => 'id'],
            '子代理账号' => ['name' => 'username','data' => 'username','render' => 'function(){
                return ("<a class=\"text-primary user_edit\" data-id=\""+ this.id +"\" style=\"cursor: pointer\">" + this.username  + "</a>") 
            }','defaultContent' => '','style' => 'text-align:center'],
            '存款总额' => ['name' => 'deposit_total', 'data' => 'deposit_total','defaultContent' => '0.00'],
            '取款总额' => ['name' => 'id', 'data' => 'withdraw_total','defaultContent' => '0.00','style' => 'text-align:center'],
            '有效投注额' => ['name' => 'id', 'data' => 'availableBetAmount','defaultContent' => '0.00','style' => 'text-align:center'],
            '存款优惠' => ['name' => 'id', 'data' => 'depositBenefitAmount','defaultContent' => '0.00','style' => 'text-align:center'],
            '红利' => ['name' => 'id', 'data' => 'bonusAmount','defaultContent' => '0.00','style' => 'text-align:center'],
            '洗码' => ['name' => 'id', 'data' => 'rebateFinancialFlowAmount','defaultContent' => '0.00','style' => 'text-align:center'],
            '子代理公司输赢' => ['name' => 'id', 'data' => 'winlose_total','defaultContent' => '0.00','style' => 'text-align:center'],
            '子代理佣金' => ['name' => 'id', 'data' => 'winlose_total','defaultContent' => '0.00','style' => 'text-align:center'],
            '获得子代理佣金提成' => ['name' => 'id', 'data' => 'sub_agent_commission_total','defaultContent' => '0.00','style' => 'text-align:center'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierAgentUsers';
    }
}
