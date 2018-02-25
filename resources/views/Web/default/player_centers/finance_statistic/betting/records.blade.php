{{--投注记录--}}
<main>
  <nav class="usercenter-row wash-code records">
      <div class="layui-inline" style="margin-left: 12px;">
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='betting_start' placeholder="请选择开始日期" value="" readonly>
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpend" name ='betting_end' placeholder="请选择结束日期" readonly>
          <span class="pull-left" style="padding-top:4px;">游戏平台：</span>
          <select name="game_plat_id" id="game_plat_id" class="pull-left" >
              <option value="0">全部平台</option>
              @foreach($gamePlat as $item)
              <option value="{!! $item->game_plat_id !!}">{!! $item->game_plat_name !!}</option>
              @endforeach
          </select>
          <span class="btn pull-left inquire1" id="bettingSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
       </div>
      <main>
          <table class="table table-bordered tab-checkbox particulars-tab1" >
              <thead>
              <tr>
                  <th>游戏平台</th>
                  <th>投注次数</th>
                  <th>投注额</th>
                  <th>有效投注额</th>
                  <th>派彩金额</th>
                  <th>总输赢</th>
                  <th>投注详情</th>
              </tr>
              </thead>
              <tbody id="bettingTableBody">
                    @include("Web.default.player_centers.finance_statistic.betting.lists")
              </tbody>
          </table>
         </main>
   </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $(function(){
        //投注记录
        addEventListener('#bettingSearch', "{!! route('players.bettingRecords') !!}");

        //投注详情
        $(".particulars-td a").on('click', function(e){
            e.preventDefault();
            var betting_detail_start = $('input[name=betting_start]').val();
            var betting_detail_end = $('input[name=betting_end]').val();
            var gamePlatId = $(this).parents('.particulars-td').siblings('input').val();
            var data = {
                'betting_detail_start' : betting_detail_start,
                'betting_detail_end' : betting_detail_end,
                'gamePlatId' : gamePlatId
            };
            var url = $(this).attr("href");
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success:function(resp){
                    layer.close(index);
                    $('#betting-record').html(resp);
                },
                error:function(xhr){
                    layer.close(index);
                    layer.msg('查无此记录');
                    return false;
                }
            })
        });
    });

    function addEventListener(selector, url){
        $(selector).on('click', function(e){
            e.preventDefault();
            var start_time = $('input[name=betting_start]').val();
            var end_time = $('input[name=betting_end]').val();
            var game_plat_id = $('select[name=game_plat_id]').val();
            var perPage = $('#bettingPerPage option:selected').val();
            var type = 'list';
            if(url == undefined){
                url = $(this).attr('href');
            }
            var data = {
                'type' : type,
                'perPage' : perPage,
                'start_time' : start_time,
                'end_time' : end_time,
                'game_plat_id' : game_plat_id
            };
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success:function(resp){
                    layer.close(index);
                    $('#bettingTableBody').html(resp);
                },
                error:function(xhr){
                    layer.close(index);
                    layer.msg('查无此记录');
                    return false;
                }
            })
        });
    }
</script>