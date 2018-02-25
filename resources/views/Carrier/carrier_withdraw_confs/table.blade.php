<table class="table table-responsive" id="carrierDepositConfs-table">
    <thead>
    <th>Carrier Id</th>
    <th>Is Allow Player Withdraw</th>
    <th>Is Allow Player Withdraw Decimal</th>
    <th>Player Day Withdraw Success Limit Count</th>
    <th>Player Day Withdraw Max Sum</th>
    <th>Player Once Withdraw Max Sum</th>
    <th>Player Once Withdraw Min Sum</th>
    <th>Is Diaplay Flow Water Check</th>
    <th>Is Check Flow Water When Withdraw</th>
    <th>Is Allow Agent Withdraw</th>
    <th>Is Allow Agent Withdraw Decimal</th>
    <th>Agent Day Withdraw Success Limit Count</th>
    <th>Agent Day Withdraw Max Sum</th>
    <th>Agent Once Withdraw Max Sum</th>
    <th>Agent Once Withdraw Min Sum</th>
    <th colspan="3">Action</th>
    </thead>
    <tbody>

    <tr>
        <td>{!! $carrierWithdrawConfs->carrier_id !!}</td>
        <td>{!! $carrierWithdrawConfs->is_allow_player_withdraw !!}</td>
        <td>{!! $carrierWithdrawConfs->is_allow_player_withdraw_decimal !!}</td>
        <td>{!! $carrierWithdrawConfs->player_day_withdraw_success_limit_count !!}</td>
        <td>{!! $carrierWithdrawConfs->player_day_withdraw_max_sum !!}</td>
        <td>{!! $carrierWithdrawConfs->player_once_withdraw_max_sum !!}</td>
        <td>{!! $carrierWithdrawConfs->player_once_withdraw_min_sum !!}</td>
        <td>{!! $carrierWithdrawConfs->is_diaplay_flow_water_check !!}</td>
        <td>{!! $carrierWithdrawConfs->is_check_flow_water_when_withdraw !!}</td>
        <td>{!! $carrierWithdrawConfs->is_allow_agent_withdraw !!}</td>
        <td>{!! $carrierWithdrawConfs->is_allow_agent_withdraw_decimal !!}</td>
        <td>{!! $carrierWithdrawConfs->agent_day_withdraw_success_limit_count !!}</td>
        <td>{!! $carrierWithdrawConfs->agent_day_withdraw_max_sum !!}</td>
        <td>{!! $carrierWithdrawConfs->agent_once_withdraw_max_sum !!}</td>
        <td>{!! $carrierWithdrawConfs->agent_once_withdraw_min_sum !!}</td>
        <td>
            {!! Form::open(['route' => ['carrierWithdrawConfs.destroy', $carrierWithdrawConfs->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('carrierWithdrawConfs.show', [$carrierWithdrawConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                <a href="{!! route('carrierWithdrawConfs.edit', [$carrierWithdrawConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>

    </tbody>
</table>