<?php
namespace App\DataTables\Carrier;

use App\Models\Log\AgentAccountAdjustLog;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\CarrierAgentLevel;

class AgentAccountAdjustLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables->eloquent($this->query())
            ->addColumn('up_agent',
            function (AgentAccountAdjustLog $log) {
                $agent = $log->agent;
                if ($agent->parent_id == 0 || is_null($agent->parentAgent)) {
                    return '系统代理';
                } else {
                    if ($agent->parentAgent->is_default == 1) {
                        return '系统代理';
                    }
                    return $agent->parentAgent->realname ? $agent->parentAgent->realname : $agent->parentAgent->username;
                }
            })
            ->addColumn('amount',
            function (AgentAccountAdjustLog $log) {
                return sprintf("%.2f", $log->amount);
            })
            ->addColumn('adjust_type_string',
            function (AgentAccountAdjustLog $log) {
                return AgentAccountAdjustLog::adjustTypeMeta()[$log->adjust_type];
            })
            ->addColumn('type_name',
            function (AgentAccountAdjustLog $log) {
                return CarrierAgentLevel::typeMeta()[$log['agent']['agentLevel']->type];
            });
        
        if ($adjust_type_value = $this->request()->get('adjust_type_value')) {
            $DataTables->where('adjust_type', $adjust_type_value);
        }
        
        if ($range_time = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $this->request()->get('date_time_range'));
            if (count($time) == 2) {
                $DataTables->where('updated_at', '>=', $time[0]);
                $DataTables->where('updated_at', '<=', $time[1]);
            }
        } else {
            $DataTables->where('created_at', '>=', date('Y-m-01', time()));
            $DataTables->where('created_at', '<=', date('Y-m-d H:i:s'));
        }
        return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // $agentAccountAdjustLogs = AgentAccountAdjustLog::with(['player.playerLevel','player.agent','operatorUser'])->orderBy('id','desc');
        $agentAccountAdjustLogs = AgentAccountAdjustLog::with(
            [
                'agent.agentLevel',
                'agent.parentAgent',
                'operatorUser'
            ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($agentAccountAdjustLogs);
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
        // ->addAction(['width' => '110px','title' => '操作'])
        ajax([
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
                        $.fn.winwinAjax.buttonActionSendAjax(_me,"' .
                     route('carrierAgentUsers.showAgentUserInfoEditModal', null) . '/"+ user_id,{},function(content){
                            $.fn.overlayToggle();
                            $("#userInfoEditModal").html(content);
                            $("#userInfoEditModal").modal("show");
                        },function(){
                            
                        },"GET",{dataType:"html"})
                    })
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
                'data' => 'id',
                'searchable' => false
            ],
            '账号' => [
                'name' => 'agent.username',
                'data' => 'agent.username',
                'defaultContent' => '',
                'render' => 'function(){
                return "<a class=\"text-primary user_edit\" data-id=\""+ this.agent.id +"\" style=\"cursor: pointer\">" + this.agent.username + "</a>"
            }'
            ],
            '代理类型' => [
                'name' => 'type_name',
                'data' => 'type_name',
                'defaultContent' => '',
                'searchable' => false
            ],
            '代理名称' => [
                'name' => 'agent.agent_level.level_name',
                'data' => 'agent.agent_level.level_name',
                'defaultContent' => '',
                'searchable' => false
            ],
            '上级代理' => [
                'name' => 'up_agent',
                'data' => 'up_agent',
                'defaultContent' => '',
                'searchable' => false
            ],
            '调整类型' => [
                'name' => 'adjust_type',
                'data' => 'adjust_type_string',
                'searchable' => false
            ],
            '金额' => [
                'name' => 'amount',
                'data' => 'amount',
                'searchable' => false
            ],
            '调整时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'searchable' => false
            ],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark'
            ],
            '操作人' => [
                'name' => 'operatorUser.username',
                'data' => 'operator_user.username'
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
        return 'agentAccountAdjustLogs';
    }
}
