<table class="table table-responsive" id="carrierDepositConfs-table">
    <thead>
    <th>Carrier Id</th>
    <th>Is Allow Deposit</th>
    <th>Is Allow Third Part Deposit Auto Arrival</th>
    <th>Unreview Deposit Record Limit</th>
    <th>Third Part Deposit Is Open</th>
    <th>Company Deposit Is Open</th>
    <th>Is Allow Company Deposit Auto Arrival</th>
    <th>Virtual Card Deposit Is Open</th>
    <th>Is Allow Virtual Card Deposit Auto Arrival</th>
    <th colspan="3">Action</th>
    </thead>
    <tbody>

    <tr>
        <td>{!! $carrierDepositConfs->carrier_id !!}</td>
        <td>{!! $carrierDepositConfs->is_allow_deposit !!}</td>
        <td>{!! $carrierDepositConfs->is_allow_third_part_deposit_auto_arrival !!}</td>
        <td>{!! $carrierDepositConfs->unreview_deposit_record_limit !!}</td>
        <td>{!! $carrierDepositConfs->third_part_deposit_is_open !!}</td>
        <td>{!! $carrierDepositConfs->company_deposit_is_open !!}</td>
        <td>{!! $carrierDepositConfs->is_allow_company_deposit_auto_arrival !!}</td>
        <td>{!! $carrierDepositConfs->virtual_card_deposit_is_open !!}</td>
        <td>{!! $carrierDepositConfs->is_allow_virtual_card_deposit_auto_arrival !!}</td>
        <td>
            {!! Form::open(['route' => ['carrierDepositConfs.destroy', $carrierDepositConfs->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('carrierDepositConfs.show', [$carrierDepositConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                <a href="{!! route('carrierDepositConfs.edit', [$carrierDepositConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>

    </tbody>
</table>