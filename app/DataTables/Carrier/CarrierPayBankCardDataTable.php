<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierActivity;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierPayBankCardDataTable extends DataTable
{

    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_pay_bank_cards.datatables_actions')
            ->make(true);
    }

    public function query()
    {
        $carrierActivityTypes = CarrierActivityDataTable::orderBy('updated_at', 'desc');
        return $this->applyScopes($carrierActivityTypes);
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
//           ->addColumn(['title' => 'intro','data' => null])
            ->addAction(['width' => '10%','title' => '操作'])
            ->ajax([
                'data' => 'function(data){
                    var formData = $(\'#searchForm\').serializeJson();
                    for(index in data.columns){
                        for(dataName in formData){
                             if(data.columns[index].name == dataName){
                                data.columns[index].search.value = formData[dataName];
                             }
                        }
                    }
                }'
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
            '序号' => ['name' => '', 'data' => '','defaultContent' => ''],
            '银行卡名称' => ['name' => '', 'data' => '','defaultContent' => ''],
            '银行卡状态' => ['name' => '', 'data' => '','defaultContent' => ''],
        ];
    }

}
