@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            新增支付渠道
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('payments.store') }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <!-- Game Plat Id Field -->
                        <div class="form-group">
                            <label for="pay_channel_type_id" class="col-sm-2 text-right">支付类型:</label>
                            <div class="col-sm-6">
                            <select class="form-control" name="pay_channel_type" style="float: left;width: 49%" onchange="selectOnchang(this)">
                                @foreach($channelType as $v)
                                    <option value="{{$v->id}}"  @if(old('pay_channel_type') == $v->id) selected @endif>{{$v->type_name}}</option>
                                @endforeach
                            </select>
                            <select class="form-control" name="pay_channel_type_id" style="float: left;width: 49%;margin-left: 2%">
                                @foreach($channelChild as $v)
                                    <option value="{{$v->id}}"  @if(old('pay_channel_type_id') == $v->id) selected @endif>{{$v->type_name}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>

                        <!-- English Game Name Field -->
                        <div class="form-group">
                            <label for="channel_name" class="col-sm-2 text-right">支付渠道名称:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="channel_name" type="text" id="channel_name" value="{{ old('channel_name') or '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="channel_code" class="col-sm-2 text-right">支付渠道代码:</label>
                            <div class="col-sm-6">
	                            <input class="form-control " name="channel_code" type="text" value="{{ old('channel_code') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_need_private_key" class="col-sm-2 text-right">是否需要私钥:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="is_need_private_key" type="radio" value="1" checked> 是&emsp;
                                <input class="minimal" name="is_need_private_key" type="radio" value="0"> 否
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_need_domain" class="col-sm-2 text-right">是否需要绑定域名:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="is_need_domain" type="radio" value="1" checked> 是&emsp;
                                <input class="minimal" name="is_need_domain" type="radio" value="0"> 否
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_need_good_name" class="col-sm-2 text-right">是否需要绑定商品名称:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="is_need_good_name" type="radio" value="1" checked> 是&emsp;
                                <input class="minimal" name="is_need_good_name" type="radio" value="0"> 否
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_need_vir_card" class="col-sm-2 text-right">是否需要填写转入账户:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="is_need_vir_card" type="radio" value="1" checked> 是&emsp;
                                <input class="minimal" name="is_need_vir_card" type="radio" value="0"> 否
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_need_vir_card" class="col-sm-2 text-right">是否需要填写商户识别码:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="is_need_identify_code" type="radio" value="1" checked> 是&emsp;
                                <input class="minimal" name="is_need_identify_code" type="radio" value="0"> 否
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="game_icon_path" class="col-sm-2 text-right">支付渠道图标:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="icon_path_url" type="file" id="game_icon_path" style="border: 0" value="{{ old('icon_path_url') }}">
                            </div>
                        </div>
                        <!-- Status Field -->
                        <!-- Submit Field -->
                        <div class="form-group">
                        	<div class="col-sm-1"></div>
                        	<div class="col-sm-8">
	                            <input class="btn btn-primary" type="submit" value="保存">&emsp;&emsp;
                                <a href="{{route('payments.index')}}" class="btn btn-default">取消</a>
                        	</div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/JavaScript">
        function selectOnchang(obj) {
            var value = obj.options[obj.selectedIndex].value;
            $.ajax({
                url:"{!! route('payments.getInfo') !!}" ,
                data:{id:value} ,
                dataType:'json' ,
                success:function(data){
                    console.log(data);
                    $('select[name=pay_channel_type_id]').html('');
                    $.each(data.data, function(index,value){
                        $('select[name=pay_channel_type_id]').append('<option value="'+value.id+'">'+value.type_name+'</option>');
                    })
                },
                error:function (xhr) {
                    toastr.error(xhr.responseJSON.message || '编辑失败', '出错啦!')
                }
            });
        }
    </script>
@endsection

