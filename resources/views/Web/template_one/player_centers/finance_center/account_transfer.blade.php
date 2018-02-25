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
        <!--转账中心-->
            <article class="transfer-center">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">转账中心</h4>
                    <div class="yue-count">
                        <span>主账户余额&nbsp;&nbsp;<i class="font-red mainAccountAmount" >{!! $main_account_amount !!}</i><i class="font-red">元</i></span>
                        <button class="btn btn-warning recycle">一键回收</button>
                    </div>
                    <div class="self-transfer">
                        <label for="dn-draw">转出账户：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle transferFrom-button" id="dn-draw" data-toggle="dropdown"/><i>请选择</i></button>
                            <span class="caret"></span>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw" id="in">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value='main' href="javascript:void(0)">主账户</a>
                                </li>
                                @foreach($playerGameAccount as $item)
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value='{!! $item->main_game_plat_code !!}' href="javascript:void(0)">@if($item->main_game_plat_name=='SUNBET') 申博 @elseif($item->main_game_plat_name=='ONWORKS') 沙巴 @else {!! $item->main_game_plat_name !!} @endif</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="glyphicon glyphicon-arrow-right" style="margin: 0 20px;"></span>
                        <label for="dn-draw">转入账户：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle transferto-button" id="dn-draw" data-toggle="dropdown"/><i>请选择</i></button>
                            <span class="caret"></span>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw" id="to">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value='main' href="javascript:void(0)">主账户</a>
                                </li>
                                @foreach($playerGameAccount as $item)
                                    <li role="presentation" class="transferFrom">
                                        <a role="menuitem" tabindex="-1" data-value='{!! $item->main_game_plat_code !!}' href="javascript:void(0)">
                                            @if($item->main_game_plat_name=='SUNBET') 申博 @elseif($item->main_game_plat_name=='ONWORKS') 沙巴 @else {!! $item->main_game_plat_name !!} @endif</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <label for="dn-draw" style="margin-left: 15px;">金额：</label>
                        <input type="text" class="form-control amount"/>
                        <button class="btn btn-warning confirm-transfer true-account">确认转账</button>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th width="25%" class="text-center">平台</th>
                                <th width="50%" class="text-center">金额</th>
                                <th width="25%" class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playerGameAccount as $item)
                            <tr>
                                <td>@if($item->main_game_plat_name=='SUNBET') 申博 @elseif($item->main_game_plat_name=='ONWORKS') 沙巴 @else {!! $item->main_game_plat_name !!} @endif</td>
                                <td><i style="width:75px;display:inline-block;text-align:right;transform:translateX(-35px);" id="{!! $item->main_game_plat_code !!}" data-va="{!! $item->account_id !!}">{!! number_format($item->amount, 2) !!}</i><i style="transform:translateX(-25px);" class="glyphicon glyphicon-refresh refresh"></i></td>
                                <td><a href="javascript:void(0)" class="transferOneTouch" data-code="{!! $item->main_game_plat_code !!}">一键转入</a></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        var account_ids=[@foreach($playerGameAccount as $item)@if($item->account_id!=0){!! $item->account_id !!},@endif @endforeach];
         function setTimeRefresh()
            {
                for(var k = 0, length = account_ids.length; k < length; k++)
                {
                    (function(k){
                        $.ajax({
                            url: "{!! route('players.accountRefresh') !!}",
                            data: { 'accountId':account_ids[k] },
                            success: function (data) {
                                if(data.success==true)
                                {
                                    if(typeof(data.data)=='string' || typeof(data.data)=='number'){
                                        data.data=parseFloat(data.data);
                                        $('i[data-va="'+account_ids[k]+'"]').html(data.data.toFixed(2));
                                    }
                                }
                                return false;
                            }
                        });
                    }(k))
                }
                
            }
        $(function() {
            //选择条件
            $(document).on('click', '.transferFrom', function(e){
                e.preventDefault();
                var _this = $(this);
                var value = _this.find('a').attr('data-value');
                var name = _this.find('a').html();
                _this.parent().prev().prev().attr('data-value', value).find('i').html(name);
                if($(".transferFrom-button").attr('data-value')==$(".transferto-button").attr('data-value'))
                {
                    _this.parent().prev().prev().attr('data-value', value).find('i').html('请选择');
                    _this.parent().prev().prev().removeAttr('data-value');
                }
            });
            //转账
            $(".transfer-center").on("click", ".true-account", function (e) {
                e.preventDefault();
                var amount = $(".amount").val();
                var reg = /^[1-9]+[0-9.]*\d*$/;
                if (!amount || isNaN(Number(amount)) || amount <= 0 || reg.test(amount) == false) {
                    layer.tips('输入金额不对', '.amount', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                } else {
                    var _me = this;
                    var confimText = $(_me).text();
                    $(_me).text('转账中...');
                    $(_me).removeClass("true-account");

                    var transferFrom = $(".transferFrom-button").attr('data-value');
                    var transferTo = $(".transferto-button").attr('data-value');
                    $.ajax({
                        url: "{!! route('players.accountTransfer') !!}",
                        data: {
                            'amount': amount,
                            'transferFrom': transferFrom,
                            'transferTo': transferTo
                        },
                        type: "POST",
                        success: function (data) {
                            if (data.success == true) {
                                layer.msg('转账成功',{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                                $(".amount").val('');
                                $('.mainAccountAmount').html(parseFloat(data.data.mainAccount).toFixed(2));
                                $('#' + transferTo).html(parseFloat(data.data.transferToAccount).toFixed(2));
                                $('#' + transferFrom).html(parseFloat(data.data.transferFromAccount).toFixed(2));
                            }
                            $(_me).text(confimText);
                            $(_me).addClass("true-account");
                            return false;
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON.success == false) {
                                layer.msg(xhr.responseJSON.message,{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                            }
                            $(_me).text(confimText);
                            $(_me).addClass("true-account");
                            return false;
                        }
                    });
                }
            });

            //一键回收
            $(".recycle").on("click", function (e) {
                e.preventDefault();
                var _me = this;
                var confimText = $(_me).text();
                $(_me).text('回收中');
                (_me).disabled = true;
                $.ajax({
                    url: "{!! route('players.accountRecycle') !!}",
                    type: "POST",
                    success: function (data) {
                        if (data.success) {
                            layer.msg('回收成功');
                            for (x in data.data.gameAccount) {
                                $('#' + x).html(data.data.gameAccount[x].toFixed(2));
                            }
                            $('.mainAccountAmount').html(data.data.mainAccount);
                        }
                        $(_me).text(confimText);
                        (_me).disabled = false;
                        return false;
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        if (xhr.responseJSON.success == false) {
                            layer.msg(xhr.responseJSON.message);
                        }
                        $(_me).text(confimText);
                        (_me).disabled = false;
                        return false;
                    }
                });
            });

            //一键转入
            $(".transferOneTouch").on("click", function (e) {
                e.preventDefault();
                var _me = $(this);
                var mainGameCode = $(this).attr('data-code');
                (_me).removeClass("transferOneTouch");
               $.ajax({
                    url: "{!! route('players.accountTransferOneTouch') !!}",
                    data: { 'transferTo':mainGameCode},
                    success: function (resp) {
                        if (resp.success) {
                            layer.msg('转入成功',{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                            (_me).addClass("transferOneTouch");
                            $('#'+mainGameCode).html(parseFloat(resp.data.transferToAccount).toFixed(2));
                            $('.mainAccountAmount').html(parseFloat(resp.data.mainAccount).toFixed(2));
                        }
                        return false;
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON.success == false) {
                            layer.msg(xhr.responseJSON.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                            (_me).addClass("transferOneTouch");
                        }
                        return false;
                    }
                });
            });

            setTimeRefresh();
           
            //刷新
            $(".refresh").on("click", function (e) {
                e.preventDefault();
                var _me = this;
                var accountId = $(_me).prev().attr('data-va');
                if(accountId!=0)
                {
                    $(_me).removeClass('refresh');
                    $.ajax({
                        url: "{!! route('players.accountRefresh') !!}",
                        data: { 'accountId':accountId },
                        success: function (data) {
                            layer.msg('刷新成功',{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                            if(data.success==true)
                            {
                                if(typeof(data.data)=='string' || typeof(data.data)=='number'){
                                    data.data=parseFloat(data.data);
                                    $(_me).prev().text(data.data.toFixed(2));
                                }
                            }
                            
                            $(_me).addClass('refresh');
                            return false;
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON.success == false) {
                                layer.msg(xhr.responseJSON.message,{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                            }
                            $(_me).addClass('refresh');
                            return false;
                        }
                    });
                }
                else
                {
                   layer.msg('刷新成功',{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                }
                
            });

        });
    </script>
@endsection
