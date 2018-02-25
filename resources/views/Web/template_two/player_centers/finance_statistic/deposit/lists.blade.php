{{--存款记录列表--}}
@if($playerDepositPaylog->items())
  @foreach($playerDepositPaylog as $playerDeposit)
      <tr>
          <td>{!! $playerDeposit->pay_order_number !!}</td>
          <td>{!! $playerDeposit->created_at !!}</td>
          <td>{!! $playerDeposit->carrierPayChannel->payChannel->payChannelType->type_name !!}</td>
          <td>{!! $playerDeposit->amount !!}</td>
          <td>{!! $playerDeposit->fee_amount !!}</td>
          <td>{!! $playerDeposit->benefit_amount !!}</td>
          <td>{!! $playerDeposit->finally_amount !!}</td>
          <td>{!! $playerDeposit->operate_time !!}</td>
          <td>{!! $playerDeposit::orderStatusMeta()[$playerDeposit->status] !!}</td>
      </tr>
  @endforeach
  <tr>
      <td colspan="2" style="text-align: left;border: 0;" id="depositPerPage">{!! \WTemplate::displayPerPage($playerDepositPaylog->total(), $playerDepositPaylog->perPage()) !!}</td>
  @if($playerDepositPaylog->links()->toHtml())
      <td colspan="7" style="text-align: right;border: 0;" id="depositPagination">{!! $playerDepositPaylog->links() !!}</td>
  @endif
  </tr>
@else
  <tr><td colspan="9">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener("#depositPagination a");
    });
</script>

