@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Agent Deposit Pay Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierAgentDepositPayLog, ['route' => ['carrierAgentDepositPayLogs.update', $carrierAgentDepositPayLog->id], 'method' => 'patch']) !!}

                        @include('carrier_agent_deposit_pay_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection