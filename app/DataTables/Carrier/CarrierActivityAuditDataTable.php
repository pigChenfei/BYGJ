<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierActivityAudit;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierActivityAuditDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_activity_audits.datatables_actions')
            ->addColumn('status_name',function(CarrierActivityAudit $activityAudit){
                return CarrierActivityAudit::statusMeta()[$activityAudit->status];
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierActivityAudit = CarrierActivityAudit::with(['player.agent','player.playerLevel','activity.actType'])->waitingAudit()->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierActivityAudit);
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
            ->addAction(['width' => '10%','title' => '操作'])    
            //->addAction(['width' => '10%'])
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
            '序号' => ['data' => 'id','searchable' => false],
            '账号' => ['name' => 'player.user_name','data' => 'player.user_name','defaultContent' => '','searchable' => true,'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.player.player_id +"\" style=\"cursor: pointer\">" + this.player.user_name + "</a>"
            }'],
            '会员等级' => ['data' => 'player.player_level.level_name','defaultContent' => '','orderable' => false,'searchable' => false],
            '所属代理' => ['data' => 'player.agent.username','render' => 'function(){
                if(this.player.agent){
                   return this.player.agent.is_default ? "系统用户" : this.player.agent.realname;
                }
                return null;
            }','orderable' => false,'searchable' => false],
            '申请活动名称' => ['data' => 'activity.name','defaultContent' => '','orderable' => false,'searchable' => false],
            '活动类型' => ['data' => 'activity.act_type.type_name','orderable' => false,'searchable' => false],
            '申请时间' => ['name' => 'created_at', 'data' => 'created_at','orderable' => false,'searchable' => false],
            '申请状态' => ['data' => 'status_name','orderable' => false,'searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierActivityTypes';
    }
}
