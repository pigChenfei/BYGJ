<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">绑定三方支付</h4>
        </div>
        {!! Form::model($paylist, ['route' => ['carrierPayChannels.bindPayList', $cid], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-12">
                    {!! Form::label('binded_third_part_pay_id', '三方支付名称') !!}
                    <select name="binded_third_part_pay_id" class="form-control disable_search_select2" style="width: 100%;">
                        @foreach($paylist as $key => $value)
                            <option value="{!! $value->id !!}">{!! $value['defPayChannel']->channel_name !!}-{!! $value->merchant_number !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierPayChannels.bindPayList',$cid)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>
<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>