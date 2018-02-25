<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 15:17
 */
Route::group([
    'namespace' => 'Agent'
], function () {
    
    // 代理后台路由
    Route::get('beingWallPaper', 'AgentLoginPageWallPaper@index')->name('beingWallPaper');
    
    Route::group([
        'prefix' => 'admin'
    ], function () {
        Auth::routes();
        Route::group(['middleware' => ['auth:agent']],function(){
            Route::get('/','HomeController@index');

            /***************  GUO WEI BEGIN *****************/
//            Route::post('dataAgentLevel','HomeController@dataAgentLevel')->name('dataAgentLevel');//代理管理(代理类型二级联动)
            Route::resource('agentSubs','AgentSubController');
            Route::resource('agentCenters','AgentCenterController'); //账户信息
            Route::post('agentOpenAccounts.createAgent','AgentOpenAccountController@createAgent')->name('agentOpenAccounts.createAgent'); //代理开户
            Route::post('agentOpenAccounts.createPlayer','AgentOpenAccountController@createPlayer')->name('agentOpenAccounts.createPlayer'); //玩家开户
            Route::resource('agentWithdraws','AgentWithdrawController'); //快速取款
            Route::delete('agentWithdraws.del','AgentWithdrawController@del')->name('agentWithdraws.del'); //删除银行阿卡
            Route::post('agentWithdraws.withdrawQuotaCheck', 'AgentWithdrawController@withdrawQuotaCheck')->name('agentWithdraws.withdrawQuotaCheck');//代理取款限额检查
            Route::post('agentWithdraws.withdrawRequest', 'AgentWithdrawController@withdrawRequest')->name('agentWithdraws.withdrawRequest');//代理取款申请
            Route::post('agentAccountCenters.agentInformationUpdate','AgentAccountCenterController@agentInformationUpdate')->name('agentAccountCenters.agentInformationUpdate'); //更新代理个人信息
            Route::resource('agentPerformances','AgentPerformanceController');//代理业绩报表
            Route::resource('agentScatterReports','AgentScatterReportController');//代理洗码报表
            Route::resource('subordinateAgentScatterReports','SubordinateAgentScatterReportController');//子代理洗码报表
            Route::get('agentScatterReports.details/{id}', 'AgentScatterReportController@details')->name('agentScatterReports.details');//代理洗码报表 查看详情
            Route::get('subordinateAgentScatterReports.details/{id}','SubordinateAgentScatterReportController@details')->name('subordinateAgentScatterReports.details');//子代理洗码报表 查看详情
            /*************** GUO WEI END *****************/
            Route::get('sms', 'HomeController@smsSubscriptions')->name('sms.index');
            Route::put('sms', 'HomeController@smsRead')->name('sms.read');
            Route::delete('sms', 'HomeController@smsDestroy')->name('sms.del');
            Route::resource('agentAccountCenters', 'AgentAccountCenterController'); // 账户信息
            Route::post('agentAccountCenters/modifyPassword', 'AgentAccountCenterController@modifyPassword'); // 密码修改
            Route::resource('agentPromotePics', 'AgentPromotePicController'); // 推广图片
            Route::resource('agentOpenAccounts', 'AgentOpenAccountController'); // 开户中心
            Route::resource('agentPlayers', 'AgentPlayerController'); // 会员报表
            Route::resource('agentPlayerDepositLogs', 'AgentPlayerDepositLogController'); // 会员报表->存款记录
            Route::resource('agentPlayerWithdrawLogs', 'AgentPlayerWithdrawLogController'); // 会员报表-》取款记录
            Route::resource('agentPlayerActivityLogs', 'AgentPlayerActivityLogController'); // 会员报表-》红利记录
            Route::resource('agentPlayerBetLogs', 'AgentPlayerBetLogController'); // 会员报表-》投注记录
            Route::resource('playerRebateFinancialFlows', 'PlayerRebateFinancialFlowController'); // 会员报表-》洗码记录
            Route::resource('agentWithdrawLogs', 'AgentWithdrawLogController'); // 代理取款记录
            Route::resource('agentSettleReports', 'AgentSettleReportController'); // 代理佣金报表
            Route::get('agentSettleReports.details/{id}', 'AgentSettleReportController@details')->name('agentSettleReports.details'); // 代理佣金报表 查看详情
            Route::get('agentSettleReports.rebate/{id}', 'AgentSettleReportController@rebate')->name('agentSettleReports.rebate'); // 代理佣金报表 查看详情
            Route::get('agentPlayers.rebate', 'AgentPlayerController@rebate')->name('agentPlayers.rebate'); // 洗码比例
            Route::get('agentSettleReports.commission', 'AgentSettleReportController@commission')->name('agentSettleReports.commission'); // 下级提成
            Route::get('agentSettleReports.commissionCut', 'AgentSettleReportController@commissionCut')->name('agentSettleReports.commissionCut'); //上级抽成
            Route::post('agentPlayers.saveRebate', 'AgentPlayerController@saveRebate')->name('agentPlayers.saveRebate'); //上级抽成
            Route::get('agentAccountCenters.bindEmail', 'AgentAccountCenterController@bindEmail')->name('agentAccountCenters.bindEmail'); // 绑定邮箱账号发送验证码
        /**
         * ************* WANGNING ****************
         */
        });
    });
});