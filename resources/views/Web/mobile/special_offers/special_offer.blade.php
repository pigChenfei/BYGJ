@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-discount">
        <!--标题栏-->
        <header class="bar bar-nav">
          <h1 class="title"> </h1>
        </header>
        <!--工具栏-->
        @include('Web.mobile.layouts.index_nav')
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block mt10"><a class="item-link item-content type">
              <div class="item-inner">
                <div class="item-title">全部优惠</div>
                <input id="type" type="hidden">
              </div></a></div>
          <div class="list-block">
            <ul id="ulcontent">
                @foreach($activityList as $activity)
                <li><a class="item-link item-content" href="{!! route('homes.mobileSpecialOffer',$activity->id) !!}">
                  <!-- <div class="item-media">
                    <div class="item-img" style="background-image:url({!! $activity->imageInfo?$activity->imageInfo->imageAsset():'' !!});"></div>
                  </div> -->
                  <div class="item-inner">
                    <div class="item-title">{!! $activity->name !!}</div>
                    <!-- <div class="item-subtitle">{!! $activity->act_content() !!}</div> -->
                    <!-- <div class="item-text icon-ww icon-time_limit_activity">限时活动</div> -->
                  </div></a>
                </li>
                @endforeach
            </ul>
          </div>
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
    tools.picker($('#type'), '请选择优惠类型', ['全部优惠',{!! $str !!}])
    $('.type').click(function(){
        $('#type').picker('open')
    })
    $('#type').on('change',function(){
        $('.type .item-title').text($(this).val())
        var activate = {!! $activate !!};
        var type=0;
        for(var key in activate)
        {
            if($(this).val()==key)
            {
                type=activate[key];
            }
        }
        var data ={'type':type};
        $.ajax({ url: "{{url('/homes.special-offer') }}", data:data, success: function(e){
            console.log(e);          
            $('#ulcontent').html('');
            $('#ulcontent').append(e);
        }});
    })
  </script>
@endsection