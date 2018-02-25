<?php

namespace App\DataTables\Carrier;

use App\Models\Log\PlayerInviteRewardLog;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlayerInviteRewardLogDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'player_invite_reward_logs.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $playerInviteRewardLogs = PlayerInviteRewardLog::orderBy('updated_at', 'desc');

        return $this->applyScopes($playerInviteRewardLogs);
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
                'dom' => 'Bfrtip',
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
            'player_id' => ['name' => 'player_id', 'data' => 'player_id'],
            'reward_type' => ['name' => 'reward_type', 'data' => 'reward_type'],
            'reward_related_player' => ['name' => 'reward_related_player', 'data' => 'reward_related_player'],
            'reward_amount' => ['name' => 'reward_amount', 'data' => 'reward_amount'],
            'related_player_deposit_amount' => ['name' => 'related_player_deposit_amount', 'data' => 'related_player_deposit_amount'],
            'related_player_bet_amount' => ['name' => 'related_player_bet_amount', 'data' => 'related_player_bet_amount'],
            'related_player_validate_bet_amount' => ['name' => 'related_player_validate_bet_amount', 'data' => 'related_player_validate_bet_amount']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'playerInviteRewardLogs';
    }
}
