<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierActivityType;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierActivityTypeDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_activity_types.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierActivityTypes = CarrierActivityType::orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierActivityTypes);
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
                            var serverUrl = \''.route('carrierActivityTypes.saveStatus',null).'\/\' + $(this).attr(\'data-id\')
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
            '类型ID' => ['name' => 'id', 'data' => 'id'],
            '类型名称' => ['name' => 'type_name', 'data' => 'type_name','orderable' => false],
            '类型描述' => ['name' => 'desc', 'data' => 'desc','orderable' => false],
            '创建时间' => ['name' => 'created_at', 'data' => 'created_at','orderable' => false],
            '状态' => [
                'name' => 'status',
                'render' => 'function(){
                    return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">正常</button>\' : \'<button class="btn btn-xs btn-danger">已关闭</button>\'
                }','orderable' => false]
            
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
