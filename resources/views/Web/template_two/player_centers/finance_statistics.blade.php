@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/layer.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/css/account.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
<main class='bg_container' style="background-color: #eaeaea;  padding-top:30px;padding-bottom: 30px;">
  	<div class="Member-Center">
        <ul class="nav nav-tabs" style="background-color: #eee;">
	          <li class="active"><a href="#deposit-record" data-toggle="tab" id="depositRecord">存款记录</a></li>
	          <li><a href="#withdrawal-record" data-toggle="tab" id="withdrawalRecord">取款记录</a></li>
	          <li><a href="#transfer-record" data-toggle="tab" id="transferRecord">转账记录</a></li>
	          <li><a href="#wash-code-record" data-toggle="tab" id="washCodeRecord">洗码记录</a></li>
	          <li><a href="#preferential-record" data-toggle="tab" id="preferentialRecord">优惠记录</a></li>
	          <li><a href="#betting-record" data-toggle="tab" id="bettingRecord">投注记录</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="deposit-record">
                {{--存款记录--}}
            </div>
            <div class="tab-pane" id="withdrawal-record">
                {{--取款记录--}}
            </div>
            <div class="tab-pane" id="transfer-record">
                {{--转账记录--}}
            </div>
            <div class="tab-pane" id="wash-code-record">
                {{--洗码记录--}}
            </div>
            <div class="tab-pane" id="preferential-record">
                {{--优惠记录--}}
            </div>
            <div class="tab-pane" id="betting-record">
                {{--投注记录--}}
            </div>
        </div>
    </div>
  </main>
@endsection
@section('scripts')
    <script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
    <script src="{!! asset('./app/js/finance_statistics.js.php') !!}"></script>
@endsection
