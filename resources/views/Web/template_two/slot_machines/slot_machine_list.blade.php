<div class="slot-machine-img" id="gamePlat">
@foreach($ptGameList as $game)
    @if(head($game->game))
    <div class="game_item">
        <div class="slot_img" style="background: url('{!! asset($game->game->game_icon_path) !!}')"></div>
        <div class="slot_footer">

            <!--<div class="back-slot-machine-footer"></div>-->
            <div>{!! $game->display_name !!}</div>
        </div>
        <div class="cover">
            <button class="btn btn-warning"><a target="_blank" href="{!! route('players.loginPTGame',$game->game->game_code) !!}">立即开始</a></button>
            <button class="btn btn-default">免费试玩</button>
        </div>
    </div>
    @endif
@endforeach
</div>
<div style="position: absolute;     
            top: 983px;
            width: 533px;
            left: 386px;"
      id="pagination">{!! $ptGameList->links() !!}</div>
<script>
    $(function(){
        //ajax请求数据
        $('#pagination li a').on('click', function(e){
            e.preventDefault();
            var _me = this;
            var gameName = $('#searchPtGame').val();
            var url = $(_me).attr('href');
            $.ajax({
                url:url,
                data:{ gameName:gameName},
                dataType:'text',
                success:function(resp){
                    $('#gameList').html(resp);
                },
                error:function(xhr){
                    if(xhr.responseJSON.message = false){
                        layer.msg('数据不存在');
                    }
                }
            });
        });

        //搜索游戏
        $('#searchPtGame').on('blur', function(){
            var gameName = this.value;
            $.ajax({
                url:"{!! route('homes.slot-machine') !!}",
                data:{
                    'gameName': gameName,
                },
                dataType:'text',
                success:function(resp){
                    $('#gameList').html(resp);
                },
                error:function(xhr){
                    if(xhr.responseJSON.success == false){
                        layer.msg('数据不存在');
                    }
                }
            });
        });

        $(".slot-machine>div>.pull-left>div").hover(function(){
            $(this).addClass('solt-left').siblings().removeClass('solt-left');
        });
        $(document).on('mouseenter','.slot-machine-img>div',function(event){
            $(this).addClass('block').siblings().removeClass('block');
        });

        $(".nav-nav li ul li a").mouseover(function(){
            $(".back-nav>div").css("display","none");
        });
        $(".nav-nav li ul li a").mouseout(function(){
            $(".back-nav>div").css("display","block");
        });
        $(".slot-machine-select li").click(function(){
            $(this).addClass("solt-sele-nav").siblings().removeClass("solt-sele-nav");
        });

        $(".game_item .cover .btn-default").click(function(){
            layer.msg('加载中',{
                icon: 16
                ,shade: 0.01
            });
        });

        $('.game_item').hover(function(){
            $(this).find('.cover').css({
                width:0,
                height:0,
                display:'block',
                opacity:0
            }).stop().animate({
                width:205,
                height:146,
                opacity:1
            })
        },function(){
            $(this).find('.cover').css('display','none');
        });

    });
</script>