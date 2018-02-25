
@if($playerRebateFinancialFlow->items())
	@foreach($playerRebateFinancialFlow as $item)
	<tr>
		<td>{!! $item->gamePlat->game_plat_name !!}</td>
		<td>{!! $item->bet_flow_amount !!}</td>
		<td>{!! number_format($item->rebate_financial_flow_amount/$item->bet_flow_amount, 2, '.', '') !!}</td>
		<td>{!! $item->rebate_financial_flow_amount !!}</td>
		@if($item->is_already_settled == \App\Models\Log\PlayerRebateFinancialFlow::STATUS_NO_SETTLED)
			<td class="settleTd"><span class="btn btn-warning at-real settleOne" style="border-radius: 2px;padding: 3px 8px;background-color: #2e8ded;border: none;" data="{!! $item->id !!}">立即结算</span></td>
		@else
			<td>已结算</td>
		@endif
	</tr>
	@endforeach
	<tr>
		<td colspan="2" id="rebateFinancialFlowPerPage" style="text-align: left; border: 0;">{!! \WTemplate::displayPerPage($playerRebateFinancialFlow->total(), $playerRebateFinancialFlow->perPage()) !!}</td>
		@if($playerRebateFinancialFlow->links()->toHtml())
			<td colspan="3" id="rebateFinancialFlowPagination" style="text-align: right; border: 0;">{!! $playerRebateFinancialFlow->links() !!}</td>
		@endif
	</tr>
@else
	<tr><td colspan="5">暂无记录</td></tr>
@endif
<script>
	$(function(){
		//分页
		addEventListener("#rebateFinancialFlowPagination a");
	});
</script>



