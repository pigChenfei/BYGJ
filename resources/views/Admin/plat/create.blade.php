@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            新增主游戏平台
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('plats.store') }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <!-- English Game Name Field -->
                        <div class="form-group">
                            <label for="main_game_plat_name" class="col-sm-2 text-right">主游戏平台名称:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="main_game_plat_name" type="text" id="main_game_plat_name" value="{{ old('main_game_plat_name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="main_game_plat_code" class="col-sm-2 text-right">主平台代码:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="main_game_plat_code" type="text" id="main_game_plat_code" value="{{ old('main_game_plat_code') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_pre" class="col-sm-2 text-right">生成帐号前辍:</label>
                            <div class="col-sm-6">
                                <input class="form-control" name="account_pre" type="text" id="account_pre" value="{{ old('account_pre') }}">
                            </div>
                        </div>

                        <!-- English Game Name Field -->
                        <div class="form-group">
                            <label class="col-sm-2 text-right">游戏主平台状态:</label>
                            <div class="col-sm-6">
                                <input class="minimal" name="status" type="radio" value="1" @if(old('status') == 1) checked @endif> 正常&emsp;
                                <input class="minimal" name="status" type="radio" value="0" @if(old('status') == 0) checked @endif> 关闭
                            </div>
                        </div>

                        <!-- Status Field -->
                        <!-- Submit Field -->
                        <div class="form-group">
                        	<div class="col-sm-1"></div>
                        	<div class="col-sm-8">
	                            <input class="btn btn-primary" type="submit" value="保存">&emsp;&emsp;
                                <a href="{{route('plats.index')}}" class="btn btn-default">取消</a>
                        	</div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

