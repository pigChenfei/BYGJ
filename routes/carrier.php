<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/26
 * Time: 15:16
 */
Route::group([
    'namespace' => 'Carrier'
],
    function () {
        
        Auth::routes();
        
        Route::get('beingWallPaper', 'CarrierLoginPageWallPaper@index')->name('beingWallPaper');
        
        // -----------------------WUGANG BEGIN --------------------------//
        \App\Http\Controllers\Carrier\Test\TestGameGatewayController::routeList();
        // -----------------------WUGANG END --------------------------//
        
        Route::group([
            'middleware' => [
                'auth:carrier',
                'carrierLog'
            ]
        ],
            function () {
                Route::get('notifications', 'Auth\CarrierNotificationDataController@index')->name('notifications');
                Route::post('markAsReadNotifications', 'Auth\CarrierNotificationDataController@markAsReadNotification')->name('markAsReadNotifications');
                // -----------------------WUGANG BEGIN -----------------------//
                \App\Http\Controllers\Carrier\CarrierMetaDataController::routeLists();
                Route::get('/', 'HomeController@index');
                Route::get('carrierImages.showUploadImageModal', 'CarrierImageController@showUploadImageModal')->name('carrierImages.showUploadImageModal');
                Route::get('playerWithdrawLogs.payModal/{withdraw_log_id}', 'PlayerWithdrawLogController@payModal')->name('playerWithdrawLogs.payModal');
                Route::get('playerWithdrawLogs.refuseModal/{withdraw_log_id}', 'PlayerWithdrawLogController@refuseModal')->name('playerWithdrawLogs.refuseModal');
                \App\Http\Controllers\Carrier\PlayerController::normalRouteLists();
                Route::get('playerDepositLogs.showReviewDepositLogModal/{deposit_log_id}', 'PlayerDepositPayReviewLogController@showReviewDepositLogModal')->name('playerDepositLogs.showReviewDepositLogModal');
                Route::get('carrierActivityAudits.bonusEdit/{id}', 'CarrierActivityAuditController@bonusEdit')->name('carrierActivityAudits.bonusEdit'); // 活动审核管理
                Route::get('carrierActivityFund.index', 'CarrierActivityFundStatistic@index')->name('carrierActivityFund.index'); // 活动资金统计
                Route::get('carrierActivityAuditHistory.index', 'CarrierActivityAuditHistoryController@index')->name('carrierActivityAuditHistory.index'); // 活动审核历史
                Route::get('carrierActivityAudits.refuseModal/{id}', 'CarrierActivityAuditController@refuseActivityAuditModal')->name('carrierActivityAudits.refuseModal'); // 拒绝活动申请模态框
                Route::post('carrierPayChannels.payment', 'CarrierPayChannelController@payment')->name('carrierPayChannels.payment');
                Route::post('carrierPayChannels.channelType', 'CarrierPayChannelController@channelType')->name('carrierPayChannels.channelType');
                Route::get('carrierPayChannels.showManualTransferRecordModal', 'CarrierPayChannelController@showManualTransferRecordModal')->name('carrierPayChannels.showManualTransferRecordModal');
                Route::get('CarrierPlayerLevels.rebateFlowShow/{level_id}', 'CarrierPlayerLevelController@rebateFlowShow')->name('CarrierPlayerLevels.rebateFlowShow');
                Route::get('CarrierPlayerLevels.rebateFlowEdit/{level_game_plat_id}', 'CarrierPlayerGamePlatRebateFinancialFlowController@edit')->name('CarrierPlayerLevels.rebateFlowEdit');
                Route::resource('carrierWinLoseStastics', 'CarrierWinLoseStasticsController');
                Route::resource('agentWinLoseStastics', 'AgentWinLoseStasticsController');
                Route::resource('gameWinLoseStastics', 'GameWinLoseStasticsController');
                // -----------------------WUGANG END -----------------------//
                Route::group([
                    'middleware' => 'rbac'
                ],
                    function () {
                        Route::resource('carrierUsers', 'CarrierUserController');
                        Route::resource('carrierAgentLevels', 'CarrierAgentLevelController'); // 代理层级
                        Route::resource('carrierPlayerLevels', 'CarrierPlayerLevelController');
                        // -----------------------GUOWEI BEGIN -----------------------//
                        Route::resource('carrierGames', 'CarrierGameController');
                        Route::resource('carrierGamePlats', 'CarrierGamePlatController');
                        Route::resource('carrierServiceTeams', 'CarrierServiceTeamController'); // 客服部门设置
                        Route::get('CarrierServiceTeams.permissionSetShow/{id}', 'CarrierServiceTeamController@permissionSetShow')->name('CarrierServiceTeams.permissionSetShow');
                        Route::post('CarrierServiceTeams/permissionSave', 'CarrierServiceTeamController@permissionSave')->name('CarrierServiceTeams.permissionSave');
                        Route::get('carrierUsers/editPassword/{id}', 'CarrierUserController@editPassword')->name('carrierUsers.editPassword'); // 客服账号修改密码
                        Route::patch('carrierUsers/savePassword/{id}', 'CarrierUserController@savePassword')->name('carrierUsers.savePassword'); // 客服账号保存修改密码
                        Route::resource('carrierDepositConfs', 'CarrierDepositConfController');
                        Route::resource('carrierWithdrawConfs', 'CarrierWithdrawConfController');
                        Route::resource('carrierPasswordRecoverySiteConfs', 'CarrierPasswordRecoverySiteConfController');
                        // -----------------------GUOWEI END -----------------------//
                        
                        // -----------------------WUGANG BEGIN -----------------------//
                        Route::patch('carrierPlayerLevels.bank', 'CarrierPlayerLevelBankCardMapController@update')->name('carrierPlayerLevels.bank');
                        Route::patch('CarrierPlayerLevels.rebateFlowUpdate/{level_game_plat_id}', 'CarrierPlayerGamePlatRebateFinancialFlowController@update')->name('CarrierPlayerLevels.rebateFlowUpdate');
                        Route::post('CarrierPlayerLevels.storeImg', 'CarrierPlayerLevelController@storeImg')->name('CarrierPlayerLevels.storeImg');
                        \App\Http\Controllers\Carrier\PlayerController::rbacRouteLists();
                        Route::resource('playerAccountLogs', 'PlayerAccountLogController');
                        Route::resource('carrierImages', 'CarrierImageController');
                        Route::resource('carrierImageCategories', 'CarrierImageCategoryController');
                        Route::resource('carrierWebSiteConfs', 'CarrierWebSiteConfController');
                        Route::resource('carrierDashLoginConfs', 'CarrierDashLoginConfController');
                        Route::resource('playerDepositPayLogs', 'PlayerDepositPayLogController');
                        Route::resource('playerDepositPayReviewLogs', 'PlayerDepositPayReviewLogController');
                        Route::resource('playerWithdrawFlowLimitLogs', 'PlayerWithdrawFlowLimitLogController');
                        Route::get('playerTransferUnknownProcess.checkAuto/{id}', 'PlayerTransferUnknownProcessController@check')->name('playerWithdrawLogs.checkAuto');
                        Route::post('playerTransferUnknownProcess.success/{id}', 'PlayerTransferUnknownProcessController@transferSuccess')->name('playerWithdrawLogs.success');
                        Route::post('playerTransferUnknownProcess.fail/{id}', 'PlayerTransferUnknownProcessController@transferFail')->name('playerWithdrawLogs.fail');
                        Route::resource('playerTransferUnknownProcess', 'PlayerTransferUnknownProcessController');
                        Route::get('carrierInviteFriendConf.edit', 'CarrierInviteFriendController@showEdit')->name('carrierInviteFriendConf.edit');
                        
                        Route::resource('playerAccountAdjustLogs', 'PlayerAccountAdjustLogController');
                        Route::get('playerAccountAdjustLogs.passPlayerAccountEdit', 'PlayerAccountAdjustLogController@passPlayerAccountEdit')->name('playerAccountAdjustLogs.passPlayerAccountEdit');
                        Route::get('playerAccountAdjustLogs.hAccountEdit', 'PlayerAccountAdjustLogController@hAccountEdit')->name('playerAccountAdjustLogs.hAccountEdit');
                        Route::get('playerAccountAdjustLogs.xAccountEdit', 'PlayerAccountAdjustLogController@xAccountEdit')->name('playerAccountAdjustLogs.xAccountEdit');
                        Route::post('playerAccountAdjustLogs.savePassPlayerAccount', 'PlayerAccountAdjustLogController@savePassPlayerAccount')->name('playerAccountAdjustLogs.savePassPlayerAccount');
                        Route::resource('playerAccountAdjustLogs', 'PlayerAccountAdjustLogController');
                        Route::resource('playerRebateFinancialFlows', 'PlayerRebateFinancialFlowController');
                        Route::post('playerRebateFinancialFlows.passRebateFinancialFlowLog', 'PlayerRebateFinancialFlowController@passRebateFinancialFlowLog')->name('playerRebateFinancialFlows.passRebateFinancialFlowLog');
                        Route::resource('playerBetFlowLogs', 'PlayerBetFlowLogController');
                        Route::get('playerWithdrawLogs/verify', 'PlayerWithdrawLogController@verify')->name('playerWithdrawLogs.verify');
                        Route::resource('playerWithdrawLogs', 'PlayerWithdrawLogController');
                        Route::patch('playerWithdrawLogs.refuseWithdrawApply/{withdraw_log_id}', 'PlayerWithdrawLogController@refuseWithdrawApply')->name('playerWithdrawLogs.refuseWithdrawApply');
                        Route::patch('playerWithdrawLogs.resetWithdrawFlowRecord/{withdraw_log_id}', 'PlayerWithdrawLogController@resetWithdrawFlowRecord')->name('playerWithdrawLogs.resetWithdrawFlowRecord');
                        Route::resource('carrierQuotaConsumptionLogs', 'CarrierQuotaConsumptionLogController');
                        Route::patch('saveInvitePlayerConf', 'CarrierInviteFriendController@saveInvitePlayerConf')->name('saveInvitePlayerConf');
                        Route::resource('playerInviteRewardLogs', 'PlayerInviteRewardLogController');
                        
                        Route::post('playerDepositLogs.reviewDepositLog/{deposit_log_id}', 'PlayerDepositPayReviewLogController@reviewDepositLog')->name('playerDepositLogs.reviewDepositLog');
                        
                        // -----------------------WUGANG END -----------------------//
                        
                        // ------------------------WANGNING BEGIN ----------------------//
                        // 流水限制汇总 重启 和完成
                        Route::post('playerWithdrawFlowLimitLogs.passCompleteFinished', 'PlayerWithdrawFlowLimitLogController@passCompleteFinished')->name('playerWithdrawFlowLimitLogs.passCompleteFinished');
                        // 投注明细 设为有效/无效
                        Route::post('playerBetFlowLogs.passBetFlowAvailable', 'PlayerBetFlowLogController@passBetFlowAvailable')->name('playerBetFlowLogs.passBetFlowAvailable');
                        
                        App\Http\Controllers\Carrier\CarrierAgentUserController::routeLists();
                        Route::resource('carrierAgentDomains', 'CarrierAgentDomainController'); // 代理域名
                        Route::resource('carrierAgentAudits', 'CarrierAgentAuditController'); // 代理审核
                        Route::resource('carrierAgentAuditHistorys', 'CarrierAgentAuditHistoryController'); // 代理审核
                        Route::get('carrierAgentAudits.audit/{id}', 'CarrierAgentAuditController@audit')->name('carrierAgentAudits.audit'); // 代理审核(客服操作)
                        Route::patch('carrierAgentAudits.saveAudit/{id}', 'CarrierAgentAuditController@saveAudit')->name('carrierAgentAudits.saveAudit'); // 点击保存代理审核(客服操作)
                        
                        /**
                         * ----------------------------------优惠活动BEGIN-------------------------------------
                         */
                        Route::resource('carrierActivityTypes', 'CarrierActivityTypeController'); // 优惠活动类型
                        Route::patch('carrierActivityTypes.saveStatus/{id}', 'CarrierActivityTypeController@saveStatus')->name('carrierActivityTypes.saveStatus'); // 优惠活动类型启用禁用
                        Route::resource('carrierActivities', 'CarrierActivityController'); // 优惠活动
                        Route::patch('carrierActivities.saveStatus/{id}', 'CarrierActivityController@saveStatus')->name('carrierActivities.saveStatus'); //
                        Route::resource('carrierActivityAudits', 'CarrierActivityAuditController'); // 活动审核管理
                        /**
                         * ----------------------------------优惠活动END-------------------------------------
                         */
                        
                        // 银行卡支付设置
                        Route::resource('carrierPayBankCards', 'CarrierPayBankCardController');
                        Route::get('carrierPayBankCards.accountList', 'CarrierPayBankCardController@account_list')->name('carrierPayBankCards.accountList');
                        Route::get('carrierPlayerLevels.bankCardAll/{id}', 'CarrierPlayerLevelController@bankCardAll')->name('carrierPlayerLevels.bankCardAll');
                        
                        Route::resource('carrierPayChannels', 'CarrierPayChannelController'); // 银行卡支付设置
                        Route::post('carrierPayChannels.newManualTransferRecord', 'CarrierPayChannelController@newManualTransferRecord')->name('carrierPayChannels.newManualTransferRecord'); // 新增手动插入转账记录
                        Route::patch('carrierPayChannels.saveStatus/{id}', 'CarrierPayChannelController@saveStatus')->name('carrierPayChannels.saveStatus'); // 银行账户管理启用禁用
                        
                        Route::resource('carrierThirdPartPays', 'CarrierThirdPartPayController'); // 银行卡接口设置
                        Route::get('carrierPayChannels.paylist/{id}', 'CarrierPayChannelController@payList')->name('carrierPayChannels.payList'); // 绑定三方支付
                        Route::patch('carrierPayChannels.bindPaylist/{cid}', 'CarrierPayChannelController@bindPayList')->name('carrierPayChannels.bindPayList'); // 确定绑定三方支付
                        Route::get('carrierPayChannels.unbundList/{id}', 'CarrierPayChannelController@unbundList')->name('carrierPayChannels.unbundList'); // 解绑三方支付
                        Route::patch('carrierPayChannels.unbundPayList/{cid}', 'CarrierPayChannelController@unbundPayList')->name('carrierPayChannels.unbundPayList'); // 确定解绑三方支付
                        Route::get('carrierThirdPartPays.getInfo', 'CarrierThirdPartPayController@getInfo')->name('carrierThirdPartPays.getInfo'); // list详情
                                                                                                                                                   // 平台费设置
                        Route::get('carrierAgentLevels.platformFee/{id}', 'CarrierAgentLevelController@platformFee')->name('carrierAgentLevels.platformFee'); // 平台费设置
                        Route::patch('carrierAgentLevels.savePlatformFee/{id}', 'CarrierAgentLevelController@savePlatformFee')->name('carrierAgentLevels.savePlatformFee'); //
                        
                        Route::get('carrierAgentLevels.agentLevelRebate/{id}', 'CarrierAgentLevelController@agentLevelRebate')->name('carrierAgentLevels.agentLevelRebate'); // 下级代理抽成比例设置
                        Route::patch('carrierAgentLevels.saveAgentLevelRebate/{id}', 'CarrierAgentLevelController@saveAgentLevelRebate')->name('carrierAgentLevels.saveAgentLevelRebate'); //
                                                                                                                                                                                           
                        // 佣金设置
                        Route::get('carrierAgentLevels.commissionAgent/{id}', 'CarrierAgentLevelController@commissionAgent')->name('carrierAgentLevels.commissionAgent'); // 佣金代理
                        Route::patch('carrierAgentLevels.saveCommissionAgent/{id}', 'CarrierAgentLevelController@saveCommissionAgent')->name('carrierAgentLevels.saveCommissionAgent'); // 保存佣金代理
                                                                                                                                                                                        // 洗码设置
                        Route::get('carrierAgentLevels.rebateFinancialFlowAgent/{id}', 'CarrierAgentLevelController@rebateFinancialFlowAgent')->name('carrierAgentLevels.rebateFinancialFlowAgent');
                        Route::patch('carrierAgentLevels.saveRebateFinancialFlowAgent/{id}', 'CarrierAgentLevelController@saveRebateFinancialFlowAgent')->name('carrierAgentLevels.saveRebateFinancialFlowAgent');
                        // 占成代理设置
                        Route::get('carrierAgentLevels.costTakeAgent/{id}', 'CarrierAgentLevelController@costTakeAgent')->name('carrierAgentLevels.costTakeAgent');
                        Route::patch('carrierAgentLevels.saveCostTakeAgent/{id}', 'CarrierAgentLevelController@saveCostTakeAgent')->name('carrierAgentLevels.saveCostTakeAgent');
                        // 代理调整资金记录
                        Route::resource('agentAccountAdjustLogs', 'AgentAccountAdjustLogController');
                        // 代理代理佣金结算
                        Route::resource('carrierAgentSettleLogs', 'CarrierAgentSettleLogController');
                        // 公司输赢
                        Route::get('carrierAgentSettleLogs.gamePlatWinAmount/{id}', 'CarrierAgentSettleLogController@gamePlatWinAmount')->name('carrierAgentSettleLogs.gamePlatWinAmount');
                        // 成本分摊
                        Route::get('carrierAgentSettleLogs.costShare/{id}', 'CarrierAgentSettleLogController@costShare')->name('carrierAgentSettleLogs.costShare');
                        // 代理洗码
                        Route::get('carrierAgentSettleLogs.rebate/{id}', 'CarrierAgentSettleLogController@rebateList')->name('carrierAgentSettleLogs.rebate');
                        // 手工调整
                        Route::get('carrierAgentSettleLogs.manualTuneup/{id}', 'CarrierAgentSettleLogController@manualTuneup')->name('carrierAgentSettleLogs.manualTuneup');
                        // 保存手工调整
                        Route::patch('carrierAgentSettleLogs.saveManualTuneup/{id}', 'CarrierAgentSettleLogController@saveManualTuneup')->name('carrierAgentSettleLogs.saveManualTuneup');
                        // 累加上月
                        Route::get('carrierAgentSettleLogs.cumulativeShow/{id}', 'CarrierAgentSettleLogController@cumulativeShow')->name('carrierAgentSettleLogs.cumulativeShow');
                        // 实际发放
                        Route::get('carrierAgentSettleLogs.actualPayment/{id}', 'CarrierAgentSettleLogController@actualPayment')->name('carrierAgentSettleLogs.actualPayment');
                        // 保存实际发放
                        Route::patch('carrierAgentSettleLogs.saveActualPayment/{id}', 'CarrierAgentSettleLogController@saveActualPayment')->name('carrierAgentSettleLogs.saveActualPayment');
                        // 初审
                        Route::get('carrierAgentSettleLogs.theTrial/{id}', 'CarrierAgentSettleLogController@theTrial')->name('carrierAgentSettleLogs.theTrial');
                        // 保存初审
                        Route::patch('carrierAgentSettleLogs.saveTheTrial/{id}', 'CarrierAgentSettleLogController@saveTheTrial')->name('carrierAgentSettleLogs.saveTheTrial');
                        // 复审
                        Route::get('carrierAgentSettleLogs.reviewTrial/{id}', 'CarrierAgentSettleLogController@reviewTrial')->name('carrierAgentSettleLogs.reviewTrial');
                        // 保存复审
                        Route::patch('carrierAgentSettleLogs.saveReviewTrial/{id}', 'CarrierAgentSettleLogController@saveReviewTrial')->name('carrierAgentSettleLogs.saveReviewTrial');
                        // 重新结算
                        Route::get('carrierAgentSettleLogs.reSettlement', 'CarrierAgentSettleLogController@reSettlement')->name('carrierAgentSettleLogs.reSettlement');
                        // 保存重新结算
                        Route::patch('carrierAgentSettleLogs.saveReSettlement', 'CarrierAgentSettleLogController@saveReSettlement')->name('carrierAgentSettleLogs.saveReSettlement');
                        
                        // 代理结算历史
                        Route::resource('carrierAgentSettleHistoryLogs', 'CarrierAgentSettleHistoryLogController');
                        // 代理存款审核
                        Route::resource('carrierAgentDepositVerify', 'CarrierAgentDepositVerifyController');
                        // 代理存款历史纪录
                        Route::resource('carrierAgentDepositPayLogs', 'CarrierAgentDepositPayLogController');
                        // 代理取款审核
                        Route::resource('carrierAgentWithdrawLogsVerify', 'CarrierAgentWithdrawLogVerifyController');
                        // 代理取款纪录
                        Route::resource('carrierAgentWithdrawLogs', 'CarrierAgentWithdrawLogController');
                        // 拒绝代理申请取款
                        Route::get('carrierAgentWithdrawLogs.refuseModal/{id}', 'CarrierAgentWithdrawLogController@refuseModal')->name('carrierAgentWithdrawLogs.refuseModal');
                        Route::patch('carrierAgentWithdrawLogs.refuseWithdrawApply/{id}', 'CarrierAgentWithdrawLogController@refuseWithdrawApply')->name('carrierAgentWithdrawLogs.refuseWithdrawApply');
                        
                        // 通过代理申请取款
                        Route::get('carrierAgentWithdrawLogs.payModal/{id}', 'CarrierAgentWithdrawLogController@payModal')->name('carrierAgentWithdrawLogs.payModal');
                        // 会员消息
                        Route::resource('carrierPlayerNews', 'CarrierPlayerNewsController');
                        // 代理消息
                        Route::resource('carrierAgentNews', 'CarrierAgentNewsController');
                        // ------------------------WANGNING END ------------------------//
                    });
            });
    });