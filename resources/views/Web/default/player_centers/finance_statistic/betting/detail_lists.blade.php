{{--投注详情列表--}}
@if($betFlowDetails->items())
    @foreach($betFlowDetails as $item)
    <tr>
        <td>{!! $item->game_flow_code !!}</td>
        <td>{!! $item->game->game_name !!}</td>
        <td>{!! $item->bet_content !!}</td>
        <td>{!! $item->bet_amount !!}</td>
        <td>{!! $item->available_bet_amount !!}</td>
        <td>{!! $item->company_payout_amount !!}</td>
        <td>{!! 0-$item->company_win_amount !!}</td>
        <td>{!! $item->created_at !!}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: left;border: 0;" id="bettingDetailPerPage">{!! \WTemplate::displayPerPage($betFlowDetails->total(), $betFlowDetails->perPage()) !!}</td>
    @if($betFlowDetails->links()->toHtml())
        <td colspan="6" style="text-align: right; border: 0;" id="bettingDetailPagination">{!! $betFlowDetails->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="8">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener("#bettingDetailPagination a");
        //搜索
        addEventListener("#bettingDetailSearch", "{!! route('players.bettingDetails')!!}");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var betting_detail_start = $('input[name=betting_detail_start]').val();
            var betting_detail_end = $('input[name=betting_detail_end]').val();
            var gamePlatId = $('input[name=gamePlatId]').val();
            var perPage = $('#bettingDetailPerPage option:selected').val();
            var type = 'list';
            var data = {
                'type' : type,
                'perPage' : perPage,
                'betting_detail_start' : betting_detail_start,
                'betting_detail_end' : betting_detail_end,
                'gamePlatId' : gamePlatId
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
                    $('#bettingDetailTableBody').html(resp)
                },
                error : function(xhr){
                    layer.close(index);
                    layer.msg('查无此记录');
                    return false;
                }
            })
        });
    }
</script>

