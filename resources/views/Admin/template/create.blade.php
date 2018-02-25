@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            新增模板
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('templates.store') }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <!-- Game Plat Id Field -->
                        <div class="form-group">
                            <label for="type" class="col-sm-2 text-right">模板类型:</label>
                            <div class="col-sm-6">
                            <select class="form-control" name="type" style="float: left;width: 49%" onchange="selectOnchang(this)">
                                <option value="1"  >PC模板</option>
                                <option value="2"  >移动端模板</option>
                                <option value="3"  >代理前台模板</option>
                                <option value="4"  >代理后台模板</option>
                            </select>
                            </div>
                        </div>

                        <!-- English Game Name Field -->
                        <div class="form-group">
                            <label for="value" class="col-sm-2 text-right">模板名称:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="value" type="text" id="value" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="value" class="col-sm-2 text-right">中文名称:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="alias" type="text" id="alias" value="">
                            </div>
                        </div>
                        <!-- Status Field -->
                        <!-- Submit Field -->
                        <div class="form-group">
                        	<div class="col-sm-1"></div>
                        	<div class="col-sm-8">
	                            <input class="btn btn-primary" type="submit" value="保存">&emsp;&emsp;
                                <a href="{{route('templates.index')}}" class="btn btn-default">取消</a>
                        	</div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

