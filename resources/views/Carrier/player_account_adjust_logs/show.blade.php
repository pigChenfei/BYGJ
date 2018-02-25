@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Account Adjust Log
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('carrier.player_account_adjust_logs.show_fields')
                    <a href="{!! route('playerAccountAdjustLogs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
