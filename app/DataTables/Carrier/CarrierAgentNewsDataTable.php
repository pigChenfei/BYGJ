<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierAgentNews;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\AgentNews\AgentNewsRelation;

class CarrierAgentNewsDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'carrier_agent_news.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierAgentNews = AgentNewsRelation::with(['agent','carrierAgentNews.serviceUser' => function($builder){
            return $builder->noSuperAdministrator();
        }])->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierAgentNews);
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
            //->addAction(['width' => '110px','title' => '操作'])
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
                }']
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['data' => 'id'],
            '代理账户' => ['data' => 'agent.username','defaultContent' => ''],
            '代理姓名' => ['data' => 'agent.realname','defaultContent' => ''],
            '信息标题' => ['data' => 'carrier_agent_news.title', 'defaultContent' => ''],
            '信息内容' => ['data' => 'carrier_agent_news.remark', 'defaultContent' => ''],
            '发布时间' => ['data' => 'carrier_agent_news.created_at', 'defaultContent' => ''],
            '发布人' => ['render' => 'function(){
                return this.carrier_agent_news.service_user ? this.carrier_agent_news.service_user.username : null
            }', 'data' => 'carrier_agent_news.service_user.username','defaultContent' => '','searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierAgentNews';
    }
}
