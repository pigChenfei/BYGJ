<aside>
    <ul class="aside list-unstyled agent-left">
        <li class="primary-tag main-tag"><a class="origin">加盟中心</a></li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'agentAccountCenters') !== false) active @endif">
            <a href="{!! route('agentAccountCenters.index') !!}"><i class="iconfont icon-personal_info"></i>账户资料<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li class="primary-tag {{ Route::is('agentPlayers*') ? 'active' : ''}}">
            <a href="javascript:;"><i class="iconfont icon-personal_manager"></i>会员管理<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" {{ Route::is('agentPlayers*') ?: 'hidden'}}>
                <li class="@if(Route::currentRouteName() == 'agentPlayers.index') active @endif"><a href="{!! route('agentPlayers.index') !!}">会员报表</a></li>
                @if(!is_null(\WinwinAuth::agentUser()->agentLevel) && \WinwinAuth::agentUser()->agentLevel->type == 2 && !is_null(\WinwinAuth::agentUser()->agentLevel->rebateFinancialFlowAgentBaseConf) &&  \WinwinAuth::agentUser()->agentLevel->rebateFinancialFlowAgentBaseConf->is_player_rebate_financial_adapt_carrier_conf == 0)
                    <li class="@if(Route::currentRouteName()=='agentPlayers.rebate') active @endif"><a href="{!! route('agentPlayers.rebate') !!}">洗码比例</a></li>
                @endif
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'agentPerformances') !== false) active @endif">
            <a href="{!! route('agentPerformances.index') !!}"><i class="iconfont icon-yeji"></i>业绩报表<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li class="primary-tag {{ Route::is('sms*') ? 'active' : ''}}">
            <a href="{{route('sms.index')}}"><i class="iconfont icon-message"></i>信息服务<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'agentWithdraws') !== false) active @endif">
            <a href="{!! route('agentWithdraws.index') !!}"><i class="iconfont icon-qukuan"></i>快速取款<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li class="primary-tag {{ Route::is('agentSettleReports*') ? 'active' : ''}}">
            <a href="javascript:;"><i class="iconfont icon-jiesuan"></i>结算报表<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" {{ Route::is('agentSettleReports*') ?: 'hidden'}}>
                <li class="@if(Route::currentRouteName() == 'agentSettleReports.index') active @endif"><a href="{!! route('agentSettleReports.index') !!}">佣金报表</a></li>
                @if(!is_null(\WinwinAuth::agentUser()->agentLevel) && \WinwinAuth::agentUser()->agentLevel->type == 1 && \WinwinAuth::agentUser()->agentLevel->is_multi_agent == 1)
                    <li class="@if(Route::currentRouteName()=='agentSettleReports.commission') active @endif"><a href="{!! route('agentSettleReports.commission') !!}">下级提成</a></li>
                @endif
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'agentWithdrawLogs') !== false) active @endif">
            <a href="{!! route('agentWithdrawLogs.index') !!}"><i class="iconfont icon-qkjl"></i>取款记录<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
    </ul>
</aside>
@section('scripts')
    <script src="{!! asset('./app/template_one/js/bootstrap-datetimepicker.min.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/bootstrap-datetimepicker.zh-CN.js') !!}"></script>
    <script>
        $(function(){
            //时间选择
            $(".start-time").datetimepicker({
                startDate: '1970-01-01 00:00',
                language: 'zh-CN',
                format: 'yyyy-mm-dd hh:ii',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 1,
                forceParse: 0
            }).on('changeDate', function(ev){
                var dat = new Date($(".start-time").val().split('-').join());
                $(".end-time").removeAttr('disabled');
                $(".end-time").datetimepicker('remove').datetimepicker({
                    startDate: dat,
                    language: 'zh-CN',
                    format: 'yyyy-mm-dd hh:ii',
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 1,
                    forceParse: 0
                })
            });
            //搜索查询
            $('.search-table').click(function () {
                var _this = $(this);
                var start_time = $('.start-time').val();
                var end_time = $('.end-time').val();
                $.ajax({
                    url:"{!! app('request')->url() !!}",
                    type: 'get',
                    async: true,
                    data: {
                        'start_time' : start_time,
                        'end_time' : end_time
                    },
                    dataType: 'html',
                    success: function(data){
                        $('#performance-container').html(data);
                    },
                    error: function(xhr){
                        layer.msg('请求失败，请重试',{
                            success: function(layero, index){
                                $(layero).css('top', '401.5px');
                            }
                        });
                    }
                })
            });
            $('.agent-left > li').click(function(e){
                e.stopPropagation();
                var _this = $(this);
                var _url = _this.find('ul');
                _this.siblings().each(function (index,value) {
                   if($(value).find('ul')){
                       $(value).find('ul').slideUp();
                   }
                });
                if(_this.find('ul')){
                    _url.slideDown();
                }
            })
        })
    </script>
@endsection