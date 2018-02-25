<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierAgentDomain;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentDomainDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_agent_domains.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierAgentDomains = CarrierAgentDomain::with('agent')->whereHas('agent', function ($query) {
            $query->where('is_default', 0);
        })
            ->select([
            '*'
        ])
            ->where([
            'carrier_id' => \Auth::user()->carrier_id
        ])->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierAgentDomains);
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
            ->addAction([
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
                'data' => 'agent.username',
                'defaultContent' => '',
                'orderable' => false
            ],
            '邀请码' => [
                'name' => 'tgcode',
                'data' => 'agent.promotion_code',
                'defaultContent' => '',
                'orderable' => false
            ],
            '绑定域名' => [
                'name' => 'website',
                'data' => 'website',
                'orderable' => false
            ],
            '创建时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'orderable' => false
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
        return 'carrierAgentDomains';
    }
}
