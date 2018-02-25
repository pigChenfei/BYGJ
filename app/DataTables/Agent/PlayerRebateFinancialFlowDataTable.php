<?php

namespace App\DataTables\Agent;

use App\Models\Log\PlayerRebateFinancialFlow;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Player;
use Carbon\Carbon;

class PlayerRebateFinancialFlowDataTable extends DataTable
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
        ->addColumn('selectCheckbox', function(PlayerRebateFinancialFlow $log){
                return $log->is_already_settled ? '' : "<input type=\"checkbox\" value='".$log->id."' class=\"square-blue log_check_box\">";
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
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $agentPlayer = PlayerRebateFinancialFlow::select("*");
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
        return [
            '序号' => ['name' => 'player_id', 'data' => 'player_id','defaultContent' => ''],
            '申请时间' => ['name' => 'created_at','data' => 'created_at','defaultContent' => ''],
            '有效投注' => ['name' => 'bet_flow_amount', 'data' => 'bet_flow_amount','defaultContent' => '0.00'],
            '洗码金额' => ['name' => 'rebate_financial_flow_amount', 'data' => 'rebate_financial_flow_amount','defaultContent' => '0.00'],
            '状态' => ['name' => 'is_already_settled', 'data' => 'is_already_settled','render' => 'function(){
                return this.is_already_settled ? "是" : "否";
            }','searchable' => false],
            '处理时间' => ['name' => 'settled_at', 'data' => 'settled_at','defaultContent' => ''],
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
