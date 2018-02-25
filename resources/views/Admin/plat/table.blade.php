@section('css')
    @include('Admin.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

<div class="col-md-12">
    <div id="dataTableBuilder_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <div class="dt-buttons btn-group"></div>
        <table class="table table-bordered table-hover dataTable no-footer" id="dataTableBuilder" width="100%" style="text-align: center; width: 100%;" role="grid" aria-describedby="dataTableBuilder_info">
            <thead>
            <tr role="row">
                <th class="sorting_disabled" rowspan="1" colspan="1">Id</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">平台名称</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">父类名称</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">平台代码</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">英文名称</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">生成帐号前辍</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">大厅代码</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">状态</th>
                <th class="sorting_disabled" rowspan="1" colspan="1">创建时间</th>
                <th width="190px" class="sorting_disabled" rowspan="1" colspan="1">操作</th>
            </tr>
            </thead>
            <tbody>
            @forelse($plats as $k => $v)
            <tr role="row">
                <td>{{$k+1}}</td>
                <td>{{$v->main_game_plat_name}}</td>
                <td>顶级分类</td>
                <td>{{$v->main_game_plat_code}}</td>
                <td></td>
                <td>{{$v->account_pre}}</td>
                <td></td>
                <td>{{$v->status == true ?'正常':'已关闭'}}</td>
                <td>{{$v->created_at}}</td>
                <td>
                    <div class="btn-group">
                        <button onclick="location.href='{{route('plats.edit', $v->main_game_plat_id)}}'" class="btn btn-default btn-xs">
                            <i class="fa fa-edit">编辑</i>
                        </button>
                        <button onclick="location.href='{{route('plats.createChild', $v->main_game_plat_id)}}'"  class='btn btn-success btn-xs'>
                            <i class="fa fa-edit">添加子类</i>
                        </button>
                        {!! TableScript::createDeleteButtonScript(Route('plats.destroy',$v->main_game_plat_id)) !!}
                    </div>
                </td>
            </tr>
            @forelse($v->gamePlats as $j => $m)
                <tr role="row">
                    <td></td>
                    <td>{{$m->game_plat_name}}</td>
                    <td>{{$v->main_game_plat_name}}</td>
                    <td></td>
                    <td>{{$m->english_game_plat_name}}</td>
                    <td></td>
                    <td>{{$m->page_site}}</td>
                    <td>{{$m->status == true ?'正常':'已关闭'}}</td>
                    <td>{{$m->created_at}}</td>
                    <td>
                        <div class="btn-group">
                            <button onclick="location.href='{{route('plats.editChild', $m->game_plat_id)}}'" class="btn btn-default btn-xs">
                                <i class="fa fa-edit">编辑</i>
                            </button>
{{--                            {!! TableScript::createDeleteButtonScript(Route('plats.destroyChild',$m->game_plat_id)) !!}--}}
                            <button type="button" class="btn btn-danger btn-xs" data-shown-callback="" data-comfirm-callback="
                $.ajax({
                    url:'{{Route('plats.destroyChild',$m->game_plat_id)}}',
                    type:'POST',
                    data:{_method:'DELETE'},
                    success:function(e){
                        toastr.clear();
                        if(e.success == true){
                             toastr.success('删除成功');
                             location.reload();
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
                    </td>
                </tr>
                @empty
                @endforelse
                @empty
                <tr class="odd"><td valign="top" colspan="10" class="dataTables_empty">表中数据为空</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>