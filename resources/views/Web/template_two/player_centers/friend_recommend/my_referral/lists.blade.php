{{--我的下线列表--}}
@if($player->items())
    @foreach($player as $play)
    <tr>
        <td>{!! $play->user_name !!}</td>
        <td>{!! $play->loginLogs->count() !!}</td>
        <td>{!! $play->login_at !!}</td>
         <td>{!! $play->created_at !!}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="2" id="referralPerPage" style="text-align: left; border: 0;">{!! \WTemplate::displayPerPage($player->total(), $player->perPage()) !!}</td>
    @if($player->links()->toHtml())
        <td colspan="2" style="text-align: right; border: 0;" id="referralPagination" >{!! $player->links() !!}</td>
    @endif
    </tr>
@else
    <tr><td colspan="4">暂无记录</td></tr>
@endif
<script>
    $(function(){
        //分页
        addEventListener("#referralPagination a ");
        //查询
        addEventListener("#referralSearch", "{!! route('players.myReferrals') !!}");
    });

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var start_time = $('input[name=referral_start]').val();
            var end_time = $('input[name=referral_end]').val();
            var status = $('select[name=recommend_status]').val();
            var perPage = $('#referralPerPage option:selected').val();
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
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#referralTableBody').html(resp);
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

