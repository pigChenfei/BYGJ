@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Agent News
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierAgentNews, ['route' => ['carrierAgentNews.update', $carrierAgentNews->id], 'method' => 'patch']) !!}

                        @include('carrier_agent_news.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection