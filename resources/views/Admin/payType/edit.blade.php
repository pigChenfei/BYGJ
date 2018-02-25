@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            新增支付类型
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('payTypes.update',['id'=>$payChannel->id]) }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{csrf_field()}}
<input type="hidden" name="_method" value="put">
                        <!-- Game Plat Id Field -->
                        <div class="form-group">
                            <label for="pay_channel_type_id" class="col-sm-2 text-right">支付类型分类:</label>
                            <div class="col-sm-6">
                            <select class="form-control" name="parent_id">
                                <option value="0" @if($payChannel->parent_id == 0) selected @endif>顶级分类</option>
                                @foreach($channelType as $v)
                                    <option value="{{$v->id}}"  @if($payChannel->parent_id == $v->id) selected @endif>{{$v->type_name}}</option>
                                    @if(count($v->childInfo) > 0)
                                        @foreach($v->childInfo as $j)
                                            <option value="{{$j->id}}"  @if($payChannel->parent_id == $j->id) selected @endif>|--{{$j->type_name}}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="type_name" class="col-sm-2 text-right">支付类型名称:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="type_name" type="text" id="type_name" value="{{ $payChannel->type_name }}">
                            </div>
                        </div>

                        <!-- English Game Name Field -->
                        <div class="form-group">
                            <label for="sort" class="col-sm-2 text-right">排序:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="sort" type="text" id="sort" value="{{ $payChannel->sort }}">
                            </div>
                        </div>
                        <div class="form-group">
                        	<div class="col-sm-1"></div>
                        	<div class="col-sm-8">
	                            <input class="btn btn-primary" type="submit" value="保存">&emsp;&emsp;
                                <a href="{{route('payTypes.index')}}" class="btn btn-default">取消</a>
                        	</div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

