@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Account Log
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playerAccountLog, ['route' => ['playerAccountLogs.update', $playerAccountLog->id], 'method' => 'patch']) !!}

                        @include('Carrier.player_account_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection