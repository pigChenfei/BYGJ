<?php

namespace App\DataTables\Agent;

use App\Models\Log\PlayerBetFlowLog;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Player;
use Carbon\Carbon;

class AgentPlayerBetLogDataTable extends DataTable
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
        $dataTables->where(['player_id'=>$this->plyaer_id]);  
        $dataTables->addColumn('game_name',function(PlayerBetFlowLog $log){
             $name = $log->game->game_name;
             if($log->player_or_banker != PlayerBetFlowLog::BET_FLOW_NONE){
                 $name .= "\n(".PlayerBetFlowLog::betFlowBankerPlayerMeta()[$log->player_or_banker].")";
             }
             return $name;
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
        $agentPlayer = PlayerBetFlowLog::with(['game.gamePlat'])->select("*");
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
            '注单号' => ['name' => 'game_flow_code','data' => 'game_flow_code','defaultContent' => ''],
            '游戏平台' => ['name' => 'game_plat_id', 'data' => 'game.game_plat.game_plat_name','defaultContent' => ''],
            '投注内容' => ['name' => 'game_id', 'data' => 'game_name','defaultContent' => ''],
            '下注金额' => ['name' => 'bet_amount', 'data' => 'bet_amount','defaultContent' => '0.00'],
            '派彩金额' => ['name' => 'company_payout_amount', 'data' => 'company_payout_amount','defaultContent' => '0.00'],
            '有效投注' => ['name' => 'available_bet_amount', 'data' => 'available_bet_amount','defaultContent' => ''],
            '公司输赢' => ['name' => 'company_win_amount', 'data' => 'company_win_amount','defaultContent' => '0.00','render' => 'function(){
                return "<span style=\"color: "+ (this.bet_flow_available == false ? "black" : (this.company_win_amount < 0 ? "red" : "green")) +"\">"+this.company_win_amount+"<span>"
            }'],
            '下注时间' => ['name' => 'created_at', 'data' => 'created_at'],
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
