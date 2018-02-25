{{--存款记录--}}
<main >
  <nav class="usercenter-row wash-code deposit-records" style="position: relative;">
      <div class="layui-inline" style="margin-left: 12px;" >
          <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
          <input class="layui-input pull-left" name ='start_time' placeholder="" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
          <input class="layui-input pull-left" name ='end_time' placeholder="" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          <span class="pull-left" style="padding-top:4px;">状态：</span>
          <select name="status" id="status" class="pull-left dropdown"  >
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
                @include('Web.default.player_centers.deposit_records_list')
              </tbody>
          </table>
      </main>
   </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>

