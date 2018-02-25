/**
 * 会员中心-财务中心导航栏下拉链接跳转
 * author WQQ 2017-04-12 15:49:44
 */
$(function(){
	$("ul.sub_nav li a").on('click', function(){
		location.reload();
	});

	getTablePage('#memberDeposit', '#member-deposit', "players.deposit");
	getTablePage('#accountTransfer', '#account-transfer', "players.account-transfer");
	getTablePage('#withdraw', '#withdraw-money', "players.withdraw-money");
	getTablePage('#applyDiscount', '#apply-discount', "players.apply-for-discount");
	getTablePage('#realTime', '#real-time', "players.rebateFinancialFlow");

	var hrefSplits = window.location.href.split('#');
	if(hrefSplits.length >= 1){
		switch (hrefSplits[1]){
			case 'account-transfer':
				$('#accountTransfer').click();
				break;
			case 'withdraw-money':
				$('#withdraw').click();
				break;
			case 'member-deposit':
				$('#memberDeposit').click();
				break;
			case 'apply-discount':
				$('#applyDiscount').click();
				break;
			case 'real-time':
				$('#realTime').click();
				break;
			default:
				$('#memberDeposit').click();
				break;
		}
	}
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
