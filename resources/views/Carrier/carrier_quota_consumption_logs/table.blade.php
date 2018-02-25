@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
@endsection

<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>支付渠道</span>
                </div>
                <select style="width: 100%" name="payChannelValue" class="disable_search_select2">
                    <option value="">--请选择---</option>
                    @foreach(\App\Helpers\Caches\CarrierInfoCacheHelper::getAllCachedCarrierPayChannels() as $carrierPayChannel)
                        <option value="{!! $carrierPayChannel->id !!} ">{!! $carrierPayChannel->display_name.' '.$carrierPayChannel->payChannel->channel_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>时间</span>
                </div>
                <input type="text" name="dateTimeRange" class="form-control pull-right" id="reservationtime">
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
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>
    <script>
        $(function(){
            $('#reservationtime').on('click',function(){
                if(!this.hasBindedPicker){
                    $(this).daterangepicker({
                            startDate: '{!! date('Y-m-01', time()) !!}',
                            endDate: '{!! date('Y-m-d H:i:s') !!}',
                            timePicker24Hour: true,
                            timePickerSeconds: true,
                            timePicker: true,
                            locale:{
                                format: "YYYY-MM-DD HH:mm:ss",
                                applyLabel: "确定",
                                cancelLabel: "取消",
                            },
                            language:'cn'
                    });
                    this.hasBindedPicker = true;
                    $(this).click();
                }
            })
        })
    </script>
@endsection