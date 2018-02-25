<div class="col-md-12">
    <div id="form" class="nav-tabs-custom">
        <ul class="nav nav-tabs" id="toggle_tabs">
            <li class="active"><a id="step1_button" href="#tab_1" data-toggle="tab" data-step="step1">基本信息</a></li>
            <li><a id="step2_button" href="#tab_2" data-url="{!! route('carrier.showAssignPermissions',null) !!}" data-toggle="tab" data-step="step2">权限分配</a></li>
            <li><a id="step3_button" href="#tab_3" data-url="{!! route('carrier.showCarrierUsers',null) !!}" data-toggle="tab" data-step="step3">账号管理</a></li>
            <li><a id="step4_button" href="#tab_4" data-url="{!! route('carrier.showCarrierBackUpDomain',null) !!}" data-toggle="tab" data-step="step4">备用域名</a></li>
        </ul>
        <?php
        
$carrier = isset($carrier) ? $carrier : null?>
        <div class="tab-content">
            <div class="tab-pane create-carrier-field active" id="tab_1">
                <form id="step1" role="form">
                    <div class="form-group col-sm-12">
                        <input type="hidden" class="carrier_id_field" name="id" value="{!! $carrier ? $carrier->id : null !!}">
                        <label for="name">运营商名称</label>
                        <input name="name" type="text" class="form-control" required value="{!! $carrier ? $carrier->name : null !!}">
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('site_url', '域名(不带http(s)://前缀)') !!}
                        {!! Form::text('site_url', $carrier ? $carrier->site_url : null , ['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('remain_quota', '配额') !!}
                        {!! Form::number('remain_quota', $carrier ? $carrier->remain_quota : 0, ['class' => 'form-control','required','min' => 0]) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('is_multi_agent', '是否支持多级代理') !!}
                        <select  name="is_multi_agent" class="form-control bank_type_select2" style="width: 100%;">
                             <option value="0" @if(isset($carrier)&&$carrier->is_multi_agent==0) selected @endif>否</option>
                             <option value="1" @if(isset($carrier)&&$carrier->is_multi_agent==1) selected @endif>是</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('is_forbidden', '是否禁用') !!}
                        <select  name="is_forbidden" class="form-control bank_type_select2" style="width: 100%;">
                             <option value="0" @if(isset($carrier)&&$carrier->is_forbidden==0) selected @endif>否</option>
                             <option value="1" @if(isset($carrier)&&$carrier->is_forbidden==1) selected @endif>是</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('template', 'PC默认模板') !!}
                        <select  name="template" class="form-control bank_type_select2" style="width: 100%;">
                            @foreach($template as $v)
                            @if($v->type == 1)
                             <option value="{!! $v->value !!}" @if(isset($carrier)&&$carrier->template==$v->value) selected @endif>{!! $v->alias !!}</option>
                             @endif
                             @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('template_mobile', '移动端默认模板') !!}
                        <select  name="template_mobile" class="form-control bank_type_select2" style="width: 100%;">
                             @foreach($template as $v2)
                              @if($v2->type == 2)
                             <option value="{!! $v2->value !!}" @if(isset($carrier)&&$carrier->template_mobile==$v2->value) selected @endif>{!! $v2->alias !!}</option>
                             @endif
                             @endforeach
                        </select>
    
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('template_agent', '代理默认模板') !!}
                        <select  name="template_agent" class="form-control bank_type_select2" style="width: 100%;">
                            @foreach($template as $v1)
                            @if($v1->type == 3)
                             <option value="{!! $v1->value !!}" @if(isset($carrier)&&$carrier->template_agent==$v1->value) selected @endif>{!! $v1->alias !!}</option>
                             @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('pins', '标签') !!}
                        {!! Form::text('pins', ($carrier && $carrier->pins) ? implode(',',$carrier->pins->map(function($element){ return $element->defPin->name; })->toArray()) : null, ['class' => 'form-control','required','min' => 0]) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('pins', '在线客服配置') !!}
                        {!! Form::textarea('online_service_file_path', ($carrier && $carrier->webSiteConf) ? $carrier->webSiteConf->online_service_file_path : null, ['class' => 'form-control','rows'=>6]) !!}
                    </div>
                </form>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane create-carrier-field" id="tab_2">
                <form id="step2" role="form" action="">


                </form>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane create-carrier-field" id="tab_3">
                <div id="step3" role="form">


                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane create-carrier-field" id="tab_4">
                <div id="step4" role="form">


                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- /.tab-content -->
    </div>
</div>


<script>
    $(function(){
        var currentIndex = 1;
        var hasCreatedCarrier = false;
        var stepArray = ['step1','step2','step3','step4'];
        var currentStep = stepArray[0];
        function nextStep(stepName) {
            var index = stepArray.indexOf(stepName);
            if(index == -1){
                return null;
            }
            if(index + 1 < stepArray.length){
                return stepArray[index + 1];
            }
            return null
        }

        function prefixStep(stepName){
            var index = stepArray.indexOf(stepName);
            if(index == -1){
                return null;
            }
            if(index - 1 > 0){
                return stepArray[index - 1];
            }
            return null
        }

        $('#step1').on('submit',function (e) {
            var data = $(this).serializeJson();
            e.preventDefault();
            $.fn.winwinAjax.buttonActionSendAjax(
                    window.document.getElementById('save'),
                    '{!! route('carriers.store') !!}',
                    data,
                    function(resp){
                        if(resp.data.id){
                            $('.carrier_id_field').val(resp.data.id);
                        }
                        $.fn.alertSuccess('操作成功');
                    },
                    function(){

                    },
                    'POST'
            )
        });

        $('#step2').on('submit',function (e) {
            var data = $(this).serialize();
            e.preventDefault();
            $.fn.winwinAjax.buttonActionSendAjax(
                    window.document.getElementById('save'),
                    '{!! route('carrier.saveAssignPermissions',null) !!}/' + $('.carrier_id_field').val(),
                    data,
                    function(resp){
                        $.fn.alertSuccess('操作成功');
                    },
                    function(){

                    },
                    'POST'
            )
        });


        $('#save').on('click',function () {
            $('form#'+currentStep).submit()
        });

        $('#toggle_tabs').on('click','a',function(e){
            var _me = this;
            var stepId = $(_me).attr('data-step');
            var carrierId = $('.carrier_id_field').val();
            if(!carrierId && stepId != 'step1'){
                $.fn.alertError('请先填写并保存基本信息');
                e.stopPropagation();
                return;
            }
            currentStep = stepId;
            if(stepId == 'step1'){
                return;
            }
            if(!_me.hasFetchedContent){
                var dataUrl = $(_me).attr('data-url');
                console.log(dataUrl);
                $.fn.winwinAjax.buttonActionSendAjax(
                        null,
                        dataUrl + '/' + carrierId,
                        {},
                        function (content) {
                            //_me.hasFetchedContent = true;
                            $('#'+stepId).html(content);
                        },
                        function () {

                        },
                        'GET',
                        {
                            dataType:'html'
                        }
                )
            }
        })
    })
</script>