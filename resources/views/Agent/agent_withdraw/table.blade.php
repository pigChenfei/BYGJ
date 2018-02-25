<div class="box-body">
    <div class="col-sm-6">
        <form class="agent_withdraw">
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <th>取款银行卡</th>
                    <td>
                        @if($agentBankCard)
                            <div style="position:relative">
                            <input type="hidden" name="player_bank_card" value="{!! $agentBankCard->id !!}">
                            <img src="{!! asset('./app/img/bank_background/'.$agentBankCard->card_type.'.png') !!}" alt="" />
                            <b></b>
                            <i></i>
                            <p style=" position: absolute;left: 32px; bottom: -2px; color: white;">卡号：{!! $agentBankCard->card_account !!}</p>
                            </div>
                            @else

                        @endif
                    </td>
                    <td>
                        <div class="box-body">
                        <h5 class="pull-left" style="text-align:center;">
                            @if($agentBankCard)

                                @else
                            <button class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('agentWithdraws.create')) !!}">添加银行卡</button>
                                @endif
                        </h5>
                        </div>
                        <div class="overlay" id="overlay" style="display: none">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>可提金额</th>
                    <td style="color: red;font-weight: 900">
                        {!! \WinwinAuth::agentUser()->amount !!}
                    </td>
                    <td>
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <th>取款金额</th>
                    <td>
                        @if($agentWithdrawConf->is_allow_agent_withdraw_decimal == 0)
                        <input class="form-control" id="apply_amount" name="apply_amount" type="text" value="" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
                        @else
                        <input class="form-control" id="apply_amount" name="apply_amount" type="text" value="" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')" >
                        @endif
                    </td>
                    <td>
                        <span id="error_show" style="color: red"> *注意：单次最低提款额为{!! $agentWithdrawConf->agent_once_withdraw_min_sum !!}元,最高{!! $agentWithdrawConf->agent_once_withdraw_max_sum !!}元</span>
                    </td>
                </tr>
                <tr>
                    <th>取款密码</th>
                    <td>
                        <input class="form-control" name="pay_password" type="password" value="">
                    </td>
                    <td>
                        <span>默认取款密码:000000，修改请至账户资料页面操作。</span>
                    </td>
                </tr>
            </table>
            <div style="text-align: center;">
                {!! Form::button('确认提交', ['class' => 'btn btn-primary','type' => 'submit','id'=>'submit_button']) !!}
            </div>

        </form>
   </div>
</div>