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
                    <td>{{$likePlayer->user_name}}</td>
                    <td>{{$likePlayer->real_name}}</td>
                    <td>{{$likePlayer->birthday}}</td>
                    <td>******</td>
                    <td>{{$likePlayer->mobile}}</td>
                    <td>{{$likePlayer->register_ip}}</td>
                    <td>{{$likePlayer->created_at}}</td>
                    <td>{{$likePlayer->login_ip}}</td>
                    <td>{{$likePlayer->login_at}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>