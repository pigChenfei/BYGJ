<?php
namespace App\DataTables\Carrier;

use App\Models\Log\CarrierAgentWithdrawLog;
use Form;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentWithdrawLogDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTable = $this->datatables->eloquent($this->query())
            ->addColumn('opname',
            function (CarrierAgentWithdrawLog $log) {
                if (is_null($log->carrierOperator) || empty($log->carrierOperator)) {
                    return '';
                }
                return $log->carrierOperator->username;
            })
            ->addColumn('status_name',
            function (CarrierAgentWithdrawLog $log) {
                return CarrierAgentWithdrawLog::statusMeta()[$log->status];
            });
        $status = $this->request()->get('status_value');
        if (! empty($status)) {
            $dataTable->where('status', $status);
        }
        $carrier_pay_channel_value = $this->request()->get('carrier_pay_channel_value');
        if (! empty($carrier_pay_channel_value)) {
            $dataTable->where('carrier_pay_channel', $carrier_pay_channel_value);
        }
        return $dataTable->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $opr = [
            1,
            - 1
        ];
        $playerWithdrawLogs = CarrierAgentWithdrawLog::with(
            [
                'bankCard.bankType',
                'agent',
                'carrierPayChannel.payChannel',
                'carrierOperator'
            ])->whereIn('status', $opr)->orderBy('updated_at', 'desc');
        return $this->applyScopes($playerWithdrawLogs);
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
            ->ajax([
            'data' => \Config::get('datatables.ajax.data')
        ])
            ->parameters(
            [
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
                'data' => 'id',
                'searchable' => false
            ],
            '流水号' => [
                'name' => 'order_number',
                'data' => 'order_number',
                'searchable' => false
            ],
            '账号' => [
                'name' => 'agent.username',
                'data' => 'agent.username',
                'defaultContent' => ''
            ],
            '姓名' => [
                'name' => 'agent.realname',
                'data' => 'agent.realname',
                'defaultContent' => ''
            ],
            '取款金额' => [
                'name' => 'apply_amount',
                'data' => 'apply_amount',
                'searchable' => false
            ],
            '实取金额' => [
                'name' => 'finally_withdraw_amount',
                'data' => 'finally_withdraw_amount',
                'searchable' => false
            ],
            '出款银行' => [
                'name' => 'carrier_pay_channel',
                'data' => 'carrier_pay_channel.pay_channel.channel_name',
                'defaultContent' => '',
                'render' => 'function(){
                return this.carrier_pay_channel ? this.carrier_pay_channel.pay_channel.channel_name : null;
            }',
                'searchable' => false
            ],
            '代理银行卡' => [
                'name' => 'player_bank_card',
                'render' => 'function(){
                return "<p class=\"no-margin\">"+ this.bank_card.bank_type.bank_name + "(" + this.bank_card.card_owner_name + ")</p><p class=\"no-margin\">" + this.bank_card.card_account + "</p>"
            }',
                'searchable' => false
            ],
            // '审核时间' => ['name' => 'reviewed_at', 'data' => 'reviewed_at'],
            // '审核人' => ['name' => 'operator', 'data' => 'operator.username'],
            '备注' => [
                'name' => 'remark',
                'render' => 'function(){
                return this.remark ? "<a class=\"text-primary\" style=\"cursor: pointer\" data-toggle=\"tooltip\" data-original-title=\"" + this.remark + "\" class=\"fa fa-question-circle\">查看</a>" : "无"
            }',
                'searchable' => false
            ],
            '状态' => [
                'name' => 'status',
                'data' => 'status_name',
                'searchable' => false
            ],
            '申请时间' => [
                'name' => 'created_at',
                'data' => 'created_at',
                'searchable' => false
            ],
            '出款时间' => [
                'name' => 'withdraw_succeed_at',
                'data' => 'withdraw_succeed_at',
                'searchable' => false
            ],
            '操作人' => [
                'name' => 'opname',
                'data' => 'opname',
                'searchable' => false
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
        return 'playerWithdrawLogs';
    }
}
