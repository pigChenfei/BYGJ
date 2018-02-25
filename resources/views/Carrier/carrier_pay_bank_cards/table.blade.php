@section('css')
    @include('Carrier.layouts.datatables_css')
@endsection

<div class="col-md-12">
    
        <div class="col-md-1">
            <div class="input-group input-group-sm">
                <a class="btn btn-primary pull-right" style="margin-top: 10px;margin-bottom: 5px" href="http://local.winwin.ph/carrier/carrierPayBankCards/account_list">银行账户排序</a>
            </div>
        </div>
        <div class="col-md-1">
            <div class="input-group input-group-sm">
                <a class="btn btn-primary pull-right" style="margin-top: 10px;margin-bottom: 5px">启用银行账户</a>
            </div>
        </div>
        <div class="col-md-1">
            <div class="input-group input-group-sm">
                <a class="btn btn-primary pull-right" style="margin-top: 10px;margin-bottom: 5px">禁用银行账户</a>
            </div>
        </div>
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>

@section('scripts')
    @parent
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection