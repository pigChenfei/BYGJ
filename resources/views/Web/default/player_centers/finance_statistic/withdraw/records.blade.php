{{--取款记录--}}
<main>
    <nav class="usercenter-row wash-code records">
        <div class="layui-inline" style="margin-left: 12px;">
            <span class="pull-left" style="margin-top: 4px;">开始时间：</span>
            <input class=" pull-left datainp wicon workinput mr25 inpstart" name ='withdraw_start' placeholder="请选择开始日期" value="" readonly>
            <span class="pull-left" style="margin-top: 4px;">结束时间：</span>
            <input class="pull-left datainp wicon workinput mr25 inpend" name ='withdraw_end' placeholder="请选择结束日期" readonly>
            <span class="pull-left" style="padding-top:4px;">状态：</span>
            <select name="withdraw_status" class="pull-left">
                <option value="">全部</option>
                @foreach($withdrawStatus as $k=>$status)
                <option value="{!! $k !!}">{!! $status !!}</option>
                @endforeach
            </select>
            <span class="btn pull-left inquire1" id="withdrawSearch" style="background-color: #2ac0ff;color: white;border-radius: 2px;">查询</span>
        </div>
        <main>
            <table class="table table-bordered tab-checkbox" style="margin: 0 10px;">
                <thead>
                <tr>
                    <th>取款编号</th>
                    <th>取款时间</th>
                    <th>取款金额</th>
                    <th>手续费</th>
                    <th>实际出款</th>
                    <th>备注</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody id="withdrawTableBody">
                    @include('Web.default.player_centers.finance_statistic.withdraw.lists')
                </tbody>
            </table>
        </main>
    </nav>
</main>


<div id="deposit6" style="display: none;">
    <div class="pull-left">
        <div>取款单号：</div>
        <div>取款时间：</div>
        <div>取款金额：</div>
        <div>备注：</div>
    </div>
    <div class="pull-right">
        <p><b>1212121212122</b></p>
        <p><b>2012-03-17</b></p>
        <p><b>100.00</b></p>
        <p><b>卡号错误</b></p>

    </div>
    <div class="clearfix"></div>
    <div class="btn">确定</div>
</div>
<script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
<script>
    $(function(){
        //分页
        //addEventListener("#withdrawPagination a");
        //查询
        addEventListener("#withdrawSearch", "{!! route('players.withdrawRecords') !!}");
    });
    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var start_time = $('input[name=withdraw_start]').val();
            var end_time = $('input[name=withdraw_end]').val();
            var status = $('input[name=withdraw_status]').val();
            var perPage = $('#withdrawPerPage option:selected').val();
            var type = 'list';
            var data = {
                'type' : type,
                'perPage' : perPage,
                'start_time' : start_time,
                'end_time' : end_time,
                'status' : status
            };
            if(url == undefined){
                url = $(this).attr('href');
            }
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#withdrawTableBody').html(resp);
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

