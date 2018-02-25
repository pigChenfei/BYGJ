{{--qq--}}
@if(($conf->player_qq_conf_status & 2) == 2)
    <div class="register_item">
        <label><b>*</b>QQ</label>
        <input type="text" name="qq_account" id="qq_account" placeholder="请输入QQ号" autocomplete="off"  required data-rule-qq='true' data-msg-required='qq号必填' data-msg-qq='qq号格式不正确'/>
        <span class="valid"></span> 
    </div>
@elseif(($conf->player_qq_conf_status & 1) == 1)
    <div class="register_item">
        <label>QQ</label>
        <input type="text" name="qq_account" id="qq_account" placeholder="请输入QQ号" autocomplete="off" data-rule-qq='true' data-msg-required='qq号必填' data-msg-qq='qq号格式不正确'/>
        <span class="valid"></span> 
    </div>
@else
@endif



