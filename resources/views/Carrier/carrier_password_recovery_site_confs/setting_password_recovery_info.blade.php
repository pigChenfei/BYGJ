
<div class="row">
    <div class="col-sm-6">
        <div class="form-group col-sm-12 forme-center">
            {!! Form::label('is_open_email_send_function', '是否开启邮箱发送功能').(Form::required_pin()) !!}
            <?php $statusDic = \App\Models\Conf\CarrierPasswordRecoverySiteConf::statusMeta() ?>
            @foreach($statusDic as $key => $value)
                @if(isset($carrierPasswordRecoverySiteConfs) && $carrierPasswordRecoverySiteConfs instanceof \App\Models\Conf\CarrierPasswordRecoverySiteConf && $carrierPasswordRecoverySiteConfs->is_open_email_send_function ==$key)
                    <label class="radio-inline">
                        <input type="radio"  value="{!! $key !!}" name="is_open_email_send_function" checked class="square-blue"><span class="icon-spacing">{!! $value !!}</span>
                    </label>
                @else
                    <label class="radio-inline">
                        <input type="radio"  value="{!! $key !!}"  name="is_open_email_send_function" class="square-blue"><span class="icon-spacing">{!! $value !!}</span>
                    </label>
                @endif
            @endforeach
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_driver', '邮件协议') !!}
            {!! Form::text('smtp_driver', null, ['class' => 'form-control']) !!}
            <p class="text-info">协议类型，如SMTP、SMTPS等。</p>
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_server', '协议服务器地址') !!}
            {!! Form::text('smtp_server', null, ['class' => 'form-control']) !!}
            <p class="text-info">协议服务器主机名，如smtp.qq.com、smtp.163.com等。</p>
        </div>
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_encryption', '邮件加密方式') !!}
            {!! Form::text('smtp_encryption',null, ['class' => 'form-control']) !!}
            <p class="text-info">邮件加密方式，如tls、ssl或null等。</p>
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_service_port', '邮件协议端口') !!}
            {!! Form::text('smtp_service_port', null, ['class' => 'form-control']) !!}
            <p class="text-info">协议端口，如25、465等。</p>
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('mail_sender', '发件人信息') !!}
            {!! Form::text('mail_sender', null, ['class' => 'form-control']) !!}
            <p class="text-info">发件人信息，收件人上显示的发件人名称，建议填写运营商信息。</p>
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_username', '邮件账号') !!}
            {!! Form::text('smtp_username', null, ['class' => 'form-control']) !!}
            <p class="text-info">邮件账号，请填写您的设置相应协议的发信邮件账号。</p>
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-8">
            {!! Form::label('smtp_password', '邮件授权码') !!}
            {!! Form::text('smtp_password',null, ['class' => 'form-control']) !!}
            <p class="text-info">邮件授权码，请填写您的设置相应协议的发信邮件账号对应的授权码。</p>
        </div>
        <!-- Site Description Field -->

    </div>
</div>




<div class="form-group col-sm-12">
    {!! Form::button('保存当前设置', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>
<style type="text/css">
    .icon-spacing {
        font-size: 16px;
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        width: 40px;
    }
    .text-info{
        margin-bottom: 0;
    }
</style>



<script>
    $(function () {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    })

</script>


