@extends('Carrier.layouts.app')
@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i>银行账户排序</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                    <div id="dataTableBuilder_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="dt-buttons btn-group"></div><div id="dataTableBuilder_processing" class="dataTables_processing panel panel-default" style="display: none;">查询中...</div>
                        <table class="table table-bordered table-hover dataTable no-footer" id="dataTableBuilder" width="100%" role="grid" aria-describedby="dataTableBuilder_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="dataTableBuilder" rowspan="1" colspan="1" style="width: 349.889px;">编号</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableBuilder" rowspan="1" colspan="1" style="width: 586.889px;">银行类型</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableBuilder" rowspan="1" colspan="1" style="width: 586.889px;">银行名称</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableBuilder" rowspan="1" colspan="1" style="width: 586.889px;">展示名称</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTableBuilder" rowspan="1" colspan="1" style="width: 586.889px;">排序</th>
                                    <th width="10%" class="sorting_disabled" rowspan="1" colspan="1" style="width: 167px;">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                        <td class="sorting_1">1</td>
                                        <td> 三方</td>
                                        <td> 国付宝</td>
                                        <td> 在线支付2</td>
                                        <td> 888</td>
                                        <td><form method="POST" action="http://local.winwin.ph/carrier/carrierActivityTypes/1" accept-charset="UTF-8">
                                                    <input name="_method" type="hidden" value="DELETE"><input name="_token" type="hidden" value="QOSVNtn3VUnFKhHAfAUzaOa7cABB0vmgVerKiT7q">
                                            <div class="btn-group">
                                            <a class="btn btn-default btn-xs" onclick="$('#overlay').show();var _me = this;this.disabled = true;$('#editAddModal').load('http://local.winwin.ph/carrier/carrierActivityTypes/1/edit',null,function(){ _me.disabled = false ;$('#overlay').hide();$('#editAddModal').modal('show');})">
                                                <i class="glyphicon glyphicon-edit">编辑</i>
                                            </a>
    <button type="button" class="btn btn-danger btn-xs" data-shown-callback="" data-comfirm-callback="
                $.ajax({
                    url:'http://local.winwin.ph/carrier/carrierActivityTypes/1',
                    type:'POST',
                    data:{_method:'DELETE'},
                    success:function(e){
                        toastr.clear();
                        if(e.success == true){
                             toastr.success('删除成功');
                             if(window.LaravelDataTables){
                                        window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                             }
                        }else{
                            toastr.error(e.message || '删除失败', '出错啦!')
                        }
                    },
                    error:function(xhr){
                        toastr.clear();
                        toastr.error(xhr.responseJSON.message || '删除失败', '出错啦!')
                    }
                })
            " data-title="确认删除?" data-content="点击确定删除当前选中项" data-toggle="modal" data-target="#myModal" onclick="">
                <i class="fa fa-trash">删除</i>
            </button>
</div>
</form>
</td></tr>
                            </tbody>
                        </table><div class="dataTables_info" id="dataTableBuilder_info" role="status" aria-live="polite"></div><div class="dataTables_paginate paging_simple_numbers" id="dataTableBuilder_paginate"></div></div>
                </div>
                <h5 class="pull-left">
                    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayBankCards.create')) !!}">添加银行账户</a>
                </h5>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    {!! TableScript::createEditOrAddModal() !!}

@endsection

