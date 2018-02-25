@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-discount-detail">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">查看详情</h1>
        </header>
        <div class="content native-scroll">
          <div class="list-block mt10"><a class="item-content name">
              <div class="item-inner">
                <div class="item-title">{!! $activity->name !!}</div>
              </div></a>
              <!-- <a class="item-content f13 time">
              <div class="item-inner">
                <div class="item-title"></div>
                <div class="item-text icon-ww icon-time_limit_activity">限时活动</div>
              </div></a> -->
            <div class="item-content f13">
              <div class="timespace"><span>活动时间：</span><span>2017年5月6日--2018年3月31日</span></div>
            </div>
            <div class="item-content f13">
              <div class="applyway"><span>申请方式：</span><span>@if($activity->is_active_apply == 1)主动申请@else自动申请@endif</span></div>
            </div>
            <div class="item-content f13">
              <div class="example">
                <span>活动类型: </span><span>@if($activity->act_type_id){!! $activity->actType->type_name !!}@else 全平台@endif</span>
              </div>
            </div>
            <div class="item-content f13">
              <div class="example">
                <span>申请规则：</span><span>{!! $activity->resultPremiumStr() !!}</span>
              </div>
            </div>
            <div class="item-content f13">
              <div class="example">
                <p>活动内容：</p>
                {!! $activity->act_content() !!}
              </div>
            </div>
          </div>
          @if($activity->is_active_apply == 1)<a class="button button-ww button-red btn-apply" href="javascript:" data-id="{!! $activity->id !!}">申请参与</a>@endif
        </div>
      </div>
    </div>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    $('.btn-apply').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var _this = $(this);
        var act_id = _this.attr('data-id');
        _this.removeClass('btn-apply');
        $.ajax({
            type:"post",
            url:"/players.applyParticipate",
            data:{
                'act_id' : act_id
            },
            dataType:'json',
            success:function(data){
                tools.tip(data.message);
            },
            error:function(xhr){
                _this.addClass('btn-apply');
                var o = JSON.parse(xhr.response);
                tools.tip(o.message);
            }
        });
    })
  </script>
@endsection