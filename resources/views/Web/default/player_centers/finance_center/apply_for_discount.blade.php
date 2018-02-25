{{--申请优惠--}}
<main>
    <nav class="usercenter-row  apply-discount" style="width: 1050px;">
        <main style="padding: 0 15px;">
            <?php

            ?>
            @foreach($activityList as $activity)
            <div>
                <div class="pull-left">
                    <p>{!! $activity->name !!}</p>
                    <p>
                        <button class="btn dropdown-toggle apply" style="border-radius: 2px;background-color: #fff;border: 1px solid #ddd;"><span class="details">查看详情</span>
                            <span class="caret" style="margin-left: 0px;"></span>
                        </button>
                    </p>
                </div>
                @if($activity->is_active_apply == true)
                    @if($activity->activityAudit)
                        <button class="pull-right btn participation btn-default" act_id="{!! $activity->id !!}" style="border-radius: 2px;" disabled="disabled">待审核</button>
                    @elseif($activity->canJoin)
                        <button class="pull-right btn btn-warning participation" act_id="{!! $activity->id !!}" style="border-radius: 2px;">申请参与</button>
                    @else
                        <button class="pull-right btn participation btn-default" act_id="{!! $activity->id !!}" style="border-radius: 2px;" disabled="disabled">已申请</button>
                    @endif
                @else
                    <button class="pull-right btn participation btn-default" act_id="{!! $activity->id !!}" style="border-radius: 2px;" disabled="disabled">已自动参与</button>
                @endif
                <div class="clearfix"></div>
                <div style="font-size: 12px;display: none;" class="apply-pre apply-sel">
                   {!! $activity->act_content() !!}
                 </div>
            </div>
            @endforeach
        </main>
    </nav>
</main>
<script src="{!! asset('./app/js/Finance-Center.js').'?v='.(App::environment() != 'production' ? \Carbon\Carbon::now()->toDateTimeString() : \Carbon\Carbon::now()->toDateString()) !!}"></script>

