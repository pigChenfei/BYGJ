$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

$(".all-checkbox").click(function(){
    console.log("1");
    if($(".message-with>div:nth-child(1)>div>input").is(':checked')){
    $(".message-with>div:nth-child(1)>div>input").prop("checked",false);
    }
    else{
        $(".message-with>div:nth-child(1)>div>input").prop("checked",true);
    }


});
$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

/*清空*/
$(".clear-SMS").click(function(){
    $(".tab-checkbox>tbody>tr>td:nth-child(1) :checkbox:checked").parent().parent().css("display","none");
});
$(".all-SMS").click(function(){

    $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",true);
});
$(".SMS-table>tbody>tr>td:nth-child(2)").click(
    function(){
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['900px', '450px'], //宽高
            content:$("#SMS")
        });
        $(this).css("font-weight","normal");
        $(this).parent().children("td:last").css("color","#333");
        $(this).parent().children("td:last").html("已读");
        $(this).parent().children("td:last").css("font-weight","inherit");
    }
);
function check2(obj){
    if($(this).prop('clicked')){
        $(this).prop('clicked',false);
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",false);
    }else{
        $(this).attr('clicked',true);
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",true);
    }
}

$(function() {
    $(".message-with").on("click", ".messages-go", function (e) {
        e.preventDefault();

        if ($(".message-with>div:nth-child(1)>div>input").is(':checked')) {
            var _me = this;
            var confimText = $(_me).val();
            var form = $(_me).parents("form");
            $(_me).val('提交中');
            $.ajax({
                url: "{!! route('players.deposit') !!}",
                data: form.serialize(),
                type: "POST",
                success: function (e) {
                    if (e.success == true) {
                        layer.alert('保存成功');
                    } else {
                        layer.alert('提交失败');
                    }
                    $(_me).val(confimText);
                },
                error: function () {
                    layer.alert('提交失败');
                    $(_me).val(confimText);
                }
            })
        } else {
            layer.alert('未选择');
        }
    });
});