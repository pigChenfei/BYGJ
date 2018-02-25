{{--手机号码--}}
@if(($conf->player_phone_conf_status & 2) == 2)
    <div class="register_item">
        <label><b>*</b>手机号码</label>
        <input type="text" name="mobile" id="mobile" placeholder="请输入您的手机号码" autocomplete="off" required data-rule-phone='true' data-msg-required='手机号必填' data-msg-phone='手机号码格式不正确'/>
        <span class="valid"></span> 
    </div>
@elseif(($conf->player_phone_conf_status & 1) == 1)
    <div class="register_item"> 
        <label><b></b>手机号码</label>
        <input type="text" name="mobile" id="mobile" placeholder="请输入您的手机号码" autocomplete="off" data-rule-phone='true' data-msg-required='手机号必填' data-msg-phone='手机号码格式不正确'/>
        <span class="valid"></span> 
    </div>
@else
@endif



