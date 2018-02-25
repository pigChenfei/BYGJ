<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">选择导出字段</h4>
        </div>
        {!! Form::model(null, ['route' => ['players.exportInfo'], 'method' => 'get' , 'id' => 'playerWithDrawReviewForm']) !!}
        <div class="modal-body" id="modalContent">
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">姓名</label>
                <input checked type="checkbox" class="square-blue" name="real_name">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">手机号</label>
                <input checked type="checkbox" class="square-blue" name="mobile">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">邮箱</label>
                <input checked type="checkbox" class="square-blue" name="email">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">QQ</label>
                <input checked type="checkbox" class="square-blue" name="qq_account">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">微信</label>
                <input checked type="checkbox" class="square-blue" name="wechat">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">会员等级</label>
                <input checked type="checkbox" class="square-blue" name="player_level_id">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">邀请人</label>
                <input checked type="checkbox" class="square-blue" name="recommend_player_id">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">所属代理</label>
                <input checked type="checkbox" class="square-blue" name="agent_id">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">主账户余额</label>
                <input checked type="checkbox" class="square-blue" name="main_account_amount">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">公司总输赢</label>
                <input checked type="checkbox" class="square-blue" name="total_win_loss">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">存款额</label>
                <input checked type="checkbox" class="square-blue" name="deposit_amount">
            </div>
            <div class="form-group col-sm-3">
                <label style="width:100px" for="user_name">取款额</label>
                <input checked type="checkbox" class="square-blue" name="withdraw_amount">
            </div>
            <div class="clearfix">
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                <button class="btn btn-primary" onclick="
                        var _me = this;
                        var data = $('#playerWithDrawReviewForm').serializeJson()
                        $.fn.winwinAjax.buttonActionSendAjax(_me,'{!! route('players.exportInfo') !!}',data,function(resp){
                            var url = resp.data;
                            window.open(url);
                            $(_me).parents('.modal').modal('hide')
                        },function(){

                        },'POST');
                        ">保存</button>
                <a onclick="$(this).parents('.modal').modal('hide')" class="btn btn-default">取消</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function(){
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        })
    })
</script>