<div class='btn-group'>
    {{--<button onclick="{!! TableScript::addOrEditModalShowEventScript(route('payTypes.edit', $id)) !!}" class='btn btn-default btn-xs'>--}}
    <button onclick="location.href='{{route('plats.edit', $main_game_plat_id)}}'" class='btn btn-default btn-xs'>
        <i class="fa fa-edit">编辑</i>
    </button>
    {{--<button onclick="{!! TableScript::addOrEditModalShowEventScript(route('carriers.edit', $id)) !!}" class='btn btn-primary btn-xs'>--}}
        {{--<i class="fa fa-edit">游戏管理</i>--}}
    {{--</button>--}}
   {{-- <button onclick="
            var _me = this;
            $.fn.winwinAjax.buttonActionSendAjax(
                    _me,
            '{!! route('toggleCarrierStatus',$id) !!}',
            {},
            function(resp){
                window.LaravelDataTables.dataTableBuilder.draw()
            },
            function() {

            },
            'PATCH'
            )
            " class='btn {!!  $is_forbidden ? 'btn-success' : 'btn-danger' !!}  btn-xs'>
        <i class="fa fa-close">{!! $is_forbidden ? '开启' : '禁用' !!}</i>
    </button> --}}
    {{--{!! TableScript::createDeleteButtonScript(Route('payTypes.destroy',$id)) !!}--}}
</div>
