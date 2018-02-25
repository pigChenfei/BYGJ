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
                    <span>默认状态</span>
                </div>
                <select name="status" class="form-control" style="width: 100%;">
                    <option value="">不限</option>
                    <option value="1">正常</option>
                    <option value="0">关闭</option>
                </select>
            </div>
            <input type="hidden" name="gamePlatId" id="platGameIdInput" value="0">
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
    <script src="/src/js/bootstrap-switch.js"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection
