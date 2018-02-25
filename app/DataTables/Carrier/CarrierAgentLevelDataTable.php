<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierAgentLevel;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentLevelDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_levels.datatables_actions')
            ->addColumn('type_name', function (CarrierAgentLevel $agentlevel) {
            return CarrierAgentLevel::typeMeta()[$agentlevel->type];
        })->addColumn('is_multi_agent', function (CarrierAgentLevel $agentlevel) {
            if (!\WinwinAuth::currentWebCarrier()->is_multi_agent){
                return '否';
            }
            return $agentlevel->is_multi_agent?'是':'否';
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
        // $carrierAgentLevels = CarrierAgentLevel::with('defaultPlayerLevel')->select('*')->orderBy('id', 'desc');
        $carrierAgentLevels = CarrierAgentLevel::with('defaultPlayerLevel')->select([
            'default_player_level',
            'type',
            'sort',
            'level_name',
            'is_default',
            'is_multi_agent',
            'is_running',
            'id'
        ])->where([
            'carrier_id' => \Auth::user()->carrier_id
        ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierAgentLevels);
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
        // ->addColumn(['title' => 'intro','data' => null])
        addAction([
            'width' => 'auto',
            'title' => '操作'
        ])
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
            'buttons' => [],
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
                            data.is_running  =  this.checked ? 1 : 0;
                            var serverUrl = \'' . route('carrierAgentLevels.saveStatus', null) . '\/\' + $(this).attr(\'data-id\')
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
                'name' => 'id',
                'render' => 'function(){ return null}',
                'style' => 'text-align:center;width:40px',
                'searchable' => false
            ],
            '代理类型' => [
                'name' => 'type',
                'data' => 'type_name',
                'orderable' => false,
                'defaultContent' => ''
            ],
            '代理名称' => [
                'name' => 'level_name',
                'data' => 'level_name',
                'orderable' => false
            ],
            '是否支持多级代理' => [
                'name' => 'is_multi_agent',
                'data' => 'is_multi_agent',
                'orderable' => false
            ],
            '代理下属默认会员等级' => [
                'name' => 'level_name',
                'data' => 'default_player_level.level_name',
                'defaultContent' => '',
                'orderable' => false
            ],
            // '是否是默认代理类型' => [
            // 'name' => 'is_default',
            // 'data' => 'is_default',
            // 'orderable' => false,
            // 'render' => 'function(){
            // return this.is_default == true ? \'是\' : \'否\'
            // }'],
            '状态' => [
                'name' => 'is_running',
                'data' => 'is_running',
                'render' => 'function(){
                    return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.is_running ? "checked=checked" : "") +">";
                    return this.is_running == true ? \'<button class="btn btn-xs btn-success">正常</button>\' : \'<button class="btn btn-xs btn-danger">已关闭</button>\'
                }'
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
        return 'carrierAgentLevels';
    }
}