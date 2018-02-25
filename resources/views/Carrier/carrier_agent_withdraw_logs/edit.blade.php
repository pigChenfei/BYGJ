@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Agent Withdraw Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierAgentWithdrawLog, ['route' => ['carrierAgentWithdrawLogs.update', $carrierAgentWithdrawLog->id], 'method' => 'patch']) !!}

                        @include('carrier_agent_withdraw_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection