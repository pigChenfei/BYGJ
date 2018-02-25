<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierAgentUser;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentAuditDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_audit.datatables_actions')
            ->addColumn('type_name', function (CarrierAgentUser $agentlevel) {
            $typeMeta = \App\Models\CarrierAgentLevel::typeMeta();
            if (! empty($typeMeta)) {
                if (!empty($agentlevel->agentLevel))
                    return $typeMeta[$agentlevel->agentLevel->type];
            }
            return '';
        })
            ->addColumn('status_name', function (CarrierAgentUser $agentlevel) {
            return \App\Models\CarrierAgentUser::audit_statusMeta()[$agentlevel->audit_status];
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
            ->where('carrier_id', '=', \Auth::user()->carrier_id)
            ->where('audit_status', '<>', 1)->orderBy('updated_at', 'desc');
        // $carrierAgentUsers = CarrierAgentUser::with('getAgentLevel')->select("*")->where(['carrier_id' => \Auth::user()->carrier_id,'audit_status'=>0]);
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
        // ->addAction(['width' => '10%'])
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
            '序号' => [
                'name' => 'id',
                'render' => 'function(){ return null}',
                'style' => 'text-align:center;width:40px',
                'searchable' => false
            ],
            '代理账号' => [
                'name' => 'username',
                'data' => 'username',
                'defaultContent' => ''
            ],
            '姓名' => [
                'name' => 'realname',
                'data' => 'realname',
                'defaultContent' => '',
                'searchable' => true
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
            '注册时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'orderable' => true
            ],
            '注册IP' => [
                'name' => 'register_ip',
                'data' => 'register_ip',
                'orderable' => true
            ],
            '状态' => [
                'data' => 'status_name',
                'defaultContent' => '',
                'searchable' => false
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
        return 'carrierAgentAudits';
    }
}
