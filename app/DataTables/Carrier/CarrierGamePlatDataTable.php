<?php

namespace App\DataTables\Carrier;

use App\Models\Map\CarrierGamePlat;
use Form;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Services\DataTable;

class CarrierGamePlatDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_game_plats.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $carrierGamePlats = CarrierGamePlat::orderBy('updated_at', 'desc');

        dd($carrierGamePlats);

        return $this->applyScopes($carrierGamePlats);
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
            ->ajax([
                'data' => 'function(data){
                    var formData = $(\'#searchForm\').serializeJson();
                    for(index in data.columns){
                        for(dataName in formData){
                             if(data.columns[index].name == dataName){
                                data.columns[index].search.value = formData[dataName];
                             }
                        }
                    }
                }'
            ])
            ->parameters([
                'paging' => true,
                'searching' => false,
                'info' => true,
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [

                ],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                    var api = this.api();
                    var dataLists = api.data();
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
                            data.status  =  this.checked ? 1 : 0;
                            var serverUrl = \''.route('carrierGamePlats.update',null).'\/\' + $(this).attr(\'data-id\')
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
            '运营商ID' => ['name' => 'carrier_id', 'data' => 'carrier_id'],
            '游戏平台ID' => ['name' => 'game_plat_id', 'data' => 'game_plat_id'],
            '前台显示名称' => ['name' => 'display_plat_name', 'data' => 'display_plat_name'],
            '状态' => [
                'name' => 'status',
                'data' => 'status',
                'render' => 'function(){
                 return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">正常</button>\' : \'<button class="btn btn-xs btn-danger">已关闭</button>\'
                }'],
            '排序' => ['name' => 'sort', 'data' => 'sort']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierGamePlats';
    }
}
