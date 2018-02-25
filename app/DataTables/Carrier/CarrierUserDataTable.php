<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierUser;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierUserDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_users.datatables_actions')
            ->addColumn('status',function(CarrierUser $carrierUser){
                return \App\Models\CarrierUser::statusMeta()[$carrierUser->status];
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
        $carrierUsers = CarrierUser::with('serviceTeam')->noSuperAdministrator()->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierUsers);
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
            ->addAction(['width' => '20%','title' => '操作'])    
            //->addAction(['width' => '10%'])
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
                    }']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['name' => 'id', 'data' => 'id','orderable' => false],
            '客服账号' => ['name' => 'username', 'data' => 'username','orderable' => false],
            '部门' => ['name'=>'team_id', 'data' => 'service_team.team_name','defaultContent' => '','orderable' => false],
            '状态' => [
                'name' => 'status',
                'data' => 'status',
                'orderable' => false],
            '最后登录时间'=>['name' => 'login_at', 'data' => 'login_at','orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierUsers';
    }
}
