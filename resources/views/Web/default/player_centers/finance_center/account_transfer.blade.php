{{--转账中心--}}
<nav class="usercenter-row usercenter-nav-transfer">
    <div class="transfer">
    	<div class="pull-left"> 
    		<b>主账户余额：<span class="mainAccountAmount">{!! $main_account_amount !!}</span></b> 
    	</div>
    	<div style="display: inline-block;margin-left: 110px;cursor: pointer;"><img src="{!! asset('./app/img/btn_rescycle.png') !!}" class="recycle"/></div>
    </div>
    <div class="clearfix"></div>
    <main class="transfer-main">
        <div class="">
            <form method="POST" name="MForm" class="pull-left" id="transfer-user">
                <span class="pull-left">请选择转出账户：</span>
                <select name="transferFrom" class="transferFrom dropdown" onchange="NoDupl(this,document.MForm.transferTo)" style="width: 150px;">
                    <option selected="" value="">请选择</option>
                    <option value='main'>主账户</option>
                    @foreach($playerGameAccount as $item)
                        <option value='{!! $item->main_game_plat_code !!}'>{!! $item->main_game_plat_name !!}</option>
                    @endforeach
                </select>
                <img src="{!! asset('./app/img/translate.png') !!}" alt="">
                <span style="">请选择转入账户：</span> 
                <select name="transferTo" class="transferTo dropdown" onchange="NoDupl(this,document.MForm.transferFrom)" style="width: 150px;">
                    <option selected="" value="">请选择</option>
                    <option value='main'>主账户</option>
                    @foreach($playerGameAccount as $item)
                        <option value='{!! $item->main_game_plat_code !!}'>{!! $item->main_game_plat_name !!}</option>
                    @endforeach
                </select>

                <div class="pull-right" style="margin-right: 0;margin-left: 22px;">
                    <span style="color: #666;">金额：</span>
                    <input type="text" class="amount" name="amount" style="margin-left: 0;height: 30px;border-radius: 2px;border-color: lightgray;" maxlength="15">
                    <input class="btn btn-primary true-account" style="background-color: #2ac0ff; width: 80px;border:0;border-radius: 2px;" value="确认转账">
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </main>
    <div class="clearfix"></div>

    <ul id="favorble_table">
        @foreach($playerGameAccount as $item)
        <li class="table_item">
            <p>{!! $item->main_game_plat_name !!}</p>
            <div>
                <span id="{!! $item->main_game_plat_code !!}">{!! $item->amount !!}</span>
                <i class="refresh" id="mainGamePlatRefresh" style="float: none;display: inline-block;"></i>
                <input type="hidden" name="accountId" value="{!! $item->account_id !!}">
                <input type="hidden" name="mainGameCode" value="{!! $item->main_game_plat_code !!}">
                <div style="width: 80px;float: right;margin-top: 11px;cursor: pointer;"><img src="{!! asset('./app/img/btn_transfer.png') !!}" class="transferOneTouch"/></div>
            </div>
        </li>
        @endforeach
        <li class="clearfix"></li>
    </ul>
</nav>

<script src="{!! asset('./app/js/Finance-Center.js') !!}"></script>
<script src="{!! asset('./app/js/Game-account-transfer.js') !!}"></script>
<script>
$(function() {
    //转账
    $("#transfer-user").on("click", ".true-account", function (e) {
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
            var confimText = $(_me).val();
            var form = $(_me).parents("form"); 
            $(_me).val('转账中');
            $(_me).removeClass("true-account");

            var transferFrom = $("select[name='transferFrom'] option:selected").val();
            var transferTo = $("select[name='transferTo'] option:selected").val();
            
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
                        layer.msg('转账成功');
                        $(".amount").val('');
                        $('.mainAccountAmount').html(data.data.mainAccount);
                        $('#' + transferTo).html(data.data.transferToAccount);
                        $('#' + transferFrom).html(data.data.transferFromAccount);
                    }
                    $(_me).val(confimText);
                    $(_me).addClass("true-account");
                    return false;
                },
                error: function (xhr) {
                    if (xhr.responseJSON.success == false) {
                        layer.msg(xhr.responseJSON.message);
                    }
                    $(_me).val(confimText);
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
        var confimText = $(_me).val();
        var form = $(_me).parents("form");
        $(_me).val('回收中');
        (_me).disabled = true;
        $.ajax({
            url: "players.accountRecycle",
            type: "POST",
            success: function (data) {
                console.log(data);
                if (data.success) {
                    layer.msg('回收成功');
                    for (x in data.data.gameAccount) {
                        $('#' + x).html(data.data.gameAccount[x]);
                    }
                    $('.mainAccountAmount').html(data.data.mainAccount);
                }
                $(_me).val(confimText);
                (_me).disabled = false;
                return false;
            },
            error: function (xhr) {
                console.log(xhr);
                if (xhr.responseJSON.success == false) {
                    layer.msg(xhr.responseJSON.message);
                }
                $(_me).val(confimText);
                (_me).disabled = false;
                return false;
            }
        });
    });

    //一键转入
    $(".transferOneTouch").on("click", function (e) {
        e.preventDefault();
        var transfer = $(this);
        var mainGameCode = $(this).parent('div').siblings("input[name=mainGameCode]").val();
        $.ajax({
            url: "{!! route('players.accountTransferOneTouch') !!}",
            data: { 'transferTo':mainGameCode},
            success: function (resp) {
                if (resp.success) {
                    layer.msg('转入成功');
                    $('#'+mainGameCode).html(resp.data.transferToAccount);
                    $('.mainAccountAmount').html(resp.data.mainAccount);
                }
                return false;
            },
            error: function (xhr) {
                if (xhr.responseJSON.success == false) {
                    layer.msg(xhr.responseJSON.message);
                }
                return false;
            }
        });
    });

    //刷新
    $(".refresh").on("click", function (e) {
        e.preventDefault();
        var _me = this;
        var accountId = $(_me).siblings("input[name=accountId]").val();
        //$(_me).removeClass('refresh');
        $.ajax({
            url: "{!! route('players.accountRefresh') !!}",
            data: { 'accountId':accountId },
            success: function (data) {
                layer.msg('刷新成功');
                $(_me).siblings('span').text(data.data.amount);
                //$(_me).addClass('refresh');
                return false;
            },
            error: function (xhr) {
                if (xhr.responseJSON.success == false) {
                    layer.msg(xhr.responseJSON.message);
                }
                //$(_me).addClass('refresh');
                return false;
            }
        });
    });

});
</script>
