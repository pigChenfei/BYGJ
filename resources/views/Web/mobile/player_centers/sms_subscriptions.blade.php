@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-messages">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a><a class="button button-link button-nav pull-right edit">编辑</a>
          <h1 class="title">站内短信</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block media-list">
            <div class="btns clearfix"><a class="button button-info btn-qx">全选</a><a class="button button-danger btn-del">删除</a></div>
            <ul id="mylist"></ul>
          </div>
        </div>
      </div>
      <div class="popup popup-message">
        <h1 class="tit"></h1>
        <p class="time"></p>
        <p class="text"></p><a class="button button-ww button-red close-popup">关闭</a>
      </div>
    </div>
    <div class="layui-m-layer layui-m-layer2" id="layui-m-layer0" index="0">
      <div class="layui-m-layershade"></div>
      <div class="layui-m-layermain">
        <div class="layui-m-layersection">
          <div class="layui-m-layerchild layui-m-anim-scale">
            <div class="layui-m-layercont"><i></i><i class="layui-m-layerload"></i><i></i>
              <p>加载中...</p>
            </div>
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
    $(document).on('click', '.item-inner', function(){
        var _that = $(this)
        $('.popup-message .tit').html(_that.find('.item-title').text())
        $('.popup-message .time').html(_that.find('.item-time').text())
        $('.popup-message .text').html(_that.find('.item-text').text())
        _that.parent().removeClass('newmsg');
        var url='{{url('/players.readSms') }}?sms_id='+_that.parent().parent().attr('data-id');
        var data={};
        $.ajax({ url: url,data, success: function(e){}});
        $.popup('.popup-message')
    })
    // 数据填入
    var data = {!! $str !!};

    function addItem(data){
        if(data && data.length!=0){
            for(var key in data){
                var $li = $('<li data-id='+data[key].id+'/>')
                var $itemlink = $('<div class="item-link item-content"><div class="checkbox"></div><div class="item-inner"></div></div>')
                if(data[key].isnew){
                    $itemlink.addClass('newmsg')
                }
                var $row = $('<div class="item-title-row"></div>')
                var $tit = $('<div class="item-title">'+data[key].title+'</div>')
                var $time = $('<div class="item-after item-time">'+data[key].time+'</div>')
                var $text = $('<div class="item-text">'+data[key].content+'</div>')
                $row.append($tit)
                $row.append($time)
                $itemlink.find('.item-inner').append($row)
                $itemlink.find('.item-inner').append($text)
                $li.append($itemlink)
                $('ul').append($li)
            }
        }else{
            $('.content').addClass('nomsg')
            $('.edit').hide()
        }
    }
    addItem(data)
    $(document).on('click', '.edit', function(){
        var _that = $(this)
        if(_that.hasClass('editing')){
            _that.html('编辑').removeClass('editing')
            $('.media-list').removeClass('editing')
        }else{
            _that.html('取消').addClass('editing')
            $('.media-list').addClass('editing')
        }
    })
    $(document).on('click', '.checkbox', function(){
        var _that = $(this)
        _that.toggleClass('checked')
    })
    var sms="{{url('/players.delSms') }}?";
    $(document).on('click', '.btn-del', function(){
        if($('.checked').size()>0){
            layer.open({
                content: '您确定要删除选中消息吗？'
                ,btn: ['确定', '不要']
                ,yes: function(index){
                    
                   $("#mylist .checked").each(function(index, item){
                    console.log($(item).parents("li").attr('data-id'));
                        sms+='sms_id[]='+$(item).parents("li").attr('data-id')+'&';
                    });
                    var data={};
                    $.ajax({ url: sms,data, success: function(e){
                                   
                    }});
                    $('.checked').parent().parent().remove();
                    layer.close(index);
                }
            });
        }else{
            return false
        }
    })

    $(document).on('click', '.btn-qx', function(){
        if($('#mylist .checkbox').size() != $('#mylist .checked').size() || $('#mylist .checked').size() == 0){
            $('#mylist .checkbox').addClass('checked')
        }else{
            $('#mylist .checkbox').removeClass('checked')
        }
    })
  </script>
@endsection