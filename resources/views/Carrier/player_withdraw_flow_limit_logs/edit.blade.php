@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Withdraw Flow Limit Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playerWithdrawFlowLimitLog, ['route' => ['playerWithdrawFlowLimitLogs.update', $playerWithdrawFlowLimitLog->id], 'method' => 'patch']) !!}

                        @include('player_withdraw_flow_limit_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection