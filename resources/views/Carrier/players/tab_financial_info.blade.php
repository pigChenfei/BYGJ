<div class="box" style="height: 300px;border-top: none;box-shadow: none;">
    <table class="table table-bordered table-hover table-responsive">
        <tbody>
        <tr>
            <th style="width: 20%">主账户余额</th>
            <td colspan="3" style="text-align: left;width: 30%">
                {!! $player->main_account_amount !!} &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger">冻结金额: {!! $player->frozen_main_account_amount ?: 0 !!}</span>
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('players.showPlayerMainAccountAmountSettingModal') !!}',
                            { player_id: '{!! $player->player_id !!}'},
                            function (html) {
                                $('#user_financial_info_over_lay').toggle();
                                $('#editAddModal').html(html).modal('show');
                            },
                            function () {
                                $('#user_financial_info_over_lay').toggle();
                            },
                            'GET',
                            {
                                dataType:'html'
                            })
                        " class="btn btn-primary btn-xs" style="margin-left: 5px">调整余额</a>
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                        _me,
                        '{!! route('players.showPlayerBonusSettingModal') !!}',
                        { player_id: '{!! $player->player_id !!}'},
                        function (html) {
                        $('#user_financial_info_over_lay').toggle();
                        $('#editAddModal').html(html).modal('show');
                        },
                        function () {
                        $('#user_financial_info_over_lay').toggle();
                        },
                        'GET',
                        {
                        dataType:'html'
                        })
                        "  class="btn btn-danger btn-xs" style="margin-left: 5px">调整红利</a>
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                        _me,
                        '{!! route('players.showPlayerRebateFinancialFlowSettingModal') !!}',
                        { player_id: '{!! $player->player_id !!}'},
                        function (html) {
                        $('#user_financial_info_over_lay').toggle();
                        $('#editAddModal').html(html).modal('show');
                        },
                        function () {
                        $('#user_financial_info_over_lay').toggle();
                        },
                        'GET',
                        {
                        dataType:'html'
                        })
                        "  class="btn btn-warning btn-xs" style="margin-left: 5px">调整洗码</a>
            </td>
            {{--<th style="width: 20%">积分</th>--}}
            {{--<td style="text-align: left;width: 20%">--}}
                {{--{!! $player->score !!}--}}
                {{--<a class="btn btn-primary btn-xs" style="margin-left: 5px">调整积分</a></td>--}}
        </tr>
        </tbody>
    </table>

    <div class="col-sm-12 text-left">
        <form id="playerFinancialForm" class="form-horizontal">
            <div class="form-group">
                <label for="dateRange" class="col-sm-1 control-label" style="text-align: left;width: 100px;">选择时间: </label>
                <div class="input-group-btn" style="width: 280px;float: left;">
                    <button type="button" name="dateRange" class="btn btn-default pull-right" id="daterange-btn">
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

    <div id="financial_detail_info">
        @include('Carrier.players.tab_financial_detail_info')
    </div>
    <div class="overlay" id="user_financial_info_over_lay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
        <h5 style="text-align: center;position: absolute;top: 57%;width: 100%;">正在获取数据...</h5>
    </div>
</div>

<script>
    $(function(){
        $('#playerFinancialForm').on('submit',function (e) {
            e.preventDefault();
            $('#user_financial_info_over_lay').toggle();
            var button = $(this).find('button[type=submit]')[0];
            var value =$('#daterange-btn span').html();
            $.fn.winwinAjax.buttonActionSendAjax(button,'{!! route('players.showFinancial',$player->player_id) !!}',{
                dateRange: value,
                type:'detailSearch'
            },function (resp) {
                $('#user_financial_info_over_lay').toggle();
                $('#financial_detail_info').html(resp);
            },function () {
                $('#user_financial_info_over_lay').toggle();
            }, 'GET', {
                        dataType:'html'
            });
        });
        $('#daterange-btn').daterangepicker(
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
                    $('#daterange-btn span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
                }
        );
    })
</script>