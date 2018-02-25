
$(function(){
	var ococus=$('#cocus'),
		oRight=ococus.find('.right'),
		oLeft=ococus.find('.left'),
		aRLi=oRight.find('li'),
		aLLi=oLeft.find('li'),
		index=0,
		timer = null;
	aRLi.mouseenter(function(){
		index=$(this).index();
		aLLi.eq(index).stop().addClass("active").animate({'opacity':1},500).siblings().stop().removeClass("active").animate({'opacity':0},500);
		aRLi.eq(index).find("i").stop().addClass("i").parent().siblings().find("i").stop().removeClass("i");
		stopFoucs();
	}).mouseleave(function(){
		timer = setInterval(function(){
			startFocus();
		},3000);
	});
	oLeft.mouseenter(function(){
		stopFoucs();
	}).mouseleave(function(){
		timer = setInterval(function(){
			startFocus();
		},3000);
	});
	timer = setInterval(function(){
		startFocus();
	},3000);
	function startFocus(){
		index++;
		index = index > aRLi.length-1 ? 0 :index;
		aLLi.eq(index).stop().addClass("active").animate({'opacity':1},500).siblings().stop().removeClass("active").animate({'opacity':0},500);
		aRLi.eq(index).stop().addClass("active").siblings().stop().removeClass("active");
		aRLi.eq(index).find("i").stop().addClass("i").parent().siblings().find("i").stop().removeClass("i");
	}
	function stopFoucs() {
		clearInterval(timer);
	}
});
$(function(){
	var ofocus=$('#focus'),
		oRight=ofocus.find('.right'),
		oLeft=ofocus.find('.left'),
		aRLi=oRight.find('li'),
		aLLi=oLeft.find('li'),
		index=0,
		timer = null;
	aRLi.mouseenter(function(){
		index=$(this).index();
		aLLi.eq(index).stop().addClass("active").animate({'opacity':1},500).siblings().stop().removeClass("active").animate({'opacity':0},500);
		aRLi.eq(index).find("i").stop().addClass("i").parent().siblings().find("i").stop().removeClass("i");
		stopFoucs();
	}).mouseleave(function(){
		timer = setInterval(function(){
			startFocus();
		},3000);
	});
	oLeft.mouseenter(function(){
		stopFoucs();
	}).mouseleave(function(){
		timer = setInterval(function(){
			startFocus();
		},3000);
	});

	timer = setInterval(function(){
		startFocus();
	},3000);
	function startFocus(){
		index++;
		index = index > aRLi.length-1 ? 0 :index;
		aLLi.eq(index).stop().addClass("active").animate({'opacity':1},500).siblings().stop().removeClass("active").animate({'opacity':0},500);
		aRLi.eq(index).stop().addClass("active").siblings().stop().removeClass("active");
		aRLi.eq(index).find("i").stop().addClass("i").parent().siblings().find("i").stop().removeClass("i");
	}
	function stopFoucs() {
		clearInterval(timer);
	}
});
$(".nav-nav li ul li a").mouseover(function(){
	$(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
	$(".back-nav>div").css("display","block");
});




