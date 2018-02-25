<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">转账未知处理</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="form-group col-sm-12">
                自动执行自助查询，如果自助查询结果未知，可将如下信息复制给博赢国际客服，进行人工查询
            </div>
            <div class="form-group col-sm-12">
            	<div style="border:1px dashed #eee;width:90%;margin:0 auto;">
            		<p>运营商：{{$playTransfer->carrier->name}}</p>
            		<p>用户名：{{$playTransfer->player->user_name}}</p>
            		<p>转账时间：{{$playTransfer->created_at}}</p>
            		<p>转出：{{$playTransfer->direction == 1 ? '主账户' : $playTransfer->mainGamePlat->main_game_plat_name}}</p>
            		<p>转入：{{$playTransfer->direction == 1 ? $playTransfer->mainGamePlat->main_game_plat_name : '主账户' }}</p>
            		<p>流水号：{{$playTransfer->transid}}</p>
            	</div>
            </div>
            <div class="form-group col-sm-12">
            	<p>自助查询结果：{!! $resMessage !!}</p>
            	<p>如果自助或人工查询结果成功，请点击&emsp;<a href="javascript:void(0);" class="player_edit" data-value="{{route('playerWithdrawLogs.success',['id'=>$playTransfer->id])}}">游戏平台转账成功</a></p>
            	<p>如果自助或人工查询结果失败，请点击&emsp;<a href="javascript:void(0);" class="player_edit" data-value="{{route('playerWithdrawLogs.fail',['id'=>$playTransfer->id])}}" style="color: red">游戏平台转账失败</a></p>
            	<br/>
            	<p>转出成功，转入失败时，资金将打入主账户</p>
            	<p>转出失败时，系统将取消本次未知记录</p>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
    	$(document).on('click','.player_edit:not(.disabled)',function (e) {
            e.preventDefault();
            var _me = this;
            var href = $(_me).attr('data-value');
            var _originText = $(_me).text();
            $(_me).text('处理中...').addClass('disabled');
            $.ajax({
                url:href,
                data:{},
                type:'POST',
                success:function(e){
                    toastr.clear();
                    if(e.success == true){
                        toastr.success(e.message);
                        $('#editAddModal').modal('hide');
                        if(window.LaravelDataTables){
                            window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                        }
                    }else{
                        toastr.error(e.message || '操作失败', '出错啦!')
                    }
                    _me.disabled = false;
                    $(_me).text(_originText).remove('disabled');
                },
                error:function(xhr){
                    toastr.clear();
                    var message = xhr.responseJSON.message || xhr.statusText
                    toastr.error(message || '操作失败', '出错啦!');
                    _me.disabled = false;
                    $(_me).text(_originText).remove('disabled');
                }
            });
        });
    });
</script>