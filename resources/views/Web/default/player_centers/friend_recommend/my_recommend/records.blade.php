{{--我要推荐--}}
<div style="min-height: 595px;">
    <main class="account-security1" style="margin:0 auto;">
        <div class="account-security pull-left" style="margin-left: 15px;margin-top: 50px;">
            <input type="hidden" name="recommend_player_id" value="{!! $player->player_id !!}">
            {{--<div>--}}
                {{--<span>--}}
                    {{--<b>邀请码</b>--}}
                {{--</span>--}}
                {{--<input type="text" disabled="true" value="{!! $player->referral_code !!}" id="Referral"/>--}}
                 {{--<span class="copy-Referral copy-value"  style="cursor: pointer">点击复制</span>--}}
            {{--</div>--}}
            <div class="clearfix"></div>
               <div><span><b>推荐链接</b></span><input type="text" style="width: 400px;" disabled="true " value="{!! $player->recommend_url !!}" id="copy-this-links"/>
                <span class="copy copy-value" style="cursor: pointer">点击复制</span></div>
            <div class="clearfix"></div>
            <div class="recommend-three" style="margin-left: 120px;font-size: 15px;">已邀请成功<b>{!! $player->invite_player_count !!}</b>个好友,累计获得 <b>{!! $player->totalBonus !!}</b>奖金</div>
            <div class="recommend-two"><b>推荐好友开户</b></div>
            <div><span><b>账号</b></span><input type="text" maxlength="11" name="user_name" class="navzhang"/><span class="tzhang" > 账号由（4-11位）小写字母或数字组成</span></div>
            <div class="clearfix"></div> 
            <div><span><b>请输入密码</b></span><input type="password" name="password" maxlength="16" class="navmi"/><span class="tmi"> 密码为6-16位字母或数字组成</span></div>
            <div class="clearfix"></div>
            <div><span><b>请确认密码</b></span>
                <input type="password" maxlength="16" name="confirm_password" class="navma"/> <span class="tma">请确认密码</span>
            </div>
            <div class="clearfix"></div>
            <div><span></span><button class="btn btn-default I-go" style="background-color:#2ac0ff;color: #fff;">确认提交</button></div>
        </div>
    </main>
</div>

<script>
$(function(){
    $(".copy-Referral").zclip({
        path: 'app/js/Copy.UI-master/assets/import/ZeroClipboard.swf',
        copy: function(){
            return $("#Referral").val();
        },
     afterCopy: function() {
            layer.msg("复制成功");
       }
     });
    $(".copy").zclip({
        path: 'app/js/Copy.UI-master/assets/import/ZeroClipboard.swf',
        copy: function(){
            return $("#copy-this-links").val();
        },
     afterCopy: function() {
            layer.msg("复制成功");
       }
     });

    //推荐注册
    $(".I-go").on('click',function(e){
        e.preventDefault();
        var button = this;
        var id = $("input:hidden[name='recommend_player_id']").val();
        var reg3 = /^([a-z0-9]){4,11}$/;
        var user_name =$(".navzhang").val();
        var reg2 = /^([a-z0-9]){6,16}$/;
        var password = $(".navmi").val();
        var confirm_password = $(".navma").val();
        if (user_name.trim() == "" || reg3.test(user_name) != true) {
            layer.tips('账号是4到11位数字或者小写英文', '.navzhang', {
                tips: [1, '#ff0000'],
                time: 200000
            })
        }else if (reg2.test(password) != true) {
            layer.tips('密码是6到16位数字或者小写英文', '.navmi', {
                tips: [1, '#ff0000'],
                time: 2000
            })
        }else if(password != confirm_password){
            layer.tips('两次密码不一致', '.navma', {
                tips: [1, '#ff0000'],
                time: 2000
            })
        }else {
            button.disabled = true;
            $.ajax({
                type: 'post',
                url: "{!! route('homes.register') !!}",
                data: {
                    'recommend_player_id' : id,
                    'user_name' : user_name,
                    'password' : password,
                    'confirm_password' : confirm_password
                },
                dataType: 'json',
                success: function (xhr) {
                    if (xhr.success == true) {
                        layer.msg('开户成功!');
                        window.location.reload();
                    }
                },
                error:function(xhr){
                    button.disabled = false;
                    if(xhr.responseJSON.success == false) {
                        layer.tips(xhr.responseJSON.message, '.navzhang', {
                            tips: [1, '#ff0000'],
                            time: 2000
                        });
                        button.disabled = false;
                    }
                }
            });
        }
    });
});
</script>

