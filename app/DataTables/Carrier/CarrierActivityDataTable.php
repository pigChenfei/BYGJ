<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierActivity;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierActivityDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_activities.datatables_actions')
            ->addColumn('bonuses_type_name',function(CarrierActivity $activity){
                if($activity->bonuses_type != 0 || !empty($activity->bonuses_type))
                {
                    return \App\Models\CarrierActivity::bonusesTypeMeta()[$activity->bonuses_type];
                }else{
                    return null;
                }
            })
            ->addColumn('censor_way_name',function(CarrierActivity $activity){
                return \App\Models\CarrierActivity::censorWayMeta()[$activity->censor_way];
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
//        $carrierActivities = CarrierActivity::query();
        $carrierActivities = CarrierActivity::with('actType')->select("*")->where('carrier_id','=',\Auth::user()->carrier_id)->orderBy('sort', 'asc');
        return $this->applyScopes($carrierActivities);
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
                'searching' => false,
                'ordering' => false,
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
                            var serverUrl = \''.route('carrierActivities.saveStatus',null).'\/\' + $(this).attr(\'data-id\')
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
            '序号' => ['name' => 'id', 'render' => 'function(){ return null}','style' => 'text-align:center;width:40px','searchable' => false],
            '活动名称' => ['name' => 'name', 'data' => 'name','searchable' => false],
            '活动类型' => ['name' => 'type_name', 'data' => 'act_type.type_name','defaultContent' => '','orderable' => false],
            '红利类型' => ['name' => 'bonuses_type', 'data' => 'bonuses_type_name','defaultContent' => '','orderable' => false],
            '审查方式' => ['name' => 'censor_way', 'data' => 'censor_way_name','orderable' => false],
            '活动状态' => [
                'name' => 'status',
                'render' => 'function(){
                    return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">已上架</button>\' : \'<button class="btn btn-xs btn-danger">已下架</button>\'
                }','orderable' => false],
            '排序'=>['name'=>'sort','data'=>'sort','searchable'=>false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierActivities';
    }
}
