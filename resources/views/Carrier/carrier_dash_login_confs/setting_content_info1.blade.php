
<div class="row">
    <div class="col-sm-6">

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_player_login', '允许会员登录').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_player_login" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_player_login == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_player_register', '允许会员注册').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_player_register" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_player_register == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>


        <!-- Site Description Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_login_failed_count_when_locked', '会员登录错误几次锁定') !!}
            {!! Form::number('player_login_failed_count_when_locked', null, ['class' => 'form-control']) !!}
        </div>


        <!-- Net Bank Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_register_forbidden_user_names', '会员注册限制账号') !!}
            {!! Form::text('player_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Atm Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_forbidden_login_comment', '会员禁止登录原因') !!}
            {!! Form::text('player_forbidden_login_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_forbidden_register_comment', '会员禁止注册原因') !!}
            {!! Form::text('player_forbidden_register_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_check_exists_real_user_name', '检测真实姓名是否同名').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_check_exists_real_user_name" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_check_exists_real_user_name == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_user_edit_self_info', '会员是否可以编辑基本信息').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_user_edit_self_info" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_user_edit_self_info == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_user_withdraw_with_password', '会员取款是否需要取款密码').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_user_withdraw_with_password" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_user_withdraw_with_password == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_user_register_base_info_required', '会员注册是否基本信息必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_user_register_base_info_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_user_register_base_info_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_user_register_telephone_required', '会员注册是否手机号必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_user_register_telephone_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_user_register_telephone_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('is_user_register_email_required', '会员注册email必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_user_register_email_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_user_register_email_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

    </div>
    <div class="col-sm-6">

        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_agent_login', '允许代理登录').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_agent_login" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_agent_login == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_agent_register', '允许代理注册').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_agent_register" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_agent_register == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Site Description Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_login_failed_count_when_locked', '代理登录失败几次锁定') !!}
            {!! Form::text('agent_login_failed_count_when_locked', null, ['class' => 'form-control']) !!}
        </div>


        <!-- Net Bank Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_register_forbidden_user_names', '代理注册限制账号') !!}
            {!! Form::text('agent_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
        </div>


        <!-- Atm Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_forbidden_login_comment', '代理禁止登录原因') !!}
            {!! Form::text('agent_forbidden_login_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_forbidden_register_comment', '代理禁止注册原因') !!}
            {!! Form::text('agent_forbidden_register_comment', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_agent_edit_self_info', '允许代理编辑自己的基本信息').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_agent_edit_self_info" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_agent_edit_self_info == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_allow_agent_withdraw_with_password', '代理取款是否需要取款密码').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_allow_agent_withdraw_with_password" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_allow_agent_withdraw_with_password == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_agent_register_base_info_required', '代理注册是否基本信息必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_agent_register_base_info_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_agent_register_base_info_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_agent_register_telephone_required', '代理注册是否手机号必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_agent_register_telephone_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_agent_register_telephone_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-12">
            {!! Form::label('is_agent_register_email_required', '是否代理注册email必填').Form::required_pin() !!}
            <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::statusMeta() ?>
            <select name="is_agent_register_email_required" class="form-control carrier_bank_cards_select2" style="width: 100%;">
                @foreach($statusDic as $key => $value)
                    @if(isset($carrierDashLoginConfs) && $carrierDashLoginConfs instanceof \App\Models\Conf\CarrierDashLoginConf && $carrierDashLoginConfs->is_agent_register_email_required == $key)
                        <option value="{!! $key !!}" selected>{!! $value !!}</option>
                    @else
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endif
                @endforeach
            </select>
        </div>

    </div>
</div>



<div class="form-group col-sm-12">
    {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>


