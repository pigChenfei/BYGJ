@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Agent Commission Settle Log
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('carrier_agent_commission_settle_logs.show_fields')
                    <a href="{!! route('carrierAgentCommissionSettleLogs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
