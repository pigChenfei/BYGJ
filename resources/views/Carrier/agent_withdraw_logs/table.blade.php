<table class="table table-responsive" id="agentWithdrawLogs-table">
    <thead>
        <th>Order Number</th>
        <th>Carrier Id</th>
        <th>Agent Id</th>
        <th>Apply Amount</th>
        <th>Fee Amount</th>
        <th>Finally Withdraw Amount</th>
        <th>Carrier Pay Channel</th>
        <th>Player Bank Card</th>
        <th>Status</th>
        <th>Reviewed At</th>
        <th>Withdraw Succeed At</th>
        <th>Operator</th>
        <th>Remark</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($agentWithdrawLogs as $agentWithdrawLog)
        <tr>
            <td>{!! $agentWithdrawLog->order_number !!}</td>
            <td>{!! $agentWithdrawLog->carrier_id !!}</td>
            <td>{!! $agentWithdrawLog->agent_id !!}</td>
            <td>{!! $agentWithdrawLog->apply_amount !!}</td>
            <td>{!! $agentWithdrawLog->fee_amount !!}</td>
            <td>{!! $agentWithdrawLog->finally_withdraw_amount !!}</td>
            <td>{!! $agentWithdrawLog->carrier_pay_channel !!}</td>
            <td>{!! $agentWithdrawLog->player_bank_card !!}</td>
            <td>{!! $agentWithdrawLog->status !!}</td>
            <td>{!! $agentWithdrawLog->reviewed_at !!}</td>
            <td>{!! $agentWithdrawLog->withdraw_succeed_at !!}</td>
            <td>{!! $agentWithdrawLog->operator !!}</td>
            <td>{!! $agentWithdrawLog->remark !!}</td>
            <td>
                {!! Form::open(['route' => ['agentWithdrawLogs.destroy', $agentWithdrawLog->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('agentWithdrawLogs.show', [$agentWithdrawLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('agentWithdrawLogs.edit', [$agentWithdrawLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>