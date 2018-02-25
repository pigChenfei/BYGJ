{{--会员中心导航栏--}}
    <ul id="nav" class="nav-nav id-nav players_center_nav">
        <li><a href="{!! route('/') !!}">网站首页<b></b></a></li>
        <li><a href="{!! route('players.financeCenter') !!}" >财务中心<b></b></a>
            <ul class="sub_nav">
                <li><a href="{!! route('players.financeCenter') !!}#member-deposit">账户存款</a>
                    <div class="back-nav">
                        <div></div>
                    </div>
                </li>
                <li><a href="{!! route('players.financeCenter') !!}#withdraw-money">快速取款</a></li>
                <li><a href="{!! route('players.financeCenter') !!}#account-transfer">转账中心</a></li>
                <li><a href="{!! route('players.financeCenter') !!}#real-time">实时洗码</a></li>
                <li><a href="{!! route('players.financeCenter') !!}#apply-discount">申请优惠</a></li>
            </ul>
        </li>
        <li><a href="{!! route('players.financeStatistics') !!}">财务报表<b></b></a>
            <ul class="sub_nav">
                <li><a href="{!! route('players.financeStatistics') !!}#deposit-record">存款记录</a>
                    <div class="back-nav">
                        <div></div>
                    </div>
                </li>
                <li><a href="{!! route('players.financeStatistics') !!}#withdrawal-record">取款记录</a></li>
                <li><a href="{!! route('players.financeStatistics') !!}#transfer-record">转账记录</a></li>
                <li><a href="{!! route('players.financeStatistics') !!}#wash-code-record">洗码记录</a></li>
                <li><a href="{!! route('players.financeStatistics') !!}#preferential-record">优惠记录</a></li>
                <li><a href="{!! route('players.financeStatistics') !!}#betting-record">投注记录</a></li>
            </ul>
        </li>
        <li><a href="{!! route('players.account-security') !!}">账户资料<b></b></a>
        </li>
        <li ><a href="{!! route('players.financeCenter') !!}#apply-discount">申请优惠<b></b></a></li>
        <li><a href="{!! route('players.friendRecommends') !!}">推荐好友<b></b></a>
            <ul class="sub_nav">
                <li><a href="{!! route('players.friendRecommends') !!}#my-recommends">我要推荐</a>
                    <div class="back-nav">
                        <div></div>
                    </div>
                </li>
                <li><a href="{!! route('players.friendRecommends') !!}#my-referrals">我的下线</a></li>
                <li><a href="{!! route('players.friendRecommends') !!}#account-statistics">账目统计</a></li>
            </ul>
        </li>
        <li><a href="{!! route('players.sms-subscriptions') !!}">站内短信</a>
        </li>
    </ul>