@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Withdraw Conf
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierWithdrawConf, ['route' => ['carrierWithdrawConfs.update', $carrierWithdrawConf->id], 'method' => 'patch']) !!}

                        @include('carrier_withdraw_confs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection