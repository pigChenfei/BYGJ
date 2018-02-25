<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use App\Models\Log\CarrierAgentDepositPayLog;
use Form;
use Illuminate\Database\Eloquent\Builder;
use Yajra\Datatables\Services\DataTable;

class CarrierAgentDepositVerifyDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables->eloquent($this->query())
            ->
        // ->addColumn('action','Carrier.player_deposit_pay_logs.datatables_actions')
        addColumn('statusName', function (CarrierAgentDepositPayLog $model) {
            return CarrierAgentDepositPayLog::orderStatusMeta()[$model->status];
        });
        
        if ($topChannelId = $this->request()->get('top_pay_channel')) {
            $typeIds = array_map(function ($element) {
                return $element['id'];
            }, PayChannelType::allPayChannelTypesByTypeId($topChannelId));
            $defChannels = PayChannel::whereIn('pay_channel_type_id', $typeIds)->get([
                'id'
            ])->toArray();
            $defChannelIds = array_map(function ($element) {
                return $element['id'];
            }, $defChannels);
            $carrierPayChannels = CarrierPayChannel::whereIn('def_pay_channel_id', $defChannelIds)->get([
                'id'
            ])->toArray();
            $carrierPayChannelsIds = array_map(function ($element) {
                return $element['id'];
            }, $carrierPayChannels);
            $DataTables->whereIn('carrier_pay_channel', $carrierPayChannelsIds);
        }
        
        if ($range_time = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $this->request()->get('date_time_range'));
            if (count($time) == 2) {
                $DataTables->where('updated_at', '>=', $time[0]);
                $DataTables->where('updated_at', '<=', $time[1]);
            }
        } else {
            $DataTables->where('created_at', '>=', date('Y-m-01', time()));
            $DataTables->where('created_at', '<=', date('Y-m-d H:i:s'));
        }
        
        return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $carrierAgentDepositPayLogs = CarrierAgentDepositPayLog::with([
            'agent',
            'carrierPayChannel.payChannel',
            'reviewUser'
        ])->WaitingReview()->orderBy('updated_at', 'desc');
        
        return $this->applyScopes($carrierAgentDepositPayLogs);
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
        // ->addAction(['width' => '120px','title' => '操作'])
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
                    $(".user_edit").on("click",function(){
                        $.fn.overlayToggle();
                        var _me = this;
                        var user_id = $(_me).attr("data-id")
                        $.fn.winwinAjax.buttonActionSendAjax(_me,"' . route('players.showPlayerInfoEditModal', null) . '/"+ user_id,{},function(content){
                            $.fn.overlayToggle();
                            $("#userInfoEditModal").html(content);
                            $("#userInfoEditModal").modal("show");
                        },function(){
                            
                        },"GET",{dataType:"html"})
                    })
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
                'defaultContent' => '',
                'searchable' => false,
                'style' => 'text-align:center'
            ],
            '存款时间/单号' => [
                'name' => 'pay_order_number',
                'data' => 'pay_order_number',
                'defaultContent' => '',
                'style' => 'text-align:center'
            ],
            '账号' => [
                'name' => 'agent.username',
                'data' => 'agent.username',
                'defaultContent' => '',
                'style' => 'text-align:center',
                'render' => 'function(){
                      return "<a class=\"text-primary user_edit\" data-id=\""+ (this.player ? this.agent.id : null) +"\" style=\"cursor: pointer\">" + (this.player ? this.agent.username : null) + "</a>"
            }'
            ],
            '姓名' => [
                'name' => 'agent.realname',
                'data' => 'agent.realname',
                'defaultContent' => '',
                'style' => 'text-align:center'
            ],
            '存款金额' => [
                'name' => 'amount',
                'data' => 'amount',
                'defaultContent' => '',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '手续费' => [
                'name' => 'fee_amount',
                'data' => 'fee_amount',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '实际到账' => [
                'name' => 'finally_amount',
                'data' => 'finally_amount',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '存款优惠' => [
                'name' => 'benefit_amount',
                'data' => 'benefit_amount',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '交易方式' => [
                'name' => 'carrier_pay_channel',
                'data' => 'carrier_pay_channel.pay_channel.channel_name',
                'defaultContent' => '',
                'style' => 'text-align:center'
            ],
            '存入银行' => [
                'name' => 'carrier_pay_channel',
                'data' => 'carrier_pay_channel.display_name',
                'defaultContent' => '',
                'style' => 'text-align:center'
            ],
            '状态' => [
                'name' => 'status',
                'data' => 'statusName',
                'style' => 'text-align:center'
            ],
            '处理时间' => [
                'name' => 'updated_at',
                'data' => 'updated_at',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '凭证' => [
                'name' => 'credential',
                'data' => 'credential',
                'style' => 'text-align:center'
            ],
            '备注' => [
                'name' => 'remark',
                'data' => 'remark',
                'style' => 'text-align:center'
            ],
            '操作人' => [
                'name' => 'review_user_id',
                'defaultContent' => '',
                'render' => 'function(){
                return this.review_user ? this.review_user.username : "";
            }',
                'style' => 'text-align:center'
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
        return 'playerDepositPayLogs';
    }
}
