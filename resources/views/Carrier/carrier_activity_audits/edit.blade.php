@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Activity Audit
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierActivityAudit, ['route' => ['carrierActivityAudits.update', $carrierActivityAudit->id], 'method' => 'patch']) !!}

                        @include('carrier_activity_audits.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection