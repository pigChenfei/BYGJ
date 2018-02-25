{!! Form::open(['route' => ['players.destroy', $player_id], 'method' => 'delete']) !!}
<div class="btn-group">
  <button type="button" class="btn btn-success btn-xs">操作</button>
  <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu" role="menu">
    {{--<li><a onclick="{!! TableScript::addOrEditModalShowEventScript(route('players.show', $player_id)) !!}"><span class="fa fa-edit"></span>编辑</a></li>--}}

    <li><a class="player_edit" href="{!! route('players.showPlayerInfoEditModal', $player_id) !!}"><span class="fa fa-edit"></span>编辑</a></li>
    <li><a style="cursor: pointer" onclick="{!! TableScript::addOrEditModalShowEventScript(route('players.showVerifyLoginPasswordModal', $player_id)) !!}"><span style="width: 14px;height:14px" class="fa fa-tv"></span>修改登录密码</a></li>
    <li><a style="cursor: pointer" onclick="{!! TableScript::addOrEditModalShowEventScript(route('players.showVerifyPayPasswordModal', $player_id)) !!}"><span class="fa fa-key"></span>修改取款密码</a></li>
    <li><a style="cursor: pointer" onclick="var _me = this; $.fn.winwinAjax.sendUpdateAjax('{!! route('players.togglePlayerAccountStatus',$player_id) !!}',{},function() {
                if(window.LaravelDataTables){
                  window.LaravelDataTables['dataTableBuilder'].ajax.reload();
                };
              },function() {

              })"><span style="width: 14px;height:14px" class="fa {!! $user_status == \App\Models\Player::USER_STATUS_OK ? 'fa-lock' : 'fa-unlock' !!}"></span>{!! $user_status == \App\Models\Player::USER_STATUS_OK ? '禁用' : '解锁' !!}会员账号</a></li>
    {{--<li class="divider"></li>--}}
    {{--<li><a href="#"><span class="fa fa-envelope"></span>发送站内短信</a></li>--}}
  </ul>
</div>
@if($is_online)
  <div class="btn-group">
    <button type="button" onclick="
            $.fn.winwinAjax.buttonActionSendAjax(this,'{!! route('players.kickOutLine',$player_id) !!}',{},function() {
              window.LaravelDataTables['dataTableBuilder'].ajax.reload();
            },function() {
            },'POST')" class="btn btn-warning btn-xs">踢线</button>
  </div>
@endif
{!! Form::close() !!}
