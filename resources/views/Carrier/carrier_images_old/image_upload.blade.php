@section('scripts')
    @parent
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
                slugCallback: function(filename) {
                    return filename.replace('(', '_').replace(']', '_');
                },
                previewSettings:{
                    image: {width: "auto", height: "160px"}
                },
                uploadExtraData:function(){
                    var obj = {};
                    obj.image_category =  $('#category').val();
                    return obj;
                },
                showUpload:true,
                showRemove:true,
                fileActionSettings:{
                    showUpload:false,
                    showRemove:false
                },
                language:'zh'
            });
            $('#input-file-field').on('fileuploaded', function(event, data, previewId, index) {
                console.log(event, data, previewId, index);

                data.extra.image_id = 1;

            });
            $('#input-file-field').on('filesuccessremove', function(event, key) {
                console.log(event , key);
            });
        })

    </script>
@endsection

@section('css')
    @parent
    <link href="{!! asset('bootstrap-fileinput/css/fileinput.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endsection

@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <div class="left">
            {!! Breadcrumbs::render(Route::current()->getAction()['as']) !!}
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-sm-12">

                <div class="form-group">
                    <label for="">选择分类</label>
                    <select name="" id="category" class="form-control">
                        @foreach($category as $item)
                        <option value="{!! $item->id !!}">{!! $item->category_name !!}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="file">
                        选择图片
                    </label>
                    <input id="input-file-field" multiple type="file" name="file" class="form-control">

                </div>


            </div>

        </div>

    </div>




@endsection
