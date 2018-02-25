@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Carrier Password Recovery Site Confs</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('carrierPasswordRecoverySiteConfs.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('Carrier.carrier_password_recovery_site_confs.table')
            </div>
        </div>
    </div>
@endsection

