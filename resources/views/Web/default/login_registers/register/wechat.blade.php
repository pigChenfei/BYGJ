{{--微信--}}
@if(($conf->player_wechat_conf_status & 2) == 2)
    <div class="register_item">
        <label><b>*</b>微信</label>
        <input type="text" name="wechat" id="wechat" placeholder="请输入微信号" autocomplete="off" required data-rule-wechat='true' data-msg-required='微信号必填' data-msg-wechat='微信号格式不正确'/>
        <span class="valid"></span> 
    </div>
@elseif(($conf->player_wechat_conf_status & 1) == 1)
    <div class="register_item">
        <label>微信</label>
        <input type="text" name="wechat" id="wechat" placeholder="请输入微信号" autocomplete="off" data-rule-wechat='true' data-msg-required='微信号必填' data-msg-wechat='微信号格式不正确'/>
        <span class="valid"></span> 
    </div>
@else
@endif



