<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\PlayerDepositPayLog;
use Carbon\Carbon;

class AgentPlayerDepositLogDataTable extends DataTable
{

    
    private $plyaer_id = null;
    private $plyaer = null;
    public function playerId($player_id,$plyaer)
    {
        $this->plyaer_id = $player_id;
        $this->plyaer = $plyaer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables
            ->eloquent($this->query());
            $dataTables->where(['player_id'=>$this->plyaer_id])
            ->addColumn('status_type',function(PlayerDepositPayLog $log){
                $status_type = PlayerDepositPayLog::orderStatusMeta()[$log->status];
                if($log->status == PlayerDepositPayLog::ORDER_STATUS_CREATED){
                    return '<span style="color: #FF00FF">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED){
                    return '<span style="color: #red">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerDepositPayLog::ORDER_STATUS_PAY_FAILED){
                    return '<span style="color: #DDDDDD">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerDepositPayLog::ORDER_STATUS_WAITING_REVIEW){
                    return '<span style="color: #FFB7DD">'.$status_type.'</span>';
                }
                else if ($log->status == PlayerDepositPayLog::ORDER_STATUS_SERVER_REVIEW_NO_PASSED){
                    return $status_type;
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
//            $dataTables['player'] = $this->plyaer;
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $playerDepositPayLog = PlayerDepositPayLog::select("*")->with('payChannelList.payChannelType');
        return $this->applyScopes($playerDepositPayLog);
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
            '存款编号' => ['name' => 'pay_order_number','data' => 'pay_order_number','defaultContent' => ''],
            '存款时间' => ['name' => 'created_at', 'data' => 'created_at','defaultContent' => ''],
            '存款类型' => ['name' => 'id', 'data' => 'pay_channel_list.pay_channel_type.type_name','defaultContent' => '0.00'],
            '存款金额' => ['name' => 'amount', 'data' => 'amount','defaultContent' => '0.00'],
            '手续费' => ['name' => 'fee_amount', 'data' => 'fee_amount','defaultContent' => '0.00'],
            '存款优惠' => ['name' => 'benefit_amount', 'data' => 'benefit_amount','defaultContent' => '0.00'],
            '实际到账' => ['name' => 'finally_amount', 'data' => 'finally_amount','defaultContent' => '0.00'],
            '状态' => ['name' => 'id', 'data' => 'status_type','defaultContent' => ''],
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
