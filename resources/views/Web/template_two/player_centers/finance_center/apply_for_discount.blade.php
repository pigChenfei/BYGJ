@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
    <style>
        .infos > .btns.float-right > .memb-btn > button {
            min-width:78px;
        }
    </style>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
        @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
        <!--优惠-->
            <article class="discount">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">申请优惠</h4>
                    <div class="coupon-box clearfix">
                        @foreach($activityList as $activity)
                        <div class="item clearfix">
                            <div class="coupon-img" style="background-image: url('{!! $activity->imageInfo?$activity->imageInfo->imageAsset():'' !!}')">
                                <div class="infos">
                                    <div class="btns text-center">
                                        @if($activity->is_active_apply == true)
                                            @if($activity->activityAudit)
                                                <div class="button button-fill-purple" disabled="disabled">待审核</div>
                                            @elseif($activity->canJoin)
                                                @if($activity->bonuses_type == 1 || $activity->bonuses_type == 2)
                                                    <div class="button button-fill-yellow" onclick="location.href='{{route('players.deposit',['act_id'=>$activity->id])}}'">申请参与</div>
                                                @else
                                                    <div class="button button-fill-yellow participation" act_id="{!! $activity->id !!}">申请参与</div>
                                                @endif
                                            @else
                                                <div class="button button-fill-gray" disabled="disabled">已申请</div>
                                            @endif
                                        @else
                                            <div class="button button-fill-gray" disabled="disabled">自动参与</div>
                                        @endif
                                        <div href="javascript:" class="button button-fill-yellow check">查看详情</div>
                                    </div>
                                </div>
                            </div>
                            <div class="coupon-detail">
                                <div class="table det-item">
                                    <div class="table-cell table-tit">活动类型</div>
                                    <div class="table-cell">
                                        <p>@if($activity->act_type_id){!! $activity->actType->type_name !!}@else 全平台@endif</p>
                                    </div>
                                </div>
                                <div class="table det-item">
                                    <div class="table-cell table-tit">申请方式</div>
                                    <div class="table-cell">
                                        <p>@if($activity->is_active_apply == 1)主动申请@else自动申请@endif</p>
                                    </div>
                                </div>
                                <div class="table det-item">
                                    <div class="table-cell table-tit">申请规则</div>
                                    <div class="table-cell">
                                        <p>{!! $activity->resultPremiumStr() !!}</p>
                                    </div>
                                </div>
                                <div class="table det-item">
                                    <div class="table-cell table-tit ">活动内容</div>
                                    <div class="table-cell"><p>{!! $activity->act_content() !!}</p></div>
                                </div>
                                <div class="table det-item mt-25">
                                    <div class="table-cell text-center">
                                        <div class="button button-fill-yellow packup"><span class="glyphicon glyphicon-triangle-top f12"></span>&nbsp;&nbsp;收起</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
   <script>
       $(".participation").click(function(){
           var _me = $(this);
           var act_id =_me.attr('act_id');
           var originValue = this.innerHTML;
           _me.html("正在申请...").removeClass("participation");
           _me.attr("disabled",true); //设置按钮禁用
           $.ajax({
               url:"players.applyParticipate",
               data:{
                   'act_id' : act_id
               },
               type:"POST",
               success:function(data){
                   if(data.success == true){
                       _me.html("待审核").removeClass("button-fill-yellow").addClass("button-fill-purple");
                       _me.attr("disabled",true); //设置按钮禁用
                       layer.msg(data.message,{
                           success: function(layero, index){
                               var _this = $(layero);
                               _this.css('top', '401.5px');
                           }
                       });
                   }else if(data.success == false){
                       _me.html(originValue);
                       _me.attr("disabled",false);
                       layer.msg('申请失败，请重试',{
                           success: function(layero, index){
                               var _this = $(layero);
                               _this.css('top', '401.5px');
                           }
                       });
                   }
               },
               error:function(xhr){
                   _me.html(originValue);
                   _me.attr("disabled",false);
                   if(xhr.responseJSON.success ==false){
                       layer.msg(xhr.responseJSON.message,{
                           success: function(layero, index){
                               var _this = $(layero);
                               _this.css('top', '401.5px');
                           }
                       });
                   }
               }
           });
       });
   </script>
@endsection

