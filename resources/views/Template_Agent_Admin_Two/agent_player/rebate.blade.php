@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--会员报表-->
    <article class="memb-excl">
        <div class="art-title"></div>
        <div class="art-body">
            <h4 class="art-tit">洗码比例</h4>   
            <div class="table-wrap">
                <form method="post" action="" style="margin: 0;" id="form">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th class="text-center" width="30%">游戏平台</th>
                            <th class="text-center" width="30%">设置会员洗码比例%&nbsp;&nbsp;<i class="glyphicon glyphicon-info-sign" style="color: #d8a659;top:2px" data-toggle="tooltip" data-original-title="会员洗码比例设置为0-100之间，根据会员的洗码金额分配给会员的比例，100全部给会员，0不给会员分配"></i></th>
                            <th class="text-center">会员洗码上线&nbsp;&nbsp;<i class="glyphicon glyphicon-info-sign" style="color: #d8a659;top:2px" data-toggle="tooltip" data-original-title="会员洗码上限，填0为不设上限"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($agentpa as $key=>$rebateFinancialFlow)
                            <tr>
                                <td>
                                    {!! $rebateFinancialFlow->carrierGamePlat->gamePlat->game_plat_name !!}
                                    <input type="hidden" name="setid[{!! $key !!}]" value="{!! $rebateFinancialFlow->id !!}">
                                </td>
                                <td>
                                    <input type="number" name="player_rebate_financial_flow_rate[{!! $key !!}]" value="{!! $rebateFinancialFlow->player_rebate_financial_flow_rate !!}" class="form-control" style="width: 60%">
                                </td>
                                <td>
                                    <input type="text" name="player_rebate_financial_flow_max_amount[{!! $key !!}]" value="{!! $rebateFinancialFlow->player_rebate_financial_flow_max_amount !!}" class="form-control" style="width: 60%">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </form>
            </div>
            <div class="tg-website" style="margin-top: 20px;">
                <button class="btn btn-warning float-right sure-rebate" style="min-width: 80px;margin-left: 15px;">确定</button>
            </div>
        </div>
    </article>
<style>
    tr td input{
        margin: 0 auto;
    }
    .table > tbody > tr > td{
        vertical-align:middle;
    }
    .fa-question-circle:before {
        content: "\f059";
    }
</style>
@endsection

@section('script')
    <script>
        $(function () {
            $("[data-toggle='tooltip']").tooltip();
        });
        $('.sure-rebate').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            var _me = this;
            _me.disabled = true;
            var _originText = $(_me).text();
            var form = $('#form');
            $(_me).text('提交中...');
            $.ajax({
                url:'{{route('agentPlayers.saveRebate')}}',
                data:form.serialize(),
                type:'POST',
                dataType:'json',
                success:function(e){
                    if(e.success == true){
                        layer.msg('操作成功!',{
                            success: function(layero, index){
                                $(layero).css('top', '401.5px');
                            }
                        });
                    }else{
                        toastr.error(e.message || '编辑失败', '出错啦!')
                    }
                    _me.disabled = false;
                    $(_me).text(_originText);
                },
                error:function(xhr){
                    layer.msg(xhr.responseJSON.message,{
                        success: function(layero, index){
                            $(layero).css('top', '401.5px');
                        }
                    });
                    _me.disabled = false;
                    $(_me).text(_originText);
                }
            });

        })
    </script>
@endsection