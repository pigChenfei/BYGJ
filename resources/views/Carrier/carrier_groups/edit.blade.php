@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Group
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierGroup, ['route' => ['carrierGroups.update', $carrierGroup->id], 'method' => 'patch']) !!}

                        @include('carrier_groups.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection