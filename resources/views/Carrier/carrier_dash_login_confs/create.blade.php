@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Dash Login Conf
        </h1>
    </section>
    <div class="content">

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'carrierDashLoginConfs.store']) !!}

                        @include('carrier_dash_login_confs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
