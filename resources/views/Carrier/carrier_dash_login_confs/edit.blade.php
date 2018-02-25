@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Dash Login Conf
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierDashLoginConf, ['route' => ['carrierDashLoginConfs.update', $carrierDashLoginConf->id], 'method' => 'patch']) !!}

                        @include('carrier_dash_login_confs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection