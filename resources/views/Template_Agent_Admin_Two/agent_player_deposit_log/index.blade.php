@extends('Agent.layouts.app')

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
                <h3 class="box-title"><i class="fa fa-tag"></i><a href="{!! route('agentPlayers.index') !!}">会员报表</a>->存款记录</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Agent.agent_player_deposit_log.table')
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
    </div>

    <div class="modal fade" id="editAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    </div>
@endsection