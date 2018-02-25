<?php
namespace App\DataTables\Carrier;

use App\Models\CarrierPayChannel;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use App\Models\Log\PlayerDepositPayLog;
use Form;
use Illuminate\Database\Eloquent\Builder;
use Yajra\Datatables\Services\DataTable;
use App\Models\PlayerTransfer;

class PlayerTransferUnknownProcessDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Carrier.player_transfer_unknown_process.datatables_actions')
            ->addColumn('state', function (PlayerTransfer $model) {
            switch ($model->state) {
                case 0:
                    return '转帐中';
                case 1:
                    return '自动转帐成功';
                case 2:
                    return '自动转帐失败';
                case 3:
                    return '手动转帐成功';
                case 4:
                    return '手动转帐失败';
            }
        })
            ->addColumn('direction', function (PlayerTransfer $model) {
            if ($model->direction == 1) {
                $str = '主账号 转入 ' . $model->mainGamePlat->main_game_plat_name;
            } else {
                $str = $model->mainGamePlat->main_game_plat_name . ' 转入 主账号';
            }
            return $str;
        });
        
        // if ($topChannelId = $this->request()->get('top_pay_channel')) {
        // $typeIds = array_map(function ($element) {
        // return $element['id'];
        // }, PayChannelType::allPayChannelTypesByTypeId($topChannelId));
        // $defChannels = PayChannel::whereIn('pay_channel_type_id', $typeIds)->get([
        // 'id'
        // ])->toArray();
        // $defChannelIds = array_map(function ($element) {
        // return $element['id'];
        // }, $defChannels);
        // $carrierPayChannels = CarrierPayChannel::whereIn('def_pay_channel_id', $defChannelIds)->get([
        // 'id'
        // ])->toArray();
        // $carrierPayChannelsIds = array_map(function ($element) {
        // return $element['id'];
        // }, $carrierPayChannels);
        // $DataTables->whereIn('carrier_pay_channel', $carrierPayChannelsIds);
        // }
        
        if ($range_time = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $this->request()->get('date_time_range'));
            if (count($time) == 2) {
                $DataTables->where('updated_at', '>=', $time[0]);
                $DataTables->where('updated_at', '<=', $time[1]);
            }
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
        $playTransferUnknown = PlayerTransfer::with([
            'player',
            'mainGamePlat',
            'carrier',
            'carrierOperator'
        ])->transferUnknown()->orderBy('updated_at', 'desc');
        
        return $this->applyScopes($playTransferUnknown);
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
            ->addAction([
            'width' => '100px',
            'title' => '操作',
            'render' => 'function(){
                return  this.action;
            }'
        ])
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
            'buttons' => [],
            'language' => \Config::get('datatables.language'),
            'drawCallback' => 'function(){
                    var api = this.api();
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
            // '单号' => ['name' => 'pay_order_number','data' => 'pay_order_number','style' => 'text-align:center'],
            '单号' => [
                'name' => 'transid',
                'data' => 'transid',
                'style' => 'text-align:center'
            ],
            '账号' => [
                'name' => 'player.user_name',
                'data' => 'player.user_name',
                'style' => 'text-align:center',
                'render' => 'function(){
                      return "<a class=\"text-primary user_edit\" data-id=\""+ (this.player ? this.player.player_id : null) +"\" style=\"cursor: pointer\">" + (this.player ? this.player.user_name : null) + "</a>"
            }'
            ],
            '主游戏平台' => [
                'name' => 'main_game_plat.main_game_plat_name',
                'data' => 'main_game_plat.main_game_plat_name',
                'style' => 'text-align:center'
            ],
            // '会员等级' => ['name' => 'player.real_name', 'data' => 'player.real_name','style' => 'text-align:center'],
            // '所属代理' => ['name' => 'player.real_name', 'data' => 'player.real_name','style' => 'text-align:center'],
            '处理状态' => [
                'name' => 'state',
                'data' => 'state',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '转账金额' => [
                'name' => 'money',
                'data' => 'money',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '出入账' => [
                'name' => 'direction',
                'data' => 'direction',
                'style' => 'text-align:center',
                'searchable' => false
            ],
            '转账时间' => [
                'name' => 'updated_at',
                'data' => 'updated_at',
                'style' => 'text-align:center',
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
        return 'playerTransferUnknownProcess';
    }
}
