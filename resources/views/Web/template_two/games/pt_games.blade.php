
@extends('Web.default.layouts.app')
<title>PT游戏中心</title>

@section('content')
<center>

    <div style="margin-bottom: 500px">
        @foreach($ptGameList as $k=>$game)
            {{--@if($k%8 == 0)
                <br><br>
            @endif--}}
            <a style="color: black" href="http://cache.download.banner.greenjade88.com/casinoclient.html?language=ZH-CN&game={!! $game->game->game_code !!}">{!! $game->display_name !!}</a><hr>
        @endforeach
    </div>
    {{--<input type="button" value="PLAY PRODUCTION" onclick="playproduction()" style="height: 50px; width: 180px"> <input type="button" value="SHOW EXAMPLE LINK" onclick="productionlink()" style="height: 50px; width: 180px"><p id="productionlink"></p><br>--}}
</center>
@endsection
