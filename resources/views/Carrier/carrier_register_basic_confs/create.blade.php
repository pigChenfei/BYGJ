@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Register Basic Conf
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'carrierRegisterBasicConfs.store']) !!}

                        @include('carrier_register_basic_confs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
