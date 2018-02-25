@extends('Web.default.layouts.app')

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
    <summary>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="2000" style="  min-width: 1098px;" >
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="{!! asset('./app/img/banner_entertainment.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/banner_entertainment.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/banner_entertainment.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/banner_entertainment.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </summary>
    <div id="live-entertainment">
    	<!--一行两张-->
    	<div class="row">
    		<div class="live_item live_item1">
                <div class="img_box">
                	<img src="{!! asset('./app/img/AG_img2x.png') !!}" alt=""/>
                </div>  
                <div class="active_shadow">
					@if(!\WinwinAuth::memberUser())
                   <button class="btn btn-blue openaccount">立即开户</button>
					@endif
                   <button class="btn btn-blue">免费试玩</button>
                </div>
            </div>
            <div class="live_item live_item1">
                <div class="img_box">
                	<img src="{!! asset('./app/img/AG_img2x.png') !!}" alt=""/>
                </div>  
                <div class="active_shadow">
					@if(!\WinwinAuth::memberUser())
                   <button class="btn btn-blue openaccount">立即开户</button>
					@endif
                   <button class="btn btn-blue">免费试玩</button>
                </div>
            </div>
    	</div>
    	<!--一行三张-->
    	<div class="row">
    		 <div class=" live_item live_item2">
	            <div class="img_box">
	            	<img src="{!! asset('./app/img/AG_img3.png') !!}" alt=""/>
	            </div>
	            <div class="active_shadow">
					@if(!\WinwinAuth::memberUser())
	                <button class="btn btn-blue openaccount">立即开户</button>
					@endif
                   	<button class="btn btn-blue">免费试玩</button>  
	            </div>
	        </div>
	        <div class="live_item live_item2"> 
	            <div class="img_box">
	            	<img src="{!! asset('./app/img/AG_img3.png') !!}" alt=""/>
	            </div>
	            <div class="active_shadow">
					@if(!\WinwinAuth::memberUser())
	                <button class="btn btn-blue openaccount">立即开户</button>
					@endif
                   	<button class="btn btn-blue">免费试玩</button>  
	            </div>
	        </div>
	        <div class="live_item live_item2">
	            <div class="img_box">
	            	<img src="{!! asset('./app/img/AG_img3.png') !!}" alt=""/>
	            </div>
	            <div class="active_shadow">
					@if(!\WinwinAuth::memberUser())
	                <button class="btn btn-blue openaccount">立即开户</button>
					@endif
                   	<button class="btn btn-blue">免费试玩</button>  
	            </div>
	        </div>
    	</div>
    </div>
    
    <!-- 模态框（Modal） -->
	<div class="modal fade" id="Modal_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	                <h4 class="modal-title" id="myModalLabel">登录博赢国际</h4>
	            </div>
	            <div class="modal-body">
	            	<form>
	            		<div class="input_item">
	            			<label for="account"><img src="{!! asset('./app/img/icon_user.png') !!}"/></label>
	            			<input type="text" name="account" id="account" maxlength="11" minlength='4' placeholder="会员账号" />
	            		</div>
	            		<div class="input_item">
	            			<label for="password"><img src="{!! asset('./app/img/icon_pwd.png') !!}"/></label>
	            			<input type="password" name="password" id="password" placeholder="登录密码" />
	            		</div>
	            		{{--<div class="input_item">--}}
	            			{{--<label for="code"><img src="{!! asset('./app/img/icon_pwd.png') !!}"/></label>--}}
	            			{{--<input type="text" name="password" id="code" placeholder="验证码" style="width: 115px;"/>--}}
	            			{{--<span class="captchaChange">{!! \Captcha::img() !!}</span>--}}
	            		{{--</div>--}}
	            		<a id="link_forget" href="">忘记登录密码？</a>
	            		<div class="input_item">
	            			<input type="submit" class="btn btn-primary" value="登录" style="background-color: #1f81db;"/>
	            		</div>	            		
	            	</form>
	            </div>
	            <div class="modal-footer">
	            	<span>还没有双赢账号？</span>
	                <button type="button" class="btn btn-warning" data-dismiss="modal" style="padding: 3px 15px;margin-left: 15px;">立即注册</button>
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@section('scripts')
    <script>

		$('#live-entertainment .live_item').hover(function(){
			$(this).find('.active_shadow').show();
		},function(){
			$(this).find('.active_shadow').hide();
		});

        $(".live-kiv>div:nth-child(2)>div").click(function(){
            layer.msg('加载中', {
                icon: 16
                ,shade: 0.01
            });
        });
        
        $('#live-entertainment .active_shadow .openaccount').click(function(){
        	$('#Modal_login').modal('show');
        });
        
        //登录
        $('#Modal_login form').submit(function(e){
        	e.preventDefault();
        	var pattern1 = /^[0-9a-zA-Z]*[a-zA-Z]+[0-9a-zA-Z]*$/;
        	var pattern2 = /[a-zA-Z0-9]{6,16}/;
        	var pattern3 = /^[0-9]{4}$/;
        	var account = $.trim($('#Modal_login #account').val());
        	var pwd = $.trim($('#Modal_login #password').val());
        	//var code = $.trim($('#Modal_login #code').val());
        	if(!pattern1.test(account)){
        		layer.tips('账号格式不正确', '#account');
        		return;
        	}
        	if(!pattern2.test(pwd)){
        		layer.tips('密码格式不正确', '#password');
        		return;
        	}
//        	if(!pattern3.test(code)){
//        		layer.tips('验证码不正确', '#code');
//        		return;
//        	}
		var data = {'user_name':account,'password':pwd};
      	$.ajax({
      			type: 'post',
				url: "/homes.login",
				data: data,
				dataType: 'json',
				async: true,
      		success:function(data){
				if(data.success == true){
					window.location.href = "players.account-security";
					return false;
				}
				if(data.success == false){
					layer.tips(data.message, '#account', {
						tips: [1, '#ff0000'],
						time: 2000
					});
				}
      		},
      		error:function(xhr){
				if(xhr.responseJSON.success == false){
						layer.tips(xhr.responseJSON.message, '#account', {
							tips: [1, '#ff0000'],
							time: 2000
						});
					return false;
				}
      		}
      	});
        });
    </script>
@endsection
