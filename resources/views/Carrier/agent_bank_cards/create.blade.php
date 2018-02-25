@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agent Bank Card
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'agentBankCards.store']) !!}

                        @include('agent_bank_cards.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
