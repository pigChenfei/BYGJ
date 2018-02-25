<table class="table table-responsive" id="carrierPasswordRecoverySiteConfs-table">
    <thead>
    <th>Carrier Id</th>
    <th>Is_Open_Email_Send_Function</th>
    <th>Smtp  Server</th>
    <th>Smtp Service Port</th>
    <th>Mail Sender</th>
    <th>Smtp Username</th>
    <th>Smtp Password</th>
    <th colspan="3">Action</th>
    </thead>
    <tbody>

    <tr>
        <td>{!! $carrierPasswordRecoverySiteConfs->carrier_id !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->is_open_email_send_function !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->smtp_server !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->smtp_service_port !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->mail_sender !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->smtp_username !!}</td>
        <td>{!! $carrierPasswordRecoverySiteConfs->smtp_password !!}</td>
        <td>
            {!! Form::open(['route' => ['carrierPasswordRecoverySiteConfs.destroy', $carrierPasswordRecoverySiteConfs->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('carrierPasswordRecoverySiteConfs.show', [$carrierPasswordRecoverySiteConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                <a href="{!! route('carrierPasswordRecoverySiteConfs.edit', [$carrierPasswordRecoverySiteConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>

    </tbody>
</table>