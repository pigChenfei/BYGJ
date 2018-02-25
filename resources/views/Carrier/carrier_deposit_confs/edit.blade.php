@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Carrier Deposit Conf
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($carrierDepositConf, ['route' => ['carrierDepositConfs.update', $carrierDepositConf->id], 'method' => 'patch']) !!}

                        @include('carrier_deposit_confs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection