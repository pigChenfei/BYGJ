@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bank Type
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($bankType, ['route' => ['bankTypes.update', $bankType->id], 'method' => 'patch']) !!}

                        @include('bank_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection