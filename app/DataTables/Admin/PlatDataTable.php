<?php
namespace App\DataTables\Admin;

use App\Models\Def\MainGamePlat;
use Yajra\Datatables\Services\DataTable;

class PlatDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Admin.plat.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $payChannels = MainGamePlat::with('gamePlats')->orderBy('updated_at', 'desc');
        
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
                'data' => 'main_game_plat_id'
            ],
            '主游戏平台名称' => [
                'name' => 'main_game_plat_name',
                'data' => 'main_game_plat_name'
            ],
            '主游戏平台代码' => [
                'name' => 'main_game_plat_code',
                'data' => 'main_game_plat_code'
            ],
            '生成帐号前辍' => [
                'name' => 'account_pre',
                'data' => 'account_pre'
            ],
            '状态' => [
                'name' => 'status',
                'render' => 'function(){
                    return this.status == true ? \'正常\' : \'已关闭\'
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
