<footer class="text-center">
    <div class="logo-wrap">
        <img src="{!! asset('./app/template_one/img/common/footer-logo.jpg') !!}"/>
        <div class="hzline"></div>
    </div>
    <div class="fc">
        <ul class="list-unstyled">
            <li onclick="window.location.href='{!! url('homes.contactCustomer?type=about')!!}'">关于我们</li>
            <li onclick="window.location.href='{!! url('homes.contactCustomer?type=common')!!}'">常见问题</li>
            <li onclick="window.location.href='{!! url('homes.contactCustomer?type=duty')!!}'">责任博彩</li>
            <li onclick="window.location.href='{{url('agents.connectUs')}}'">联系我们</li>
            <li onclick="window.location.href='{{url('agents.registerPage')}}'">合作伙伴</li>
        </ul>
    </div>

    <h6>{!! \WinwinAuth::currentWebCarrier()->webSiteConf->site_footer_comment !!}</h6>
</footer>


