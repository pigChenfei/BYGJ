@foreach($ptGameList as $v)
    <li class="item-content img-item" style="background-image: url({{asset($v->game->game_icon_path)}})">
        <a href="javascript:"></a>
        <i class="icon-ww icon-collection"></i>
    </li>
@endforeach