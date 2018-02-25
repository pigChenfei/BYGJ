{{--洗码记录--}}
<main>
  <nav class="usercenter-row wash-code records">
      <div class="layui-inline" style="margin-left: 12px;">
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='wash_code_start' placeholder="请选择开始日期" value="" readonly>
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpend" name ='wash_code_end' placeholder="请选择结束日期" readonly>
          <span class="pull-left" style="padding-top:4px;">状态：</span>
          <select name="wash_code_status" class="pull-left">
              <option value="">全部</option>
              @foreach($rebateFinancialStatus as $k=>$status)
              <option value="{!! $k !!}">{!! $status !!}</option>
              @endforeach
          </select>
          <span class="btn pull-left inquire1" id="washCodeSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
      </div>
      <main>
          <table class="table table-bordered tab-checkbox">
              <thead>
              <tr>
                  <th>洗码编号</th>
                  <th>平台</th>
                  <th>有效投注额</th>
                  <th>洗码金额</th>
                  <th>派发时间</th>
                  <th>状态</th>
              </tr>
              </thead>
              <tbody id="washCodeTableBody">
                   @include('Web.default.player_centers.finance_statistic.wash_code.lists')
              </tbody>
          </table>
      </main>
  </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $(function(){
        //分页
        //addEventListener("#washCodePagination a");
        //查询
        addEventListener("#washCodeSearch", "{!! route('players.washCodeRecords') !!}");
    });
    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var start_time = $('input[name=wash_code_start]').val();
            var end_time = $('input[name=wash_code_end]').val();
            var status = $('select[name=wash_code_status]').val();
            var perPage = $('#washCodePerPage option:selected').val();
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
                    $('#washCodeTableBody').html(resp);
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