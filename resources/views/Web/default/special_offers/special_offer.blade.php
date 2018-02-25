@extends('Web.default.layouts.app')

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
	<div class="banner">
		<img src="{!! asset('./app/img/special_banner.png') !!}">
	</div>
    <div class="AG">
        <div role="tabpanel" class="AG-fish"> 
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab_0" aria-controls="tab_0" role="tab" data-toggle="tab">全部优惠</a></li>
                @foreach($actType as $key => $value)
                    <li role="presentation"><a href="#tab_{!! $value->id !!}" aria-controls="tab_{!! $value->id !!}" role="tab" data-toggle="tab">{!! $value->type_name !!}</a></li>
                @endforeach
            </ul> 
            <div class="tab-content special-spesl" style="margin-left: 170px;">           	 
            	<div role="tabpanel" class="tab-pane tab-play active" id="tab_0" style="padding-top: 10px;margin-left: 0px;">
            		@foreach($activityList as $key => $value)
                    <div style="margin: 0px;">
                        <div class="find-out-details" style="margin-left: 4px;">
                            <div>
                            	<i style="background:url({!! $value['img']->imageAsset() !!});width:920px;height: 170px;
                            	background-repeat: no-repeat;background-position: center;background-size:100% 100%; display: block;margin-right: 10px;"></i>
                            </div>
                        </div> 
                        <div class="offer-box">
                            <div>{!! $value->act_content() !!}</div>
                        </div>
                    </div>                 
                    <div class="clearfix"></div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
@endsection 

@section('scripts') 
<script>
    $(".tab-pane>div>.pull-right>div>span:nth-child(2)").click(function(){
        $(this).parents().parents().parent().find(".offer-box").addClass(".select-offer");
    });
    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });
        
    $(".find-out-details").each(function(){
    	$(this).click(function(){
    		if($(this).siblings('.offer-box').css("display")=="none"){
	            $(this).siblings(".offer-box").css("display","block"); 
	            $(this).parents().siblings().find(".offer-box").css("display","none");
	        }else{
	            $(this).parents().find(".offer-box").css("display","none");
	        }
    	});  
    });
    
    $(".refresh").click(
        function(){
            layer.load();
            setTimeout(function(){
                layer.closeAll('loading');
            }, 2000);
        }
    );

    $(".Apply-Now").click(function(){
        layer.alert("对不起！请先登录");
    });
    
    $('.offer-box p font').css('font-size','14px');
    </script>
@endsection
