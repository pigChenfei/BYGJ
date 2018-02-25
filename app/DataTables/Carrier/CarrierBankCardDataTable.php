<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierBankCard;
use Form;
use Datatables;
use Yajra\Datatables\Services\DataTable;

class CarrierBankCardDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_bank_cards.datatables_actions')
            ->addColumn('status_name',function(CarrierBankCard $card){
                return CarrierBankCard::statusMeta()[$card->status];
            })
            ->addColumn('pay_support_channel_name',function(CarrierBankCard $card){
                return CarrierBankCard::paySupportChannelsMeta()[$card->pay_support_channel];
            })
            ->addColumn('use_purpose_name',function(CarrierBankCard $card){
                return CarrierBankCard::usedForPurposeMeta()[$card->use_purpose];
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

        $carrierBankCards = CarrierBankCard::where(['carrier_id' => \Auth::user()->carrier_id])->with('cardBankType')->orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierBankCards);
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
            ->addAction(['width' => '10%','title' => '操作'])
            ->ajax([
                'data' => \Config::get('datatables.ajax.data')
            ])
            ->parameters([
                'paging' => true,
                'searching' => false,
                'info' => true,
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [
                ],
                'language' => \Config::get('datatables.language'),
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
            '序号' => ['name' => 'card_type_id', 'data' => 'card_type_id','orderable' => false],
            '银行' => ['name' => 'bank_type','data' => 'card_bank_type.bank_name', 'orderable' => false],
            '账号' => ['name' => 'account', 'data' => 'account','orderable' => false],
            '持卡人' => ['name' => 'owner_name', 'data' => 'owner_name','orderable' => false],
            '状态' => ['name' => 'status','data' => 'status_name','orderable' => false],
            '支付渠道' => ['data' => 'pay_support_channel_name','orderable' => false],
            '用途' => ['data' => 'use_purpose_name','orderable' => false],
            '开户行' => ['name' => 'card_origin_place', 'data' => 'card_origin_place','orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierBankCards';
    }
}
