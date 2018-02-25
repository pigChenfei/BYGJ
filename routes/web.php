<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */
Route::group([
    'namespace' => 'Agent'
], function () {
    Route::group([
        'prefix' => 'admin'
    ], function () {
        Auth::routes();
    });
});
Route::get('/cache', function () {
    return cache('key');
});
// 支付 webhook
Route::group([
    'namespace' => 'Payment'
],
    function () {
        Route::any('/postback/{gateway}/{order_id?}', 'CheckoutController@webhook');
        Route::any('/player.pay/return/{gateway}/{order_id?}', 'CheckoutController@returnNotify')->name('pay.return');
    });

Route::group([
    'namespace' => 'Web'
],
    function () {
        
        // ---------------------WUGANG BEGIN -----------------------//
        \App\Vendor\GameGateway\Gateway\GameGatewayRunTime::route();
        \App\Vendor\Pay\Gateway\PayOrderRuntime::orderRouteList();
        // ---------------------WUGANG END -----------------------//
        // author WQQ 2017-3-21 16:51:24
        // -----------------------WQQ BEGIN --------------------------//
        // 顶部
        Route::get('homes.test/{action}', 'TestController@test')->name('homes.test'); // 测试
                                                                                      // Route::get('homes.testa', 'TestAController@test')->name('homes.testa'); // 测试
        Route::get('homes.isonline', 'HomeController@isonline')->name('homes.isonline'); // 测试
        Route::get('/', 'HomeController@index')->name('/'); // 首页
        Route::post('homes.login', 'HomeController@login')->name('member/login'); // 登录
        Route::get('homes.registerPage', 'HomeController@registerPage')->name('homes.registerPage'); // 注册页面
        Route::get('homes.moblieRegister', 'HomeController@moblieRegister')->name('homes.moblieRegister'); // 注册页面
        Route::get('homes.mobileLogin', 'HomeController@mobileLogin')->name('homes.mobileLogin'); // 注册页面
        Route::get('homes.mobileForget', 'HomeController@moblieForget')->name('homes.mobileForget'); // 手机忘记密码发送邮箱验证码
        Route::get('homes.mobileForgetPage', 'HomeController@mobileForgetPage')->name('homes.mobileForgetPage'); // 手机忘记密码发送邮箱验证码成功页面
        Route::get('homes.mobileForgetVerify', 'HomeController@mobileForgetVerify')->name('homes.mobileForgetVerify'); // 手机忘记密码发送邮箱验证码成功页面
        Route::get('homes.contactCustomer', 'HomeController@contactCustomer')->name('homes.contactCustomer'); // 联系客服页面
                                                                                                              
        // 发送邮件验证码
        Route::get('homes.sendEmailVerificode', 'HomeController@sendEmailVerificode')->name('homes.sendEmailVerificode'); // 会员退出
                                                                                                                          // 邮箱找回密码
        Route::get('homes.modifyPassword', 'HomeController@modifyPassword')->name('homes.modifyPassword'); // 会员退出
        
        Route::post('homes.isAllowRegisterAjax', 'HomeController@isAllowRegisterAjax')->name(
            'homes.isAllowRegisterAjax'); // 是否允许注册
        Route::post('homes.register', 'HomeController@register')->name('homes.register'); // 注册处理
        Route::get('homes.captcha', 'HomeController@captcha')->name('homes.captcha'); // 验证码
        Route::get('homes.forget-password', 'HomeController@forgetPassword')->name('homes.forget-password'); // 忘记密码
                                                                                                             
        // 导航栏
        Route::get('homes.live-entertainment', 'HomeController@liveEntertainment')->name('homes.live-entertainment'); // 真人娱乐
        Route::get('homes.slot-machine', 'GameController@slotMachine')->name('homes.slot-machine'); // 老虎机
        Route::get('homes.ag-fish', 'HomeController@agFish')->name('homes.ag-fish'); // AG捕鱼
        Route::get('homes.sports-games', 'HomeController@sportsGames')->name('homes.sports-games'); // 体育投注
        Route::get('homes.lottery-betting', 'HomeController@lotteryBetting')->name('homes.lottery-betting'); // 彩票投注
        Route::get('homes.mobile', 'HomeController@mobile')->name('homes.mobile'); // 手机版
        Route::get('homes.special-offer', 'HomeController@specialOffer')->name('homes.special-offer'); // 优惠活动
        Route::get('homes.mobileSpecialOffer/{id}', 'HomeController@mobileSpecialOffer')->name(
            'homes.mobileSpecialOffer'); // 优惠活动
                                         
        // 底部
        Route::get('homes.about-us', 'HomeController@aboutUs')->name('homes.about-us'); // 关于我们
        Route::get('homes.contact-us', 'HomeController@contactUs')->name('homes.contact-us'); // 联系我们
        Route::get('homes.vip-system', 'HomeController@vipSystem')->name('homes.vip-system'); // VIP制度
        Route::get('homes.FAQ', 'HomeController@FAQ')->name('homes.FAQ'); // 常见问题
        Route::get('homes.privacy-protection', 'HomeController@privacyProtection')->name('homes.privacy-protection'); // 隐私保护
        Route::get('homes.gambling-responsibility', 'HomeController@gamblingResponsibility')->name(
            'homes.gambling-responsibility'); // 博彩责任
        Route::get('homes.terms-of-service', 'HomeController@termsOfService')->name('homes.terms-of-service'); // 服务条款
        Route::get('homes.partners', 'HomeController@partners')->name('homes.partners'); // 合作伙伴
        Route::get('homes.license-display', 'HomeController@licenseDisplay')->name('homes.license-display'); // 拍照展示
                                                                                                             
        // 代理前台路由
        Route::get('agents.index', 'AgentController@index')->name('agents.index'); // 首页
        Route::post('agents.login', 'AgentController@login')->name('agents.login'); // 登录
        Route::get('agents.registerPage', 'AgentController@registerPage')->name('agents.registerPage'); // 注册页面
        Route::post('agents.register', 'AgentController@register')->name('agents.register'); // 注册处理
        Route::get('agents.captcha', 'AgentController@captcha')->name('agents.captcha'); // 验证码
        Route::get('agents.pattern', 'AgentController@pattern')->name('agents.pattern'); // 合营模式
        Route::get('agents.policy', 'AgentController@policy')->name('agents.policy'); // 佣金政策
        Route::get('agents.protocol', 'AgentController@protocol')->name('agents.protocol'); // 合营协议
        Route::get('agents.connectUs', 'AgentController@connectUs')->name('agents.connectUs'); // 联系我们
        Route::post('agents.dataAgentLevel', 'AgentController@dataAgentLevel')->name('agents.dataAgentLevel'); // 代理管理(代理类型二级联动)
        Route::get('agents.loginOut', 'AgentController@logout')->name('agents.loginOut'); // 退出登录
        Route::get('homes.bindEmail', 'HomeController@bindEmail')->name('homes.bindEmail'); // 绑定邮箱账号
        Route::group([
            'middleware' => [
                'auth:member'
            ]
        ],
            function () {
                // 会员中心
                Route::get('players.account-security', 'PlayerCenterController@accountSecurity')->name(
                    'players.account-security'); // 会员中心
                Route::post('userperfectinformation.resetPassword', 'UserPerfectInformationController@resetPassword')->name(
                    'userperfectinformation.resetPassword'); // 修改登录密码
                Route::post('userperfectinformation.resetWithdrawPassword',
                    'UserPerfectInformationController@resetWithdrawPassword')->name(
                    'userperfectinformation.resetWithdrawPassword'); // 修改取款密码
                Route::post('userperfectinformation.resetPtPassword',
                    'UserPerfectInformationController@resetPtPassword')->name('userperfectinformation.resetPtPassword'); // 修改PT密码
                Route::get('players.logout', 'PlayerCenterController@logout')->name('players.logout'); // 会员退出
                                                                                                       
                // 会员中心
                Route::get('players.account-info', 'PlayerCenterController@accountInfo')->name('players.account-info'); // 移动会员补全资料
                Route::get('players.bindEmail', 'PlayerCenterController@bindEmail')->name('players.bindEmail'); // 绑定邮箱账号
                
                Route::post('players.perfectUserInformation', 'PlayerCenterController@perfectUserInformation')->name(
                    'players.perfectUserInformation'); // 完善个人信息
                Route::post('userperfectinformation.resetPassword', 'UserPerfectInformationController@resetPassword')->name(
                    'userperfectinformation.resetPassword'); // 修改登录密码
                Route::post('userperfectinformation.resetWithdrawPassword',
                    'UserPerfectInformationController@resetWithdrawPassword')->name(
                    'userperfectinformation.resetWithdrawPassword'); // 修改取款密码
                Route::post('userperfectinformation.resetPtPassword',
                    'UserPerfectInformationController@resetPtPassword')->name('userperfectinformation.resetPtPassword'); // 修改PT密码
                Route::post('userperfectinformation.resetPhone', 'UserPerfectInformationController@resetPhone')->name(
                    'userperfectinformation.resetPhone'); // 修改手机号
                                                          
                // 财务中心
                Route::get('players.purse-security', 'PlayerCenterController@purseSecurity')->name(
                    'players.purse-security'); // 手机端钱包中心
                Route::get('players.pay-type/{payChannelTypeId}/{carrierPayChannelId}',
                    'PlayerCenterController@depositTypePage')->name('players.pay-type'); // 不同存款界面
                Route::get('players.financeCenter', 'PlayerCenterController@financeCenter')->name(
                    'players.financeCenter'); // 财务中心
                Route::get('players.selectTab', 'PlayerCenterController@selectTab')->name('players.selectTab'); // 财务中心
                Route::get('players.deposit', 'PlayerDepositPayLogController@deposit')->name('players.deposit'); // 存款页面
                Route::get('players.depositSecond', 'PlayerDepositPayLogController@depositSecond')->name(
                    'players.depositSecond'); // 存款页面第二步
                
                Route::get('players.withdraw-money', 'PlayerCenterController@withdrawMoney')->name(
                    'players.withdraw-money'); // 快速取款
                Route::get('players.balance', 'PlayerCenterController@balance')->name('players.balance'); // 我的余额
                Route::get('players.bankcardManager', 'PlayerCenterController@bankcardManager')->name(
                    'players.bankcardManager'); // 银行卡管理
                Route::get('players.addBankcard', 'PlayerCenterController@addBankcard')->name('players.addBankcard'); // 添加银行卡
                
                Route::post('playerwithdraw.addBankCard', 'PlayerWithdrawController@addBankCard')->name(
                    'playerwithdraw.addBankCard'); // 新增银行卡
                Route::post('playerwithdraw.deleteBankCard', 'PlayerWithdrawController@deleteBankCard')->name(
                    'playerwithdraw.deleteBankCard'); // 删除银行卡
                Route::post('playerwithdraw.withdrawQuotaCheck', 'PlayerWithdrawController@withdrawQuotaCheck')->name(
                    'playerwithdraw.withdrawQuotaCheck'); // 玩家取款限额检查
                Route::post('playerwithdraw.withdrawApply', 'PlayerWithdrawController@withdrawApply')->name(
                    'playerwithdraw.withdrawApply'); // 玩家取款限额检查
                Route::post('playerwithdraw.withdrawApplyone', 'PlayerWithdrawController@withdrawApplyone')->name(
                    'playerwithdraw.withdrawApplyone'); // 玩家取款流程
                Route::get('playerwithdraw.bankcard', 'PlayerWithdrawController@bankcard')->name(
                    'playerwithdraw.bankcard'); // 手机端银行卡页面
                Route::get('playerwithdraw.addBankcardPage', 'PlayerWithdrawController@addBankcardPage')->name(
                    'playerwithdraw.addBankcardPage'); // 手机端添加银行卡页面
                
                Route::get('players.account-transfer', 'PlayerTransferController@index')->name(
                    'players.account-transfer'); // 转账中心页面
                Route::get('players.apply-for-discount', 'PlayerCenterController@applyForDiscount')->name(
                    'players.apply-for-discount'); // 申请优惠
                Route::post('players.applyParticipate', 'PlayerCenterController@applyParticipate')->name(
                    'players.applyParticipate'); // 申请参与优惠
                
                Route::get('players.rebateFinancialFlow', 'PlayerRebateFinancialFlowController@rebateFinancialFlow')->name(
                    'players.rebateFinancialFlow'); // 实时洗码
                Route::post('players.settleMoney', 'PlayerRebateFinancialFlowController@settleMoney')->name(
                    'players.settleMoney'); // 结算
                                            
                // 财务报表
                Route::get('players.financeStatistics', 'PlayerCenterController@financeStatistics')->name(
                    'players.financeStatistics'); // 财务报表
                Route::get('players.depositRecords', 'PlayerDepositPayLogController@depositRecords')->name(
                    'players.depositRecords'); // 存款记录
                Route::get('players.withdrawRecords', 'PlayerWithdrawController@withdrawRecords')->name(
                    'players.withdrawRecords'); // 取款记录
                Route::get('players.transferRecords', 'PlayerTransferController@transferRecords')->name(
                    'players.transferRecords'); // 转账记录
                Route::get('players.washCodeRecords', 'PlayerWashCodeController@washCodeRecords')->name(
                    'players.washCodeRecords'); // 洗码记录
                Route::get('players.discountRecords', 'PlayerCenterController@discountRecords')->name(
                    'players.discountRecords'); // 优惠记录
                Route::get('players.bettingRecords', 'PlayerCenterController@bettingRecords')->name(
                    'players.bettingRecords'); // 投注记录
                Route::get('players.bettingDetails', 'PlayerCenterController@bettingDetails')->name(
                    'players.bettingDetails'); // 投注详情
                                               
                // 客户服务
                Route::get('players.sms-subscriptions', 'PlayerCenterController@smsSubscriptions')->name(
                    'players.sms-subscriptions'); // 站内信
                Route::get('players.delSms', 'PlayerCenterController@delSms')->name('players.delSms'); // 删除站内信
                Route::get('players.readSms', 'PlayerCenterController@readSms')->name('players.readSms'); // 读取站内信
                Route::get('players.messageInStation', 'PlayerCenterController@messageInStation')->name(
                    'players.messageInStation'); // 站内短信
                                                 
                // 推荐好友
                Route::get('players.friendRecommends', 'PlayerCenterController@friendRecommends')->name(
                    'players.friendRecommends'); // 推荐好友
                Route::get('players.mobilefriends', 'PlayerCenterController@mobilefriends')->name(
                    'players.mobilefriends'); // 推荐好友
                Route::get('players.selectAccountStatistics', 'PlayerCenterController@selectAccountStatistics')->name(
                    'players.selectAccountStatistics'); // H5筛选
                Route::get('players.myRecommends', 'PlayerCenterController@myRecommends')->name('players.myRecommends'); // 我的推荐
                
                Route::get('players.myReferrals', 'PlayerCenterController@myReferrals')->name('players.myReferrals'); // 我的下线
                Route::get('players.selectmyReferrals', 'PlayerCenterController@selectmyReferrals')->name(
                    'players.selectmyReferrals'); // 我的下线选择时间
                Route::get('players.accountStatistics', 'PlayerCenterController@accountStatistics')->name(
                    'players.accountStatistics'); // 账目统计
                Route::get('players.statisticDetails', 'PlayerCenterController@statisticDetails')->name(
                    'players.statisticDetails'); // 账目统计详情
                                                 
                // 存款
                Route::get('players.DepositTypePage', 'PlayerDepositPayLogController@DepositTypePage')->name(
                    'players.DepositTypePage'); // 不同存款界面
                Route::post('players.depositPayLogCreate', 'PlayerDepositPayLogController@depositPayLogCreate')->name(
                    'players.depositPayLogCreate'); // 存款处理
                Route::get('players.qrcode', 'PlayerDepositPayLogController@qrcode')->name('players.qrcode'); // 跳转微信扫描界面
                Route::get('players.createWeChatQRcode/{id}', 'PlayerDepositPayLogController@createWeChatQRcode')->name(
                    'players.createWeChatQRcode'); // 存款成功跳转微信扫描界面
                Route::get('players.depositRecordsDelete/{id}', 'PlayerDepositPayLogController@depositRecordsDelete')->name(
                    'players.depositRecordsDelete'); // 删除存款记录
                Route::get('players.depositDropBatch', 'PlayerDepositPayLogController@depositDropBatch')->name(
                    'players.depositDropBatch'); // 批量删除存款记录
                                                 
                // 跳转到支付页面
                Route::get('players.Pay/{pay_order_number}', 'PlayerDepositPayLogController@pay')->name('players.pay');
                
                // 转账
                Route::post('players.accountTransfer', 'PlayerTransferController@accountTransfer')->name(
                    'players.accountTransfer'); // 转账处理
                Route::post('players.accountRecycle', 'PlayerTransferController@accountRecycle')->name(
                    'players.accountRecycle'); // 账户一键回收
                Route::get('players.accountRefresh', 'PlayerTransferController@accountRefresh')->name(
                    'players.accountRefresh'); // 刷新显示主游戏平台金额
                Route::get('players.accountTransferOneTouch', 'PlayerTransferController@accountTransferOneTouch')->name(
                    'players.accountTransferOneTouch'); // 一键转入
                Route::get('players.transferRecordsDelete/{id}', 'PlayerTransferController@transferRecordsDelete')->name(
                    'players.transferRecordsDelete'); // 转账记录删除
                Route::get('players.transferDropBatch', 'PlayerTransferController@transferDropBatch')->name(
                    'players.transferDropBatch'); // 转账记录批量删除
                                                  
                // 账户资料
                Route::get('players.accountPassword', 'UserPerfectInformationController@accountPassword')->name(
                    'players.accountPassword'); // 修改登录密码
                Route::get('players.selectChangepwd', 'UserPerfectInformationController@selectChangepwd')->name(
                    'players.selectChangepwd'); // 修改密码选择页面
                Route::get('players.accountQukuan', 'UserPerfectInformationController@accountQukuan')->name(
                    'players.accountQukuan'); // 修改取款密码
                Route::get('players.accountPhone', 'UserPerfectInformationController@accountPhone')->name(
                    'players.accountPhone'); // 手机号
                Route::get('players.accountPtPassword', 'UserPerfectInformationController@accountPtPassword')->name(
                    'players.accountPtPassword'); // 修改pt密码
                Route::get('players.accountBankcard', 'UserPerfectInformationController@accountBankcard')->name(
                    'players.accountBankcard'); // 银行卡管理
                                                
                // PT游戏
                Route::get('players.loginPTGame/{pt_game_code}', 'GameController@loginPTGame')->name(
                    'players.loginPTGame');
                Route::get('players.searchPtGame', 'GameController@searchPtGame')->name('players.searchPtGame');
                // PT转账功能，add by tlt
                Route::get('players.PTDeposit', 'GameController@PTDeposit')->name('players.PTDeposit');
                Route::get('players.PTWithdraw', 'GameController@PTWithdraw')->name('players.PTWithdraw');
                Route::get('players.getPTBalance', 'GameController@getPTBalance')->name('players.getPTBalance');
                // PT登出游戏账号
                Route::get('players.logoutPT', 'GameController@logoutPT')->name('players.logoutPT');
                
                /* BBin游戏 */
                // 进入游戏大厅
                Route::get('players.loginBBinHall/{providercode}', 'GameController@loginBBinHall')->name(
                    'players.loginBBinHall');
                Route::get('players.loginBBinGame', 'GameController@loginBBinGame');
                Route::get('players.bbinCreateMember', 'GameController@playBBinGameByH5');
                Route::get('players.loginPlat', 'GameController@loginPlat');
                
                /* VR 游戏 */
                Route::get('players.loginVRHall', 'GameController@loginVRHall')->name('players.loginVRHall');
                
                // PNG游戏
                Route::get('players.pngRegister', 'GameController@pngRegister');
                Route::get('players.pngPlayerActive', 'GameController@pngPlayerActive');
                Route::get('players.pngGameOpen', 'GameController@pngGameOpen');
                
                // OneWorks loginOneWorkHall
                Route::get('players.loginOneWorkHall', 'GameController@loginOneWorkHall')->name(
                    'players.loginOneWorkHall');
                Route::get('players.oneWorkCreateMember', 'GameController@oneWorkCreateMember');
                Route::get('players.onWorkLogin', 'GameController@onWorkLogin');
                
                // 进入电子游戏
                Route::get('players.joinElectronicGame/{game_id}', 'GameController@joinElectronicGame')->name(
                    'players.joinElectronicGame');
                
                // 进入电游试玩
                Route::get('players.joinDemoElectronicGame/{game_id}', 'GameController@joinDemoElectronicGame')->name(
                    'players.joinDemoElectronicGame');
                
                // 转帐
                Route::get('players.Transfer/{main_game_plat}/{money}/{direction}', 'GameController@Transfer')->name(
                    'players.Transfer');
                
                // MGEUR_API测试 add by tlt
                // Route::get('players.loginGame', 'GameController@index')->name('players.loginGame');
                Route::get('players.authenticate', 'GameController@authenticate');
                Route::get('players.createMember', 'GameController@createMember');
                Route::get('players.updatePassword', 'GameController@updatePassword');
                Route::get('players.getAccount', 'GameController@getAccount');
                Route::get('players.listChildAccounts', 'GameController@listChildAccounts');
                Route::get('players.MGCredit', 'GameController@MGCredit')->name('players.MGCredit');
                Route::get('players.MGDebit', 'GameController@MGDebit')->name('players.MGDebit');
                Route::get('players.verifyTransaction', 'GameController@verifyTransaction');
                Route::get('players.getMGBalance', 'GameController@getMGBalance');
                // 进入启动游戏
                Route::get('players.launchItem/{item_id}/{app_id}', 'GameController@launchItem')->name(
                    'players.launchItem');
                Route::get('players.getGameNoteDetailLink', 'GameController@getGameNoteDetailLink');
                Route::get('players.getGamesAndTransferRecords', 'GameController@getGamesAndTransferRecords');
                Route::get('players.editAccount', 'GameController@editAccount');
                Route::get('players.getGamesRecord', 'GameController@getGamesRecord');
                // MG游戏记录获取与保存
                Route::get('players.synchronizeMGGameFlowToDB', 'GameController@synchronizeMGGameFlowToDB');
                // MG登出游戏账号
                Route::get('players.logoutMG', 'GameController@logoutMG')->name('players.logoutMG');
                
                // Sunset接口测试 add by tlt
                Route::get('players.Oauth', 'GameController@Oauth');
                Route::get('players.gamesLobbyPoints', 'GameController@gamesLobbyPoints');
                Route::get('players.playerAuthorize', 'GameController@playerAuthorize');
                Route::get('players.playerDeauthorize', 'GameController@playerDeauthorize');
                Route::get('players.getSBBalance', 'GameController@getSBBalance');
                Route::get('players.getMultipleBalance', 'GameController@getMultipleBalance');
                Route::get('players.getBalanceList', 'GameController@getBalanceList');
                Route::get('players.SBCredit', 'GameController@SBCredit')->name('players.SBCredit');
                Route::get('players.SBDebit', 'GameController@SBDebit')->name('players.SBDebit');
                Route::get('players.transferHistory', 'GameController@transferHistory');
                Route::get('players.betTransactionHistory', 'GameController@betTransactionHistory');
                Route::get('players.gameHistory', 'GameController@gameHistory');
                Route::get('players.betHistory', 'GameController@betHistory');
                Route::get('players.providersHistory', 'GameController@providersHistory');
                Route::get('players.getGamesList', 'GameController@getGamesList');
                // 进入启动游戏,路由参数，providercode平台代码，code：游戏代码
                Route::get('players.gameLauncher/{providercode}/{code}', 'GameController@gameLauncher')->name(
                    'players.gameLauncher');
                // 保存玩家游戏记录
                Route::get('players.synchronizeSBGameFlowToDB', 'GameController@synchronizeSBGameFlowToDB');
                // Sunset登出游戏账号
                Route::get('players.logoutSB', 'GameController@logoutSB')->name('players.logoutSB');
                
                // 电游游戏列表
                Route::get('players.getAllPTElectronicGames', 'GameController@getAllPTElectronicGames')->name(
                    'players.getAllPTElectronicGames');
                Route::get('players.getAllMGElectronicGames', 'GameController@getAllMGElectronicGames')->name(
                    'players.getAllMGElectronicGames');
                Route::get('players.getAllTGPElectronicGames', 'GameController@getAllTGPElectronicGames')->name(
                    'players.getAllMGElectronicGames');
                Route::post('players.collectGame', 'GameController@collectGame')->name('players.collectGame');
            });
        // -----------------------WQQ END --------------------------//
        // Route::get('orderTest','TestPayGatewayController@playerOrder');
        // -----------------------binn游戏接口--------------------------//
    });