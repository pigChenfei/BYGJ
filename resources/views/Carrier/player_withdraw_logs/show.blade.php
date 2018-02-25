<div class="modal-dialog modal-lg" style="min-width: 1280px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">流水详情检查</h4>
            <h5 class="text-primary">该用户未完成流水: {!! $unfinishedAmount !!}; 其中不限平台: {!! $unfinishedWithPlatAmount !!}</h5>
        </div>
        {!! Form::model($playerWithdrawLog, ['route' => ['playerWithdrawLogs.update', $playerWithdrawLog->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="col-sm-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>限制类型</th>
                            <th style="width: 200px">限制平台</th>
                            <th>流水要求</th>
                            <th>已完成流水</th>
                            <th>关联活动</th>
                            <th>是否已完成流水</th>
                            <th style="width: 300px">流水详情</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withDrawFlowRecords as $withDrawFlowRecord)
                            <tr class="flow_limit_row">
                                <td>{!! \App\Models\Log\PlayerWithdrawFlowLimitLog::limitTypeMeta()[$withDrawFlowRecord->limit_type]!!}</td>
                                <td>{!! implode(',',$withDrawFlowRecord->limitGamePlats->map(function($element){
                                            return $element->gamePlat->game_plat_name;
                                        })->toArray()) !!}</td>
                                <td>{!! $withDrawFlowRecord->limit_amount !!}</td>
                                <td>{!! $withDrawFlowRecord->complete_limit_amount !!}</td>
                                <td>{!! $withDrawFlowRecord->carrierActivity ? $withDrawFlowRecord->carrierActivity->name : ''  !!}</td>
                                <td class="flow_has_finished_td">{!! $withDrawFlowRecord->is_finished ? '<span class="no-margin">是</span>' : '<span class="no-margin" style="color:red">否</span>' !!}</td>
                                <td>{!! $withDrawFlowRecord->limitFlowCompleteDetail->map(function($element){
                                            return '<p class="no-margin">'.$element->game->game_name.' '.$element->flow_amount.'</p>';
                                        })->reduce(function($pre,$next){
                                            return $pre.$next;
                                        }) !!}</td>
                                <td>
                                    @if(!$withDrawFlowRecord->is_finished)
                                        <button onclick="var _me = this;
                                                $.fn.winwinAjax.buttonActionSendAjax(
                                                _me,
                                                '{!! route('playerWithdrawLogs.resetWithdrawFlowRecord',$withDrawFlowRecord->id) !!}',
                                                {},
                                                function(){
                                                $(_me).parents('.flow_limit_row').find('.flow_has_finished_td').eq(0).html('<span class=\'no-margin\'>是</span>');
                                                $(_me).hide();
                                                },
                                                function(){
                                                },'PATCH')" class="btn btn-xs btn-warning">清除流水</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>