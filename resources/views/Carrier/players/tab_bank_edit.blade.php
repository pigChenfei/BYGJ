<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑{{$bankCard->player->user_name}}银行卡信息</h4>
        </div>
        {!! Form::model($bankCard, ['class' => 'form-horizontal','id' => 'playerAccountAdjustCreateForm']) !!}
        <input type="hidden" name="card_id" value="{{$bankCard->card_id}}">
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="card_owner_name" class="col-sm-3 control-label">开&nbsp;&nbsp;户&nbsp;&nbsp;人</label>
                    <div class="col-sm-9">
                        <input placeholder="请输入开户人姓名" type="text" name="card_owner_name" class="form-control" value="{{$bankCard->card_owner_name}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_account" class="col-sm-3 control-label">卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</label>
                    <div class="col-sm-9">
                        <input placeholder="请输入卡号" type="number" name="card_account" class="form-control" value="{{$bankCard->card_account}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_type" class="col-sm-3 control-label">银行名称</label>
                    <div class="col-sm-9">
                        <select name="card_type" class="form-control">
                            <option value="">不限</option>
                            @foreach($banks as $v)
                                <option value="{!! $v->type_id !!}" @if($bankCard->card_type == $v->type_id)selected @endif>{!! $v->bank_name !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="card_birth_place" class="col-sm-3 control-label">分行名称</label>
                    <div class="col-sm-9">
                        <input placeholder="请输入分行名称" type="text" name="card_birth_place" class="form-control" value="{{$bankCard->card_birth_place}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('players.tabBankStore')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function () {

    })
</script>