<div class='btn-group'>
    <button onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(_me,'{!! route('game.showAssignCarriersModal') !!}',{game_ids:[{!! $game_id !!}]},function(html){
            $('#editAddModal').html(html).modal('show');
            },null,'POST',{dataType:'html'})" class='btn btn-default btn-xs'>
        <i class="fa fa-edit">分配运营商</i>
    </button>
    <button onclick="var _me = this; $.fn.winwinAjax.buttonActionSendAjax(_me,'{!! route('game.toggleGameStatus',$game_id) !!}',{},function(){
                $.fn.alertSuccess('操作成功');
                 window.LaravelDataTables.dataTableBuilder.draw()
            },null,'POST')" class='btn {!! $status == \App\Models\Def\Game::STATUS_AVAILABLE ? 'btn-danger' : 'btn-success' !!} btn-xs'>
        @if($status == \App\Models\Def\Game::STATUS_AVAILABLE)
            <i class="fa fa-close">关闭</i>
        @elseif($status == \App\Models\Def\Game::STATUS_CLOSED)
            <i class="fa fa-eye">开放</i>
        @endif
    </button>
    <button onclick="location.href='{{ route('games.show', ['id' => $game_id]) }}'" class='btn btn-default btn-xs'>
        <i class="fa fa-edit">修改</i>
    </button>
</div>
