<?php

namespace App\DataTables\Carrier;

use App\Models\Conf\CarrierDepositConf;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierDepositConfDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'carrier_deposit_confs.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierDepositConfs = CarrierDepositConf::orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierDepositConfs);
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
            'is_allow_deposit' => ['name' => 'is_allow_deposit', 'data' => 'is_allow_deposit'],
            'is_allow_third_part_deposit_auto_arrival' => ['name' => 'is_allow_third_part_deposit_auto_arrival', 'data' => 'is_allow_third_part_deposit_auto_arrival'],
            'unreview_deposit_record_limit' => ['name' => 'unreview_deposit_record_limit', 'data' => 'unreview_deposit_record_limit'],
            'third_part_deposit_is_open' => ['name' => 'third_part_deposit_is_open', 'data' => 'third_part_deposit_is_open'],
            'company_deposit_is_open' => ['name' => 'company_deposit_is_open', 'data' => 'company_deposit_is_open'],
            'is_allow_company_deposit_auto_arrival' => ['name' => 'is_allow_company_deposit_auto_arrival', 'data' => 'is_allow_company_deposit_auto_arrival'],
            'virtual_card_deposit_is_open' => ['name' => 'virtual_card_deposit_is_open', 'data' => 'virtual_card_deposit_is_open'],
            'is_allow_virtual_card_deposit_auto_arrival' => ['name' => 'is_allow_virtual_card_deposit_auto_arrival', 'data' => 'is_allow_virtual_card_deposit_auto_arrival']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierDepositConfs';
    }
}
