<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierActivity;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierActivityFundDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('status_name',function(CarrierActivity $element){
                return $element->status == CarrierActivity::STATUS_SHELVES ? '已上架' : '已下架';
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
//        $carrierActivities = CarrierActivity::query();
        $carrierActivities = CarrierActivity::with('actType')->select("*")->where('carrier_id','=',\Auth::user()->carrier_id)->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierActivities);
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
//           ->addColumn(['title' => 'intro','data' => null])
            ->ajax([
                'data' => \Config::get('datatables.ajax.data')
            ])
            ->parameters([
                'searching' => false,
                'ordering' => false,
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [
                ],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                    var api = this.api();
                    var dataLists = api.data();
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
            '活动名称' => ['name' => 'name', 'data' => 'name','searchable' => false],
            '参与人数' => ['name' => 'join_player_count', 'data' => 'join_player_count'],
            '参与次数' => ['name' => 'join_times', 'data' => 'join_times','orderable' => false],
            '存款总额' => ['name' => 'join_deposit_amount','data' => 'join_deposit_amount'],
            '红利总额' => ['name' => 'join_bonus_amount', 'data' => 'join_bonus_amount'],
            '活动状态' => ['name' => 'status' ,'data' => 'status_name'],
            '创建时间' => ['name' => 'created_at', 'data' => 'created_at']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierActivities';
    }
}
