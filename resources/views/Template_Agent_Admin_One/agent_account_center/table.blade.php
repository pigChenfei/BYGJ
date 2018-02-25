<table class="table table-responsive" id="agentCenters-table">
    <thead>
        <th>Username</th>
        <th>Password</th>
        <th>Realname</th>
        <th>Agent Level Id</th>
        <th>Amount</th>
        <th>Player Number</th>
        <th>Skype</th>
        <th>Qq</th>
        <th>Wechat</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Promotion Code</th>
        <th>Card Account</th>
        <th>Card Type</th>
        <th>Card Owner Name</th>
        <th>Card Birth Place</th>
        <th>Parent Id</th>
        <th>Carrier Id</th>
        <th>Status</th>
        <th>Audit Status</th>
        <th>Is Default</th>
        <th>Customer Remark</th>
        <th>Customer Time</th>
        <th>Login Time</th>
        <th>Register Ip</th>
        <th>Remember Token</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($agentCenters as $agentCenter)
        <tr>
            <td>{!! $agentCenter->username !!}</td>
            <td>{!! $agentCenter->password !!}</td>
            <td>{!! $agentCenter->realname !!}</td>
            <td>{!! $agentCenter->agent_level_id !!}</td>
            <td>{!! $agentCenter->amount !!}</td>
            <td>{!! $agentCenter->player_number !!}</td>
            <td>{!! $agentCenter->skype !!}</td>
            <td>{!! $agentCenter->qq !!}</td>
            <td>{!! $agentCenter->wechat !!}</td>
            <td>{!! $agentCenter->mobile !!}</td>
            <td>{!! $agentCenter->email !!}</td>
            <td>{!! $agentCenter->promotion_code !!}</td>
            <td>{!! $agentCenter->card_account !!}</td>
            <td>{!! $agentCenter->card_type !!}</td>
            <td>{!! $agentCenter->card_owner_name !!}</td>
            <td>{!! $agentCenter->card_birth_place !!}</td>
            <td>{!! $agentCenter->parent_id !!}</td>
            <td>{!! $agentCenter->carrier_id !!}</td>
            <td>{!! $agentCenter->status !!}</td>
            <td>{!! $agentCenter->audit_status !!}</td>
            <td>{!! $agentCenter->is_default !!}</td>
            <td>{!! $agentCenter->customer_remark !!}</td>
            <td>{!! $agentCenter->customer_time !!}</td>
            <td>{!! $agentCenter->login_time !!}</td>
            <td>{!! $agentCenter->register_ip !!}</td>
            <td>{!! $agentCenter->remember_token !!}</td>
            <td>
                {!! Form::open(['route' => ['agentCenters.destroy', $agentCenter->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('agentCenters.show', [$agentCenter->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('agentCenters.edit', [$agentCenter->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>