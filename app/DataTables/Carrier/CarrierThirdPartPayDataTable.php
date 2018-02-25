<?php

namespace App\DataTables\Carrier;

use App\Models\Conf\CarrierThirdPartPay;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierThirdPartPayDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_third_part_pays.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierThirdPartPays = CarrierThirdPartPay::with('defPayChannel')->whereHas('defPayChannel',
            function ($query) {
                $query;
            })->select("*")->where('carrier_id','=',\Auth::user()->carrier_id)->orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierThirdPartPays);
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
            //->addAction(['width' => '10%'])
            ->addAction(['width' => '280px','title' => '操作'])
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
            '三方支付平台' => ['data' => 'def_pay_channel.channel_name','defaultContent' => '', 'orderable' => false],
            '商户号' => ['name' => 'merchant_number', 'data' => 'merchant_number'],
            '商户绑定域名' => ['name' => 'merchant_bind_domain', 'data' => 'merchant_bind_domain'],
//            '私钥' => ['name' => 'private_key', 'data' => 'private_key'],
//            '商户识别码' => ['name' => 'merchant_identify_code', 'data' => 'merchant_identify_code']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierThirdPartPays';
    }
}
