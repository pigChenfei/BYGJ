@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
<link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/discount.css') !!}">
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection
@section('content')
<section class="aboutus-main">
        	<div class="main-wrap clearfix">
        		<aside class="float-left">
        			<div class="item" data-id="box1"><a href="{!! url('homes.contactCustomer?type=about')!!}">关于我们<span class="glyphicon glyphicon-menu-right"></span></a></div>
        			<div class="item" data-id="box2"><a href="{!! url('homes.contactCustomer?type=common') !!}">常见问题<span class="glyphicon glyphicon-menu-right"></span></a></div>
        			<div class="item active" data-id="box3"><a href="{!! url('homes.contactCustomer?type=duty') !!}">责任博彩<span class="glyphicon glyphicon-menu-right"></span></a></div>
        		</aside>
        		<article class="aboutus-wrap float-left">
        			<div class="aboutus-box" id="box1">
        				<h3 class="text-center">责任博彩</h3>
	        			<div class="paragraph">
	        				{!! $webConf !!}
	        			</div>
        			</div>
        		</article>
        	</div>
        </section>
@endsection
@section('scripts')
<script src='{!! asset('./app/template_one/js/common.js') !!}'></script>
	<script type="text/javascript">
		$('.qa-head').click(function(){
			var _that = $(this)
			if(_that.parent().hasClass('active')){
				_that.parent().removeClass('active')
				_that.parent().find('.qa-body').slideUp(300)
				return false;
			}
			_that.parent().addClass('active').siblings().removeClass('active')
			_that.parent().find('.qa-body').slideDown(300)
			_that.parent().siblings().find('.qa-body').slideUp(300)
		})
		$(function(){
			$('.qa-box').eq(0).addClass('active').siblings().removeClass('active')
			$('.qa-box').eq(0).find('.qa-body').show()
		})
	</script>
@endsection