@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Player Invite Reward Log
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('player_invite_reward_logs.show_fields')
                    <a href="{!! route('playerInviteRewardLogs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
