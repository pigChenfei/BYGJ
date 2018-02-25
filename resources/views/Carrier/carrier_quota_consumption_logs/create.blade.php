@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Quota Consumption Log
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'carrierQuotaConsumptionLogs.store']) !!}

                        @include('carrier_quota_consumption_logs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
