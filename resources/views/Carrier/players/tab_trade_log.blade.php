<div class="box" style="border-top: none;box-shadow: none;">
    <div class="col-sm-12 text-left">
        <form id="playerTradeForm" class="form-horizontal">
            <div class="form-group">
                <label for="dateRange" class="col-sm-1 control-label"
                       style="text-align: left;width: 100px;">选择时间: </label>
                <div class="input-group-btn" style="width: 280px;float: left;">
                    <button type="button" name="dateRange" class="btn btn-default pull-right" id="trade-daterange-btn">
                        <span>{!! date('Y-m-01', time()) !!} - {!! date('Y-m-d H:i:s') !!}</span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <table id="trade_log_table" class="table table-bordered table-hover dataTable" style="width: 100%">
            @include('Carrier.players.tab_trade_detail_log')
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="overlay" id="user_trade_info_over_lay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
        <h5 style="text-align: center;position: absolute;top: 57%;width: 100%;">正在获取数据...</h5>
    </div>
</div>

<script>
    $(function(){
        $('#playerTradeForm').on('submit',function (e) {
            e.preventDefault();
            $('#user_trade_info_over_lay').toggle();
            var button = $(this).find('button[type=submit]')[0];
            var value =$('#trade-daterange-btn span').html();
            $.fn.winwinAjax.buttonActionSendAjax(button,'{!! route('players.showTradeLog',$player->player_id) !!}',{
                dateRange: value,
                type:'detailSearch'
            },function (resp) {
                $('#user_trade_info_over_lay').toggle();
                $('#trade_log_table').html(resp);
            },function () {
                $('#user_trade_info_over_lay').toggle();
            }, 'GET', {
                dataType:'html'
            });
        });
        $('#trade-daterange-btn').daterangepicker(
                {
                    ranges: {
                        '今天': [moment(), moment()],
                        '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '本周': [moment().startOf('week').add(1,'days'), moment()],
                        '上周': [moment().subtract(1, 'week').startOf('week').add(1,'days'),moment().subtract(1, 'week').endOf('week').add(1,'days')],
                        '本月': [moment().startOf('month'), moment().endOf('month')],
                        '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: '{!! date('Y-m-01', time()) !!}',
                    endDate: '{!! date('Y-m-d H:i:s') !!}',
                    opens: "right",
                    locale:{
                        format: "YYYY-MM-DD HH:mm:ss",
                        applyLabel: "确定",
                        cancelLabel: "取消",
                        customRangeLabel:"自定义区间",
                    },
                },
                function (start, end) {
                    $('#trade-daterange-btn span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
                }
        );
    })
</script>