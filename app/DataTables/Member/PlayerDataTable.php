<?php

namespace App\DataTables\Member;

use App\Models\Member\Player;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'players.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $players = Player::query();

        return $this->applyScopes($players);
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
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
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
            'mobile' => ['name' => 'mobile', 'data' => 'mobile'],
            'real_name' => ['name' => 'real_name', 'data' => 'real_name'],
            'user_name' => ['name' => 'user_name', 'data' => 'user_name'],
            'password' => ['name' => 'password', 'data' => 'password'],
            'pay_password' => ['name' => 'pay_password', 'data' => 'pay_password'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'score' => ['name' => 'score', 'data' => 'score'],
            'account_money' => ['name' => 'account_money', 'data' => 'account_money'],
            'login_time' => ['name' => 'login_time', 'data' => 'login_time'],
            'delete_time' => ['name' => 'delete_time', 'data' => 'delete_time'],
            'login_ip' => ['name' => 'login_ip', 'data' => 'login_ip'],
            'operator_id' => ['name' => 'operator_id', 'data' => 'operator_id'],
            'agent_id' => ['name' => 'agent_id', 'data' => 'agent_id'],
            'is_online' => ['name' => 'is_online', 'data' => 'is_online'],
            'user_status' => ['name' => 'user_status', 'data' => 'user_status'],
            'remark' => ['name' => 'remark', 'data' => 'remark'],
            'level_id' => ['name' => 'level_id', 'data' => 'level_id'],
            'qq_account' => ['name' => 'qq_account', 'data' => 'qq_account'],
            'birthday' => ['name' => 'birthday', 'data' => 'birthday'],
            'register_ip' => ['name' => 'register_ip', 'data' => 'register_ip']
        ];
    }



    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'players';
    }
}
