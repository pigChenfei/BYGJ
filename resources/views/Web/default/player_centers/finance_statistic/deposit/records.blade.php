{{--存款记录--}}
<main>
  <nav class="usercenter-row wash-code records" style="position: relative;">
      <div class="layui-inline" style="margin-left: 12px;">
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='deposit_start' placeholder="请选择开始日期" value="" readonly>
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpend" name ='deposit_end' placeholder="请选择结束日期" readonly>
          <span class="pull-left" style="padding-top:4px;">状态：</span>
          <select name="deposit_status" class="pull-left">
              <option value="">全部</option>
              @foreach($orderStatus as $k => $status)
                 <option value="{!! $k !!}">{!! $status !!}</option>
              @endforeach 
          </select>
          <span class="btn pull-left inquire1" id="depositSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;padding: 4px 12px;">查询</span>
      </div>
      <main>
          <table class="table table-bordered tab-checkbox">
              <thead>
		              <tr>
		                  <th>存款编号</th>
		                  <th>存款时间</th>
		                  <th>存款类型</th>
		                  <th>存款金额</th>
		                  <th>手续费</th>
		                  <th>优惠金额</th>
		                  <th>实际到账</th>
		                  <th>处理时间</th>
		                  <th>状态</th>		
		              </tr>
              </thead>
              <tbody id="depositTableBody">
                 @include('Web.default.player_centers.finance_statistic.deposit.lists')
              </tbody>
          </table>
      </main>
   </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $(function(){
        //分页
        //addEventListener("#depositPagination a");
        //查询
        addEventListener("#depositSearch", "{!! route('players.depositRecords') !!}");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var start_time = $('input[name=deposit_start]').val();
            var end_time = $('input[name=deposit_end]').val();
            var status = $('select[name=deposit_status]').val();
            var perPage = $('#depositPerPage option:selected').val();
            var type = 'list';
            if(url == undefined){
                url = $(this).attr('href');
            }
            var data = {
                'type' : type,
                'perPage' : perPage,
                'start_time' : start_time,
                'end_time' : end_time,
                'status' : status
            };
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#depositTableBody').html(resp);
                },
                error : function(xhr){
                    layer.close(index);
                    ayer.msg('查无此记录');
                    return false;
                }
            })
        });
    }
</script>
