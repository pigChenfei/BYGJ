<?php

namespace App\DataTables\Agent;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\CarrierAgentSettleLog;
class AgentSettleReportDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Agent.agent_settle_report.datatables_actions')
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
        $carrierActivityAudit = CarrierAgentSettleLog::with(['settlePeriods'])->where('agent_id','=',\WinwinAuth::agentUser()->id);
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
                    $.fn.winwinAjax.buttonActionSendAjax(_me,"'.route('agentSettleReports.details',null).'/"+ id,{},function(content){
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
            '结算期' => ['data' => 'settle_periods.periods','defaultContent' => '','orderable' => false],
            '佣金报表' => ['data' => '','defaultContent' => '查看详情','orderable' => false,'render' => 'function(){
                      return "<a class=\"text-primary details_report\" data-id=\""+ (this.id ? this.id : null) +"\" style=\"cursor: pointer\">查看详情</a>"
            }'],
            '累计上期佣金' => ['data' => 'cumulative_last_month','defaultContent' => '0.00','orderable' => false],
            '手工调整' => ['data' => 'manual_tuneup','defaultContent' => '0.00','orderable' => false],
            '本期佣金' => ['data' => 'this_period_commission','defaultContent' => '0.00','orderable' => false],
            '实际发放' => ['data' => 'actual_payment','defaultContent' => '0.00','orderable' => false],
            '结转下月' => ['data' => 'transfer_next_month','defaultContent' => '0.00','orderable' => false],
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
