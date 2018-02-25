@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
    <style>
        .member-container .bankcard-wrap .item:nth-child(4n+1) {
            margin-left: 0px;
        }
        .masklayer .dialog-wrap .dialog-body input{
            width: 217px;
            padding-left: 22px;
            margin-left:20px;
        }
        .add-card .dropdown .btn {
            width: 220px;
            padding: 0;
            line-height: 33px;
            height:33px;
            margin-bottom: 0;
            border-radius: inherit;
            text-align: left;
            padding-left: 22px;
            margin-left:20px;
            background: none;
            color: rgba(0, 0, 0, 0.8) !important;
            border-bottom: 1px solid #ccc;
        }
        .dropdown-menu{
            left: 96px;
            min-width:217px;
        }
        .dropdown-menu > li > a{padding-left:42px;}
        .member-container .bankcard-wrap .glyphicon.xuanzhong{
            top:65px;
            color: red;
            display: block;
        }
        label{
            font-weight:100;
        }
        .masklayer .dialog-wrap .dialog-body span {
            top: 15px;
            left: inherit;
            right: 20px;
        }
    </style>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
        @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
        <!--快速取款-->
            <article class="drawmoney">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">快速取款</h4>
                    <div class="memb-box clearfix memb-bottomtip" style="position: unset">
                        <div class="table">
                            <div class="table-cell" style="width: 80px;vertical-align: top;">
                                温馨提示：
                            </div>
                            <div class="table-cell">
                                {!! \WinwinAuth::currentWebCarrier()->webSiteConf->with_draw_comment() !!}
                            </div>
                        </div>
                    </div>
                    <h5 style="margin-bottom:0;">已绑定银行卡</h5>
                    <div class="bankcard-wrap clearfix bangding-select">
                        @foreach($playerBankCards as $playerBankCard)
                            <div class="item bank bank-select" style="background: url('{!! asset('./app/img/bank_background/'.$playerBankCard->card_type.'.png') !!}')" data-id="{!! $playerBankCard->card_id !!}">
                                <span class="glyphicon glyphicon-remove-sign del-card" data-id="{!! $playerBankCard->card_id !!}"></span>
                                <p>卡号：{!! $playerBankCard->card_account !!}</p>
                            </div>
                        @endforeach
                        <div class="item add"><span class="glyphicon glyphicon-remove-sign"></span></div>
                    </div>
                    <div class="memb-box clearfix">
                        {{--设置取款银行卡--}}
                        <input type="hidden" id="withdraw-card" name="withdraw-card">
                        <div class="form-inline float-left" style="margin-bottom: 20px;margin-top:10px;">
                            <div class="draw-item"><span>可取金额：</span><i class="font-plum can-draw">{!! number_format(\WinwinAuth::memberUser()->main_account_amount ,2) !!}</i></div>
                            <div class="draw-item"><span>完成流水：</span><i class="font-plum drawed">{!! number_format($complete, 2) !!}</i></div>
                            <div class="draw-item"><span>未完成流水：</span><i class="font-plum undraw">{!! number_format($unfinished, 2) !!}</i></div>
                        </div>
                        <div class="float-right">
                            <div class="form-inline mb-10 draw-money-info">
                                <label class="sm" for="draw-money-num">取款金额：</label>
                                <input type="text" autocomplate="off" class="form-control" placeholder="单次最低提款额为{!! $carrierWithdrawConf->player_once_withdraw_min_sum !!}元,最高{!! $carrierWithdrawConf->player_once_withdraw_max_sum !!}元" id="draw-money-num" data-min="{!! $carrierWithdrawConf->player_once_withdraw_min_sum !!}" data-max="{!! $carrierWithdrawConf->player_once_withdraw_max_sum !!}"/>
                            </div>
                            <div class="form-inline bind-bank" >
                                <label class="sm" for="draw-password">取款密码：</label>
                                <input type="password" autocomplate="off" placeholder="默认取款密码:000000，修改请至账户资料页面操作" class="form-control" id="draw-password">
                            </div>
                        </div>
                        <div class="msg font-plum float-right text-center" style="width:435px;padding-top: 10px;"></div>
                    </div>
                    <div class="text-center"><button class="btn btn-warning mb-20 submit_button" id="submit_button"  @if(\WinwinAuth::memberUser()->main_account_amount < $carrierWithdrawConf->player_once_withdraw_min_sum) disabled @endif>提交</button></div>
                </div>
            </article>
        </div>
        @if(!\WinwinAuth::memberUser()->mobile && !\WinwinAuth::memberUser()->email)
            <div class="masklayer">
                <div class="dialog-wrap">
                    <div>
                        <div class="dialog-head">温馨提示</div>
                        <div class="dialog-body text-center">
                            <h4>您还没绑定手机号或者邮箱账号，是否去绑定？</h4>
                        </div>
                        <div class="dialog-foot clearfix">
                            <button class="btn btn-warning float-left" style="width: 150px;" onclick="location.href='{{ route('players.account-security') }}'">是</button>
                            <button class="btn btn-warning2 float-right fou" style="width: 150px">否</button>
                        </div>
                    </div>
                    <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
                </div>
            </div>
        @endif
    </section>

    <div class="masklayer add-card" style="display: none;">
        <div class="dialog-wrap">
            <!--绑定银行卡-->
                <div class="add-card">
                    <div class="dialog-head">
                        绑定银行卡
                    </div>
                    <div class="dialog-body text-center">
                        <div class="form-inline">
                            <label for="account-holder">开&nbsp;&nbsp;户&nbsp;&nbsp;人</label>
                            <input type="text" name="card_owner_name" class="card_owner_name" id="account-holder" placeholder="请填写开户人的姓名"/>
                        </div>
                        <div class="form-inline">
                            <label for="card-num">卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</label>
                            <input type="text" name="card_account" class="card_account" id="card-num" placeholder="请填写您的银行卡号"/>
                        </div>
                        <div class="form-inline">
                            <div class="dropdown">
                                <label for="banks">银行名称</label>
                                <input type="hidden" name="card_type"/>
                                <button class="btn dropdown-toggle card_type" id="dropdownMenu1" data-toggle="dropdown" style="width:217px;"/>
                                <i style="color:rgba(0,0,0,0.5)">请选择银行</i>
                                </button>
                                <span class="caret"></span>
                                <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dropdownMenu1"  style="height:300px;overflow-y:scroll">
                                    @foreach($banks as $v)
                                        <li role="presentation" class="select-bank" data-type="{!! $v->type_id !!}">
                                            <a role="menuitem" tabindex="-1" href="javascript:void(0)">{!! $v->bank_name !!}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="bank-detailname">分行名称</label>
                            <input type="text" class="card_birth_place" id="bank-detailname" name="card_birth_place" placeholder="如：岳阳市岳阳楼支行"/>
                        </div>
                    </div>
                    <div class="dialog-foot">
                        <button class="btn btn-warning submit-add-bank">确认绑定</button>
                    </div>
                </div>
            <!--关闭-->
            <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
        </div>
    </div>
    <div class="masklayer delete-card" style="display: none;">
        <div class="dialog-wrap">
            <!--删除银行卡-->
            <div class="del-card" >
                <div class="dialog-head"></div>
                <div class="dialog-body text-center">
                    <h4>你确定要删除此银行卡吗？</h4>
                </div>
                <div class="dialog-foot clearfix">
                    <button class="btn btn-warning2 float-left shanchu-bank" style="width: 150px;">是</button>
                    <button class="btn btn-warning float-right fou" style="width: 150px;height: auto">否</button>
                </div>
            </div>
            <!--关闭-->
            <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(function () {

            //弹出删除银行卡弹框
            $(document).on('click', '.del-card', function(e){
                e.preventDefault();
                e.stopPropagation();
                var id = $(this).attr('data-id');
                $('.shanchu-bank').attr('data-id', id);
                $('.masklayer.delete-card').show();

            });

            //弹出添加银行卡弹框
            $(document).on('click', '.add', function(){
                $('.masklayer.add-card').show();
            });

            //提交选择银行卡
            $(document).on('click', '.bank-select', function(e){
                e.preventDefault();
                e.stopPropagation();
                var id = $(this).attr('data-id');
                $('.xuanzhong').remove();
                $(this).append('<span class="glyphicon glyphicon-ok-sign xuanzhong"></span>');
                $('input[name=withdraw-card]').val(id);
            });

            $(document).on('click', '.fou', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parents('.masklayer').hide();

            });

            //添加选择银行
            $(document).on('click', '.select-bank', function(){
                var _this = $(this);
                var type = _this.attr('data-type');
                var name = _this.find('a').html();
                $('input[name=card_type]').val(type);
                _this.parent().prev().prev().html(name);
            });

            //ajax请求添加银行卡
            $(".submit-add-bank").click(function () {
                var button = $(this);
                var userde = $("#account-holder").val() ;//持卡人姓名
                var card_account = $("#card-num").val() ; //卡号
                var bank_type_id = $("input[name=card_type]").val() ; //银行类型id
                var branch_name = $("#bank-detailname").val() ;//分行名称
                var reg3 =/^[\u4e00-\u9fa5]{2,40}$/;
                if(reg3.test(userde)!= true){
                    layer.tips('请输入真实姓名', '#account-holder', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if (Number($("#card-num").val().length) < 16) {
                    layer.tips('卡号长度不对', '#card-num', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if (!bank_type_id) {
                    layer.tips('请选择银行', '#dropdownMenu1', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if((isNaN(Number($("#card-num").val().length) < 16))){
                    layer.tips('请输入正确卡号', '#card-num', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if ($("#bank-detailname").val() == "") {
                    layer.tips('分行不能为空', '#bank-detailname', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                } else {
                    button.removeClass('submit-add-bank');
                    $.ajax({
                        type: 'post',
                        async: true,
                        url: "{!! route('playerwithdraw.addBankCard') !!}",
                        data: {
                            'card_owner_name' : userde,
                            'card_account': card_account,
                            'card_type': bank_type_id,
                            'card_birth_place': branch_name
                        },
                        dataType: 'json',
                        success: function(data){
                            if(data.success == true){
                                layer.msg('新增银行卡成功!',{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                                location.reload();

                            }
                        },
                        error: function(xhr){
                            if(xhr.responseJSON){
                                if (xhr.responseJSON.field){
                                    layer.tips(xhr.responseJSON.message, '.'+xhr.responseJSON.field, {
                                        tips: [1, '#ff0000'],
                                        time: 2000
                                    });
                                }else{
                                    layer.msg(xhr.responseJSON.message,{
                                        success: function(layero, index){
                                            $(layero).css('top', '401.5px');
                                        }
                                    });
                                    $('.masklayer.add-card').toggle();
                                }
                                button.addClass('submit-add-bank');
                            }
                        }
                    });
                }
            });

            //ajax请求删除银行卡
            //删除银行卡
            $(".shanchu-bank").click(function(){
                var button = $(this);
                var card_id = $('.shanchu-bank').attr('data-id');
                button.removeClass('shanchu-bank');
                $.ajax({
                    type: 'post',
                    async: true,
                    url: "{!! route('playerwithdraw.deleteBankCard') !!}",
                    data: {
                        'card_id' : card_id
                    },
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            layer.msg('删除银行卡成功!',{
                                success: function(layero, index){
                                    $(layero).css('top', '401.5px');
                                }
                            });
                            location.reload();
                        }
                    },
                    error: function(xhr){
                        if(xhr.responseJSON){
                            layer.msg(xhr.responseJSON.message,{
                                success: function(layero, index){
                                    $(layero).css('top', '401.5px');
                                }
                            });
                        }
                        button.addClass('shanchu-bank');
                    }
                });
            });
            
            //提交取款
            $(".submit_button").on('click',function(e) {
                e.preventDefault();
                var button = $(this);
                var userde = $("#draw-money-num").val()-0; //取款金额
                var max_money = $("#draw-money-num").attr('data-max')-0; //取款金额
                var min_money = $("#draw-money-num").attr('data-min')-0; //取款金额
                var pay_password = $("#draw-password").val(); //取款密码
                var card_id = $("#withdraw-card").val(); //用户选择的银行卡card_id
                if (card_id == "") { //没有绑定银行卡
                    layer.tips('请先选择银行卡', '.bangding-select', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                } else if (isNaN(Number(userde)) || userde>max_money || userde<min_money) {
                    layer.tips('请输入合适的金额', '#draw-money-num', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if (pay_password.trim()==""||isNaN(Number(pay_password)) || pay_password.length < 6) {
                    layer.tips('请输入正确的银行密码', '#draw-password', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else{
                    button.removeClass('submit_button');
                    $.ajax({
                        type: 'post',
                        async: true,
                        url: "{!! route('playerwithdraw.withdrawApply') !!}",
                        data: {
                            'player_bank_card':card_id,
                            'apply_amount':userde,
                            'pay_password':pay_password
                        },
                        dataType: 'json',
                        success: function(data){
                            if(data.success == true){
                                layer.msg('申请成功,请等待客服审核!',{
                                    success: function(layero, index){
                                        $(layero).css('top', '401.5px');
                                    }
                                });
                                location.href='/players.withdrawRecords';
                            }
                        },
                        error: function(xhr){
                            if(xhr.responseJSON){
                                button.parent().prev().find('div.msg.font-plum.text-center').html(xhr.responseJSON.message);
                            }
                            button.addClass('submit_button');
                        }
                    });
                }
            });
        }) ;
        var timer = setTimeout(function(){$('input').removeAttr('style');},500)
    </script>
@endsection

