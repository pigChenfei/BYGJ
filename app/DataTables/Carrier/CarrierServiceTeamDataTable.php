<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierServiceTeam;
use Form;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Services\DataTable;

class CarrierServiceTeamDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_service_teams.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierServiceTeams = CarrierServiceTeam::noAdministrator()->orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierServiceTeams);
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
//           ->addColumn(['title' => 'intro','data' => null])
            ->addAction(['width' => '280px','title' => '操作'])
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
                    var dataLists = api.data();
            　　    var startIndex= api.context[0]._iDisplayStart;
                　　api.column(0).nodes().each(function(cell, i) {
                　　　　cell.innerHTML = startIndex + i + 1;
                　　});
                    $("[name=\'winwin-switch\']").bootstrapSwitch({
                        size:\'mini\',
                        onText:\'正常\',
                        offText:\'关闭\',
                        on:\'success\',
                        off:\'warning\',
                        onSwitchChange:function(){
                            var tr = $(this).parents(\'tr\');
                            var data = dataLists[api.row(tr).index()]
                            data._method = \'PATCH\';
                            delete data.action;
                            data.status  =  this.checked ? 1 : 0;
                            var serverUrl = \''.route('carrierServiceTeams.update',null).'\/\' + $(this).attr(\'data-id\')
                            $.fn.showOverlayLoading();
                            var _me = this;
                            $.ajax({
                                url:serverUrl,
                                data:data,
                                type:\'POST\',
                                success:function(e){
                                    if(e.success != true){
                                        toastr.error(e.message || \'更新失败\', \'出错啦!\')
                                    }
                                    $.fn.hideOverlayLoading();
                                },
                                error:function(xhr){
                                    $.fn.hideOverlayLoading();
                                    toastr.error(xhr.responseJSON.message || \'更新失败\', \'出错啦!\');
                                }
                            })
                        }
                    });
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
            '序号' => ['name' => 'id', 'data' => 'id','orderable' => false],
            '部门名称' => ['name' => 'team_name', 'data' => 'team_name','orderable' => false],
            '备注' => ['name' => 'remark', 'data' => 'remark','orderable' => false],
            '状态' => [
                'name' => 'status',
                'data' => 'status',
                'orderable' => false,
                'render' => 'function(){
                 return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                }'],
            '最后修改' => ['name' => 'updated_at', 'data' => 'updated_at','orderable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierServiceTeams';
    }
}
