<div class="content">
    <div class="clearfix"></div>

    <div class="row">

        <div class="col-sm-12">

            <div class="form-group">
                <label for="">选择分类</label>
                <select name="" id="category" class="form-control">
                    @foreach($category as $item)
                        <option value="{!! $item->id !!}" @if(isset($carrierImage)&&$carrierImage->image_category == $item->id)selected @endif>{!! $item->category_name !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">图片链接类型</label>
                <div class="form-control" style="border: 0;background-color: unset">
                    <label><input type="radio" name="url_type" value="0" @if(isset($carrierImage)&&isset($carrierImage->url_type)&&$carrierImage->url_type == 0 )checked @endif>站外链接</label>
                    <label><input type="radio" name="url_type" value="1"@if(isset($carrierImage)&&$carrierImage->url_type == 1)checked @endif>站内链接</label>
                </div>

            </div>
            <div class="form-group">
                <label>图片链接地址</label>
                <input type="text" name="" class="form-control urlLink url_link" @if(isset($carrierImage)&&$carrierImage->url_type == 1)style="display: none;" @endif placeholder="请输入站外链接，前面加上http://或https://" @if(isset($carrierImage)&&$carrierImage->url_type == 0)value="{{$carrierImage->url_link}}" @endif>
                <select name="" id="" class="form-control urlLink url_link" @if((isset($carrierImage)&&$carrierImage->url_type != 1) || empty($carrierImage))style="display: none;" @endif >
                    @foreach($games as $k => $v)
                        <option @if(!strstr($k, '/'))disabled @else value="{{$k}}" @endif @if(isset($carrierImage)&&$carrierImage->url_link == $k)selected @endif>@if(strstr($k, '/'))--@endif{{$v}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="file">
                    选择图片
                </label>
                <input id="input-file-field" @if(!isset($carrierImage))multiple @endif type="file" name="file" class="form-control">
                <input multiple type="hidden" name="image_path" @if(isset($carrierImage))value="{{$carrierImage->image_path}}" @endif>
                <input multiple type="hidden" name="id" @if(isset($carrierImage))value="{{$carrierImage->id}}" @endif>

            </div>


        </div>

    </div>

</div>

<link href="{!! asset('bootstrap-fileinput/css/fileinput.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
<script src="{!! asset('bootstrap-fileinput/js/plugins/canvas-to-blob.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('bootstrap-fileinput/js/plugins/sortable.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('bootstrap-fileinput/js/plugins/purify.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('bootstrap-fileinput/js/fileinput.min.js') !!}"></script>
<script src="{!! asset('bootstrap-fileinput/js/locales/zh.js') !!}"></script>
<script>

    $(function(){
        $("#input-file-field").fileinput({
            uploadUrl: '{!! route('carrierImages.store') !!}', // you must set a valid URL here else you will get an error
            showUploadedThumbs:false,
            allowedFileExtensions : ['jpg', 'png','gif'],
            validateInitialCount:false,
            overwriteInitial: false,
            maxFileSize: 1024,
            maxFileCount: Infinity,
            dropZoneTitle:'{!! isset($carrierImage)?'拖拽文件到这里 …':'拖拽文件到这里 …'.'<br>'.'支持多文件同时上传' !!}',
            slugCallback: function(filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            previewSettings:{
                image: {width: "auto", height: "160px"}
            },
            uploadExtraData:function(){
                var obj = {};
                obj.image_category =  $('#category').val();
                obj.url_type =  $('input[name=url_type]:checked').val();
                if (obj.url_type == 1){
                    obj.url_link =  $('.url_link option:selected').val();
                }else{
                    obj.url_link =  $('.url_link').val();
                }
                obj.image_path =  $('input[name=image_path]').val();
                obj.id =  $('input[name=id]').val();
                return obj;
            },
            showUpload:true,
            showRemove:true,
            fileActionSettings:{
                showUpload:false,
                showRemove:false
            },
            language:'zh',
            initialPreview:[@if(isset($carrierImage))'<img src="{{$carrierImage->imageAsset()}}" class="file-preview-frame">'@endif],
        });
        $('#input-file-field').on('fileuploaderror', function(event, data, msg) {  //一个文件上传失败
            if (data.jqXHR.responseJSON.success == false){
                toastr.error(data.jqXHR.responseJSON.message || '编辑失败', '出错啦!');
            }
        });
        $('#input-file-field').on('fileuploaded', function(event, data, previewId, index) {
            data.extra.image_id = 1;
            toastr.success('上传成功');
            $("#editAddModal").modal("hide");
            window.LaravelDataTables['dataTableBuilder'].ajax.reload();
        });
        $('#input-file-field').on('filecleared', function(event, key) {
            $('input[name=image_path]').val('');
            $('.file-preview-initial').remove();
        });
        $('#input-file-field').on('filebatchselected', function(event, key) {
            $('.file-preview-initial').remove();
        });

        $("input[name=url_type]").change(function() {
            var _this = $(this);
            if (_this.val() == 0){
                $('.urlLink').hide().removeClass('url_link');
                $("input.urlLink").show().addClass('url_link');
            }else{
                $('.urlLink').hide().removeClass('url_link');
                $("select.urlLink").show().addClass('url_link');
            }
        });
    })

</script>