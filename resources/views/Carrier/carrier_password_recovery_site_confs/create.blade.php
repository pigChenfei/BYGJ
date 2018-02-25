@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Password Recovery Site Conf
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'carrierPasswordRecoverySiteConfs.store']) !!}

                        @include('carrier_password_recovery_site_confs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
