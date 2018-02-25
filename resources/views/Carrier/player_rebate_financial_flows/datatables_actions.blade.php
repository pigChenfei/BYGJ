<div class='btn-group'>
    @if(!$is_already_settled)
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerRebateFinancialFlows.passRebateFinancialFlowLog')!!}',
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
        给予返水
    </button>
    <button onclick="
            var selectedIds = [{!! $id !!}];
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                _me,
                '{!! route('playerRebateFinancialFlows.passRebateFinancialFlowLog')!!}',
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
        返零
    </button>
    @endif
</div>