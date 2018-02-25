<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierPlayerLevel;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierPlayerLevelDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_player_levels.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $carrierPlayerLevels = CarrierPlayerLevel::select(['sort','level_name','is_default','status','id','upgrade_rule'])->bySort('desc');
        return $this->applyScopes($carrierPlayerLevels);
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
                'paging' => false,
                'searching' => false,
                'ordering' => false,
                'info' => false,
                'dom' => 'Bfrt',
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
                            var serverUrl = \''.route('carrierPlayerLevels.update',null).'\/\' + $(this).attr(\'data-id\')
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
            '序号' => [
                'name' => 'sort',
                'data' => 'sort',
                'orderable' => true,
            ],
            '等级名称' => [
                'name' => 'level_name',
                'data' => 'level_name',
                'orderable' => false,
            ],
            '是否是默认等级' => [
                'name' => 'is_default',
                'data' => 'is_default',
                'orderable' => false,
                'render' => 'function(){
                    return this.is_default == true ? \'是\' : \'否\'
                }'],
            '状态' => [
                'name' => 'status',
                'data' => 'status',
                'orderable' => false,
                'render' => 'function(){
                 return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">正常</button>\' : \'<button class="btn btn-xs btn-danger">已关闭</button>\'
                }'],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierPlayerLevels';
    }
}
