@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Game Win Lose Stastics
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'gameWinLoseStastics.store']) !!}

                        @include('game_win_lose_stastics.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
