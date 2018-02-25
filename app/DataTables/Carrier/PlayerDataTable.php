<?php
namespace App\DataTables\Carrier;

use App\Models\Log\PlayerBetFlowLog;
use App\Models\Player;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.players.datatables_actions')
            ->addColumn('user_status_name', 
            function (Player $player) {
                $user_status_name = Player::userStatusMeta()[$player->user_status];
                if ($player->user_status == Player::USER_STATUS_CLOSED) {
                    return '<span style="color: red">' . $user_status_name . '</span>';
                } else if ($player->user_status == Player::USER_STATUS_LOCKED) {
                    return '<span style="color: #f39c12">' . $user_status_name . '</span>';
                }
                return $user_status_name;
            })
            ->addColumn('is_online_name', 
            function (Player $player) {
                $online_name = Player::onlineMeta()[$player->is_online];
                if ($player->is_online == Player::ONLINE_ON) {
                    return '<span style="color: green">' . $online_name . '</span>';
                }
                return $online_name;
            })
            ->addColumn('total_win_loss',
            function (Player $player) {
                return $player->betFlowLogs->map(function(PlayerBetFlowLog $log){
                    return $log->company_win_amount;
                })->reduce(function($pre,$next){ return $pre + $next; });
            })
            ->setRowAttr([
            'style' => 'text-align:center'
        ]);
        $userStatus = $this->request()->get('user_status_value');
        if (! is_null($userStatus) && $userStatus != '') {
            $dataTables->where('user_status', $userStatus);
        }
        $isOnline = $this->request()->get('is_online_value');
        if (! is_null($isOnline) && $isOnline != '') {
            $dataTables->where('is_online', $isOnline);
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
        $players = Player::select(
            [
                'player_id',
                'user_name',
                'mobile',
                'real_name',
                'main_account_amount',
                'agent_id',
                'player_level_id',
                'is_online',
                'user_status',
                'total_win_loss',
                'remark',
                'login_at',
                'login_domain',
                'created_at',
                'login_ip',
                'recommend_player_id'
            ])->with([
            'agent',
            'playerLevel',
            'betFlowLogs'=>function($query){
                $query->betFlowAvailable();
            },
            'invitedPlayer'
        ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($players);
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
            'width' => '110px',
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

    private function columnEditRender($columnName)
    {
        $columnEditScript = "$.fn.overlayToggle();  $(\\\"#editAddModal\\\").load(\\\"" .
             route('players.showPlayerMainAccountAmountSettingModal') .
             "\\\",{ player_id : player_id },function(){}).modal(\\\"show\\\")";
        return 'function() {var player_id = this.player_id; ";}';
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
                'name' => 'user_name',
                'render' => 'function(){ return null}',
                'style' => 'text-align:center;width:40px'
            ],
            '账号' => [
                'name' => 'user_name',
                'data' => 'user_name',
                'render' => 'function(){
                return ("<a class=\"text-primary user_edit\" data-id=\""+ this.player_id +"\" style=\"cursor: pointer\">" + (this.is_online ?  ("<i class=\"fa fa-circle\" style=\"color: #219c22\"></i>&nbsp;&nbsp;" + this.user_name) : ("<i class=\"fa fa-circle\" style=\"color: lightgray\"></i>&nbsp;&nbsp;" + this.user_name))  + "</a>") 
            }',
                'style' => 'text-align:center'
            ],
            '姓名' => [
                'name' => 'real_name',
                'data' => 'real_name',
                'style' => 'text-align:center'
            ],
            '会员等级' => [
                'name' => 'player_level_id',
                'data' => 'player_level.level_name',
                'defaultContent' => '',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '邀请人' => [
                'name' => 'recommend_player_id',
                'data' => 'invited_player',
                'render' => 'function(){
                if(this.invited_player){
                    return this.invited_player.real_name;
                }
                return null;
            }',
                'style' => 'text-align:center'
            ],
            '所属代理' => [
                'name' => 'agent.realname',
                'data' => 'agent.realname',
                'render' => 'function(){
                if(this.agent){
                   return this.agent.is_default ? "系统用户" : this.agent.realname;
                }
                return null;
            }',
                'style' => 'text-align:center'
            ],
            '主账户余额' => [
                'name' => 'main_account_amount',
                'data' => 'main_account_amount',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '公司总输赢' => [
                'name' => 'total_win_loss',
                'data' => 'total_win_loss',
                'render' => 'function(){ 
                return this.total_win_loss >= 0 ? Number(this.total_win_loss).toFixed(2) : ("<span style=\"color: red\">" + Number(this.total_win_loss).toFixed(2) +"</span>")
            }',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            // '是否在线' => ['name' => 'is_online', 'data' => 'is_online_name','style' => 'display:hidden'],
            '最后登录时间' => [
                'name' => 'login_at',
                'data' => 'login_at',
                'style' => 'text-align:center;width:126px',
                'searchable' => false
            ],
            // '最后登录IP' => ['name' => 'login_ip', 'data' => 'login_ip', 'style' => 'text-align:center'],
            '访问网址' => [
                'name' => 'login_domain',
                'data' => 'login_domain',
                'style' => 'text-align:center;width:145px',
                'searchable' => false
            ],
            // '注册时间' => ['name' => 'created_at', 'data' => 'created_at','style' => 'text-align:center'],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '状态' => [
                'name' => 'user_status',
                'data' => 'user_status_name',
                'style' => 'text-align:center',
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
        return 'players';
    }
}
