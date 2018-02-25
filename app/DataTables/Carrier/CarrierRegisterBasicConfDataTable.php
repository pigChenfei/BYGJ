<?php

namespace App\DataTables\Carrier;

use App\Models\Conf\CarrierRegisterBasicConf;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierRegisterBasicConfDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'carrier_register_basic_confs.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierRegisterBasicConfs = CarrierRegisterBasicConf::orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierRegisterBasicConfs);
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
            'player_birthday_conf_status' => ['name' => 'player_birthday_conf_status', 'data' => 'player_birthday_conf_status'],
            'player_realname_conf_status' => ['name' => 'player_realname_conf_status', 'data' => 'player_realname_conf_status'],
            'player_email_conf_status' => ['name' => 'player_email_conf_status', 'data' => 'player_email_conf_status'],
            'player_phone_conf_status' => ['name' => 'player_phone_conf_status', 'data' => 'player_phone_conf_status'],
            'player_qq_conf_status' => ['name' => 'player_qq_conf_status', 'data' => 'player_qq_conf_status'],
            'player_wechat_conf_status' => ['name' => 'player_wechat_conf_status', 'data' => 'player_wechat_conf_status'],
            'player_consignee_conf_status' => ['name' => 'player_consignee_conf_status', 'data' => 'player_consignee_conf_status'],
            'player_receiving_address_conf_status' => ['name' => 'player_receiving_address_conf_status', 'data' => 'player_receiving_address_conf_status'],
            'agent_type_conf_status' => ['name' => 'agent_type_conf_status', 'data' => 'agent_type_conf_status'],
            'agent_realname_conf_status' => ['name' => 'agent_realname_conf_status', 'data' => 'agent_realname_conf_status'],
            'agent_birthday_conf_status' => ['name' => 'agent_birthday_conf_status', 'data' => 'agent_birthday_conf_status'],
            'agent_email_conf_status' => ['name' => 'agent_email_conf_status', 'data' => 'agent_email_conf_status'],
            'agent_phone_conf_status' => ['name' => 'agent_phone_conf_status', 'data' => 'agent_phone_conf_status'],
            'agent_qq_conf_status' => ['name' => 'agent_qq_conf_status', 'data' => 'agent_qq_conf_status'],
            'agent_skype_conf_status' => ['name' => 'agent_skype_conf_status', 'data' => 'agent_skype_conf_status'],
            'agent_wechat_conf_status' => ['name' => 'agent_wechat_conf_status', 'data' => 'agent_wechat_conf_status'],
            'agent_promotion_mode_conf_status' => ['name' => 'agent_promotion_mode_conf_status', 'data' => 'agent_promotion_mode_conf_status'],
            'agent_promotion_url_conf_status' => ['name' => 'agent_promotion_url_conf_status', 'data' => 'agent_promotion_url_conf_status'],
            'agent_promotion_idea_conf_status' => ['name' => 'agent_promotion_idea_conf_status', 'data' => 'agent_promotion_idea_conf_status']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierRegisterBasicConfs';
    }
}
