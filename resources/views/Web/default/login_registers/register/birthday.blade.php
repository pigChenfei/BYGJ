{{--出生日期--}}
@if(($conf->player_birthday_conf_status & 2) == 2)
    <div class="register_item">
        <label><b>*</b>出生日期</label>
        <input type="text" name="birthday" id="birthday" required class="datainp"  placeholder="请选择出生日期" data-rule-date='true' data-msg-required='请选择出生日期' data-msg-date='请选择出生日期' autocomplete="off" readonly/>
        <span class="valid"></span>
    </div>
@elseif(($conf->player_birthday_conf_status & 1) == 1)
    <div class="register_item">
        <label>出生日期</label>
        <input type="text" name="birthday" id="birthday" class="datainp"   placeholder="请选择出生日期"  data-rule-date='true' data-msg-required='请选择出生日期' data-msg-date='请选择出生日期' autocomplete="off" readonly/>
        <span class="valid"></span>
    </div>
@else
@endif


