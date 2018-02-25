@section('css')
    @include('Carrier.layouts.datatables_css')
    @include('Components.ImagePicker.style')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{!! asset('datetimepicker/bootstrap-datetimepicker.css') !!}">
@endsection
<?php
$payChannelTypes = \App\Models\Def\PayChannelType::topPayChannelTypes();
?>
<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>支付渠道</span>
                </div>
                <select name="top_pay_channel" class="form-control disable_search_select2" id="">
                    <option value="">--请选择---</option>
                    @foreach($payChannelTypes as $payChannelType)
                        <option value="{!! $payChannelType->id !!}">{!! $payChannelType->type_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>是否启用</span>
                </div>
                <?php $statusDic = \App\Models\CarrierPayChannel::statusMeta() ?>
                <select name="status" class="form-control select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach($statusDic as $key => $value)
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
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="/src/js/bootstrap-switch.js"></script>
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script src="{!! asset('datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
    <script src="{!! asset('datetimepicker/bootstrap-datetimepicker.zh-CN.js') !!}"></script>
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection