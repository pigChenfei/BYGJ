<table class="table table-responsive" id="agentBankCards-table">
    <thead>
        <th>Carrier Id</th>
        <th>Agent Id</th>
        <th>Card Account</th>
        <th>Card Type</th>
        <th>Card Owner Name</th>
        <th>Card Birth Place</th>
        <th>Status</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($agentBankCards as $agentBankCard)
        <tr>
            <td>{!! $agentBankCard->carrier_id !!}</td>
            <td>{!! $agentBankCard->agent_id !!}</td>
            <td>{!! $agentBankCard->card_account !!}</td>
            <td>{!! $agentBankCard->card_type !!}</td>
            <td>{!! $agentBankCard->card_owner_name !!}</td>
            <td>{!! $agentBankCard->card_birth_place !!}</td>
            <td>{!! $agentBankCard->status !!}</td>
            <td>
                {!! Form::open(['route' => ['agentBankCards.destroy', $agentBankCard->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('agentBankCards.show', [$agentBankCard->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('agentBankCards.edit', [$agentBankCard->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>