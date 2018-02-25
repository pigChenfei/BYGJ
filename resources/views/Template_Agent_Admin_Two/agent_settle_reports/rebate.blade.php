<!DOCTYPE html>
<html lang="zh">
<head>
    <title>dataTables</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/bootstrap.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/bootstrap-datetimepicker.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/winwin_style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/member_center.css') !!}"/>
    <style type="text/css">
        html,body{background: #FFF;min-width:auto}
        .member-container{
            margin: 0;
            color: rgba(0,0,0,0.8);
            padding: 0;
            width: auto;
        }
        .member-container .table-wrap .table > thead > tr > th{
            color: rgba(0,0,0,0.8);
            background: #EEE;
            border-color: #c2c2c2;
            vertical-align: middle;
            text-align: center;
        }
        .table > thead > tr > th{
            border-top: 1px solid #ddd;
        }
        .pagenation-container .pageinfo p,
        .member-container .norecord{
            color: rgba(0,0,0,0.8);
        }
        .member-container .table-wrap,
        .member-container .table-wrap .table > thead > tr > th + th,
        .member-container .table-wrap .table > tbody > tr > td + td{
            border-color: #c2c2c2;
            padding-left: 0;
            padding-right: 0;
            border-left: 1px solid #c2c2c2;
        }
        .pagination{
            font-size: 12px;
        }
        .pagenation-container .pagination .more a{
            color: #000;
            padding-left: 2px;
            padding-right: 2px;
        }
        .member-container .table-wrap .table>thead>tr>th{
            line-height: 24px;
        }
    </style>
</head>
<body>
<section class="member-container">
    <div class="tablesbox">
        <div class="table-wrap">
            <table class="table text-center">
                <caption style="text-align: center">洗码详情</caption>
                <thead style="border-top: 1px solid #c2c2c2;">
                <tr role="row">
                    <th>游戏平台</th>
                    <th>投注额</th>
                    <th>有效投注额</th>
                    <th>比例</th>
                    <th>洗码金额</th>
                </tr>
                </thead>
                <tbody>
                @forelse($platFormFee as $pff)
                    @if(!empty($rebates[$pff->carrier_game_plat_id]))
                        <tr role="row">
                            <td>{{$pff->carrierGamePlat->gamePlat->game_plat_name}}</td>
                            <td>{{$rebates[$pff->carrier_game_plat_id]['cathectic_amount']}}</td>
                            <td>{{$rebates[$pff->carrier_game_plat_id]['available_cathectic_amount']}}</td>
                            <td>{!! $pff->agent_rebate_financial_flow_rate !!}%</td>
                            <td>{!! $rebates[$pff->carrier_game_plat_id]['rebateFinancialFlow'] !!}</td>
                        </tr>
                    @endif
                    @empty
                        <tr>
                            <td colspan="5">暂无数据</td>
                        </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
<style>
    .member-container .table-wrap .table>tbody>tr:nth-child(2n){
        background: #fff;
    }
    .member-container .table-wrap .table>tbody{
        color: rgba(0,0,0,0.8);
    }
    .member-container .table-wrap .table>tbody>tr{
        border-top: 1px solid #c2c2c2;
    }
</style>
</body>

</html>