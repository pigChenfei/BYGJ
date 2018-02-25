@extends('Carrier.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('submit','.web_site_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierDashLoginConfs.update',1),'保存') !!}
            });
            $(document).on('submit','.deposit_site_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierDepositConfs.update',1),'保存') !!}
            });
            $(document).on('submit','.withdraw_site_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierWithdrawConfs.update',1),'保存') !!}
            });
            $(document).on('submit','.password_recovery_site_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierPasswordRecoverySiteConfs.update',1),'保存') !!}
            });
        })
    </script>
@endsection

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="clearfix"></div>


        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">系统参数设置</a></li>
                <li><a href="#tab_2" data-toggle="tab">登录注册信息设置</a></li>
                <li><a href="#tab_3" data-toggle="tab">系统存款设置</a></li>
                <li><a href="#tab_4" data-toggle="tab">系统取款设置</a></li>
                <li><a href="#tab_5" data-toggle="tab">找回密码设置</a></li>
                {{--<li class="dropdown">--}}
                {{--<a class="dropdown-toggle" data-toggle="dropdown" href="#">--}}
                {{--其他设置 <span class="caret"></span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li role="presentation"><a role="menuitem" data-toggle="tab" href="#tab_8">取款说明</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>--}}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="box-body">
                        {!! Form::model($carrierDashLoginConfs, ['route' => ['carrierDashLoginConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="base_info">
                        @include('Carrier.carrier_dash_login_confs.setting_base_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <div class="box-body">
                        {!! Form::model($carrierDashLoginConfs, ['route' => ['carrierDashLoginConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="content_info">
                        @include('Carrier.carrier_dash_login_confs.setting_content_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    <div class="box-body">
                        {!! Form::model($carrierDepositConfs, ['route' => ['carrierDepositConfs.update', 1], 'method' => 'patch','class' => 'deposit_site_form']) !!}
                        <input type="hidden" name="update_type" value="deposit_info">
                        @include('Carrier.carrier_deposit_confs.setting_deposit_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4">
                    <div class="box-body">
                        {!! Form::model($carrierWithdrawConfs, ['route' => ['carrierWithdrawConfs.update', 1], 'method' => 'patch','class' => 'withdraw_site_form']) !!}
                        <input type="hidden" name="update_type" value="withdraw_info">
                        @include('Carrier.carrier_withdraw_confs.setting_withdraw_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_5">
                    <div class="box-body">
                        {!! Form::model($carrierPasswordRecoverySiteConfs, ['route' => ['carrierPasswordRecoverySiteConfs.update', 1], 'method' => 'patch','class' => 'password_recovery_site_form']) !!}
                        <input type="hidden" name="update_type" value="setting_password_recovery_info">
                        @include('Carrier.carrier_password_recovery_site_confs.setting_password_recovery_info')
                        {!! Form::close() !!}
                    </div>
                </div>



            </div>
        </div>
    </div>

@endsection

