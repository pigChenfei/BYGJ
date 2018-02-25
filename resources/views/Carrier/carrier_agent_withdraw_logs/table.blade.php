@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>出款银行</span>
                </div>
                <select name="carrier_pay_channel_value" style="width: 100%" class="form-control disable_search_select2">
                    <option value="">--请选择--</option>
                @foreach(\App\Models\CarrierPayChannel::available()->withdrawPurpose()->with('payChannel.payChannelType')->get()->filter(function($element){
                        return $element->payChannel->payChannelType->isCompanyBankTransfer();
                    }) as $payChannel)
                        <option value="{!! $payChannel->id !!}">{!! $payChannel->payChannel->channel_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>状态</span>
                </div>
                <select name="status_value" style="width: 100%" class="form-control disable_search_select2">
                    <option value="">--请选择--</option>
                    @foreach(\App\Models\Log\CarrierAgentWithdrawLog::statusMeta() as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>账号</span>
                </div>
                <input type="text" class="form-control" name="search[value]" placeholder="账号">
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
    <script src="{!!  asset('js/vue.min.js') !!}"></script>
    @include('vendor.datatable.datatables_template')
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>

    <script src="{!! asset('datepicker/bootstrap-datepicker.js') !!}"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endsection