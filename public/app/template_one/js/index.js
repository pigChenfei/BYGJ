// $(window).scroll(function() {
// 	if($(window).scrollTop() != 0) {
// 		$("header").css({
// 			'background': '#1a1a1a'
// 		})
// 		$(".form-group").css({
// 			'background': 'rgba(255,255,255,0.2)'
// 		});
// 	} else {
// 		$("header").css({
// 			'background': 'rgba(0,0,0,0.1)'
// 		})
// 		$(".form-group").css({
// 			'background': 'rgba(0,0,0,0.3)'
// 		});
// 	}
// });
$(function() {
	$(".index-main-nav li").off('click').on('click', function() {
		var _that = $(this),
			inx = _that.index(),
			tabShowInx = $(".tab-wrap:visible").index() - 1;
		if(inx == tabShowInx) {
			return false;
		} else {
			_that.siblings().find('a').removeClass('active');
			_that.find('a').addClass('active');
			$(".tab-wrap").hide().eq(inx).show();
		}
	})
});
$(function(){
    scroll_f();
    //滚动
    var scrollIndex=0;
    var Timer=null;
    function scroll_f(){
        clearInterval(Timer);
        var ul=$("#scroll ul");
        var li=ul.children("li");
        var h=li.height();
        var index=0;
        ul.css("height",h*li.length*2);
        ul.html(ul.html()+ul.html());
        function run(){
            if(scrollIndex>=li.length){
                ul.css({top:0});
                scrollIndex=1;
                ul.animate({top:-scrollIndex*h},1000);
            }else{
                scrollIndex++;
                ul.animate({top:-scrollIndex*h},1000);
            }
        }
        Timer=setInterval(run,2000);
    }
})

$('.modal').each(function(a,b){
	$(b).find('.btnwrap').append($('.header-foot li').eq(a+1).find('.nav-sm a'));
})
