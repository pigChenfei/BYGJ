@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--取款记录-->
    <article class="saverecord">
        <div class="art-title"></div>
        <div class="art-body">
            <h4 class="art-tit">取款记录</h4>
            <div class="query">
                <label for="">开始时间：</label>
                <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                <label for="">结束时间：</label>
                <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                <label for="">状态：</label>
                <div class="dropdown" style="display: inline-block;float: left;">
                    <button class="btn dropdown-toggle status" data-value="@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{app('request')->input('status')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{$arr[app('request')->input('status')]}}@else全部@endif</i></button>
                    <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                        <li role="presentation" class="transferFrom">
                            <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                        </li>
                        @foreach($arr as $k => $v)
                            <li role="presentation" class="transferFrom">
                                <a role="menuitem" tabindex="-1" data-value='{!! $k !!}' href="javascript:void(0)">{{ $v }}</a>
                            </li>
                        @endforeach

                    </ul>
                </div>
                <button class="btn btn-warning float-right record-search"><i>查询</i></button>
            </div>
            <div class="table-wrap">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">取款编号</th>
                        <th class="text-center">取款时间</th>
                        <th class="text-center">取款金额</th>
                        <th class="text-center">手续费</th>
                        <th class="text-center">实际出款</th>
                        <th class="text-center">备注</th>
                        <th class="text-center">状态</th>
                    </tr>
                    </thead>
                    <div id="performance-container">
                        @include(\WinwinAuth::agentUser()->template_agent_admin.'.agent_withdraw_log.table')
                    </div>
                </table>
            </div>
            @if(!$agentWithdrawLog->total())
                <div class="norecord table"><div class="table-cell">暂无记录</div></div>
            @else
                <div class="pagenation-container clearfix">
                    <div class="pageinfo float-left">
                        <p>共<i class="game-count">{{ $agentWithdrawLog->total() }}</i>项，共<i class="pagenum">{{ $agentWithdrawLog->lastPage() }}</i>页，每页<i class="onpagenum">{{ $agentWithdrawLog->perPage() }}</i>个</p>
                    </div>
                    {{ $agentWithdrawLog->appends($parameter)->links('Web.template_one.pageStyle.template_one', 1) }}
                </div>
            @endif
        </div>
    </article>
@endsection
@section('script')
    <script>
        $(function () {
            //选择条件
            $(document).on('click', '.transferFrom', function(e){
                e.preventDefault();
                var _this = $(this);
                var value = _this.find('a').attr('data-value');
                var name = _this.find('a').html();
                _this.parent().prev().prev().attr('data-value', value).find('i').html(name);
            });
            // 报表搜索查询
            var url = "{!! app('request')->url() !!}";
            $(document).on('click','.record-search',function(event){
                event.preventDefault();
                event.stopPropagation();
                var start_time = $('.start-time').val();
                var end_time = $('.end-time').val();
                var status = $('.status').attr('data-value');
                window.location.href = url+'?status='+status+'&start_time='+start_time+'&end_time='+end_time;
            });
        })
    </script>
@endsection