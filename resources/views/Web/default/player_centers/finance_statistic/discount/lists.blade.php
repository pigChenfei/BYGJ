{{--优惠记录列表--}}
@if($carrierActivityAudit->items())
    @foreach($carrierActivityAudit as $item)
        <tr>
            <td>{!! $item->id !!}</td>
            <td>{!! $item->activity->name !!}</td>
            <td>{!! $item->activity->actType->type_name !!}</td>
            <td>{!! $item->process_bonus_amount !!}</td>
            <td>{!! $item->updated_at !!}</td>
            <td>{!! $item->process_withdraw_flow_limit !!}</td>
            <td>{!! $item->remark !!}</td>
            <td>{!! $item->statusMeta()[$item->status] !!}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: left;border: 0;" id="discountPerPage">{!! \WTemplate::displayPerPage($carrierActivityAudit->total(), $carrierActivityAudit->perPage()) !!}</td>
    @if($carrierActivityAudit->links()->toHtml())
        <td colspan="6" style="text-align: right;;border: 0;" id="discountPagination">{!! $carrierActivityAudit->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="8">暂无记录</td></tr>
@endif

<script>
    $(function(){
        //分页
        addEventListener("#discountPagination a");
    });
</script>
