@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
@endsection

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--业绩报表-->
    <article class="performance-excl">
        <div class="art-title"></div>
        <div class="art-body clearfix">
            <h4 class="art-tit">业绩报表</h4>
            <div class="query">
                <label for="">开始时间：</label>
                <input type="text" class="form-control start-time" placeholder="选择开始时间" readonly/>
                <label for="">结束时间：</label>
                <input type="text" class="form-control end-time" placeholder="选择结束时间" readonly disabled />
                <button class="btn btn-warning search-table"><i>查询</i></button>
            </div>
            <div id="performance-container">
                @include(\WinwinAuth::agentUser()->template_agent_admin.'.agent_performance.table')
            </div>
        </div>
    </article>
@endsection
