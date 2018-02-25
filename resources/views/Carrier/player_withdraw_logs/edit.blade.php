@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Withdraw Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playerWithdrawLog, ['route' => ['playerWithdrawLogs.update', $playerWithdrawLog->id], 'method' => 'patch']) !!}

                        @include('player_withdraw_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection