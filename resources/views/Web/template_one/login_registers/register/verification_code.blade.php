{{--验证码--}}
<div class="form-inline clearfix">
    <label><i class="font-red">✱ </i>验证码</label>
    <input type="text" name="verification_code" id="verification_code"  class="form-control" placeholder="请填写验证码" autocomplete="off"/><span class="captchaChange">{!! \Captcha::img() !!}</span>
    <span class="valid"></span>
</div>


