
@foreach($activityList as $activity)
    <div class="item clearfix">
        <div class="coupon-img" style="background-image: url('{!! $activity->imageInfo?$activity->imageInfo->imageAsset():'' !!}')">
            <div class="infos">
                <div class="btns text-center">
                	@if($activity->is_active_apply == true)
                        @if($activity->canJoin == false && $activity->isApply && $activity->waiting)
                            <div class="button button-fill-purple" disabled="disabled">待审核</div>
                        @elseif($activity->canJoin == false && $activity->isApply && $activity->waiting == false)
							<div class="button button-fill-purple" disabled="disabled">已参与</div>
                        @elseif($activity->canJoin)
                            @if($activity->bonuses_type == 1 || $activity->bonuses_type == 2)
                                <div class="button button-fill-yellow @if(!\WinwinAuth::memberUser())tx_login_game @endif" @if(\WinwinAuth::memberUser()) onclick="location.href='{{route('players.deposit',['act_id'=>$activity->id])}}'" @endif>申请参与</div>
                            @else
                                <div class="button button-fill-yellow participation @if(!\WinwinAuth::memberUser())tx_login_game @endif" act_id="{!! $activity->id !!}">申请参与</div>
                            @endif
                        @else
                            <div class="button button-fill-gray" disabled="disabled">人数已满</div>
                        @endif
                    @else
                        <div class="button button-fill-gray" disabled="disabled">自动参与</div>
                    @endif
                    <div class="button button-fill-yellow check">查看详情</div>
                </div>
            </div>
        </div>
        <div class="coupon-detail">
            <div class="table det-item">
                <div class="table-cell table-tit">活动名称</div>
                <div class="table-cell">
                    <p>{!! $activity->name !!}</p>
                </div>
            </div>
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
                <div class="table-cell">{!! $activity->act_content() !!}</div>
            </div>
            <div class="table det-item mt-25">
                <div class="table-cell text-center">
                    <div class="button button-fill-yellow packup"><span class="glyphicon glyphicon-triangle-top f12"></span>&nbsp;&nbsp;收起</div>
                </div>
            </div>
        </div>
    </div>
@endforeach
