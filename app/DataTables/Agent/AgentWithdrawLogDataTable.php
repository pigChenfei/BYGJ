<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use Carbon\Carbon;
use App\Models\Log\AgentWithdrawLog;

class AgentWithdrawLogDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables
            ->eloquent($this->query())
            ->addColumn('status_type',function(AgentWithdrawLog $log){
                $status_type = AgentWithdrawLog::statusMeta()[$log->status];
                if($log->status == AgentWithdrawLog::STATUS_WAITING_REVIEWED){
                    return '<span style="color: #FF00FF">'.$status_type.'</span>';
                }
                else if ($log->status == AgentWithdrawLog::STATUS_REFUSED){
                    return '<span style="color: red">'.$status_type.'</span>';
                }
                else if ($log->status == AgentWithdrawLog::STATUS_PAYED_OUT){
                    return '<span style="color: #DDDDDD">'.$status_type.'</span>';
                }
                return $status_type;
            });
            if($range_time = $this->request()->get('date_time_range')){
                $time = explode(' - ',$this->request()->get('date_time_range'));
                if(count($time) == 2){
                    $dataTables->where('updated_at','>=',$time[0]);
                    $dataTables->where('updated_at', '<=', $time[1]);
                }
            }else{
                $dataTables->where('created_at','>=',date('Y-m-01', time()));
                $dataTables->where('created_at', '<=', date('Y-m-d H:i:s'));
            }
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *log_player_withdraw
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $agentWithdrawLog = AgentWithdrawLog::select("*")->where(['agent_id'=>\WinwinAuth::agentUser()->id]);
        return $this->applyScopes($agentWithdrawLog);
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
            '序号' => ['name' => 'id', 'data' => 'id','defaultContent' => ''],
            '取款编号' => ['name' => 'order_number','data' => 'order_number','defaultContent' => ''],
            '取款时间' => ['name' => 'created_at', 'data' => 'created_at','defaultContent' => ''],
            '取款金额' => ['name' => 'apply_amount', 'data' => 'apply_amount','defaultContent' => '0.00'],
            '手续费' => ['name' => 'fee_amount', 'data' => 'fee_amount','defaultContent' => '0.00'],
            '实际出款金额' => ['name' => 'finally_withdraw_amount', 'data' => 'finally_withdraw_amount','defaultContent' => '0.00'],
            '处理时间' => ['name' => 'reviewed_at', 'data' => 'reviewed_at','defaultContent' => ''],
            '备注' => ['name' => 'reviewed_at', 'data' => 'reviewed_at','defaultContent' => ''],
            '状态' => ['name' => 'status_type', 'data' => 'status_type','defaultContent' => ''],
            
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
