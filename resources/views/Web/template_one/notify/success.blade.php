@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/video_game.css') !!}"/>
@endsection

@section('header-nav')
	@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection


@section('content')
	<section class="notice-main {{$img}}">
		<div class="msg-wrap">
			@if(!empty($data))
			存款金额为¥{{$data['amount']}}，手续费为¥{{$data['fee_amount']}}，优惠¥{{$data['benefit_amount']}}
			<br/>
			实际到账¥{{$data['finally_amount']}}，账户余额为¥{{$data['main_account_amount']}}
			@endif
		</div>
    	<div class="btns-wrap clearfix">
			<a href="/">返回首页</a>
			@if($img == 'payerror')
			<a href="{{ route('players.deposit') }}">重新存款</a>
			@else
			<a href="{{route('players.depositRecords')}}">存款记录</a>
			@endif
    	</div>
    </section>
        
@endsection
