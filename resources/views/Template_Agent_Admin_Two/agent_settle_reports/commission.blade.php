@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--会员报表-->
    <article class="memb-excl">
        <div class="art-title"></div>
        <div class="art-body">
            <div class="query">
                <label for="">代理层级：</label>
                <div class="dropdown" style="display: inline-block;float: unset">
                    <button class="btn dropdown-toggle level" data-value="{{$parameter['level']}}" id="dn-draw" data-toggle="dropdown"/><i>@if($parameter['level']){{$arr[$parameter['level']]}}@else全部@endif</i></button>
                    <span class="caret"></span>
                    <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                        <li role="presentation" class="transferFrom">
                            <a role="menuitem" tabindex="-1" data-value="0"  href="javascript:void(0)">全部</a>
                        </li>
                        @foreach($arr as $k => $v)
                            <li role="presentation" class="transferFrom">
                                <a role="menuitem" tabindex="-1" data-value='{!! $k !!}' href="javascript:void(0)">下{{ $v }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <button class="btn btn-warning record-search"><i>查询</i></button>
            </div>
            <div class="table-wrap">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">结算期</th>
                        <th class="text-center">提成代理</th>
                        <th class="text-center">提成金额</th>
                        <th class="text-center">提成比例（%）</th>
                        <th class="text-center">提成时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($carrierActivityAudit as $item)
                        <tr>
                            <td title="{!! $item->settlePeriods->settlePeriods->periods !!}">{!! $item->settlePeriods->settlePeriods->periods !!}</td>
                            <td>{!! $item->outAgent->username !!}</td>
                            <td>{!! $item->commission_money !!}</td>
                            <td>{!! $item->commission_rate !!}</td>
                            <td>{!! $item->created_at !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(!$carrierActivityAudit->total())
                <div class="norecord table"><div class="table-cell">暂无记录</div></div>
            @else
                <div class="pagenation-container clearfix">
                    <div class="pageinfo float-left">
                        <p>共<i class="game-count">{{ $carrierActivityAudit->total() }}</i>项，共<i class="pagenum">{{ $carrierActivityAudit->lastPage() }}</i>页，每页<i class="onpagenum">{{ $carrierActivityAudit->perPage() }}</i>个</p>
                    </div>
                    {{ $carrierActivityAudit->appends($parameter)->links('Web.template_one.pageStyle.template_one', 1) }}
                </div>
            @endif
        </div>
    </article>

@endsection
@section('script')
    <script>
        $(function () {
            //选择条件
            $('.transferFrom').click(function(e){
                e.preventDefault();
                var _this = $(this);
                var value = _this.find('a').attr('data-value');
                var name = _this.find('a').html();
                _this.parent().prev().prev().attr('data-value', value).find('i').html(name);
            });
            var url = "{!! app('request')->url() !!}";
            $('.record-search').click(function(){
                var level = $('.level').attr('data-value');
                window.location.href = url+'?level='+level;
            });
        })
    </script>
@endsection