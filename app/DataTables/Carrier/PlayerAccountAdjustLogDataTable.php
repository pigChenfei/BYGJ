<?php
namespace App\DataTables\Carrier;

use App\Models\Log\PlayerAccountAdjustLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogGamePlat;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerAccountAdjustLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables->eloquent($this->query())
            ->
        // ->addColumn('action', 'carrier.player_account_adjust_logs.datatables_actions')
        addColumn('adjust_type_string',
            function (PlayerAccountAdjustLog $log) {
                return PlayerAccountAdjustLog::adjustTypeMeta()[$log->adjust_type];
            });
        
        if ($adjust_type_value = $this->request()->get('adjust_type_value')) {
            $DataTables->where('adjust_type', $adjust_type_value);
        }
        
        if ($range_time = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $this->request()->get('date_time_range'));
            if (count($time) == 2) {
                $DataTables->where('updated_at', '>=', $time[0]);
                $DataTables->where('updated_at', '<=', $time[1]);
            }
        } else {
            $DataTables->where('created_at', '>=', date('Y-m-01', time()));
            $DataTables->where('created_at', '<=', date('Y-m-d H:i:s'));
        }
        return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $playerAccountAdjustLogs = PlayerAccountAdjustLog::with(
            [
                'player.playerLevel',
                'player.agent',
                'operatorUser'
            ])->orderBy('id', 'desc');
        return $this->applyScopes($playerAccountAdjustLogs);
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
            ->
        // ->addAction(['width' => '110px','title' => '操作'])
        ajax([
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
                'defaultContent' => '',
                'searchable' => false
            ],
            '账号' => [
                'name' => 'player.user_name',
                'data' => 'player.user_name',
                'searchable' => true,
                'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.player.player_id +"\" style=\"cursor: pointer\">" + this.player.user_name + "</a>"
            }'
            ],
            '会员等级' => [
                'name' => 'player.player_level',
                'data' => 'player.player_level.level_name',
                'searchable' => false,
                'defaultContent' => ''
            ],
            '所属代理' => [
                'name' => 'player.agent',
                'data' => 'player.agent.realname',
                'defaultContent' => '',
                'searchable' => false,
                'render' => 'function(){
                if(this.player.agent){
                   return this.player.agent.is_default ? "系统用户" : this.player.agent.realname;
                }
                return null;
            }'
            ],
            '调整类型' => [
                'name' => 'adjust_type',
                'data' => 'adjust_type_string',
                'searchable' => false,
                'defaultContent' => ''
            ],
            '金额' => [
                'name' => 'amount',
                'data' => 'amount',
                'searchable' => false
            ],
            '调整时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'searchable' => false
            ],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark',
                'searchable' => false
            ],
            '操作人' => [
                'name' => 'operatorUser.username',
                'data' => 'operator_user.username',
                'defaultContent' => '',
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
        return 'playerAccountAdjustLogs';
    }
}
