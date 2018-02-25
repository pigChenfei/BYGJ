@extends('Agent.layouts.app')

@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i>子代理洗码报表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                @include('Agent.subordinate_agent_scatter_reports.table')
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>
    </div>

    @include('Components.player_edit_modal')
    <script>
        $(function () {

            $(document).on('click','.player_edit',function (e) {
                e.preventDefault();
                var _me = this;
                var userInfoModal = $("#userInfoEditModal");
                $.fn.overlayToggle();
                $.fn.winwinAjax.buttonActionSendAjax(_me,_me.href,{},function(content){
                    $.fn.overlayToggle();
                    userInfoModal.html(content);
                    userInfoModal.modal("show");
                },function(){

                },"GET",{dataType:"html"});
//                editDom.load(this.href,null,function () {
//                    listDom.toggle();
//                    editDom.toggle();
//                    $('#overlay').hide();
//                });
            })
        })
    </script>
@endsection