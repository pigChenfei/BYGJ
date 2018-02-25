@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>代理名称</span>
                </div>
                <input class="form-control" style="width: 100%;" name="level_name" id="level_name" value="" placeholder="代理名称">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>代理类型</span>
                </div>
                <select name="type" class="form-control select2" style="width: 100%;">
                    <option value="">不限</option>
                    <option value="1">佣金代理</option>
                    <option value="2">洗码代理</option>
                    <option value="3">占成代理</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>是否启用</span>
                </div>
                <select name="is_running" class="form-control select2" style="width: 100%;">
                    <option value="">不限</option>
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-sm">
                <button class="btn btn-primary btn-sm" type="submit">搜索</button>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>

@section('scripts')
    @parent
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="/src/js/bootstrap-switch.js"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection