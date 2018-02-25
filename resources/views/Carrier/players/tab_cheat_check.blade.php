<div class="box box-primary">
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <table class="table table-bordered table-hover table-responsive">
            <thead>
            <tr role="row">
                <th>ID</th>
                <th>账号</th>
                <th>姓名</th>
                <th>出生日期</th>
                <th>密码</th>
                <th>手机号</th>
                <th>注册IP</th>
                <th>注册时间</th>
                <th>最后登录IP</th>
                <th>最后登录时间</th>
            </tr>
            </thead>
            <tbody>
            @foreach($likePlayers as $key => $likePlayer)
            <tr role="row">
                <td>{{$key+1}}</td>
                <td>@if($likePlayer['user_name']==$player['user_name']) <font color="red">{{$likePlayer['user_name']}}</font> @else {{$likePlayer['user_name']}} @endif</td>
                <td>@if($likePlayer['real_name']==$player['real_name']) <font color="red">{{$likePlayer['real_name']}}</font> @else {{$likePlayer['real_name']}} @endif</td>
                <td>@if($likePlayer['birthday']==$player['birthday']) <font color="red">{{$likePlayer['birthday']}}</font> @else {{$likePlayer['birthday']}} @endif</td>
                <td>@if($likePlayer['password']==$player['password']) <font color="red">******</font> @else ****** @endif</td>
                <td>@if($likePlayer['mobile']==$player['mobile']) <font color="red">{{$likePlayer['mobile']}}</font> @else {{$likePlayer['mobile']}} @endif</td>
                <td>@if($likePlayer['register_ip']==$player['register_ip']) <font color="red">{{$likePlayer['register_ip']}}</font> @else {{$likePlayer['register_ip']}} @endif</td>
                <td>@if(date("Y-m-d H:i",strtotime($likePlayer['created_at']))==date("Y-m-d H:i",strtotime($player['created_at']))) <font color="red">{{$likePlayer['created_at']}}</font> @else {{$likePlayer['created_at']}} @endif</td>
                <td>@if($likePlayer['login_ip']==$player['login_ip']) <font color="red">{{$likePlayer['login_ip']}}</font> @else {{$likePlayer['login_ip']}} @endif</td>
                <td>@if(date("Y-m-d H:i",strtotime($likePlayer['login_at']))==date("Y-m-d H:i",strtotime($player['login_at']))) <font color="red">{{$likePlayer['login_at']}}</font> @else {{$likePlayer['login_at']}} @endif</td>
            </tr>
             @endforeach
            </tbody>
        </table>
    </div>
</div>
