<?php

namespace App\DataTables\Admin;

use App\Models\Carrier;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Admin.carriers.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carriers = Carrier::orderBy('updated_at', 'desc');

        return $this->applyScopes($carriers);
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
            ->addAction(['width' => '190px','title' => '操作'])
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
            'id' => ['name' => 'id', 'data' => 'id'],
            '名称' => ['name' => 'name', 'data' => 'name'],
            '域名' => ['name' => 'site_url', 'data' => 'site_url'],
            '状态' => ['name' => 'is_forbidden', 'data' => 'is_forbidden', 'render' => 'function(){
                return this.is_forbidden ? "<span class=\"text-danger\">已禁用</span>" : "<span class=\"text-success\">正常</span>"
            }'],
            '剩余配额' => ['name' => 'remain_quota', 'data' => 'remain_quota']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carriers';
    }
}
