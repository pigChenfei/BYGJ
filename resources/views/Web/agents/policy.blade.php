@extends('Web.agents.layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset('./agent-data/css/index.css') !!}"/>
    <style>
        @media only screen and (max-width:768px ) {
            #navbar-collapse #myTab,#navbar-collapse #myTab li{
                display: inline-block !important;
            }
        }
    </style>
@endsection


@section('script')
    <script src="{!! asset('./agent-data/js/index.js') !!}"></script>
@endsection


@section('content')
    <!--佣金政策-->
    <div class="tab-pane fade in public" id="policy">
        <div class="container">
            <div class="banner">
                <img src="{!! asset('./agent-data/img/banner_policy.png') !!}"/>
            </div>
            <h3>佣金政策</h3>
            <div class="chapter">
                <h4>一、佣金计算</h4>
                <h5>1.推广代理佣金计算</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>级别</th>
                        <th>公司本月总盈利（CNY）</th>
                        <th>活跃玩家数最低要求</th>
                        <th>佣金百分比</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>1-3000</td>
                        <td>3</td>
                        <td>35.00%</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>3000-5000</td>
                        <td>5</td>
                        <td>40.00%</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>5000-10000</td>
                        <td>10</td>
                        <td>45.00%</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>10000-20000</td>
                        <td>20</td>
                        <td>50.00%</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>20001-12000000</td>
                        <td>50</td>
                        <td>55.00%</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="chapter">
                <h4>二、佣金统计周期和提取周期</h4>
                <p>代理以自然月为一个统计周期，即每月1号到当月最后一天。每月10号之前完成代理佣金结算，代理可在每月10号后，申请提取佣金。佣金将在三个工作日内自动转入提款账户</p>
            </div>

            <div class="chapter">
                <h4>三、佣金结算要求</h4>
                <p>1.当月至少满足有效会员5个</p>
                <p>2.账户盈利状态达到500以上</p>
                <p>3.收款人姓名必须与注册真实姓名一致</p>
            </div>

        </div>
    </div>
    @include('Web.agents.layouts.footer')
@endsection