@extends('Agent.layouts.app')
@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('submit','.create_agent',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('agentOpenAccounts.createAgent'),'开户',";document.getElementsByClassName('create_agent')[0].reset();") !!}
            });
            $(document).on('submit','.create_player',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('agentOpenAccounts.createPlayer'),'开户',";document.getElementsByClassName('create_player')[0].reset();") !!}
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
                <li class="active"><a href="#tab_1" data-toggle="tab">代理开户</a></li>
                <li><a href="#tab_2" data-toggle="tab">会员开户</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    <div class="box-body">
                        <div class="col-sm-6">
                        @if($agentLevel != 5)
                        <form class="create_agent">
                        @if(isset($agent))
                        <input type="hidden" name="agent_level_id" value="{!! $agent->agent_level_id !!}">
                        <input type="hidden" name="parent_id" value="{!! $agent->id !!}">
                        @elseif(isset($agentWashCode))
                        <input type="hidden" name="agent_level_id" value="{!! $washCodeAgent->agent_level_id !!}">
                        <input type="hidden" name="parent_id" value="{!! $washCodeAgent->id !!}">
                        @endif
                            <table class="table table-bordered table-hover table-responsive">
                                <tr>
                                    <th>代理账号</th>
                                    <td>
                                        <input class="form-control" name="username" type="text" value="">
                                    </td>
                                    <td>
                                        <span>账号由(4～16位)小写字母或数字组成</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>请输入密码</th>
                                    <td>
                                        <input class="form-control" name="password" type="password" value="">
                                    </td>
                                    <td>
                                        <span>密码为(6～16位)字母或数字组成</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>请确认密码</th>
                                    <td>
                                        <input class="form-control" name="confirm_password" type="password" value="">
                                    </td>
                                    <td>
                                        <span>请确认密码</span>
                                    </td>
                                </tr>
                                @if(isset($agentCommission))
                                <tr>
                                    <th> {!! Form::label('commission_ratio', '下级代理佣金提成比例') !!}</th>
                                    <td>
                                        {!! Form::number('commission_ratio', $agentCommission, ['class' => 'form-control','min'=> 0 ,'max' =>$agentCommission]) !!}
                                    </td>
                                    <td>
                                        <span>提成比例不得超出本身</span>
                                    </td>
                                </tr>
                                @elseif(isset($agentWashCode))
                                @foreach($agentWashCode as $item)
                                    <tr>
                                        <input type="hidden" name="game_plat_id[]" value="{!! $item->carrier_game_plat_id !!}">
                                        <th> {!! Form::label('agent_rebate_financial_flow_rate[]', $item->gamePlat->game_plat_name .'洗码比例') !!}</th>
                                        <td>
                                            {!! Form::number('agent_rebate_financial_flow_rate[]', $item->agent_rebate_financial_flow_rate, ['class' => 'form-control','step' => '0.01','min'=> 0 ,'max' =>$item->agent_rebate_financial_flow_rate]) !!}
                                        </td>
                                        <td>
                                            <span>洗码比例不得超出本身</span>
                                        </td>
                                    </tr>
                                @endforeach

                                @else

                                @endif

                            </table>
                            <div style="text-align: center;">
                                {!! Form::button('确认提交', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                            </div>
                            <div class="overlay" id="overlay" style="display: none">
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                        </form>
                        @else
                            <h2 style="color: red">当前代理暂不支持代理开户功能,有疑问请联系在线客服!</h2>
                        @endif
                    </div>
                    </div>

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <div class="box-body">
                        <div class="col-sm-6">
                        <form class="create_player"  method="post">
                        <input type="hidden" name="agent_id" value="{!! WinwinAuth::agentUser()->id !!}">
                            <table class="table table-bordered table-hover table-responsive">
                                <tr>
                                    <th>玩家账号</th>
                                    <td>
                                        <input class="form-control" name="user_name" type="text" value="">
                                    </td>
                                    <td>
                                        <span>账号由(4～16位)小写字母或数字组成</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>请输入密码</th>
                                    <td>
                                        <input class="form-control" name="password" type="password" value="">
                                    </td>
                                    <td>
                                        <span>密码为(6～16位)字母或数字组成</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>请确认密码</th>
                                    <td>
                                        <input class="form-control" name="confirm_password" type="password" value="">
                                    </td>
                                    <td>
                                        <span>请确认密码</span>
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: center;">
                                {!! Form::button('确认提交', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                            </div>
                            <div class="overlay" id="overlay" style="display: none">
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
