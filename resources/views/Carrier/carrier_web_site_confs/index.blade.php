@extends('Carrier.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
@endsection

@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('submit','.web_site_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierWebSiteConfs.update',1),'保存') !!}
            })
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
                <li class="active"><a href="#tab_1" data-toggle="tab">基本设置</a></li>
                <li><a href="#tab_2" data-toggle="tab">网站内容</a></li>
                <li><a href="#tab_14" data-toggle="tab">关于我们</a></li>
                <li><a href="#tab_3" data-toggle="tab">常见问题</a></li>
                <li><a href="#tab_15" data-toggle="tab">责任博彩</a></li>
                <li><a href="#tab_9" data-toggle="tab">联系我们</a></li>
                <li><a href="#tab_8" data-toggle="tab">取款说明</a></li>
                {{--<li><a href="#tab_11" data-toggle="tab">代理首页</a></li>--}}
                {{--<li><a href="#tab_10" data-toggle="tab">代理合营模式</a></li>--}}
                <li><a href="#tab_7" data-toggle="tab">代理合作协议</a></li>
                <li><a href="#tab_6" data-toggle="tab">代理佣金政策</a></li>
                <li><a href="#tab_12" data-toggle="tab">手机端关于我们</a></li>
                <li><a href="#tab_13" data-toggle="tab">手机端联系我们</a></li>
                {{--<li><a href="#tab_4" data-toggle="tab">隐私政策</a></li>--}}
                {{--<li><a href="#tab_5" data-toggle="tab">规则条款</a></li>--}}
                {{--<li><a href="#tab_16" data-toggle="tab">在线客服管理</a></li>--}}{{--//总后台配置--}}

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
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                            <input type="hidden" name="update_type" value="base_info">
                            @include('Carrier.carrier_web_site_confs.setting_base_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="content_info">
                        @include('Carrier.carrier_web_site_confs.setting_content_info')
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="tab-pane" id="tab_14">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="about_us">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'about_us','name' => 'about_us','defaultContent' => $carrierWebSiteConf->about_us()])
                        </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="common_questions">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'common_questions','name' => 'common_questions','defaultContent' => $carrierWebSiteConf->common_question()])
                        </div>

                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="tab-pane" id="tab_15">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="duty">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'duty','name' => 'duty','defaultContent' => $carrierWebSiteConf->duty()])
                        </div>

                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_4">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="privacy_policy">
                        <div class="form-group col-sm-12">
                        @include('Components.Editor.index',['id' => 'privacy_policy','name' => 'privacy_policy','defaultContent' => $carrierWebSiteConf->privacy_policy()])
                            </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_5">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="rule_clause">
                        <div class="form-group col-sm-12">
                        @include('Components.Editor.index',['id' => 'rule_clause','name' => 'rule_clause','defaultContent' => $carrierWebSiteConf->rule_clause()])
                            </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_6">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="commission_policy">
                        <div class="form-group col-sm-12">
                        @include('Components.Editor.index',['id' => 'commission_policy','name' => 'commission_policy','defaultContent' => $carrierWebSiteConf->commission_policy()])
                            </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_7">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="jointly_operated_agreement">
                        <div class="form-group col-sm-12">
                        @include('Components.Editor.index',['id' => 'jointly_operated_agreement','name' => 'jointly_operated_agreement','defaultContent' => $carrierWebSiteConf->jointly_operated_agreement()])
                            </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_8">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="with_draw_comment">
                        <div class="form-group col-sm-12">
                        @include('Components.Editor.index',['id' => 'with_draw_comment','name' => 'with_draw_comment','defaultContent' => $carrierWebSiteConf->with_draw_comment()])
                            </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_9">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="contact_us">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'contact_us','name' => 'contact_us','defaultContent' => $carrierWebSiteConf->contact_us()])
                        </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                {{--合营模式--}}
                <div class="tab-pane" id="tab_10">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="agent_pattern">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'agent_pattern','name' => 'agent_pattern','defaultContent' => $carrierWebSiteConf->agent_pattern()])
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                {{--代理首页--}}
                <div class="tab-pane" id="tab_11">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="agent_index">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'agent_index','name' => 'agent_index','defaultContent' => $carrierWebSiteConf->agent_index()])
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                {{--手机端关于我们--}}
                <div class="tab-pane" id="tab_12">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="mobile_about">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'mobile_about','name' => 'mobile_about','defaultContent' => $carrierWebSiteConf->mobile_about()])
                        </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                {{--手机端联系我们--}}
                <div class="tab-pane" id="tab_13">
                    <div class="box-body">
                        {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}
                        <input type="hidden" name="update_type" value="mobile_contact">
                        <div class="form-group col-sm-12">
                            @include('Components.Editor.index',['id' => 'mobile_contact','name' => 'mobile_contact','defaultContent' => $carrierWebSiteConf->mobile_contact()])
                        </div>
                        <div class="form-group col-sm-12" style="color:red">
                            温馨提示：修改问题，请点击编辑器左上角图标，按照模板添加或修改，或者自行修改添加，并返回本界面保存
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                {{--在线客服--}}
                {{--<div class="tab-pane" id="tab_16">--}}
                    {{--<div class="box-body">--}}
                        {{--{!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', 1], 'method' => 'patch','class' => 'web_site_form']) !!}--}}
                        {{--<input type="hidden" name="update_type" value="online_service">--}}
                        {{--<div class="form-group col-sm-12">--}}
                            {{--@include('Components.Editor.index',['id' => 'online_service','name' => 'online_service','defaultContent' => $carrierWebSiteConf->online_service()])--}}
                        {{--</div>--}}
                        {{--<div class="form-group col-sm-12">--}}
                            {{--{!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}--}}
                        {{--</div>--}}
                        {{--{!! Form::close() !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>

@endsection

