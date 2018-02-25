{{--转账记录列表--}}
@if($playerAccountLog->items())
    @foreach($playerAccountLog as $accountLog)
    <tr>
      <td>{!! $accountLog->log_id !!}</td>
      <td>{!! $accountLog->created_at !!}</td>
      <td>{!! $accountLog->fund_source !!}</td>
      <td>{!! $accountLog->amount !!}</td>
      <td>{!! $accountLog->remark !!}</td>
    </tr>
    @endforeach
    <tr>
        <td id="transferPerPage" colspan="2" style="text-align: left;border: 0;">{!! \WTemplate::displayPerPage($playerAccountLog->total(), $playerAccountLog->perPage()) !!}</td>
    @if($playerAccountLog->links()->toHtml())
        <td id="transferPagination" colspan="3" style="text-align: right;border: 0;">{!! $playerAccountLog->links() !!}</td>
    @endif
    </tr>
@else
  <tr><td colspan="5">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener("#transferPagination a");
    });
</script>
