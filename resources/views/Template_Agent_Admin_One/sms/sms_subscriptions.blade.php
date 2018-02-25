@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    <section class="">
        @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
        <!--站内短信-->
            <article class="sitemsg">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">信息服务</h4>
                    <div class="table-wrap mb-20">
                        <table class="table text-center tab-checkbox">
                            <thead>
                            <tr>
                                <th width="10%" class="text-center">&nbsp;</th>
                                <th width="55%" class="text-center">主题</th>
                                <th width="20%" class="text-center">时间</th>
                                <th width="15%" class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if($stationLetterList->items())
                                    @foreach($stationLetterList as $v)
                                        <tr class="sms-list" id="mes_{!! $v->id !!}">
                                            <td ><input type="checkbox" name="message" value="{!! $v->id !!}"/></td>
                                            <td class="theme" data-value="{!! $v->id !!}"><i>{!! $v->carrierAgentNews->title !!}</i></td>
                                            <td>{!! $v->created_at !!}</td>
                                            <td>{!! empty($v->agent_view_status)?'未读':'已读' !!}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(!$stationLetterList->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="select-all" style="">
                            <span style="padding-left: 31px;margin-right: 30px;">
                                <label style="cursor: pointer"><input type="checkbox" name="select-select" style="position: relative;top: 2px;margin-right: 8px;">全选</label>
                            </span>
                            <span class="del-sms">删除</span>
                        </div>
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $stationLetterList->total() }}</i>项，共<i class="pagenum">{{ $stationLetterList->lastPage() }}</i>页，每页<i class="onpagenum">{{ $stationLetterList->perPage() }}</i>个</p>
                            </div>
                            {{ $stationLetterList->links('Web.template_one.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>

            </article>
        </div>
    </section>
    <div class="masklayer" style="display: none;">
        <div class="dialog-wrap">
            <!--删除银行卡-->
            <div class="add-card">
                <div class="dialog-head">
                    关于维护世界和平的500字文书
                </div>
                <div class="dialog-body text-center">
                    <p class="dialog-remark">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam nulla officiis quis repellat libero labore assumenda nesciunt voluptatum fuga dolor enim consectetur iste minus! Libero tenetur optio blanditiis reiciendis possimus.</p>
                </div>
                <div class="dialog-foot">
                    <button class="btn btn-warning" onclick="$(this).parents('.masklayer').hide();">确认</button>
                </div>
            </div>
            <!--关闭-->
            <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
        </div>
    </div>
@endsection

@section('script')
    {{--<script src="{!! asset('./app/js/account-security.js') !!}"></script>--}}
    <script>
        $(function () {
            //全选
            $(document).on('click', 'input[name=select-select]', function () {
                if(this.checked){
                    $("input[name='message']").prop('checked', true)
                }else{
                    $("input[name='message']").prop('checked', false)
                }
            });

            //读取站内信
            $(document).on('click', '.theme', function () {
                var _this = $(this);
                var sms_id = _this.attr('data-value') ;
                _this.removeClass('theme');
                $.ajax({
                    type: 'put',
                    async: true,
                    url: "{!! route('sms.read') !!}",
                    data: {
                    	sms_id:sms_id
                    },
                    dataType: 'json',
                    success: function(data){
                        _this.addClass('theme');
                        if(data.success == true){
                            _this.next().next().html('已读');
                            $('.masklayer').show();
                            $('.masklayer').find('.dialog-head').html(data.data.carrier_agent_news.title);
                            $('.masklayer').find('.dialog-remark').html(data.data.carrier_agent_news.remark);
                        }
                    },
                    error: function(xhr){
                        if(xhr.responseJSON){
                            layer.msg(xhr.responseJSON.message,{
                                success: function(layero, index){
                                    $(layero).css('top', '401.5px');
                                }
                            });
                        }
                        _this.addClass('theme');
                    }
                });
            }) ;

            //删除站内信
            $(".del-sms").click(function () {
                var _this = $(this);
                var make_sure = true;
                var info = $("input:checkbox[name='message']:checked");
                if (info.length == 0) {
                    layer.msg('请选择要删除的站内信',{
                        success: function(layero, index){
                            var _this = $(layero);
                            _this.css('top', '401.5px');
                        }
                    });
                    make_sure = false;
                    return false;
                }

                //把选中的站内信拼接成数组
                var sms_id = new Array();
                $.each(info, function (index, value) {
                    sms_id[index] = $(value).val(); //将站内信id存放到数组中
                });

                if (make_sure){
                    layer.confirm('确定要删除选中信息吗？', {
                            btn: ['确定','取消'], //按钮
                            success: function(layero, index){
                                var _thisT = $(layero);
                                _thisT.css('top', '401.5px');
                                _thisT.find('.layui-layer-content').css('color', '#333').css('text-align', 'center');
                            }
                        },function () {
                        _this.removeClass('del-sms');
                            $.ajax({
                                type: 'delete',
                                async: true,
                                url: "{!! route('sms.del') !!}",
                                data: {
                                    'sms_id' : sms_id
                                },
                                dataType: 'json',
                                success: function(data){
                                    if(data.success == true){
                                        layer.msg(data.data,{
                                            success: function(layero, index){
                                                var _this = $(layero);
                                                _this.css('top', '401.5px');
                                            }
                                        });
                                    }
                                    $.each(sms_id, function (index, value) {
                                        $('#mes_'+value).remove();
                                    });
                                    _this.addClass('del-sms');
                                },
                                error: function(xhr){
                                    if(xhr.responseJSON){
                                        layer.msg(xhr.responseJSON.message,{
                                            success: function(layero, index){
                                                var _this = $(layero);
                                                _this.css('top', '401.5px');
                                            }
                                        });
                                    }
                                    _this.addClass('del-sms');
                                }
                            });
                        }
                    );

                }
            })

        }) ;

    </script>
@endsection