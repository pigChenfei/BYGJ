{{--投注详情--}}
<main>
    <nav class="usercenter-row wash-code deposit-records records">
        <div class="layui-inline" style="margin-left: 12px;">

       </div>
        <input type="hidden" value="{!! $data['gamePlatId'] !!}" name="gamePlatId">
        <input type="hidden" value="{!! $data['betting_detail_start'] !!}" name="betting_detail_start">
        <input type="hidden" value="{!! $data['betting_detail_end'] !!}" name="betting_detail_end">
        <main>
            <table class="table table-bordered tab-checkbox "  >
                <thead >
                <tr>
                    <th>游戏局号</th>
                    <th>游戏名称</th>
                    <th>投注内容</th>
                    <th>下注金额</th>
                    <th>有效投注额</th>
                    <th>派彩金额</th>
                    <th>输赢</th>
                    <th>投注时间</th>
                </tr>
                </thead>
                <tbody id="bettingDetailTableBody">
                    @include('Web.default.player_centers.finance_statistic.betting.detail_lists')
                </tbody>
            </table>
        </main>
   </nav>
</main>
    
<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
