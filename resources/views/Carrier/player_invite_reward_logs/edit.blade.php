@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Invite Reward Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($playerInviteRewardLog, ['route' => ['playerInviteRewardLogs.update', $playerInviteRewardLog->id], 'method' => 'patch']) !!}

                        @include('player_invite_reward_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection