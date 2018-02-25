{{--取款列表记录--}}
@if($playerWithdrawLog->items())
    @foreach($playerWithdrawLog as $item)
        <tr>
            <td>{!! $item->order_number !!}</td>
            <td>{!! $item->withdraw_succeed_at!!}</td>
            <td>{!! $item->apply_amount !!}</td>
            <td>{!! $item->fee_amount !!}</td>
            <td>{!! $item->finally_withdraw_amount !!}</td>
            <td>{!! $item->remark !!}</td>
            <td>{!! $item->statusMeta()[$item->status] !!}</td>
        </tr>
    @endforeach
        <tr>
            <td colspan="2" id="withdrawPerPage" style="text-align: left; border: 0;">{!! \WTemplate::displayPerPage($playerWithdrawLog->total(), $playerWithdrawLog->perPage()) !!}</td>
            @if($playerWithdrawLog->links()->toHtml())
            <td colspan="5" id="withdrawPagination" style="text-align: right; border: 0;">{!! $playerWithdrawLog->links() !!}</td>
            @endif
        </tr>
@else
    <tr><td colspan="7">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener("#withdrawPagination a");
    });
</script>
