<?php
namespace App\DataTables\Carrier;

use App\Models\Log\PlayerAccountAdjustLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerWithdrawFlowLimitLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.player_withdraw_flow_limit_logs.datatables_actions')
            ->addColumn('selectCheckbox',
            function (PlayerWithdrawFlowLimitLog $log) {
                return "<input type=\"checkbox\" value='" . $log->id . "' class=\"square-blue log_check_box\">";
            })
            ->addColumn('limit_type_string',
            function (PlayerWithdrawFlowLimitLog $log) {
                if (array_key_exists($log->limit_type, PlayerWithdrawFlowLimitLog::limitTypeMeta())) {
                    return PlayerWithdrawFlowLimitLog::limitTypeMeta()[$log->limit_type];
                } else {
                    return '未知';
                }
            })
            ->addColumn('limit_game_plats_name',
            function (PlayerWithdrawFlowLimitLog $log) {
                if ($log->limitGamePlats->count() > 0) {
                    $game = $log->limitGamePlats->map(
                        function (PlayerWithdrawFlowLimitLogGamePlat $element) {
                            return $element->gamePlat->game_plat_name;
                        })
                        ->toArray();
                    return implode(',', $game);
                }
                return '不限平台';
            });
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $playerWithdrawFlowLimitLogs = PlayerWithdrawFlowLimitLog::with(
            [
                'player',
                'carrierActivity',
                'limitGamePlats.gamePlat'
            ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($playerWithdrawFlowLimitLogs);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addColumn(
            [
                'width' => '50px',
                'title' => '<input style="display: none" type="checkbox" class="square-blue selectCheckbox">',
                'data' => 'selectCheckbox',
                'searchable' => false
            ])
            ->columns($this->getColumns())
            ->addAction([
            'width' => '135px',
            'title' => '操作'
        ])
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
                        $(".user_edit").on("click",function(){
                            $.fn.overlayToggle();
                            var _me = this;
                            var user_id = $(_me).attr("data-id")
                            $.fn.winwinAjax.buttonActionSendAjax(_me,"' .
                     route('players.showPlayerInfoEditModal', null) . '/"+ user_id,{},function(content){
                                $.fn.overlayToggle();
                                $("#userInfoEditModal").html(content);
                                $("#userInfoEditModal").modal("show");
                            },function(){
                                
                            },"GET",{dataType:"html"})
                        })
                         $(\'input[type="checkbox"].square-blue, input[type="radio"].square-blue\').iCheck({
                            checkboxClass: \'icheckbox_square-blue\',
                            radioClass: \'iradio_square-blue\'
                        }).show();
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
            '账号' => [
                'name' => 'player.user_name',
                'data' => 'player.user_name',
                'searchable' => true,
                'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.player.player_id +"\" style=\"cursor: pointer\">" + this.player.user_name + "</a>"
            }'
            ],
            '限制来源' => [
                'name' => 'limit_type',
                'data' => 'limit_type_string',
                'defaultContent' => '',
                'searchable' => false
            ],
            '限游戏平台' => [
                'name',
                'id',
                'data' => 'limit_game_plats_name',
                'defaultContent' => '',
                'searchable' => false
            ],
            '流水限额' => [
                'name' => 'limit_amount',
                'data' => 'limit_amount',
                'defaultContent' => '',
                'searchable' => false
            ],
            '已完成流水' => [
                'name' => 'complete_limit_amount',
                'data' => 'complete_limit_amount',
                'defaultContent' => '',
                'searchable' => false
            ],
            '是否已完成' => [
                'name' => 'is_finished',
                'data' => 'is_finished',
                'render' => 'function(){
                return this.is_finished ? \'是\' : \'否\';
            }',
                'searchable' => false
            ],
            '关联活动' => [
                'name' => 'related_activity',
                'defaultContent' => '',
                'render' => 'function(){
                return this.carrier_activity ? this.carrier_activity.name : "";
            }',
                'searchable' => false
            ],
            '创建时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
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
        return 'playerWithdrawFlowLimitLogs';
    }
}
