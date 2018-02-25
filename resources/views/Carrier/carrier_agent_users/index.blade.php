@extends('Carrier.layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box" id="all_player_list">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 代理列表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                    @include('Carrier.carrier_agent_users.table')
                    <h5 class="pull-left">
                        <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-left: 5px" onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
                        <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-left: 15px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentUsers.create')) !!}">新增代理用户</a>
                    </h5>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
    @include('Components.player_edit_modal')
@endsection


