@extends('Agent.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
@endsection

@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>
    <script src="{!! asset('datepicker/bootstrap-datepicker.js') !!}"></script>
    <script>
        $(function () {
            $('#datepicker').datepicker({
                autoclose: true,
                language:'cn',
                format:'yyyy-mm-dd',
            });
            $(document).on('submit','.agent_information',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('agentAccountCenters.agentInformationUpdate'),'保存',";window.location.reload();") !!}
            });
            var inputs = document.getElementById("agent_information").getElementsByTagName("input");
            var textarea = $("#promotion_notion");
            var button = $("#agent_information_button");
            for(var i = 0; i < inputs.length; i++){
                if(inputs[i].value.length == 0){
                    return  button.show();
                }
            }
            if( textarea.val() == ""){
                return  button.show();
            }
            return button.hide();
        });
    </script>

@endsection

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="nav-tabs-custom">
            <div class="content">
                <div class="clearfix"></div>
                <div class="box box-primary color-palette-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tag"></i>账户中心</h3>
                        <div class="box-tools">
                            <ul class="pull-right pagination-sm pagination">
                            </ul>
                        </div>
                    </div>
                    <div class="box-body">
                        <label><h4><span style="color: red;">个人信息</span></h4></label>
                            @include('Agent.agent_account_center.setting_base_info')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

