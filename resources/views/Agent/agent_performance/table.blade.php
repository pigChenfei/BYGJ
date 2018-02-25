<div class="col-md-12" style="padding-bottom: 28px">
    <form action="" id="searchForm">
        <div class="col-md-4">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>时间</span>
                </div>
                <input type="text" name="date_time_range" class="form-control pull-right" id="reservationtime">
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
        <table class="table table-responsive  table-bordered table-hover dataTable">
        <tr>
            <th class="col-md-3">会员总人数</th>
            <td>
                {!! $agentMemberSum !!}
            </td>
            <th class="col-md-3">推广点击</th>
            <td>
                {!! $promoteClicks !!}
            </td>
        </tr>
        <tr>
            <th>新增会员</th>
            <td>
                {!! $agentNewMemberSum !!}
            </td>
            <th>活跃会员</th>
            <td>
                {!! $activeMember !!}
            </td>
        </tr>
        <tr>
            <th>首次存款次数</th>
            <td>
                {!! $firstDepositNumber !!}
            </td>
            <th>全部存款次数</th>
            <td>
                {!! $depositNumber !!}
            </td>
        </tr>
        <tr>
            <th>全部存款金额</th>
            <td>
                {!! $depositAmount !!}
            </td>
            <th>全部取款金额</th>
            <td>
                {!! $withdrawAmount !!}
            </td>
        </tr>
        <tr>
            <th>(PT平台)投注额</th>
            <td>
                {!! $ptBettingAmount !!}
            </td>
            <th>(PT平台)公司输赢</th>
            <td>
                {!! $ptCompanyWinAmount !!}
            </td>
        </tr>
        <tr>
            <th>(AG平台)投注额</th>
            <td>
                {!! $agBettingAmount !!}
            </td>
            <th>(AG平台)公司输赢</th>
            <td>
                {!! $agCompanyWinAmount !!}
            </td>
        </tr>
        <tr>
            <th>(MG平台)投注额</th>
            <td>
                {!! $mgBettingAmount !!}
            </td>
            <th>(MG平台)公司输赢</th>
            <td>
                {!! $mgCompanyWinAmount !!}
            </td>
        </tr>
        <tr>
            <th>有效投注总额</th>
            <td>
                {!! $effectiveTotalBetting !!}
            </td>
            <th>公司总输赢</th>
            <td>
                {!! $companyWinAmount !!}
            </td>
        </tr>
    </table>
</div>