<?php
namespace App\DataTables\Carrier;

use App\Models\Log\CarrierQuotaConsumptionLog;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierQuotaConsumptionLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'carrier_quota_consumption_logs.datatables_actions');
        if ($dateTimeRange = $this->request()->get('dateTimeRange')) {
            $time = explode(' - ', $dateTimeRange);
            if (count($time) == 2) {
                $dataTables->where('created_at', '>=', $time[0]);
                $dataTables->where('created_at', '<=', $time[1]);
            }
        }
        
        if ($payChannel = $this->request()->get('payChannelValue')) {
            $dataTables->where('related_pay_channel', $payChannel);
        }
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierQuotaConsumptionLogs = CarrierQuotaConsumptionLog::orderBy('updated_at', 'desc')->orderBy('log_id', 'desc')->with('carrierPayChannel.payChannel');
        return $this->applyScopes($carrierQuotaConsumptionLogs);
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
            ->
        // ->addAction(['width' => '10%'])
        ajax([
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
            '序号' => [
                'name' => 'log_id',
                'data' => 'log_id'
            ],
            '支付渠道' => [
                'name' => 'related_pay_channel',
                'data' => 'related_pay_channel',
                'render' => 'function(){
                return this.carrier_pay_channel ? (this.carrier_pay_channel.pay_channel.channel_name + " " + this.carrier_pay_channel.owner_name + " "+ this.carrier_pay_channel.display_name) : "";
            }'
            ],
            '金额' => [
                'name' => 'amount',
                'data' => 'amount'
            ],
            '余额' => [
                'name' => 'pay_channel_remain_amount',
                'data' => 'pay_channel_remain_amount'
            ],
            '明细' => [
                'name' => 'consumption_source',
                'data' => 'consumption_source'
            ],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark'
            ],
            '时间' => [
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
        return 'carrierQuotaConsumptionLogs';
    }
}
