/**
 * 会员中心-财务报表导航栏下拉链接跳转
 * author WQQ 2017-04-12 15:49:44
 */
$(function(){
	$("ul.sub_nav li a").on('click', function(){
		location.reload();
	});

	getTablePage('#depositRecord', '#deposit-record', "players.depositRecords");
	getTablePage('#withdrawalRecord', '#withdrawal-record', "players.withdrawRecords");
	getTablePage('#transferRecord', '#transfer-record', "players.transferRecords");
	getTablePage('#washCodeRecord', '#wash-code-record', "players.washCodeRecords");
	getTablePage('#preferentialRecord', '#preferential-record', "players.discountRecords");
	getTablePage('#bettingRecord', '#betting-record', "players.bettingRecords");

	var hrefSplits = window.location.href.split('#');
	if(hrefSplits.length >= 1){
		switch (hrefSplits[1]){
			case 'deposit-record':
				$('#depositRecord').click();
				break;
			case 'withdrawal-record':
				$('#withdrawalRecord').click();
				break;
			case 'transfer-record':
				$('#transferRecord').click();
				break;
			case 'wash-code-record':
				$('#washCodeRecord').click();
				break;
			case 'preferential-record':
				$('#preferentialRecord').click();
				break;
			case 'betting-record':
				$('#bettingRecord').click();
				break;
			default:
				$('#depositRecord').click();
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
