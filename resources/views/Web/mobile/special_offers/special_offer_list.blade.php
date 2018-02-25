@foreach($activityList as $activity)
<li><a class="item-link item-content" href="{!! route('homes.mobileSpecialOffer',$activity->id) !!}">
        <!-- <div class="item-media">
            <div class="item-img" style="background-image:url({!! $activity->imageInfo->imageAsset() !!});"></div>
        </div> -->
        <div class="item-inner">
            <div class="item-title">{!! $activity->name !!}</div>
            <!-- <div class="item-subtitle">{!! $activity->act_content() !!}</div>
            <div class="item-text icon-ww icon-time_limit_activity">限时活动</div> -->
        </div>
    </a>
</li>
@endforeach
              