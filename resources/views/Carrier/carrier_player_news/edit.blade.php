@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Player News Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierPlayerNewsLog, ['route' => ['carrierPlayerNewsLogs.update', $carrierPlayerNewsLog->id], 'method' => 'patch']) !!}

                        @include('carrier_player_news_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection