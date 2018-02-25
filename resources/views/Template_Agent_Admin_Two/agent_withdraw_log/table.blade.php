
<tbody>
@foreach($agentWithdrawLog as $v)
    <tr>
        <td>{!! $v->order_number !!}</td>
        <td title="{{$v->created_at}}">{!! substr($v->created_at,0, 10) !!}</td>
        <td>{!! $v->apply_amount !!}</td>
        <td>{!! $v->fee_amount !!}</td>
        <td>{!! $v->finally_withdraw_amount !!}</td>
        <td title="{!! $v->remark !!}">{!! str_limit($v->remark, 20) !!}</td>
        <td>{!! $v->statusMeta()[$v->status] !!}</td>
    </tr>
@endforeach
</tbody>