<aside>
    <ul class="aside list-unstyled">
        <li class="primary-tag main-tag"><a class="origin">会员中心</a></li>
        <li class="primary-tag @if(Route::currentRouteName() == 'players.deposit' || strpos(Route::currentRouteName(), 'withdraw-money') || strpos(Route::currentRouteName(), 'account-transfer') || strpos(Route::currentRouteName(), 'rebateFinancialFlow')) active droped @endif">
            <a href="javascript:void(0)"><i class="iconfont icon-finance"></i>财务中心<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" @if(Route::currentRouteName() != 'players.deposit' && !strpos(Route::currentRouteName(), 'withdraw-money') && !strpos(Route::currentRouteName(), 'account-transfer') && !strpos(Route::currentRouteName(), 'rebateFinancialFlow')) hidden @endif>
                {{--<li @if(Route::currentRouteName() == 'players.account-security') class="active" @endif><a href="{{ route('players.account-security') }}">账户存款</a></li>--}}
                <li @if(Route::currentRouteName() == 'players.deposit') class="active" @endif><a href="{{ route('players.deposit') }}">账户存款</a></li>
                <li @if(Route::currentRouteName() == 'players.withdraw-money') class="active" @endif><a href="{!! route('players.withdraw-money') !!}">快速取款</a></li>
                <li @if(Route::currentRouteName() == 'players.account-transfer') class="active" @endif><a href="{{ route('players.account-transfer') }}">转账中心</a></li>
                <li @if(Route::currentRouteName() == 'players.rebateFinancialFlow') class="active" @endif><a href="{!! route('players.rebateFinancialFlow') !!}">实时洗码</a></li>
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'depositRecords') || strpos(Route::currentRouteName(), 'withdrawRecords') || strpos(Route::currentRouteName(), 'transferRecords') || strpos(Route::currentRouteName(), 'washCodeRecords') || strpos(Route::currentRouteName(), 'discountRecords') || strpos(Route::currentRouteName(), 'bettingRecords') || strpos(Route::currentRouteName(), 'bettingDetails')) active droped @endif">
            <a href="javascript:void(0)"><i class="iconfont icon-statement"></i>财务报表<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items"  @if(!strpos(Route::currentRouteName(), 'depositRecords') && !strpos(Route::currentRouteName(), 'withdrawRecords') && !strpos(Route::currentRouteName(), 'transferRecords') && !strpos(Route::currentRouteName(), 'washCodeRecords') && !strpos(Route::currentRouteName(), 'discountRecords') && !strpos(Route::currentRouteName(), 'bettingRecords') && !strpos(Route::currentRouteName(), 'bettingDetails')) hidden @endif>
                <li @if(Route::currentRouteName() == 'players.depositRecords') class="active" @endif><a href="{!! route('players.depositRecords') !!}">存款记录</a></li>
                <li @if(Route::currentRouteName() == 'players.withdrawRecords') class="active" @endif><a href="{!! route('players.withdrawRecords') !!}">取款记录</a></li>
                <li @if(Route::currentRouteName() == 'players.transferRecords') class="active" @endif><a href="{!! route('players.transferRecords') !!}">转账记录</a></li>
                <li @if(Route::currentRouteName() == 'players.washCodeRecords') class="active" @endif><a href="{!! route('players.washCodeRecords') !!}">洗码记录</a></li>
                <li @if(Route::currentRouteName() == 'players.discountRecords') class="active" @endif><a href="{!! route('players.discountRecords') !!}">优惠记录</a></li>
                <li @if(Route::currentRouteName() == 'players.bettingRecords' || Route::currentRouteName() == 'players.bettingDetails') class="active" @endif><a href="{!! route('players.bettingRecords') !!}">投注记录</a></li>
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'apply-for-discount')) active droped @endif">
            <a href="{!! route('players.apply-for-discount') !!}"><i class="iconfont icon-coupon"></i>申请优惠<span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'myRecommends') || strpos(Route::currentRouteName(), 'myReferrals') || strpos(Route::currentRouteName(), 'accountStatistics') || strpos(Route::currentRouteName(), 'statisticDetails')) active droped @endif">
            <a href="javascript:void(0)"><i class="iconfont icon-recommend"></i>邀请好友<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" @if(!strpos(Route::currentRouteName(), 'myRecommends') && !strpos(Route::currentRouteName(), 'myReferrals') && !strpos(Route::currentRouteName(), 'accountStatistics') && !strpos(Route::currentRouteName(), 'statisticDetails')) hidden @endif>
                <li @if(Route::currentRouteName() == 'players.myRecommends') class="active" @endif><a href="{!! route('players.myRecommends') !!}">我要邀请</a></li>
                <li @if(Route::currentRouteName() == 'players.myReferrals') class="active" @endif><a href="{!! route('players.myReferrals') !!}">我的下线</a></li>
                <li @if(Route::currentRouteName() == 'players.accountStatistics' || Route::currentRouteName() == 'players.statisticDetails') class="active" @endif><a href="{!! route('players.accountStatistics') !!}">账户统计</a></li>
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'sms-subscriptions')) active droped @endif">
            <a href="javascript:void(0)"><i class="iconfont icon-message"></i>信息服务<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" @if(!strpos(Route::currentRouteName(), 'sms-subscriptions')) hidden @endif>
                <li @if(Route::currentRouteName() == 'players.sms-subscriptions') class="active" @endif><a href="{!! route('players.sms-subscriptions') !!}">站内短信</a></li>
            </ul>
        </li>
        <li class="primary-tag @if(strpos(Route::currentRouteName(), 'accountPassword') || strpos(Route::currentRouteName(), 'accountBankcard') || strpos(Route::currentRouteName(), 'account-security') || strpos(Route::currentRouteName(), 'accountPhone') || strpos(Route::currentRouteName(), 'accountQukuan') || strpos(Route::currentRouteName(), 'accountPtPassword')) active droped @endif">
            <a href="javascript:void(0)"><i class="iconfont icon-personal_info"></i>账户资料<span class="glyphicon glyphicon-menu-right"></span></a>
            <ul class="list-unstyled items" @if(!strpos(Route::currentRouteName(), 'accountPassword') && !strpos(Route::currentRouteName(), 'accountBankcard') && !strpos(Route::currentRouteName(), 'account-security') && !strpos(Route::currentRouteName(), 'accountPhone') && !strpos(Route::currentRouteName(), 'accountQukuan') && !strpos(Route::currentRouteName(), 'accountPtPassword')) hidden @endif>
                <li @if(Route::currentRouteName() == 'players.account-security') class="active" @endif><a href="{!! route('players.account-security') !!}">个人信息</a></li>
                <li @if(Route::currentRouteName() == 'players.accountPassword') class="active" @endif><a href="{!! route('players.accountPassword') !!}">登录密码修改</a></li>
                <li @if(Route::currentRouteName() == 'players.accountQukuan') class="active" @endif><a href="{!! route('players.accountQukuan') !!}">取款密码修改</a></li>
                {{--<li @if(Route::currentRouteName() == 'players.accountPhone') class="active" @endif><a href="{!! route('players.accountPhone') !!}">邮箱修改</a></li>--}}
                {{-- <li @if(Route::currentRouteName() == 'players.accountPtPassword') class="active" @endif><a href="{!! route('players.accountPtPassword') !!}">PT密码修改</a></li> --}}
                <li @if(Route::currentRouteName() == 'players.accountBankcard') class="active" @endif><a href="{!! route('players.accountBankcard') !!}">银行卡管理</a></li>
            </ul>
        </li>
    </ul>
</aside>
<script src="{!! asset('./app/template_one/js/bootstrap-datetimepicker.min.js') !!}"></script>
<script src="{!! asset('./app/template_one/js/bootstrap-datetimepicker.zh-CN.js') !!}"></script>
<script>
    $(function () {
        //左侧菜单栏显示隐藏
        bindEvent('.primary-tag','a[class!="origin"]','click',function(){
            var _that = $(this).parent();
            if (_that.find('ul:visible').size()>0) {
                _that.removeClass('droped')
                _that.find('ul').slideUp();
            } else {
                _that.addClass('droped').siblings().removeClass('droped')
                _that.find('ul').slideDown();
                _that.siblings().find('ul').slideUp();
            }
        });
        bindEvent('.coupon-box','.check','click',function(){
            $(this).parents(".item").find('.coupon-detail').slideToggle();
        });
        bindEvent('.coupon-box','.packup','click',function(){
            $(this).parents(".coupon-detail").slideUp();
        });
        //选择条件
        $(document).on('click', '.transferFrom', function(e){
            e.preventDefault();
            var _this = $(this);
            var value = _this.find('a').attr('data-value');
            var name = _this.find('a').html();
            _this.parent().prev().prev().attr('data-value', value).find('i').html(name);
        });
        //时间选择
        $(".start-time").datetimepicker({
            startDate: '1970-01-01 00:00',
            language: 'zh-CN',
            format: 'yyyy-mm-dd hh:ii',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 1,
            forceParse: 0
        }).on('changeDate', function(ev){
            var dat = new Date($(".start-time").val().split('-').join());
            $(".end-time").removeAttr('disabled');
            $(".end-time").datetimepicker('remove').datetimepicker({
                startDate: dat,
                language: 'zh-CN',
                format: 'yyyy-mm-dd hh:ii',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 1,
                forceParse: 0
            })
        });
        $(".drawrecord .start-time,.transfer-record .start-time").datetimepicker('remove').datetimepicker({
            startDate: '1970-01-01 00:00:00',
            language: 'zh-CN',
            format: 'yyyy-mm-dd hh:ii:ss',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 0,
            forceParse: 0
        }).on('changeDate', function(ev){
            var dat = new Date($(".drawrecord .start-time,.transfer-record .start-time").val().split('-').join());
            $(".drawrecord .end-time,.transfer-record .end-time").removeAttr('disabled');
            $(".drawrecord .end-time,.transfer-record .end-time").datetimepicker('remove').datetimepicker({
                startDate: dat,
                language: 'zh-CN',
                format: 'yyyy-mm-dd hh:ii:ss',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 0,
                forceParse: 0
            })
        });
        // 报表搜索查询
        var url = "{!! app('request')->url() !!}";
        $(document).on('click','.record-search',function(event){
            event.preventDefault();
            event.stopPropagation();
            var start_time = $('.start-time').val();
            var end_time = $('.end-time').val();
            var status = $('.status').attr('data-value');
            window.location.href = url+'?status='+status+'&start_time='+start_time+'&end_time='+end_time;
        });
    })
</script>
