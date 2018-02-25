@extends('Web.mobile.layouts.app')

@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-videogame">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a><a class="icon icon-search pull-right open-search" data-popup=".popup-search"></a>
          <h1 class="title">电子游艺</h1>
        </header>
        <!--内容区-->
        <div class="content">
          <div class="list-block">
            <ul class="row">
              <li class="col-40"><a class="item-content item-link gametype">
                  <div class="item-inner">
                    <div class="item-title"></div>
                    <div class="item-after">全部游戏</div>
                    <input id="gametype" type="hidden">
                  </div></a></li>
              <li class="col-60"><a class="item-content filter">
                  <div class="item-inner">
                    <div class="item-title"></div>
                    <div class="item-after open-panel">筛选</div>
                  </div></a></li>
            </ul>
          </div>
          <div class="img-block infinite-scroll native-scroll">
            <ul class="list-container clearfix"></ul>
          </div>
          <div class="infinite-scroll-preloader">
            <div class="preloader"></div>
          </div>
        </div>
        <div class="popup popup-search" id="page-search">
          <div class="bar bar-header">
            <div class="searchbar"><a class="searchbar-cancel close-popup">取消</a>
              <div class="search-input">
                <label class="icon icon-search" for="search"></label>
                <input type="search" id="search" placeholder="输入关键字...">
              </div>
            </div>
          </div>
          <!--内容区-->
          <!--div.clear-history
          a.icon-ww.ico-del
          a 清除历史搜索
          -->
          <div class="content native-scroll"></div>
          <div class="search-history" style="margin-top:2.2rem;">
            <div class="tit">热搜</div>
            <div class="hot-tag mb10 clearfix">
              <div class="tag-item">AG</div>
              <div class="tag-item">老虎机</div>
            </div>
            <!--div.tit 历史搜索-->
            <!--div.his-tag.clearfix
            div.tag-item AG
            div.tag-item 老虎机
            div.tag-item 纸牌         
            -->
          </div>
        </div>
      </div>
    </div>
    <div class="panel-overlay"></div>
    <div class="panel panel-right panel-reveal theme-white" id="panel-left-demo">
      <div class="content-block">
        <div class="gameplatform-block">
          <div class="tit">游戏平台</div>
          <div class="list-block clearfix">
              <input type="hidden" name="main_game_plat" value="">
            <div class="item active" data-node="main_game_plat" data-value="0">全部</div>
              @foreach($main_game_plat_array as $v)
                  <div class="item" data-node="main_game_plat" data-value="{{$v->main_game_plat_id}}">{{ str_replace('电子游戏','',$v->game_plat_name) }}</div>
              @endforeach
          </div>
        </div>
      </div>
      <div class="content-block">
        <div class="gametype-block">
          <div class="tit">游戏类型</div>
          <div class="list-block clearfix">
              <input type="hidden" name="game_mcategory" value="">
            <div class="item active" data-node="game_mcategory" data-value="0">全部</div>
              @foreach($game_mcategory_array as $k => $v)
                    <div class="item" data-node="game_mcategory" data-value="{{$k}}">{{ $v }}</div>
              @endforeach
          </div>
        </div>
      </div>
      <div class="content-block">
        <div class="gamelines">
          <div class="tit">赔付线数</div>
          <div class="list-block clearfix">
              <input type="hidden" name="game_lines" value="">
            <div class="item active" data-node="game_lines" data-value="0">全部</div>
              @foreach($game_lines_array as $k => $v)
                <div class="item" data-node="game_lines" data-value="{{$k}}">{{$v}}线</div>
              @endforeach
          </div>
        </div>
      </div>
      <div class="button-wrap full-bottom row"><a class="col-50 button-fill reset">重置</a><a class="col-50 button-fill button-danger close-panel sure select-sure">确定</a></div>
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
    $(function(){
        //公共参数
        var url = "{!! app('request')->url() !!}";
        var pageNumber = 1;
        var gameObject = {
            '全部游戏':{'is_all':1},
            '推荐游戏':{'is_recommend':1},
            '最新游戏':{'is_new':1},
            '我的游戏':{'is_mine':1}
        };

        //picker选择
        var game_tag = ['全部游戏','推荐游戏','最新游戏'];
        if(islogin){
            game_tag.push('我的游戏');
        }
        tools.picker($('#gametype'), '请选择游戏标签', game_tag);
        $('.gametype').click(function(){
            $('#gametype').picker('open')
        });
        $('#gametype').on('change',function(){
            $('.gametype .item-after').text($(this).val())
        });
        // 第一次加载初始化
        dataInit(pageNumber, {}, url, 'get', 'json','html');
        //picker筛选
        $(document).on('click', '.close-picker', function(){
            var name = $('#gametype').val();
            var node = gameObject[name];
            pageNumber = 1;
            dataInit(pageNumber, node, url, 'get', 'json','html');
        });
        //名字搜索筛选
        $(document).on('click','.open-search', function () {
            $.popup('.popup-search');
            $('.popup-overlay').hide()
        });
        $('#search').on('keyup',function(event){
            if(event.keyCode == 13){
                var gameName = $('#search').val();
                pageNumber = 1;
                dataInit(pageNumber, {gameName:gameName}, url, 'get', 'json','html');
                $.closeModal('.popup-search')
            }
        });
        //右侧菜单筛选
        $(document).on('click', '.item', function(){
            var node = $(this).attr('data-node');
            var val = $(this).attr('data-value');
            $('input[name='+node+']').val(val);
        });
        //右侧菜单筛选确定
        $(document).on('click', '.select-sure', function(){

            var selectObject = {
                gameName:$('#search').val(),
                main_game_plat:$('input[name=main_game_plat]').val(),
                game_mcategory:$('input[name=game_mcategory]').val(),
                game_lines:$('input[name=game_lines]').val()
            };
            var pickerC = gameObject[$('.gametype .item-after').text()];
            $.extend(selectObject, pickerC);
            pageNumber = 1;
            dataInit(pageNumber, selectObject, url, 'get', 'json','html');
        });
        
        $('.reset').on('click',function(){
            $('.panel .item').removeClass('active');
            $('.panel .list-block').each(function(index, item){
                $(item).find('.item').eq(0).addClass('active')
            });
            $(this).parent().parent().find('input').val('');
        });
        tools.toggleActive($('.item'));

        //上拉加载
        $(document).on("pageInit", "#page-videogame", function(e, id, page) {
            var loading = false;
            $(page).on('infinite', function() {
                // 如果正在加载，则退出
                if(loading) return;
                // 设置flag
                loading = true;
                // 模拟1s的加载过程
                setTimeout(function() {
                    // 重置加载flag
                    loading = false;
                    var selectObject = {
                        gameName:$('#search').val(),
                        main_game_plat:$('input[name=main_game_plat]').val(),
                        game_mcategory:$('input[name=game_mcategory]').val(),
                        game_lines:$('input[name=game_lines]').val()
                    };
                    var pickerC = gameObject[$('.gametype .item-after').text()];
                    $.extend(selectObject, pickerC);
                    dataInit(pageNumber, selectObject, url, 'get', 'json');
                    // 更新最后加载的序号
                    $.refreshScroller();
                }, 1000);
            });
        });
        //收藏按钮在收藏和已收藏样式切换
        $(document).on('click','.list-container .collect',function(event){
            event.preventDefault();
            event.stopPropagation();
            if (!islogin){
                $.confirm('想要收藏，请先登录', function () {
                    location.href ="{{ url('/homes.mobileLogin') }}";
                });
            }else{
                var _this = $(this);
                var action = _this.attr('data-action');
                var carrier_game_id = _this.attr('data-id');
                var action_change = 0;
                if (action == 0){
                    action_change = 1;
                }
                _this.removeClass('collect');
                $.ajax({
                    type: 'post',
                    url: "{{route('players.collectGame')}}",
                    data: {action:action,carrier_game_id:carrier_game_id},
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            _this.attr('data-action', action_change);
                            _this.toggleClass('icon-collection').toggleClass('icon-collection_fill').toggleClass('fontred');
                        }
                        _this.addClass('collect');
                        tools.tip(data.data);
                    },
                    error: function(xhr){
                        var o = JSON.parse(xhr.response);
                        if(o.success==false) {
                            tools.tip(o.message);
                        };
                        _this.addClass('collect');
                    }
                });
            }
        });
        //游戏链接跳转
        $(document).off('click', '.game_link').on('click', '.game_link',function (event) {
            var _this = $(this);
            if(event.target === this){
                if(!islogin){
                    location.href ="{{ url('/homes.mobileLogin') }}";
                }else {
                    $.confirm('为了确保游戏顺利进行,请确认您的余额是否充足！确定进入转账中心，取消直接进入游戏', '温馨提示',
                        function () {
                            location.href = '/players.account-transfer';
                        },
                        function () {
                            location.href = _this.attr('data-href');
                        }
                    );
                }
            }
        });
        function dataInit(page, date, url, type, dataType, is_append) {
            if(!dataType){dataType='json';}
            if(!type){type='get';}
            if(!is_append){is_append='append';}
            if(!page){page=1;}
            date.page=page;
            $.ajax({
                type: type,
                url: url,
                data: date,
                dataType: dataType,
                success: function(data){
                    var container = '';
                    $.each(data.data.data,function (index,value) {
                        container += '<li class="item-content img-item game_link" data-href="/players.joinElectronicGame/'+value.game_id+'" style="background-image: url('+value.game.game_icon_path+')">\n' +
                            '        <a href="javascript:">'+value.display_name+'</a>' +
                            is_collect(value.collect_info, value.id) +
                            '    </li>';
                    });
                    if (is_append == 'append'){
                        $('.infinite-scroll .list-container').append(container);
                    } else if(is_append == 'html'){
                        $('.infinite-scroll .list-container').html(container);
                    }
                    if ($('.list-container li').length >= data.data.total) {
                        // 加载完毕，则注销无限加载事件，以防不必要的加载
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        // 删除加载提示符
                        $('.infinite-scroll-preloader').remove();
                    }else{
                        if ($('.infinite-scroll-preloader').length <= 0){
                            $('#page-videogame .content').append('<div class="infinite-scroll-preloader">\n' +
                                '            <div class="preloader"></div>\n' +
                                '          </div>');
                            $.attachInfiniteScroll($('.infinite-scroll'))
                        }
                    }
                    pageNumber++;
                },
                error: function(xhr){

                }
            });
        }
        function is_collect(a,b){
            if (a == 1){
                return '<i class="icon-ww icon-collection_fill fontred collect" data-action="0" data-id="'+b+'"></i>';
            }else{
                return '<i class="icon-ww icon-collection collect" data-action="1" data-id="'+b+'"></i>';
            }
        }
        $.init()
    })
    $(document).on('click', '.modal-overlay', function(){
        $(this).prev().remove();
        $(this).remove();
    })
  </script>
@endsection