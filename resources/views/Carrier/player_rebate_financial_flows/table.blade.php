@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="{!! asset('daterangepicker/daterangepicker.css') !!}">
    <link rel="stylesheet" href="{!! asset('datepicker/datepicker3.css') !!}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
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
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>会员</span>
                </div>
                <input type="text" name="search[value]" class="form-control pull-right" placeholder="账号">
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
    <h5 class="pull-left">
            <a class="btn btn-primary" onclick="
                var selectCheckbox = $('input[type=checkbox].log_check_box:checked');
                var selectedIds = selectCheckbox.map(function(index,element){ return element.value }).toArray();
                var _me = this;
                $.fn.winwinAjax.buttonActionSendAjax(
                        _me,
                    '{!! route('playerRebateFinancialFlows.passRebateFinancialFlowLog')!!}',
                    {
                        passType:'all',
                        logIds : selectedIds
                    },function(){
                        try{
                        window.LaravelDataTables.dataTableBuilder.draw()
                        }catch (e){

                        }
                    },function(){

                    },'POST');
            ">批量给予返水</a>
            <a class="btn btn-danger" onclick="
                    var selectCheckbox = $('input[type=checkbox].log_check_box:checked');
                    var selectedIds = selectCheckbox.map(function(index,element){ return element.value }).toArray();
                    var _me = this;
                    $.fn.winwinAjax.buttonActionSendAjax(
                        _me,
                        '{!! route('playerRebateFinancialFlows.passRebateFinancialFlowLog')!!}',
                    {
                        passType:'none',
                        logIds : selectedIds
                    },function(){
                        try{
                            window.LaravelDataTables.dataTableBuilder.draw()
                        }catch (e){
                        }
                    },function(){

                    },'POST');
                    ">批量返零</a>
            <a class="btn btn-primary" onclick="window.LaravelDataTables['dataTableBuilder'].ajax.reload();">刷新</a>
    </h5>
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
            //时间区间选择控件
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