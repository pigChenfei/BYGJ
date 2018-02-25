@extends('Carrier.layouts.app')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box" id="all_player_list">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 所有会员列表</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                    @include('Carrier.players.table')
                    <h5 class="pull-left">
                        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.exportInfo'))
                            <!--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('players.exportInfoFieldSelect')) !!}">导出会员信息</a>-->
                        @endif
                            <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-left: 15px" onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
                    </h5>
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

