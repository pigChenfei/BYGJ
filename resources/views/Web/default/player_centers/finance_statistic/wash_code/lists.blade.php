{{--洗码记录列表--}}
@if($playerRebateFinancialFlow->items())
    @foreach($playerRebateFinancialFlow as $item)
        <tr>
            <td>{!! $item->id !!}</td>
            <td>{!! $item->gamePlat->game_plat_name !!}</td>
            <td>{!! $item->bet_flow_amount !!}</td>
            <td>{!! $item->rebate_financial_flow_amount !!}</td>
            <td>{!! $item->settled_at !!}</td>
            <td>
                @if($item->is_already_settled)
                <font color="red">{!! $item->statusMeta()[$item->is_already_settled] !!}</font>
                @else
                {!! $item->statusMeta()[$item->is_already_settled] !!}
                @endif
            </td>

        </tr>
    @endforeach
    <tr>
        <td colspan="2" id="washCodePerPage" style="text-align: left; border: 0;">{!! \WTemplate::displayPerPage($playerRebateFinancialFlow->total(), $playerRebateFinancialFlow->perPage()) !!}</td>
        @if($playerRebateFinancialFlow->links()->toHtml())
            <td colspan="4" id="washCodePagination" style="text-align: right; border: 0;">{!! $playerRebateFinancialFlow->links() !!}</td>
        @endif
    </tr>
@else
    <tr><td colspan="6">暂无记录</td></tr>
@endif

<script>
    $(function(){
        //分页
        addEventListener("#washCodePagination a");
    });
</script>


