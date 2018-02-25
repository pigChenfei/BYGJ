@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Web Site Conf
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierWebSiteConf, ['route' => ['carrierWebSiteConfs.update', $carrierWebSiteConf->id], 'method' => 'patch']) !!}

                        @include('carrier_web_site_confs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection