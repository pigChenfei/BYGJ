@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player
        </h1>
    </section>
    <div class="content">

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'players.store']) !!}

                        @include('Carrier.players.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
