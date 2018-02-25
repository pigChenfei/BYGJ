//财务中心
$(document).ready(function() {
    var first =$("#Member-deposit>nav>ul>li:first");
    first.addClass("active");
    var first_bank=$(".bank-kad>span>div:first");
    first_bank.addClass("bank-true");
}); 

$(".reality").val("42.5");
$("#dide-kade").click(function(){
    var option =$("#dide-kade>option:checked").text();
    var procedure=0.85;
    var dide=Number(option)*procedure;
    $(".reality").val(dide);
});

$("#deposit-select").change(function(){
    var select=$("#deposit-select").val();
    if(select != ''){
        $(".deposit-display").css("display","none");
    }else{
        $(".deposit-display").css("display","block");
    }
});

$(".select>ul>li>a").click(function(){
    option=$(".select>ul>li>a").html();
    caret=$('.select>button>span');
    $(".select>button").html( option);
    $(".select>button").css('color','#333');
    $(".select>button").css('background-color','#fff');
}); 

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});
$(".bank-sure>div").click(function(){
    $(this).addClass("bank-true").siblings().removeClass("bank-true");
});

$(function(){
	$("#usercenter-content").on('submit',function(e){ e.preventDefault(); return })
    $("#usercenter-content").on("click",".Confirm-the-address",function(e){
        e.preventDefault();
        var entered  = $(".user-de").val();
        if(entered.trim() == "" || isNaN(Number(entered)) || entered < 100 || entered > 50000 ){
            layer.tips('输入金额不对', '.user-de', {
                tips: [1, '#ff0000'],
                time: 2000
            });
        } else {
            var _me =this;
            var confimText = $(_me).val();
            var form = $(_me).parents("form");
            $(_me).val('提交中');
            $.ajax({
                url:"players.depositPayLogCreate",
                data:form.serialize(),
                type:"POST",
                dataType:'json',
                success:function(data){
                    if(data.success == true){
                        layer.msg('订单生成成功');
                        window.location.href = data.data;
                        return false;
                    }
                    else{
                        layer.msg('订单生成失败');
                    }
                    $(_me).val(confimText);
                },
                error:function(xhr){
                    if(xhr.responseJSON.success == false ){
                        layer.msg('订单生成失败');
                        console.log(xhr.responseJSON);
                    }
                }
            });
        }  
    });
      
    $("#usercenter-content1").on('submit',function(e){ 
    	e.preventDefault(); 
      	return;
    });
    
    $("#usercenter-content1").on("click",".Confirm-the-address1",function(e){
		e.preventDefault();
		var entered  = $(".user-de1").val();
     	if( entered.trim() =="" || isNaN(Number(entered)) || entered < 100 || entered > 50000 ){
            layer.tips('输入金额不对', '.user-de1', {
                tips: [1, '#ff0000'],
                time: 2000
            });
        } else {
            var _me =this;
            var confimText = $(_me).val();
            var form = $(_me).parents().parents("form");
            $(_me).val('提交中');
            var amount = form.find(".user-de1").val();
            $.ajax({
                url:"players.depositPayLogCreate",
                data:{
                      'amount':amount
                },
                type:"POST",
                dataType:'json',
                success:function(data){
                    if(data.success == true){
                        layer.msg('订单生成成功');
                        //console.log(data.data);return;
                       window.location.href = data.data;
                        return false;
                    }else{
                        layer.msg('订单生成失败');
                        console.log(1);
                    }
                    $(_me).val(confimText);
                },
                error:function(xhr){
                    if(xhr.responseJSON.success == false ){
                        layer.msg('订单生成失败');
                        console.log(xhr.responseJSON);
                    }
                }
            });
            return false;
        }
    });
});

$("#usercenter-content2").on('submit',function(e){
  	e.preventDefault();
  	return;
});

$("#usercenter-content2").on("click", ".Confirm-the-address2",function(e){
    e.preventDefault();
    var bankAccount = $(".reality-text").val();//银行账号
    var useName = $(".name-de99").val();//持卡人
    var pattern = /^[\u4e00-\u9fa5]{2,40}$/;
    var cardId = $('select[name=cardId]').val();//账户选择
    var amount = $(".user-de12").val();//金额
    var bankTypeId = $('select[name=bankTypeId]').val();//银行名称
    var type = $('select[name=depositType]').val();//存款类型
    var time = $("#dateinfo").val();
    var activityId = $("select[name=activityId]").val();
    var payChannelTypeId = $("input[name=payChannelTypeId]").val();
    var carrierPayChannelId = $("input[name=carrierPayChannelId]").val();
    var _me =this;
    var confimText = $(_me).val();

  	if(!cardId){
      	if(amount.trim() =="" || isNaN(Number(amount)) || amount<=0){
    		layer.tips('输入的金额不正确', '.user-de12', {
        		tips: [1, '#ff0000'],
        		time: 2000
    		});
      	}else if(useName.trim() == ""  || pattern.test(useName) != true){
          	layer.tips('请输入正确的姓名', '.name-de99', {
              	tips: [1, '#ff0000'],
              	time: 2000
          	});
      	}else if(bankAccount.trim() =="" || isNaN(Number(bankAccount)) || bankAccount.length<16 || bankAccount.length > 19){
          	layer.tips('输入卡号不对', '.reality-text', {
              	tips: [1, '#ff0000'],
              	time: 2000
          	});
      	}else if(time==""){
            layer.tips('请选择存款时间', '#dateinfo', {
                tips: [1, '#ff0000'],
                time: 2000
            });
      	}else {
            //增加银行账户存款
            var data = {
                'amount': amount,
                'useName': useName,
                'bankTypeId': bankTypeId,
                'bankAccount': bankAccount,
                'depositType' : type,
                'depositTime' : time,
                'carrierPayChannelId' : carrierPayChannelId,
                'payChannelTypeId' : payChannelTypeId,
                'activityId' : activityId
            };
    		$(_me).val('提交中');
    		$.ajax({
                url:"players.depositPayLogCreate",
                data:data,
                type:"POST",
                dataType:'json',
                success:function(resp){
                    if(resp.success){
                        layer.msg('存款申请成功');
                       window.location.href = resp.data;
                    }else{
                        layer.msg('存款申请失败');
                    }
                    return false;
                    $(_me).val(confimText);
                },
                error:function(xhr){
                    layer.msg(xhr.responseJSON.message);
                    return false;
                    $(_me).val(confimText);
                }
    		});
			return false;
      	}
  	}else {
	    if(amount.trim() =="" || isNaN(Number(amount)) || amount<=0){
        	layer.tips('输入的金额不正确', '.user-de12', {
            	tips: [1, '#ff0000'],
            	time: 2000
        	});
	    }else if(time.trim()=="") {
            layer.tips('请选择存款时间', '#dateinfo', {
                tips: [1, '#ff0000'],
                time: 2000
            });
        }else {
            var data ={
                'cardId' : cardId,
                'amount': amount,
                'depositType' : type,
                'depositTime' : time,
                'carrierPayChannelId' : carrierPayChannelId,
                'payChannelTypeId' : payChannelTypeId,
                'activityId' : activityId
            };
        	$(_me).val('提交中');
        	$.ajax({
            	url:"players.depositPayLogCreate",
            	data:data,
            	type:"POST",
                dataType:'json',
            	success:function(resp){
                    console.log(resp);
                	if(resp.success){
                        layer.msg('存款申请成功');
                        window.location.href = resp.data;
                	}else{
                        layer.msg('存款申请失败');
                	}
                    return false;
                    $(_me).val(confimText);
          		},
          		error:function(xhr){
                    layer.msg(xhr.responseJSON.message);
                	$(_me).val(confimText);
                    return false;
            	}
        	});
      	}
  	}
});

$("#usercenter-content3").on('submit',function(e){ 
		e.preventDefault();  
		return;
});

$("#usercenter-content3").on("click",".Confirm-the-address3",function(e){
    e.preventDefault();
    var userde =$(".user-de23").val();
    if (userde.trim() =="" || isNaN(Number(userde)) || userde < 100 || userde > 50000) {
        layer.tips('输入金额不对', '.user-de23', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else { console.log(2);
        var _me =this;
        var confimText = $(_me).val();
        var form = $(_me).parents("form");
        $(_me).val('提交中');
        $.ajax({
            url:"players.depositPayLogCreate",
            data:form.serialize(),
            type:"POST",
            dataType:'json',
            success:function(e){
                if(e.success == true){
                    layer.open({
                        type: 1,
                        skin: 'layui-layer-rim', //加上边框
                        area: ['285px', '320px'], //宽高
                        content:$('#deposit3')
                    });
                }else{
                    layer.alert('提交失败');
                }
                $(_me).val(confimText);
            },
            error:function(){
                layer.alert('提交失败');
                $(_me).val(confimText);
            }
        });
    }
});

$("#usercenter-content4").on('submit',function(e){
	e.preventDefault(); 
	return;
});

$("#usercenter-content4").on("click",".Confirm-the-address4",function(e){
    e.preventDefault();
    var userde = $(".reality-text1").val();
    if (userde =="" ) {
        layer.tips('请输入卡号', '.reality-text1', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if ($(".reality-pass").val()=="" ) {
        layer.tips('请输入密码', '.reality-pass', {
            tips: [1, '#ff0000'],
            time: 2000
        });
		}else{
        var _me =this;
        var confimText = $(_me).val();
        var form = $(_me).parents("form");
        $(_me).val('提交中');
        $.ajax({
            url:"",
            data:form.serialize(),
            type:"POST",
            success:function(e){
                if(e.success == true){
                    layer.open({
                        type: 1,
                        skin: 'layui-layer-rim', //加上边框
                        area: ['305px', '350px'], //宽高
                        content:$('#deposit5')
                    });
                }else{
                    layer.alert('提交失败');
                }
                $(_me).val(confimText);
            },
            error:function(){
                layer.alert('提交失败');
                $(_me).val(confimText);
            }  
        });
    }
});


$(".user-de").blur(function(){
    var many=$(".user-de").val();
    $(".pull").html(many);
});
$(".user-de1").blur(function(){
    var many=$(".user-de1").val();
    $(".pull").html(many);
});
/*取款*/
$('.width-img').click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['380px', '390px'], //宽高
        content:$('#width-img')
    });
});
$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

$("#Branch-Name").on('click',function(e) {
    e.preventDefault();
    var button = this;
    var userde = $(".with-name").val();
    var card_account = $(".with-kade").val();
    var bank_type_id = $("#bank_type_id").val();
    var branch_name = $(".subsidiary-bank").val();

    var reg3 =/^[\u4e00-\u9fa5]{2,40}$/;
    if(reg3.test(userde)!= true){
        layer.tips('请输入真实姓名', '.with-name', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if (Number($(".with-kade").val().length) < 16) {
        layer.tips('卡号长度不对', '.with-kade', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if((isNaN(Number($(".with-kade").val().length) < 16))){
        layer.tips('请输入正确卡号', '.with-kade', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if ($(".subsidiary-bank").val() == "") {
        layer.tips('分行不能为空', '.subsidiary-bank', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    } else {
        button.disabled = true;
        $.ajax({
            type: 'post',
            async: true,
            url: "/playerwithdraw.addBankCard",
            data: {
                'card_owner_name' : userde,
                'card_account': card_account,
                'card_type': bank_type_id,
                'card_birth_place': branch_name
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    layer.msg('新增银行卡成功!');
                    window.location.href = "./players.financeCenter#withdraw-money";
                    location.reload();
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '#'+xhr.responseJSON.field+'>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                    button.disabled = false;
                }
            }
        });
    }
});

$(".user-wt").on('change',function () {
    var withdraw_number = $(".user-wt").val();
    var button = $("#submit_button");
    $.ajax({
        type: 'post',
        async: true,
        url: "/playerwithdraw.withdrawQuotaCheck",
        data: {
            'withdraw_number':withdraw_number,
        },
        dataType: 'json',
        success: function(xhr){
            if(xhr.success == true){
                $('#error_show').text(xhr.message);
                button.attr("disabled", false);
            }
        },
        error: function(xhr){
            if(xhr.responseJSON){
                $('#error_show').text(xhr.responseJSON.message);
                 button.attr("disabled", true);
            }
        }
    });
});

$(".withdraw-true").on('click',function(e) {
    e.preventDefault();
    var button = this;
    var userde = $(".user-wt").val();
    var pay_password = $(".user-qt").val();
    var card_id = $(".bank-true input[type='hidden']").val();
    if (userde.trim() == "" || isNaN(Number(userde)) ) {
        layer.tips('输入正确的金额', '.user-wt', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if ($(".user-qt").val().trim()==""||isNaN(Number($(".user-qt").val())) || $(".user-qt").val().length !="6") {
        layer.tips('请输入正确的银行密码', '.user-qt', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        button.disabled = true;
        $.ajax({
            type: 'post',
            async: true,
            url: "/playerwithdraw.withdrawApply",
            data: {
                'player_bank_card':card_id,
                'apply_amount':userde,
                'pay_password':pay_password
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    layer.msg('申请成功,请等待客服审核!');
                    window.setTimeout(function () {
                        window.location.href = "./players.financeCenter#withdraw-money";
                        location.reload();
                    },3);
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '.show_error>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }
                button.disabled = false;
            }
        });
    }
});

$(".bank-kad>span>div").click(function(){
    $(this).addClass("bank-true").siblings().removeClass("bank-true");
});
$(".bank-kad>span>div").mouseover(function(){
    $(this).addClass("bank-flase").siblings().removeClass("bank-flase");
});

//删除银行卡
$(".bank-kad>span>div>i").click(function(){
    var card_id = $(this).parent().find('input').val();
    $.ajax({
        type: 'post',
        async: true,
        url: "/playerwithdraw.deleteBankCard",
        data: {
            'card_id' : card_id
        },
        dataType: 'json',
        success: function(data){
            if(data.success == true){
                layer.msg('删除银行卡成功!');
                window.location.href = "./players.financeCenter#withdraw-money";
                location.reload();
            }
        },
        error: function(xhr){
            if(xhr.responseJSON){ 
                layer.msg(xhr.responseJSON.message);
            } 
        }
    });
    // $(this).parent().hide();
});

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});
$(function(){
    $(".ceshi option:checked").click(function(){
        var attr= $(this).val();
    });
});
$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

/*洗码*/
function check(obj){
    if($(this).prop('clicked')){
        $(this).prop('clicked',false);
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",false);
    }else{
        $(this).attr('clicked',true);
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",true);
    }
}
$(".all-real").click(function() { 
    layer.confirm('确定要将洗码金额全部结算吗？', {
        btn: ['确定', '取消'] //按钮
    }, function () {
        layer.msg('已结算', {icon: 1});
    }, function () {

    });
});

/*申请优惠*/
 $(".apply").click(function() {
    if($(this).attr('clicked') == 'true'){
        $(this).attr('clicked',false);
       $(this).find('.details').text("查看详情");
       $(this).parents().parents().parents().find(".apply-pre").css("display","none");
    }else{
      	$(this).attr('clicked',true);
       	$(this).find('.details').text("收起详情"); 
       	$(this).parents().parents().parents().find(".apply-pre").css("display","block");
        $(this).parents().parents().parents().siblings().find(".apply").attr('clicked',false);
        $(this).parents().parents().parents().siblings().find(".apply").find('.details').text("查看详情");
        $(this).parents().parents().parents().siblings().find(".apply-pre").css("display","none");
    }
});


$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

$(".participation").click(function(){
    var _me = $(this);
    var act_id =_me.attr('act_id');
    var originValue = this.innerHTML;
    _me.html("正在申请...").removeClass("participation");
    _me.attr("disabled",true); //设置变灰按钮
    $.ajax({
        url:"players.applyParticipate",
        data:{
            'act_id' : act_id
        },
        type:"POST",
        success:function(data){
            if(data.success == true){
                _me.html("待审核").removeClass("btn-warning").addClass("btn-default");
                _me.attr("disabled",true); //设置变灰按钮
                layer.msg(data.message);
            }else if(data.success == false){
                _me.html(originValue).removeClass("btn-default").addClass("btn-warning");
                _me.attr("disabled",false);
                layer.msg('申请失败');
            }
        },
        error:function(xhr){
            _me.html(originValue).removeClass("btn-default").addClass("btn-warning");
            _me.attr("disabled",false);
            if(xhr.responseJSON.success ==false){
                layer.msg(xhr.responseJSON.message);
            }
        }
    });
});

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

$(".inquire1").click(function(){
    var index = layer.load(1, {
        shade: [0.1,'#fff'] ,
        time: 2000//20s后自动关闭
    });
});


/*投注记录*/
$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});
$(".inquire1").click(function(){
    var index = layer.load(1, {
        shade: [0.1,'#fff'] ,
        time: 2000//20s后自动关闭
    });
});

if($(".transferFrom option:selected").val()==""||$(".transferTo option:selected").val()==""){
    $(".true-account").css("disabled","disabled");
}else{
     $(".true-account").css("disabled","false");
}

$.jeDate(".datainp",{
    format: 'YYYY-MM-DD hh:mm:ss',
    minDate: '1900-01-01 00:00:00', //设定最小日期为当前日期
    isinitVal:false,
    festival:false,
    ishmsVal:false,
    maxDate: '2099-06-30 23:59:59', //最大日期
});
$.jeDate("#dateinfo",{
    format: 'YYYY-MM-DD hh:mm:ss',
    minDate: '1900-01-01 00:00:00', //设定最小日期为当前日期
    isinitVal:false,
    festival:false,
    ishmsVal:false,
    maxDate: '2099-06-30 23:59:59', //最大日期
});

$(".with-name").click(function(){
    $(this).attr("placeholder","");
});
$(".with-name").blur(function(){
    $(this).attr("placeholder","默认本人姓名");
});
$(".with-kade").click(function(){
    $(this).attr("placeholder","");
});
$(".with-kade").blur(function(){
    $(this).attr("placeholder","请填写您的银行卡号");
});
$(".subsidiary-bank").click(function(){
    $(this).attr("placeholder","");
});
$(".subsidiary-bank").blur(function(){
    $(this).attr("placeholder","如：岳阳市岳阳楼支行");
})


