<div class='btn-group'>
    @if(!$bet_flow_available)
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerBetFlowLogs.passBetFlowAvailable')!!}',
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
        设为有效
    </button>
    @else
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerBetFlowLogs.passBetFlowAvailable')!!}',
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
        设为无效
    </button>
    @endif
</div>