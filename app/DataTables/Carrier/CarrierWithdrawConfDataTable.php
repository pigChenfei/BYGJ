<?php

namespace App\DataTables\Carrier;

use App\Models\Conf\CarrierWithdrawConf;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierWithdrawConfDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'carrier_withdraw_confs.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierWithdrawConfs = CarrierWithdrawConf::orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierWithdrawConfs);
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
            'carrier_id' => ['name' => 'carrier_id', 'data' => 'carrier_id'],
            'is_allow_player_withdraw' => ['name' => 'is_allow_player_withdraw', 'data' => 'is_allow_player_withdraw'],
            'is_allow_player_withdraw_decimal' => ['name' => 'is_allow_player_withdraw_decimal', 'data' => 'is_allow_player_withdraw_decimal'],
            'player_day_withdraw_success_limit_count' => ['name' => 'player_day_withdraw_success_limit_count', 'data' => 'player_day_withdraw_success_limit_count'],
            'player_day_withdraw_max_sum' => ['name' => 'player_day_withdraw_max_sum', 'data' => 'player_day_withdraw_max_sum'],
            'player_once_withdraw_max_sum' => ['name' => 'player_once_withdraw_max_sum', 'data' => 'player_once_withdraw_max_sum'],
            'player_once_withdraw_min_sum' => ['name' => 'player_once_withdraw_min_sum', 'data' => 'player_once_withdraw_min_sum'],
            'is_diaplay_flow_water_check' => ['name' => 'is_diaplay_flow_water_check', 'data' => 'is_diaplay_flow_water_check'],
            'is_check_flow_water_when_withdraw' => ['name' => 'is_check_flow_water_when_withdraw', 'data' => 'is_check_flow_water_when_withdraw'],
            'is_allow_agent_withdraw' => ['name' => 'is_allow_agent_withdraw', 'data' => 'is_allow_agent_withdraw'],
            'is_allow_agent_withdraw_decimal' => ['name' => 'is_allow_agent_withdraw_decimal', 'data' => 'is_allow_agent_withdraw_decimal'],
            'agent_day_withdraw_success_limit_count' => ['name' => 'agent_day_withdraw_success_limit_count', 'data' => 'agent_day_withdraw_success_limit_count'],
            'agent_day_withdraw_max_sum' => ['name' => 'agent_day_withdraw_max_sum', 'data' => 'agent_day_withdraw_max_sum'],
            'agent_once_withdraw_max_sum' => ['name' => 'agent_once_withdraw_max_sum', 'data' => 'agent_once_withdraw_max_sum'],
            'agent_once_withdraw_min_sum' => ['name' => 'agent_once_withdraw_min_sum', 'data' => 'agent_once_withdraw_min_sum']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierWithdrawConfs';
    }
}
