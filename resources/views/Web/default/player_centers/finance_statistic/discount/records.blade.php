{{--优惠记录--}}
<main>
  <nav class="usercenter-row wash-code records">
      <div class="layui-inline" style="margin-left: 12px;">
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='discount_start' placeholder="请选择开始日期" value="" readonly>
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class=" pull-left datainp wicon workinput mr25 inpend" name ='discount_end' placeholder="请选择结束日期" readonly>
          <span class="pull-left" style="padding-top:4px;">状态：</span> 
          <select name="discount_status" class="pull-left">
              <option value="">全部</option>
              @foreach($carrierActivityStatus as $k=>$value)
              <option value='{!! $k !!}'>{!! $value !!}</option>
              @endforeach
          </select>
          <span class="btn pull-left inquire1" id="discountSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
         </div>
      <main>
          <table class="table table-bordered tab-checkbox">
              <thead>
		              <tr>
		                  <th>优惠编号</th>
		                  <th>优惠名称</th>
		                  <th>优惠类型</th>
		                  <th>红利金额</th>
		                  <th>审核时间</th>
		                  <th>流水要求</th>
		                  <th>备注</th>
		                  <th>状态</th>
		              </tr>
	            </thead>
              <tbody id="discountTableBody">
                 @include("Web.default.player_centers.finance_statistic.discount.lists")
              </tbody>
          </table>
      </main>
    </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $(function(){
        //分页
        //addEventListener("#discountPagination a");
        //查询
        addEventListener("#discountSearch", "{!! route('players.discountRecords') !!}");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var start_time = $('input[name=discount_start]').val();
            var end_time = $('input[name=discount_end]').val();
            var status = $('select[name=discount_status]').val();
            var perPage = $('#discountPerPage option:selected').val();
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
                    $('#discountTableBody').html(resp);
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