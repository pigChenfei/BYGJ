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
                    {!! Form::open(['route' => 'carrierGroups.store']) !!}

                        @include('carrier_groups.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
