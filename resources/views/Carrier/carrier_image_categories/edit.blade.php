@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Image Category
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierImageCategory, ['route' => ['carrierImageCategories.update', $carrierImageCategory->id], 'method' => 'patch']) !!}

                        @include('carrier_image_categories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection