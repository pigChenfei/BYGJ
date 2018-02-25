@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>状态</span>
                </div>
                <select name="user_status_value" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach(\App\Models\Player::userStatusMeta() as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>是否在线</span>
                </div>
                <select name="is_online_value" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach(\App\Models\Player::onlineMeta() as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>搜索</span>
                </div>
                <input type="text" name="search[value]" class="form-control pull-right" placeholder="账号/姓名/所属代理">
                <input type="hidden" name="search[regex]" value="false">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-sm">
                <button class="btn btn-primary btn-md" type="submit">搜索</button>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>

@section('scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>
    <script src="{!! asset('datepicker/bootstrap-datepicker.js') !!}"></script>
    <script>
        $('#dataTableBuilder').on('click', 'td a.main_account_amount_edit', function () {
            var _me = this;
            var tr = $(this).closest('tr');
            var row = window.LaravelDataTables['dataTableBuilder'].row( tr );
            var data = row.data();
        });
    </script>
@endsection