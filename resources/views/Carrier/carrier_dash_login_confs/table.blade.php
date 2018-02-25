<table class="table table-responsive" id="carrierDashLoginConfs-table">
    <thead>
        <th>Carrier Id</th>
        <th>Forbidden Login Comment</th>
        <th>Carrier Login Failed Count When Locked</th>
        <th>Is Allow Player Login</th>
        <th>Is Allow Player Register</th>
        <th>Player Login Failed Count When Locked</th>
        <th>Player Register Forbidden User Names</th>
        <th>Player Forbidden Login Comment</th>
        <th>Player Forbidden Register Comment</th>
        <th>Is Check Exists Real User Name</th>
        <th>Is Allow User Edit Self Info</th>
        <th>Is Allow User Withdraw With Password</th>
        <th>Is User Register Base Info Required</th>
        <th>Is User Register Telephone Required</th>
        <th>Is User Register Email Required</th>
        <th>Is Allow Agent Login</th>
        <th>Is Allow Agent Register</th>
        <th>Agent Login Failed Count When Locked</th>
        <th>Agent Register Forbidden User Names</th>
        <th>Agent Forbidden Login Comment</th>
        <th>Agent Forbidden Register Comment</th>
        <th>Is Allow Agent Edit Self Info</th>
        <th>Is Allow Agent Withdraw With Password</th>
        <th>Is Agent Register Base Info Required</th>
        <th>Is Agent Register Telephone Required</th>
        <th>Is Agent Register Email Required</th>
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
            <td>{!! $carrierDashLoginConfs->carrier_id !!}</td>
            <td>{!! $carrierDashLoginConfs->forbidden_login_comment !!}</td>
            <td>{!! $carrierDashLoginConfs->carrier_login_failed_count_when_locked !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_player_login !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_player_register !!}</td>
            <td>{!! $carrierDashLoginConfs->player_login_failed_count_when_locked !!}</td>
            <td>{!! $carrierDashLoginConfs->player_register_forbidden_user_names !!}</td>
            <td>{!! $carrierDashLoginConfs->player_forbidden_login_comment !!}</td>
            <td>{!! $carrierDashLoginConfs->player_forbidden_register_comment !!}</td>
            <td>{!! $carrierDashLoginConfs->is_check_exists_real_user_name !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_user_edit_self_info !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_user_withdraw_with_password !!}</td>
            <td>{!! $carrierDashLoginConfs->is_user_register_base_info_required !!}</td>
            <td>{!! $carrierDashLoginConfs->is_user_register_telephone_required !!}</td>
            <td>{!! $carrierDashLoginConfs->is_user_register_email_required !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_agent_login !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_agent_register !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_login_failed_count_when_locked !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_register_forbidden_user_names !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_forbidden_login_comment !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_forbidden_register_comment !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_agent_edit_self_info !!}</td>
            <td>{!! $carrierDashLoginConfs->is_allow_agent_withdraw_with_password !!}</td>
            <td>{!! $carrierDashLoginConfs->is_agent_register_base_info_required !!}</td>
            <td>{!! $carrierDashLoginConfs->is_agent_register_telephone_required !!}</td>
            <td>{!! $carrierDashLoginConfs->is_agent_register_email_required !!}</td>
            <td>{!! $carrierDashLoginConfs->player_birthday_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_realname_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_email_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_phone_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_qq_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_wechat_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_consignee_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->player_receiving_address_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_type_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_realname_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_birthday_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_email_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_phone_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_qq_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_skype_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_promotion_mode_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_promotion_url_conf_status !!}</td>
            <td>{!! $carrierDashLoginConfs->agent_promotion_idea_conf_status !!}</td>
            <td>
                {!! Form::open(['route' => ['carrierDashLoginConfs.destroy', $carrierDashLoginConfs->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('carrierDashLoginConfs.show', [$carrierDashLoginConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('carrierDashLoginConfs.edit', [$carrierDashLoginConfs->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>

    </tbody>
</table>