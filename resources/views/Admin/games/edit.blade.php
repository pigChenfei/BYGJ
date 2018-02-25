@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Game
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($game, ['route' => ['games.update', $game->id], 'method' => 'patch']) !!}

                        @include('games.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection