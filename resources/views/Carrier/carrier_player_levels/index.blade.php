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
                <h3 class="box-title"><i class="fa fa-tag"></i> 会员等级列表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Carrier.carrier_player_levels.table')
                <h5 class="pull-left">
                    <a class="btn btn-primary" style="margin-left: 15px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPlayerLevels.create')) !!}">新增会员等级</a>
                    <a class="btn btn-primary"  onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
                </h5>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    {!! TableScript::createEditOrAddModal() !!}

@endsection

