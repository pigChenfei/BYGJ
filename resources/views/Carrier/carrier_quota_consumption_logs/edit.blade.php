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
                   {!! Form::model($carrierQuotaConsumptionLog, ['route' => ['carrierQuotaConsumptionLogs.update', $carrierQuotaConsumptionLog->id], 'method' => 'patch']) !!}

                        @include('carrier_quota_consumption_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection