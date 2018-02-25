<?php

namespace App\DataTables\Carrier;

use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierPasswordRecoverySiteConfDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'carrier_password_recovery_site_confs.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierPasswordRecoverySiteConfs = CarrierPasswordRecoverySiteConf::orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierPasswordRecoverySiteConfs);
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
            'is_open_email_send_function' => ['name' => 'is_open_email_send_function', 'data' => 'is_open_email_send_function'],
            'smtp_ server' => ['name' => 'smtp_ server', 'data' => 'smtp_ server'],
            'smtp_service_port' => ['name' => 'smtp_service_port', 'data' => 'smtp_service_port'],
            'mail_sender' => ['name' => 'mail_sender', 'data' => 'mail_sender'],
            'smtp_username' => ['name' => 'smtp_username', 'data' => 'smtp_username'],
            'smtp_password' => ['name' => 'smtp_password', 'data' => 'smtp_password']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierPasswordRecoverySiteConfs';
    }
}
