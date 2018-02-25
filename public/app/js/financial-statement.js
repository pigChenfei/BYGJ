//财务报表

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});
$(".deposit-clear").click(function(){
    if($(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked") == true){
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",false);
    }else{
        $(".tab-checkbox>tbody>tr>td:nth-child(1) input").prop("checked",true);
    }

});
$(".clear-records").click(function(){
        $(this).parent().css("display","none");
});
/*取款记录*/
$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

//  layui-layer-loading1
$('.Card-number-wrong').click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['310px', '300px'], //宽高
        content:$('#deposit6')
    });
});
/*洗码记录*/

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

//日期选择初始化设置
var start = {
    format: 'YYYY-MM-DD hh:mm:ss',
    minDate: '1900-01-01 00:00:00', //设定最小日期为当前日期
    isinitVal:false,
    festival:false,
    ishmsVal:false,
    maxDate: '2099-06-30 23:59:59', //最大日期
    choosefun: function(elem,datas){
        end.minDate = datas; //开始日选好后，重置结束日的最小日期
    }
};
var end = {
    format: 'YYYY-MM-DD hh:mm:ss',
    minDate: '1900-01-01 00:00:00', //设定最小日期为当前日期
    isinitVal:false,
    festival:false,
    ishmsVal:false,
    maxDate: '2099-06-16 23:59:59', //最大日期
    choosefun: function(elem,datas){
        start.maxDate = datas; //将结束日的初始值设定为开始日的最大日期
    }
};
$.jeDate('.inpstart',start);
$.jeDate('.inpend',end);

function p(s){
    return s<10?'0' +s:s;
}
var date_ = new Date();  
var year = date_.getFullYear();  
var month = date_.getMonth() + 1;  
var firstdate = year + '-' + p(month) + '-01'    
$(".inpstart").val(firstdate+" 00:00:00");
var day = new Date(year,month,0);      
var lastdate = year + '-' + p(month) + '-' + day.getDate();  
$(".inpend").val(lastdate+" 23:59:59");
