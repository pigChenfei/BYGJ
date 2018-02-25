
<div class="row">
    <div class="col-sm-6">
        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('site_title', '网站标题').Form::required_pin() !!}
            {!! Form::text('site_title', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Site Key Words Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('site_key_words', '网站关键词').Form::required_pin() !!}
            {!! Form::text('site_key_words', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Site Description Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('site_description', '网站描述') !!}
            {!! Form::text('site_description', null, ['class' => 'form-control']) !!}
        </div>


        <!-- Net Bank Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('net_bank_deposit_comment', '网银存款提醒') !!}
            {!! Form::text('net_bank_deposit_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Atm Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('atm_deposit_comment', 'ATM存款提醒') !!}
            {!! Form::text('atm_deposit_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('third_part_deposit_comment', '第三方存款提醒') !!}
            {!! Form::text('third_part_deposit_comment', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('third_part_deposit_comment', '活动图片分辨率设置') !!}
            <div class="input-group">
                <span class="input-group-addon"> 宽(px)</span>
                {!! Form::number('activity_image_resolution_width',null,['class' => 'form-control','min' => 0]) !!}
                <a class="input-group-addon" >高(px)</a>
                {!! Form::number('activity_image_resolution_height',null,['class' => 'form-control', 'min' => 0]) !!}
            </div>

        </div>
        <div class="form-group col-sm-12">
             {!! Form::label('template', 'PC模板') !!}
             <select name="template" class="form-control bank_type_select2" style="width: 100%;">
                @foreach($templates as $template)
                @if($template->templates->type==1)
                <option value="{{$template->templates->value}}" @if($pctemplate==$template->templates->value) selected @endif >{{$template->templates->alias}}</option>
                @endif
                @endforeach
             </select>
        </div>
        <div class="form-group col-sm-12">
             {!! Form::label('template_mobile', '移动端模板') !!}
            <select name="template_mobile" class="form-control bank_type_select2" style="width: 100%;">
                @foreach($templates as $template)
                @if($template->templates->type==2)
                <option value="{{$template->templates->value}}" @if($template_mobile==$template->templates->value) selected @endif>{{$template->templates->alias}}</option>
                @endif
                @endforeach
             </select>
        </div>
        <div class="form-group col-sm-12">
             {!! Form::label('template_agent', '代理首页模板') !!}
            <select name="template_agent" class="form-control bank_type_select2" style="width: 100%;">
                @foreach($templates as $template)
                @if($template->templates->type==3)
                <option value="{{$template->templates->value}}" @if($template_agent==$template->templates->value) selected @endif>{{$template->templates->alias}}</option>
                @endif
                @endforeach
             </select>
        </div>
    </div>
</div>
<div class="form-group col-sm-12">
    {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>


