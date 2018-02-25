<?php
namespace App\DataTables\Carrier;

use App\Models\Def\GamePlat;
use App\Models\Def\MainGamePlat;
use App\Models\Log\PlayerAccountLog;
use App\Models\PlayerGameAccount;
use Form;
use Illuminate\Database\Eloquent\Builder;
use Yajra\Datatables\Services\DataTable;

class PlayerAccountLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query())
            ->addColumn('fund_type_name',
            function ($model) {
                return PlayerAccountLog::fundTypeMeta()[$model->fund_type];
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
        
        if ($gamePlat = $this->request()->get('gamePlat')) {
            $gamePlat = GamePlat::where('game_plat_id', $this->request()->get('gamePlat'))->with('mainGamePlat')->first();
            if (! $gamePlat) {
                throw new \InvalidArgumentException('无法找到该游戏平台');
            }
            $dataTables->where('main_game_plat_id', $gamePlat->main_game_plat_id);
        }
        
        if ($logType = $this->request()->get('logType')) {
            $dataTables->where('fund_type', $logType);
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
        $playerAccountLogs = PlayerAccountLog::with(
            [
                'player',
                'serviceUser'
            ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($playerAccountLogs);
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
        // ->addAction(['width' => '10%','title' => '操作'])
        ajax([
            'data' => \Config::get('datatables.ajax.data')
        ])
            ->parameters(
            [
                'ordering' => false,
                'paging' => true,
                'searching' => false,
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
            '金额' => [
                'name' => 'amount',
                'data' => 'amount',
                'searchable' => false
            ],
            '类型' => [
                'name' => 'fund_type',
                'data' => 'fund_type_name'
            ],
            '来源' => [
                'name' => 'fund_source',
                'data' => 'fund_source'
            ],
            '明细' => [
                'name' => 'remark',
                'data' => 'remark',
                'render' => 'function(){
                  return this.remark ? "<a class=\"text-primary\" style=\"cursor: pointer\" data-toggle=\"tooltip\" data-original-title=\"" + this.remark + "\" class=\"fa fa-question-circle\">查看</a>" : "无"
            }'
            ],
            '时间' => [
                'name' => 'created_at',
                'data' => 'created_at'
            ],
            '操作人' => [
                'name' => 'operator_reviewer_id',
                'render' => 'function(){
                return this.service_user ? this.service_user.username : null
            }',
                'data' => 'service_user.username',
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
        return 'playerAccountLogs';
    }
}
