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
                <h3 class="box-title"><i class="fa fa-tag"></i> 流水限制汇总</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Carrier.player_withdraw_flow_limit_logs.table')
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
    @include('Components.player_edit_modal')
@endsection

