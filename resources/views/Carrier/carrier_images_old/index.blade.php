@extends('Carrier.layouts.app')

@section('css')
    @parent
    <style>
        @keyframes fade-in {
            0% {opacity: 0;}
            100% {opacity: 1;}
        }
        @-webkit-keyframes fade-in {
            0% {opacity: 0;}
            100% {opacity: 1;}
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(function(){

            $(document).on('click','.deleteAction',function(e){
                var deleteUrl = $(this).attr('data-url');
                var _actionBtn = $(this).parents('li').find('.action');
                _actionBtn.each(function(index,btn){
                    btn.disabled = true;
                });
                var _me = this;
                $(_me).text('删除中...');
                $.ajax({
                    url: deleteUrl,
                    type:'POST',
                    data:{_method:'DELETE'},
                    success:function(e){
                        toastr.clear();
                        if(e.success == true){
                            toastr.success('删除成功');
                            $(_me).parents('li').remove();
                        }else{
                            toastr.error(e.message || '删除失败');
                        }
                        _actionBtn.each(function(index,btn){
                            btn.disabled = false;
                        });
                        $(_me).text('删除');
                    },
                    error:function(xhr){
                        toastr.clear();
                        toastr.error(xhr.responseJSON.message || '删除失败');
                        _actionBtn.each(function(index,btn){
                            btn.disabled = false;
                        });
                        $(_me).text('删除');
                    }
                })

            })

        })
    </script>
@endsection

@section('content')
    <section class="content-header">

    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-2">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <p class="text-muted text-center">分类</p>
                        <ul class="list-group list-group-unbordered">
                            @foreach($categories as $category)
                            <li class="list-group-item text-center">
                                <a href="{!! route('carrierImages.show',$category->id) !!}"><b>{!! $category->category_name !!}</b></a>
                            </li>
                            @endforeach
                        </ul>
                        <button onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierImageCategories.create')) !!}" class="btn btn-primary btn-block"><b>新增分类</b></button>
                        <a href="{!! route('carrierImages.showUploadImageModal') !!}"  class="btn btn-success btn-block"><b>上传素材</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-10">
                @if($images->count() > 0)
                <ul class="users-list clearfix">
                    @foreach($images as $image)
                    <li>
                        <div style="background-color: rgba(0,0,0,0.1);padding: 5px;">
                            <div style="background: url('{!! $image->imageAsset() !!}') center no-repeat;
                                    background-size: contain;
                                    height: 280px;
                                    animation: fade-in;
                                    animation-duration: 0.8s;
                                    -webkit-animation:fade-in 0.8s;
                                    "></div>
                            {{--<img style="border-radius: 0" src="{!! asset(Storage::url($image->image_path)) !!}" alt="User Image">--}}
                            <span class="users-list-name" href="#">{!! $image->remark ?: '暂无备注' !!}</span>
                            <form role="form">
                                <div class="col-sm-12 form-group">
                                    <input type="text" class="form-control" value="sdfsdfsdfsd">
                                </div>
                                <div class="clearfix"></div>
                            </form>
                            <span class="users-list-name" href="#">{!! $image->created_at !!}</span>
                            <span class="users-list-date">{!! sprintf('%.2fKB',$image->image_size / 1024) !!}</span>
                            <div class="col-sm-12" style="margin-top: 5px">
                                <button class="col-sm-5 pull-left btn btn-danger btn-xs deleteAction action" data-url="{!! route('carrierImages.destroy', $image->id) !!}">删除</button>
                                <button class="col-sm-5 pull-right btn btn-primary btn-xs deleteAction action" data-url="{!! route('carrierImages.destroy', $image->id) !!}">备注</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                    <h3 class="text-center">暂无图片</h3>
                @endif
            </div>
        </div>

    </div>

    {!! TableScript::createEditOrAddModal() !!}

    <script>

    </script>

@endsection

