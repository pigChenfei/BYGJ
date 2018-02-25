@extends('Agent.layouts.app')

@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
@endsection

@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('submit','.agent_withdraw',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('agentWithdraws.withdrawRequest'),'申请',";window.location.reload();") !!}
            });
            $("#apply_amount").on('change',function () {
                var apply_amount = $("#apply_amount").val();
                var button = $("#submit_button");
                $.ajax({
                    type: 'post',
                    async: true,
                    url: "{!! route('agentWithdraws.withdrawQuotaCheck') !!}",
                    data: {
                        'apply_amount':apply_amount
                    },
                    dataType: 'json',
                    success: function(xhr){
                        if(xhr.success == true){
                            $('#error_show').text(xhr.message);
                            button.attr("disabled", false);
                        }
                    },
                    error: function(xhr){
                        if(xhr.responseJSON){
                            $('#error_show').text(xhr.responseJSON.message);
                            button.attr("disabled", true);
                        }
                    }
                });
            });
        })
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
                        <h3 class="box-title"><i class="fa fa-tag"></i>财务中心</h3>
                        <div class="box-tools">
                            <ul class="pull-right pagination-sm pagination">
                            </ul>
                        </div>
                    </div>
                    <div class="box-body">
                        <label><h4><span style="color: red;">快速取款</span></h4></label>
                        <div class="box-body">
                            @include('Agent.agent_withdraw.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! TableScript::createEditOrAddModal() !!}

@endsection


