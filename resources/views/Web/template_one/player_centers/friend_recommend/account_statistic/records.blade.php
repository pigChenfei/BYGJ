@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
	<link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
@endsection

@section('header-nav')
	@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
	<section class="member-container">
		<div class="member-wrap clearfix">
		@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
		<!--账目统计-->
			<article class="accounting">
				<div class="art-title"></div>
				<div class="art-body">
					<h4 class="art-tit">账户统计</h4>
					<div class="query">
						<label for="">开始时间：</label>
						<input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
						<label for="">结束时间：</label>
						<input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
						<button class="btn btn-warning record-search"><i>查询</i></button>
					</div>
					<div class="table-wrap mb-30">
						<table class="table text-center">
							<thead>
							<tr>
								<th class="text-center">总会员数</th>
								<th class="text-center">有效会员数</th>
								<th class="text-center">总存款额</th>
								<th class="text-center">总投注额</th>
								<th class="text-center">总有效投注额</th>
								<th class="text-center">奖金</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td style="height:50px;">{!! $statisticTotal->totalMembers !!}</td>
								<td>{!! $statisticTotal->availableMembers !!}</td>
								<td>{!! $statisticTotal->totalDepositAmount !!}</td>
								<td>{!! $statisticTotal->totalBetAmount !!}</td>
								<td>{!! $statisticTotal->availableTotalBetAmount !!}</td>
								<td class="twolines">
									<i>{!! $statisticTotal->totalBonus !!}</i><br />
									<a href="{!! route('players.statisticDetails') !!}">查看详情</a>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="table-wrap">
						<table class="table text-center">
							<thead>
							<tr>
								<th class="text-center">会员账号</th>
								<th class="text-center">总存款额</th>
								<th class="text-center">总存款额</th>
								<th class="text-center">有效投注额</th>
								<th class="text-center">最后登录时间</th>
								<th class="text-center">注册时间</th>
							</tr>
							</thead>
							<tbody>
							@foreach($recommentdPlayer as $item)
								<tr>
									<td>{!! $item->user_name !!}</td>
									<td>{!! $item->depositLogs->sum('amount') !!}</td>
									<td>{!! $item->betFlowLogs->sum('bet_amount') !!}</td>
									<td>{!! $item->betFlowLogs->sum('available_bet_amount') !!}</td>
									<td>{!! $item->login_at !!}</td>
									<td>{!! $item->created_at !!}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					@if(!$recommentdPlayer->total())
						<div class="norecord table"><div class="table-cell">暂无记录</div></div>
					@else
						<div class="pagenation-container clearfix">
							<div class="pageinfo float-left">
								<p>共<i class="game-count">{{ $recommentdPlayer->total() }}</i>项，共<i class="pagenum">{{ $recommentdPlayer->lastPage() }}</i>页，每页<i class="onpagenum">{{ $recommentdPlayer->perPage() }}</i>个</p>
							</div>
							{{ $recommentdPlayer->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
						</div>
					@endif
				</div>
			</article>
		</div>
	</section>
@endsection


