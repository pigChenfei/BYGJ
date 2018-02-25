@section('css')
    @include('Admin.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('duallist/bootstrap-duallistbox.css') !!}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>游戏平台</span>
                </div>
                <select name="game_plat_value" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach(\App\Models\Def\GamePlat::all() as $gamePlat)
                        <option value="{!! $gamePlat->game_plat_id !!}">{!! $gamePlat->game_plat_name  !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>状态</span>
                </div>
                <select name="status_value" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    <option value="{!! \App\Models\Def\Game::STATUS_AVAILABLE !!}">正常</option>
                    <option value="{!! \App\Models\Def\Game::STATUS_CLOSED !!}">关闭</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>搜索</span>
                </div>
                <input type="text" name="search[value]" class="form-control pull-right">
                <input type="hidden" name="search[regex]" value="false">
            </div>
            <input type="hidden" name="gamePlatId" id="platGameIdInput" value="2">
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-sm">
                <button class="btn btn-primary btn-sm" type="submit">搜索</button>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable','style' => 'text-align:center']) !!}
    <h5 class="pull-left">
        <div class='btn-group'>
            <button class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" onclick="
                    var selectCheckbox = $('input[type=checkbox].log_check_box:checked');
                    var selectedIds = selectCheckbox.map(function(index,element){ return element.value }).toArray();
                    var _me = this;
                    $.fn.winwinAjax.buttonActionSendAjax(
                    _me,
                    '{!! route('game.showAssignCarriersModal')!!}',
                    {
                        game_ids : selectedIds
                    },function(html){
                        $('#editAddModal').html(html).modal('show');
                    },function(){

                    },'POST',{dataType:'html'});
                    ">批量分配运营商</button>
            {{--<a class="btn btn-primary pull-left" style="margin-top: -10px;margin-bottom: 5px;margin-left: 10px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('game.create')) !!}">新增游戏</a>--}}
            <a class="btn btn-primary pull-left" style="margin-top: -10px;margin-bottom: 5px;margin-left: 10px" onclick="window.location.href='{{ route('games.create') }}'">新增游戏</a>
        </div>
    </h5>

</div>

@section('scripts')
    @include('Admin.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    @include('Components.Ajax.WinwinAjax')
    <script src="{!! asset('duallist/jquery.bootstrap-duallistbox.js') !!}"></script>
    <script>
        $(function(){
            //多选控件处理
            $('table').on('ifClicked','.selectCheckbox', function(){
                var allCheckbox = $('input[type=checkbox].log_check_box');
                if (this.checked == false){
                    allCheckbox.each(function(index,element){
                        $(element).iCheck('check');
                    })
                }else{
                    allCheckbox.each(function(index,element){
                        $(element).iCheck('uncheck');
                    })
                }
            }).on('ifChanged','.log_check_box',function(){
                var selectedCheckboxLength = $('input[type=checkbox].log_check_box:checked').length;
                var allCheckboxLength = $('input[type=checkbox].log_check_box').length;
                if(allCheckboxLength == selectedCheckboxLength){
                    $('.selectCheckbox').iCheck('check');
                }else{
                    $('.selectCheckbox').iCheck('uncheck');
                }
            });
        })
    </script>
@endsection