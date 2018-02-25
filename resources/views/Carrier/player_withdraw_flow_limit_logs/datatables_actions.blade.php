<div class='btn-group'>
    @if(!$is_finished)
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerWithdrawFlowLimitLogs.passCompleteFinished')!!}',
                {
                passType:'all',
                logIds : selectedIds
                },function(){
                    try{
                        window.LaravelDataTables.dataTableBuilder.draw()
                    }catch (e){

                    }
                },function(){

                },'POST'
            );
            " class='btn btn-primary btn-xs'>
        <i class="fa fa-edit"></i>
        完成
    </button>
    @else
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerWithdrawFlowLimitLogs.passCompleteFinished')!!}',
            {
                passType:'none',
                logIds : selectedIds
            },function(){
                try{
                    window.LaravelDataTables.dataTableBuilder.draw()
                }catch (e){

                }
            },function(){

            },'POST'
            );" class='btn btn-danger btn-xs'>
        <i class="fa fa-edit"></i>
        重启
    </button>
    @endif
</div>