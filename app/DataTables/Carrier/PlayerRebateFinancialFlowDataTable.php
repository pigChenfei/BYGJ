<?php

namespace App\DataTables\Carrier;

use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Player;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerRebateFinancialFlowDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {

        $dataTables = $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.player_rebate_financial_flows.datatables_actions')
            ->addColumn('selectCheckbox', function(PlayerRebateFinancialFlowNew $log){
                return $log->is_already_settled ? '' : "<input type=\"checkbox\" value='".$log->id."' class=\"square-blue log_check_box\">";
            });

        $dataTables->addColumn('created_at_format',function(PlayerRebateFinancialFlowNew $flow){
            return $flow->created_at->format('Y-m-d');
        });
        if($date_time_range = $this->request()->get('date_time_range')){
            $time = explode(' - ',$date_time_range);
            if(count($time) == 2){
                $dataTables->where('created_at','>=',$time[0]);
                $dataTables->where('created_at', '<=', $time[1]);
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
        $playerRebateFinancialFlows = PlayerRebateFinancialFlowNew::orderBy('is_already_settled','asc')
            ->orderBy('updated_at', 'desc')
            ->where('is_effect', 0)
            ->where('rebate_type', 1)
            ->with(['player.playerLevel','player.agent','gamePlat']);

        return $this->applyScopes($playerRebateFinancialFlows);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addColumn(['width' => '50px','title' => '<input style="display: none" type="checkbox" class="square-blue selectCheckbox">','searchable' => false,'data' => 'selectCheckbox'])
            ->columns($this->getColumns())
            ->addAction(['width' => '135px','title' => '操作'])
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
                        $(".user_edit").on("click",function(){
                            $.fn.overlayToggle();
                            var _me = this;
                            var user_id = $(_me).attr("data-id")
                            $.fn.winwinAjax.buttonActionSendAjax(_me,"'.route('players.showPlayerInfoEditModal',null).'/"+ user_id,{},function(content){
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
                    }']);

    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '创建时间' => ['name' => 'created_at','data' => 'created_at_format','searchable' => false],
            '账号' => ['name' => 'player.user_name', 'data' => 'player.user_name','searchable' => true,'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.player.player_id +"\" style=\"cursor: pointer\">" + this.player.user_name + "</a>"
            }'],
            '会员等级' => ['name' => 'player.player_level','data' => 'player.player_level.level_name','searchable' => false],
            '所属代理' => ['data' => 'player.agent.username','searchable' => false,'render' => 'function(){
                if(this.player.agent){
                   return this.player.agent.is_default ? "系统用户" : this.player.agent.realname;
                }
                return null;
            }'],
            '游戏平台' => ['name' => 'game_plat', 'data' => 'game_plat.game_plat_name','searchable' => false],
            '有效投注' => ['name' => 'bet_flow_amount', 'data' => 'bet_flow_amount','searchable' => false],
            '公司派彩额' => ['name' => 'company_pay_out_amount', 'data' => 'company_pay_out_amount','searchable' => false],
            '洗码金额' => ['name' => 'rebate_financial_flow_amount', 'data' => 'rebate_financial_flow_amount','searchable' => false],
            '是否返水' => ['name' => 'settled_type', 'data' => 'settled_type','render' => 'function(){
                return this.settled_type == 1 ? "是" : "否";
            }','searchable' => false],
            '是否已结算' => ['name' => 'is_already_settled', 'data' => 'is_already_settled','render' => 'function(){
                return this.is_already_settled ? "是" : "否";
            }','searchable' => false],
            '结算时间' => ['name' => 'settled_at','data' => 'settled_at','searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'playerRebateFinancialFlows';
    }
}
