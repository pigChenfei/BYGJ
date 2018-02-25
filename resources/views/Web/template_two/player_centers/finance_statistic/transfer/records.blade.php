@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
        @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
        <!--转账记录-->
            <article class="transfer-record">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">转账记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <button class="btn btn-warning record-search"><i>查询</i></button>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th class="text-center">转账编号</th>
                                <th class="text-center">转账时间</th>
                                <th width="22.2%" colspan="3" class="text-center">转账明细</th>
                                <th class="text-center">转账金额</th>
                                <th colspan="3" class="text-center">明细</th>
                            </tr>
                            </thead>
                            @foreach($playerAccountLog as $accountLog)
                            <tbody>
                                <!-- <td>{!! $accountLog->remark !!}</td> -->
                                <tr>
                                    <td rowspan="4">{!! $accountLog->log_id !!}</td>
                                    <td rowspan="4">{!! $accountLog->created_at !!}</td>
                                    <td rowspan="4" class="t-r">{!! explode(' 转到 ',$accountLog->fund_source)[0] !!}</td>
                                    <td rowspan="4" width="6.15%"><span class="glyphicon glyphicon-arrow-right" style="transform:translateY(-2px);"></span></td>
                                    <td rowspan="4" class="t-l">{!! explode(' 转到 ',$accountLog->fund_source)[1] !!}</td>
                                    <td rowspan="4">{!! $accountLog->amount !!}</td>
                                    <td rowspan="2" class="b-b b-l">{!! explode('原',explode(', ',(explode(';',$accountLog->remark)[0]))[0])[0] !!}</td>
                                    <td class="b-b b-l t-l">{!! '原'.explode('原',explode(', ',(explode(';',$accountLog->remark)[0]))[0])[1] !!}</td>
                                </tr>
                                <tr>
                                    <td class="b-b b-l t-l">{!! explode(', ',(explode(';',$accountLog->remark)[0]))[1] !!}</td>
                                </tr>
                                <tr>
                                    <td rowspan="2" class="b-l">{!! explode('原',explode(', ',(explode(';',$accountLog->remark)[1]))[0])[0] !!}</td>
                                    <td class="b-b b-l t-l">{!! '原'.explode('原',explode(', ',(explode(';',$accountLog->remark)[1]))[0])[1] !!}</td>
                                </tr>
                                <tr>
                                    <td class="b-l t-l">{!! explode(', ',(explode(';',$accountLog->remark)[1]))[1] !!}</td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                    @if(!$playerAccountLog->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $playerAccountLog->total() }}</i>项，共<i class="pagenum">{{ $playerAccountLog->lastPage() }}</i>页，每页<i class="onpagenum">{{ $playerAccountLog->perPage() }}</i>个</p>
                            </div>
                            {{ $playerAccountLog->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    {{--<script src="{!! asset('./app/js/account-security.js') !!}"></script>--}}
@endsection

