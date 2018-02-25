{{--我的下线--}}
<main>
    <nav class="usercenter-row wash-code deposit-records">
        <div style="margin-top: 40px;margin-bottom: 30px;">
            <div class="layui-inline" style="margin-left: 12px;">
                <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
                <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='referral_start'   readonly style="margin-right: 25px;height: 30px;" >
                <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
                <input class=" pull-left datainp wicon workinput mr25 inpend" name ='referral_end'  readonly style="margin-right: 25px;height: 30px;">
                <span class="btn btn-blue inquire1" id="referralSearch" style="margin-left: 0;">查询</span>
            </div>
        </div>
        <main>
            <table class="table table-bordered like-recommend tab-checkbox" style="margin-left:10px;">
                <thead>
                <tr>
                    <th>会员账号</th>
                    <th>登录次数</th>
                    <th>最后登录时间</th>
                    <th>注册时间</th>
                </tr>
                </thead>
                <tbody id ="referralTableBody">
                @include("Web.default.player_centers.friend_recommend.my_referral.lists")
                </tbody>
            </table>
        </main>
    </nav>
</main>
<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>

