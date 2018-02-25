{{--数目统计列表--}}
@if($recommentdPlayer->items())
    @foreach($recommentdPlayer as $item)
        <tr>
          <td>{!! $item->user_name !!}</td>
          <td>{!! $item->depositLogs->sum('amount') !!}</td>
          <td>{!! $item->betFlowLogs->sum('bet_amount') !!}</td>
          <td>{!! $item->betFlowLogs->sum('available_bet_amount') !!}</td>
          <td>{!! $item->login_at !!}</td>
          <td>{!! $item->created_at !!}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: left; border: 0;" id="statisticPerPage">{!! \WTemplate::displayPerPage($recommentdPlayer->total(), $recommentdPlayer->perPage()) !!}</td>
    @if($recommentdPlayer->links()->toHtml())
        <td colspan="4" style="text-align: right; border: 0;" id="statisticPagination">{!! $recommentdPlayer->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="6">暂无记录</td></tr>
@endif

<script>
    $(function(){
        //分页
        addEventListener("#statisticPagination a");
        //查询
        addEventListener("#statisticSearch", "{!! route('players.accountStatistics') !!}");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var start_time = $('input[name=statistic_start]').val();
            var end_time = $('input[name=statistic_end]').val();
            var perPage = $('#statisticPerPage option:selected').val();
            var type = 'list';
            var data = {
                'type' : type,
                'perPage' : perPage,
                'start_time' : start_time,
                'end_time' : end_time
            };
            if(url == undefined){
                url = $(this).attr('href');
            }
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#statisticTableBody').html(resp);
                },
                error:function(xhr){
                    layer.close(index);
                    ayer.msg('查无此记录');
                    return false;
                }
            })
        });
    }
</script>
