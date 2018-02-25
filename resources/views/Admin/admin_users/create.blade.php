@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Admin User
        </h1>
    </section>
    <div class="content">

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'adminUsers.store']) !!}

                        @include('admin_users.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
