@section('css')
    @include('Carrier.layouts.datatables_css')
    @include('Components.ImagePicker.style')
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>状态</span>
                </div>
                <select name="status" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach(\App\Models\CarrierBankCard::statusMeta() as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endforeach
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
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection