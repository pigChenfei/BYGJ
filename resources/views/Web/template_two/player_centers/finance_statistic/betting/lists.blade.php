{{--投注记录列表--}}
@if($betFlowLogs->items())
    @foreach($betFlowLogs as $item)
    <tr>
      <td>{!! $item->gamePlat->game_plat_name !!}</td>
      <td>{!! $item->count !!}</td>
      <td>{!! $item->bet_water !!}</td>
      <td>{!! $item->effective_bet !!}</td>
      <td>{!! $item->payout !!}</td>
      <td>{!! $item->income * -1 !!} </td>
      <td class="particulars-td"><b><a href="{!! route('players.bettingDetails') !!}" style="color: red;">查看详情</a></b></td>
      <input type="hidden" class="betGamePlatId" value="{!! $item->game_plat_id !!}">
    </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: left;border: 0;" id="bettingPerPage">{!! \WTemplate::displayPerPage($betFlowLogs->total(), $betFlowLogs->perPage()) !!}</td>
    @if($betFlowLogs->links()->toHtml())
       <td colspan="5" style="text-align: right; border: 0;" id="bettingPagination">{!! $betFlowLogs->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="7">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener('#bettingPagination a');
    });
</script>

