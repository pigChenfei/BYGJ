{{--数目统计列表--}}
@if($statisticDetails->items())
    @foreach($statisticDetails->items() as $detail)
        <tr>
            <td>{!! $detail->user_name !!}</td>
            <td>{!! $detail->related_player_deposit_amount !!}</td>
            <td>{!! $detail->related_player_bet_amount !!}</td>
            <td>{!! $detail->related_player_validate_bet_amount !!}</td>
            <td>{!! $detail->rewardType()[$detail->reward_type] !!}</td>
            <td>{!! $detail->reward_amount !!}</td>
            <td>{!! $detail->created_at !!}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: left; border: 0;" id="detailPerPage">{!! \WTemplate::displayPerPage($statisticDetails->total(), $statisticDetails->perPage()) !!}</td>
    @if($statisticDetails->links()->toHtml())
        <td colspan="5" style="text-align: right; border: 0;" id="detailPagination">{!! $statisticDetails->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="7">暂无记录</td></tr>
@endif

<script>
    $(function(){
        //分页
        addEventListener("#detailPagination a");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var start_time = $('input[name=statistic_start]').val();
            var end_time = $('input[name=statistic_end]').val();
            var perPage = $('#detailPerPage option:selected').val();
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
                    $('#detailTableBody').html(resp);
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
