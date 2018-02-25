<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Player;
use Carbon\Carbon;

class AgentPlayerDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables
            ->eloquent($this->query())
            //存款
            ->addColumn('deposit_total',function(Player $agentPlayer){
                    return $agentPlayer->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //取款
            ->addColumn('withdraw_total',function(Player $agentPlayer){
                    return $agentPlayer->accountLogs->map(function(PlayerAccountLog $log){
                        if($log->fund_type == PlayerAccountLog::FUND_TYPE_WITHDRAW){
                            return $log->amount;
                        }
                        return $log->amount = 0.00;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //有效投注额
            ->addColumn('availableBetAmount',function(Player $agentPlayer){
                    return $agentPlayer->betFlowLogs->map(function(PlayerBetFlowLog $log){
                        return $log->bet_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //存款优惠
            ->addColumn('depositBenefitAmount',function(Player $agentPlayer){
                    return $agentPlayer->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->benefit_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //红利
            ->addColumn('bonusAmount',function(Player $agentPlayer){
                    return $agentPlayer->depositLogs->map(function(PlayerDepositPayLog $log){
                        return $log->bonus_amount;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //洗码
            ->addColumn('rebateFinancialFlowAmount',function(Player $agentPlayer){
                    return $agentPlayer->accountLogs->map(function(PlayerAccountLog $log){
                        if($log->fund_type == PlayerAccountLog::FUND_TYPE_FINANCIAL_FLOW){
                            return $log->amount;
                        }
                        return $log->amount = 0.00;
                    })->reduce(function($pre,$next){ return $pre + $next; });
            })
            //子代理公司输赢
            ->addColumn('winlose_total',function(Player $agentPlayer){
                    return $agentPlayer->total_win_loss;
            });
//            if($date_time_range = $this->request()->get('date_time_range')){
//                $time = explode(' - ',$date_time_range);
//                if(count($time) == 2){
//                    $dataTables->where('created_at','>=',$time[0]);
//                    $dataTables->where('created_at', '<=', $time[1]);
//                }
//            }
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $agentPlayer = Player::with(['betFlowLogs'=>function($query){
            $query->betFlowAvailable();
        },'accountLogs','depositLogs' => function($query){
            $query->payedSuccessfully();
        }])->select("*")->where(['agent_id'=>\WinwinAuth::agentUser()->id]);
        return $this->applyScopes($agentPlayer);
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
        /*return [
            '序号' => ['name' => 'player_id', 'data' => 'player_id','defaultContent' => ''],
            '会员账号' => ['name' => 'user_name','data' => 'user_name','defaultContent' => ''],
            '注册日期' => ['name' => 'created_at', 'data' => 'created_at','defaultContent' => ''],
            '存款总额' => ['name' => 'id', 'data' => 'deposit_total','defaultContent' => '0.00','render' => 'function(){
                return ("<a href=\"agentPlayerDepositLogs/" + this.player_id + "\"  style=\"cursor: pointer\">" + (this.deposit_total ? this.deposit_total : 0.00)  + "</a>")
            }'],
            '取款总额' => ['name' => 'id', 'data' => 'withdraw_total','defaultContent' => '0.00','render' => 'function(){
                return ("<a href=\"agentPlayerWithdrawLogs/" + this.player_id + "\"  style=\"cursor: pointer\">" + (this.withdraw_total ? this.withdraw_total : 0.00)  + "</a>")
            }'],
            '存款优惠' => ['name' => 'id', 'data' => 'depositBenefitAmount','defaultContent' => '0.00'],
            '红利' => ['name' => 'id', 'data' => 'bonusAmount','defaultContent' => '0.00','render' => 'function(){
                return ("<a href=\"agentPlayerActivityLogs/" + this.player_id + "\"  style=\"cursor: pointer\">" + (this.bonusAmount ? this.bonusAmount : 0.00)  + "</a>")
            }'],
            '洗码' => ['name' => 'id', 'data' => 'rebateFinancialFlowAmount','render' => 'function(){
                return ("<a href=\"playerRebateFinancialFlows/" + this.player_id + "\"  style=\"cursor: pointer\">" + (this.rebateFinancialFlowAmount ? this.rebateFinancialFlowAmount : 0.00)  + "</a>")
            }','defaultContent' => '0.00'],
            '投注额' => ['name' => 'id', 'data' => 'availableBetAmount','defaultContent' => '0.00','render' => 'function(){
                return ("<a href=\"agentPlayerBetLogs/" + this.player_id + "\"  style=\"cursor: pointer\">" + (this.availableBetAmount ? this.availableBetAmount : 0.00)  + "</a>")
            }',],
            '公司输赢' => ['name' => 'id', 'data' => 'winlose_total','defaultContent' => '0.00'],
        ];*/
        return [
            '账号' => ['name' => 'user_name','data' => 'user_name','class'=>'text-center','defaultContent' => ''],
            '姓名' => ['name' => 'real_name','data' => 'real_name','class'=>'text-center','defaultContent' => ''],
            '状态' => ['name' => 'user_status','data' => 'user_status_name','class'=>'text-center','defaultContent' => '','render'=>'function(){
            return this.user_status == 1?"正常":(this.user_status == 2?"关闭":"锁定");
            }'],
            '总存款' => ['name' => 'deposit_total', 'data' => 'deposit_total','class'=>'text-center','defaultContent' => '0.00'],
            '总取款' => ['name' => 'withdraw_total', 'data' => 'withdraw_total','class'=>'text-center','defaultContent' => '0.00'],
            '总优惠' => ['name' => 'depositBenefitAmount', 'data' => 'depositBenefitAmount','class'=>'text-center','defaultContent' => '0.00'],
            '总洗码' => ['name' => 'rebateFinancialFlowAmount', 'data' => 'rebateFinancialFlowAmount','class'=>'text-center','defaultContent' => '0.00'],
            '公司总输赢' => ['name' => 'winlose_total', 'data' => 'winlose_total','class'=>'text-center','defaultContent' => '0.00'],
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
