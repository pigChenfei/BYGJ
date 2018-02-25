<main>
    <nav class="usercenter-row">
        <div class=" pull-left width-top bank-kad bank-sure" ><b class="pull-left">取款银行卡</b>
            <span style="display: block;width: 76%;" class="pull-left">
                     @foreach($playerBankCards as $playerBankCard)
                   	<div>
                       	<input type="hidden" name="card_id" value="{!! $playerBankCard->card_id !!}">
                       	<img src="{!! asset('./app/img/bank_background/'.$playerBankCard->card_type.'.png') !!}" alt="" />
                      	<b></b>
                      	<i></i>
                       	<p>卡号：{!! $playerBankCard->card_account !!}</p>
                    </div>
                    @endforeach
                    <div>
                    <a><img src="{!! asset('./app/img/addbankcard.png') !!}" alt="" class="width-img"/></a><b></b>
               </div>
            </span>
        </div>
        <div class="clearfix"></div>
    </nav>
    <div>
        <div class="withdraw-main">
            <div><span><b>可取金额</b></span><b><span style="color: red;font-size: 16px;">{!! \WinwinAuth::memberUser()->main_account_amount !!}</span></b>
                @if($carrierWithdrawConf->is_display_flow_water_check != 0)
                <span style="float: right;width: 730px;">{!! $prompt_messages !!}</span>
                @endif
            </div>
            <div><span><b>取款金额</b></span>
                @if($carrierWithdrawConf->is_allow_player_withdraw_decimal == 0)
                <input type="text" class="user-wt" maxlength="15" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" />
                @else
                    <input type="text" class="user-wt" maxlength="15" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')" style="margin-left: -3px;"/>
                @endif
                    <span id="error_show">*注意：单次最低提款额为{!! $carrierWithdrawConf->player_once_withdraw_min_sum !!}元,最高{!! $carrierWithdrawConf->player_once_withdraw_max_sum !!}元</span></div>
            <div class="show_error"><span><b>取款密码</b></span><input type="password" class="user-qt" maxlength="10" /> <span id="error_show">默认取款密码:000000，修改请至账户资料页面操作。</span></div>
            <div><button id="submit_button" class="btn btn-default withdraw-true" style="color: #fff;
                background-color: #2ac0ff;
                border: none;
                margin-left: 145px;
                margin-top: 20px;
                width: 210px;
                height: 40px;">确认提交</button>
            </div>
        </div>
    </div>


    <div id="width-img" style="display: none;padding: 0;">
        <span>绑定银行卡</span>
        <div id="card_owner_name"><span>开户人</span><input type="text" placeholder="默认本人姓名" class="with-name"/></div>
        <div class="clearfix"></div>
        <div id="card_account"><span>卡号</span><input type="text" placeholder="请填写您的银行卡号"  maxlength="20" class="with-kade"/></div>
        <div class="clearfix"></div>
        <div>
        	<span>银行名称</span>
            <select name="bank_type_id" id="bank_type_id">
                @foreach($banks as $bank)
                    <option value="{!! $bank->type_id !!}">{!! $bank->bank_name !!}</option>
                @endforeach
            </select>
        </div>
        <div class="clearfix"></div>
        <div id="card_birth_place"><span>分行名称</span><input type="text" placeholder="如：岳阳市岳阳楼支行" class="subsidiary-bank"/></div>
        <div class="clearfix"></div>
        <div><button class="btn btn-default layui-layer-btn0" id="Branch-Name" >确认绑定</button></div>
    </div>
</main>


<link rel="stylesheet" href="{!! asset('./app/css/withdraw_money.css') !!}"/>
<script src="{!! asset('./app/js/select.js') !!}"></script>
<script src="{!! asset('./app/js/Finance-Center.js') !!}"></script>

