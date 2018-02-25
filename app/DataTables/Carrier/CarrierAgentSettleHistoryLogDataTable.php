<?php
namespace App\DataTables\Carrier;

use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\CarrierAgentSettleLog;

class CarrierAgentSettleHistoryLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_commission_settle_logs.datatables_actions')
            ->addColumn('cumulative_last_month_all',
            function (CarrierAgentSettleLog $log) {
                return bcadd($log->cumulative_last_month_rebate, $log->cumulative_last_month, 2);
            })
            ->addColumn('manual_tuneup_all', function (CarrierAgentSettleLog $log) {
            return bcadd($log->manual_tuneup_rebate, $log->manual_tuneup, 2);
        })
            ->addColumn('transfer_next_month_all',
            function (CarrierAgentSettleLog $log) {
                return bcadd($log->transfer_next_month, $log->transfer_next_month_rebate, 2);
            })
            ->addColumn('actual_payment_all', function (CarrierAgentSettleLog $log) {
            return bcadd($log->actual_payment, $log->actual_payment_rebate, 2);
        })
            ->addColumn('status_name', function (CarrierAgentSettleLog $log) {
            return CarrierAgentSettleLog::statusMeta()[$log->status];
        })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierActivityAudit = CarrierAgentSettleLog::with([
            'agent.agentLevel',
            'settlePeriods'
        ])->where([
            'status' => CarrierAgentSettleLog::SET_COMPLETED_STATUS
        ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierActivityAudit);
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
            ->parameters(
            [
                'paging' => true,
                'searching' => false,
                'ordering' => false,
                'info' => true,
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                    var api = this.api();
                    var startIndex= api.context[0]._iDisplayStart;
                    api.column(0).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                });

                $(".cost_share_edit").on("click",function(){
                    $.fn.overlayToggle();
                    var _me = this;
                    var user_id = $(_me).attr("data-id")
                    $.fn.winwinAjax.buttonActionSendAjax(_me,"' .
                     route('carrierAgentSettleLogs.costShare', null) . '/"+ user_id,{},function(content){
                        $.fn.overlayToggle();
                        $("#userInfoEditModal").html(content);
                        $("#userInfoEditModal").modal("show");
                    },function(){

                    },"GET",{dataType:"html"})
                })
                
                $(".rebate_edit").on("click",function(){
                    $.fn.overlayToggle();
                    var _me = this;
                    var user_id = $(_me).attr("data-id")
                    $.fn.winwinAjax.buttonActionSendAjax(_me,"' .
                     route('carrierAgentSettleLogs.rebate', null) . '/"+ user_id,{},function(content){
                        $.fn.overlayToggle();
                        $("#userInfoEditModal").html(content);
                        $("#userInfoEditModal").modal("show");
                    },function(){

                    },"GET",{dataType:"html"})
                })
                
                }'
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => [
                'data' => 'id'
            ],
            '结算期' => [
                'data' => 'settle_periods.periods',
                'defaultContent' => '',
                'orderable' => false
            ],
            '代理账号' => [
                'data' => 'agent.username',
                'defaultContent' => '',
                'orderable' => false
            ],
            '代理名称' => [
                'data' => 'agent.agent_level.level_name',
                'defaultContent' => '',
                'orderable' => false
            ],
            '有效会员' => [
                'name' => 'available_member_number',
                'data' => 'available_member_number',
                'defaultContent' => '',
                'orderable' => false
            ],
            '公司输赢' => [
                'data' => 'game_plat_win_amount',
                'defaultContent' => '0.00',
                'orderable' => false
            ],
            '成本分摊' => [
                'data' => 'cost_share',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<a class=\"text-primary cost_share_edit\" data-id=\""+ (this.id ? this.id : null) +"\" style=\"cursor: pointer\">" + (this.cost_share ? this.cost_share : 0.00) + "</a>"
            }'
            ],
            '累加上月' => [
                'data' => 'cumulative_last_month_all',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<div data-toggle=\"tooltip\" style=\"cursor:pointer;color:#337ab7\" data-original-title=\"累加上月佣金："+this.cumulative_last_month+"  累加上月洗码："+this.cumulative_last_month_rebate+"\">" + (this.cumulative_last_month_all ? this.cumulative_last_month_all : 0.00) + "</div>"
            }'
            ],
            '手工调整' => [
                'data' => 'manual_tuneup_all',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<div data-toggle=\"tooltip\" data-original-title=\"手工调整佣金："+this.manual_tuneup+"  手工调整洗码金额："+this.manual_tuneup_rebate+"\" style=\"cursor: pointer;color:#337ab7\">" + (this.manual_tuneup_all ? this.manual_tuneup_all : 0.00) + "</div>"
            }'
            ],
            '本期佣金' => [
                'data' => 'this_period_commission',
                'defaultContent' => '0.00',
                'orderable' => false
            ],
            '本期洗码' => [
                'data' => 'rebate_amount',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<a class=\"text-primary rebate_edit\" data-id=\""+ (this.id ? this.id : null) +"\" style=\"cursor: pointer\">" + (this.rebate_amount ? this.rebate_amount : 0.00) + "</a>"
            }'
            ],
            '实际发放' => [
                'data' => 'actual_payment_all',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<div data-toggle=\"tooltip\" data-original-title=\"实际发放佣金："+this.actual_payment+"  手工调整洗码金额："+this.actual_payment_rebate+"\" style=\"cursor: pointer;color:#337ab7\">" + (this.actual_payment_all ? this.actual_payment_all : 0.00) + "</div>"
            }'
            ],
            '结转下月' => [
                'data' => 'transfer_next_month_all',
                'defaultContent' => '0.00',
                'orderable' => false,
                'render' => 'function(){
                      return "<div data-toggle=\"tooltip\" data-original-title=\"佣金结转："+this.transfer_next_month+"  洗码结转："+this.transfer_next_month_rebate+"\" style=\"cursor: pointer;color:#337ab7\">" + (this.transfer_next_month_all ? this.transfer_next_month_all : 0.00) + "</div>"
            }'
            ],
            '审核状态' => [
                'data' => 'status_name',
                'defaultContent' => '',
                'orderable' => false
            ],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark',
                'defaultContent' => '',
                'orderable' => false
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierActivityTypes';
    }
}
