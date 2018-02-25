<?php
namespace App\DataTables\Carrier;

use App\Models\Log\PlayerBetFlowLog;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerBetFlowLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.player_bet_flow_logs.datatables_actions');
        $dataTables->addColumn('game_name', function (PlayerBetFlowLog $log) {
            if ($log->game_id == 0 && ! empty($log->game_type) && is_numeric($log->game_type)) {
                if (is_null($log->gameByType())) {
                    return '';
                } else {
                    return $log->gameByType->game_name;
                }
            } elseif ($log->game_id == 0 && count($log->game) <= 0) {
                if (count($log->gamePlat()) <= 0) {
                    return '';
                } else {
                    return $log->gamePlat->game_plat_name;
                }
            } else {
                if (is_null($log->game)) {
                    return '';
                }
                $name = $log->game->game_name;
                if ($log->player_or_banker != PlayerBetFlowLog::BET_FLOW_NONE) {
                    $name .= "\n(" . PlayerBetFlowLog::betFlowBankerPlayerMeta()[$log->player_or_banker] . ")";
                }
                return $name;
            }
        })
            ->addColumn('game_plat_name', function (PlayerBetFlowLog $log) {
            if (count($log->gamePlat()) <= 0) {
                return '';
            } else {
                return $log->gamePlat->game_plat_name;
            }
        });
        if ($date_time_range = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $date_time_range);
            if (count($time) == 2) {
                $dataTables->where('created_at', '>=', $time[0]);
                $dataTables->where('created_at', '<=', $time[1]);
            }
        } else {
            $dataTables->where('created_at', '>=', date('Y-m-01 00:00:00', time()));
            $dataTables->where('created_at', '<=', date('Y-m-d H:i:s', time()));
        }
        $game_plat_id = $this->request()->get('game_plat_value');
        if (! empty($game_plat_id)) {
            $dataTables->where('game_plat_id', $game_plat_id);
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
        $playerBetFlowLogs = PlayerBetFlowLog::with([
            'player',
            'game.gamePlat'
        ])->orderBy('updated_at', 'desc');
        
        return $this->applyScopes($playerBetFlowLogs);
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
            ->addAction([
            'width' => '135px',
            'title' => '操作'
        ])
            ->
        // ->addAction(['width' => '10%'])
        ajax([
            'data' => \Config::get('datatables.ajax.data')
        ])
            ->parameters([
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
                    $(".user_edit").on("click",function(){
                        $.fn.overlayToggle();
                        var _me = this;
                        var user_id = $(_me).attr("data-id")
                        $.fn.winwinAjax.buttonActionSendAjax(_me,"' . route('players.showPlayerInfoEditModal', null) . '/"+ user_id,{},function(content){
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
                'name' => 'id',
                'render' => 'function(){ return null}',
                'style' => 'text-align:center;width:40px',
                'searchable' => false
            ],
            '账号' => [
                'name' => 'player.user_name',
                'data' => 'player.user_name',
                'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.player.player_id +"\" style=\"cursor: pointer\">" + this.player.user_name + "</a>"
            }',
                'searchable' => true
            ],
            '注单号' => [
                'name' => 'game_flow_code',
                'data' => 'game_flow_code',
                'searchable' => false
            ],
            '游戏平台' => [
                'name' => 'game_plat_id',
                'data' => 'game_plat_name',
                'searchable' => false
            ],
            '游戏名称' => [
                'name' => 'game_id',
                'data' => 'game_name',
                'searchable' => false
            ],
            '投注时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'searchable' => false
            ],
            '投注金额' => [
                'name' => 'bet_amount',
                'data' => 'bet_amount',
                'render' => 'function(){
                return "<span style=\"color: "+ (this.bet_flow_available == false ? "black" : (this.company_win_amount < 0 ? "red" : "green")) +"\">"+this.bet_amount+"<span>"
            }',
                'searchable' => false
            ],
            '派彩金额' => [
                'name' => 'company_payout_amount',
                'data' => 'company_payout_amount',
                'render' => 'function(){
                return "<span style=\"color: "+ (this.bet_flow_available == false ? "black" : (this.company_win_amount < 0 ? "red" : "green")) +"\">"+this.company_payout_amount+"<span>"
            }',
                'searchable' => false
            ],
            '公司输赢' => [
                'name' => 'company_win_amount',
                'data' => 'company_win_amount',
                'render' => 'function(){
                return "<span style=\"color: "+ (this.bet_flow_available == false ? "black" : (this.company_win_amount < 0 ? "red" : "green")) +"\">"+this.company_win_amount+"<span>"
            }',
                'searchable' => false
            ],
            '有效投注' => [
                'name' => 'available_bet_amount',
                'data' => 'available_bet_amount',
                'render' => 'function(){
                return "<span style=\"color: "+ (this.bet_flow_available == false ? "black" : (this.company_win_amount < 0 ? "red" : "green")) +"\">"+this.available_bet_amount+"<span>"
            }',
                'searchable' => false
            ],
            '结算状态' => [
                'name' => 'game_status',
                'data' => 'game_status',
                'render' => 'function(){
                return this.game_status ? "已结算" : "未结算";
            }',
                'searchable' => false
            ],
            '是否有效' => [
                'name' => 'bet_flow_available',
                'data' => 'bet_flow_available',
                'render' => 'function(){
                return this.bet_flow_available ? "是" : "否";
            }',
                'searchable' => false
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
        return 'playerBetFlowLogs';
    }
}
