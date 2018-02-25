<div class="box-body" id="agent_information">
        <form class="agent_information">
        <table class="table table-responsive  table-bordered table-hover dataTable">
        <tr>
            <th>账号</th>
            <td>
                @if($agentAccountCenter->username)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->username !!}" disabled>
                @else
                <input class="form-control" name="username" type="text" value="" >
                @endif
            </td>
            <th>代理类型</th>
            <td>
                @if($carrierAgentLevelType)
                <input class="form-control" name="" type="text" value="{!! $carrierAgentLevelType !!}" disabled>
                @else
                <input class="form-control" name="" type="text" value="">
                @endif
            </td>
        </tr>
        <tr>
            <th>姓名</th>
            <td>
                @if($agentAccountCenter->realname)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->realname !!}" disabled>
                @else
                <input class="form-control" name="realname" type="text" value="">
                @endif
            </td>
            <th>账户余额</th>
            <td>
                @if($agentAccountCenter->amount)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->amount !!}" disabled>
                @else
                <input class="form-control" name="amount" type="text" value="">
                @endif
            </td>
        </tr>
        <tr>
            <th>出生日期</th>
            <td>
                @if($agentAccountCenter->birthday)
                    <input type="text" class="form-control pull-right" id="datepicker" value="{!! $agentAccountCenter->birthday !!}" disabled>
                @else
                    <input type="text" name="birthday" class="form-control pull-right" id="datepicker" value="1970-01-01">
                @endif
            </td>
            <th>会员礼金</th>
            <td>
                @if($agentAccountCenter->experience_amount)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->experience_amount !!}" disabled>
                @else
                <input class="form-control" name="experience_amount" type="text" value="">
                @endif
            </td>
        </tr>
        <tr>
            <th>邮箱地址</th>
            <td>
                @if($agentAccountCenter->email)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->email !!}" disabled>
                @else
                <input class="form-control" name="email" type="text" value="">
                @endif
            </td>
            <th>邀请码</th>
            <td>
                @if($agentAccountCenter->promotion_code)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->promotion_code !!}" disabled>
                @else
                <input class="form-control" name="promotion_code" type="text" value="">
                @endif
            </td>
        </tr>
        <tr>
            <th>手机</th>
            <td>
                @if($agentAccountCenter->mobile)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->mobile !!}" disabled>
                @else
                <input class="form-control" name="mobile" type="text" value="">
                @endif
            </td>
            <th>会员推荐链接</th>
            <td>
                <input class="form-control" name="" type="text" value="{!! asset('homes.registerPage') !!}?recommend_code={!! $agentAccountCenter->promotion_code !!}" disabled>
            </td>
        </tr>
        <tr>
            <th>QQ</th>
            <td>
                @if($agentAccountCenter->qq)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->qq !!}" disabled>
                @else
                <input class="form-control" name="qq" type="text" value="">
                @endif
            </td>
            <th>代理推荐链接</th>
            <td>
                <input class="form-control" name="" type="text" value="{!! asset('agents.registerPage') !!}?recommend_code={!! $agentAccountCenter->promotion_code !!}" disabled>
            </td>
        </tr>
        <tr>
            <th>微信</th>
            <td>
                @if($agentAccountCenter->wechat)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->wechat !!}" disabled>
                @else
                <input class="form-control" name="wechat" type="text" value="">
                @endif
            </td>
            <th>推广网址</th>
            <td>
                @if($agentAccountCenter->promotion_url)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->promotion_url !!}" disabled>
                @else
                <input class="form-control" name="promotion_url" type="text" value="">
                @endif
            </td>
        </tr>
        <tr>
            <th>skype</th>
            <td>
                @if($agentAccountCenter->skype)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->skype !!}" disabled>
                @else
                <input class="form-control" name="skype" type="text" value="">
                @endif
            </td>
            <th rowspan="2">邀请介绍</th>
            <td rowspan="2">
                @if($agentAccountCenter->promotion_notion)
                <textarea class="form-control" name="" rows="3" id="promotion_notion" disabled>{!! $agentAccountCenter->promotion_notion !!}</textarea>
                @else
                <textarea class="form-control" name="promotion_notion" id="promotion_notion" rows="3"></textarea>
                @endif
            </td>
        </tr>
        <tr>
            <th>注册日期</th>
            <td>
                @if($agentAccountCenter->created_at)
                <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->created_at !!}" disabled>
                @else
                <input class="form-control" name="created_at" type="text" value="">
                @endif
            </td>
        </tr>
    </table>
    <div>
        {!! Form::button('确认保存', ['class' => 'btn btn-primary','type' => 'submit','id' => 'agent_information_button']) !!}
    </div>
    </form>
</div>
<div class="box-body">
    <label><h4><span style="color: red;">银行卡信息</span></h4></label>
        <table class="table table-responsive  table-bordered table-hover dataTable">
            <tr>
                <th style="width: 216px">银行</th>
                <td>
                    @if($agentAccountCenter->agentBankCard)
                    <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->agentBankCard->bankType->bank_name !!}" disabled>
                    @else
                    <input class="form-control" name="bank_name" type="text" value="" placeholder="添加银行卡信息请至快速取款页面,谢谢!">
                    @endif
                </td>
                <th style="width: 299px">开户姓名</th>
                <td>
                    @if($agentAccountCenter->agentBankCard)
                    <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->agentBankCard->card_owner_name !!}" disabled>
                    @else
                    <input class="form-control" name="card_owner_name" type="text" value="">
                    @endif
                </td>
            </tr>
            <tr>
                <th>分行</th>
                <td>
                    @if($agentAccountCenter->agentBankCard)
                    <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->agentBankCard->card_birth_place !!}" disabled>
                    @else
                    <input class="form-control" name="card_birth_place" type="text" value="">
                    @endif
                </td>
                <th>银行卡号</th>
                <td>
                    @if($agentAccountCenter->agentBankCard)
                    <input class="form-control" name="" type="text" value="{!! $agentAccountCenter->agentBankCard->card_account !!}" disabled>
                    @else
                    <input class="form-control" name="card_account" type="text" value="">
                    @endif
                </td>
            </tr>
        </table>
</div>