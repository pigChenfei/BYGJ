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
        html,body{width: 880px; min-width: auto;background: #FFF;}
        .member-container{
            width: 880px;
            margin: 0;
            color: rgba(0,0,0,0.8);
            padding: 0;
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
        .member-container .table-wrap .table > tbody > tr > td,
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
            line-height: 48px;
        }
    </style>
</head>
<body>
<section class="member-container">
    <div class="tablesbox">
        <div class="table-wrap">
            <table class="table text-center">
                <thead>
                <tr>
                    <th rowspan="2">游戏平台</th>
                    <th rowspan="2">有效会员</th>
                    <th colspan="6">
                        佣金收入计算
                    </th>
                    <th colspan="3">
                        洗吗收入计算
                    </th>
                </tr>
                <tr>
                    <th>公司输赢</th>
                    <th>存款优惠</th>
                    <th>红利</th>
                    <th>洗码</th>
                    <th>平台费</th>
                    <th>公司实际输赢</th>
                    <th>有效投注额</th>
                    <th>洗码比例</th>
                    <th>洗码金额</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                </tr>
                <tr>
                    <td>小计</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
                </tr>
                <tr>
                    <td>平台合计</td>
                    <td colspan="2">0.00</td>
                    <td>佣金比例</td>
                    <td>30%</td>
                    <td>佣金收入</td>
                    <td>0.00</td>
                    <td>洗码收入</td>
                    <td>0.00</td>
                    <td>子代理提成</td>
                    <td>0.00</td>
                </tr>
                <tr>
                    <td>总佣金</td>
                    <td colspan="10">0.00</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
    </div>
</section>
</body>

</html>