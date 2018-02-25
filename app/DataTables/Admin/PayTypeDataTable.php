<?php
namespace App\DataTables\Admin;

use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Def\PayChannelType;

class PayTypeDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Admin.payType.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $payChannels = PayChannelType::with('parentPayChannelType')->orderBy('updated_at', 'desc');
        
        return $this->applyScopes($payChannels);
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
            'width' => '190px',
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
            'id' => [
                'name' => 'id',
                'data' => 'id'
            ],
            '银行类型名称' => [
                'name' => 'type_name',
                'data' => 'type_name'
            ],
            '父类名称' => [
                'name' => 'parent_pay_channel_type.type_name',
                'data' => 'parent_pay_channel_type.type_name',
                'render'=>'function(){
                return this.parent_pay_channel_type ?this.parent_pay_channel_type.type_name:"顶级分类"
                }'
            ],
            '创建时间' => [
                'name' => 'created_at',
                'data' => 'created_at'
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
        return 'payTypes';
    }
}
