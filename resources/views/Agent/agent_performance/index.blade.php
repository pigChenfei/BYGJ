@extends('Agent.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
@endsection

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="nav-tabs-custom">
            <div class="content">
                <div class="clearfix"></div>
                <div class="box box-primary color-palette-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tag"></i>业绩报表</h3>
                        <div class="box-tools">
                            <ul class="pull-right pagination-sm pagination">
                            </ul>
                        </div>
                    </div>
                    <div class="box-body">
                            @include('Agent.agent_performance.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>
    <script src="{!! asset('datepicker/bootstrap-datepicker.js') !!}"></script>
    <script>
        $(function(){
            $('#reservationtime').daterangepicker({
                startDate: '{!! date('Y-m-01', time()) !!}',
                endDate: '{!! date('Y-m-d H:i:s') !!}',
                timePicker24Hour: true,
                timePickerSeconds: true,
                timePicker: true,
                locale:{
                    format: "YYYY-MM-DD HH:mm:ss",
                    applyLabel: "确定",
                    cancelLabel: "取消",
                },
                language:'cn'
            });
        })
    </script>
@endsection