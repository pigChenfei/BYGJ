@extends('Carrier.layouts.app')

@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>
    <div class="content">
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">平台列表</h3>
                </div>
                <div class="box-body">
                <ul class="todo-list treeview-menu" >
                    @foreach($gamePlats as $item)
                    <li class="treeview">
                        <a id ="{!! $item->gamePlat->game_plat_id !!}" href="javascript:void(0)" onclick="getGames({!! $item->gamePlat->game_plat_id  !!})" class="getGame" style="font-size:16px;color:#2e3436;">{{$item->gamePlat->game_plat_name}}</a>
                        <div class="tools">
                            <i class="fa fa-edit" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierGamePlats.edit', $item->id)) !!}"></i>
                        </div>
                    </li>
                    @endforeach
                </ul>
                </div>


            </div>
            <div class="col-md-10">
                <div class="box box-primary color-palette-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tag"></i> 游戏列表</h3>
                        <div class="box-tools">
                            <ul class="pull-right pagination-sm pagination">
                            </ul>
                        </div>
                    </div>
                    <div class="box-body">
                        @include('Carrier.carrier_games.table')
                    </div>

                    <div class="overlay" id="overlay" style="display: none">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    {!! TableScript::createEditOrAddModal() !!}

@endsection
<script>

        function getGames(id) {
            $('#platGameIdInput').val(id);
            $('#platGameIdInput').parents('form').find('button[type=submit]').click();
        }



</script>

