<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierAgentUser;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentAuditHistoryDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_audit_history.datatables_actions')
            ->addColumn('type_name',function(CarrierAgentUser $agentlevel){
                return \App\Models\CarrierAgentLevel::typeMeta()[$agentlevel['agentLevel']->type];
            })
            ->addColumn('status_name',function(CarrierAgentUser $agentlevel){
                return \App\Models\CarrierAgentUser::statusMeta()[$agentlevel->status];
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
        $carrierAgentUsers = CarrierAgentUser::with('agentLevel')->select("*")
            ->where('carrier_id','=',\Auth::user()->carrier_id)
            ->where('audit_status','=',1)->where('is_default',0)
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
            '代理账号' => ['name' => 'username','data' => 'username'],
            '姓名' => ['name' => 'realname', 'data' => 'realname'],
            '代理类型' => ['name' => 'type', 'data' => 'type_name','defaultContent' => '','orderable' => false],
            '代理名称' => ['name' => 'level_name', 'data' => 'agent_level.level_name','defaultContent' => '','orderable' => false],
            '注册时间' => ['name' => 'created_at', 'data' => 'created_at','orderable' => false],
            '注册IP' => ['name' => 'register_ip', 'data' => 'register_ip','orderable' => false],
            '状态' => ['data' => 'status_name','orderable' => false],
            '备注' => ['name' => 'customer_remark','data' => 'customer_remark','orderable' => false],
            '处理时间' => ['name' => 'customer_time','data' => 'customer_time','orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierAgentAudits';
    }
}
