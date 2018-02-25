@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
        <div class="page page-current" id="page-bankcard-manager">
            <!--标题栏-->
            <header class="bar bar-nav"><a class="icon icon-left back"></a><a class="button button-link button-nav pull-right edit">编辑</a>
                <h1 class="title">银行卡管理</h1>
            </header>
            <!--内容区-->
            <div class="content native-scroll">
                <div class="checkboxlist">
                    @foreach($playerBankCards as $v)
                        <div class="ckitem" data-id="{!! $v->card_id !!}"></div>
                    @endforeach
                </div>
                <div class="cardlist">
                    @foreach($playerBankCards as $k => $v)
                        <div class="card-item bankcardbg{!! $k%8 + 1 !!}">
                            <div class="bankinfo">
                                <i class="banklogo">{!! $logoArr[$v->bankType->wap_icon] !!}</i>
                                <div class="cardinfo">
                                    <span class="bankname f16" style="top: -0.8rem;">{!! $v->bankType->bank_name !!}</span>
                                </div>
                            </div>
                            <div class="cardnum f12">{!! $v->card_account !!}</div>
                        </div>
                    @endforeach
                </div>
                <div class="btns">
                    <a class="button button-ww button-red btn-addcard" href="{!! route('playerwithdraw.addBankcardPage') !!}">添加银行卡</a>
                    <a class="button button-ww button-red btn-delcard" href="javascript:">删除银行卡</a>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-m-layer layui-m-layer2" id="layui-m-layer0" index="0">
        <div class="layui-m-layershade"></div>
        <div class="layui-m-layermain">
            <div class="layui-m-layersection">
                <div class="layui-m-layerchild layui-m-anim-scale">
                    <div class="layui-m-layercont"><i></i><i class="layui-m-layerload"></i><i></i>
                        <p>加载中...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
    <script>$.config = {router: false};</script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
    <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
    <script>
        $(document).on('click', '.edit', function(){
            var _that = $(this)
            var btn1 = $('.btn-addcard'),btn2 = $('.btn-delcard')
            if(_that.hasClass('editing')){
                _that.html('编辑').removeClass('editing')
                $('.content').removeClass('editing')
                btn1.attr('style','display:block');btn2.attr('style','display:none')
            }else{
                _that.html('取消').addClass('editing')
                $('.content').addClass('editing')
                btn1.attr('style','display:none');btn2.attr('style','display:block')
            }
        })
        $(document).on('click', '.ckitem', function(){
            var _that = $(this)
            _that.toggleClass('checked')
        })
        $(document).on('click', '.btn-delcard', function(){
            if($('.checked').size()>0){
                var card_ids = new Array();
                layer.open({
                    content: '确定要删除选中银行卡吗？'
                    ,btn: ['确定', '不要']
                    ,yes: function(index){
                        $('.checked').each(function(index, item){
                            card_ids.push($(item).attr('data-id'))
                        });
                        $.ajax({
                            type: 'post',
                            url: "{!! route('playerwithdraw.deleteBankCard') !!}",
                            data: {
                                'card_id' : card_ids
                            },
                            dataType: 'json',
                            success: function(data){
                                tools.tip('操作成功');
                                $('.checked').each(function(index, item){
                                    $('.card-item').eq($(item).index()).remove();
                                    $(item).remove()
                                })
                            },
                            error: function(xhr){
                                var mes = $.parseJSON(xhr.response);
                                $.alert(mes.message);
                                return false;
                            }
                        });
                        layer.close(index);
                    }
                });
            }else{
                $.alert('请选择要删除的银行卡');
            }
        });
    </script>
@endsection