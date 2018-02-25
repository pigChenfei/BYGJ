@extends('Carrier.layouts.app')
@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 银行账户列表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Carrier.carrier_pay_channels.table')
                <h5 class="pull-left">
                    <!--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" >禁用的银行账户</a>-->
                    <!--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" >启用的银行账户</a>-->
                    <a class="btn btn-primary" style="margin-top: -10px;;margin-left: 15px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayChannels.create')) !!}">添加银行账户</a>
                    <a class="btn btn-primary" style="margin-top: -10px;" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayChannels.showManualTransferRecordModal')) !!}">银行账户转账</a>
                    <a class="btn btn-primary " style="margin-top: -10px" onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
                </h5>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    {!! TableScript::createEditOrAddModal() !!}

@endsection

