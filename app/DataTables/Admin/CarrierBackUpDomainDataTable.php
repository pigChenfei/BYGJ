<?php

namespace App\DataTables\Admin;

use App\Models\CarrierBackUpDomain;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierBackUpDomainDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Admin.carrier_back_up_domains.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierBackUpDomains = CarrierBackUpDomain::with(['carrier'])->orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierBackUpDomains);
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
            '序号' => ['name' => 'id', 'data' => 'id'],
            '运营商名称' => ['data' => 'carrier.name'],
            '域名' => ['name' => 'domain', 'data' => 'domain'],
            '状态' => ['name' => 'status', 'data' => 'status', 'render' => 'function(){
                return this.status ? "<span class=\"text-success\">正常</span>" : "<span class=\"text-danger\">已禁用</span>"
            }'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierBackUpDomains';
    }
}
