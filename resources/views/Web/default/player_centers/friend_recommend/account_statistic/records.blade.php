{{--数目统计--}}
<main>
	<nav class="usercenter-row wash-code deposit-records">
		<!--查询条件-->
		<div class="layui-inline" style="margin-left: 22px;margin-top: 40px;width: 800px;">
			<span class="pull-left" style="margin-top: 4px;">开始时间：</span>
			<input class=" pull-left datainp wicon workinput mr25 inpstart" name ='statistic_start'   readonly style="margin-right: 25px;">
			<span class="pull-left" style="margin-top: 4px;">结束时间：</span>
			<input class=" pull-left datainp wicon workinput mr25 inpend" name ='statistic_end'  readonly style="margin-right: 25px;">
			<span class="btn btn-blue inquire1" id="statisticSearch" style="margin-left: 0;">查询</span>
			<span style="margin-left: 10px;"><b>总会员数：<span style="color: red;">{!!  $statisticTotal->totalMembers !!}</span></b></span>
		</div>
		<!--会员统计报表-->
		<div style="height: initial;" class="statistics-click">
			<div class="statistics">
				<p>
					<span>总会员数</span>
					<span>有效会员数</span>
					<span>总存款额</span>
					<span>总投注额</span>
					<span>总有效投注额</span>
					<span>奖金</span>
				</p>
				<p>
					<span><b>{!! $statisticTotal->totalMembers !!}</b></span>
					<span><b>{!! $statisticTotal->availableMembers !!}</b></span>
					<span><b>{!! $statisticTotal->totalDepositAmount !!}</b></span>
					<span><b>{!! $statisticTotal->totalBetAmount !!}</b></span>
					<span><b>{!! $statisticTotal->availableTotalBetAmount !!}</b></span>
					<span style="border-right: 0;"><b>{!! $statisticTotal->totalBonus !!}</b><a class="details" data-toggle="modal" data-target="#detailsModal" id="details">查看详情</a></span>
				</p>
			</div>
		</div>
		<p class="tips">提示：推荐存款奖金结算时间为每周一结算。推荐洗码奖金结算时间为每周一结算。</p>

		<!--投注记录报表-->
		<main>
			<table class="table table-bordered" style="margin-left: 22px;">
				<thead>
				<tr>
					<th>会员账号</th>
					<th>总存款额</th>
					<th>总投注额</th>
					<th>有效投注额</th>
					<th>最后登录时间</th>
					<th>注册时间</th>
				</tr>
				</thead>
				<tbody id="statisticTableBody">
				@include("Web.default.player_centers.friend_recommend.account_statistic.lists")
				</tbody>
			</table>
		</main>
	</nav>
</main>

<!--查看详情弹框-->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>

<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $('#details').on('click', function(e){
        e.preventDefault();
        var start_time = $('input[name=statistic_start]').val();
        var end_time = $('input[name=statistic_end]').val();
        var data = {
            'start_time' : start_time,
            'end_time' : end_time
        };
        var index = layer.load(1, {
            shade: [0.1, '#fff']
        });
        $.ajax({
            url : "{!! route('players.statisticDetails') !!}",
            data : data,
            dataType : 'text',
            success : function(resp){
                layer.close(index);
                $('#detailsModal').html(resp);
            },
            error:function(xhr){
                layer.close(index);
                ayer.msg('查无此记录');
                return false;
            }
        })
    });
</script>

