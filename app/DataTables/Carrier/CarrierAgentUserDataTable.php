<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierAgentUser;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentUserDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_users.datatables_actions')
            ->addColumn('up_agent', function (CarrierAgentUser $agent) {
            if ($agent->parent_id == 0 || is_null($agent->parentAgent)) {
                return '系统代理';
            } else {
                if ($agent->parentAgent->is_default == 1) {
                    return '系统代理';
                }
                return $agent->parentAgent->realname ? $agent->parentAgent->realname : $agent->parentAgent->username;
            }
        })
            ->addColumn('type_name', function (CarrierAgentUser $agentlevel) {
            return \App\Models\CarrierAgentLevel::typeMeta()[$agentlevel['agentLevel']->type];
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
        // $carrierAgentUsers = CarrierAgentUser::with('getAgentLevel')->select("*")->where(['carrier_id' => \Auth::user()->carrier_id],['audit_status'=>1]);
        $carrierAgentUsers = CarrierAgentUser::with('agentLevel')->select("*")
            ->where('carrier_id', '=', \Auth::user()->carrier_id)
            ->where('audit_status', '=', 1)
            ->where('is_default', 0)
            ->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierAgentUsers);
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
            'width' => '280px',
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
                            data.status  =  this.checked ? 1 : 0;
                            var serverUrl = \'' . route('carrierAgentUsers.saveStatus', null) . '\/\' + $(this).attr(\'data-id\')
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
            '代理账号' => [
                'name' => 'username',
                'data' => 'username',
                'orderable' => false
            ],
            '姓名' => [
                'name' => 'realname',
                'data' => 'realname',
                'orderable' => false
            ],
            '代理类型' => [
                'name' => 'type',
                'data' => 'type_name',
                'defaultContent' => '',
                'orderable' => false
            ],
            '代理名称' => [
                'name' => 'level_name',
                'data' => 'agent_level.level_name',
                'defaultContent' => '',
                'orderable' => false
            ],
            '上级代理' => [
                'name' => 'up_agent',
                'data' => 'up_agent',
                'defaultContent' => '',
                'orderable' => false
            ],
            '邀请码' => [
                'name' => 'promotion_code',
                'data' => 'promotion_code',
                'orderable' => false
            ],
            '注册时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'orderable' => false
            ],
            '注册IP' => [
                'name' => 'register_ip',
                'data' => 'register_ip',
                'orderable' => false
            ],
            '客服备注' => [
                'name' => 'customer_remark',
                'data' => 'customer_remark',
                'orderable' => false
            ],
            '状态' => [
                'name' => 'status',
                'render' => 'function(){
                    return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">正常</button>\' : \'<button class="btn btn-xs btn-danger">已关闭</button>\'
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
        return 'carrierAgentUsers';
    }
}
