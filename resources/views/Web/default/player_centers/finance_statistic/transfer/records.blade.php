{{--转账记录--}}
<main>
  <nav class="usercenter-row wash-code records" style="position: relative;">
      <div class="layui-inline" style="margin-left: 12px;">
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='transfer_start' placeholder="请选择开始日期"  readonly>
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpend" name ='transfer_end' placeholder="请选择结束日期" readonly>
          <span class="pull-left" style="padding-top:4px;">状态：</span>
            <select name="transfer_status" class="pull-left">
              <option value="">全部</option>
              <option value="">成功</option>
              <option value="">失败</option>
            </select>
          <span class="btn pull-left inquire1" id="transferSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
      </div>
      <main>
          <table class="table table-bordered tab-checkbox">
              <thead>
              <tr>
                  <th>转账编号</th>
                  <th>转账时间</th>
                  <th>转账明细</th>
                  <th>转账金额</th>
                  <th>明细</th>
              </tr>
              </thead>
              <tbody id="transferTableBody">
                    @include("Web.default.player_centers.finance_statistic.transfer.lists")
              </tbody>
          </table>
      </main>
  </nav>
</main>
<script src="{!! asset('./app/js/Game-account-transfer.js') !!}"></script>
<script src="{!! asset('./app/js/financial-statement.js') !!}"></script><script>
    $(function(){
        //分页
        //addEventListener("#transferPagination a");
        //查询
        addEventListener("#transferSearch", "{!! route('players.transferRecords') !!}");
    });
    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            if(url == undefined){
                url = $(this).attr('href');
            }
            var start_time = $('input[name=transfer_start]').val();
            var end_time = $('input[name=transfer_end]').val();
            var perPage = $('#transferPerPage option:selected').val();
            var type = 'list';
            var data = {
                'type' : type,
                'perPage' : perPage,
                'start_time' : start_time,
                'end_time' : end_time
            };

            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#transferTableBody').html(resp);
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
