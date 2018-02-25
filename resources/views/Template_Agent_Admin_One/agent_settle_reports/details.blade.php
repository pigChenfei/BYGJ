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
                <caption style="text-align: center;line-height: 30px;">成本分摊</caption>
                <thead style="border-top: 1px solid #c2c2c2;">
                <tr>
                    <th width="50%">类型</th>
                    <th>小计</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>存款优惠</td>
                        <td>{!! $depositAmount or '0.00' !!}</td>
                    </tr>
                    <tr>
                        <td>红利</td>
                        <td>{!! $bonusAmount or '0.00' !!}</td>
                    </tr>
                    <tr>
                        <td>洗码</td>
                        <td>{!! $totalRebateAmount or '0.00' !!}</td>
                    </tr>
                    <tr>
                        <td>存款手续费</td>
                        <td>{!! $totalInFeeAmount or '0.00' !!}</td>
                    </tr>
                    <tr>
                        <td>取款手续费</td>
                        <td>{!! $outFeeAmount or '0.00' !!}</td>
                    </tr>
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