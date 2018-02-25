@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')
    
@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--佣金取款-->
    <style>
        .member-container .add-card .dropdown-menu li a{
            padding-left: 42px;
        }
    </style>
    <article class="bankdata commission-withdrawals">
        <div class="art-title">
        </div>
        <form action="{!! route('agentWithdraws.withdrawRequest') !!}" method="post" id="shenqing-qukuan">
            <div class="art-body">
                <h4 class="art-tit">快速取款</h4>
                <h5 style="margin-bottom:0;">已绑定银行卡</h5>
                <div class="bankcard-wrap clearfix">
                    @if($agentBankCard)
                        <input type="hidden" name="player_bank_card" value="{!! $agentBankCard->id !!}">
                        <div class="item bank zs" style="background: url('{!! asset('./app/img/bank_background/'.$agentBankCard->card_type.'.png') !!}')">
                            <span class="glyphicon glyphicon-remove-sign bankcard-del"></span>
                            <p>卡号：{!! $agentBankCard->card_account !!}</p>
                        </div>
                    @else
                        <div class="item add bankcard-add"><span class="glyphicon glyphicon-remove-sign"></span></div>
                    @endif
                </div>
                <div class="memb-box">
                    <div class="form-inline table" style="margin-bottom: 20px;margin-left: 10px;">
                        <div class="table-cell">可取金额：<i class="font-red can-draw" style="padding-left:18px;">{!! \WinwinAuth::agentUser()->amount !!}</i></div>
                    </div>
                    <div class="form-inline mb-10">
                        <label class="sm" for="draw-money-num">取款金额：</label>
                        <input type="text" name="apply_amount" class="form-control" id="draw-money-num">
                        <span class="tip">注意：单次最低提款额为{!! $agentWithdrawConf->agent_once_withdraw_min_sum !!}元,最高{!! $agentWithdrawConf->agent_once_withdraw_max_sum !!}元</span>
                    </div>
                    <div class="form-inline">
                        <label class="sm" for="draw-password">取款密码：</label>
                        <input type="password" name="pay_password" class="form-control" id="draw-password">
                        <span class="tip">默认取款密码:000000，修改请至账户资料页面操作。</span>
                    </div>
                    <div class="msg font-red f14" style="text-align: center;margin-top: 20px;"></div>
                </div>
                <div class="text-center">
                    <button class="btn btn-warning shenqing-qukuan">提交</button>
                </div>
            </div>
        </form>
    </article>
    <div class="masklayer add-card" style="display: none;">
        <div class="dialog-wrap">
            <!--绑定银行卡-->
            <form id="add-banccard" action="{!! route('agentWithdraws.store') !!}" method="post">
                {!! csrf_field() !!}
                <div class="add-card">
                    <div class="dialog-head">
                        绑定银行卡
                    </div>
                    <div class="dialog-body text-center">
                        <div class="form-inline">
                            <label for="account-holder">开&nbsp;&nbsp;户&nbsp;&nbsp;人</label>
                            <input type="text" name="card_owner_name" id="account-holder" placeholder="请填写开户人的姓名"/>
                        </div>
                        <div class="form-inline">
                            <label for="card-num">卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</label>
                            <input type="text" name="card_account" id="card-num" placeholder="请填写您的银行卡号"/>
                        </div>
                        <div class="form-inline">
                            <div class="dropdown mb-10">
                                <label for="banks" style="transform: translateX(3px);">银行名称</label>
                                <input type="hidden" name="card_type"/>
                                <button class="btn dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" style="margin-left:3px;background:none!important;height:33px;"/>
                                <i style="color:rgba(0,0,0,0.5)">请选择银行</i>
                                </button>
                                <span class="caret" style="top:16px;"></span>
                                <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dropdownMenu1" style="height:300px;overflow-y:scroll;">
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
                            <input type="text" id="bank-detailname" name="card_birth_place" placeholder="如：岳阳市岳阳楼支行"/>
                        </div>
                        <div class="msg font-red f14"></div>
                    </div>
                    <div class="dialog-foot">
                        <button class="btn btn-warning bangding-bank">确认绑定</button>
                    </div>
                </div>
            </form>
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
                    <div class="msg font-red f14"></div>
                </div>
                <div class="dialog-foot clearfix">
                    <button class="btn btn-warning2 float-left shanchu-bank" style="width: 140px;">是</button>
                    <button class="btn btn-warning float-right" onclick="$(this).parents('.masklayer').hide();" style="width: 140px;">否</button>
                </div>
            </div>
            <!--关闭-->
            <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
        </div>
    </div>
    @if(!\WinwinAuth::agentUser()->mobile && !\WinwinAuth::agentUser()->email)
        <div class="masklayer">
            <div class="dialog-wrap">
                <!--删除银行卡-->
                <div>
                    <div class="dialog-head">温馨提示</div>
                    <div class="dialog-body text-center">
                        <h4>您还没绑定手机号或者邮箱账号，是否去绑定？</h4>
                    </div>
                    <div class="dialog-foot clearfix">
                        <button class="btn btn-warning float-left" style="width: 150px;" onclick="location.href='{{url('agent/admin/agentAccountCenters')}}'">是</button>
                        <button class="btn btn-warning2 float-right" style="width: 150px;" onclick="$(this).parents('.masklayer').hide();">否</button>
                    </div>
                </div>
                <!--关闭-->
                <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        $(function () {
            //添加银行卡
            $(document).on('click', '.bankcard-add', function(){
                $('.masklayer.add-card').show();
            });
            //删除银行卡
            $(document).on('click', '.bankcard-del', function(){
                $('.masklayer.delete-card').show();
            });
            //选择银行
            $(document).on('click', '.select-bank', function(){
                var _this = $(this);
                var type = _this.attr('data-type');
                var name = _this.find('a').html();
                $('input[name=card_type]').val(type);
                _this.parent().prev().prev().html(name);
            });
            //添加银行卡
            $('.bangding-bank').click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var _this = $(this);
                var form = $('#add-banccard');
                _this.removeClass('bangding-bank');
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (data) {
                        layer.msg('操作成功',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        if (xhr.responseJSON.success == false){
                            form.find('div.msg.font-red.f14').html(xhr.responseJSON.message);
                        }
                        _this.addClass('bangding-bank');
                    }
                });
            });
            //删除银行卡
            $('.shanchu-bank').click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var _this = $(this);
                _this.removeClass('shanchu-bank');
                $.ajax({
                    type: "delete",
                    dataType: "json",
                    url: "{!! route('agentWithdraws.del') !!}",
                    success: function (data) {
                        layer.msg('操作成功',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.success == false){
                            _this.parents('.masklayer.delete-card').find('div.msg.font-red.f14').html(xhr.responseJSON.message);
                        }
                        _this.addClass('shanchu-bank');
                    }
                });
            });
            //申请确定
            $('.shenqing-qukuan').click(function(e){
                e.preventDefault();
                e.stopPropagation();
                var _this = $(this);
                var apply_amount = $('input[name=apply_amount]').val();
                var form = $('#shenqing-qukuan');
                _this.removeClass('shenqing-qukuan');
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{!! route('agentWithdraws.withdrawQuotaCheck') !!}",
                    data: {apply_amount:apply_amount},
                    success: function(data) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: form.attr('action'),
                            data: form.serialize(),
                            success: function (data) {
                                layer.msg('操作成功',{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                                location.reload();
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                if (xhr.responseJSON.success == false){
                                    form.find('div.msg.font-red.f14').html(xhr.responseJSON.message);
                                }
                                _this.addClass('shenqing-qukuan');
                            }
                        });
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.success == false){
                            form.find('div.msg.font-red.f14').html(xhr.responseJSON.message);
                        }
                        _this.addClass('shenqing-qukuan');
                    }
                });
            });
        });
    </script>
@endsection


