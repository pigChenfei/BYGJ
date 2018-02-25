{{--洗码记录--}}
<main style="min-height: 595px;">

  <nav class="usercenter-row wash-code deposit-records">
      <dl><b><span>财务报表</span> > <span>洗码记录</span></b></dl>
      <div class="layui-inline" style="margin-left: 12px;">
            <span class="pull-left" style="margin-top: 8px;">开始时间：</span>
          <input class="layui-input pull-left" name ='start_time' placeholder="" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          <span class="pull-left" style="margin-top: 8px;">结束时间：</span>
          <input class="layui-input pull-left" name ='end_time' placeholder="" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          <span class="pull-left" style="padding-top:8px;">状态：</span>
          <select name="" id=""  class="pull-left dropdown">
              <option value="">全部</option>
              <option value="">已结算</option>  
              <option value="">失败</option>
          </select>
          <span class="btn pull-left inquire1" id="depositSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
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
              <tbody>
	              <tr>
	                  <td>2111111117</td>
	                  <td>AG真人</td>
	                  <td>10000.00</td>
	                  <td>60.00</td>
	                  <td>2017-02-16 16:01:47</td>
	                   <td style="color: green;">已结算</td>
	              </tr>
	              <tr>
	                  <td>2111111117</td>
	                  <td>AG真人</td>
	                  <td>10000.00</td>
	                  <td>60.00</td>
	                  <td>2017-02-16 16:01:47</td>
	                  <td style="color:red;">失败</td>
	              </tr>
	              <tr>
	                  <td>2111111117</td>
	                  <td>AG捕鱼</td>
	                  <td>10000.00</td>
	                  <td>60.00</td>
	                  <td>2017-02-16 16:01:47</td>
	                  <td style="color: green;">已结算</td>
	              </tr>
	              <tr>
	                  <td>2111111117</td>
	                  <td>AG真人</td>
	                  <td>10000.00</td>
	                  <td>60.00</td>
	                  <td>2017-02-16 16:01:47</td>
	                  <td style="color: green;">已结算</td>
	              </tr>
              </tbody>
          </table>
      </main>
      <div>
        	<div id="demo7" class="pull-right" style="margin-right: -98px;margin-top: -7px;"></div>
      </div>

  </nav>
</main>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>

