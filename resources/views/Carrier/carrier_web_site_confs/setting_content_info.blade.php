<!-- Site Notice Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('site_notice', '网站公告') !!}
    {!! Form::textarea('site_notice', null, ['class' => 'form-control','rows' => 5]) !!}
</div>

<!-- Site Footer Comment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('site_footer_comment', '网站底部说明') !!}
    {!! Form::textarea('site_footer_comment', null, ['class' => 'form-control','rows' => 5]) !!}
</div>


{{--<div class="form-group col-sm-12 col-lg-12" id="bannerImageComponent">--}}
    {{--{!! Form::label('banner_image_ids', 'Banner选择') !!}--}}
    {{--<input type="hidden" name="bannerImagesData" v-bind:value="bannerImagesData">--}}
    {{--<div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">--}}
        {{--<h5 class="col-sm-1 col-lg-1 box-title text-right">第@{{ index + 1 }}张图片 </h5>--}}
        {{--<div class="col-sm-5 col-lg-5">--}}
            {{--@include('Components.ImagePicker.index',[--}}
            {{--'vBind' => function($imageId){--}}
                {{--return 'v-bind:selected="value.selectedImageValue == '.$imageId.'"';--}}
            {{--},--}}
            {{--'onchange' => '$.fn.bannerImageComponent.bannerImageSelected(this)'--}}
            {{--])--}}
        {{--</div>--}}
        {{--<div class="col-sm-6 col-lg-6">--}}
            {{--<div class="input-group">--}}
                {{--<span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择Banner显示页面</span>--}}
                {{--<select onchange="$.fn.bannerImageComponent.bannerChanged(this.id,this)" v-bind:id="index" multiple="multiple"--}}
                        {{--class="form-control banner_page_select2"--}}
                        {{--style="width: 100%">--}}
                    {{--<option value="">--请选择---</option>--}}
                    {{--@foreach(\App\Models\Conf\CarrierWebSiteConf::sitePages() as $pageId => $pageName)--}}
                        {{--<option v-bind:selected="value.selectedBannerPages.indexOf('{!! $pageId !!}') != -1" value="{!! $pageId !!}">{!! $pageName !!}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                {{--<a class="input-group-addon" v-on:click="sortUp(index)" v-if="selectedData.length > 1 &&  index != 0 && value.selectedBannerPages.length > 0 && value.selectedImageValue != null"  style="cursor: pointer;"><i class="fa fa-arrow-up"></i></a>--}}
                {{--<a class="input-group-addon" v-on:click="sortDown(index)" v-if="selectedData.length > 1 && index != selectedData.length - 1 && selectedData[index+1].selectedBannerPages.length > 0 && selectedData[index+1].selectedImageValue != null"  style="cursor: pointer;"><i class="fa fa-arrow-down"></i></a>--}}
                {{--<a class="input-group-addon" v-if='selectedData.length > 1' v-on:click="removeData(index)"><i class="fa fa-remove"></i></a>--}}
                {{--<a class="input-group-addon" v-if='index == selectedData.length-1 && canNewRow' v-on:click="insertNewSelectData"><i class="fa fa-plus"></i></a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}


<div class="form-group col-sm-12">
    {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>

<?php
//$bannerImageGroup = [];
//foreach ($carrierWebSiteConf->bannerImages as $bannerImage){
//if(isset($bannerImageGroup[$bannerImage->banner_image_id])){
//$bannerImageGroup[$bannerImage->banner_image_id][] = ''.$bannerImage->banner_belong_page;
//}else{
//$bannerImageGroup[$bannerImage->banner_image_id] = [''.$bannerImage->banner_belong_page];
//};
//}
//$bannerImageGroup = array_map(function($imageId) use ($bannerImageGroup){
//return [
//'selectedImageValue' => ''.$imageId,
//'selectedBannerPages' => $bannerImageGroup[$imageId]
//];
//},array_keys($bannerImageGroup));
?>

{{--<script>--}}
    {{--$(function () {--}}

        {{--$.fn.bannerImageComponent = new Vue({--}}
            {{--el: '#bannerImageComponent',--}}
            {{--created: function () {--}}
                {{--this.insertNewSelectData();--}}
                {{--@if($bannerImageGroup)--}}
                        {{--this.selectedData = JSON.parse('{!! json_encode($bannerImageGroup) !!}');--}}
                {{--@endif--}}
            {{--},--}}
            {{--data: {--}}
                {{--needInitialSelect2:false,--}}
                {{--selectedData: []--}}
            {{--},--}}
            {{--methods: {--}}
                {{--insertNewSelectData: function (event) {--}}
                    {{--var obj = {--}}
                        {{--selectedImageValue:null,--}}
                        {{--selectedBannerPages:[],--}}
                    {{--};--}}
                    {{--this.selectedData.push(obj);--}}
                    {{--if (!event) {--}}
                        {{--return--}}
                    {{--}--}}
                    {{--this.needInitialSelect2 = true;--}}
                {{--},--}}
                {{--removeData:function (index) {--}}
                    {{--this.needInitialSelect2 = true;--}}
                    {{--this.selectedData.splice(index,1);--}}
                {{--},--}}
                {{--initialLastSelect2:function () {--}}
                    {{--if(this.needInitialSelect2 == false){ return }--}}
                    {{--$('.banner_page_select2').select2({--}}
                        {{--closeOnSelect: false--}}
                    {{--});--}}
                    {{--$('.banner_select2').select2({--}}
                        {{--templateResult: function (state) {--}}
                            {{--console.log(state);--}}
                            {{--if (!state.id) { return state.text; }--}}
                            {{--var $state = $(--}}
                                    {{--'<div>' +--}}
                                    {{--'<div style="width: 80px;height:50px;background: url('+state.text+') no-repeat center;background-size: cover" class="pull-left" />' +--}}
                                    {{--'<div> ' +--}}
                                    {{--'<p class="pull-left" style="margin-left: 20px;max-width: 80%;overflow: hidden;">' + state.text + '</p>' +--}}
                                    {{--'<div class="clearfix"></div>' +--}}
                                    {{--'</div>'--}}
                            {{--);--}}
                            {{--return $state;--}}
                        {{--},--}}
                        {{--minimumResultsForSearch: Infinity,--}}
                        {{--placeholder: "请选择图片"--}}
                    {{--});--}}
                    {{--this.needInitialSelect2 = false;--}}
                {{--},--}}
                {{--sortUp:function (index) {--}}
                    {{--var nextValue = this.selectedData[index - 1];--}}
                    {{--var currentValue = this.selectedData[index];--}}
                    {{--Vue.set(this.selectedData, index, nextValue);--}}
                    {{--Vue.set(this.selectedData, index - 1, currentValue);--}}
                    {{--this.needInitialSelect2 = true;--}}
                {{--},--}}
                {{--sortDown:function (index) {--}}
                    {{--var nextValue = this.selectedData[index + 1];--}}
                    {{--var currentValue = this.selectedData[index];--}}
                    {{--Vue.set(this.selectedData, index, nextValue);--}}
                    {{--Vue.set(this.selectedData, index + 1, currentValue);--}}
                    {{--this.needInitialSelect2 = true;--}}
                {{--},--}}
                {{--bannerChanged:function (index,event) {--}}
                    {{--var selectedPageIds = [];--}}
                    {{--$(event).find('option:selected').each(function(index,element){--}}
                        {{--selectedPageIds.push(element.value);--}}
                    {{--});--}}
                    {{--this.selectedData[index].selectedBannerPages = selectedPageIds;--}}
                {{--},--}}
                {{--bannerImageSelected:function (event) {--}}
                    {{--var selectIndex = $('.banner_select2').index(event);--}}
                    {{--var component = this;--}}
                    {{--$(event).find('option:selected').each(function(index,element){--}}
                        {{--component.selectedData[selectIndex].selectedImageValue = element.value;--}}
                    {{--});--}}
                {{--}--}}
            {{--},--}}
            {{--updated:function(){--}}
                {{--this.initialLastSelect2();--}}
            {{--},--}}
            {{--mounted:function () {--}}
                {{--this.needInitialSelect2 = true;--}}
                {{--this.initialLastSelect2();--}}
            {{--},--}}
            {{--computed: {--}}
                {{--canNewRow:function () {--}}
                    {{--if(this.selectedData.length > 0){--}}
                        {{--var latestObj = this.selectedData[this.selectedData.length - 1];--}}
                        {{--if (latestObj.selectedBannerPages.length == 0 || latestObj.selectedImageValue == null){--}}
                            {{--return false;--}}
                        {{--}--}}
                    {{--}--}}
                    {{--return true;--}}
                {{--},--}}
                {{--bannerImagesData:function () {--}}
                    {{--return JSON.stringify(this.selectedData.filter(function(element){--}}
                        {{--return element.electedImageValue != null || element.selectedBannerPages.length > 0;--}}
                    {{--}));--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}

{{--//        $('.banner_page_select2').select2({--}}
{{--//            closeOnSelect: false--}}
{{--//        });--}}
    {{--})--}}
{{--</script>--}}

{{--@include('Components.ImagePicker.scripts')--}}