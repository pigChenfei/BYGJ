$(function(){

    /**
     * 会员中心-财务报表导航栏下拉链接跳转
     * author WQQ 2017-04-12 15:49:44
     */
    $("ul.sub_nav li a").on('click', function() {
        location.reload();
    });

    getTablePage('#myRecommends', '#my-recommends', "players.myRecommends");
    getTablePage('#myReferrals', '#my-referrals', "players.myReferrals");
    getTablePage('#accountStatistics', '#account-statistics', "players.accountStatistics");

    var hrefSplits = window.location.href.split('#');
    if(hrefSplits.length >= 1){
        switch (hrefSplits[1]){
            case 'my-recommends':
                $('#myRecommends').click();
                break;
            case 'my-referrals':
                $('#myReferrals').click();
                break;
            case 'account-statistics':
                $('#accountStatistics').click();
                break;
            default:
                $('#myRecommends').click();
                break;
        }
    }

    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });

    $("marquee").click(function(){
        layer.alert('   尊敬的泰雅365会员您好！全新版本已上线，给您带来全新的体验效果！祝您好运！！！')
    });
    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });
// $(".inquire1").click(function(){
//     var index = layer.load(1, {
//         shade: [0.1,'#fff'] ,
//         time: 2000//20s后自动关闭
//     });
// });
    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });
    $(".statistics-click>span").click(function(){
        $(this).addClass("btn-danger").removeClass("btn-default").siblings().addClass("btn-default").removeClass("btn-danger");
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
});

//页面ajax请求
function getTablePage(clickTag, showTag, url){
    $(clickTag).on('click', function(e){
        e.preventDefault();
        $.ajax({
            url:url,
            dataType:'text',
            success:function(resp){
                $(showTag).html(resp);
            },
            error:function(xhr){
                //xhr.responseJson
            }
        });
    });
}
