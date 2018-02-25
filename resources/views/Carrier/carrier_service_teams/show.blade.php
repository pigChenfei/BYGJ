@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Service Team
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('carrier_service_teams')
                    <a href="{!! route('carrierServiceTeams.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
