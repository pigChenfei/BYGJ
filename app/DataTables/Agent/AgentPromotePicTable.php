<?php

namespace App\DataTables\Agent;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\CarrierAgentUser;
class AgentPromotePicTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables
            ->eloquent($this->query());
         return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierAgentUsers = CarrierAgentUser::with('agentLevel')->select("*")->where('parent_id','=',\WinwinAuth::agentUser()->id);
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
            '序号' => ['name' => 'id', 'data' => 'id'],
            '广告名' => ['name' => '','data' => '','defaultContent' => ''],
            '广告类型' => ['name' => '', 'data' => '','defaultContent' => ''],
            '广告尺寸' => ['name' => '', 'data' => '','defaultContent' => ''],
            '缩略图' => ['name' => '', 'data' => '','defaultContent' => ''],
            '图片地址' => ['name' => '', 'data' => '','defaultContent' => ''],
            '更新日期' => ['name' => '', 'data' => '','defaultContent' => ''],
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
