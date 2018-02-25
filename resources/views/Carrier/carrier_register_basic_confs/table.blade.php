<table class="table table-responsive" id="carrierWebSiteConfs-table">
    <thead>
    <th>Carrier Id</th>
    <th>Player Birthday Conf Status</th>
    <th>Player Realname Conf Status</th>
    <th>Player Email Conf Status</th>
    <th>Player Phone Conf Status</th>
    <th>Player Qq Conf Status</th>
    <th>Player Wechat Conf Status</th>
    <th>Player Consignee Conf Status</th>
    <th>Player Receiving Address Conf Status</th>
    <th>Agent Type Conf Status</th>
    <th>Agent Realname Conf Status</th>
    <th>Agent Birthday Conf Status</th>
    <th>Agent Email Conf Status</th>
    <th>Agent Phone Conf Status</th>
    <th>Agent Qq Conf Status</th>
    <th>Agent Skype Conf Status</th>
    <th>Agent Wechat Conf Status</th>
    <th>Agent Promotion Mode Conf Status</th>
    <th>Agent Promotion Url Conf Status</th>
    <th>Agent Promotion Idea Conf Status</th>
    <th colspan="3">Action</th>
    </thead>
    <tbody>

        <tr>
            <td>{!! $carrierRegisterBasicConfs->carrier_id !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_birthday_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_realname_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_email_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_phone_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_qq_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_wechat_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_consignee_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->player_receiving_address_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_type_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_realname_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_birthday_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_email_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_phone_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_qq_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_skype_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_promotion_mode_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_promotion_url_conf_status !!}</td>
            <td>{!! $carrierRegisterBasicConfs->agent_promotion_idea_conf_status !!}</td>
            <td>
                {!! Form::open(['route' => ['carrierRegisterBasicConfs.destroy', $carrierRegisterBasicConfs->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('carrierRegisterBasicConfs.show', [$carrierRegisterBasicConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('carrierRegisterBasicConfs.edit', [$carrierRegisterBasicConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
   
    </tbody>
</table>