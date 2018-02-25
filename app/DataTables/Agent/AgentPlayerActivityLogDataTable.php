<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use Carbon\Carbon;
use App\Models\Log\PlayerActivityLog;

class AgentPlayerActivityLogDataTable extends DataTable
{

    
    private $plyaer_id = null;


    public function playerId($player_id)
    {
        $this->plyaer_id = $player_id;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables
            ->eloquent($this->query());
            $dataTables->where(['player_id'=>$this->plyaer_id])
            ->addColumn('status_type',function(PlayerActivityLog $log){
                $status_type = PlayerActivityLog::statusMeta()[$log->status];
                if($log->status == PlayerActivityLog::STATUS_CHECK_AUDIT){
                    return '<span style="color: #FF00FF">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerActivityLog::STATUS_REFUSED){
                    return '<span style="color: #red">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerActivityLog::STATUS_REFUSE){
                    return '<span style="color: #DDDDDD">'.$status_type.'</span>';
                }
                return $status_type;
            })
            ->addColumn('handle_way',function(PlayerActivityLog $log){
                return PlayerActivityLog::handleWayMeta()[$log->handle_way];
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
        $playerWithdrawLog = PlayerActivityLog::select("*")->with('activity');
        return $this->applyScopes($playerWithdrawLog);
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
            '申请时间' => ['name' => 'created_at','data' => 'created_at','defaultContent' => ''],
            '活动名称' => ['data' => 'activity.name','defaultContent' => ''],
            '红利金额' => ['name' => 'amount', 'data' => 'amount','defaultContent' => '0.00'],
            '状态' => ['name' => 'status_type', 'data' => 'status_type','defaultContent' => ''],
            '处理方式' => ['name' => 'handle_way', 'data' => 'handle_way','defaultContent' => ''],
            '处理时间' => ['name' => 'handle_at', 'data' => 'handle_at','defaultContent' => ''],
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
