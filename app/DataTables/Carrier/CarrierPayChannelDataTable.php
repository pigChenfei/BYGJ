<?php

namespace App\DataTables\Carrier;

use App\Models\CarrierPayChannel;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Def\PayChannel;
use App\Models\Def\PayChannelType;
use Illuminate\Database\Eloquent\Builder;

class CarrierPayChannelDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'Carrier.carrier_pay_channels.datatables_actions')
            ->addColumn('status_name',function(CarrierPayChannel $card){
                return CarrierPayChannel::statusMeta()[$card->status];
            })
            ->addColumn('use_purpose_name',function(CarrierPayChannel $card){
                return CarrierPayChannel::usedForPurposeMeta()[$card->use_purpose];
            })
            ->filter(function (Builder $builder){
                if($this->request()->get('top_pay_channel')){
                    $typeIds = array_map(function ($element){
                        return $element['id'];
                    },PayChannelType::allPayChannelTypesByTypeId($this->request()->get('top_pay_channel')));
                    $defChannels = PayChannel::whereIn('pay_channel_type_id',$typeIds)->get(['id'])->toArray();
                    $defChannelIds = array_map(function($element){
                        return $element['id'];
                    },$defChannels);
                    $builder->whereIn('def_pay_channel_id',$defChannelIds);
                }
                return $builder;
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
        $carrierPayChannels = CarrierPayChannel::with('payChannel.payChannelType.parentPayChannelType')->orderBy('updated_at', 'desc');

        return $this->applyScopes($carrierPayChannels);
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
            ->addAction(['width' => '180px','title' => '操作'])
            ->ajax([
                'data' => \Config::get('datatables.ajax.data')
            ])
            ->parameters([
                'paging' => false,
                'searching' => false,
                'ordering' => false,
                'info' => false,
                'dom' => 'Bfrt',
                'scrollX' => false,
                'buttons' => [
                ],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                    var api = this.api();
                    var dataLists = api.data();
            　　    var startIndex= api.context[0]._iDisplayStart;
                　　api.column(0).nodes().each(function(cell, i) {
                　　　　cell.innerHTML = startIndex + i + 1;
                　　});
                    $("[name=\'winwin-switch\']").bootstrapSwitch({
                        size:\'mini\',
                        onText:\'正常\',
                        offText:\'关闭\',
                        on:\'success\',
                        off:\'warning\',
                        onSwitchChange:function(){
                            var tr = $(this).parents(\'tr\');
                            var data = dataLists[api.row(tr).index()]
                            data._method = \'PATCH\';
                            delete data.action;
                            data.status  =  this.checked ? 1 : 0;
                            var serverUrl = \''.route('carrierPayChannels.saveStatus',null).'\/\' + $(this).attr(\'data-id\')
                            $.fn.showOverlayLoading();
                            var _me = this;
                            $.ajax({
                                url:serverUrl,
                                data:data,
                                type:\'POST\',
                                success:function(e){
                                    if(e.success != true){
                                        toastr.error(e.message || \'更新失败\', \'出错啦!\')
                                    }
                                    $.fn.hideOverlayLoading();
                                },
                                error:function(xhr){
                                    $.fn.hideOverlayLoading();
                                    toastr.error(xhr.responseJSON.message || \'更新失败\', \'出错啦!\');
                                }
                            })
                        }
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
            '账户类型' => ['data' => 'pay_channel.pay_channel_type.parent_pay_channel_type.type_name','defaultContent' => '', 'orderable' => false],
            '持卡人' => ['name' => 'owner_name', 'data' => 'owner_name','orderable' => false],
            '银行卡号' => ['name' => 'account','data' => 'account', 'orderable' => false],
            '前台展示名称' => ['name' => 'display_name','data' => 'display_name','defaultContent' => '', 'orderable' => false],
            '银行名称' => ['data' => 'pay_channel.channel_name','defaultContent' => '', 'orderable' => false],
            '支持方式' => ['data' => 'pay_channel.pay_channel_type.type_name','defaultContent' => '', 'orderable' => false],
            '余额' => ['name' => 'balance','data' => 'balance','orderable' => false],
            '用途' => ['name'=>'use_purpose_name','data' => 'use_purpose_name','orderable' => false],
            '状态' => [
                'name' => 'status',
                'data' => 'status',
                'width' => '110px',
                'render' => 'function(){
                    return "<input id=\'switch-animate\' data-id=\'"+ this.id +"\' name=\'winwin-switch\' type=\'checkbox\' "+ (this.status ? "checked=checked" : "") +">";
                    return this.status == true ? \'<button class="btn btn-xs btn-success">启用</button>\' : \'<button class="btn btn-xs btn-danger">禁用</button>\'
                }'],
//            '最后修改时间' => ['name' => 'updated_at','data' => 'updated_at','orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierPayChannels';
    }
}
