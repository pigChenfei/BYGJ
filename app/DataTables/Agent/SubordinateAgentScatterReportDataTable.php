<?php

namespace App\DataTables\Agent;
use App\Models\CarrierAgentUser;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\CarrierAgentSettleLog;
class SubordinateAgentScatterReportDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'agent.subordinate_agent_scatter_report.datatables_actions')
//            ->addColumn('status_name',function(CarrierAgentCommissionSettleLog $log){
//                return CarrierAgentCommissionSettleLog::statusMeta()[$log->status];
//            })
            ->make(true);
    }


    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierActivityAudit = CarrierAgentUser::with('agentSettle.settlePeriods')->where('parent_id','=',\WinwinAuth::agentUser()->id);
        //$carrierActivityAudit = CarrierAgentSettleLog::with(['settlePeriods.agentUser'])->where('agent_id','=',\WinwinAuth::agentUser()->id);
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
                
                $(".details_report").on("click",function(){
                    $.fn.overlayToggle();
                    var _me = this;
                    var id = $(_me).attr("data-id")
                    $.fn.winwinAjax.buttonActionSendAjax(_me,"'.route('subordinateAgentScatterReports.details',null).'/"+ id,{},function(content){
                        $.fn.overlayToggle();
                        $("#userInfoEditModal").html(content);
                        $("#userInfoEditModal").modal("show");
                    },function(){

                    },"GET",{dataType:"html"})
                })

                }']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['data' => 'id'],
            '结算期' => ['data' => 'agent_settle.settle_periods.periods','defaultContent' => '2017-04','orderable' => false],
            '子代理账号' => ['data'=> 'username'],
            '代理报表' => ['data' => '','defaultContent' => '查看详情','orderable' => false,'render' => 'function(){
                      return "<a class=\"text-primary details_report\" data-id=\""+ (this.id ? this.id : null) +"\" style=\"cursor: pointer\">查看详情</a>"
            }'],
            '代理佣金' => ['data' => 'agent_settle.this_period_commission','defaultContent' => '0.00','orderable' => false],
            '提成金额' => ['data' => '','defaultContent' => '0.00','orderable' => false],
            '审核状态' => ['data' => '','defaultContent' => '','orderable' => false,'render' => 'function(){
                    return this.status == 3 ? \'已经结算\' : \'未结算\'
                }'],
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
