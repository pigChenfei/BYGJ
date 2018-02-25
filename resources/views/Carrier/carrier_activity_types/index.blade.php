@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i>优惠活动类型列表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Carrier.carrier_activity_types.table')
                <h5 class="pull-left">
                    <a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierActivityTypes.create')) !!}">新增活动类型</a>
                    <a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('carrierActivities.index') !!}">优惠活动列表</a>
                    <a class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
                </h5>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    </div>
@endsection