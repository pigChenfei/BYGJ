@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Bet Flow Log
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('player_bet_flow_logs.show_fields')
                    <a href="{!! route('playerBetFlowLogs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
