{{--验证码--}}
<div class="register_item">
    <label><b>*</b>验证码</label>
    <input type="text" name="verification_code" id="verification_code" placeholder="请填写验证码" autocomplete="off"/><span class="captchaChange">{!! \Captcha::img() !!}</span>
    <span class="valid"></span> 
</div>


