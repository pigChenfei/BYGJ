@extends('Admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            新增游戏
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="POST" action="{{ route('games.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{csrf_field()}}
                            <input type="hidden" name="game_id" value="{{ $game->game_id }}">

                        <!-- Game Plat Id Field -->
                        <div class="form-group col-sm-6">
                            <label for="game_plat_id">游戏平台:</label>
                            <select class="form-control" name="game_plat_id">
                                @foreach($game_plat as $v)
                                    <option value="{{$v->game_plat_id . '+' .$v->main_game_plat_id}}"  @if($game->game_plat_id == $v->game_plat_id) selected @endif>{{$v->game_plat_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- English Game Name Field -->
                        <div class="form-group col-sm-6">
                            <label for="english_game_name">英文名称:</label>
                            <input class="form-control" name="english_game_name" type="text" id="english_game_name" value="{{ $game->english_game_name}}">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="game_name">中文名称:</label>
                            <input class="form-control" name="game_name" type="text" value="{{ $game->game_name }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="game_type">game_type:</label>
                            <input class="form-control" name="game_type" type="text" value="{{ $game->game_type }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="game_kind">game_kind:</label>
                            <input class="form-control" name="game_kind" type="text" value="{{ $game->game_kind }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="sub_game_kind">sub_game_kind:</label>
                            <input class="form-control" name="sub_game_kind" type="text" value="{{ $game->sub_game_kind }}">
                        </div>

                        <!-- Game Code Field -->
                        <div class="form-group col-sm-6">
                            <label for="game_code">游戏代码:</label>
                            <input class="form-control" name="game_code" type="text" id="game_code" value="{{ $game->game_code }}">
                        </div>

                        <!-- Game Lines Field -->
                        <div class="form-group col-sm-6">
                            <label for="game_lines">线数:</label>
                            <select class="form-control" name="game_lines">
                                @foreach($game_lines_array as $k => $v)
                                    <option value="{{$k}}"  @if($game->game_lines == $k) selected @endif>{{$v}}线</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Game Icon Path Field -->
                        <div class="form-group col-sm-6">
                            <label for="game_mcategory">游戏分类:</label>
                            <select class="form-control" name="game_mcategory">
                                @foreach($game_mcategory_array as $k => $v)
                                    <option value="{{$k}}"  @if($game->game_mcategory == $k) selected @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="return_award_rate">返奖率:</label>
                            <input class="form-control" name="return_award_rate" type="number" id="return_award_rate" value="{{ $game->return_award_rate }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="game_popularity">人气:</label>
                            <input class="form-control" name="game_popularity" type="number" id="game_popularity" value="{{ $game->game_popularity }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="gold_pool">奖金池:</label>
                            <input class="form-control" name="gold_pool" type="number" value="{{ $game->gold_pool }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="game_icon_path">游戏图标路径:</label>
                            <input class="form-control" name="game_icon_path_up" type="file" id="game_icon_path" style="border: 0" value="{{ $game->game_icon_path }}">
                            <input class="form-control" name="game_icon_path" type="hidden" value="{{ $game->game_icon_path }}">
                            @if(!empty($game->game_icon_path))
                            <img src="/{{ $game->game_icon_path }}">
                            @endif
                        </div>
                        <!-- Status Field -->
                        <div class="form-group col-sm-6" style="height: 59px;">
                            <label for="status" style="display: block;">状态:</label>
                            <div class="checkbox-inline" style="margin-left: 0">
                                <label><input name="status" type="radio" value="0" @if($game->status == 0) checked @endif>关闭&nbsp;&nbsp;&nbsp;</label>
                                <label><input name="status" type="radio" value="1" @if($game->status == 1) checked @endif>正常</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="is_demo" style="display: block;">是否试玩:</label>
                            <div class="checkbox-inline" style="margin-left: 0">
                                <label><input name="is_demo" type="radio" value="0" @if($game->is_demo == 0) checked @endif>否&nbsp;&nbsp;&nbsp;</label>
                                <label><input name="is_demo" type="radio" value="1"  @if($game->is_demo == 1) checked @endif>是</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="is_recommend" style="display: block;">是否推荐:</label>
                            <div class="checkbox-inline" style="margin-left: 0">
                                <label><input name="is_recommend" type="radio" value="0" @if($game->is_recommend == 0) checked @endif>否&nbsp;&nbsp;&nbsp;</label>
                                <label><input name="is_recommend" type="radio" value="1"  @if($game->is_recommend == 1) checked @endif>是</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="is_recommend" style="display: block;">支持flash:</label>
                            <div class="checkbox-inline" style="margin-left: 0">
                                <label><input name="flashcode" type="radio" value="0" @if($game->flashcode == 0) checked @endif>否&nbsp;&nbsp;&nbsp;</label>
                                <label><input name="flashcode" type="radio" value="1"  @if($game->flashcode == 1) checked @endif>是</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="is_recommend" style="display: block;">支持手机端:</label>
                            <div class="checkbox-inline" style="margin-left: 0">
                                <label><input name="is_wap" type="radio" value="0" @if($game->is_wap == 0) checked @endif>否&nbsp;&nbsp;&nbsp;</label>
                                <label><input name="is_wap" type="radio" value="1"  @if($game->is_wap == 1) checked @endif>是</label>
                            </div>
                        </div>
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <input class="btn btn-primary" type="submit" value="保存">
                            <a href="{{route('games.index')}}" class="btn btn-default">取消</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
