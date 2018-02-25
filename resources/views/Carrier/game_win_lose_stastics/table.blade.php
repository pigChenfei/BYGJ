@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
@endsection
<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-4">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>时间</span>
                </div>
                <input type="text" name="date_time_range" class="form-control pull-right" id="reservationtime">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>游戏平台</span>
                </div>
                <select name="game_plat_value" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach(\App\Models\Def\GamePlat::all() as $gamePlat)
                        <option value="{{ $gamePlat->game_plat_id }}">{!! $gamePlat->game_plat_name  !!}</option>
                    @endforeach
                </select>
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
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script src="{!! asset('daterangepicker/moment.min.js') !!}"></script>
    <script src="{!! asset('daterangepicker/daterangepicker.js') !!}"></script>
    <script src="{!! asset('datepicker/bootstrap-datepicker.js') !!}"></script>
    <script>
        $(function(){
            $('#reservationtime').daterangepicker({
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
        })
    </script>
@endsection