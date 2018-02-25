@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Bet Flow Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playerBetFlowLog, ['route' => ['playerBetFlowLogs.update', $playerBetFlowLog->id], 'method' => 'patch']) !!}

                        @include('player_bet_flow_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection