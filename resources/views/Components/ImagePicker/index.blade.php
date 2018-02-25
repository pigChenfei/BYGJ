<div class="input-group col-sm-12 col-lg-12">
    <span class="input-group-addon"><i class="fa fa-file-image-o"></i> 请选择图片</span>
    <select onchange="{!! isset($onchange) ? $onchange : '' !!}"  id="{!! isset($id) ? $id : '' !!}" name="{!! isset($name) ? $name : 'banner_image_ids' !!}" class="form-control banner_select2" style="width: 100%">
        <option></option>
        <?php
        if(!isset($imageCategories)){
            $imageCategories = \App\Models\Image\CarrierImageCategory::with('images')->get();
            $imageCategories = $imageCategories->filter(function($element){
                return $element->images->count() > 0;
            });
        }
        ?>
        @foreach($imageCategories as $imageCategory)
            <optgroup label="{!! $imageCategory->category_name !!}"></optgroup>
            @foreach($imageCategory->images as $image)
                <option {!! isset($vBind) && is_callable($vBind) ? $vBind($image->id) : null !!} {!! isset($default) && $default == $image->id ? 'selected' : '' !!} value="{!! $image->id !!}" data-remark="{!! $image->remark !!}">{!! $image->imageAsset() !!}</option>
            @endforeach
        @endforeach
    </select>
</div>