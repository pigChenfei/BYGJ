/*
 Navicat Premium Data Transfer

 Source Server         : ttcbet
 Source Server Type    : MySQL
 Source Server Version : 50636
 Source Host           : 47.52.246.121:3306
 Source Schema         : ttcbet2

 Target Server Type    : MySQL
 Target Server Version : 50636
 File Encoding         : 65001

 Date: 06/02/2018 17:53:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for conf_carrier_agent_level_commission
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_agent_level_commission`;
CREATE TABLE `conf_carrier_agent_level_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned DEFAULT NULL COMMENT '运营商ID',
  `agent_level_id` int(11) DEFAULT NULL COMMENT '代理类型id',
  `level` tinyint(1) unsigned DEFAULT '1' COMMENT '代理级别',
  `commission_ratio` decimal(8,5) DEFAULT '0.00000' COMMENT '阶级佣金比例',
  `commission_max` decimal(11,2) DEFAULT '0.00' COMMENT '佣金最大上线 0无上限',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商下级佣金代理比例设置';

-- ----------------------------
-- Table structure for conf_carrier_commission_agent
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_commission_agent`;
CREATE TABLE `conf_carrier_commission_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) NOT NULL COMMENT '代理类型名称ID',
  `deposit_fee_undertake_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '存款手续费承担比例',
  `deposit_fee_undertake_max` int(11) DEFAULT '0' COMMENT '存款手续费承担上限',
  `deposit_preferential_undertake_ratio` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '代理存款优惠承担比例 0不承担',
  `deposit_preferential_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '代理存款优惠最高承担金额  0表示无上限',
  `rebate_financial_flow_undertake_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '承担返水比例 0表示无上限',
  `rebate_financial_flow_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '返水承担上线 0无上限',
  `bonus_undertake_ratio` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '红利承担比例 0无上限',
  `bonus_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '红利承担上限  0表示无上限',
  `available_member_monthly_bet_amount` int(11) NOT NULL DEFAULT '0' COMMENT '有效会员当月投注额',
  `available_member_count` int(11) NOT NULL DEFAULT '0' COMMENT '代理佣金有效会员总数',
  `max_commission_amount_per_time` int(11) NOT NULL DEFAULT '0' COMMENT '总佣金单次限额',
  `commission_ratio` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '总佣金比例',
  `commission_step_ratio` varchar(500) DEFAULT NULL COMMENT '总佣金阶梯比例， json格式： 格式待确定',
  `sub_commission_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '下级代理佣金提成比例',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理佣金设置';

-- ----------------------------
-- Table structure for conf_carrier_commission_agent_platform_fee
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_commission_agent_platform_fee`;
CREATE TABLE `conf_carrier_commission_agent_platform_fee` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) NOT NULL,
  `carrier_game_plat_id` int(11) DEFAULT '0' COMMENT '运营商开放的游戏平台id',
  `platform_fee_max` int(11) DEFAULT '0' COMMENT '平台费上限',
  `platform_fee_rate` decimal(5,2) DEFAULT '0.00' COMMENT '平台费比例%',
  `agent_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '代理洗码比例',
  `agent_rebate_financial_flow_max_amount` int(11) DEFAULT '0' COMMENT '代理洗码上限',
  `computing_mode` tinyint(1) DEFAULT '0' COMMENT '开启佣金计算模式 1开启',
  `computing_mode_2` tinyint(1) DEFAULT '0' COMMENT '开启洗码计算模式 1 开启',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金代理平台费设置';

-- ----------------------------
-- Table structure for conf_carrier_cost_take_agent
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_cost_take_agent`;
CREATE TABLE `conf_carrier_cost_take_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) NOT NULL COMMENT '代理类型名称ID',
  `deposit_fee_undertake_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '存款手续费承担比例',
  `deposit_fee_undertake_max` int(11) DEFAULT '0' COMMENT '存款手续费承担上限',
  `deposit_preferential_undertake_ratio` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '代理存款优惠承担比例 0不承担',
  `deposit_preferential_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '代理存款优惠最高承担金额  0表示无上限',
  `rebate_financial_flow_undertake_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '承担返水比例 0表示无上限',
  `rebate_financial_flow_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '返水承担上线 0无上限',
  `bonus_undertake_ratio` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '红利承担比例 0无上限',
  `bonus_undertake_max` int(11) NOT NULL DEFAULT '0' COMMENT '红利承担上限  0表示无上限',
  `can_player_join_activity` tinyint(1) DEFAULT '0' COMMENT '会员是否跟随网站优惠活动(是否能够参加优惠活动)',
  `is_player_rebate_financial_adapt_carrier_conf` tinyint(1) DEFAULT '0' COMMENT '会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算)',
  `cost_take_ration` decimal(5,2) DEFAULT '0.00' COMMENT '占成比例',
  `protection_fund` int(11) DEFAULT '0' COMMENT '保障金',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='占成设置';

-- ----------------------------
-- Table structure for conf_carrier_cost_take_agent_platform_fee
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_cost_take_agent_platform_fee`;
CREATE TABLE `conf_carrier_cost_take_agent_platform_fee` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) NOT NULL,
  `carrier_game_plat_id` int(11) DEFAULT '0' COMMENT '运营商开放的游戏平台id',
  `platform_fee_max` int(11) DEFAULT '0' COMMENT '平台费上限',
  `platform_fee_rate` decimal(5,2) DEFAULT '0.00' COMMENT '平台费比例%',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='占成代理平台费设置';

-- ----------------------------
-- Table structure for conf_carrier_deposit
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_deposit`;
CREATE TABLE `conf_carrier_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `is_allow_player_deposit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '玩家是否允许存款',
  `is_allow_agent_deposit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '代理是否允许存款',
  `is_allow_third_part_deposit_auto_arrival` tinyint(1) NOT NULL DEFAULT '0' COMMENT '三方存款是否允许自动到账, 如果是 则不需要客服审核即可到账. 如果有优惠自动给玩家返优惠',
  `unreview_deposit_record_limit` int(11) NOT NULL DEFAULT '0' COMMENT '允许未审核存款条数：设置条数，超出的自动过期消失',
  `third_part_deposit_is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '三方存款是否开启',
  `company_deposit_is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '公司存款是否开启:公司包括转账汇款，扫码支付（公司入款）',
  `is_allow_company_deposit_auto_arrival` tinyint(1) NOT NULL DEFAULT '0' COMMENT '公司存款是否自动到账；是或否（公司存款方式肯定是否，一般都是要审核的）',
  `virtual_card_deposit_is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '点卡存款是否开启',
  `is_allow_virtual_card_deposit_auto_arrival` tinyint(1) NOT NULL DEFAULT '0' COMMENT '点卡存款是否自动到账',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CONF_DEPOSIT_CARRIER_idx` (`carrier_id`),
  CONSTRAINT `CONF_DEPOSIT_CARRIER` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商存款设置';

-- ----------------------------
-- Table structure for conf_carrier_invite_player
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_invite_player`;
CREATE TABLE `conf_carrier_invite_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT NULL,
  `bet_reward_rule` text COMMENT '投注额奖励规则',
  `bet_reward_settle_period` int(11) DEFAULT '7' COMMENT '投注额奖励结算周期  按天还是按周计算  默认 7天(一周)',
  `deposit_reward_rule` text COMMENT '存款额奖励规则',
  `deposit_reward_settle_period` int(11) DEFAULT '7' COMMENT '存款额奖励结算周期  按天还是按周计算  默认 7天(一周)',
  `invalid_player_deposit_amount` decimal(11,2) DEFAULT '0.00' COMMENT '有效会员达到的存款金额条件',
  `invalid_player_bet_amount` decimal(11,2) DEFAULT '0.00' COMMENT '有效会员达到的投注金额条件',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请好友设置';

-- ----------------------------
-- Table structure for conf_carrier_password_recovery_site
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_password_recovery_site`;
CREATE TABLE `conf_carrier_password_recovery_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT NULL COMMENT '运营商ID',
  `is_open_email_send_function` tinyint(1) DEFAULT '1' COMMENT '是否启用邮件发送功能',
  `smtp_server` varchar(50) DEFAULT NULL COMMENT 'smtp服务器',
  `smtp_service_port` int(11) DEFAULT NULL COMMENT 'smtp服务器端口',
  `mail_sender` varchar(50) DEFAULT NULL COMMENT '邮件发送人',
  `smtp_username` varchar(50) DEFAULT NULL COMMENT 'smtp用户名',
  `smtp_password` varchar(50) DEFAULT NULL COMMENT 'smtp密码',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `smtp_encryption` varchar(10) DEFAULT 'tls' COMMENT '加密方式',
  `smtp_driver` varchar(10) DEFAULT 'smtp' COMMENT '邮件引擎',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商密码找回配置表';

-- ----------------------------
-- Table structure for conf_carrier_rebate_financial_flow_agent
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_rebate_financial_flow_agent`;
CREATE TABLE `conf_carrier_rebate_financial_flow_agent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) unsigned NOT NULL COMMENT '代理类型名称id',
  `carrier_game_plat_id` int(11) unsigned NOT NULL COMMENT '运营商开放的游戏平台id',
  `agent_rebate_financial_flow_max_amount` decimal(11,2) unsigned DEFAULT '0.00' COMMENT '代理洗码上限',
  `agent_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '代理洗码比例',
  `player_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '玩家洗码比例',
  `player_rebate_financial_flow_max_amount` decimal(11,2) DEFAULT '0.00' COMMENT '玩家洗码上限',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_level_id` (`agent_level_id`,`carrier_game_plat_id`) USING BTREE,
  UNIQUE KEY `carrier_game_plat_id` (`carrier_game_plat_id`,`agent_level_id`) USING BTREE,
  CONSTRAINT `conf_carrier_rebate_financial_flow_agent_ibfk_1` FOREIGN KEY (`agent_level_id`) REFERENCES `inf_carrier_agent_level` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `conf_carrier_rebate_financial_flow_agent_ibfk_2` FOREIGN KEY (`carrier_game_plat_id`) REFERENCES `map_carrier_game_plats` (`game_plat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第一级洗码代理类型游戏平台设置';

-- ----------------------------
-- Table structure for conf_carrier_rebate_financial_flow_agent_base_info
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_rebate_financial_flow_agent_base_info`;
CREATE TABLE `conf_carrier_rebate_financial_flow_agent_base_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `agent_level_id` int(11) NOT NULL,
  `available_member_monthly_bet_amount` int(11) DEFAULT '0' COMMENT '有效会员当月投注额',
  `available_member_count` int(11) DEFAULT '0' COMMENT '有效会员数',
  `is_player_rebate_financial_adapt_carrier_conf` tinyint(1) DEFAULT '0' COMMENT '会员是否跟随网站洗码优惠(玩家洗码配置是否按照运营商配置计算)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='洗码代理基本设置';

-- ----------------------------
-- Table structure for conf_carrier_rebate_financial_flow_subordinate_agent
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_rebate_financial_flow_subordinate_agent`;
CREATE TABLE `conf_carrier_rebate_financial_flow_subordinate_agent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商ID',
  `agent_id` int(11) NOT NULL COMMENT '代理商ID',
  `carrier_game_plat_id` int(11) NOT NULL COMMENT '运营商开放的游戏平台id',
  `agent_rebate_financial_flow_max_amount` decimal(11,2) unsigned DEFAULT '0.00' COMMENT '代理洗码上限',
  `agent_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '代理洗码比例',
  `player_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '玩家洗码比例',
  `player_rebate_financial_flow_max_amount` decimal(11,2) DEFAULT '0.00' COMMENT '玩家洗码上限',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='下级洗码代理游戏平台洗码设置';

-- ----------------------------
-- Table structure for conf_carrier_register_login
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_register_login`;
CREATE TABLE `conf_carrier_register_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '运营商登陆注册类设置表',
  `carrier_id` int(11) NOT NULL DEFAULT '1' COMMENT '所属运营商',
  `forbidden_login_comment` varchar(55) NOT NULL DEFAULT '密码输入错误超过5次,账号被锁定,有问题请联系客服人员' COMMENT '后台禁止登陆提示原因',
  `carrier_login_failed_count_when_locked` tinyint(1) NOT NULL DEFAULT '5' COMMENT '后台登陆错误导致锁定的次数 0不锁定',
  `is_allow_player_login` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许玩家登陆',
  `is_allow_player_register` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许玩家注册',
  `player_login_failed_count_when_locked` tinyint(1) NOT NULL DEFAULT '5' COMMENT '玩家登陆失败锁定时的错误次数',
  `player_login_failed_locked_time` int(11) NOT NULL DEFAULT '5' COMMENT '用户登录错误锁定时间',
  `player_register_forbidden_user_names` varchar(500) DEFAULT 'admin,root' COMMENT '玩家注册限制用户名 逗号分隔多个用户名',
  `player_forbidden_login_comment` varchar(255) DEFAULT '密码输入错误超过5次,账号被锁定,有问题请联系客服人员' COMMENT '玩家禁止登陆原因',
  `player_forbidden_register_comment` varchar(255) DEFAULT '注册系统升级中,有疑问请联系客服' COMMENT '玩家禁止注册原因',
  `is_check_exist_player_real_user_name` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否检测玩家真实姓名是否同名',
  `is_allow_user_withdraw_with_password` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许玩家取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.',
  `is_allow_agent_login` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许代理登陆',
  `is_allow_agent_register` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许代理注册',
  `agent_login_failed_count_when_locked` tinyint(1) NOT NULL DEFAULT '5' COMMENT '当代理登陆失败锁定时的登陆次数',
  `agent_login_failed_locked_time` int(11) NOT NULL DEFAULT '5' COMMENT '代理登录错误锁定时间',
  `agent_register_forbidden_user_names` varchar(255) NOT NULL DEFAULT 'admin,root' COMMENT '代理注册禁止注册的用户名列表 逗号分隔',
  `agent_forbidden_login_comment` varchar(55) NOT NULL DEFAULT '密码输入错误超过5次,账号被锁定,有问题请联系客服人员' COMMENT '代理禁止登陆原因',
  `agent_forbidden_register_comment` varchar(55) NOT NULL DEFAULT '注册系统升级中,有疑问请联系客服' COMMENT '代理禁止注册原因',
  `is_allow_agent_withdraw_with_password` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许代理取款时输入取款密码,如果允许 则取款时需要输入取款密码并且需要用户设置取款密码.',
  `is_check_exist_agent_real_user_name` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否检测代理真实姓名是否存在',
  `player_birthday_conf_status` int(1) DEFAULT NULL COMMENT '玩家生日配置项状态(0:无状态;1:显示;2:必填;多种情况下进行按位且运算判断',
  `player_realname_conf_status` int(1) DEFAULT NULL COMMENT '玩家真实姓名配置项状态',
  `player_email_conf_status` int(1) DEFAULT '0' COMMENT '玩家邮箱配置项状态',
  `player_phone_conf_status` int(1) DEFAULT NULL COMMENT '玩家手机配置项状态',
  `player_sex_conf_status` int(1) DEFAULT NULL COMMENT '玩家性别配置项状态',
  `player_qq_conf_status` int(1) DEFAULT NULL COMMENT '玩家qq配置项状态',
  `player_wechat_conf_status` int(1) DEFAULT NULL COMMENT '玩家微信配置项状态',
  `agent_type_conf_status` int(1) DEFAULT NULL COMMENT '代理类型配置项状态',
  `agent_realname_conf_status` int(1) DEFAULT NULL COMMENT '代理真实姓名配置项状态',
  `agent_birthday_conf_status` int(1) DEFAULT NULL COMMENT '代理生日配置项状态',
  `agent_email_conf_status` int(1) DEFAULT NULL COMMENT '代理邮箱配置项状态',
  `agent_phone_conf_status` int(1) DEFAULT NULL COMMENT '代理手机配置项状态',
  `agent_qq_conf_status` int(1) DEFAULT NULL COMMENT '代理qq配置项状态',
  `agent_skype_conf_status` int(1) DEFAULT NULL COMMENT '代理skype配置项状态',
  `agent_wechat_conf_status` int(1) DEFAULT NULL COMMENT '代理微信配置项状态',
  `agent_promotion_url_conf_status` int(1) DEFAULT NULL COMMENT '代理推广网址配置项状态',
  `agent_promotion_idea_conf_status` int(1) DEFAULT NULL COMMENT '代理推广想法配置项状态',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `DASH_SETTING_CARRIER_ID_idx` (`carrier_id`),
  CONSTRAINT `CARRIER_SETTING_LOGIN_CARRIER_ID` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商登陆注册类设置表';

-- ----------------------------
-- Table structure for conf_carrier_subordinate_agent_commission
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_subordinate_agent_commission`;
CREATE TABLE `conf_carrier_subordinate_agent_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned DEFAULT NULL COMMENT '运营商ID',
  `agent_id` int(11) unsigned DEFAULT NULL COMMENT '代理ID',
  `commission_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '总佣金比例',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商下级代理佣金设置';

-- ----------------------------
-- Table structure for conf_carrier_third_part_pay
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_third_part_pay`;
CREATE TABLE `conf_carrier_third_part_pay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `def_pay_channel_id` int(11) NOT NULL COMMENT '三方支付平台ID',
  `merchant_number` varchar(50) DEFAULT NULL COMMENT '商户号',
  `merchant_bind_domain` varchar(255) NOT NULL COMMENT '商户绑定域名',
  `public_key` text COMMENT '公钥',
  `private_key` text COMMENT '私钥',
  `vir_card_no_in` varchar(50) DEFAULT NULL COMMENT '国付宝转入账户',
  `merchant_identify_code` varchar(45) DEFAULT NULL COMMENT '商户识别码',
  `good_name` varchar(50) DEFAULT NULL,
  `pay_ids_json` varchar(50) DEFAULT NULL COMMENT '账户支付渠道',
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商三方支付接口配置';

-- ----------------------------
-- Table structure for conf_carrier_web_banners
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_web_banners`;
CREATE TABLE `conf_carrier_web_banners` (
  `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT NULL COMMENT '所属运营商',
  `banner_name` varchar(255) DEFAULT NULL COMMENT 'Banner名称',
  `banner_image_id` int(11) DEFAULT NULL COMMENT '网站图片id',
  `sort` int(11) DEFAULT '1' COMMENT '排序',
  `banner_belong_page` tinyint(1) NOT NULL COMMENT '所属页面 \n1 ''首页'',\n2 ''真人娱乐页'',\n3 ''彩票页面'',\n4 ''电子游戏页'',\n5 ''体育游戏页'',\n6 ''优惠活动页'',\n7 ''帮助页'',\n8 ‘合营代理页''',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`banner_id`),
  KEY `CARRIER_IMAGE_ID_idx` (`banner_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商网站banner配置表';

-- ----------------------------
-- Table structure for conf_carrier_web_site
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_web_site`;
CREATE TABLE `conf_carrier_web_site` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商',
  `site_title` varchar(50) NOT NULL COMMENT '网站标题',
  `site_key_words` varchar(255) DEFAULT NULL COMMENT '网站关键词',
  `site_description` varchar(255) DEFAULT NULL COMMENT '网站描述',
  `site_javascript` text COMMENT '网站js',
  `site_notice` text COMMENT '网站公告',
  `site_footer_comment` text COMMENT '网站底部说明',
  `common_question_file_path` varchar(255) DEFAULT NULL COMMENT '常见问题文件目录',
  `contact_us_file_path` varchar(255) DEFAULT NULL COMMENT '联系我们文件目录',
  `about_us_file_path` varchar(255) DEFAULT NULL COMMENT '关于我们',
  `duty_file_path` varchar(255) DEFAULT NULL COMMENT '责任博彩',
  `privacy_policy_file_path` varchar(255) DEFAULT NULL COMMENT '隐私政策文件目录',
  `rule_clause_file_path` varchar(255) DEFAULT NULL COMMENT '规则条款文件目录',
  `with_draw_comment_file_path` varchar(255) DEFAULT NULL COMMENT '提款说明文件目录',
  `net_bank_deposit_comment` varchar(255) DEFAULT NULL COMMENT '网银存款说明',
  `atm_deposit_comment` varchar(255) DEFAULT NULL COMMENT 'ATM存款说明',
  `third_part_deposit_comment` varchar(255) DEFAULT NULL COMMENT '第三方存款说明',
  `commission_policy_file_path` varchar(255) DEFAULT NULL COMMENT '佣金政策文件目录',
  `jointly_operated_agreement_file_path` varchar(255) DEFAULT NULL COMMENT '合营协议文件目录',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `activity_image_resolution` varchar(45) DEFAULT NULL COMMENT '活动图片分辨率 按照*分隔  例如 1024*768 ',
  `agent_index_file_path` varchar(255) DEFAULT NULL COMMENT '代理首页文件目录',
  `agent_pattern_file_path` varchar(255) DEFAULT NULL COMMENT 'agent_pattern_file_path',
  `mobile_about_file_path` varchar(255) DEFAULT NULL COMMENT '手机端关于我们文件目录',
  `mobile_contact_file_path` varchar(255) DEFAULT NULL COMMENT '手机端联系我们文件目录',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商网站基本配置';

-- ----------------------------
-- Table structure for conf_carrier_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `conf_carrier_withdraw`;
CREATE TABLE `conf_carrier_withdraw` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商ID',
  `is_allow_player_withdraw` tinyint(1) DEFAULT '1' COMMENT '是否允许玩家取款',
  `is_allow_player_withdraw_decimal` tinyint(1) DEFAULT '0' COMMENT '是否允许玩家取款小数:如(0.88)',
  `player_day_withdraw_success_limit_count` tinyint(1) DEFAULT NULL COMMENT '玩家单日取款成功限制次数',
  `player_day_withdraw_max_sum` int(11) DEFAULT NULL COMMENT '玩家单日取款最大金额',
  `player_once_withdraw_max_sum` int(11) DEFAULT NULL COMMENT '玩家单次取款最大金额',
  `player_once_withdraw_min_sum` int(11) DEFAULT NULL COMMENT '玩家单次取款最小金额',
  `is_display_flow_water_check` tinyint(1) DEFAULT '1' COMMENT '是否显示流水提示:开启后在取款页面有流水限制的提示，未完成的提示已完成流水多少。',
  `is_open_risk_management_check` tinyint(1) DEFAULT '1' COMMENT '是否开启风控审核',
  `is_check_flow_water_when_withdraw` tinyint(1) DEFAULT '1' COMMENT '取款是否检测流水，完成的可取款，未完成的提示流水未完成，不能提款',
  `is_allow_agent_withdraw` tinyint(1) DEFAULT '1' COMMENT '是否允许代理取款',
  `is_allow_agent_withdraw_decimal` tinyint(1) DEFAULT '0' COMMENT '是否允许取款小数',
  `agent_day_withdraw_success_limit_count` tinyint(1) DEFAULT NULL COMMENT '代理单日取款成功限制次数',
  `agent_day_withdraw_max_sum` int(11) DEFAULT NULL COMMENT '代理单日取款最大金额',
  `agent_once_withdraw_max_sum` int(11) DEFAULT NULL COMMENT '代理单次取款最大金额',
  `agent_once_withdraw_min_sum` int(11) DEFAULT NULL COMMENT '代理单次取款最小金额',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商取款设置表';

-- ----------------------------
-- Table structure for conf_rebate_financial_flow_agent_game_plat
-- ----------------------------
DROP TABLE IF EXISTS `conf_rebate_financial_flow_agent_game_plat`;
CREATE TABLE `conf_rebate_financial_flow_agent_game_plat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理id',
  `carrier_game_plat_id` int(11) DEFAULT '0' COMMENT '运营商开放的游戏平台id',
  `agent_rebate_financial_flow_max_amount` decimal(11,2) DEFAULT '0.00' COMMENT '代理洗码上限',
  `agent_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '代理洗码比例',
  `player_rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '玩家洗码比例',
  `player_rebate_financial_flow_max_amount` decimal(11,2) DEFAULT '0.00' COMMENT '玩家洗码上限',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='下级洗码代理游戏平台比例设置';

-- ----------------------------
-- Table structure for conf_templates
-- ----------------------------
DROP TABLE IF EXISTS `conf_templates`;
CREATE TABLE `conf_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '模板类型 1=PC前端模板，2=PC移动模板,3=代理模板',
  `value` varchar(255) DEFAULT NULL COMMENT '模板名称',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '模版简称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of conf_templates
-- ----------------------------
BEGIN;
INSERT INTO `conf_templates` VALUES (1, 1, 'template_one', '炫酷黑', '2018-01-20 11:56:34', '2018-02-01 19:16:36');
INSERT INTO `conf_templates` VALUES (2, 2, 'mobile', '默认', '2018-01-20 11:57:03', '2018-02-01 19:16:46');
INSERT INTO `conf_templates` VALUES (3, 3, 'template_agent_one', '炫酷黑', '2018-01-20 11:57:33', '2018-02-01 19:16:54');
INSERT INTO `conf_templates` VALUES (9, 1, 'template_two', '亮花白', '2018-02-01 19:16:26', '2018-02-01 19:16:26');
INSERT INTO `conf_templates` VALUES (10, 3, 'template_agent_two', '亮花白', '2018-02-01 19:17:19', '2018-02-01 19:17:59');
INSERT INTO `conf_templates` VALUES (11, 4, 'Template_Agent_Admin_One', '炫酷黑', '2018-02-01 20:13:26', '2018-02-01 20:13:26');
COMMIT;

-- ----------------------------
-- Table structure for def_bank_types
-- ----------------------------
DROP TABLE IF EXISTS `def_bank_types`;
CREATE TABLE `def_bank_types` (
  `type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(150) NOT NULL COMMENT '银行卡名称 如 中国农业银行,微信',
  `bank_type` tinyint(1) NOT NULL DEFAULT '1',
  `bank_background_url` varchar(255) DEFAULT NULL COMMENT '银行背景图片路径',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `wap_icon` varchar(50) DEFAULT NULL COMMENT '手机端图标class类',
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_id_UNIQUE` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='银行卡类型';

-- ----------------------------
-- Records of def_bank_types
-- ----------------------------
BEGIN;
INSERT INTO `def_bank_types` VALUES (1, '工商银行', 1, 'app/img/bank_background/1.png', NULL, NULL, 'gongshang');
INSERT INTO `def_bank_types` VALUES (2, '建设银行', 1, 'app/img/bank_background/2.png', NULL, NULL, 'jianshe');
INSERT INTO `def_bank_types` VALUES (3, '招商银行', 1, 'app/img/bank_background/3.png', NULL, NULL, 'zhaoshang');
INSERT INTO `def_bank_types` VALUES (4, '农业银行', 1, 'app/img/bank_background/4.png', NULL, NULL, 'nongye');
INSERT INTO `def_bank_types` VALUES (5, '中国银行', 1, 'app/img/bank_background/5.png', NULL, NULL, 'zhongguo');
INSERT INTO `def_bank_types` VALUES (6, '中信银行', 1, 'app/img/bank_background/6.png', NULL, NULL, 'zhongxin');
INSERT INTO `def_bank_types` VALUES (7, '浦发银行', 1, 'app/img/bank_background/7.png', NULL, NULL, 'pufa');
INSERT INTO `def_bank_types` VALUES (8, '光大银行', 1, 'app/img/bank_background/8.png', NULL, NULL, 'guangda');
INSERT INTO `def_bank_types` VALUES (9, '交通银行', 1, 'app/img/bank_background/9.png', NULL, NULL, 'jiaotong');
INSERT INTO `def_bank_types` VALUES (10, '邮政储蓄', 1, 'app/img/bank_background/10.png', NULL, NULL, 'youzheng');
INSERT INTO `def_bank_types` VALUES (11, '民生银行', 1, 'app/img/bank_background/11.png', NULL, NULL, 'minsheng');
INSERT INTO `def_bank_types` VALUES (12, '华夏银行', 1, 'app/img/bank_background/12.png', NULL, NULL, 'huaxia');
INSERT INTO `def_bank_types` VALUES (13, '平安银行', 1, 'app/img/bank_background/13.png', NULL, NULL, 'pingan');
INSERT INTO `def_bank_types` VALUES (14, '广发银行', 1, 'app/img/bank_background/14.png', NULL, NULL, 'guangfa');
INSERT INTO `def_bank_types` VALUES (15, '北京银行', 1, 'app/img/bank_background/15.png', NULL, NULL, 'beijing');
INSERT INTO `def_bank_types` VALUES (16, '兴业银行', 1, 'app/img/bank_background/16.png', NULL, NULL, 'xingye');
INSERT INTO `def_bank_types` VALUES (17, '江苏银行', 1, 'app/img/bank_background/17.png', NULL, NULL, 'jiangsu');
COMMIT;

-- ----------------------------
-- Table structure for def_game_plats
-- ----------------------------
DROP TABLE IF EXISTS `def_game_plats`;
CREATE TABLE `def_game_plats` (
  `game_plat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_game_plat_id` int(11) unsigned DEFAULT NULL COMMENT '所属主游戏平台id',
  `english_game_plat_name` varchar(50) NOT NULL DEFAULT '',
  `game_plat_name` varchar(50) NOT NULL COMMENT '游戏平台名称',
  `page_site` varchar(255) NOT NULL DEFAULT '0' COMMENT 'BBin大厅代码',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1 打开  0关闭',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sort` tinyint(20) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`game_plat_id`),
  KEY `main_game_plat_id` (`main_game_plat_id`) USING BTREE,
  CONSTRAINT `def_game_plats_ibfk_1` FOREIGN KEY (`main_game_plat_id`) REFERENCES `def_main_game_plats` (`main_game_plat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='游戏平台表';

-- ----------------------------
-- Records of def_game_plats
-- ----------------------------
BEGIN;
INSERT INTO `def_game_plats` VALUES (1, 4, '', 'PT真人', 'bal', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (2, 1, '', 'BBIN真人', 'live', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (3, 5, '', 'SUNBET真人', '', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (4, 2, '', 'MG真人', '', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (6, 10, '', 'GD真人', '', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (7, 1, '', 'BBIN体育', 'ball', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (8, 8, '', '沙巴体育', '0', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (9, 1, '', 'BBIN电子游戏', '0', 1, NULL, NULL, 8);
INSERT INTO `def_game_plats` VALUES (10, 11, '', 'TGP电子游戏', '0', 1, NULL, NULL, 7);
INSERT INTO `def_game_plats` VALUES (11, 2, '', 'MG电子游戏', '0', 1, NULL, NULL, 9);
INSERT INTO `def_game_plats` VALUES (12, 4, '', 'PT电子游戏', '0', 1, NULL, NULL, 10);
INSERT INTO `def_game_plats` VALUES (13, 1, '', 'BBIN彩票', '0', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (14, 1, '', 'BBIN小费', '0', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (15, 9, '', 'VR彩票', '0', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (16, 12, '', 'TTG电子游戏', '0', 1, NULL, NULL, 0);
INSERT INTO `def_game_plats` VALUES (17, 14, '', 'PNG电子游戏', '0', 1, NULL, NULL, 0);
COMMIT;

-- ----------------------------
-- Table structure for def_games
-- ----------------------------
DROP TABLE IF EXISTS `def_games`;
CREATE TABLE `def_games` (
  `game_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_game_plat_id` int(11) NOT NULL,
  `game_plat_id` int(11) unsigned NOT NULL COMMENT '所属游戏平台id',
  `english_game_name` varchar(255) NOT NULL DEFAULT '',
  `game_name` varchar(255) NOT NULL COMMENT '游戏名称',
  `game_type` varchar(255) DEFAULT NULL,
  `game_kind` varchar(255) DEFAULT NULL,
  `sub_game_kind` varchar(255) DEFAULT NULL,
  `game_code` varchar(45) DEFAULT NULL COMMENT '游戏代码',
  `game_mcategory` varchar(255) NOT NULL DEFAULT '' COMMENT 'BB游戏退水分类1=老虎机,2=捕鱼,3=桌面游戏,4=纸牌,5=刮刮乐,6=街机游戏,7=大型机台,8=特色游戏，9=其它',
  `game_lines` int(11) DEFAULT NULL COMMENT '游戏线路，1=1-4线，2=5-9线，3=15-25线，4=30-50线，5=51-146线，6=243线，7=720线，8=1024线',
  `game_icon_path` varchar(255) DEFAULT NULL COMMENT '游戏图标路径',
  `return_award_rate` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '返奖率',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1正常  0关闭',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `game_popularity` int(10) DEFAULT '100' COMMENT '人气',
  `is_recommend` tinyint(1) DEFAULT '0' COMMENT '是否推荐 默认0 不推荐',
  `is_demo` tinyint(1) DEFAULT '0' COMMENT '支持试玩 默认0 不支持',
  `gold_pool` decimal(15,2) DEFAULT '0.00' COMMENT '奖金池 元',
  `flashcode` tinyint(1) DEFAULT '0' COMMENT 'flashcode 0不支持 1支持',
  `is_wap` tinyint(1) DEFAULT '0' COMMENT '支持手机端 0不支持 1支持',
  PRIMARY KEY (`game_id`),
  KEY `game_plat_id` (`game_plat_id`) USING BTREE,
  KEY `game_code` (`game_code`) USING BTREE,
  CONSTRAINT `def_games_ibfk_1` FOREIGN KEY (`game_plat_id`) REFERENCES `def_game_plats` (`game_plat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=886 DEFAULT CHARSET=utf8 COMMENT='游戏列表';

-- ----------------------------
-- Records of def_games
-- ----------------------------
BEGIN;
INSERT INTO `def_games` VALUES (1, 1, 9, '', '惑星战记', '5005', '5', '1', '5005', '1', 1, 'app/img/admin/game/71f2606eb8dcd59d23523b6026d338cd.jpeg', 0.00, 1, NULL, '2018-02-02 14:27:10', 149, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (2, 1, 9, '', 'Staronic', '5006', '5', '1', '5006', '1', 1, 'app/img/admin/game/b22ab58934028f32e6801b02d57df1d7.jpeg', 0.00, 1, NULL, '2018-02-02 00:09:18', 121, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (3, 1, 9, '', '激爆水果盘', '5007', '5', '1', '5007', '1', 1, 'app/img/admin/game/a27c4b1686e6b297214e4fc0f6886174.jpeg', 0.00, 1, NULL, '2018-02-05 16:05:26', 108, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (4, 1, 9, '', '猴子爬树', '5008', '5', '1', '5008', '1', 1, 'app/img/admin/game/af23b2c3bdadd883094954ca0875f4ff.jpeg', 0.00, 1, NULL, '2018-02-06 09:43:28', 106, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (5, 1, 9, '', '金刚爬树', '5009', '5', '1', '5009', '1', 1, 'app/img/admin/game/f909879b404884f07280c7a290845d6f.jpeg', 0.00, 1, NULL, '2018-02-06 09:53:35', 103, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (6, 1, 9, '', '外星战记', '5010', '5', '1', '5010', '1', 1, 'app/img/admin/game/6a6ec81d17e797425cfaa3522fcfc55b.jpeg', 0.00, 1, NULL, '2018-02-02 14:56:49', 117, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (7, 1, 9, '', '外星争霸', '5012', '5', '1', '5012', '1', 1, 'app/img/admin/game/9680eea9577a47612663791f4b5eb4cd.jpeg', 0.00, 1, NULL, '2018-01-27 00:21:31', 104, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (8, 1, 9, '', '传统', '5013', '5', '1', '5013', '1', 1, 'app/img/admin/game/dedf2fc7ffcc0a54a9cc3f3a4d5181fb.jpeg', 0.00, 1, NULL, '2018-02-01 12:55:31', 107, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (9, 1, 9, '', '丛林', '5014', '5', '1', '5014', '1', 1, 'app/img/admin/game/76d8dd149690fdaf43a403dc9647f2cc.jpeg', 0.00, 1, NULL, '2017-12-29 10:02:06', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (10, 1, 9, '', 'FIFA2010', '5015', '5', '1', '5015', '1', 1, 'app/img/admin/game/e5633aa625c4aeed5de5b2967b76c8c8.jpeg', 0.00, 1, NULL, '2018-01-09 16:41:41', 103, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (11, 1, 9, '', '史前丛林冒险', '5016', '5', '1', '5016', '1', 1, 'app/img/admin/game/0d75be6962504d61e98434710b5aa98c.jpeg', 0.00, 1, NULL, '2018-01-08 13:26:48', 101, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (12, 1, 9, '', '星际大战', '5017', '5', '1', '5017', '1', 1, 'app/img/admin/game/0321c4f9280c643e871d550211ebae6b.jpeg', 0.00, 1, NULL, '2018-01-08 13:27:04', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (13, 1, 9, '', '2012伦敦奥运', '5026', '5', '1', '5026', '1', 1, 'app/img/admin/game/969b8defebd5a1289747ce93cdf0c118.jpeg', 0.00, 1, NULL, '2018-01-08 13:27:23', 101, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (14, 1, 9, '', '功夫龙', '5027', '5', '1', '5027', '1', NULL, NULL, 0.00, 0, NULL, '2017-12-27 20:16:47', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (15, 1, 9, '', '中秋月光派对', '5028', '5', '1', '5028', '1', 1, 'app/img/admin/game/fa7224e27dfec2550f639a61e4bf8116.jpeg', 0.00, 1, NULL, '2018-01-08 13:27:41', 101, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (16, 1, 9, '', '圣诞派对', '5029', '5', '1', '5029', '1', 1, 'app/img/admin/game/3cdecbe9e4b5c98b46ef156891025787.jpeg', 0.00, 1, NULL, '2018-01-08 13:27:57', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (17, 1, 9, '', '幸运财神', '5030', '5', '1', '5030', '1', 1, 'app/img/admin/game/44792e419d5bf19d38cfc7eb6dd08038.jpeg', 0.00, 1, NULL, '2018-02-06 09:55:01', 109, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (18, 1, 9, '', '王牌5PK', '5034', '5', '1', '5034', '7', 1, 'app/img/admin/game/258f9e488653d12219c97d443037fa05.jpeg', 0.00, 1, NULL, '2018-01-08 13:28:15', 101, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (19, 1, 9, '', '加勒比扑克', '5035', '5', '1', '5035', '3', 1, 'app/img/admin/game/68fdb84e54165dcca60b584fda289e06.jpeg', 0.00, 1, NULL, '2018-01-08 13:28:34', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (20, 1, 9, '', '鱼虾蟹', '5039', '5', '1', '5039', '3', 1, 'app/img/admin/game/76cd6e01da4e078d90c15fb60ff2172a.jpeg', 0.00, 1, NULL, '2018-02-01 19:50:51', 105, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (21, 1, 9, '', '百搭二王', '5040', '5', '1', '5040', '7', 1, 'app/img/admin/game/0db80d916d2681525a62bfeb280151d5.jpeg', 0.00, 1, NULL, '2018-01-08 13:28:54', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (22, 1, 9, '', '7PK', '5041', '5', '1', '5041', '7', 1, 'app/img/admin/game/74aa3c083bb7850abde5843dabdd991e.jpeg', 0.00, 1, NULL, '2018-01-08 13:29:17', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (23, 1, 9, '', '钻石水果盘', '5043', '5', '1', '5043', '1', 1, 'app/img/admin/game/b5caaceef1a7910aeb4d9103750e6405.jpeg', 0.00, 1, NULL, '2018-01-08 17:06:52', 103, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (24, 1, 9, '', '明星97II', '5044', '5', '1', '5044', '1', 1, 'app/img/admin/game/6d7da9abbe4e16c853dcef5c6f451116.jpeg', 0.00, 1, NULL, '2017-12-29 10:31:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (25, 1, 9, '', '特务危机', '5048', '5', '1', '5048', '1', 1, 'app/img/admin/game/1baa0fb034fcb85bf3bf728eb4607400.jpeg', 0.00, 1, NULL, '2018-01-08 13:29:37', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (26, 1, 9, '', '玉蒲团', '5049', '5', '1', '5049', '1', 1, 'app/img/admin/game/cc14740c2f13fb1337566d323aed1595.jpeg', 0.00, 1, NULL, '2018-01-08 13:29:56', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (27, 1, 9, '', '爆骰', '5054', '5', '1', '5054', '3', NULL, NULL, 0.00, 0, NULL, '2017-12-27 20:06:28', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (28, 1, 9, '', '明星97', '5057', '5', '1', '5057', '1', 1, 'app/img/admin/game/48ca49cbae37939982fc3835a56f8f47.jpeg', 0.00, 1, NULL, '2017-12-29 10:31:13', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (29, 1, 9, '', '疯狂水果盘', '5058', '5', '1', '5058', '1', 1, 'app/img/admin/game/17df428ad5318c7da755bdba814145aa.jpeg', 0.00, 1, NULL, '2018-01-09 15:57:09', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (30, 1, 9, '', '动物奇观五', '5060', '5', '1', '5060', '1', 1, 'app/img/admin/game/9dc48c6baff0336a36a0cfbea6076697.jpeg', 0.00, 1, NULL, '2018-01-08 13:30:18', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (31, 1, 9, '', '超级7', '5061', '5', '1', '5061', '1', 1, 'app/img/admin/game/4134ab1d84ae1572896ef95e439b3367.jpeg', 0.00, 1, NULL, '2017-12-29 10:01:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (32, 1, 9, '', '龙在囧途', '5062', '5', '1', '5062', '1', 1, 'app/img/admin/game/8849233af38a6cee575a2a780b8af735.jpeg', 0.00, 1, NULL, '2018-02-01 19:54:16', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (33, 1, 9, '', '水果拉霸', '5063', '5', '1', '5063', '1', 1, 'app/img/admin/game/5df94a653c0a6743eb0de443725cea4c.jpeg', 0.00, 1, NULL, '2018-01-08 13:05:49', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (34, 1, 9, '', '扑克拉霸', '5064', '5', '1', '5064', '1', 1, 'app/img/admin/game/76ca337f2725e1131564248dc7dddca6.jpeg', 0.00, 1, NULL, '2018-02-06 09:55:32', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (35, 1, 9, '', '筒子拉霸', '5065', '5', '1', '5065', '1', 1, 'app/img/admin/game/c68de9394c8b4c4ea2fa8a6b83944b2b.jpeg', 0.00, 1, NULL, '2018-01-08 14:45:01', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (36, 1, 9, '', '足球拉霸', '5066', '5', '1', '5066', '1', 1, 'app/img/admin/game/75f16615f3838ee87d9031723dc27036.jpeg', 0.00, 1, NULL, '2017-12-29 10:45:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (37, 1, 9, '', '大话西游', '5067', '5', '1', '5067', '1', NULL, NULL, 0.00, 0, NULL, '2017-12-27 20:07:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (38, 1, 9, '', '酷搜马戏团', '5068', '5', '1', '5068', '1', NULL, NULL, 0.00, 0, NULL, '2017-12-27 20:08:33', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (39, 1, 9, '', '水果擂台', '5069', '5', '1', '5069', '1', NULL, NULL, 0.00, 0, NULL, '2017-12-27 20:09:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (40, 1, 9, '', '黄金大转盘', '5070', '5', '1', '5070', '7', 1, 'app/img/admin/game/0ed578fac9df2458168b90e044962f7c.jpeg', 0.00, 1, NULL, '2018-01-08 13:30:51', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (41, 1, 9, '', '百家乐大转盘', '5073', '5', '1', '5073', '7', 1, 'app/img/admin/game/68f02dea3e4fc7729a08b4b550f7a36a.jpeg', 0.00, 1, NULL, '2018-01-08 13:31:27', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (42, 1, 9, '', '数字大转盘', '5076', '5', '1', '5076', '7', 1, 'app/img/admin/game/75eb62b1a52aa0c42fe9f6b3166292d4.jpeg', 0.00, 1, NULL, '2017-12-29 10:38:35', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (43, 1, 9, '', '水果大转盘', '5077', '5', '1', '5077', '7', 1, 'app/img/admin/game/e4955758b45edc61ab4ec3d08ab1eac8.jpeg', 0.00, 1, NULL, '2017-12-29 10:38:55', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (44, 1, 9, '', '象棋大转盘', '5078', '5', '1', '5078', '7', 1, 'app/img/admin/game/126a95a806e2ff686002bab21378fa9c.jpeg', 0.00, 1, NULL, '2018-01-08 13:31:47', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (45, 1, 9, '', '3D数字大转盘', '5079', '5', '1', '5079', '7', 1, 'app/img/admin/game/7dc6a7794d75eb8754110595ca161c3d.jpeg', 0.00, 1, NULL, '2017-12-29 09:57:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (46, 1, 9, '', '乐透转盘', '5080', '5', '1', '5080', '7', 1, 'app/img/admin/game/d249ff5983afe1756894339a81ecab1f.jpeg', 0.00, 1, NULL, '2018-01-08 13:32:08', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (47, 1, 9, '', '钻石列车', '5083', '5', '1', '5083', '7', 1, 'app/img/admin/game/b04a0ab09813bc90b6df23c513049238.jpeg', 0.00, 1, NULL, '2017-12-29 10:46:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (48, 1, 9, '', '圣兽传说', '5084', '5', '1', '5084', '7', 1, 'app/img/admin/game/5e9e76794bc9366d02901ecb593cdfd3.jpeg', 0.00, 1, NULL, '2017-12-29 10:37:58', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (49, 1, 9, '', '斗大', '5088', '5', '1', '5088', '3', 1, 'app/img/admin/game/0406275856017f8e19f2790b6a8ba198.jpeg', 0.00, 1, NULL, '2017-12-29 10:03:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (50, 1, 9, '', '红狗', '5089', '5', '1', '5089', '3', 1, 'app/img/admin/game/e1fb48d0d82ee84814baf9cb20c36f1c.jpeg', 0.00, 1, NULL, '2017-12-29 10:07:34', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (51, 1, 9, '', '金鸡报喜', '5090', '5', '1', '5090', '1', 1, 'app/img/admin/game/b4950b320d90030bf90bba6503cafc89.jpeg', 0.00, 0, NULL, '2017-12-29 10:11:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (52, 1, 9, '', '三国拉霸', '5091', '5', '1', '5091', '1', 1, 'app/img/admin/game/bc68b45c7c4152961e2d2ff1e303c4de.jpeg', 0.00, 1, NULL, '2018-01-08 13:32:51', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (53, 1, 9, '', '封神榜', '5092', '5', '1', '5092', '1', 1, 'app/img/admin/game/47ac4931e9d1367bf6401b75ed10fe8d.jpeg', 0.00, 1, NULL, '2018-01-08 13:33:02', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (54, 1, 9, '', '金瓶梅', '5093', '5', '1', '5093', '1', 1, 'app/img/admin/game/f4fae3a80887dae045737df149b5a21f.jpeg', 0.00, 0, NULL, '2017-12-29 10:13:00', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (55, 1, 9, '', '金瓶梅2', '5094', '5', '1', '5094', '1', 1, 'app/img/admin/game/299802e846c4461705e9897de36ff879.jpeg', 0.00, 1, NULL, '2018-02-06 09:56:24', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (56, 1, 9, '', '斗鸡', '5095', '5', '1', '5095', '1', 1, 'app/img/admin/game/c63a20d9f61b5663589030897b642126.jpeg', 0.00, 1, NULL, '2018-02-06 09:57:27', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (57, 1, 9, '', '五行', '5096', '5', '1', '5096', '1', 1, 'app/img/admin/game/6d62bbd74b56d3207592a10b9fc26fc6.jpeg', 0.00, 0, NULL, '2017-12-29 10:41:46', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (58, 1, 9, '', '欧式轮盘', '5105', '5', '1', '5105', '3', 1, 'app/img/admin/game/4a259ebb723f267de91f47b5b22109bf.jpeg', 0.00, 1, NULL, '2017-12-29 10:33:12', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (59, 1, 9, '', '三国', '5106', '5', '1', '5106', '1', 1, 'app/img/admin/game/ae8b61d2a0824a7bcdc4fc02fb041a81.jpeg', 0.00, 1, NULL, '2018-02-06 09:58:57', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (60, 1, 9, '', '美式轮盘', '5107', '5', '1', '5107', '3', 1, 'app/img/admin/game/6263d92b20f5d1217cd06077466b7f44.jpeg', 0.00, 1, NULL, '2017-12-29 10:30:14', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (61, 1, 9, '', '彩金轮盘', '5108', '5', '1', '5108', '3', 1, 'app/img/admin/game/5ae5cb997a58f61e744ef04b1da2618f.jpeg', 0.00, 1, NULL, '2017-12-29 10:00:58', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (62, 1, 9, '', '法式轮盘', '5109', '5', '1', '5109', '3', 1, 'app/img/admin/game/524bf9d934dc0b8fc07c55d0723bda0c.jpeg', 0.00, 1, NULL, '2017-12-29 10:05:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (63, 1, 9, '', '经典21点', '5115', '5', '1', '5115', '3', 1, 'app/img/admin/game/28b7adb0d70ccf4a96c0c65b6e642da3.jpeg', 0.00, 1, NULL, '2018-01-08 13:33:33', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (64, 1, 9, '', '西班牙21点', '5116', '5', '1', '5116', '3', 1, 'app/img/admin/game/4d37cac92cbe0ef400f79c3b3ebae75b.jpeg', 0.00, 1, NULL, '2018-01-08 13:33:47', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (65, 1, 9, '', '维加斯21点', '5117', '5', '1', '5117', '3', 1, 'app/img/admin/game/ec9f00d55c13f79d415419d71f1fb86e.jpeg', 0.00, 1, NULL, '2018-01-08 13:33:57', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (66, 1, 9, '', '奖金21点', '5118', '5', '1', '5118', '3', 1, 'app/img/admin/game/f8fe831717b221452e5e9330cad24f65.jpeg', 0.00, 1, NULL, '2018-01-08 13:34:16', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (67, 1, 9, '', '皇家德州扑克', '5131', '5', '1', '5131', '3', 1, 'app/img/admin/game/3e633a49e7e733409203dd7612742143.jpeg', 0.00, 1, NULL, '2018-01-08 13:34:26', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (68, 1, 9, '', '火焰山', '5201', '5', '1', '5201', '1', 1, 'app/img/admin/game/f8cf73022e7d5b082a8346e09f565f8f.jpeg', 0.00, 1, NULL, '2018-02-06 09:59:47', 102, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (69, 1, 9, '', '月光宝盒', '5202', '5', '1', '5202', '1', 1, 'app/img/admin/game/49c9a246fe2d819706c9ba12785da397.jpeg', 0.00, 1, NULL, '2018-01-08 13:35:00', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (70, 1, 9, '', '爱你一万年', '5203', '5', '1', '5203', '1', 1, 'app/img/admin/game/73724cef388d55473ea51729a67b05ad.jpeg', 0.00, 1, NULL, '2018-01-08 13:35:16', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (71, 1, 9, '', '2014FIFA', '5204', '5', '1', '5204', '1', 1, 'app/img/admin/game/a3df443ebb3ea26b6bd1f5bc205c321d.jpeg', 0.00, 1, NULL, '2017-12-29 09:58:14', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (72, 1, 9, '', '夜市人生', '5402', '5', '1', '5402', '1', 1, 'app/img/admin/game/5d5c7f366c3e0be3c38660f10edb220c.jpeg', 0.00, 1, NULL, '2018-01-08 15:54:40', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (73, 1, 9, '', '沙滩排球', '5404', '5', '1', '5404', '1', 1, 'app/img/admin/game/94fa8714446d203ea4bd00c74129f0eb.jpeg', 0.00, 1, NULL, '2017-12-29 10:36:51', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (74, 1, 9, '', '神舟27', '5406', '5', '1', '5406', '1', 1, 'app/img/admin/game/ed0de085e597dbca3aa762ca7198784d.jpeg', 0.00, 1, NULL, '2018-02-01 19:56:06', 102, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (75, 1, 9, '', '大红帽与小野狼', '5407', '5', '1', '5407', '1', 1, 'app/img/admin/game/66947e931dc9a3c7cf57d6e748045939.jpeg', 0.00, 1, NULL, '2018-02-06 10:00:46', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (76, 1, 9, '', '秘境冒险', '5601', '5', '1', '5601', '1', 1, 'app/img/admin/game/ab448e573905f00954508c27ac51646a.jpeg', 0.00, 1, NULL, '2018-02-05 16:07:28', 102, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (77, 1, 9, '', '连连看', '5701', '5', '1', '5701', '5', 1, 'app/img/admin/game/2a89bb037b1b69c1cd62a58c8af0c04e.jpeg', 0.00, 1, NULL, '2018-01-08 13:36:40', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (78, 1, 9, '', '发达啰', '5703', '5', '1', '5703', '5', 1, 'app/img/admin/game/ce57d6bc67a6c6eb5a3a11529e7bb2d8.jpeg', 0.00, 1, NULL, '2018-01-08 13:36:51', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (79, 1, 9, '', '斗牛', '5704', '5', '1', '5704', '5', 1, 'app/img/admin/game/b6318f13d13ead77446ad748fcc9adb5.jpeg', 0.00, 1, NULL, '2018-01-08 13:37:05', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (80, 1, 9, '', '聚宝盆', '5705', '5', '1', '5705', '5', 1, 'app/img/admin/game/3d958590bb51d1abc2f124b180e277fd.jpeg', 0.00, 1, NULL, '2018-01-08 13:37:16', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (81, 1, 9, '', '浓情巧克力', '5706', '5', '1', '5706', '5', 1, 'app/img/admin/game/11ad855c050ccfc4e666d088e397b94e.jpeg', 0.00, 1, NULL, '2018-01-08 13:37:30', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (82, 1, 9, '', '金钱豹', '5707', '5', '1', '5707', '5', 1, 'app/img/admin/game/fcbc501aa6838663117670030ab28013.jpeg', 0.00, 1, NULL, '2018-01-08 13:37:44', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (83, 1, 9, '', '海豚世界', '5801', '5', '1', '5801', '1', 1, 'app/img/admin/game/cdcb3a53b06ff01518728fa81493a191.jpeg', 0.00, 1, NULL, '2017-12-29 10:07:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (84, 1, 9, '', '阿基里斯', '5802', '5', '1', '5802', '1', 1, 'app/img/admin/game/70a283ab8a6eeee41df910edb568c6fd.jpeg', 0.00, 1, NULL, '2018-01-08 13:37:57', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (85, 1, 9, '', '阿兹特克宝藏', '5803', '5', '1', '5803', '1', 1, 'app/img/admin/game/2d7bc5fa75920031c151929903b98bcc.jpeg', 0.00, 1, NULL, '2017-12-29 09:59:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (86, 1, 9, '', '大明星', '5804', '5', '1', '5804', '1', 1, 'app/img/admin/game/37b8ce0f7e80dee439747c15989a38d3.jpeg', 0.00, 1, NULL, '2018-02-05 16:08:14', 102, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (87, 1, 9, '', '凯萨帝国', '5805', '5', '1', '5805', '1', 1, 'app/img/admin/game/5daba06551bc029ef3814ce6949e2fda.jpeg', 0.00, 1, NULL, '2017-12-29 10:18:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (88, 1, 9, '', '奇幻花园', '5806', '5', '1', '5806', '1', 1, 'app/img/admin/game/06a226d1d715dfe8bf73a3a34261c440.jpeg', 0.00, 1, NULL, '2018-01-08 13:38:30', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (89, 1, 9, '', '浪人武士', '5808', '5', '1', '5808', '1', 1, 'app/img/admin/game/46a5fa3508a20bc57faab59c0fb60101.jpeg', 0.00, 1, NULL, '2018-01-08 13:38:41', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (90, 1, 9, '', '空战英豪', '5809', '5', '1', '5809', '1', 1, 'app/img/admin/game/19c7291e631c613fd0ac89d87a2f9441.jpeg', 0.00, 1, NULL, '2018-01-08 13:38:54', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (91, 1, 9, '', '航海时代', '5810', '5', '1', '5810', '1', 1, 'app/img/admin/game/8420f5e82881d92d85d47b5e7c090b89.jpeg', 0.00, 1, NULL, '2017-12-29 10:07:21', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (92, 1, 9, '', '狂欢夜', '5811', '5', '1', '5811', '1', 1, 'app/img/admin/game/b63150efa19076c38312fbd64c673d8a.jpeg', 0.00, 1, NULL, '2018-01-08 13:39:08', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (93, 1, 9, '', '国际足球', '5821', '5', '1', '5821', '1', 1, 'app/img/admin/game/fc604c1babc985654239ec343b171878.jpeg', 0.00, 1, NULL, '2018-01-08 13:39:21', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (94, 1, 9, '', '发大财', '5823', '5', '1', '5823', '1', 1, 'app/img/admin/game/4c1c8936240dfbb3f60849f53df3952d.jpeg', 0.00, 1, NULL, '2017-12-29 10:05:04', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (95, 1, 9, '', '恶龙传说', '5824', '5', '1', '5824', '1', 1, 'app/img/admin/game/ef5c791509f67bfedfc6b85d04c5aace.jpeg', 0.00, 1, NULL, '2017-12-29 10:04:21', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (96, 1, 9, '', '金莲', '5825', '5', '1', '5825', '1', 1, 'app/img/admin/game/625c4f890661a04af44c8e4864f957b2.jpeg', 0.00, 1, NULL, '2018-01-08 13:39:34', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (97, 1, 9, '', '金矿工', '5826', '5', '1', '5826', '1', 1, 'app/img/admin/game/a3f85f279a37a5fcc9fcae7cc6d0eb21.jpeg', 0.00, 1, NULL, '2018-01-08 13:39:46', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (98, 1, 9, '', '老船长', '5827', '5', '1', '5827', '1', 1, 'app/img/admin/game/168dedcdf3e0fa7cbc270bdda66958eb.jpeg', 0.00, 1, NULL, '2018-01-08 13:40:01', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (99, 1, 9, '', '霸王龙', '5828', '5', '1', '5828', '1', 1, 'app/img/admin/game/3b2ce278a865be9bdfa7bfcc0a804e7e.jpeg', 0.00, 1, NULL, '2017-12-29 09:59:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (100, 1, 9, '', '高速卡车', '5832', '5', '1', '5832', '1', 1, 'app/img/admin/game/c02b2b85a1a689fc9955e658c89dede0.jpeg', 0.00, 1, NULL, '2018-01-08 13:40:22', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (101, 1, 9, '', '沉默武士', '5833', '5', '1', '5833', '1', 1, 'app/img/admin/game/e43e2b74c637c40608d843929473fc31.jpeg', 0.00, 1, NULL, '2018-01-08 13:40:33', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (102, 1, 9, '', '喜福牛年', '5835', '5', '1', '5835', '1', 1, 'app/img/admin/game/190f07c8b11dbed3f7efc1dc9b371583.jpeg', 0.00, 1, NULL, '2017-12-29 10:42:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (103, 1, 9, '', '龙卷风', '5836', '5', '1', '5836', '1', 1, 'app/img/admin/game/786ec939f4331932a8278bfed7b58fac.jpeg', 0.00, 1, NULL, '2018-01-08 13:40:46', 100, 0, 1, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (104, 1, 9, '', '喜福猴年', '5837', '5', '1', '5837', '1', 1, 'app/img/admin/game/a788d76f0559c34d4566e57fcc66d3a0.jpeg', 0.00, 1, NULL, '2017-12-29 10:42:25', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (105, 1, 9, '', '经典高球', '5839', '5', '1', '5839', '1', 1, 'app/img/admin/game/7244247824d52d71766111ce5b94bfd0.jpeg', 0.00, 0, NULL, '2017-12-29 10:16:09', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (106, 1, 9, '', '连环夺宝', '5901', '5', '3', '5901', '8', 1, 'app/img/admin/game/7c80561fe4118f266ae123b394eaf8ad.jpeg', 0.00, 1, NULL, '2017-12-29 10:28:25', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (107, 1, 9, '', '糖果派对', '5902', '5', '2', '5902', '8', 1, 'app/img/admin/game/d452c6ba673d0cacdf867f91e53fa159.jpeg', 0.00, 1, NULL, '2018-01-18 16:49:38', 104, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (108, 1, 9, '', '秦皇秘宝', '5903', '5', '1', '5903', '8', 1, 'app/img/admin/game/1e7276da2c4720c12e2ddbeb3e7b6a09.jpeg', 0.00, 0, NULL, '2017-12-29 10:35:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (109, 1, 9, '', '蒸汽炸弹', '5904', '5', '3', '5904', '8', 1, 'app/img/admin/game/cf8e8a5359fc303dd31506231a8309ba.jpeg', 0.00, 1, NULL, '2018-02-01 19:57:05', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (110, 1, 9, '', '趣味台球', '5907', '5', '1', '5907', '8', 1, 'app/img/admin/game/26883a6bc96de1bff6f3f40df0020432.jpeg', 0.00, 0, NULL, '2017-12-29 10:35:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (111, 1, 9, '', '糖果派对2', '5908', '5', '5', '5908', '8', 1, 'app/img/admin/game/e1a32b85ad2332cf205e716ab655d3b3.jpeg', 0.00, 0, NULL, '2017-12-29 10:39:50', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (112, 1, 9, '', '开心消消乐', '5909', '5', '1', '5909', '8', 1, 'app/img/admin/game/88c07e6758e7ba5a79cb28789a7e701b.jpeg', 0.00, 0, NULL, '2017-12-29 10:17:55', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (113, 1, 9, '', '捕鱼达人', '30599', NULL, '2', '30599', '2', 1, 'app/img/admin/game/c45ab2b87b51d705834f8e25d219070a.jpeg', 0.00, 1, NULL, '2018-02-05 16:08:58', 112, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (114, 1, 9, '', '捕鱼大师', '38001', NULL, '10', '38001', '2', 1, 'app/img/admin/game/efe0fe7d96b1bd551a4e0b02872bfe20.jpeg', 0.00, 1, NULL, '2018-02-02 14:43:55', 108, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (168, 11, 10, '', '水果大爆炸', 'Arcade_Bomb', NULL, NULL, 'Arcade_Bomb', '1', 1, 'app/img/admin/game/fac2c54e194c3877ae04293860fb30b8.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-02-02 14:26:57', 129, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (169, 11, 10, '', '凤凰涅槃', 'Red_Phoenix_Rising', NULL, NULL, 'Red_Phoenix_Rising', '1', 1, 'app/img/admin/game/fb8b1ebc3ec92aff56dc34e4522c1776.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-12 17:20:04', 110, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (170, 11, 10, '', '金殿丽影', 'Temple_Of_Gold', NULL, NULL, 'Temple_Of_Gold', '1', 1, 'app/img/admin/game/5c848f247bbfcc988fab07c6e3f3b6bb.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-17 19:33:45', 103, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (171, 11, 10, '', '幸运五星', 'Five_Star', NULL, NULL, 'Five_Star', '1', 1, 'app/img/admin/game/342f7520993b0c8f6d8b3d6718978d19.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-18 15:39:43', 104, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (172, 11, 10, '', '波斯宝藏', 'Persian_Fortune', NULL, NULL, 'Persian_Fortune', '1', 1, 'app/img/admin/game/c92ecb8a8fdcb87223369e749910cf0b.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:03:59', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (173, 11, 10, '', '精灵童话', 'Winter_Wonders', NULL, NULL, 'Winter_Wonders', '1', 1, 'app/img/admin/game/0789a70cfc9d7de1dd7db130914b1c1b.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-06 18:22:07', 101, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (174, 11, 10, '', '宝石暴走', 'Gems_Gone_Wild', NULL, NULL, 'Gems_Gone_Wild', '', NULL, NULL, 0.00, 0, '2017-12-04 18:33:03', '2017-12-27 19:15:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (175, 11, 10, '', '三国争霸', 'Three_Kingdoms', NULL, NULL, 'Three_Kingdoms', '1', 1, 'app/img/admin/game/2740518b2a9ee940d543636c34f1792a.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:07:08', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (176, 11, 10, '', '珍兽秘境', 'Chinese_Wilds', NULL, NULL, 'Chinese_Wilds', '1', 1, 'app/img/admin/game/e28ea26d5d9065237213db95cffe56f9.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:08:08', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (177, 11, 10, '', '图腾的秘密', 'Totem_Lightning', NULL, NULL, 'Totem_Lightning', '1', 1, 'app/img/admin/game/5e612fb7f770f4309d351d498da66efb.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-02 15:55:08', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (178, 11, 10, '', '铃儿响叮当', 'Jingle_Bells', NULL, NULL, 'Jingle_Bells', '1', 1, 'app/img/admin/game/f6a52944143d3f67045452fc856a4c29.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-02 15:59:58', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (179, 11, 10, '', '甄妃传奇', 'Imperial_Palace', NULL, NULL, 'Imperial_Palace', '1', 1, 'app/img/admin/game/b154d138512b6f7a7de0a335d68b5c4d.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-08 14:40:49', 101, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (180, 11, 10, '', '珠光宝器', 'Jade_Charms', NULL, NULL, 'Jade_Charms', '1', 1, 'app/img/admin/game/cb0d55933bf5d962ddd9481aba160f98.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-02 16:31:38', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (181, 11, 10, '', '幸运大转盘', 'Grand_Wheel', NULL, NULL, 'Grand_Wheel', '1', 1, 'app/img/admin/game/96c14f8946882d40d178240454ab44b1.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:17:37', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (182, 11, 10, '', '富贵金蟾', 'Golden_Toad', NULL, NULL, 'Golden_Toad', '1', 1, 'app/img/admin/game/c8e0cc7f54987f1d2441f5b1a2e19842.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:18:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (183, 11, 10, '', '财富满屋', 'Fortune_House', NULL, NULL, 'Fortune_House', '1', 1, 'app/img/admin/game/5b9bf403d1eb61bd48e5fc8fb71f5b6f.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:20:29', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (184, 11, 10, '', '靴猫剑客', 'Puss_N_Boots', NULL, NULL, 'Puss_N_Boots', '1', 1, 'app/img/admin/game/ba0f6259153469b6aa71be0bee0fb7ea.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:21:35', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (185, 11, 10, '', '甜蜜情人节', 'Lucky_Valentine', NULL, NULL, 'Lucky_Valentine', '1', 1, 'app/img/admin/game/181f2655522b091696c90b34643ca151.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:22:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (186, 11, 10, '', '神灯之谜', 'Golden_Lamps', NULL, NULL, 'Golden_Lamps', '1', 1, 'app/img/admin/game/b1fed269667bcb9e7d6c07d453369edc.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:23:39', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (187, 11, 10, '', '寻找法老', 'Ancient_Script', NULL, NULL, 'Ancient_Script', '1', 1, 'app/img/admin/game/b3cc1314940461935495060324afb8d4.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:24:29', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (188, 11, 10, '', '三剑客', 'Three_Musketeers', NULL, NULL, 'Three_Musketeers', '1', 1, 'app/img/admin/game/903400520daca6d4f43718c5e0faab5b.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:25:49', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (189, 11, 10, '', '招财猫', 'Lucky_Fortune_Cat', NULL, NULL, 'Lucky_Fortune_Cat', '1', 1, 'app/img/admin/game/d2ff7e82ee6d4c0a3d131c53977d8924.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:26:37', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (190, 11, 10, '', '麒麟送宝', 'Golden_Offer', NULL, NULL, 'Golden_Offer', '1', 1, 'app/img/admin/game/e263c1694f5a1a56140049d9280c7160.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-15 13:05:53', 101, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (191, 11, 10, '', '功夫神话', 'Wild_Fight', NULL, NULL, 'Wild_Fight', '1', 1, 'app/img/admin/game/f20d05d80c9c9cb4832e153505b9ba1d.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:29:09', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (192, 11, 10, '', '深海宝藏', 'Ocean_Fortune', NULL, NULL, 'Ocean_Fortune', '1', 1, 'app/img/admin/game/65b05cb7c804022c27fa70b4a6f19128.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:30:10', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (193, 11, 10, '', '斯巴达勇士', 'Wild_Spartans', NULL, NULL, 'Wild_Spartans', '1', 1, 'app/img/admin/game/8a5dcf82e4b108076962da47fe01700e.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:31:08', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (194, 11, 10, '', '蓝钻风暴', 'Blue_Diamond', NULL, NULL, 'Blue_Diamond', '1', 1, 'app/img/admin/game/5e8c42eaba64093c3c5bfde387eb1fe2.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:32:09', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (195, 11, 10, '', '摇滚星', 'Gold_Star', NULL, NULL, 'Gold_Star', '1', 1, 'app/img/admin/game/3e94e293ac111d20b5f6542cb46f6181.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-13 14:06:57', 104, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (196, 11, 10, '', '彩虹的祝福', 'Rainbow_Jackpots', NULL, NULL, 'Rainbow_Jackpots', '1', 1, 'app/img/admin/game/022669d43d70148b453a9738078b6f46.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-01-02 16:30:35', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (197, 11, 10, '', '财神', 'God_Of_Wealth', NULL, NULL, 'God_Of_Wealth', '1', 1, 'app/img/admin/game/27033ae7e8801ffde3e08940ea8e46f3.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:34:56', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (198, 11, 10, '', '兔子的彩蛋', 'Lucky_Easter', NULL, NULL, 'Lucky_Easter', '1', 1, 'app/img/admin/game/ab5b675a5f872bf8f42668800d0e3399.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:36:05', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (199, 11, 10, '', '妖神传说', 'Ten_Elements', NULL, NULL, 'Ten_Elements', '1', 1, 'app/img/admin/game/33b21aa724e9ff3624bb177771510627.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:37:46', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (200, 11, 10, '', '狂野西部诱惑', 'Wild_Wild_Chest', NULL, NULL, 'Wild_Wild_Chest', '1', 1, 'app/img/admin/game/6d792687840d8d7c85378082032264a5.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-02-05 16:10:20', 102, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (201, 11, 10, '', '圣兽传奇', 'Divine_Ways', NULL, NULL, 'Divine_Ways', '1', 1, 'app/img/admin/game/19863dcd8d07f69a181d138c5b993286.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:40:32', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (202, 11, 10, '', '龙之谕', 'Dragons_Luck', NULL, NULL, 'Dragons_Luck', '1', 1, 'app/img/admin/game/029101e9e2c61852bd10d12201b3b61b.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-28 09:14:49', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (203, 11, 10, '', '五福临门', 'Magic_Gate', NULL, NULL, 'Magic_Gate', '1', 1, 'app/img/admin/game/44b9a404a45ce5aa47adbe342998cc93.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:43:41', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (204, 11, 10, '', '幸运万圣节', 'Lucky_Halloween', NULL, NULL, 'Lucky_Halloween', '1', 1, 'app/img/admin/game/94cb066883f5ae6d54fac40253471dba.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:45:03', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (205, 11, 10, '', '恭贺新禧', 'Fortune_Fest', NULL, NULL, 'Fortune_Fest', '1', 1, 'app/img/admin/game/3d56c4164c858ebca0254fef1dbbfaad.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:45:59', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (206, 11, 10, '', '翡翠王', 'Mega_Jade', NULL, NULL, 'Mega_Jade', '1', 1, 'app/img/admin/game/c539d35d52dc1ec69519dab43db99a6b.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:48:16', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (207, 11, 10, '', '幸运魔法师', 'Lucky_Wizard', NULL, NULL, 'Lucky_Wizard', '1', 1, 'app/img/admin/game/7cc0fc10c47c794abc9a203978f3d3c4.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:49:59', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (208, 11, 10, '', '金莲呈祥', 'Golden_Lotus', NULL, NULL, 'Golden_Lotus', '1', 1, 'app/img/admin/game/204011a1310ffa90b6d189205f6f06cb.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-28 09:18:38', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (209, 11, 10, '', '灰姑娘', 'Cinderella', NULL, NULL, 'Cinderella', '1', 1, 'app/img/admin/game/ea82e9331bc7ad11e82a7a13283efe18.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-02-05 16:10:45', 102, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (210, 11, 10, '', '魔箭精灵', 'Elven_Magic', NULL, NULL, 'Elven_Magic', '1', 1, 'app/img/admin/game/c88f291aaaa2740d6953efbcf6f6b7d3.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:51:49', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (211, 11, 10, '', '西游寻宝', 'Epic_Journey', NULL, NULL, 'Epic_Journey', '1', 1, 'app/img/admin/game/3d324f7eec8739adfe8e0f4e1277d82a.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:52:44', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (212, 11, 10, '', '华夏祥瑞', 'Chinese_Treasures', NULL, NULL, 'Chinese_Treasures', '1', 1, 'app/img/admin/game/74e9aa58011748a8efb9bef20ea127b8.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:53:41', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (213, 11, 10, '', '拉神的恩赐', 'Ras_Legend', NULL, NULL, 'Ras_Legend', '1', 1, 'app/img/admin/game/37e57063139c39612ffeecf228184e7a.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:54:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (214, 11, 10, '', '雄狮纳财', 'Lion_Dance', NULL, NULL, 'Lion_Dance', '1', 1, 'app/img/admin/game/64b43fe05649889d89c370ec5b08a374.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:55:38', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (215, 11, 10, '', '金象探宝', 'Elephant_Treasure', NULL, NULL, 'Elephant_Treasure', '1', 1, 'app/img/admin/game/270e61b03407b28cbde45ea23c25c93a.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:56:28', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (216, 11, 10, '', '梦想好声音', 'Stage_888', NULL, NULL, 'Stage_888', '1', 1, 'app/img/admin/game/cb0633c77e174a3b023309d3cdd787b7.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 19:57:37', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (217, 11, 10, '', '百家乐', 'Baccarat', NULL, NULL, 'Baccarat', '1', 1, 'app/img/admin/game/6534134551a251c4964fe0f7a0cb5dca.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2018-02-03 13:17:51', 102, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (218, 11, 10, '', '轮盘赌', 'Roulette', NULL, NULL, 'Roulette', '1', 1, 'app/img/admin/game/c19dbf8eb85aa4218ae1ab811aec5e5c.jpeg', 0.00, 1, '2017-12-04 18:33:03', '2017-12-27 20:00:09', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (219, 2, 11, '', '城堡建筑师II', '1894', NULL, NULL, '1894', '1', 3, 'app/img/admin/game/ad03ec0c6e7712fe43782cc68bbf9b64.png', 0.00, 0, NULL, '2017-12-27 20:02:44', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (220, 2, 11, '', '糖果巡游', '1893', NULL, NULL, '1893', '1', 3, 'app/img/admin/game/fa6c3b6d38f8ec70c44f5e26c4e5aa57.png', 0.00, 1, NULL, '2018-02-02 14:27:19', 107, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (221, 2, 11, '', '杂技群英会', '1892', NULL, NULL, '1892', '1', 2, 'app/img/admin/game/b8c3eb44bc309538751217abfec54bcf.png', 0.00, 1, NULL, '2017-12-27 17:25:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (222, 2, 11, '', '侏罗纪世界', '1891', NULL, NULL, '1891', '1', 6, 'app/img/admin/game/fbdd1f4182b74dc5c23697e5b4be0039.png', 0.00, 1, NULL, '2017-12-27 17:26:21', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (223, 2, 11, '', '美丽骷髅', '1890', NULL, NULL, '1890', '1', 6, 'app/img/admin/game/d01262ba08f6081a3072df3f89853102.png', 0.00, 1, NULL, '2017-12-27 17:27:04', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (224, 2, 11, '', '金库甜心', '1888', NULL, NULL, '1888', '1', 3, 'app/img/admin/game/4d62d421d31de92304c4a07e31d88f25.jpeg', 0.00, 1, NULL, '2017-12-28 11:37:12', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (225, 2, 11, '', '禁忌王座', '1887', NULL, NULL, '1887', '1', 4, 'app/img/admin/game/aa068c29b53497d73e3bf046d6b567b1.png', 0.00, 1, NULL, '2017-12-27 17:27:45', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (226, 2, 11, '', '梦果子乐园', '1886', NULL, NULL, '1886', '1', 7, 'app/img/admin/game/4f1e4b74e22d8cd4937715ea9dad0334.png', 0.00, 0, NULL, '2017-12-27 20:03:20', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (227, 2, 11, '', '巨额现金乘数', '1885', NULL, NULL, '1885', '1', 2, 'app/img/admin/game/4f1e4b74e22d8cd4937715ea9dad0334.png', 0.00, 1, NULL, '2017-12-27 17:32:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (228, 2, 11, '', '运财酷儿-5卷轴', '1884', NULL, NULL, '1884', '1', 2, 'app/img/admin/game/97fe5cb0ef1897f2a359652d5db39bdd.png', 0.00, 1, NULL, '2017-12-27 17:34:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (229, 2, 11, '', '热力四射', '1883', NULL, NULL, '1883', '1', 4, 'app/img/admin/game/74fd6d9d8b5d5b60005d2f71ebfbb390.png', 0.00, 1, NULL, '2017-12-27 17:34:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (230, 2, 11, '', '青龙出海', '1882', NULL, NULL, '1882', '1', 5, 'app/img/admin/game/19cc10081afd66fd4364e8b0272d620f.png', 0.00, 1, NULL, '2017-12-27 17:36:13', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (231, 2, 11, '', '花粉之国', '1881', NULL, NULL, '1881', '1', 7, 'app/img/admin/game/0679d958ee25b7099f8c620f754146b2.png', 0.00, 1, NULL, '2017-12-27 17:36:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (232, 2, 11, '', '经典243', '1879', NULL, NULL, '1879', '1', 6, 'app/img/admin/game/d2ff77ff5ceb360f711e42b94139fcd3.jpeg', 0.00, 1, NULL, '2017-12-28 11:39:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (233, 2, 11, '', '水果VS糖果', '1878', NULL, NULL, '1878', '1', 6, 'app/img/admin/game/642272965f869157df1a8ee54258d529.png', 0.00, 1, NULL, '2017-12-27 17:37:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (234, 2, 11, '', '秘密爱慕者', '1877', NULL, NULL, '1877', '1', 3, 'app/img/admin/game/d814663cbe9b4a1cc8f0bca92f4ee8ab.png', 0.00, 1, NULL, '2017-12-27 17:38:39', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (235, 2, 11, '', '富裕人生', '1851', NULL, NULL, '1851', '1', 4, 'app/img/admin/game/284ae454232fb1534f364e4da41935d8.png', 0.00, 1, NULL, '2017-12-27 17:39:59', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (236, 2, 11, '', '轩辕帝传', '1849', NULL, NULL, '1849', '1', 3, 'app/img/admin/game/c6c16d4aa5d79632516561fc85be043a.png', 0.00, 1, NULL, '2017-12-27 17:40:43', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (237, 2, 11, '', '泰山', '1847', NULL, NULL, '1847', '1', 4, 'app/img/admin/game/c2ba096d5fb2f9bccaf75d1d5caf0565.jpeg', 0.00, 1, NULL, '2017-12-28 11:51:41', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (238, 2, 11, '', '幸运龙宝贝', '1424', NULL, NULL, '1424', '1', 6, 'app/img/admin/game/ce1441ad20486b6a3ea9ad5612c73163.png', 0.00, 1, NULL, '2017-12-27 17:41:28', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (239, 2, 11, '', '上海美人', '1421', NULL, NULL, '1421', '1', 2, 'app/img/admin/game/6506839ace0115e46dcf98540c5c6f49.jpeg', 0.00, 1, NULL, '2017-12-28 11:48:38', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (240, 2, 11, '', '迷失拉斯维加斯', '1420', NULL, NULL, '1420', '1', 6, 'app/img/admin/game/fe2da594fe7271ac0438b268c916468c.jpeg', 0.00, 1, NULL, '2018-01-08 17:28:15', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (241, 2, 11, '', '千斤顶或更好', '1417', NULL, NULL, '1417', '1', 1, 'app/img/admin/game/28e68af3eb711662eef19c5bc24ebdc3.jpeg', 0.00, 1, NULL, '2017-12-28 11:46:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (242, 2, 11, '', '万能两点', '1415', NULL, NULL, '1415', '1', 1, 'app/img/admin/game/7329f3ef3d8de78a2e7eabf2497df591.jpeg', 0.00, 1, NULL, '2017-12-28 11:53:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (243, 2, 11, '', '王牌和面孔', '1413', NULL, NULL, '1413', '1', 1, 'app/img/admin/game/170fe9578dc5d031a15ee8b02aae54f2.jpeg', 0.00, 1, NULL, '2017-12-28 11:54:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (244, 2, 11, '', '泰利嗬', '1395', NULL, NULL, '1395', '1', 2, 'app/img/admin/game/d4c05691ccb9a4b33ba954fd8e8ed139.jpeg', 0.00, 1, NULL, '2017-12-28 11:51:24', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (245, 2, 11, '', '女仕之夜', '1389', NULL, NULL, '1389', '1', 2, 'app/img/admin/game/45aa1edb8806182b85a9de68e6162a0c.jpeg', 0.00, 1, NULL, '2017-12-28 11:44:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (246, 2, 11, '', '反转马戏团', '1386', NULL, NULL, '1386', '1', 6, 'app/img/admin/game/f480604741981fdea12d3b45181a5652.jpeg', 0.00, 1, NULL, '2017-12-28 11:33:58', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (247, 2, 11, '', '亚洲美人', '1384', NULL, NULL, '1384', '1', 6, 'app/img/admin/game/c7329ba65b0ad994d73493844bfafe58.jpeg', 0.00, 1, NULL, '2017-12-28 11:56:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (248, 2, 11, '', '缤纷糖果', '1374', NULL, NULL, '1374', '1', 3, 'app/img/admin/game/a4ece274b0e626a00419206418f2d834.jpeg', 0.00, 1, NULL, '2018-01-06 16:21:09', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (249, 2, 11, '', '昆虫派对', '1366', NULL, NULL, '1366', '1', 5, 'app/img/admin/game/e7328701805710df964d5755d1b569ac.jpeg', 0.00, 1, NULL, '2017-12-28 11:41:46', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (250, 2, 11, '', '伴娘', '1360', NULL, NULL, '1360', '1', 4, 'app/img/admin/game/ef2fe91a8f20c1b04fd4b55daf4f43f0.jpeg', 0.00, 1, NULL, '2018-02-01 19:50:23', 104, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (251, 2, 11, '', '开心点心', '1345', NULL, NULL, '1345', '1', 2, 'app/img/admin/game/0839cdfb55b8f26ffa39eae54dad5f6a.jpeg', 0.00, 1, NULL, '2017-12-28 11:41:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (252, 2, 11, '', '玛雅公主', '1343', NULL, NULL, '1343', '1', 3, 'app/img/admin/game/26e142dafd5e474db55c10da4d9e0d70.jpeg', 0.00, 1, NULL, '2017-12-28 11:43:39', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (253, 2, 11, '', '雷神2', '1330', NULL, NULL, '1330', '1', 6, 'app/img/admin/game/1d93012324d56637b2186fc35b6ccd88.jpeg', 0.00, 1, NULL, '2017-12-28 11:43:01', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (254, 2, 11, '', '沙发土豆', '1327', NULL, NULL, '1327', '1', 1, 'app/img/admin/game/40c8e4054a6ae0f035899d0eba7169ed.jpeg', 0.00, 1, NULL, '2017-12-28 11:47:35', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (255, 2, 11, '', '王牌和八面', '1324', NULL, NULL, '1324', '1', 1, 'app/img/admin/game/f80bc2c9b986de0068975740275c0079.jpeg', 0.00, 1, NULL, '2017-12-28 11:54:00', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (256, 2, 11, '', '终极杀手', '1321', NULL, NULL, '1321', '1', 3, 'app/img/admin/game/699df607c67316087688f8853cf7d529.jpeg', 0.00, 1, NULL, '2017-12-28 11:57:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (257, 2, 11, '', '泰坦之藏匿', '1320', NULL, NULL, '1320', '1', 3, 'app/img/admin/game/c64cd4b692cbe6da22773918592b92e6.jpeg', 0.00, 1, NULL, '2017-12-28 11:52:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (258, 2, 11, '', '燃烧的欲望', '1318', NULL, NULL, '1318', '1', 6, 'app/img/admin/game/b095652777b91b0930a5128e04e65259.jpeg', 0.00, 1, NULL, '2017-12-28 11:47:21', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (259, 2, 11, '', '疯狂的帽子', '1314', NULL, NULL, '1314', '1', 4, 'app/img/admin/game/e0a75ba5ec10aaa07ea8c98c333f086d.jpeg', 0.00, 1, NULL, '2018-02-05 16:11:47', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (260, 2, 11, '', '纯铂金', '1312', NULL, NULL, '1312', '1', 4, 'app/img/admin/game/0b45466526f7fc065e50096319a1d030.jpeg', 0.00, 1, NULL, '2018-01-06 16:26:13', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (261, 2, 11, '', '海底世界', '1308', NULL, NULL, '1308', '1', 3, 'app/img/admin/game/cca80574758045d3940313bbf98928b3.jpeg', 0.00, 1, NULL, '2017-12-28 11:35:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (262, 2, 11, '', '108好汉', '1302', NULL, NULL, '1302', '1', 3, 'app/img/admin/game/e486a03d5230ec65d61a94406f796df0.png', 0.00, 1, NULL, '2017-12-27 17:50:53', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (263, 2, 11, '', '旋转大战', '1294', NULL, NULL, '1294', '1', 2, 'app/img/admin/game/dd4ae14bb60d70b2acd24ccd7bc4cbc6.jpeg', 0.00, 1, NULL, '2017-12-27 17:51:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (264, 2, 11, '', '雷电击', '1293', NULL, NULL, '1293', '1', 2, 'app/img/admin/game/17b83eab479438c15eeb6b80c10394f1.jpeg', 0.00, 1, NULL, '2017-12-28 11:42:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (265, 2, 11, '', '中心球场', '1291', NULL, NULL, '1291', '1', 2, 'app/img/admin/game/251cc3bd1c3317f68540786f3abd4329.jpeg', 0.00, 1, NULL, '2017-12-28 11:57:27', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (266, 2, 11, '', '比基尼派对', '1290', NULL, NULL, '1290', '1', 6, 'app/img/admin/game/aea962a071160f2bde0b9cfa1e0161ee.png', 0.00, 1, NULL, '2017-12-27 17:52:34', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (267, 2, 11, '', '橄榄球明星', '1287', NULL, NULL, '1287', '1', 6, 'app/img/admin/game/267bdddbd58365e57750d15c51e7c289.jpeg', 0.00, 1, NULL, '2017-12-28 11:34:53', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (268, 2, 11, '', '幸运双星', '1283', NULL, NULL, '1283', '1', 2, 'app/img/admin/game/998f45057f24d767f88e848ed361d0b3.png', 0.00, 1, NULL, '2017-12-27 17:53:22', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (269, 2, 11, '', '射击', '1274', NULL, NULL, '1274', '1', 4, 'app/img/admin/game/47bec7070be57ca1d41f7a587fe778d0.jpeg', 0.00, 1, NULL, '2017-12-28 11:49:00', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (270, 2, 11, '', '沙滩宝贝', '1263', NULL, NULL, '1263', '1', 3, 'app/img/admin/game/cd19935b155970c7ee4057a7f2374699.jpeg', 0.00, 1, NULL, '2017-12-28 11:47:56', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (271, 2, 11, '', '美国酒吧', '1257', NULL, NULL, '1257', '1', 3, 'app/img/admin/game/182b7e6cd13ddbfabed5712e4563c9f1.jpeg', 0.00, 1, NULL, '2017-12-28 11:44:34', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (272, 2, 11, '', '神秘梦境', '1254', NULL, NULL, '1254', '1', 6, 'app/img/admin/game/94a64a877b19501fed75ec7ee769b0f1.jpeg', 0.00, 1, NULL, '2017-12-28 11:49:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (273, 2, 11, '', '埃及女神伊西絲', '1250', NULL, NULL, '1250', '1', 3, 'app/img/admin/game/bb165466de3878ea0badeecfbbdbaf46.jpeg', 0.00, 1, NULL, '2017-12-28 11:30:40', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (274, 2, 11, '', '史地大发现', '1246', NULL, NULL, '1246', '1', 3, 'app/img/admin/game/88e60f454056474a920b9e857d5738a0.jpeg', 0.00, 1, NULL, '2017-12-28 11:50:23', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (275, 2, 11, '', '炫富一族', '1245', NULL, NULL, '1245', '1', 3, 'app/img/admin/game/bb24a4eee520ac9814ea3f942b387cac.jpeg', 0.00, 1, NULL, '2017-12-28 11:56:15', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (276, 2, 11, '', '丛林吉姆-黄金国', '1244', NULL, NULL, '1244', '1', 3, 'app/img/admin/game/88b2d42cd0794281d942126be7a0f13d.png', 0.00, 1, NULL, '2017-12-27 17:57:27', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (277, 2, 11, '', '瞧！', '1241', NULL, NULL, '1241', '1', 6, 'app/img/admin/game/3f42710a2fd918ac3f1d5c6dc988c66a.jpeg', 0.00, 1, NULL, '2017-12-28 11:47:00', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (278, 2, 11, '', '换牌扑克', '1240', NULL, NULL, '1240', '1', 1, 'app/img/admin/game/ff5a32867bb514935b94c7e9b4198487.jpeg', 0.00, 1, NULL, '2017-12-28 11:35:48', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (279, 2, 11, '', '老鹰的翅膀', '1236', NULL, NULL, '1236', '1', 3, 'app/img/admin/game/c94d9d11413f733f2fec882bd9cf11d4.jpeg', 0.00, 1, NULL, '2017-12-28 11:42:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (280, 2, 11, '', '闪亮的圣诞节？', '1234', NULL, NULL, '1234', '1', 4, 'app/img/admin/game/7fcc1a2f2aaa419cfe5c5949ae3a5dd6.jpeg', 0.00, 1, NULL, '2017-12-28 11:48:18', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (281, 2, 11, '', '老虎之眼', '1232', NULL, NULL, '1232', '1', 4, 'app/img/admin/game/9476da38f4dc963ccd1b3075b042b9ea.jpeg', 0.00, 1, NULL, '2017-12-28 11:42:01', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (282, 2, 11, '', '冰球突破', '1229', NULL, NULL, '1229', '1', 6, 'app/img/admin/game/521b7492f974ddb09ff7f1fa65751bfe.jpeg', 0.00, 1, NULL, '2017-12-28 11:31:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (283, 2, 11, '', '狂野大熊猫', '1222', NULL, NULL, '1222', '1', 6, 'app/img/admin/game/cbb70c576bc68b20e9239db5e5cc0e74.jpeg', 0.00, 1, NULL, '2018-01-06 16:22:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (284, 2, 11, '', '宝石迷阵', '1207', NULL, NULL, '1207', '1', 6, 'app/img/admin/game/5c20f32762deb8a2beeafdac4023b216.jpeg', 0.00, 1, NULL, '2017-12-28 11:31:10', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (285, 2, 11, '', '黄金公主', '1190', NULL, NULL, '1190', '1', 3, 'app/img/admin/game/882368599e5292e7475c66460e4bd289.png', 0.00, 1, NULL, '2018-02-03 08:43:30', 114, 1, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (286, 2, 11, '', '复古卷轴-极热', '1189', NULL, NULL, '1189', '1', 4, 'app/img/admin/game/a957327e70d0429972d423c15456481d.png', 0.00, 1, NULL, '2017-12-27 18:01:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (287, 2, 11, '', '足球明星', '1186', NULL, NULL, '1186', '1', 6, 'app/img/admin/game/9a010324946be85d99b247daa1f82a99.jpeg', 0.00, 1, NULL, '2017-12-28 11:57:55', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (288, 2, 11, '', '丛林摇摆', '1173', NULL, NULL, '1173', '1', 3, 'app/img/admin/game/143b8414d82cb0dd8d591fc0860743f9.jpeg', 0.00, 1, NULL, '2017-12-28 11:33:09', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (289, 2, 11, '', '猫头鹰乐园', '1171', NULL, NULL, '1171', '1', 2, 'app/img/admin/game/784f6f11e2c0919d533839420461f215.jpeg', 0.00, 1, NULL, '2017-12-27 18:02:29', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (290, 2, 11, '', '3D纯银玫瑰', '1170', NULL, NULL, '1170', '1', 3, 'app/img/admin/game/26e3e71463dc39d12f74a65c54f9447b.jpeg', 0.00, 1, NULL, '2018-01-06 16:23:06', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (291, 2, 11, '', '冒险之旅', '1169', NULL, NULL, '1169', '1', 4, 'app/img/admin/game/db1c377873e1e08828ca8da5b231ca2d.jpeg', 0.00, 1, NULL, '2017-12-28 11:44:14', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (292, 2, 11, '', '星梦之吻', '1167', NULL, NULL, '1167', '1', 4, 'app/img/admin/game/1e7e1d7ef1958c65ed1e491ad27aab55.jpeg', 0.00, 1, NULL, '2017-12-28 11:55:34', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (293, 2, 11, '', '急冻钻石', '1165', NULL, NULL, '1165', '1', 3, 'app/img/admin/game/83e2b1a6ade423ad4f6d9261656d7c8b.png', 0.00, 1, NULL, '2017-12-27 18:04:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (294, 2, 11, '', '东方珍兽', '1164', NULL, NULL, '1164', '1', 6, 'app/img/admin/game/3bacb5fabdad61436ce2795da4ba948d.jpeg', 0.00, 1, NULL, '2017-12-28 11:33:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (295, 2, 11, '', '上流社会', '1163', NULL, NULL, '1163', '1', 3, 'app/img/admin/game/f81ce348583619a48acd9d6ef841022e.jpeg', 0.00, 1, NULL, '2017-12-27 18:05:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (296, 2, 11, '', '篮球巨星', '1159', NULL, NULL, '1159', '1', 6, 'app/img/admin/game/7709251f00c6aca7bfa0bdb7fba83cdb.png', 0.00, 1, NULL, '2017-12-27 18:06:09', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (297, 2, 11, '', '特务珍金', '1155', NULL, NULL, '1155', '1', 2, 'app/img/admin/game/1783297972483e0064af4d9495ba2b42.jpeg', 0.00, 1, NULL, '2017-12-28 11:53:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (298, 2, 11, '', '卡萨缦都', '1151', NULL, NULL, '1151', '1', 2, 'app/img/admin/game/4b23147669469d41153b082fa884f8ac.jpeg', 0.00, 1, NULL, '2017-12-28 11:40:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (299, 2, 11, '', '太阳征程', '1150', NULL, NULL, '1150', '1', 2, 'app/img/admin/game/2079fc0239b7e14820527cbe2483757c.png', 0.00, 1, NULL, '2017-12-27 18:07:17', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (300, 2, 11, '', '疯狂奖金局末平分', '1148', NULL, NULL, '1148', '1', 1, 'app/img/admin/game/4cbd34f470506be8e9933233edb8e09e.jpeg', 0.00, 1, NULL, '2017-12-28 11:34:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (301, 2, 11, '', '马戏篷', '1133', NULL, NULL, '1133', '1', 2, 'app/img/admin/game/445aa2622c735bc1073274ee702bddbb.jpeg', 0.00, 1, NULL, '2017-12-28 11:43:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (302, 2, 11, '', '夏天', '1130', NULL, NULL, '1130', '1', 2, 'app/img/admin/game/1a426178658a341b867086a71590bc3f.jpeg', 0.00, 1, NULL, '2017-12-28 11:54:35', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (303, 2, 11, '', '古墓丽影', '1122', NULL, NULL, '1122', '1', 3, 'app/img/admin/game/adb4cadafde8db9dfd9024685b03b988.png', 0.00, 1, NULL, '2017-12-27 18:08:35', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (304, 2, 11, '', '狂欢节', '1117', NULL, NULL, '1117', '1', 2, 'app/img/admin/game/004a4ef2684b13ac91aa05e9caa11656.jpeg', 0.00, 1, NULL, '2017-12-27 18:09:18', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (305, 2, 11, '', '派对鱼', '1113', NULL, NULL, '1113', '1', 6, 'app/img/admin/game/4cfe8e7fa5a2c7c137b42e64319dab47.jpeg', 0.00, 1, NULL, '2017-12-28 11:45:56', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (306, 2, 11, '', '复古旋转', '1110', NULL, NULL, '1110', '1', 3, 'app/img/admin/game/68cff77a107d741e76109eaabfc3f9b0.jpeg', 0.00, 1, NULL, '2017-12-27 18:10:29', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (307, 2, 11, '', '不朽情缘', '1103', NULL, NULL, '1103', '1', 6, 'app/img/admin/game/c106245ee7827291fcb621e27e2784b6.jpeg', 0.00, 1, NULL, '2018-02-01 12:55:03', 103, 1, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (308, 2, 11, '', '双重韦密', '1102', NULL, NULL, '1102', '1', 1, 'app/img/admin/game/43d0bfa0b44f31e2b281b25081743e48.jpeg', 0.00, 1, NULL, '2017-12-28 11:51:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (309, 2, 11, '', '银行爆破', '1097', NULL, NULL, '1097', '1', 2, 'app/img/admin/game/8588a42134722db5861c5aff3b020ae6.jpeg', 0.00, 1, NULL, '2017-12-28 11:57:12', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (310, 2, 11, '', '欧式轮盘', '1095', NULL, NULL, '1095', '1', 1, 'app/img/admin/game/da4a82744f74a54d966e94694bebce80.jpeg', 0.00, 1, NULL, '2017-12-28 11:45:16', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (311, 2, 11, '', '地狱男爵', '1081', NULL, NULL, '1081', '1', 3, 'app/img/admin/game/2c63ab41a361a54da91ce73b62cf88ce.jpeg', 0.00, 1, NULL, '2017-12-27 18:12:17', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (312, 2, 11, '', '快乐假日', '1072', NULL, NULL, '1072', '1', 6, 'app/img/admin/game/a64bf9f5903b0fb5c52121b460303b70.png', 0.00, 1, NULL, '2017-12-27 18:13:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (313, 2, 11, '', '银芳', '1062', NULL, NULL, '1062', '1', 4, 'app/img/admin/game/ea46c21d07a14598e8f48b0b0fa11daa.jpeg', 0.00, 1, NULL, '2017-12-28 11:56:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (314, 2, 11, '', '幸运的锦鲤', '1060', NULL, NULL, '1060', '1', 3, 'app/img/admin/game/26423beac92fc003b13b1f1fd4996c9c.jpeg', 0.00, 1, NULL, '2017-12-28 11:55:59', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (315, 2, 11, '', 'K歌乐韵', '1053', NULL, NULL, '1053', '1', 2, 'app/img/admin/game/1317d87007d26740ffe6356ecfa05d7e.png', 0.00, 1, NULL, '2017-12-27 18:14:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (316, 2, 11, '', '经典黄金21点', '1052', NULL, NULL, '1052', '1', 1, 'app/img/admin/game/b368b43653eb70efe663d7bb0c0e541d.jpeg', 0.00, 1, NULL, '2017-12-28 11:40:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (317, 2, 11, '', '歐式21点黄金桌', '1051', NULL, NULL, '1051', '1', 1, 'app/img/admin/game/f8a1976a94105975077db7f3d19aa69b.jpeg', 0.00, 1, NULL, '2017-12-28 11:45:32', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (318, 2, 11, '', '狮子的骄傲', '1049', NULL, NULL, '1049', '1', 5, 'app/img/admin/game/1cfed92ad7a64d49a767913731a3d2a7.jpeg', 0.00, 1, NULL, '2017-12-28 11:49:55', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (319, 2, 11, '', '万圣节老虎机', '1047', NULL, NULL, '1047', '1', 3, 'app/img/admin/game/efa7ac5e75163add4845e287f7544a44.jpeg', 0.00, 1, NULL, '2018-01-06 16:23:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (320, 2, 11, '', '漂亮猫咪', '1045', NULL, NULL, '1045', '1', 6, 'app/img/admin/game/178ca71f56457ac9c18e6f4f2e54afe7.jpeg', 0.00, 1, NULL, '2017-12-27 18:16:18', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (321, 2, 11, '', '黄金时代', '1041', NULL, NULL, '1041', '1', 3, 'app/img/admin/game/e854be0e3598451f9d16620ecfcc6e20.jpeg', 0.00, 1, NULL, '2018-01-03 16:18:15', 100, 1, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (322, 2, 11, '', '舞龙', '1037', NULL, NULL, '1037', '1', 6, 'app/img/admin/game/2815786942df0ccb2046a47dace21e9d.png', 0.00, 1, NULL, '2017-12-27 18:22:19', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (323, 2, 11, '', '5卷的驱动器', '1035', NULL, NULL, '1035', '1', 2, 'app/img/admin/game/1718d2c386e7c32aa9be5bfd37ceb333.jpeg', 0.00, 1, NULL, '2017-12-28 11:30:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (324, 2, 11, '', '寿司多多', '1032', NULL, NULL, '1032', '1', 3, 'app/img/admin/game/57bab10ca58e6103ee28dd5576568a03.jpeg', 0.00, 1, NULL, '2017-12-28 11:50:47', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (325, 2, 11, '', '雷神', '1028', NULL, NULL, '1028', '1', 2, 'app/img/admin/game/717c0a93512d9b6e9b183562e5fe1dcd.jpeg', 0.00, 1, NULL, '2017-12-27 18:24:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (326, 2, 11, '', '抢银行', '1023', NULL, NULL, '1023', '1', 2, 'app/img/admin/game/14c9333e30000b7bb442a7f696626d4c.jpeg', 0.00, 1, NULL, '2018-01-04 13:56:35', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (327, 2, 11, '', '财宝宫殿', '1020', NULL, NULL, '1020', '1', 2, 'app/img/admin/game/fa37053b47b22338b82792eff954ded7.jpeg', 0.00, 1, NULL, '2017-12-28 11:32:30', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (328, 2, 11, '', '阿瓦隆', '1013', NULL, NULL, '1013', '1', 3, 'app/img/admin/game/242682a6264354838e7c0de9860bb426.png', 0.00, 1, NULL, '2017-12-27 18:25:06', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (329, 2, 11, '', '冒险宫殿', '1010', NULL, NULL, '1010', '1', 2, 'app/img/admin/game/b8853f9086d9f8d44efb9cec3e1e496b.png', 0.00, 1, NULL, '2017-12-27 18:25:53', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (330, 2, 11, '', '押韵的卷轴-心挞', '1009', NULL, NULL, '1009', '1', 4, 'app/img/admin/game/f7ec906edda0ec459ebebdd71260c42f.png', 0.00, 1, NULL, '2017-12-27 18:27:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (331, 2, 11, '', '阿拉斯加捕捞', '1004', NULL, NULL, '1004', '1', 6, 'app/img/admin/game/9483170ba5a62b6739f4757f6267c30d.jpeg', 0.00, 1, NULL, '2017-12-28 11:30:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (332, 2, 11, '', '春假', '1002', NULL, NULL, '1002', '1', 2, 'app/img/admin/game/7f2e821c2de75bbe90ea1ba89798793e.jpeg', 0.00, 1, NULL, '2017-12-28 11:32:52', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (333, 2, 11, '', '怪兽多多', '1001', NULL, NULL, '1001', '1', 3, 'app/img/admin/game/5b899281c8ddf82407fe19fc47eb413e.jpeg', 0.00, 1, NULL, '2017-12-28 11:35:17', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (334, 4, 12, '', '三卡吹牛', 'ash3brg', NULL, NULL, 'ash3brg', '1', 1, 'app/img/admin/game/75e15b045a0236146b25d8e5e0013ed3.png', 0.00, 1, NULL, '2018-02-02 14:27:31', 114, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (335, 4, 12, '', '狂欢夜', 'hb', NULL, NULL, 'hb', '1', 1, 'app/img/admin/game/0a4679dfb02d95ce689763acd182f322.jpeg', 0.00, 1, NULL, '2018-02-01 22:18:35', 108, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (336, 4, 12, '', '梦游仙境豪华版', 'ashadv', NULL, NULL, 'ashadv', '1', 1, 'app/img/admin/game/c7dd5edeb8eebeab5723ca0aefabf667.jpeg', 0.00, 1, NULL, '2018-01-29 16:06:00', 106, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (337, 4, 12, '', '众神时代', 'aogs', NULL, NULL, 'aogs', '1', 1, 'app/img/admin/game/72baa45c98c634e220ef83cece8f43eb.jpeg', 0.00, 1, NULL, '2018-01-29 16:08:34', 104, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (338, 4, 12, '', '众神时代：命运姐妹', 'ftsis', NULL, NULL, 'ftsis', '1', 1, 'app/img/admin/game/6a7aa3709f4c981fedb1e44e14d09944.png', 0.00, 1, NULL, '2018-01-17 19:29:22', 103, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (339, 4, 12, '', '众神时代：狂暴4', 'furf', NULL, NULL, 'furf', '1', 1, 'app/img/admin/game/8d7c1f2157977107e1cdee7865e422ed.jpeg', 0.00, 1, NULL, '2018-01-10 14:37:01', 103, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (340, 4, 12, '', '众神时代：智慧女神', 'athn', NULL, NULL, 'athn', '1', 1, 'app/img/admin/game/95c0b80b1c0768353bb58dd340b7f969.png', 0.00, 1, NULL, '2018-01-07 04:46:17', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (341, 4, 12, '', '众神时代：奥林匹斯之王', 'kolymp', NULL, NULL, 'kolymp', '1', 1, 'app/img/admin/game/7f43693d69720debde8e864e59a9e71a.png', 0.00, 1, NULL, '2017-12-27 15:30:52', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (342, 4, 12, '', '众神时代：奥林匹斯王子', 'hrcls', NULL, NULL, 'hrcls', '1', 1, 'app/img/admin/game/c39e36bbb3e1cbb65e0acf92f6869351.png', 0.00, 1, NULL, '2017-12-27 15:32:40', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (343, 4, 12, '', '众神时代轮盘', 'aogro', NULL, NULL, 'aogro', '1', 1, 'app/img/admin/game/63854f8e7bf534f4a03a6350d67b0274.jpeg', 0.00, 1, NULL, '2018-01-13 14:38:10', 103, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (344, 4, 12, '', '野生亚马逊', 'ashamw', NULL, NULL, 'ashamw', '1', 1, 'app/img/admin/game/0c99480abf4279cb9afbda3f7245433b.jpeg', 0.00, 1, NULL, '2017-12-28 18:35:34', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (345, 4, 12, '', '弓箭手', 'arc', NULL, NULL, 'arc', '1', 1, 'app/img/admin/game/32326aedb76147393d623f59d962870e.png', 0.00, 1, NULL, '2017-12-27 15:34:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (346, 4, 12, '', '北极宝藏', 'art', NULL, NULL, 'art', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 13:26:05', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (347, 4, 12, '', '亚特兰蒂斯女王', 'gtsatq', NULL, NULL, 'gtsatq', '1', 1, 'app/img/admin/game/ca1279474d28c4c78d2bcbd4795f5e30.png', 0.00, 1, NULL, '2017-12-27 15:35:28', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (348, 4, 12, '', '百家乐', 'ba', NULL, NULL, 'ba', '1', 1, 'app/img/admin/game/955c0003c8a7392ca7e7b6d9a687b262.jpeg', 0.00, 1, NULL, '2018-01-31 18:01:06', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (349, 4, 12, '', '白狮', 'bs', NULL, NULL, 'bs', '1', 1, 'app/img/admin/game/bfa7d2391851ef4257184f63410a95fc.png', 0.00, 1, NULL, '2018-01-08 15:08:04', 104, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (350, 4, 12, '', '海滨嘉年华', 'bl', NULL, NULL, 'bl', '1', 1, 'app/img/admin/game/80a4f25650732b70a3b5fb38a427e0d7.png', 0.00, 1, NULL, '2017-12-27 15:37:41', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (351, 4, 12, '', '百慕大三角', 'bt', NULL, NULL, 'bt', '1', 1, 'app/img/admin/game/e74406a9ab8a2eed6e29b31eb6782bc7.png', 0.00, 1, NULL, '2018-01-13 14:40:59', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (352, 4, 12, '', '21点', 'mobbj', NULL, NULL, 'mobbj', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 13:26:37', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (353, 4, 12, '', '熊之舞', 'bob', NULL, NULL, 'bob', '1', 1, 'app/img/admin/game/964c81aa6aa49374fe1ec81bdd42f214.jpeg', 0.00, 1, NULL, '2017-12-28 18:33:46', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (354, 4, 12, '', '魔豆赏金', 'ashbob', NULL, NULL, 'ashbob', '1', 1, 'app/img/admin/game/7d05ad3b73c81f19e7450d0b4b4b2e26.jpeg', 0.00, 1, NULL, '2018-02-05 16:14:21', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (355, 4, 12, '', '犎牛闪电突击', 'bfb', NULL, NULL, 'bfb', '1', 1, 'app/img/admin/game/568beda75a6c70bd1f1d2d205b748ffc.jpeg', 0.00, 1, NULL, '2017-12-28 18:16:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (356, 4, 12, '', '船长的宝藏', 'ct', NULL, NULL, 'ct', '1', 1, 'app/img/admin/game/0d8479a0889c8f8d5355b2d3898560c2.png', 0.00, 1, NULL, '2017-12-27 15:40:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (357, 4, 12, '', '娱乐场同花顺', 'cheaa', NULL, NULL, 'cheaa', '1', 1, 'app/img/admin/game/c62a9c315921746cfce2d0097bb4d819.jpeg', 0.00, 1, NULL, '2018-01-13 15:35:57', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (358, 4, 12, '', '猫王战赌城', 'ctiv', NULL, NULL, 'ctiv', '1', 1, 'app/img/admin/game/e8a3b3eb217f94e6294f8b9936b2d690.jpeg', 0.00, 1, NULL, '2017-12-28 18:23:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (359, 4, 12, '', '猫后', 'catqc', NULL, NULL, 'catqc', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 13:27:49', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (360, 4, 12, '', '超级 888', 'chao', NULL, NULL, 'chao', '1', 1, 'app/img/admin/game/bf0da27d92cafe74ee3861e51d3100b5.jpeg', 0.00, 1, NULL, '2018-01-13 15:37:02', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (361, 4, 12, '', '狂野樱桃', 'chl', NULL, NULL, 'chl', '1', 1, 'app/img/admin/game/d36626c6391456021c3e5bba280ca39a.jpeg', 0.00, 1, NULL, '2017-12-28 18:21:29', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (362, 4, 12, '', '宝箱满满', 'ashcpl', NULL, NULL, 'ashcpl', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 13:28:59', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (363, 4, 12, '', '中式厨房', 'cm', NULL, NULL, 'cm', '1', 1, 'app/img/admin/game/676deab8d87c450ca2b309b1ce78f6b5.jpeg', 0.00, 1, NULL, '2017-12-28 18:37:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (364, 4, 12, '', '警察和土匪', 'gtscnb', NULL, NULL, 'gtscnb', '1', 1, 'app/img/admin/game/cb73ec450bceefbfdc553e40530f9487.jpeg', 0.00, 1, NULL, '2017-12-28 18:20:39', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (365, 4, 12, '', '牛仔和外星人', 'gtscbl', NULL, NULL, 'gtscbl', '1', 1, 'app/img/admin/game/25148517e0673b0314810ba1b39d3b35.jpeg', 0.00, 1, NULL, '2017-12-28 18:24:12', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (366, 4, 12, '', '疯狂之七', 'c7', NULL, NULL, 'c7', '1', 1, 'app/img/admin/game/093bc7a298e7acdddf8fec5a39d988a4.png', 0.00, 1, NULL, '2017-12-27 15:42:41', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (367, 4, 12, '', '无畏的戴夫', 'gtsdrdv', NULL, NULL, 'gtsdrdv', '1', 1, 'app/img/admin/game/085ab04b53af5082b541dfdc2a2f7bcd.jpeg', 0.00, 1, NULL, '2017-12-28 18:32:04', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (368, 4, 12, '', '沙漠财宝', 'mobdt', NULL, NULL, 'mobdt', '1', 1, 'app/img/admin/game/671d5a6b3fe27c4853b9df2a551435fd.png', 0.00, 1, NULL, '2017-12-27 15:43:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (369, 4, 12, '', '海豚之梦', 'dnr', NULL, NULL, 'dnr', '1', 1, 'app/img/admin/game/f949e661acd103015a33443b68412114.jpeg', 0.00, 1, NULL, '2017-12-28 18:17:14', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (370, 4, 12, '', '情圣博士', 'dlm', NULL, NULL, 'dlm', '1', 1, 'app/img/admin/game/86162b135d3d371c226e3be5d06f18de.jpeg', 0.00, 1, NULL, '2017-12-28 18:25:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (371, 4, 12, '', '龙之国度', 'gtsdgk', NULL, NULL, 'gtsdgk', '1', 1, 'app/img/admin/game/caea806223a219a92413c54f87cda280.jpeg', 0.00, 1, NULL, '2017-12-28 18:22:27', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (372, 4, 12, '', '复活节惊喜', 'eas', NULL, NULL, 'eas', '1', 1, 'app/img/admin/game/205ea270fe4cccdd86cd6eeeb73f013b.jpeg', 0.00, 1, NULL, '2017-12-28 18:16:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (373, 4, 12, '', '埃斯梅拉达', 'esm', NULL, NULL, 'esm', '1', 1, 'app/img/admin/game/b48b065e66d794c35530777ebcefc326.jpeg', 0.00, 1, NULL, '2017-12-27 15:45:16', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (374, 4, 12, '', '欧式轮盘', 'mobro', NULL, NULL, 'mobro', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 13:29:23', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (375, 4, 12, '', '人人中头奖', 'evj', NULL, NULL, 'evj', '1', 1, 'app/img/admin/game/5dc301d445ba7a8cdd9389422847afdb.jpeg', 0.00, 1, NULL, '2018-01-13 15:29:27', 106, 1, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (376, 4, 12, '', '魔镜与公主', 'ashfta', NULL, NULL, 'ashfta', '1', 1, 'app/img/admin/game/f462f15a50d374eb111092dff43d0e11.jpeg', 0.00, 1, NULL, '2018-01-13 14:43:02', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (377, 4, 12, '', '翡翠公主', 'fcgz', NULL, NULL, 'fcgz', '1', 1, 'app/img/admin/game/a918f581fccbf31f16cbeab464810e62.png', 0.00, 1, NULL, '2017-12-27 15:46:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (378, 4, 12, '', '飞龙在天', 'gtsflzt', NULL, NULL, 'gtsflzt', '1', 1, 'app/img/admin/game/53a1ee6a78a62bc593b23d4213b8e63b.png', 0.00, 1, NULL, '2017-12-27 15:47:24', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (379, 4, 12, '', '疯狂麻将', 'fkmj', NULL, NULL, 'fkmj', '1', 1, 'app/img/admin/game/3a507021ed0473415d57123f49fb34fe.png', 0.00, 1, NULL, '2017-12-27 15:48:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (380, 4, 12, '', '五虎将', 'ftg', NULL, NULL, 'ftg', '1', 1, 'app/img/admin/game/ebb636b819845e479642bb45f053e4e0.jpeg', 0.00, 1, NULL, '2017-12-28 18:32:56', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (381, 4, 12, '', '青春之泉', 'foy', NULL, NULL, 'foy', '1', 1, 'app/img/admin/game/8cb57e1f4a8517bfae0df0ede392250a.jpeg', 0.00, 1, NULL, '2017-12-28 18:25:22', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (382, 4, 12, '', '足球嘉年华', 'gtsfc', NULL, NULL, 'gtsfc', '1', 1, 'app/img/admin/game/9c618e88c2c093baedf1547e2e68b196.jpeg', 0.00, 1, NULL, '2017-12-28 18:38:22', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (383, 4, 12, '', '终极足球', 'fbr', NULL, NULL, 'fbr', '1', 1, 'app/img/admin/game/6d0d1c66c894450dad8759ef7ab4f3b2.png', 0.00, 1, NULL, '2017-12-27 15:49:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (384, 4, 12, '', '惊异之林', 'fow', NULL, NULL, 'fow', '1', 1, 'app/img/admin/game/b137b69338593c66cbc346cedd14b8c9.png', 0.00, 1, NULL, '2017-12-27 15:50:53', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (385, 4, 12, '', '五福海盗', 'frtf', NULL, NULL, 'frtf', '1', 1, 'app/img/admin/game/4ebaf0929d76d0feb2b59a478dbc181e.jpeg', 0.00, 1, NULL, '2018-02-05 16:22:23', 102, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (386, 4, 12, '', '幸运日', 'fday', NULL, NULL, 'fday', '1', 1, 'app/img/admin/game/5241ebc6b615a77e53d00e31288e6cf1.jpeg', 0.00, 1, NULL, '2017-12-28 18:33:13', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (387, 4, 12, '', '幸运狮子', 'frtln', NULL, NULL, 'frtln', '1', 1, 'app/img/admin/game/b3932ebfeb0148bfee6f274f13672eb0.png', 0.00, 1, NULL, '2018-01-09 16:19:00', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (388, 4, 12, '', '狐媚宝藏', 'fxf', NULL, NULL, 'fxf', '1', 1, 'app/img/admin/game/f60c938792c832d7a52c16c20fbe5623.jpeg', 0.00, 1, NULL, '2017-12-28 18:18:08', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (389, 4, 12, '', '青春之泉', 'foy', NULL, NULL, 'foy', '1', 1, 'app/img/admin/game/8cb57e1f4a8517bfae0df0ede392250a.jpeg', 0.00, 0, NULL, '2018-01-06 13:22:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (390, 4, 12, '', '德托里传奇', 'fdt', NULL, NULL, 'fdt', '1', 1, 'app/img/admin/game/ce29fd7ea4dbd6457e13e33547f62465.jpeg', 0.00, 1, NULL, '2017-12-28 18:15:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (391, 4, 12, '', '德托里传奇积宝游戏', 'fdtjg', NULL, NULL, 'fdtjg', '1', 1, 'app/img/admin/game/8e3d0ae903f09e6ed0239887b9508648.jpeg', 0.00, 1, NULL, '2017-12-28 18:15:34', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (392, 4, 12, '', '水果狂热', 'fmn', NULL, NULL, 'fmn', '1', 1, 'app/img/admin/game/55f32489fd4a7cef6cd2008526024cd8.jpeg', 0.00, 1, NULL, '2017-12-28 18:30:29', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (393, 4, 12, '', '圆月财富', 'ashfmf', NULL, NULL, 'ashfmf', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 14:08:31', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (394, 4, 12, '', '酷炫水果农场', 'fff', NULL, NULL, 'fff', '1', 1, 'app/img/admin/game/e405b520f9b0b9f26a53f70ce3f667f3.png', 0.00, 1, NULL, '2017-12-27 15:55:36', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (395, 4, 12, '', '酷炫水果', 'fnfrj', NULL, NULL, 'fnfrj', '1', 1, 'app/img/admin/game/34774170ab1887665c36e2d43e81e751.jpeg', 0.00, 1, NULL, '2017-12-27 15:56:22', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (396, 4, 12, '', '古怪猴子', 'fm', NULL, NULL, 'fm', '1', 1, 'app/img/admin/game/bdb34580f40df81d0e37ce48576737f5.png', 0.00, 1, NULL, '2018-02-03 16:51:38', 106, 1, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (397, 4, 12, '', '艺伎故事', 'ges', NULL, NULL, 'ges', '1', 1, 'app/img/admin/game/74fff9b44bedcde503c2db6461d2ea22.jpeg', 0.00, 1, NULL, '2017-12-28 18:35:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (398, 4, 12, '', '角斗士积宝', 'glrj', NULL, NULL, 'glrj', '1', 1, 'app/img/admin/game/ec718c0e9d9e8b5dc6a39e17db6a3012.jpeg', 0.00, 1, NULL, '2017-12-28 18:20:23', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (399, 4, 12, '', '宝石女王', 'gemq', NULL, NULL, 'gemq', '1', 1, 'app/img/admin/game/227ed232746b47c6a3983165f270fbf4.jpeg', 0.00, 1, NULL, '2017-12-27 15:58:14', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (400, 4, 12, '', '金色召集', 'grel', NULL, NULL, 'grel', '1', 1, 'app/img/admin/game/ad2d64e38fc78060db920141cc0dba51.png', 0.00, 1, NULL, '2017-12-27 15:59:10', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (401, 4, 12, '', '黄金体育竞技场', 'glg', NULL, NULL, 'glg', '1', 1, 'app/img/admin/game/aa447045a26beaca71a750af3530630f.jpeg', 0.00, 1, NULL, '2017-12-28 18:18:41', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (402, 4, 12, '', '黄金之旅', 'gos', NULL, NULL, 'gos', '1', 1, 'app/img/admin/game/65a2794b147bfb4b3920be7b7d1a860c.png', 0.00, 1, NULL, '2017-12-27 16:00:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (403, 4, 12, '', '海底探宝', 'bib', NULL, NULL, 'bib', '1', 1, 'app/img/admin/game/39f763a1ceff4f60dbdcda70b91c9f1e.jpeg', 0.00, 1, NULL, '2017-12-28 18:16:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (404, 4, 12, '', '最强奥德赛', 'gro', NULL, NULL, 'gro', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 14:40:05', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (405, 4, 12, '', '万圣节宝藏', 'hlf', NULL, NULL, 'hlf', '1', 1, 'app/img/admin/game/f86dae372531dc39a0264417b311b281.jpeg', 0.00, 1, NULL, '2018-01-31 15:26:51', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (406, 4, 12, '', '万圣节宝藏 2', 'hlf2', NULL, NULL, 'hlf2', '1', 1, 'app/img/admin/game/52404d6e2ece126e6f18f669b788daa9.jpeg', 0.00, 1, NULL, '2017-12-28 18:31:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (407, 4, 12, '', '鬼宅', 'hh', NULL, NULL, 'hh', '1', 1, 'app/img/admin/game/daab52bd461c6eb52177374778f37627.jpeg', 0.00, 1, NULL, '2017-12-28 18:16:33', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (408, 4, 12, '', '丛林之心', 'ashhotj', NULL, NULL, 'ashhotj', '1', 1, 'app/img/admin/game/bd8ca6087916cc18eec84e10cfea80b3.png', 0.00, 1, NULL, '2017-12-27 16:01:28', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (409, 4, 12, '', '武则天', 'heavru', NULL, NULL, 'heavru', '1', 1, 'app/img/admin/game/45421a5c47e52af3a8b76a0ced41bf28.png', 0.00, 1, NULL, '2017-12-27 16:02:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (410, 4, 12, '', '高速公路之王', 'hk', NULL, NULL, 'hk', '1', 1, 'app/img/admin/game/9eb24e7393303f9b6b4728587fa68bc6.png', 0.00, 1, NULL, '2018-01-08 14:44:23', 105, 1, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (411, 4, 12, '', '炙热宝石', 'gts50', NULL, NULL, 'gts50', '1', 1, 'app/img/admin/game/38ca5753d72acfbed2d8d39ba7a9a1ee.jpeg', 0.00, 1, NULL, '2017-12-28 18:37:18', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (412, 4, 12, '', '火热KTV', 'hotktv', NULL, NULL, 'hotktv', '1', 1, 'app/img/admin/game/ee5c83f89cd768c86da44a2ec8a41d2e.jpeg', 0.00, 0, NULL, '2017-12-28 18:19:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (413, 4, 12, '', '印加帝国头奖', 'aztec', NULL, NULL, 'aztec', '1', 1, 'app/img/admin/game/e826e1b4aafff8dbfcef95b6bb32412b.jpeg', 0.00, 1, NULL, '2017-12-28 18:36:15', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (414, 4, 12, '', '浮冰流', 'ir', NULL, NULL, 'ir', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 14:40:48', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (415, 4, 12, '', '幸运爱尔兰', 'irl', NULL, NULL, 'irl', '1', 1, 'app/img/admin/game/c4386a60a44fee9cf0ebea29f1ae2fb0.png', 0.00, 1, NULL, '2017-12-27 16:05:13', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (416, 4, 12, '', '奖金巨人', 'jpgt', NULL, NULL, 'jpgt', '1', 1, 'app/img/admin/game/72a55b4455e4e111570dd30de81e7c36.jpeg', 0.00, 1, NULL, '2017-12-28 18:19:25', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (417, 4, 12, '', '玉皇大帝', 'gtsje', NULL, NULL, 'gtsje', '1', 1, 'app/img/admin/game/c10186446db01bea9a24530ee5ec4d34.png', 0.00, 1, NULL, '2017-12-27 16:06:10', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (418, 4, 12, '', '吉祥 8', 'gtsjxb', NULL, NULL, 'gtsjxb', '1', 1, 'app/img/admin/game/b60ca1dceb5a52f6c5483e6976def367.png', 0.00, 1, NULL, '2017-12-27 16:07:23', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (419, 4, 12, '', '金钱蛙', 'jqw', NULL, NULL, 'jqw', '1', 1, 'app/img/admin/game/2c67b714dd3f2040460ec1b4afd929fb.png', 0.00, 1, NULL, '2017-12-27 16:08:16', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (420, 4, 12, '', '约翰韦恩', 'gtsjhw', NULL, NULL, 'gtsjhw', '', NULL, NULL, 0.00, 0, NULL, '2017-12-29 11:12:27', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (421, 4, 12, '', '无敌金刚', 'kkg', NULL, NULL, 'kkg', '1', 1, 'app/img/admin/game/06250ede21cd50f556dc9ebae94322e5.jpeg', 0.00, 1, NULL, '2017-12-29 11:12:12', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (422, 4, 12, '', '遍地黄金', 'lndg', NULL, NULL, 'lndg', '', NULL, NULL, 0.00, 0, NULL, '2017-12-29 11:13:58', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (423, 4, 12, '', '烈焰钻石', 'ght_a', NULL, NULL, 'ght_a', '1', 1, 'app/img/admin/game/07e3d9e5512b398c3b9a183732dbb832.png', 0.00, 1, NULL, '2017-12-27 16:09:23', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (424, 4, 12, '', '六福兽', 'kfp', NULL, NULL, 'kfp', '1', 1, 'app/img/admin/game/58b7cbf590a8d039af70c9031105c7cb.jpeg', 0.00, 1, NULL, '2017-12-28 18:22:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (425, 4, 12, '', '龙龙龙', 'longlong', NULL, NULL, 'longlong', '1', 1, 'app/img/admin/game/8877e496264a3c34582215e65433dc60.png', 0.00, 1, NULL, '2017-12-27 16:10:17', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (426, 4, 12, '', '疯狂乐透', 'lm', NULL, NULL, 'lm', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 15:23:10', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (427, 4, 12, '', '魔力老虎机', 'mgstk', NULL, NULL, 'mgstk', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:06:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (428, 4, 12, '', '玛丽莲梦露', 'gtsmrln', NULL, NULL, 'gtsmrln', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:06:15', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (429, 4, 12, '', '幸运女士', 'mfrt', NULL, NULL, 'mfrt', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:06:39', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (430, 4, 12, '', '蒙提派森之万世魔星', 'ashlob', NULL, NULL, 'ashlob', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:07:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (431, 4, 12, '', '返利先生', 'mrcb', NULL, NULL, 'mrcb', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:06:55', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (432, 4, 12, '', '海王星王国', 'nk', NULL, NULL, 'nk', '1', 1, 'app/img/admin/game/7d41794108c60915118ba03f341eb75d.jpeg', 0.00, 1, NULL, '2017-12-28 18:17:26', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (433, 4, 12, '', '年年有余', 'nian', NULL, NULL, 'nian', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:07:34', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (434, 4, 12, '', '月亮下的黑豹', 'pmn', NULL, NULL, 'pmn', '1', 1, 'app/img/admin/game/8b076a1c95a2849f066602922cadcaae.jpeg', 0.00, 1, NULL, '2017-12-28 18:36:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (435, 4, 12, '', '企鹅度假', 'pgv', NULL, NULL, 'pgv', '1', 1, 'app/img/admin/game/43d33cfe1b7981fe4b7566a12323fbfc.jpeg', 0.00, 1, NULL, '2017-12-28 18:24:33', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (436, 4, 12, '', '多幅完美21点', 'bjp', NULL, NULL, 'bjp', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:07:47', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (437, 4, 12, '', '粉红豹', 'pnp', NULL, NULL, 'pnp', '1', 1, 'app/img/admin/game/29bc6e454dc488e0dddd8689959daa60.png', 0.00, 1, NULL, '2017-12-27 16:13:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (438, 4, 12, '', '法老王的秘密', 'pst', NULL, NULL, 'pst', '1', 1, 'app/img/admin/game/7d504af0c1ba80299192973aad1fa0cb.jpeg', 0.00, 1, NULL, '2017-12-28 18:15:50', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (439, 4, 12, '', '三只小猪与大灰狼', 'paw', NULL, NULL, 'paw', '1', 1, 'app/img/admin/game/648d8830211222f2c23d1dee18b4b805.jpeg', 0.00, 1, NULL, '2017-12-28 18:28:42', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (440, 4, 12, '', '充裕财富', 'gtspor', NULL, NULL, 'gtspor', '1', 1, 'app/img/admin/game/08a8b663316d70e3dcd4938d65c52238.jpeg', 0.00, 1, NULL, '2017-12-28 18:14:44', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (441, 4, 12, '', '奖金美式轮盘', 'rodz_g', NULL, NULL, 'rodz_g', '1', 1, 'app/img/admin/game/18a3b777a83ba7b4054980e5b5fc52b7.jpeg', 0.00, 1, NULL, '2017-12-28 18:19:45', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (442, 4, 12, '', '奖金欧式轮盘', 'ro_g', NULL, NULL, 'ro_g', '1', 1, 'app/img/admin/game/af50553a349eda86bab9415c394cf43d.jpeg', 0.00, 1, NULL, '2017-12-28 18:20:02', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (443, 4, 12, '', '紫色狂热', 'phot', NULL, NULL, 'phot', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:08:01', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (444, 4, 12, '', '权杖女王', 'qnw', NULL, NULL, 'qnw', '1', 1, 'app/img/admin/game/458db81bbd554a2bd7783bbb976149a1.png', 0.00, 1, NULL, '2017-12-27 16:15:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (445, 4, 12, '', '日日进财', 'ririjc', NULL, NULL, 'ririjc', '1', 1, 'app/img/admin/game/5551e2446d1cc0a1474068b9435d1b91.jpeg', 0.00, 1, NULL, '2017-12-28 18:27:17', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (446, 4, 12, '', '日日生财', 'ririshc', NULL, NULL, 'ririshc', '1', 1, 'app/img/admin/game/5ebdbdf35dfaf6211132ed7098c05164.png', 0.00, 1, NULL, '2017-12-27 16:16:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (447, 4, 12, '', '洛奇', 'rky', NULL, NULL, 'rky', '1', 1, 'app/img/admin/game/a81ffc1b0b779b4c2dd452aa5129000d.jpeg', 0.00, 1, NULL, '2017-12-28 18:22:45', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (448, 4, 12, '', '罗马荣光', 'rng2', NULL, NULL, 'rng2', '1', 1, 'app/img/admin/game/e287a6dfd4a69dc1e7f542612ac3db06.png', 0.00, 1, NULL, '2017-12-27 16:17:09', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (449, 4, 12, '', '野生狩猎', 'sfh', NULL, NULL, 'sfh', '1', 1, 'app/img/admin/game/15da39ade78c7e1378d7ea8305016e67.jpeg', 0.00, 1, NULL, '2017-12-28 18:35:21', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (450, 4, 12, '', '桑巴之舞', 'gtssmbr', NULL, NULL, 'gtssmbr', '1', 1, 'app/img/admin/game/4ab40191a35f317ba1c55f80da21b88e.jpeg', 0.00, 1, NULL, '2017-12-28 18:29:01', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (451, 4, 12, '', '圣诞老人奇袭', 'ssp', NULL, NULL, 'ssp', '1', 1, 'app/img/admin/game/c3235d17e086715de8b6d70cd3d64a38.jpeg', 0.00, 1, NULL, '2017-12-28 18:30:10', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (452, 4, 12, '', '亚马逊之谜', 'samz', NULL, NULL, 'samz', '1', 1, 'app/img/admin/game/58f27400bdc4c6d418d61aeec549a1a4.jpeg', 0.00, 1, NULL, '2017-12-28 18:34:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (453, 4, 12, '', '神秘夏洛克', 'shmst', NULL, NULL, 'shmst', '1', 1, 'app/img/admin/game/8aae7d8a1399a4f409c5d2f5d810754b.jpeg', 0.00, 1, NULL, '2017-12-28 18:29:53', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (454, 4, 12, '', '四象', 'sx', NULL, NULL, 'sx', '1', 1, 'app/img/admin/game/92433bda582bd9da59cfbe0cb5500d5b.png', 0.00, 1, NULL, '2017-12-27 16:18:28', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (455, 4, 12, '', '忍者风云', 'sis', NULL, NULL, 'sis', '1', 1, 'app/img/admin/game/7b1ea2d93c46fb012d1758d1269e8655.jpeg', 0.00, 1, NULL, '2017-12-28 18:26:45', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (456, 4, 12, '', '银弹', 'sib', NULL, NULL, 'sib', '1', 1, 'app/img/admin/game/725777e9c88e9740e2977d35df9785d2.png', 0.00, 1, NULL, '2017-12-27 16:19:19', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (457, 4, 12, '', '辛巴达金航记', 'ashsbd', NULL, NULL, 'ashsbd', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:08:17', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (458, 4, 12, '', '欧莱里之黄金大田', 'srcg', NULL, NULL, 'srcg', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:08:32', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (459, 4, 12, '', '幸运直击', 'sol', NULL, NULL, 'sol', '1', 1, 'app/img/admin/game/d8fb891903134ea755a98c20bc53215c.jpeg', 0.00, 1, NULL, '2017-12-28 18:33:30', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (460, 4, 12, '', '孙悟空', 'gtsswk', NULL, NULL, 'gtsswk', '1', 1, 'app/img/admin/game/ed2e3e064f04f3228608f549d8e9a391.png', 0.00, 1, NULL, '2017-12-27 16:20:38', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (461, 4, 12, '', '超级狮子', 'slion', NULL, NULL, 'slion', '1', 1, 'app/img/admin/game/249cf95f2fc478a58097682276048360.png', 0.00, 1, NULL, '2017-12-27 16:21:36', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (462, 4, 12, '', '甜蜜派对', 'cnpr', NULL, NULL, 'cnpr', '1', 1, 'app/img/admin/game/30d2597813421fdab840fe795fb6d70a.png', 0.00, 1, NULL, '2017-12-27 16:22:20', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (463, 4, 12, '', '泰国梦天堂', 'tpd2', NULL, NULL, 'tpd2', '', NULL, NULL, 0.00, 1, NULL, NULL, 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (464, 4, 12, '', '泰国佛寺', 'tht', NULL, NULL, 'tht', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:08:49', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (465, 4, 12, '', '海上寻宝', 'dcv', NULL, NULL, 'dcv', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:09:02', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (466, 4, 12, '', '水晶鞋', 'ashglss', NULL, NULL, 'ashglss', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:09:18', 100, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (467, 4, 12, '', '大明帝国', 'gtsgme', NULL, NULL, 'gtsgme', '1', 1, 'app/img/admin/game/d9a8a66c0371f3f38a7df53321eb3d03.png', 0.00, 1, NULL, '2017-12-27 16:23:48', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (468, 4, 12, '', '恋爱之船', 'lvb', NULL, NULL, 'lvb', '1', 1, 'app/img/admin/game/8c2f95523a0d8d19f58fbc74cb7ea44f.jpeg', 0.00, 1, NULL, '2017-12-28 18:21:43', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (469, 4, 12, '', '唐吉诃德', 'donq', NULL, NULL, 'donq', '1', 1, 'app/img/admin/game/01bfaba1acb2fd00addfae0fc62d57a3.jpeg', 0.00, 1, NULL, '2017-12-28 18:30:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (470, 4, 12, '', '三个火枪手', 'tmqd', NULL, NULL, 'tmqd', '1', 1, 'app/img/admin/game/80819e7ed86bce840b05d9f43a8b0e37.jpeg', 0.00, 1, NULL, '2017-12-28 18:27:37', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (471, 4, 12, '', '三门问题', 'ashtmd', NULL, NULL, 'ashtmd', '1', 1, 'app/img/admin/game/3635d2b85dcb4cfb2ec26a3720a52da7.jpeg', 0.00, 1, NULL, '2017-12-28 18:28:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (472, 4, 12, '', '捍卫战士', 'topg', NULL, NULL, 'topg', '1', 1, 'app/img/admin/game/8e107b295743ba4bbe35eb277eaa4175.jpeg', 0.00, 1, NULL, '2017-12-28 18:17:49', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (473, 4, 12, '', '顶级王牌名人游戏', 'ttc', NULL, NULL, 'ttc', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:09:29', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (474, 4, 12, '', '三倍猴子', 'trpmnk', NULL, NULL, 'trpmnk', '1', 1, 'app/img/admin/game/2c3981f1b3e71eabde0723508dc62031.png', 0.00, 1, NULL, '2017-12-27 16:25:44', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (475, 4, 12, '', '开心假期', 'er', NULL, NULL, 'er', '1', 1, 'app/img/admin/game/e19c2dc01387ab9d8e200f31ee2f3f50.jpeg', 0.00, 1, NULL, '2017-12-27 16:26:32', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (476, 4, 12, '', '开心假期豪華版', 'vcstd', NULL, NULL, 'vcstd', '1', 1, 'app/img/admin/game/aff104262e9395506e3af1aa5600ad6d.png', 0.00, 1, NULL, '2017-12-27 16:26:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (477, 4, 12, '', '维京狂热', 'gts52', NULL, NULL, 'gts52', '', NULL, NULL, 0.00, 0, NULL, '2017-12-28 18:09:43', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (478, 4, 12, '', '白狮王', 'whk', NULL, NULL, 'whk', '1', 1, 'app/img/admin/game/e01166049f94546765981eb5af89b3a8.jpeg', 0.00, 1, NULL, '2017-12-28 18:13:30', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (479, 4, 12, '', '野生动物大世界', 'gtswg', NULL, NULL, 'gtswg', '1', 1, 'app/img/admin/game/9a30509a09fd289ecaaf3586c2f486ea.jpeg', 0.00, 1, NULL, '2017-12-28 18:34:24', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (480, 4, 12, '', '野生世界：北极大冒险', 'ashwgaa', NULL, NULL, 'ashwgaa', '1', 1, 'app/img/admin/game/eafcbbd9f78e40ea41526a3fb3d2dee2.jpeg', 0.00, 1, NULL, '2018-02-05 16:57:36', 101, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (481, 4, 12, '', '狂野精灵', 'wis', NULL, NULL, 'wis', '1', 1, 'app/img/admin/game/bc79929a6d3969d2dc3ede4e10a16abb.jpeg', 0.00, 1, NULL, '2017-12-28 18:21:11', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (482, 4, 12, '', '纯金之翼', 'gtswng', NULL, NULL, 'gtswng', '1', 1, 'app/img/admin/game/ad195163635dadb039d8c6c43fdeef88.jpeg', 0.00, 1, NULL, '2017-12-28 18:14:57', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (483, 4, 12, '', '舞龙', 'wlg', NULL, NULL, 'wlg', '1', 1, 'app/img/admin/game/f651ee144f3f0ed0264a26aebf15be48.png', 0.00, 1, NULL, '2017-12-27 16:28:30', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (484, 4, 12, '', '五路财神', 'wlcsh', NULL, NULL, 'wlcsh', '1', 1, 'app/img/admin/game/e1a35063f4333eaffb5d5aa53e539a72.jpeg', 0.00, 1, NULL, '2017-12-27 16:29:09', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (485, 4, 12, '', '招财进宝', 'zcjb', NULL, NULL, 'zcjb', '1', 1, 'app/img/admin/game/6b9850e205047d0018ae32a0da3e5994.jpeg', 0.00, 1, NULL, '2017-12-27 16:30:05', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (486, 4, 12, '', '招财进宝积宝财池', 'zcjbjp', NULL, NULL, 'zcjbjp', '1', 1, 'app/img/admin/game/fc04e0069c2806b4a052ce0a9bf79cfb.png', 0.00, 1, NULL, '2017-12-27 16:30:33', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (487, 4, 12, '', '招财童子', 'zctz', NULL, NULL, 'zctz', '1', 1, 'app/img/admin/game/d592512a82c11bd2d458418bc16e0ee8.jpeg', 0.00, 1, NULL, '2017-12-28 18:37:03', 100, 0, 1, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (488, 4, 1, '', '真人7席百家乐', NULL, NULL, NULL, '7bal', '9', 1, NULL, 0.00, 1, '2017-12-16 17:10:10', '2017-12-16 17:11:56', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (489, 4, 1, '', '真人百家乐', NULL, NULL, NULL, 'bal', '9', 1, NULL, 0.00, 1, '2017-12-16 17:11:43', '2017-12-16 17:11:43', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (490, 4, 1, '', '真人21点', NULL, NULL, NULL, 'bjl', '9', 1, NULL, 0.00, 1, '2017-12-16 17:12:50', '2017-12-16 17:12:50', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (491, 4, 1, '', '真人法式轮盘', NULL, NULL, NULL, 'rofl', '9', 1, NULL, 0.00, 1, '2017-12-16 17:13:37', '2017-12-16 17:13:37', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (492, 4, 1, '', '真人轮盘', NULL, NULL, NULL, 'rol', '9', 1, NULL, 0.00, 1, '2017-12-16 17:14:13', '2017-12-16 17:14:13', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (493, 1, 2, '', '百家乐', '3001', NULL, NULL, '3001', '9', 1, NULL, 0.00, 1, '2017-12-16 17:19:20', '2017-12-16 17:19:34', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (494, 1, 2, '', '二八杠', '3002', NULL, NULL, '3002', '9', 1, NULL, 0.00, 1, '2017-12-16 17:20:14', '2017-12-16 17:20:24', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (495, 1, 2, '', '龙虎斗', '3003', NULL, NULL, '3003', '9', 1, NULL, 0.00, 1, '2017-12-16 17:20:47', '2017-12-16 17:22:12', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (496, 1, 2, '', '三公', '3005', NULL, NULL, '3005', '9', 1, NULL, 0.00, 1, '2017-12-16 17:21:16', '2017-12-16 17:22:24', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (497, 1, 2, '', '温州牌九', '3006', NULL, NULL, '3006', '9', 1, NULL, 0.00, 1, '2017-12-16 17:21:58', '2017-12-16 17:22:37', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (498, 1, 2, '', '轮盘', '3007', NULL, NULL, '3007', '9', 1, NULL, 0.00, 1, '2017-12-16 17:23:17', '2017-12-16 17:24:10', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (499, 1, 2, '', '骰宝', '3008', NULL, NULL, '3008', '9', 1, NULL, 0.00, 1, '2017-12-16 17:23:54', '2017-12-16 17:23:54', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (500, 1, 2, '', '德州扑克', '3010', NULL, NULL, '3010', '9', 1, NULL, 0.00, 1, '2017-12-16 17:24:41', '2017-12-16 17:24:41', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (501, 1, 2, '', '色碟', '3011', NULL, NULL, '3011', '9', 1, NULL, 0.00, 1, '2017-12-16 17:25:22', '2017-12-16 17:25:22', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (502, 1, 2, '', '牛牛', '3012', NULL, NULL, '3012', '9', 1, NULL, 0.00, 1, '2017-12-16 17:25:51', '2017-12-16 17:26:03', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (503, 1, 2, '', '无限21点', '3014', NULL, NULL, '3014', '9', 1, NULL, 0.00, 1, '2017-12-16 17:26:30', '2017-12-16 17:26:30', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (504, 1, 2, '', '番摊', '3015', NULL, NULL, '3015', '9', 1, NULL, 0.00, 1, '2017-12-16 17:27:06', '2017-12-16 17:27:06', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (505, 1, 2, '', '鱼虾蟹', '3016', NULL, NULL, '3016', '9', 1, NULL, 0.00, 1, '2017-12-16 17:27:55', '2017-12-16 17:27:55', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (506, 5, 3, '', '百家乐01', NULL, NULL, NULL, 'Baccarat_1', '9', 1, NULL, 0.00, 1, '2017-12-16 17:31:47', '2017-12-16 17:32:00', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (507, 5, 3, '', '百家乐02', NULL, NULL, NULL, 'Baccarat_2', '9', 1, NULL, 0.00, 1, '2017-12-16 17:32:46', '2017-12-16 17:32:46', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (508, 5, 3, '', '百家乐03', NULL, NULL, NULL, 'Baccarat_3', '9', 1, NULL, 0.00, 1, '2017-12-16 17:33:17', '2017-12-16 17:33:17', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (509, 5, 3, '', '百家乐04', NULL, NULL, NULL, 'Baccarat_4', '9', 1, NULL, 0.00, 1, '2017-12-16 17:33:48', '2017-12-16 17:33:48', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (510, 5, 3, '', '百家乐05', NULL, NULL, NULL, 'Baccarat_5 ', '9', 1, NULL, 0.00, 1, '2017-12-16 17:34:19', '2017-12-16 17:34:19', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (511, 5, 3, '', '百宛', NULL, NULL, NULL, 'Baccarat_6', '9', 1, NULL, 0.00, 1, '2017-12-16 17:34:40', '2017-12-16 17:46:55', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (512, 5, 3, '', '百家乐06', NULL, NULL, NULL, 'Baccarat_6', '9', 1, NULL, 0.00, 1, '2017-12-16 17:35:02', '2017-12-16 17:35:02', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (513, 5, 3, '', '星级百家乐01', NULL, NULL, NULL, 'Super_Baccarat_ 1', '9', 1, NULL, 0.00, 1, '2017-12-16 17:35:38', '2017-12-16 17:35:38', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (514, 5, 3, '', '星级百家乐02', NULL, NULL, NULL, 'Super_Baccarat_ 2', '9', 1, NULL, 0.00, 1, '2017-12-16 17:36:12', '2017-12-16 17:36:12', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (515, 5, 3, '', '星级百家乐03', NULL, NULL, NULL, 'Super_Baccarat_ 3', '9', 1, NULL, 0.00, 1, '2017-12-16 17:36:50', '2017-12-16 17:36:50', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (516, 5, 3, '', '星级百家乐04', NULL, NULL, NULL, 'Super_Baccarat_ 4', '9', 1, NULL, 0.00, 1, '2017-12-16 17:37:24', '2017-12-16 17:37:24', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (517, 5, 3, '', '星级百家乐05', NULL, NULL, NULL, 'Super_Baccarat_ 5', '9', 1, NULL, 0.00, 1, '2017-12-16 17:37:56', '2017-12-16 17:37:56', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (518, 5, 3, '', '星级百家乐06', NULL, NULL, NULL, 'Super_Baccarat_ 6', '9', 1, NULL, 0.00, 1, '2017-12-16 17:38:30', '2017-12-16 17:38:30', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (519, 5, 3, '', '龙虎', NULL, NULL, NULL, 'Dragon_Tiger', '9', 1, NULL, 0.00, 1, '2017-12-16 17:39:00', '2017-12-16 17:39:00', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (520, 5, 3, '', '骰宝', NULL, NULL, NULL, 'Sic_Bo', '9', 1, NULL, 0.00, 1, '2017-12-16 17:39:37', '2017-12-16 17:39:37', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (521, 5, 3, '', '轮盘', NULL, NULL, NULL, 'Roulette', '9', 1, NULL, 0.00, 1, '2017-12-16 17:40:13', '2017-12-16 17:40:13', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (522, 5, 3, '', '斗牛', NULL, NULL, NULL, 'Bullfight', '9', 1, NULL, 0.00, 1, '2017-12-16 17:41:20', '2017-12-16 17:41:20', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (523, 5, 3, '', '三公', NULL, NULL, NULL, '3_Card_Poker', '9', 1, NULL, 0.00, 1, '2017-12-16 17:41:51', '2017-12-16 17:41:51', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (524, 5, 3, '', '雀九', NULL, NULL, NULL, 'Mahjong', '9', 1, NULL, 0.00, 1, '2017-12-16 17:42:37', '2017-12-16 17:42:37', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (525, 5, 3, '', '申博真人娱乐馆', NULL, NULL, NULL, 'Sunbet_Lobby', '9', 1, NULL, 0.00, 1, '2017-12-16 17:43:49', '2017-12-16 17:43:49', 1, 0, 0, 0.00, 0, 1);
INSERT INTO `def_games` VALUES (526, 5, 3, '', '自选多合一', NULL, NULL, NULL, 'All-In-One_Game', '9', 1, NULL, 0.00, 1, '2017-12-16 17:45:07', '2017-12-16 17:45:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (527, 5, 3, '', '咪牌百家乐', NULL, NULL, NULL, 'Bid_Baccarat', '9', 1, NULL, 0.00, 1, '2017-12-16 17:46:04', '2017-12-16 17:46:04', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (528, 5, 6, '', 'GD真人娱乐馆', NULL, NULL, NULL, 'Gold_Deluxe_Lobby', '9', 1, NULL, 0.00, 1, '2017-12-16 17:49:42', '2017-12-16 17:49:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (529, 5, 6, '', '传奇百家乐', NULL, NULL, NULL, 'Traditional_Baccarat', '9', 1, NULL, 0.00, 1, '2017-12-16 17:50:15', '2017-12-16 17:50:15', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (530, 5, 6, '', '3D百家乐', NULL, NULL, NULL, '3D_Baccarat', '9', 1, NULL, 0.00, 1, '2017-12-16 17:50:46', '2017-12-16 17:50:46', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (531, 5, 6, '', '多彩百家乐', NULL, NULL, NULL, 'Multi_Bet_Baccarat', '9', 1, NULL, 0.00, 1, '2017-12-16 17:51:18', '2017-12-16 17:51:18', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (532, 5, 6, '', '多台下注', NULL, NULL, NULL, 'Multi_Table', '9', 1, NULL, 0.00, 1, '2017-12-16 17:51:54', '2017-12-16 17:51:54', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (533, 5, 6, '', '骰宝', NULL, NULL, NULL, 'Sic_Bo', '9', 1, NULL, 0.00, 1, '2017-12-16 17:52:24', '2017-12-16 17:52:24', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (534, 5, 6, '', '轮盘', NULL, NULL, NULL, 'Roulette', '9', 1, NULL, 0.00, 1, '2017-12-16 17:52:52', '2017-12-16 17:52:52', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (535, 4, 1, '', '传奇百家乐01', NULL, NULL, NULL, 'Traditional_Baccarat_01', '9', 1, NULL, 0.00, 1, '2017-12-16 17:53:29', '2017-12-16 17:53:29', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (536, 5, 6, '', '传奇百家乐02', NULL, NULL, NULL, 'Traditional_Baccarat_02', '9', 1, NULL, 0.00, 1, '2017-12-16 17:54:09', '2017-12-16 17:54:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (537, 5, 6, '', '传奇百家乐03', NULL, NULL, NULL, 'Traditional_Baccarat_03', '9', 1, NULL, 0.00, 1, '2017-12-16 17:54:43', '2017-12-16 17:54:43', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (538, 5, 6, '', '传奇百家乐04', NULL, NULL, NULL, 'Traditional_Baccarat_04', '9', 1, NULL, 0.00, 1, '2017-12-16 17:55:30', '2017-12-16 17:55:30', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (539, 5, 6, '', '传奇百家乐05', NULL, NULL, NULL, 'Traditional_Baccarat_05', '9', 1, NULL, 0.00, 1, '2017-12-16 17:56:07', '2017-12-16 17:56:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (540, 5, 6, '', '传奇百家乐06', NULL, NULL, NULL, 'Traditional_Baccarat_06', '9', 1, NULL, 0.00, 1, '2017-12-16 17:56:49', '2017-12-16 17:56:49', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (541, 5, 6, '', '传奇百家乐07', NULL, NULL, NULL, 'Traditional_Baccarat_07', '9', 1, NULL, 0.00, 1, '2017-12-16 17:57:29', '2017-12-16 17:57:29', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (542, 5, 6, '', '传奇百家乐08', NULL, NULL, NULL, 'Traditional_Baccarat_08', '9', 1, NULL, 0.00, 1, '2017-12-16 17:58:09', '2017-12-16 17:58:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (543, 5, 6, '', '3D百家乐01', NULL, NULL, NULL, '3D_Baccarat_01', '9', 1, NULL, 0.00, 1, '2017-12-16 17:58:49', '2017-12-16 17:58:49', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (544, 5, 6, '', '3D百家乐02', NULL, NULL, NULL, '3D_Baccarat_02', '9', 1, NULL, 0.00, 1, '2017-12-16 17:59:43', '2017-12-16 17:59:43', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (545, 5, 6, '', '3D百家乐03', NULL, NULL, NULL, '3D_Baccarat_03', '9', 1, NULL, 0.00, 1, '2017-12-16 18:00:29', '2017-12-16 18:00:29', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (546, 5, 6, '', '3D百家乐04', NULL, NULL, NULL, '3D_Baccarat_04', '9', 1, NULL, 0.00, 1, '2017-12-16 18:00:59', '2017-12-16 18:00:59', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (547, 5, 6, '', '3D百家乐05', NULL, NULL, NULL, '3D_Baccarat_05', '9', 1, NULL, 0.00, 1, '2017-12-16 18:01:30', '2017-12-16 18:01:30', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (548, 5, 6, '', '3D百家乐06', NULL, NULL, NULL, '3D_Baccarat_06', '9', 1, NULL, 0.00, 1, '2017-12-16 18:01:56', '2017-12-16 18:01:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (549, 5, 6, '', '3D百家乐07', NULL, NULL, NULL, '3D_Baccarat_07', '9', 1, NULL, 0.00, 1, '2017-12-16 18:02:40', '2017-12-16 18:02:40', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (550, 5, 6, '', '3D百家乐08', NULL, NULL, NULL, '3D_Baccarat_08', '9', 1, NULL, 0.00, 1, '2017-12-16 18:03:16', '2017-12-16 18:03:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (551, 5, 6, '', '多彩百家乐01', NULL, NULL, NULL, 'Multi_Bet_Baccarat_01', '9', 1, NULL, 0.00, 1, '2017-12-16 18:03:50', '2017-12-16 18:04:02', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (552, 5, 6, '', '多彩百家乐02', NULL, NULL, NULL, 'Multi_Bet_Baccarat_02', '9', 1, NULL, 0.00, 1, '2017-12-16 18:04:52', '2017-12-16 18:04:52', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (553, 5, 6, '', '多彩百家乐03', NULL, NULL, NULL, 'Multi_Bet_Baccarat_03', '9', 1, NULL, 0.00, 1, '2017-12-16 18:05:33', '2017-12-16 18:05:33', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (554, 5, 6, '', '多彩百家乐04', NULL, NULL, NULL, 'Multi_Bet_Baccarat_04', '9', 1, NULL, 0.00, 1, '2017-12-16 18:06:10', '2017-12-16 18:06:10', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (555, 5, 6, '', '多彩百家乐05', NULL, NULL, NULL, 'Multi_Bet_Baccarat_05', '9', 1, NULL, 0.00, 1, '2017-12-16 18:06:45', '2017-12-16 18:06:45', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (556, 5, 6, '', '多彩百家乐06', NULL, NULL, NULL, 'Multi_Bet_Baccarat_06', '9', 1, NULL, 0.00, 1, '2017-12-16 18:07:22', '2017-12-16 18:07:22', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (557, 5, 6, '', '多彩百家乐07', NULL, NULL, NULL, 'Multi_Bet_Baccarat_07', '9', 1, NULL, 0.00, 1, '2017-12-16 18:07:56', '2017-12-16 18:07:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (558, 5, 6, '', '多彩百家乐08', NULL, NULL, NULL, 'Multi_Bet_Baccarat_08', '9', 1, NULL, 0.00, 1, '2017-12-16 18:08:31', '2017-12-16 18:08:31', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (559, 2, 4, '', '百家乐单人Playboy桌', NULL, NULL, NULL, '1054', '9', 1, NULL, 0.00, 1, '2017-12-16 18:12:03', '2017-12-16 18:17:44', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (560, 2, 4, '', '轮盘单人Playboy桌', NULL, NULL, NULL, '1018', '9', 1, NULL, 0.00, 1, '2017-12-16 18:12:41', '2017-12-16 18:12:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (561, 2, 4, '', '百家乐单人桌', NULL, NULL, NULL, '1210', '9', 1, NULL, 0.00, 1, '2017-12-16 18:13:18', '2017-12-16 18:13:18', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (562, 2, 4, '', '轮盘单人桌', NULL, NULL, NULL, '1323', '9', 1, NULL, 0.00, 1, '2017-12-16 18:13:51', '2017-12-16 18:13:51', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (563, 2, 4, '', '骰宝', NULL, NULL, NULL, '1238', '9', 1, NULL, 0.00, 1, '2017-12-16 18:14:41', '2017-12-16 18:14:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (564, 2, 4, '', '百家乐多人桌', NULL, NULL, NULL, '1172', '9', 1, NULL, 0.00, 1, '2017-12-16 18:15:12', '2017-12-16 18:17:14', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (565, 2, 4, '', '轮盘多人桌', NULL, NULL, NULL, '1298', '9', 1, NULL, 0.00, 1, '2017-12-16 18:15:46', '2017-12-16 18:15:46', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (566, 2, 4, '', '轮盘多人Playboy桌', NULL, NULL, NULL, '1352', '9', 1, NULL, 0.00, 1, '2017-12-16 18:16:23', '2017-12-16 18:16:23', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (567, 2, 4, '', '百家乐多人Playboy桌', NULL, NULL, NULL, '1422', '9', 1, NULL, 0.00, 1, '2017-12-16 18:16:46', '2017-12-16 18:17:25', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (568, 1, 7, '', '足球', NULL, NULL, NULL, 'FK', '9', 1, NULL, 0.00, 1, '2017-12-16 18:19:38', '2017-12-16 18:19:38', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (569, 1, 7, '', '篮球', NULL, NULL, NULL, 'BK', '9', 1, NULL, 0.00, 1, '2017-12-16 18:20:48', '2017-12-16 18:20:48', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (570, 1, 7, '', '美式足球', NULL, NULL, NULL, 'FK', '9', 1, NULL, 0.00, 1, '2017-12-16 18:21:17', '2017-12-16 18:21:17', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (571, 1, 7, '', '冰球', NULL, NULL, NULL, 'IH', '9', 1, NULL, 0.00, 1, '2017-12-16 18:21:42', '2017-12-16 18:21:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (572, 1, 7, '', '棒球', NULL, NULL, NULL, 'BS', '9', 1, NULL, 0.00, 1, '2017-12-16 18:22:09', '2017-12-16 18:22:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (573, 1, 7, '', '网球', NULL, NULL, NULL, 'TN', '9', 1, NULL, 0.00, 1, '2017-12-16 18:22:37', '2017-12-16 18:22:37', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (574, 1, 7, '', '其他', NULL, NULL, NULL, 'F1', '9', 1, NULL, 0.00, 1, '2017-12-16 18:23:07', '2017-12-16 18:23:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (575, 1, 7, '', '冠军赛', NULL, NULL, NULL, 'SP', '9', 1, NULL, 0.00, 1, '2017-12-16 18:23:35', '2017-12-16 18:24:18', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (576, 1, 7, '', '混合过关', NULL, NULL, NULL, 'CB', '9', 1, NULL, 0.00, 1, '2017-12-16 18:24:03', '2017-12-16 18:24:03', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (577, 8, 8, '', '足球', '1', NULL, NULL, '1', '9', 1, NULL, 0.00, 1, '2017-12-16 18:37:02', '2017-12-16 18:37:02', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (578, 8, 8, '', '篮球', '2', NULL, NULL, '2', '9', 1, NULL, 0.00, 1, '2017-12-16 18:38:53', '2017-12-16 18:38:53', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (579, 8, 8, '', '足球', '3', NULL, NULL, '3', '9', 1, NULL, 0.00, 1, '2017-12-16 18:39:27', '2017-12-16 18:39:27', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (580, 8, 8, '', '冰上曲棍球', '4', NULL, NULL, '4', '9', 1, NULL, 0.00, 1, '2017-12-16 18:39:55', '2017-12-16 18:39:55', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (581, 4, 1, '', '网球', '5', NULL, NULL, '5', '9', 1, NULL, 0.00, 1, '2017-12-16 18:40:15', '2017-12-16 18:40:15', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (582, 8, 8, '', '排球', '6', NULL, NULL, '6', '9', 1, NULL, 0.00, 1, '2017-12-16 18:41:09', '2017-12-16 18:41:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (583, 8, 8, '', '台球', '7', NULL, NULL, '7', '9', 1, NULL, 0.00, 1, '2017-12-16 18:41:41', '2017-12-16 18:41:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (584, 8, 8, '', '棒球', '8', NULL, NULL, '8', '9', 1, NULL, 0.00, 1, '2017-12-16 18:42:13', '2017-12-16 18:42:13', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (585, 8, 8, '', '羽毛球', '9', NULL, NULL, '9', '9', 1, NULL, 0.00, 1, '2017-12-16 18:42:46', '2017-12-16 18:42:46', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (586, 8, 8, '', '高尔夫', '10', NULL, NULL, '10', '9', 1, NULL, 0.00, 1, '2017-12-16 18:43:09', '2017-12-16 18:45:12', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (587, 8, 8, '', '赛车', '11', NULL, NULL, '11', '9', 1, NULL, 0.00, 1, '2017-12-16 18:45:51', '2017-12-16 18:45:51', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (588, 8, 8, '', '游泳', '12', NULL, NULL, '12', '9', 1, NULL, 0.00, 1, '2017-12-16 18:46:26', '2017-12-16 18:46:26', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (589, 8, 8, '', '政治', '13', NULL, NULL, '13', '9', 1, NULL, 0.00, 1, '2017-12-16 18:46:59', '2017-12-16 18:46:59', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (590, 8, 8, '', '水球', '14', NULL, NULL, '14', '9', 1, NULL, 0.00, 1, '2017-12-16 18:47:33', '2017-12-16 18:47:33', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (591, 8, 8, '', '跳水', '15', NULL, NULL, '15', '9', 1, NULL, 0.00, 1, '2017-12-16 19:08:46', '2017-12-16 19:09:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (592, 8, 8, '', '拳击', '16', NULL, NULL, '16', '9', 1, NULL, 0.00, 1, '2017-12-16 19:09:20', '2017-12-16 19:09:31', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (593, 8, 8, '', '射箭', '17', NULL, NULL, '17', '9', 1, NULL, 0.00, 1, '2017-12-16 19:10:30', '2017-12-16 19:10:30', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (594, 8, 8, '', '乒乓球', '18', NULL, NULL, '18', '9', 1, NULL, 0.00, 1, '2017-12-16 19:11:20', '2017-12-16 19:11:20', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (595, 8, 8, '', '举重', '19', NULL, NULL, '19', '9', 1, NULL, 0.00, 1, '2017-12-16 19:11:42', '2017-12-16 19:11:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (596, 8, 8, '', '皮划艇', '20', NULL, NULL, '20', '9', 1, NULL, 0.00, 1, '2017-12-16 19:12:17', '2017-12-16 19:12:17', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (597, 8, 8, '', '体操', '21', NULL, NULL, '21', '9', 1, NULL, 0.00, 1, '2017-12-16 19:12:40', '2017-12-16 19:12:40', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (598, 8, 8, '', '田径', '22', NULL, NULL, '22', '9', 1, NULL, 0.00, 1, '2017-12-16 19:13:07', '2017-12-16 19:13:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (599, 8, 8, '', '马术', '23', NULL, NULL, '23', '9', 1, NULL, 0.00, 1, '2017-12-16 19:13:32', '2017-12-16 19:13:32', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (600, 8, 8, '', '手球', '24', NULL, NULL, '24', '9', 1, NULL, 0.00, 1, '2017-12-16 19:13:56', '2017-12-16 19:13:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (601, 8, 8, '', '飞镖', '25', NULL, NULL, '25', '9', 1, NULL, 0.00, 1, '2017-12-16 19:14:20', '2017-12-16 19:14:20', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (602, 8, 8, '', '橄榄球', '26', NULL, NULL, '26', '9', 1, NULL, 0.00, 1, '2017-12-16 19:14:44', '2017-12-16 19:14:44', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (603, 8, 8, '', '板球', '27', NULL, NULL, '27', '9', 1, NULL, 0.00, 1, '2017-12-16 19:15:10', '2017-12-16 19:15:10', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (604, 8, 8, '', '曲棍球', '28', NULL, NULL, '28', '9', 1, NULL, 0.00, 1, '2017-12-16 19:15:29', '2017-12-16 19:15:29', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (605, 8, 8, '', '冬季运动', '29', NULL, NULL, '29', '9', 1, NULL, 0.00, 1, '2017-12-16 19:15:50', '2017-12-16 19:15:50', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (606, 8, 8, '', '壁球', '30', NULL, NULL, '30', '9', 1, NULL, 0.00, 1, '2017-12-16 19:16:16', '2017-12-16 19:16:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (607, 8, 8, '', '娱乐', '31', NULL, NULL, '31', '9', 1, NULL, 0.00, 1, '2017-12-16 19:16:37', '2017-12-16 19:16:37', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (608, 8, 8, '', '网前球', '32', NULL, NULL, '32', '9', 1, NULL, 0.00, 1, '2017-12-16 19:16:57', '2017-12-16 19:16:57', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (609, 8, 8, '', '骑自行车', '33', NULL, NULL, '33', '9', 1, NULL, 0.00, 1, '2017-12-16 19:17:21', '2017-12-16 19:17:21', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (610, 8, 8, '', '铁人三项', '41', NULL, NULL, '41', '9', 1, NULL, 0.00, 1, '2017-12-16 19:17:51', '2017-12-16 19:17:51', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (611, 8, 8, '', '摔跤', '42', NULL, NULL, '42', '9', 1, NULL, 0.00, 1, '2017-12-16 19:18:12', '2017-12-16 19:18:12', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (612, 8, 8, '', '电子竞技', '43', NULL, NULL, '43', '9', 1, NULL, 0.00, 1, '2017-12-16 19:18:35', '2017-12-16 19:18:35', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (613, 8, 8, '', '泰拳', '44', NULL, NULL, '44', '9', 1, NULL, 0.00, 1, '2017-12-16 19:18:56', '2017-12-16 19:18:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (614, 8, 8, '', '板球游戏', '50', NULL, NULL, '50', '9', 1, NULL, 0.00, 1, '2017-12-16 19:19:24', '2017-12-16 19:19:24', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (615, 8, 8, '', '其他运动', '99', NULL, NULL, '99', '9', 1, NULL, 0.00, 1, '2017-12-16 19:19:44', '2017-12-16 19:27:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (616, 8, 8, '', '混合足球 Mix Parlay', '99MP', NULL, NULL, '99MP', '9', 1, NULL, 0.00, 1, '2017-12-16 19:20:16', '2017-12-16 19:20:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (617, 8, 8, '', '赛马', '151', NULL, NULL, '151', '9', 1, NULL, 0.00, 1, '2017-12-16 19:20:37', '2017-12-16 19:20:37', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (618, 8, 8, '', '灰狗', '152', NULL, NULL, '152', '9', 1, NULL, 0.00, 1, '2017-12-16 19:20:59', '2017-12-16 19:20:59', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (619, 8, 8, '', '马具', '153', NULL, NULL, '153', '9', 1, NULL, 0.00, 1, '2017-12-16 19:21:21', '2017-12-16 19:21:21', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (620, 8, 8, '', '赛马固定赔率', '154', NULL, NULL, '154', '9', 1, NULL, 0.00, 1, '2017-12-16 19:21:43', '2017-12-16 19:21:43', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (621, 8, 8, '', '数字游戏', '161', NULL, NULL, '161', '9', 1, NULL, 0.00, 1, '2017-12-16 19:22:07', '2017-12-16 19:22:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (622, 8, 8, '', '虚拟足球', '180', NULL, NULL, '180', '9', 1, NULL, 0.00, 1, '2017-12-16 19:22:32', '2017-12-16 19:22:32', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (623, 8, 8, '', '虚拟赛马', '181', NULL, NULL, '181', '9', 1, NULL, 0.00, 1, '2017-12-16 19:23:01', '2017-12-16 19:23:01', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (624, 8, 8, '', '虚拟灵狮', '182', NULL, NULL, '182', '9', 1, NULL, 0.00, 1, '2017-12-16 19:23:32', '2017-12-16 19:23:32', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (625, 8, 8, '', '虚拟赛道', '183', NULL, NULL, '183', '9', 1, NULL, 0.00, 1, '2017-12-16 19:23:55', '2017-12-16 19:23:55', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (626, 8, 8, '', '虚拟 F1', '184', NULL, NULL, '184', '9', 1, NULL, 0.00, 1, '2017-12-16 19:24:16', '2017-12-16 19:24:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (627, 8, 8, '', '虚拟自行车', '185', NULL, NULL, '185', '9', 1, NULL, 0.00, 1, '2017-12-16 19:24:42', '2017-12-16 19:24:42', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (628, 8, 8, '', '虚拟网球', '186', NULL, NULL, '186', '9', 1, NULL, 0.00, 1, '2017-12-16 19:25:07', '2017-12-16 19:25:07', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (629, 8, 8, '', '基诺', '202', NULL, NULL, '202', '9', 1, NULL, 0.00, 1, '2017-12-16 19:25:28', '2017-12-16 19:25:28', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (630, 8, 8, '', '赌场', '251', NULL, NULL, '251', '9', 1, NULL, 0.00, 1, '2017-12-16 19:25:52', '2017-12-16 19:27:23', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (631, 8, 8, '', 'RNG 游戏', '208', NULL, NULL, '208', '9', 1, NULL, 0.00, 1, '2017-12-16 19:26:27', '2017-12-16 19:26:27', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (632, 8, 8, '', '迷你游戏', '209', NULL, NULL, '209', '9', 1, NULL, 0.00, 1, '2017-12-16 19:26:48', '2017-12-16 19:26:48', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (633, 8, 8, '', '移动', '210', NULL, NULL, '210', '9', 1, NULL, 0.00, 1, '2017-12-16 19:27:09', '2017-12-16 19:27:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (634, 1, 13, '', '六合彩', 'LT', NULL, NULL, 'LT', '9', 1, NULL, 0.00, 1, '2017-12-16 19:30:47', '2017-12-16 19:30:47', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (635, 1, 13, '', 'BB 竞速六合彩', 'BBQL', NULL, NULL, 'BBQL', '9', 1, NULL, 0.00, 1, '2017-12-16 19:31:14', '2017-12-16 19:31:33', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (636, 1, 13, '', 'BB 滚球王', 'BBRB', NULL, NULL, 'BBRB', '9', 1, NULL, 0.00, 1, '2017-12-16 19:32:13', '2017-12-16 19:32:13', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (637, 1, 13, '', 'BB3D', 'B3D', NULL, NULL, 'B3D', '1', 1, NULL, 0.00, 1, '2017-12-16 19:32:36', '2017-12-16 19:47:30', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (638, 1, 13, '', '3D 彩', 'BJ3D', NULL, NULL, 'BJ3D', '9', 1, NULL, 0.00, 1, '2017-12-16 19:33:04', '2017-12-16 19:33:04', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (639, 1, 13, '', '排列三', 'PL3D', NULL, NULL, 'PL3D', '9', 1, NULL, 0.00, 1, '2017-12-16 19:33:33', '2017-12-16 19:33:33', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (640, 1, 13, '', '上海时时乐', 'SH3D', NULL, NULL, 'SH3D', '9', 1, NULL, 0.00, 1, '2017-12-16 19:33:57', '2017-12-16 19:33:57', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (641, 1, 13, '', 'BB 高低', 'BBHL', NULL, NULL, 'BBHL', '9', 1, NULL, 0.00, 1, '2017-12-16 19:34:19', '2017-12-16 19:34:19', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (642, 1, 13, '', 'BB 双喜龙门', 'BBAD', NULL, NULL, 'BBAD', '9', 1, NULL, 0.00, 1, '2017-12-16 19:34:43', '2017-12-16 19:34:43', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (643, 1, 13, '', 'BB 淘金蛋', 'BBGE', NULL, NULL, 'BBGE', '9', 1, NULL, 0.00, 1, '2017-12-16 19:35:03', '2017-12-16 19:35:03', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (644, 1, 13, '', '梯子游戏', 'LDDR', NULL, NULL, 'LDDR', '9', 1, NULL, 0.00, 1, '2017-12-16 19:35:25', '2017-12-16 19:35:25', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (645, 1, 13, '', '经典梯子', 'LDRS', NULL, NULL, 'LDRS', '9', 1, NULL, 0.00, 1, '2017-12-16 19:35:50', '2017-12-16 19:35:50', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (646, 1, 13, '', 'BB 射龙门', 'BBLM', NULL, NULL, 'BBLM', '9', 1, NULL, 0.00, 1, '2017-12-16 19:36:15', '2017-12-16 19:36:15', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (647, 1, 13, '', 'BB 幸运熊猫', 'LKPA', NULL, NULL, 'LKPA', '9', 1, NULL, 0.00, 1, '2017-12-16 19:36:43', '2017-12-16 19:36:43', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (648, 1, 13, '', 'BB 百家彩票-A', 'BCRA', NULL, NULL, 'BCRA', '9', 1, NULL, 0.00, 1, '2017-12-16 19:37:17', '2017-12-16 19:37:17', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (649, 1, 13, '', 'BB 百家彩票-B', 'BCRB', NULL, NULL, 'BCRB', '9', 1, NULL, 0.00, 1, '2017-12-16 19:37:37', '2017-12-16 19:37:37', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (650, 1, 13, '', 'BB 百家彩票-C', 'BCRC', NULL, NULL, 'BCRC', '9', 1, NULL, 0.00, 1, '2017-12-16 19:38:00', '2017-12-16 19:38:00', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (651, 1, 13, '', 'BB 百家彩票-D', 'BCRD', NULL, NULL, 'BCRD', '9', 1, NULL, 0.00, 1, '2017-12-16 19:38:34', '2017-12-16 19:38:34', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (652, 1, 13, '', 'BB 百家彩票-E', 'BCRE', NULL, NULL, 'BCRE', '9', 1, NULL, 0.00, 1, '2017-12-16 19:38:54', '2017-12-16 19:38:54', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (653, 1, 13, '', '北京 PK 拾', 'BJPK', NULL, NULL, 'BJPK', '9', 1, NULL, 0.00, 1, '2017-12-16 19:39:14', '2017-12-16 19:39:14', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (654, 1, 13, '', 'BB PK3', 'BBPK', NULL, NULL, 'BBPK', '9', 1, NULL, 0.00, 1, '2017-12-16 19:39:37', '2017-12-16 19:39:37', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (655, 1, 13, '', 'BB 雷电 PK', 'RDPK', NULL, NULL, 'RDPK', '9', 1, NULL, 0.00, 1, '2017-12-16 19:39:57', '2017-12-16 19:39:57', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (656, 1, 13, '', '广东 11 选 5', 'GDE5', NULL, NULL, 'GDE5', '9', 1, NULL, 0.00, 1, '2017-12-16 19:40:18', '2017-12-16 19:40:18', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (657, 1, 13, '', '江西 11 选 5', 'JXE5', NULL, NULL, 'JXE5', '9', 1, NULL, 0.00, 1, '2017-12-16 19:40:41', '2017-12-16 19:40:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (658, 1, 13, '', '山东十一运夺金', 'SDE5', NULL, NULL, 'SDE5', '9', 1, NULL, 0.00, 1, '2017-12-16 19:41:11', '2017-12-16 19:41:11', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (659, 1, 13, '', '重庆时时彩', 'CQSC', NULL, NULL, 'CQSC', '9', 1, NULL, 0.00, 1, '2017-12-16 19:41:31', '2017-12-16 19:41:31', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (660, 1, 13, '', '新疆时时彩', 'XJSC', NULL, NULL, 'XJSC', '9', 1, NULL, 0.00, 1, '2017-12-16 19:41:56', '2017-12-16 19:41:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (661, 1, 13, '', '天津时时彩', 'TJSC', NULL, NULL, 'TJSC', '9', 1, NULL, 0.00, 1, '2017-12-16 19:42:17', '2017-12-16 19:42:17', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (662, 1, 13, '', '江苏快 3', 'JSQ3', NULL, NULL, 'JSQ3', '9', 1, NULL, 0.00, 1, '2017-12-16 19:42:38', '2017-12-16 19:42:38', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (663, 1, 13, '', '安徽快 3', 'AHQ3', NULL, NULL, 'AHQ3', '9', 1, NULL, 0.00, 1, '2017-12-16 19:43:01', '2017-12-16 19:47:10', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (664, 1, 13, '', 'BB 竞速快乐彩', 'BBQK', NULL, NULL, 'BBQK', '9', 1, NULL, 0.00, 1, '2017-12-16 19:43:34', '2017-12-16 19:43:34', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (665, 1, 13, '', 'BB 快乐彩', 'BBKN', NULL, NULL, 'BBKN', '9', 1, NULL, 0.00, 1, '2017-12-16 19:43:57', '2017-12-16 19:43:57', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (666, 1, 13, '', '加拿大卑斯', 'CAKN', NULL, NULL, 'CAKN', '9', 1, NULL, 0.00, 1, '2017-12-16 19:44:22', '2017-12-16 19:44:22', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (667, 1, 13, '', '北京快乐 8', 'BJKN', NULL, NULL, 'BJKN', '9', 1, NULL, 0.00, 1, '2017-12-16 19:44:48', '2017-12-16 19:44:48', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (668, 1, 13, '', '重庆幸运农场', 'CQSF', NULL, NULL, 'CQSF', '9', 1, NULL, 0.00, 1, '2017-12-16 19:45:16', '2017-12-16 19:45:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (669, 1, 13, '', '天津十分彩', 'TJSF', NULL, NULL, 'TJSF', '9', 1, NULL, 0.00, 1, '2017-12-16 19:45:41', '2017-12-16 19:45:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (670, 1, 13, '', '广西十分彩', 'GXSF', NULL, NULL, 'GXSF', '9', 1, NULL, 0.00, 1, '2017-12-16 19:46:00', '2017-12-16 19:46:56', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (671, 1, 13, '', '重庆百变王牌', 'CQWC', NULL, NULL, 'CQWC', '9', 1, NULL, 0.00, 1, '2017-12-16 19:46:19', '2017-12-16 19:46:19', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (672, 1, 13, '', '其他', 'OTHER', NULL, NULL, 'OTHER', '9', 1, NULL, 0.00, 1, '2017-12-16 19:46:44', '2017-12-16 19:46:44', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (673, 4, 12, 'Adventures in Wonderland', '仙境冒险', 'Progressive Slot Machines', NULL, NULL, 'ashadv', '1', 1, 'app/img/admin/game/986f2805531b7735268766f540abac46.png', 0.00, 1, '2017-12-26 11:34:37', '2017-12-27 16:31:51', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (674, 12, 16, 'Dynasty Empire', '帝國王朝', 'DynastyEmpire', NULL, NULL, '1047', '1', 7, 'app/img/admin/game/5adbb0252c71f8afc6c98ca04785fd8f.jpeg', 0.00, 1, '2018-01-12 09:16:45', '2018-02-02 14:26:44', 44, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (675, 12, 16, 'StacksOfCheese', '堆叠奶酪', 'StacksOfCheese', NULL, NULL, '1044', '1', 1, 'app/img/admin/game/f89a843009f10240c269ffb713d7e930.jpeg', 0.00, 1, '2018-01-12 09:18:28', '2018-01-15 11:28:52', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (676, 12, 16, 'SuperKids', '超级宝贝', 'SuperKids', NULL, NULL, '1072', '1', 1, 'app/img/admin/game/ef8dbc43d996e68671a63aee209228a0.jpeg', 0.00, 1, '2018-01-12 09:19:26', '2018-01-15 10:57:15', 6, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (677, 12, 16, 'Huluwa', '葫芦娃', 'Huluwa', NULL, NULL, '1078', '1', 4, 'app/img/admin/game/1d6e1873168c4d490fbdab42ff6ea025.jpeg', 0.00, 1, '2018-01-12 09:20:32', '2018-01-15 10:57:45', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (678, 12, 16, 'DetectiveBlackCat', '黑猫警长', 'DetectiveBlackCat', NULL, NULL, '1075', '1', 4, 'app/img/admin/game/e082fefd4ab6d9c92878ad1254eddb32.jpeg', 0.00, 1, '2018-01-12 09:21:48', '2018-01-13 13:59:10', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (679, 12, 16, 'GongXiFaCai', '恭喜发财', 'GongXiFaCai', NULL, NULL, '1073', '1', 3, 'app/img/admin/game/baceb4cd42285d6d685e5ad1ed86bf0c.jpeg', 0.00, 1, '2018-01-12 09:22:43', '2018-01-12 16:47:35', 1, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (680, 12, 16, 'Legend Of Link H5', '林克的传说', 'LegendOfLinkH5', NULL, NULL, '1049', '1', 3, 'app/img/admin/game/f549219dfcdab7e539b88ad2aaab7f0d.jpeg', 0.00, 1, '2018-01-12 09:23:41', '2018-01-12 16:55:25', 1, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (681, 12, 16, 'EightImmortals', '八仙过海', 'EightImmortals', NULL, NULL, '1042', '1', 3, 'app/img/admin/game/fd10e3652b6b0ab5acb29f467d30403e.jpeg', 0.00, 1, '2018-01-12 09:24:46', '2018-01-12 16:42:01', 1, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (682, 12, 16, 'DragonBallReels', '龍珠', 'DragonBallReels', NULL, NULL, '1040', '1', 3, 'app/img/admin/game/2586525454d5f6fd1382920b424fddd6.jpeg', 0.00, 1, '2018-01-12 09:25:49', '2018-01-15 11:43:07', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (683, 12, 16, 'RoseOfVenice ', '威尼斯野玫瑰', 'RoseOfVenice', NULL, NULL, '1039', '1', 3, 'app/img/admin/game/a675c6a61771d348e429db0ebec0b489.jpeg', 0.00, 1, '2018-01-12 09:26:54', '2018-01-12 17:03:09', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (684, 12, 16, 'BerryBlastPlus', '水果对对碰', 'BerryBlastPlus', NULL, NULL, '1038', '1', 6, 'app/img/admin/game/4af8855a10f3c513d9d5e186f9cd1ddc.jpeg', 0.00, 1, '2018-01-12 09:27:50', '2018-01-12 17:00:43', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (685, 12, 16, 'TigerSlayer', '武松打虎', 'TigerSlayer', NULL, NULL, '1037', '1', 7, 'app/img/admin/game/6d55ab50f939cffd28b304ca005cf241.jpeg', 0.00, 1, '2018-01-12 09:28:47', '2018-01-12 17:03:48', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (686, 12, 16, 'TheHoppingDead', '僵尸先生', 'TheHoppingDead', NULL, NULL, '1036', '1', 3, 'app/img/admin/game/8c8324d8534a3968057382533c5fcceb.jpeg', 0.00, 1, '2018-01-12 09:29:38', '2018-01-12 16:53:20', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (687, 12, 16, 'YearofTheMonkey', '猴年大吉', 'YearOfTheMonkey', NULL, NULL, '1035', '1', 1, 'app/img/admin/game/523c0f20ed6f07b6e5677ec783578374.jpeg', 0.00, 1, '2018-01-12 09:30:27', '2018-01-12 16:51:08', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (688, 12, 16, 'Cleopatra', '埃及艳后', 'Cleopatra', NULL, NULL, '1034', '1', 3, 'app/img/admin/game/488596c1530f55957d1bcc7a5f62cb23.jpeg', 0.00, 1, '2018-01-12 09:31:11', '2018-01-12 16:41:46', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (689, 12, 16, 'RobinHood', '罗宾汉', 'RobinHood', NULL, NULL, '1033', '1', 4, 'app/img/admin/game/e9a4f5eadf6a4be639d7790600f5aaab.jpeg', 0.00, 1, '2018-01-12 09:32:02', '2018-01-12 16:56:42', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (690, 12, 16, 'NeutronStar', '中子星', 'NeutronStar', NULL, NULL, '1031', '1', 2, 'app/img/admin/game/4aa302c05f4805e7af0e2835287e0652.jpeg', 0.00, 1, '2018-01-12 09:32:46', '2018-01-12 17:09:34', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (691, 12, 16, 'TikiTreasures', '图腾宝藏', 'TikiTreasures', NULL, NULL, '1030', '1', 5, 'app/img/admin/game/664f0d40ae7799af816396c6394b5b76.jpeg', 0.00, 1, '2018-01-12 09:33:29', '2018-01-12 17:02:24', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (692, 12, 16, 'DragonPalace', '龍宮', 'DragonPalace', NULL, NULL, '1029', '1', 6, 'app/img/admin/game/c72f6c89c757cc38294a271c92f48df9.jpeg', 0.00, 1, '2018-01-12 09:34:12', '2018-01-15 11:43:30', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (693, 12, 16, 'GrandPrix', '国际赛车', 'GrandPrix', NULL, NULL, '1028', '1', 6, 'app/img/admin/game/ac15773d1f471f25fc16312688ef72a8.jpeg', 0.00, 1, '2018-01-12 09:34:52', '2018-01-12 16:47:51', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (694, 12, 16, 'AladdinHandOfMidas', '阿拉丁-点金手', 'AladdinHandOfMidas', NULL, NULL, '1027', '1', 3, 'app/img/admin/game/11209d4b12aa446de44471249a8d967b.jpeg', 0.00, 1, '2018-01-12 09:35:34', '2018-01-12 16:41:20', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (695, 12, 16, 'Athena', '雅典娜', 'Athena', NULL, NULL, '1026', '1', 4, 'app/img/admin/game/dbd485acc94360cceeaf079918b97025.jpeg', 0.00, 1, '2018-01-12 09:36:12', '2018-01-12 17:06:01', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (696, 12, 16, 'LuckyPanda', '幸运熊猫', 'LuckyPanda', NULL, NULL, '1025', '1', 5, 'app/img/admin/game/f30f4359271a4a986cc3f74bec4d97fc.jpeg', 0.00, 1, '2018-01-12 09:36:55', '2018-01-12 17:05:22', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (697, 12, 16, 'TheSilkRoad', '丝绸之路', 'TheSilkRoad', NULL, NULL, '1024', '1', 6, 'app/img/admin/game/285d7c986413cbc323c70110b7f66039.jpeg', 0.00, 1, '2018-01-12 09:37:33', '2018-01-12 17:01:08', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (698, 12, 16, 'SnakeCharmer', '耍蛇者', 'SnakeCharmer', NULL, NULL, '1023', '1', 3, 'app/img/admin/game/86158d3dc70ba3730fee65ad30c1f975.jpeg', 0.00, 1, '2018-01-12 09:38:09', '2018-01-12 17:00:30', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (699, 12, 16, 'HotVolcano', '炽热火山', 'HotVolcano', NULL, NULL, '1022', '1', 3, 'app/img/admin/game/6de31b22ec84b418dd098644a376f75d.jpeg', 0.00, 1, '2018-01-12 09:38:46', '2018-01-12 16:44:02', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (700, 12, 16, 'Drago King', '海龍王', 'DragoKing', NULL, NULL, '1021', '1', 3, NULL, 0.00, 0, '2018-01-12 09:39:20', '2018-01-15 11:44:16', 3, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (701, 12, 16, 'FireGoddess', '美女火神', 'FireGoddess', NULL, NULL, '1019', '1', 7, 'app/img/admin/game/d0371b163d8fec096d094354a16d8bb3.jpeg', 0.00, 1, '2018-01-12 09:40:06', '2018-01-12 17:18:35', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (702, 12, 16, 'PotOGoldII', '金罐子II', 'PotOGoldII', NULL, NULL, '1018', '1', 3, 'app/img/admin/game/628373710c8eef92b25d7e87b16d0591.jpeg', 0.00, 1, '2018-01-12 09:40:37', '2018-01-12 16:54:08', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (703, 12, 16, 'ZeusVsHades', '天神VS冥王', 'ZeusVsHades', NULL, NULL, '1017', '1', 3, 'app/img/admin/game/742d0a3c9df80b5d74645f65e74e093d.jpeg', 0.00, 1, '2018-01-12 09:41:22', '2018-02-05 16:58:54', 3, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (704, 12, 16, 'MadMonkey', '疯狂的猴子', 'MadMonkey', NULL, NULL, '1016', '1', 4, 'app/img/admin/game/1af9e2fb4bdb85cfaaaddd67794e18c4.jpeg', 0.00, 1, '2018-01-12 09:41:59', '2018-01-12 17:18:47', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (705, 12, 16, 'RedHotFreeSpins', '惹火的自由旋转', 'RedHotFreeSpins', NULL, NULL, '1015', '1', 6, 'app/img/admin/game/147257c945b9ccc83539aac6a24acc46.jpeg', 0.00, 1, '2018-01-12 09:42:32', '2018-01-12 16:58:34', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (706, 12, 16, 'MoreMonkeys', '更多猴子', 'MoreMonkeys', NULL, NULL, '1014', '1', 7, 'app/img/admin/game/5c37dacf8afc48785bb85bb472372ef9.jpeg', 0.00, 1, '2018-01-12 09:43:06', '2018-01-12 16:47:20', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (707, 12, 16, 'Monkey AndTheMoon', '猴子捞月', 'MonkeyAndTheMoon', NULL, NULL, '1013', '1', 4, 'app/img/admin/game/6925a31bda52f6d5f5384462b059a002.jpeg', 0.00, 1, '2018-01-12 09:43:50', '2018-01-12 16:51:23', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (708, 12, 16, 'FivePirates', '五个海盗', 'FivePirates', NULL, NULL, '1012', '1', 7, 'app/img/admin/game/2399e4fbdc58dfb560257dfc8d879d65.jpeg', 0.00, 1, '2018-01-12 09:44:25', '2018-01-12 17:03:34', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (709, 12, 16, 'JadeEmpire', '翡翠帝国', 'JadeEmpire', NULL, NULL, '1011', '1', 5, 'app/img/admin/game/68c80378617a619e15d04f55c603a56c.jpeg', 0.00, 1, '2018-01-12 09:44:58', '2018-01-12 16:46:19', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (710, 12, 16, 'KatLeeII', '吉李 II:赏金猎人', 'KatLeeII', NULL, NULL, '1009', '1', 4, 'app/img/admin/game/1a56753123f1469d6c1c44d20c9c5fb2.jpeg', 0.00, 1, '2018-01-12 09:45:28', '2018-01-12 16:52:13', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (711, 12, 16, 'CashGrabII', '快抓钱2', 'CashGrabII', NULL, NULL, '1008', '1', 7, 'app/img/admin/game/b235c6111e8006d419ab0df8855f228f.jpeg', 0.00, 1, '2018-01-12 09:46:07', '2018-01-12 16:54:58', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (712, 12, 16, 'SilverLion', '银狮奖', 'SilverLion', NULL, NULL, '1007', '1', 7, 'app/img/admin/game/a5efa9c997bffa2838867612f32799d9.jpeg', 0.00, 1, '2018-01-12 09:46:44', '2018-01-12 17:07:22', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (713, 12, 16, 'ActionHeroes ', '战斗英雄', 'ActionHeroes', NULL, NULL, '1006', '1', 4, 'app/img/admin/game/78f7a71405579e8b83c4b18dd31243d8.jpeg', 0.00, 1, '2018-01-12 09:47:17', '2018-01-12 17:07:37', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (714, 12, 16, 'JourneyWest', '西游记', 'JourneyWest', NULL, NULL, '1004', '1', 4, 'app/img/admin/game/7b0f4d559d8c694a56285a7ab2fa8718.jpeg', 0.00, 1, '2018-01-12 09:47:44', '2018-01-12 17:04:35', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (715, 12, 16, 'DolphinGold', '金海豚', 'DolphinGold', NULL, NULL, '1003', '1', 4, 'app/img/admin/game/ca0f9866a459177b199c80257f9f8210.jpeg', 0.00, 1, '2018-01-12 09:48:31', '2018-01-12 16:54:31', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (716, 12, 16, 'ZodiacWild', '十二生肖', 'ZodiacWild', NULL, NULL, '1002', '1', 4, 'app/img/admin/game/87329a002fa77f2c732e0e732dc70560.jpeg', 0.00, 1, '2018-01-12 09:49:03', '2018-01-12 16:59:50', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (717, 12, 16, 'FuStar', '福星高照', 'FuStar', NULL, NULL, '1001', '1', 3, 'app/img/admin/game/13102835355d7058d00b178140cf99b0.jpeg', 0.00, 1, '2018-01-12 09:49:34', '2018-01-12 16:47:08', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (718, 12, 16, 'FortunePay', '招财进宝', 'FortunePay', NULL, NULL, '1000', '1', 4, 'app/img/admin/game/587ea38ddf129eacb2e72ddfee50c43d.jpeg', 0.00, 1, '2018-01-12 09:50:08', '2018-01-12 17:07:56', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (719, 12, 16, 'Fortune8Cat', '8 招财猫', 'Fortune8Cat', NULL, NULL, '540', '1', 7, 'app/img/admin/game/9b5719985ba62ddbe66d187d8856b7f0.jpeg', 0.00, 1, '2018-01-12 09:50:43', '2018-01-12 16:40:49', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (720, 12, 16, 'ChilliGold', '火辣金砖', 'ChilliGold', NULL, NULL, '533', '1', 4, 'app/img/admin/game/05a66bf7e22b82c65383d9440cf87278.jpeg', 0.00, 1, '2018-01-12 09:51:20', '2018-01-12 16:51:56', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (721, 12, 16, 'ChoySunDoa', '财神到', 'ChoySunDoa', NULL, NULL, '530', '1', 6, 'app/img/admin/game/3858e9895544833850c7d041a83d7534.jpeg', 0.00, 1, '2018-01-12 09:51:53', '2018-01-12 16:43:31', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (722, 12, 16, 'FrogsNFlies', '捕蝇大赛', 'FrogsNFlies', NULL, NULL, '526', '1', 7, 'app/img/admin/game/352eca9ef2245e9685d55ebc972bf275.jpeg', 0.00, 1, '2018-01-12 09:52:26', '2018-01-12 16:42:57', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (723, 12, 16, 'AladdinsLegacy', '阿拉丁神迹', 'AladdinsLegacy', NULL, NULL, '525', '1', 3, 'app/img/admin/game/a6360717c1ac2e6efa121dcf7be6f21a.jpeg', 0.00, 1, '2018-01-12 09:52:55', '2018-01-12 16:41:33', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (724, 12, 16, 'Taxi', '计程车', 'Taxi', NULL, NULL, '516', '1', 3, 'app/img/admin/game/6bee53310dcc02129970708726d8c65c.jpeg', 0.00, 1, '2018-01-12 09:53:26', '2018-01-12 16:52:52', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (725, 12, 16, 'SamuraiPrincess', '外道姬', 'SamuraiPrincess', NULL, NULL, '515', '1', 4, 'app/img/admin/game/403aa68ccba69a5d6b9fd077cca01eed.jpeg', 0.00, 1, '2018-01-12 09:54:01', '2018-01-12 17:02:35', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (726, 12, 16, 'ThunderingZeus', '天神宙斯', 'ThunderingZeus', NULL, NULL, '486', '1', 3, 'app/img/admin/game/9db1720d2fa34665cd966b143b46630f.jpeg', 0.00, 1, '2018-01-12 09:54:35', '2018-01-12 17:01:52', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (727, 12, 16, 'LostTemple', '失落的神庙', 'LostTemple', NULL, NULL, '484', '1', 7, 'app/img/admin/game/9f2494d0399705e0b3b4cc423800ca6e.jpeg', 0.00, 1, '2018-01-12 09:55:08', '2018-01-12 16:59:37', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (728, 12, 16, 'ShogunShowdown', '幕府摊牌', 'ShogunShowdown', NULL, NULL, '483', '1', 4, 'app/img/admin/game/c0f37121c3eda7e51ba813a08c5c31e0.jpeg', 0.00, 1, '2018-01-12 09:55:38', '2018-01-12 16:57:21', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (729, 12, 16, 'VampiresVsWerewolves', '吸血鬼大战狼人', 'VampiresVsWerewolves', NULL, NULL, '480', '1', 3, 'app/img/admin/game/ae76186dbf7b98f291dfbdf985a79336.jpeg', 0.00, 1, '2018-01-12 09:56:12', '2018-01-12 17:04:48', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (730, 12, 16, 'SerengetiDiamonds', '塞伦盖提之钻', 'SerengetiDiamonds', NULL, NULL, '478', '1', 3, 'app/img/admin/game/963de270daf368bd19e955ff77b7827e.jpeg', 0.00, 1, '2018-01-12 09:56:41', '2018-01-12 16:59:03', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (731, 12, 16, 'AngelsTouch', '天使的触摸', 'AngelsTouch', NULL, NULL, '477', '1', 4, 'app/img/admin/game/6930d39f88653264b2fd26842c7c5e91.jpeg', 0.00, 1, '2018-01-12 09:57:12', '2018-01-13 14:07:56', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (732, 12, 16, 'DracosFire', '天龙火焰', 'DracosFire', NULL, NULL, '475', '1', 4, 'app/img/admin/game/0c6dea3c6f52c2f49e672cb8d4bc1fd7.jpeg', 0.00, 1, '2018-01-12 09:57:42', '2018-01-12 16:56:30', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (733, 12, 16, 'Sinful Spins Slots', '万恶旋转', 'Sinful Spins Slots', NULL, NULL, '474', '1', 3, 'app/img/admin/game/477e695439a2df982e86e1bfc50823d3.jpeg', 0.00, 1, '2018-01-12 09:58:18', '2018-01-12 17:02:47', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (734, 12, 16, 'Bars And Bells Slots', '酒吧门铃', 'BarsAndBellsSlots', NULL, NULL, '473', '1', 1, 'app/img/admin/game/4012ecbcb8f5c716a89d24d8a2e7ea1f.jpeg', 0.00, 1, '2018-01-12 09:59:06', '2018-01-12 16:54:44', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (735, 12, 16, 'VictoryRidgeSlots', '维多利山脊', 'VictoryRidgeSlots', NULL, NULL, '468', '1', 3, 'app/img/admin/game/6b8cdbdeb781219637fc69602ee4a69d.jpeg', 0.00, 1, '2018-01-12 09:59:38', '2018-01-12 17:03:21', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (736, 12, 16, 'Arthurs Quest II', '亚瑟寻宝之旅 II', 'ArthursQuestIISlots', NULL, NULL, '462', '1', 2, 'app/img/admin/game/e95279645e65c3b89b82ba71caebb47f.jpeg', 0.00, 1, '2018-01-12 10:00:22', '2018-01-12 17:06:34', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (737, 12, 16, 'TheGreatCasiniSlots', '大卡西尼', 'TheGreatCasiniSlots', NULL, NULL, '453', '1', 3, 'app/img/admin/game/72d6faad542e1a83f993a61c2f12c57a.jpeg', 0.00, 1, '2018-01-12 10:00:51', '2018-01-12 16:44:29', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (738, 12, 16, 'MagicalGroveSlots', '魔法森林', 'MagicalGroveSlots', NULL, NULL, '452', '1', 3, 'app/img/admin/game/c916e631196336372e729bbd9c742822.jpeg', 0.00, 1, '2018-01-12 10:01:24', '2018-01-12 16:57:11', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (739, 12, 16, 'SurfsUpSlots', '冲浪', 'SurfsUpSlots', NULL, NULL, '449', '1', 3, 'app/img/admin/game/f9d5f92e9807e3c9faf805167aa75130.jpeg', 0.00, 1, '2018-01-12 10:01:56', '2018-01-12 16:44:15', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (740, 12, 16, 'TBSpinNWinSlots', '三重红利幸运大转盘', 'TBSpinNWinSlots', NULL, NULL, '447', '1', 4, 'app/img/admin/game/1733cb6fbe32e8e404c252c1489e376a.jpeg', 0.00, 1, '2018-01-12 10:02:22', '2018-01-12 16:59:16', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (741, 12, 16, 'FortuneTellerSlots', '算命嬴大奖', 'FortuneTellerSlots', NULL, NULL, '446', '1', 3, 'app/img/admin/game/5fda81b5afb221390804a04a12ff1728.jpeg', 0.00, 1, '2018-01-12 10:02:49', '2018-01-12 17:01:18', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (742, 12, 16, 'BerryBlastSlots', '浆果培击', 'BerryBlastSlots', NULL, NULL, '444', '1', 4, 'app/img/admin/game/dfa4a3f1695557973f4881a080c25266.jpeg', 0.00, 1, '2018-01-12 10:03:18', '2018-01-12 16:53:05', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (743, 12, 16, 'KatLeeSlots', '吉李：赏金猎人', 'KatLeeSlots', NULL, NULL, '440', '1', 4, 'app/img/admin/game/a2a1207f0f77fc0e3ce5b6dea9d7f33c.jpeg', 0.00, 1, '2018-01-12 10:03:49', '2018-01-12 16:52:25', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (744, 12, 16, 'LadysCharmsSlots', '淑女魅力', 'LadysCharmsSlots', NULL, NULL, '439', '1', 3, 'app/img/admin/game/ed08210026dbf48c7c8c4e0efc378ba3.jpeg', 0.00, 1, '2018-01-12 10:04:16', '2018-01-12 17:00:12', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (745, 12, 16, 'VivaVeneziaSlots', '威尼斯万岁', 'VivaVeneziaSlots', NULL, NULL, '438', '1', 3, 'app/img/admin/game/4344e81f09252b96d43aac697b09b7a2.jpeg', 0.00, 1, '2018-01-12 10:04:46', '2018-01-12 17:02:59', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (746, 12, 16, 'FanCashticSlots', '奇妙嬴现金', 'FanCashticSlots', NULL, NULL, '437', '1', 2, 'app/img/admin/game/a5673da29bb5b120f63db6b994a869f8.jpeg', 0.00, 1, '2018-01-12 10:05:14', '2018-01-12 16:58:09', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (747, 12, 16, 'WildMummySlots', '疯狂木乃伊', 'WildMummySlots', NULL, NULL, '428', '1', 3, 'app/img/admin/game/459d7622cc5705d3be3ea1a38f70a848.jpeg', 0.00, 1, '2018-01-12 10:05:44', '2018-01-12 16:46:56', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (748, 12, 16, 'PolarRichesSlots', '极地财宝', 'PolarRichesSlots', NULL, NULL, '424', '1', 3, 'app/img/admin/game/afdabb9e08c407d025ebd1f57fc1061e.jpeg', 0.00, 1, '2018-01-12 10:06:12', '2018-01-12 16:52:37', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (749, 12, 16, 'Dragon8sSlots', '龙8', 'Dragon8sSlots', NULL, NULL, '423', '1', 3, 'app/img/admin/game/9785525b14edf9d3e807474502a39903.jpeg', 0.00, 1, '2018-01-12 10:06:40', '2018-01-12 16:55:38', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (750, 12, 16, 'MonkeyLoveSlots', '猴恋', 'MonkeyLoveSlots', NULL, NULL, '421', '1', 3, 'app/img/admin/game/7d91d2fce050ab1d010d9cd5c9729b78.jpeg', 0.00, 1, '2018-01-12 10:07:09', '2018-01-12 16:50:52', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (751, 12, 16, 'NeptunesGoldSlots', '海王星的金', 'NeptunesGoldSlots', NULL, NULL, '416', '1', 3, 'app/img/admin/game/fec6edcb58299434658d07eb5b0b21f7.jpeg', 0.00, 1, '2018-01-12 10:07:38', '2018-01-12 16:48:47', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (752, 12, 16, 'AmazonAdventureSlots', '亚马逊奇遇记', 'AmazonAdventureSlots', NULL, NULL, '414', '1', 2, 'app/img/admin/game/ac0d9f9cbf1008f61a505769cfc95027.jpeg', 0.00, 1, '2018-01-12 10:08:07', '2018-01-12 17:06:19', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (753, 12, 16, 'JackpotHolidaySlots', '头彩假日', 'JackpotHolidaySlots', NULL, NULL, '413', '1', 2, 'app/img/admin/game/b1095ce357608dbcc7ae553bec2a642c.jpeg', 0.00, 1, '2018-01-12 10:08:39', '2018-01-12 17:02:11', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (754, 12, 16, 'FruitParty', '水果会', 'FruitParty', NULL, NULL, '411', '1', 2, 'app/img/admin/game/17e7735db622faf524876caad3589fe4.jpeg', 0.00, 1, '2018-01-12 10:09:05', '2018-01-12 17:00:55', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (755, 12, 16, 'GoooalSlots', '足球赛事', 'GoooalSlots', NULL, NULL, '401', '1', 1, 'app/img/admin/game/3aa2d2af6b7a082106a9958e25e393bd.jpeg', 0.00, 1, '2018-01-12 10:09:34', '2018-01-12 17:10:11', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (756, 12, 16, 'CashGrab', '快抓钱', 'GenericSlots', '21', NULL, '391', '1', 1, 'app/img/admin/game/a7f4cb657ef2be29a99bdbbd16b19742.jpeg', 0.00, 1, '2018-01-12 10:10:26', '2018-01-12 16:55:13', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (757, 12, 16, 'Oktoberfest', '啤酒节', 'Oktoberfest', NULL, NULL, '65', '1', 2, 'app/img/admin/game/e60d3bf1095ccb3e7767f9628ad0cff4.jpeg', 0.00, 1, '2018-01-12 10:10:55', '2018-01-12 16:57:53', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (758, 12, 16, 'BullsEyeBucks', '牛眼钞票', 'BullsEyeBucks', NULL, NULL, '64', '1', 2, 'app/img/admin/game/53934d265e28a550bbe84f7197d4159c.jpeg', 0.00, 1, '2018-01-12 10:11:37', '2018-01-12 16:57:36', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (759, 12, 16, 'ArthursQuest', '亚瑟寻宝之旅 I', 'ArthursQuest', NULL, NULL, '63', '1', 2, 'app/img/admin/game/5626da8075321f64418fa4ad0fc51fe0.jpeg', 0.00, 1, '2018-01-12 10:12:06', '2018-01-12 17:06:47', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (760, 12, 16, 'JumpForGold', '奔向黄金', 'JumpForGold', NULL, NULL, '57', '1', 1, 'app/img/admin/game/87cf1255faf6bcc37c843f8676637849.jpeg', 0.00, 1, '2018-01-12 10:12:37', '2018-01-12 16:42:41', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (761, 12, 16, 'Cash Inferno', '现金地狱', 'FiveReelSlots', NULL, NULL, '40', '1', 2, 'app/img/admin/game/c0004108253810310e3772a418cd267f.jpeg', 0.00, 1, '2018-01-12 10:13:55', '2018-01-12 17:05:01', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (762, 12, 16, 'HoleInOne', '一杆进洞', 'HoleInOne', NULL, NULL, '18', '1', 2, 'app/img/admin/game/2b315f94ba504dd04aae9222f306ea61.jpeg', 0.00, 1, '2018-01-12 10:14:35', '2018-01-12 17:07:06', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (763, 12, 16, 'Lucky_Cherry', '幸运樱桃', 'GenericSlots', NULL, NULL, '17', '1', 1, 'app/img/admin/game/c338b81a418d0ccf9dd3c7e77393a67c.jpeg', 0.00, 1, '2018-01-12 10:15:10', '2018-01-12 17:05:37', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (764, 12, 16, 'Space Invasion', '太空入侵', 'GenericSlots', NULL, NULL, '16', '1', 1, 'app/img/admin/game/4e327959b93b13ba01b24dac5eabcaa0.jpeg', 0.00, 1, '2018-01-12 10:15:58', '2018-01-12 17:01:28', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (765, 12, 16, 'HollywoodReels', '好莱坞明星', 'HollywoodReels', NULL, NULL, '15', '1', 2, 'app/img/admin/game/0681a748a99934524e2d99613ec33692.jpeg', 0.00, 1, '2018-01-12 10:16:23', '2018-01-12 16:49:51', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (766, 12, 16, 'Wild_West', '狂野西部', 'GenericSlots', NULL, NULL, '11', '1', 1, NULL, 0.00, 0, '2018-01-12 10:16:56', '2018-01-13 14:08:20', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (767, 12, 16, 'FastTrack', '赛车快道', 'FastTrack', NULL, NULL, '10', '1', 2, 'app/img/admin/game/2237754a32b83bca2c3f1734be4d11f3.jpeg', 0.00, 1, '2018-01-12 10:17:30', '2018-01-13 14:10:29', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (768, 12, 16, 'Pirate_Treasure', '海盗宝库', 'GenericSlots', NULL, NULL, '9', '1', 1, 'app/img/admin/game/9ff428440667fc35eddd28cb1a068b9a.jpeg', 0.00, 1, '2018-01-12 10:18:16', '2018-01-12 16:48:07', 1, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (769, 14, 17, 'wildmelonmobile', 'wildmelon', NULL, NULL, NULL, 'wildmelon', '1', 1, 'app/img/admin/game/eee9462b5bb683fb0ade0c3c408b9633.jpeg', 0.00, 1, '2018-01-12 15:23:18', '2018-02-02 15:26:33', 72, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (770, 14, 17, 'luckydiamondsmobile', 'luckydiamonds', NULL, NULL, NULL, 'luckydiamonds', '1', 1, 'app/img/admin/game/e378895be31fb8ade27ccabcdb37dd5e.jpeg', 0.00, 1, '2018-01-12 15:24:21', '2018-02-02 14:39:52', 18, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (771, 14, 17, 'minibaccaratmobile', 'minibaccarat', NULL, NULL, NULL, 'minibaccarat', '1', 1, 'app/img/admin/game/d1247f363fdd51d20e0ad49590f1b915.jpeg', 0.00, 1, '2018-01-12 15:24:47', '2018-01-12 18:19:04', 8, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (772, 14, 17, 'europeanroulettemobile', 'europeanroulette', NULL, NULL, NULL, 'europeanroulette', '1', 1, 'app/img/admin/game/6c624fa0d58bda7906c6f450144d7094.jpeg', 0.00, 1, '2018-01-12 15:25:23', '2018-02-02 14:48:10', 11, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (773, 14, 17, '', 'englishroulette', NULL, NULL, NULL, 'englishroulette', '1', 1, 'app/img/admin/game/f5e5ed38bf0957b4dedfb4052a78f9f2.jpeg', 0.00, 1, '2018-01-12 15:25:47', '2018-02-02 14:41:02', 4, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (774, 14, 17, '', 'frenchroulette', NULL, NULL, NULL, 'frenchroulette', '1', 1, 'app/img/admin/game/e31d1f1273d6160fee4d812a7048d1ca.jpeg', 0.00, 1, '2018-01-12 15:26:29', '2018-01-12 18:17:46', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (775, 14, 17, 'beatmemobile', 'beatme', NULL, NULL, NULL, 'beatme', '1', 1, 'app/img/admin/game/9aad0a78cd0a9450902366c8e6ea751f.jpeg', 0.00, 1, '2018-01-12 15:27:07', '2018-01-12 18:16:55', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (776, 14, 17, 'casinostudpokermobile', 'casinostudpoker', NULL, NULL, NULL, 'casinostudpoker', '1', 1, 'app/img/admin/game/f6d77790f02f23f27e06a3a76901c5b2.jpeg', 0.00, 1, '2018-01-12 15:27:38', '2018-01-12 18:17:29', 4, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (777, 14, 17, 'jollyrogermobile', 'jollyroger', NULL, NULL, NULL, 'jollyroger', '1', 1, 'app/img/admin/game/a2b73753a003aff29d26ea3c1a815dac.jpeg', 0.00, 1, '2018-01-12 15:28:07', '2018-01-12 18:18:51', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (778, 14, 17, 'fruitbonanzamobile', 'fruitbonanza', NULL, NULL, NULL, 'fruitbonanza', '1', 1, 'app/img/admin/game/82bb7dbdec820ad0b306075ac82dfa3e.jpeg', 0.00, 1, '2018-01-12 15:28:34', '2018-01-12 18:18:15', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (779, 14, 17, 'blackjackmhmobile', 'blackjackmh', NULL, NULL, NULL, 'blackjackmh', '1', 1, 'app/img/admin/game/f837118dc0c0303ac6e24073d178e7a3.jpeg', 0.00, 1, '2018-01-12 15:28:58', '2018-01-12 18:18:35', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (780, 14, 17, 'europeanblackjackmhmobile', 'europeanblackjackmh', NULL, NULL, NULL, 'europeanblackjackmh', '1', 1, 'app/img/admin/game/2f8eb41ca162c40d39ba5cd142a78ef8.jpeg', 0.00, 1, '2018-01-12 15:29:22', '2018-01-12 18:14:20', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (781, 14, 17, 'doubleexposureblackjackmhmobile', 'doubleexposureblackjackmh', NULL, NULL, NULL, 'doubleexposureblackjackmh', '1', 1, 'app/img/admin/game/fadd8e32a14fe8342459ef3037c4d6c6.jpeg', 0.00, 1, '2018-01-12 15:29:52', '2018-01-12 18:13:14', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (782, 14, 17, 'singledeckblackjackmhmobile', 'singledeckblackjackmh', NULL, NULL, NULL, 'singledeckblackjackmh', '1', 1, 'app/img/admin/game/9516426d606242746b7b6b09df7d1b6d.jpeg', 0.00, 1, '2018-01-12 15:30:30', '2018-01-12 18:16:27', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (783, 14, 17, 'casinoholdemmobile', 'casinoholdem', NULL, NULL, NULL, 'casinoholdem', '1', 1, 'app/img/admin/game/0320e359d9856d0ffd96db8359f051f7.jpeg', 0.00, 1, '2018-01-12 15:30:54', '2018-01-12 18:12:33', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (784, 14, 17, 'paigowpokermobile', 'paigowpoker', NULL, NULL, NULL, 'paigowpoker', '1', 1, 'app/img/admin/game/87c4124ff4507b4e9fca583d6b507383.jpeg', 0.00, 1, '2018-01-12 15:31:17', '2018-01-12 18:16:04', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (785, 14, 17, '', 'frenchroulettelp', NULL, NULL, NULL, 'frenchroulettelp', '1', 1, 'app/img/admin/game/ef87b19d12916c34cb5ad56eecfc9b6f.jpeg', 0.00, 1, '2018-01-12 15:31:40', '2018-02-02 14:41:30', 4, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (786, 14, 17, 'scratchahoymobile', 'scratchahoy', NULL, NULL, NULL, 'scratchahoy', '1', 1, 'app/img/admin/game/774e7aa3a2955e3d5c360a92130c4f82.jpeg', 0.00, 1, '2018-01-12 15:32:04', '2018-02-02 14:41:47', 5, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (787, 14, 17, 'holeinonemobile', 'holeinone', NULL, NULL, NULL, 'holeinone', '1', 1, NULL, 0.00, 1, '2018-01-12 15:32:29', '2018-01-15 01:15:18', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (788, 14, 17, 'aceofspadesmobile', 'aceofspades', NULL, NULL, NULL, 'aceofspades', '1', 1, 'app/img/admin/game/a70757c6e3ce4dcdd008b863ed8293a3.jpeg', 0.00, 1, '2018-01-12 15:32:56', '2018-01-12 18:11:15', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (789, 14, 17, 'belloffortunemobile', 'belloffortune', NULL, NULL, NULL, 'belloffortune', '1', 1, 'app/img/admin/game/2f990e6e1b88a906474ea8e8b7a0f9e8.jpeg', 0.00, 1, '2018-01-12 15:33:22', '2018-01-12 18:11:42', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (790, 14, 17, 'speedcashmobile', 'speedcash', NULL, NULL, NULL, 'speedcash', '1', 1, 'app/img/admin/game/96bb94d568bb5babaaa1fe35a2e54309.jpeg', 0.00, 1, '2018-01-12 15:33:44', '2018-01-12 18:16:39', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (791, 14, 17, 'giftshopmobile', 'giftshop', NULL, NULL, NULL, 'giftshop', '1', 1, 'app/img/admin/game/980948755110a9cb6af957d01e72401f.jpeg', 0.00, 1, '2018-01-12 15:34:14', '2018-01-12 18:15:37', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (792, 14, 17, 'goldengoalmobile', 'goldengoal', NULL, NULL, NULL, 'goldengoal', '1', 1, 'app/img/admin/game/49610f2f684111da53112156b435342e.jpeg', 0.00, 1, '2018-01-12 15:34:38', '2018-01-13 13:44:50', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (793, 14, 17, '', 'catsandcash', NULL, NULL, NULL, 'catsandcash', '1', 1, 'app/img/admin/game/2211f9e772c527f3c17dd944ff2de0a9.jpeg', 0.00, 1, '2018-01-12 15:34:53', '2018-01-13 13:38:25', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (794, 14, 17, '', 'copsnrobbers', NULL, NULL, NULL, 'copsnrobbers', '1', 1, 'app/img/admin/game/92a2826ddd394ccda3664fb6a035a94a.jpeg', 0.00, 1, '2018-01-12 15:35:14', '2018-01-13 13:39:13', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (795, 14, 17, 'fortunetellermobile', 'fortuneteller', NULL, NULL, NULL, 'fortuneteller', '1', 1, 'app/img/admin/game/511333ddda3ba8f5744d5820f75a7126.jpeg', 0.00, 1, '2018-01-12 15:35:44', '2018-02-05 17:05:54', 8, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (796, 14, 17, 'photosafarimobile', 'photosafari', NULL, NULL, NULL, 'photosafari', '1', 1, 'app/img/admin/game/43971c273cc60517062b993e7e36d866.jpeg', 0.00, 1, '2018-01-12 15:36:07', '2018-01-13 13:52:04', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (797, 14, 17, 'spaceracemobile', 'spacerace', NULL, NULL, NULL, 'spacerace', '1', 1, 'app/img/admin/game/fb5948316469fec0901c907ddbb4c3e2.jpeg', 0.00, 1, '2018-01-12 15:36:34', '2018-01-13 13:54:53', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (798, 14, 17, '5xmagicmobile', '5xmagic', NULL, NULL, NULL, '5xmagic', '1', 1, 'app/img/admin/game/fbf207d0bf1d071fd7f79045bb95c626.jpeg', 0.00, 1, '2018-01-12 15:37:00', '2018-01-13 13:36:14', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (799, 14, 17, 'irishgoldmobile', 'irishgold', NULL, NULL, NULL, 'irishgold', '1', 1, 'app/img/admin/game/57507483c03744de309c61e9697337b1.jpeg', 0.00, 1, '2018-01-12 15:37:23', '2018-01-13 13:47:28', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (800, 14, 17, 'enchantedmeadowmobile', 'enchantedmeadow', NULL, NULL, NULL, 'enchantedmeadow', '1', 1, 'app/img/admin/game/76c02069eba7d224af8fe18ec595aaa8.jpeg', 0.00, 1, '2018-01-12 15:37:45', '2018-01-13 13:42:00', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (801, 14, 17, 'triplechancehilomobile', 'triplechancehilo', NULL, NULL, NULL, 'triplechancehilo', '1', 1, 'app/img/admin/game/b679d16120f345650128caed22fa8705.jpeg', 0.00, 1, '2018-01-12 15:38:15', '2018-01-13 13:56:32', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (802, 14, 17, 'richesoframobile', 'richesofra', NULL, NULL, NULL, 'richesofra', '1', 1, 'app/img/admin/game/3949ec52ca99d298527a4c0b8b56db52.jpeg', 0.00, 1, '2018-01-12 15:38:42', '2018-01-13 13:53:33', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (803, 14, 17, 'dragonshipmobile', 'dragonship', NULL, NULL, NULL, 'dragonship', '1', 1, 'app/img/admin/game/a682afcafd6eb268a1aec287c9fbef77.jpeg', 0.00, 1, '2018-01-12 15:39:09', '2018-01-13 13:41:05', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (804, 14, 17, 'pearllagoonmobile', 'pearllagoon', NULL, NULL, NULL, 'pearllagoon', '1', 1, 'app/img/admin/game/32f3b45f377b8d08c51820d745ac5208.jpeg', 0.00, 1, '2018-01-12 15:39:33', '2018-01-13 13:51:17', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (805, 14, 17, 'pearllagoonmobile', 'pearllagoon', NULL, NULL, NULL, 'pearllagoon', '1', 1, NULL, 0.00, 0, '2018-01-12 15:39:54', '2018-01-13 13:51:28', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (806, 14, 17, 'jewelboxmobile', 'jewelbox', NULL, NULL, NULL, 'jewelbox', '1', 1, 'app/img/admin/game/47523d9d93abf4da97bd35fc0b35e365.jpeg', 0.00, 1, '2018-01-12 15:41:41', '2018-01-13 13:48:04', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (807, 14, 17, 'aztecidolsmobile', 'aztecidols', NULL, NULL, NULL, 'aztecidols', '1', 1, 'app/img/admin/game/611ab9a0c6f3941e5a15f89bbe999268.jpeg', 0.00, 1, '2018-01-12 15:42:03', '2018-01-13 13:37:09', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (808, 14, 17, '', 'gunslinger', NULL, NULL, NULL, 'gunslinger', '1', 1, 'app/img/admin/game/1eeedae743ecef1e2ec59b5fbe33ed7a.jpeg', 0.00, 1, '2018-01-12 15:42:18', '2018-01-13 13:46:28', 2, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (809, 14, 17, 'mythmobile', 'myth', NULL, NULL, NULL, 'myth', '1', 1, 'app/img/admin/game/a5e6ff91cd1fb0b7a5eb31448febc45b.jpeg', 0.00, 1, '2018-01-12 15:42:38', '2018-01-13 13:50:36', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (810, 14, 17, 'goldtrophy2mobile', 'goldtrophy2', NULL, NULL, NULL, 'goldtrophy2', '1', 1, 'app/img/admin/game/e6e6d60ff729d172ca4228d4b8a3f815.jpeg', 0.00, 1, '2018-01-12 15:43:12', '2018-01-13 13:46:01', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (811, 14, 17, 'wildbloodmobile', 'wildblood', NULL, NULL, NULL, 'wildblood', '1', 1, 'app/img/admin/game/7e029d39e3669ef62889ea9537d24e1c.jpeg', 0.00, 1, '2018-01-12 15:43:43', '2018-01-13 13:57:26', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (812, 14, 17, 'leprechaungoesegyptmobile', 'leprechaungoesegypt', NULL, NULL, NULL, 'leprechaungoesegypt', '1', 1, 'app/img/admin/game/6520bd13bb0c191047c5283a14311a8d.jpeg', 0.00, 1, '2018-01-12 15:44:04', '2018-01-13 13:49:05', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (813, 14, 17, 'ninjafruitsmobile', 'ninjafruits', NULL, NULL, NULL, 'ninjafruits', '1', 1, 'app/img/admin/game/55a8d97ada29decc7de10f2ceb958f2d.jpeg', 0.00, 1, '2018-01-12 15:44:25', '2018-01-13 13:50:53', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (814, 14, 17, 'trollhuntersmobile', 'trollhunters', NULL, NULL, NULL, 'trollhunters', '1', 1, 'app/img/admin/game/2f8fd1fb6e7b60e293f0aba0c82754c3.jpeg', 0.00, 1, '2018-01-12 15:44:46', '2018-01-13 13:56:43', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (815, 14, 17, 'ragetorichesmobile', 'ragetoriches', NULL, NULL, NULL, 'ragetoriches', '1', 1, 'app/img/admin/game/4f15e56b1dc1eac7e88294651903b37b.jpeg', 0.00, 1, '2018-01-12 15:45:06', '2018-01-13 13:53:20', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (816, 14, 17, 'enchantedcrystalsmobile', 'enchantedcrystals', NULL, NULL, NULL, 'enchantedcrystals', '1', 1, 'app/img/admin/game/dc51ea3aab23bfc69657b9d343a00529.jpeg', 0.00, 1, '2018-01-12 15:45:26', '2018-01-13 13:41:43', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (817, 14, 17, 'energoonzmobile', 'energoonz', NULL, NULL, NULL, 'energoonz', '1', 1, 'app/img/admin/game/18063ac5154d67e4d6bf867e81f24112.jpeg', 0.00, 1, '2018-01-12 15:45:56', '2018-01-13 13:42:12', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (818, 14, 17, 'jacksorbettermobile', 'jacksorbetter', NULL, NULL, NULL, 'jacksorbetter', '1', 1, 'app/img/admin/game/9e55c8fda83e202f811ea9daedf0da1b.jpeg', 0.00, 1, '2018-01-12 15:46:21', '2018-01-13 13:47:40', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (819, 14, 17, 'deuceswildmobile', 'deuceswild', NULL, NULL, NULL, 'deuceswild', '1', 1, 'app/img/admin/game/22dec9d64059a7cf7365a2238fe1375a.jpeg', 0.00, 1, '2018-01-12 15:46:43', '2018-01-13 13:39:46', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (820, 14, 17, 'jokerpokermobile', 'jokerpoker', NULL, NULL, NULL, 'jokerpoker', '1', 1, 'app/img/admin/game/3fc18443cd22fde94a08a6fd10805492.jpeg', 0.00, 1, '2018-01-12 15:47:06', '2018-01-13 13:48:28', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (821, 14, 17, 'kingsorbettermobile', 'kingsorbetter', NULL, NULL, NULL, 'kingsorbetter', '1', 1, 'app/img/admin/game/ece0b7f5962e7974654b8155e0255f98.jpeg', 0.00, 1, '2018-01-12 15:47:25', '2018-01-13 13:48:40', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (822, 14, 17, 'tensorbettermobile', 'tensorbetter', NULL, NULL, NULL, 'tensorbetter', '1', 1, 'app/img/admin/game/acba232917726f53636986f963f8f1ce.jpeg', 0.00, 1, '2018-01-12 15:47:48', '2018-01-13 13:55:59', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (823, 14, 17, 'doublebonusmobile', 'doublebonus', NULL, NULL, NULL, 'doublebonus', '1', 1, 'app/img/admin/game/fb21607d36226f42c8573cd660810a52.jpeg', 0.00, 1, '2018-01-12 15:48:10', '2018-01-13 13:40:15', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (824, 14, 17, 'deucesandjokermobile', 'deucesandjoker', NULL, NULL, NULL, 'deucesandjoker', '1', 1, 'app/img/admin/game/2f1a061789b8ea4772613a2d6b00b45b.jpeg', 0.00, 1, '2018-01-12 15:48:31', '2018-01-13 13:39:59', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (825, 14, 17, 'jackpotpokermobile', 'jackpotpoker', NULL, NULL, NULL, 'jackpotpoker', '1', 1, NULL, 0.00, 1, '2018-01-12 15:48:56', '2018-01-12 15:48:56', 1, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (826, 14, 17, 'chinesenewyearmobile', 'chinesenewyear', NULL, NULL, NULL, 'chinesenewyear', '1', 1, 'app/img/admin/game/0c78a6903569de712c472ba0edc752d6.jpeg', 0.00, 1, '2018-01-12 15:49:17', '2018-01-13 13:38:36', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (827, 14, 17, 'ladyoffortunemobile', 'ladyoffortune', NULL, NULL, NULL, 'ladyoffortune', '1', 1, 'app/img/admin/game/5774237568cc7b3329662fe39578411a.jpeg', 0.00, 1, '2018-01-12 15:49:39', '2018-01-13 13:48:54', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (828, 14, 17, 'pearlsofindiamobile', 'pearlsofindia', NULL, NULL, NULL, 'pearlsofindia', '1', 1, 'app/img/admin/game/e94b817d5914cd26d49946747407d8c2.jpeg', 0.00, 1, '2018-01-12 15:49:58', '2018-01-13 13:51:52', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (829, 14, 17, 'mysteryjokermobile', 'mysteryjoker', NULL, NULL, NULL, 'mysteryjoker', '1', 1, 'app/img/admin/game/7a6407ff03568674984ad501bfcbf7aa.jpeg', 0.00, 1, '2018-01-12 15:50:24', '2018-01-13 13:50:25', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (830, 14, 17, 'crazycowsmobile', 'crazycows', NULL, NULL, NULL, 'crazycows', '1', 1, 'app/img/admin/game/74b30847478500974ac91eed48855bc8.jpeg', 0.00, 1, '2018-01-12 15:50:48', '2018-01-13 13:39:24', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (831, 14, 17, 'goldenticketmobile', 'goldenticket', NULL, NULL, NULL, 'goldenticket', '1', 1, 'app/img/admin/game/20ab46ba0d5614dc2e353c4b38dd97cf.jpeg', 0.00, 1, '2018-01-12 15:51:08', '2018-01-13 13:45:11', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (832, 14, 17, 'gemixmobile', 'gemix', NULL, NULL, NULL, 'gemix', '1', 1, 'app/img/admin/game/bff2c1af1f2575f998dad77490e79fe0.jpeg', 0.00, 1, '2018-01-12 15:51:28', '2018-01-13 13:43:50', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (833, 14, 17, 'towerquestmobile', 'towerquest', NULL, NULL, NULL, 'towerquest', '1', 1, 'app/img/admin/game/7ff3e4798954ae6ccd682e846a856433.jpeg', 0.00, 1, '2018-01-12 15:51:50', '2018-01-13 13:56:10', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (834, 14, 17, 'merryxmasmobile', 'merryxmas', NULL, NULL, NULL, 'merryxmas', '1', 1, 'app/img/admin/game/4b49d864038989f6626e57608c0d6e77.jpeg', 0.00, 1, '2018-01-12 15:52:09', '2018-01-13 13:49:41', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (835, 14, 17, 'goldenlegendmobile', 'goldenlegend', NULL, NULL, NULL, 'goldenlegend', '1', 1, 'app/img/admin/game/828cfa94beaa917a1bbd2f6c48c5aabf.jpeg', 0.00, 1, '2018-01-12 15:52:29', '2018-01-13 13:45:02', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (836, 14, 17, 'pimpedmobile', 'pimped', NULL, NULL, NULL, 'pimped', '1', 1, 'app/img/admin/game/e4d4b5fd4292d34f80dbaacc0628f7c0.jpeg', 0.00, 1, '2018-01-12 15:52:49', '2018-01-13 13:52:22', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (837, 14, 17, 'spinpartymobile', 'spinparty', NULL, NULL, NULL, 'spinparty', '1', 1, 'app/img/admin/game/138ba0b5d122dafb2386f173e752c703.jpeg', 0.00, 1, '2018-01-12 15:53:08', '2018-01-13 13:55:04', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (838, 14, 17, 'eastereggsmobile', 'eastereggs', NULL, NULL, NULL, 'eastereggs', '1', 1, 'app/img/admin/game/18a90cc34a27858f635adb3e01e37fb0.jpeg', 0.00, 1, '2018-01-12 15:53:29', '2018-01-13 13:41:16', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (839, 14, 17, 'wildnorthmobile', 'wildnorth', NULL, NULL, NULL, 'wildnorth', '1', 1, 'app/img/admin/game/af3b062f9749c56be1bfcd958953c80a.jpeg', 0.00, 1, '2018-01-12 15:53:50', '2018-01-13 13:57:47', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (840, 14, 17, 'superflipmobile', 'superflip', NULL, NULL, NULL, 'superflip', '1', 1, 'app/img/admin/game/c32cef251e5c6357e42e4cb4b2e6c702.jpeg', 0.00, 1, '2018-01-12 15:54:09', '2018-01-13 13:55:25', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (841, 14, 17, 'eyeofthekrakenmobile', 'eyeofthekraken', NULL, NULL, NULL, 'eyeofthekraken', '1', 1, 'app/img/admin/game/e02919115c4b3dd6dc9fa8ddbb3c183d.jpeg', 0.00, 1, '2018-01-12 15:54:29', '2018-01-13 13:42:24', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (842, 14, 17, 'masquerademobile', 'masquerade', NULL, NULL, NULL, 'masquerade', '1', 1, 'app/img/admin/game/0a7f50b781eef94cb0ec12c75db7982e.jpeg', 0.00, 1, '2018-01-12 15:54:48', '2018-01-13 13:54:11', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (843, 14, 17, 'cloudquestmobile', 'cloudquest', NULL, NULL, NULL, 'cloudquest', '1', 1, 'app/img/admin/game/c00f8c9d1c7e9ed2e3e75a80304cce54.jpeg', 0.00, 1, '2018-01-12 15:55:08', '2018-01-13 13:39:01', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (844, 14, 17, '', 'aztecprincess', NULL, NULL, NULL, 'aztecprincess', '1', 1, NULL, 0.00, 0, '2018-01-12 15:55:21', '2018-01-13 14:03:46', 4, 0, 0, 0.00, 1, 0);
INSERT INTO `def_games` VALUES (845, 14, 17, 'wizardofgemsmobile', 'wizardofgems', NULL, NULL, NULL, 'wizardofgems', '1', 1, 'app/img/admin/game/d63ae385e3cd5e105f18b5cb3bec461c.jpeg', 0.00, 1, '2018-01-12 15:55:46', '2018-01-13 13:58:07', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (846, 14, 17, 'grimmuertomobile', 'grimmuerto', NULL, NULL, NULL, 'grimmuerto', '1', 1, 'app/img/admin/game/de0f994be2b5213a39b3fe224df9c5b5.jpeg', 0.00, 1, '2018-01-12 15:56:12', '2018-01-13 13:46:14', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (847, 14, 17, 'sambacarnivalmobile', 'sambacarnival', NULL, NULL, NULL, 'sambacarnival', '1', 1, 'app/img/admin/game/ef4f58bf83f466adc326017684e173ab.jpeg', 0.00, 1, '2018-01-12 15:56:32', '2018-01-13 13:54:40', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (848, 14, 17, 'happyhalloweenmobile', 'happyhalloween', NULL, NULL, NULL, 'happyhalloween', '1', 1, 'app/img/admin/game/90a4bb2b8b384d8f99d84f00c90fddaa.jpeg', 0.00, 1, '2018-01-12 15:56:52', '2018-01-13 13:46:40', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (849, 14, 17, 'firejokermobile', 'firejoker', NULL, NULL, NULL, 'firejoker', '1', 1, 'app/img/admin/game/4d1b8e4d794af724c61c6f49a9c6f178.jpeg', 0.00, 1, '2018-01-12 15:57:13', '2018-01-13 13:42:49', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (850, 14, 17, 'firejokermobile', 'firejoker', NULL, NULL, NULL, 'firejoker', '1', 1, NULL, 0.00, 0, '2018-01-12 15:57:36', '2018-01-13 13:43:04', 1, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (851, 14, 17, 'turkishvillagekidmobile', 'turkishvillagekid', NULL, NULL, NULL, 'turkishvillagekid', '1', 1, NULL, 0.00, 0, '2018-01-12 15:57:56', '2018-01-13 14:04:26', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (852, 14, 17, 'christmasjokermobile', 'christmasjoker', NULL, NULL, NULL, 'christmasjoker', '1', 1, NULL, 0.00, 0, '2018-01-12 15:58:18', '2018-01-13 14:05:07', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (853, 14, 17, 'bookofdeadmobile', 'bookofdead', NULL, NULL, NULL, 'bookofdead', '1', 1, 'app/img/admin/game/16b1d7b81e8bc74670d4a9ef9179184b.jpeg', 0.00, 1, '2018-01-12 15:58:41', '2018-01-13 13:37:45', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (854, 14, 17, 'sailsofgoldmobile', 'sailsofgold', NULL, NULL, NULL, 'sailsofgold', '1', 1, 'app/img/admin/game/985afd4731228db9c66d9a7e577819be.jpeg', 0.00, 1, '2018-01-12 15:59:00', '2018-01-13 13:54:30', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (855, 14, 17, 'goldencaravanmobile', 'goldencaravan', NULL, NULL, NULL, 'goldencaravan', '1', 1, 'app/img/admin/game/64fa7a64c5f12ff8d4062dc8127d9504.jpeg', 0.00, 1, '2018-01-12 15:59:20', '2018-01-13 13:44:24', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (856, 14, 17, 'flyingpigsmobile', 'flyingpigs', NULL, NULL, NULL, 'flyingpigs', '1', 1, 'app/img/admin/game/997a68ab3fd7924b62cfd80d8cba082b.jpeg', 0.00, 1, '2018-01-12 15:59:40', '2018-01-13 13:43:20', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (857, 14, 17, 'bugspartymobile', 'bugsparty', NULL, NULL, NULL, 'bugsparty', '1', 1, 'app/img/admin/game/464abb20fe74e5cb7686292d9730de8b.jpeg', 0.00, 1, '2018-01-12 16:00:05', '2018-01-13 13:38:08', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (858, 14, 17, 'moneywheelmobile', 'moneywheel', NULL, NULL, NULL, 'moneywheel', '1', 1, 'app/img/admin/game/d1c0bdf58784f17eebae1a6c245f1f08.jpeg', 0.00, 1, '2018-01-12 16:00:25', '2018-01-13 13:49:54', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (859, 14, 17, 'vikingrunecraftmobile', 'vikingrunecraft', NULL, NULL, NULL, 'vikingrunecraft', '1', 1, 'app/img/admin/game/09f2f06f8360dcf0181f16fd552e5c27.jpeg', 0.00, 1, '2018-01-12 16:00:45', '2018-01-13 13:56:54', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (860, 14, 17, 'matsurimobile', 'matsuri', NULL, NULL, NULL, 'matsuri', '1', 1, 'app/img/admin/game/3a1549c3c70f94880681b1e1b147355d.jpeg', 0.00, 1, '2018-01-12 16:01:08', '2018-01-13 13:49:21', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (861, 14, 17, 'sevensinsmobile', 'sevensins', NULL, NULL, NULL, 'sevensins', '1', 1, 'app/img/admin/game/96732019fee6b6fa27d948c12aee7ba7.jpeg', 0.00, 1, '2018-01-12 16:01:31', '2018-01-13 13:36:47', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (862, 14, 17, 'hugomobile', 'hugo', NULL, NULL, NULL, 'hugo', '1', 1, NULL, 0.00, 0, '2018-01-12 16:02:00', '2018-01-13 14:05:57', 4, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (863, 14, 17, 'superwheelmobile', 'superwheel', NULL, NULL, NULL, 'superwheel', '1', 1, 'app/img/admin/game/354b4c957e57511b385613094644eead.jpeg', 0.00, 1, '2018-01-12 16:02:20', '2018-01-13 13:55:38', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (864, 14, 17, 'prissyprincessmobile', 'prissyprincess', NULL, NULL, NULL, 'prissyprincess', '1', 1, 'app/img/admin/game/66a930bbe7a4bf8c0d44b4a055311acb.jpeg', 0.00, 1, '2018-01-12 16:02:40', '2018-01-13 13:52:33', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (865, 14, 17, 'jademagicianmobile', 'jademagician', NULL, NULL, NULL, 'jademagician', '1', 1, 'app/img/admin/game/bd9e61273fc41e446e7682d178fd9466.jpeg', 0.00, 1, '2018-01-12 16:02:59', '2018-01-13 13:47:52', 3, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (866, 14, 17, 'holidayseasonmobile', 'holidayseason', NULL, NULL, NULL, 'holidayseason', '1', 1, 'app/img/admin/game/aa7d9f1ec2bf727cd6352116ebbf6303.jpeg', 0.00, 1, '2018-01-12 16:03:20', '2018-01-13 13:46:52', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (867, 14, 17, 'multifruit81mobile', 'multifruit81', NULL, NULL, NULL, 'multifruit81', '1', 1, 'app/img/admin/game/52dbdf56d3999807a3dffa2265f9a2fe.jpeg', 0.00, 1, '2018-01-12 16:03:40', '2018-01-13 13:50:14', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (868, 14, 17, 'aztecwarriorprincessmobile', 'aztecwarriorprincess', NULL, NULL, NULL, 'aztecwarriorprincess', '1', 1, 'app/img/admin/game/f4460115d3eddbbe8ab48f2248c95eb2.jpeg', 0.00, 1, '2018-01-12 16:04:01', '2018-01-13 13:37:34', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (869, 14, 17, 'sweet27mobile', 'sweet27', NULL, NULL, NULL, 'sweet27', '1', 1, 'app/img/admin/game/898bc90b27cbd7340b91a9d288420551.jpeg', 0.00, 1, '2018-01-12 16:04:24', '2018-01-13 13:55:48', 2, 0, 0, 0.00, 1, 1);
INSERT INTO `def_games` VALUES (870, 9, 15, 'VRSSCA', 'VR 金星 1.5 分彩', NULL, NULL, NULL, '1', '9', 1, NULL, 0.00, 1, '2018-01-12 17:31:03', '2018-01-13 16:52:55', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (871, 9, 15, 'VRPK10A', 'VR 赛车', NULL, NULL, NULL, '2', '9', 1, NULL, 0.00, 1, '2018-01-12 17:31:31', '2018-01-13 16:53:09', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (872, 9, 15, 'VRSSCC', 'VR3 分彩', NULL, NULL, NULL, '11', '1', 1, NULL, 0.00, 1, '2018-01-12 17:32:13', '2018-01-12 17:32:13', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (873, 9, 15, 'VRSSCB', 'VR 火星 5 分彩', NULL, NULL, NULL, '12', '1', 1, NULL, 0.00, 1, '2018-01-12 17:32:49', '2018-01-12 17:32:49', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (874, 9, 15, 'VRPK10B', 'VR 快艇', NULL, NULL, NULL, '13', '1', 1, NULL, 0.00, 1, '2018-01-12 17:33:20', '2018-01-12 17:33:20', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (875, 9, 15, 'VRBCRA', 'VR 百家乐', NULL, NULL, NULL, '15', '1', 1, NULL, 0.00, 1, '2018-01-12 17:33:52', '2018-01-12 17:33:52', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (876, 9, 15, 'VRHKSIXA', 'VR 六合彩', NULL, NULL, NULL, '16', '1', 1, NULL, 0.00, 1, '2018-01-12 17:34:18', '2018-01-12 17:34:18', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (877, 9, 15, 'CQSSC', '重庆时时彩', NULL, NULL, NULL, '3', '1', 1, NULL, 0.00, 1, '2018-01-12 17:34:47', '2018-01-12 17:34:47', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (878, 9, 15, 'XJSSC', '新疆时时彩', NULL, NULL, NULL, '4', '1', 1, NULL, 0.00, 1, '2018-01-12 17:35:17', '2018-01-12 17:35:17', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (879, 9, 15, 'TJSSC', '天津时时彩', NULL, NULL, NULL, '5', '1', 1, NULL, 0.00, 1, '2018-01-12 17:35:41', '2018-01-12 17:35:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (880, 9, 15, 'GD115', '广东 11 选 5', NULL, NULL, NULL, '6', '1', 1, NULL, 0.00, 1, '2018-01-12 17:36:06', '2018-01-12 17:36:06', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (881, 9, 15, 'JX115', '江西 11 选 5', NULL, NULL, NULL, '7', '1', 1, NULL, 0.00, 1, '2018-01-12 17:36:41', '2018-01-12 17:36:41', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (882, 9, 15, 'PK10', '北京赛车', NULL, NULL, NULL, '8', '1', 1, NULL, 0.00, 1, '2018-01-12 17:37:16', '2018-01-12 17:37:16', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (883, 9, 15, 'JSKS', '江苏快三', NULL, NULL, NULL, '9', '1', 1, NULL, 0.00, 1, '2018-01-12 17:37:45', '2018-01-12 17:37:45', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (884, 9, 15, 'LUCKY28', '幸运 28', NULL, NULL, NULL, '10', '1', 1, NULL, 0.00, 1, '2018-01-12 17:38:14', '2018-01-12 17:38:14', 1, 0, 0, 0.00, 0, 0);
INSERT INTO `def_games` VALUES (885, 9, 15, 'HKMARKSIX', '香港六合彩', NULL, NULL, NULL, '14', '1', 1, NULL, 0.00, 1, '2018-01-12 17:38:41', '2018-01-12 17:38:41', 1, 0, 0, 0.00, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for def_main_game_plats
-- ----------------------------
DROP TABLE IF EXISTS `def_main_game_plats`;
CREATE TABLE `def_main_game_plats` (
  `main_game_plat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_game_plat_code` varchar(20) NOT NULL COMMENT '主平台代码',
  `main_game_plat_name` varchar(255) DEFAULT NULL COMMENT '主游戏平台名称',
  `account_pre` varchar(255) NOT NULL DEFAULT '' COMMENT '生成帐号前辍',
  `status` tinyint(1) DEFAULT '1' COMMENT '游戏主平台状态 1 正常  0关闭',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`main_game_plat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='主游戏平台表';

-- ----------------------------
-- Records of def_main_game_plats
-- ----------------------------
BEGIN;
INSERT INTO `def_main_game_plats` VALUES (1, 'bbin', 'BBIN', 'ttc', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (2, 'mg', 'MG', 'TTC', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (3, 'ag', 'AG', '', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (4, 'pt', 'PT', 'TTC', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (5, 'sunbet', 'SUNBET', 'TTC', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (8, 'onworks', 'ONWORKS', 'on', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (9, 'vr', 'VR', '', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (10, 'gd', 'GD', '', 0, '2018-01-30 11:46:26', NULL);
INSERT INTO `def_main_game_plats` VALUES (11, 'tgp', 'TGP', '', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (12, 'ttg', 'TTG', 'TTC_', 1, NULL, NULL);
INSERT INTO `def_main_game_plats` VALUES (14, 'png', 'PNG', '', 1, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for def_pay_channel_list
-- ----------------------------
DROP TABLE IF EXISTS `def_pay_channel_list`;
CREATE TABLE `def_pay_channel_list` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(150) NOT NULL COMMENT '银行卡名称 如 中国农业银行,微信',
  `channel_code` varchar(100) NOT NULL COMMENT '编码',
  `pay_channel_type_id` tinyint(3) NOT NULL DEFAULT '1' COMMENT '银行类型  \n1   传统银行 如:中国农业银行\n2  第三方支付 如:微信\n3  网络银行 如:网商银行',
  `is_need_private_key` tinyint(1) DEFAULT '0' COMMENT '是否需要填写私钥',
  `is_need_vir_card` tinyint(1) DEFAULT '0' COMMENT '是否需要填写转入账户',
  `icon_path_url` varchar(255) DEFAULT NULL COMMENT '支付渠道图标',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_need_domain` tinyint(1) DEFAULT '0' COMMENT '是否需要绑定域名',
  `is_need_good_name` tinyint(1) DEFAULT '0' COMMENT '是否需要绑定商品名称',
  `is_need_identify_code` tinyint(1) DEFAULT '0' COMMENT '是否需要填写商户识别码：0 不需要，1需要',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_id_UNIQUE` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of def_pay_channel_list
-- ----------------------------
BEGIN;
INSERT INTO `def_pay_channel_list` VALUES (1, 'Npay', 'NPAY', 8, 1, 0, NULL, '2018-01-15 20:41:10', '2018-01-15 20:41:10', 0, 0, 0);
INSERT INTO `def_pay_channel_list` VALUES (2, 'Npay', 'NPAY', 9, 1, 0, NULL, '2018-01-15 20:41:20', '2018-01-15 20:41:20', 0, 0, 0);
INSERT INTO `def_pay_channel_list` VALUES (3, 'Npay', 'NPAY', 10, 1, 0, NULL, '2018-01-15 20:41:32', '2018-01-15 20:41:32', 0, 0, 0);
INSERT INTO `def_pay_channel_list` VALUES (4, '招商银行', 'CMBCHINA', 5, 0, 0, NULL, '2018-02-01 21:02:29', '2018-02-01 21:02:29', 0, 0, 0);
INSERT INTO `def_pay_channel_list` VALUES (5, '工商银行', 'CMBCCHINA', 5, 0, 0, NULL, '2018-02-01 21:02:54', '2018-02-01 21:02:54', 0, 0, 0);
INSERT INTO `def_pay_channel_list` VALUES (6, '国付宝', 'GUOFUBAO', 8, 1, 1, 'app/img/bank/gfb.png', '2018-02-03 15:57:22', '2018-02-06 14:39:47', 1, 1, 1);
COMMIT;

-- ----------------------------
-- Table structure for def_pay_channel_type
-- ----------------------------
DROP TABLE IF EXISTS `def_pay_channel_type`;
CREATE TABLE `def_pay_channel_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL COMMENT '银行类型名称',
  `parent_id` int(11) DEFAULT '0' COMMENT '父类ID',
  `sort` tinyint(3) DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='支付渠道类型表';

-- ----------------------------
-- Records of def_pay_channel_type
-- ----------------------------
BEGIN;
INSERT INTO `def_pay_channel_type` VALUES (1, '三方', 0, 0, NULL, NULL);
INSERT INTO `def_pay_channel_type` VALUES (4, '公司', 0, 0, NULL, NULL);
INSERT INTO `def_pay_channel_type` VALUES (5, '银行转账', 4, 0, '2018-02-02 09:24:43', '2018-02-01 20:56:01');
INSERT INTO `def_pay_channel_type` VALUES (6, '扫码支付', 4, 0, '2018-02-01 20:55:54', '2018-02-01 20:55:54');
INSERT INTO `def_pay_channel_type` VALUES (7, '点卡', 1, 0, '2017-12-16 13:32:12', NULL);
INSERT INTO `def_pay_channel_type` VALUES (8, '在线支付', 1, 0, '2017-12-18 15:07:17', NULL);
INSERT INTO `def_pay_channel_type` VALUES (9, '扫码支付', 1, 0, '2017-12-16 13:32:17', NULL);
INSERT INTO `def_pay_channel_type` VALUES (10, 'H5支付', 1, 0, '2018-01-13 16:09:54', '2018-01-13 16:09:54');
COMMIT;

-- ----------------------------
-- Table structure for def_pins
-- ----------------------------
DROP TABLE IF EXISTS `def_pins`;
CREATE TABLE `def_pins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for inf_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `inf_admin_user`;
CREATE TABLE `inf_admin_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
  `password` varchar(128) DEFAULT NULL,
  `mobile` char(11) DEFAULT NULL COMMENT '手机号码',
  `email` varchar(50) DEFAULT NULL COMMENT 'email',
  `status` tinyint(1) DEFAULT '1' COMMENT '1 正常 -1关闭',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后一次登录时间',
  `login_ip` int(11) DEFAULT NULL COMMENT ' 登录IP',
  `parent_id` int(11) DEFAULT NULL COMMENT '父ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户基本表';

-- ----------------------------
-- Records of inf_admin_user
-- ----------------------------
BEGIN;
INSERT INTO `inf_admin_user` VALUES (3, 'winwin', '$2y$10$PbNvMY7nBxZMHw0b/dSHAenrRlCnIqp1uhERrxFY1FIjYYEtVQG8u', NULL, 'winwin@163.com', 1, NULL, NULL, NULL, '2017-04-15 15:13:41', '2018-02-05 15:12:01', NULL, 'BvzAagzBLZEcQi70ZaEImXp58uXLTwspKfDQMPGJn2aHHh0JWfGNOO7o7DEr');
COMMIT;

-- ----------------------------
-- Table structure for inf_agent
-- ----------------------------
DROP TABLE IF EXISTS `inf_agent`;
CREATE TABLE `inf_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL COMMENT '用户名',
  `password` varchar(100) DEFAULT NULL COMMENT '密码',
  `realname` varchar(64) DEFAULT NULL COMMENT '真实姓名',
  `agent_level_id` int(11) DEFAULT '0' COMMENT '代理层级ID',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '代理余额',
  `pay_password` varchar(64) DEFAULT NULL COMMENT '取款密码',
  `experience_amount` decimal(11,2) DEFAULT '0.00' COMMENT '会员礼金',
  `player_number` int(11) DEFAULT NULL COMMENT '下线玩家数量',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `skype` varchar(100) DEFAULT NULL COMMENT 'skype账号',
  `qq` varchar(30) DEFAULT NULL COMMENT 'QQ',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信',
  `mobile` varchar(15) DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `promotion_url` varchar(50) DEFAULT NULL COMMENT '代理推广网址',
  `promotion_url_click_number` int(11) unsigned DEFAULT '0' COMMENT '代理推广网址点击次数',
  `promotion_notion` varchar(255) DEFAULT NULL COMMENT '代理推广介绍',
  `promotion_code` varchar(50) DEFAULT NULL COMMENT '推广码',
  `parent_id` int(11) DEFAULT '0' COMMENT '代理商父ID 介绍人',
  `carrier_id` int(11) DEFAULT NULL COMMENT '运营商ID',
  `status` tinyint(1) DEFAULT '0' COMMENT '代理商账号状态 1 启用 0, 禁用',
  `audit_status` tinyint(1) DEFAULT '0' COMMENT '客服审核状态 1已审核 =0审核中 2拒绝',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '运营商默认代理 1是 0不是',
  `customer_remark` varchar(255) DEFAULT NULL COMMENT '客服备注',
  `customer_time` timestamp NULL DEFAULT NULL COMMENT '客服处理时间',
  `login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `register_ip` varchar(15) DEFAULT NULL COMMENT '注册IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '注册时间',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `template_agent_admin` varchar(255) DEFAULT 'Template_Agent_Admin_One',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`,`carrier_id`) USING BTREE,
  KEY `operator_id` (`carrier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商信息表';

-- ----------------------------
-- Table structure for inf_agent_bank_cards
-- ----------------------------
DROP TABLE IF EXISTS `inf_agent_bank_cards`;
CREATE TABLE `inf_agent_bank_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned NOT NULL COMMENT '运营商ID',
  `agent_id` int(11) NOT NULL COMMENT '代理ID',
  `card_account` varchar(50) DEFAULT NULL COMMENT '取款账号',
  `card_type` tinyint(3) DEFAULT NULL COMMENT '银行卡类型',
  `card_owner_name` varchar(50) DEFAULT NULL COMMENT '持卡人姓名',
  `card_birth_place` varchar(255) DEFAULT NULL COMMENT '开户行地址',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否有效 0无效 1有效',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_id` (`agent_id`) USING BTREE,
  UNIQUE KEY `agent_card_account` (`card_account`) USING BTREE,
  CONSTRAINT `inf_agent_bamk_cards_1` FOREIGN KEY (`agent_id`) REFERENCES `inf_agent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理银行卡表';

-- ----------------------------
-- Table structure for inf_agent_domain
-- ----------------------------
DROP TABLE IF EXISTS `inf_agent_domain`;
CREATE TABLE `inf_agent_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL COMMENT '代理ID',
  `carrier_id` int(11) NOT NULL COMMENT '运营商ID',
  `website` varchar(255) NOT NULL COMMENT '推广域名',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理推广域名';

-- ----------------------------
-- Table structure for inf_agent_news_relation
-- ----------------------------
DROP TABLE IF EXISTS `inf_agent_news_relation`;
CREATE TABLE `inf_agent_news_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `agent_news_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `agent_deleted_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '代理删除标示；0 未删除，1 已删除',
  `agent_view_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '代理删除标示；0 未读，1 已读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier`;
CREATE TABLE `inf_carrier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '运营商名称',
  `site_url` varchar(255) DEFAULT NULL COMMENT '站点地址',
  `is_forbidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用 1是  0否',
  `remain_quota` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '当前额度',
  `is_multi_agent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许运营商支持设置多级代理0否 1是',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `template` varchar(255) DEFAULT 'template_one' COMMENT '模板选择',
  `template_agent` varchar(255) DEFAULT 'template_agent_one' COMMENT '代理商模板选择',
  `template_agent_admin` varchar(255) DEFAULT 'Template_Agent_Admin_One',
  `template_mobile` varchar(255) DEFAULT NULL COMMENT '移动端模板',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_activity
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity`;
CREATE TABLE `inf_carrier_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `act_type_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠活动类型ID',
  `name` varchar(100) NOT NULL COMMENT '活动名称',
  `sort` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '0' COMMENT '活动状态 1 上架 0下架',
  `current_deposit_amount` int(11) DEFAULT '0' COMMENT '当前存款额',
  `bonuses_type` tinyint(1) DEFAULT '0' COMMENT '红利(返奖)类型 1百分比 2固定金额',
  `rebate_financial_bonuses_step_rate_json` varchar(500) DEFAULT NULL COMMENT '红利类型阶梯比例 josn',
  `flow_want_pattern` tinyint(1) DEFAULT '1' COMMENT '流水要求模式',
  `apply_times` tinyint(1) DEFAULT '0' COMMENT '玩家申请次数',
  `censor_way` tinyint(1) DEFAULT '1' COMMENT '审查方式 1手动，2自动',
  `ip_times` int(11) DEFAULT '0' COMMENT '同一IP限制参与次数',
  `image_id` int(1) DEFAULT '0' COMMENT '活动图片ID 从公用图片库调用',
  `is_deposit_display` tinyint(1) DEFAULT '1' COMMENT '是否显示在存款界面 1是 0否',
  `is_website_display` tinyint(1) DEFAULT '1' COMMENT '网站前台是否显示1是 0否',
  `mutex_parent_id` int(11) DEFAULT '0' COMMENT '互斥活动(不能与某个活动同时参与)',
  `is_bet_amount_enjoy_flow` tinyint(1) DEFAULT '1' COMMENT '活动期间内的投注额是否享受反水  1是 0不是',
  `apply_rule_string` text COMMENT '申请规则',
  `content_file_path` varchar(255) DEFAULT NULL COMMENT '活动内容文件目录',
  `is_active_apply` tinyint(1) DEFAULT '0' COMMENT '是否主动申请 1是 0不是',
  `join_times` int(11) DEFAULT '0' COMMENT '参与次数',
  `join_player_count` int(11) DEFAULT '0' COMMENT '参与人数',
  `join_deposit_amount` decimal(11,2) DEFAULT '0.00' COMMENT '存款总额',
  `join_bonus_amount` decimal(11,2) DEFAULT '0.00' COMMENT '参与红利总额',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠活动';

-- ----------------------------
-- Table structure for inf_carrier_activity_agent_user
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_agent_user`;
CREATE TABLE `inf_carrier_activity_agent_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `agent_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理用户ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动代理用户';

-- ----------------------------
-- Table structure for inf_carrier_activity_amphoteric_game_plat
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_amphoteric_game_plat`;
CREATE TABLE `inf_carrier_activity_amphoteric_game_plat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL,
  `carrier_id` int(11) NOT NULL,
  `carrier_game_plat_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商开放的游戏平台id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='正负盈利产生的平台';

-- ----------------------------
-- Table structure for inf_carrier_activity_audit
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_audit`;
CREATE TABLE `inf_carrier_activity_audit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `player_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1待审核 2通过 -1拒绝',
  `ip` varchar(15) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `process_deposit_amount` decimal(11,2) DEFAULT '0.00' COMMENT '处理存款金额',
  `process_bonus_amount` decimal(11,2) DEFAULT '0.00' COMMENT '处理红利金额',
  `process_withdraw_flow_limit` decimal(11,2) DEFAULT '0.00' COMMENT '处理取款流水',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动审核管理';

-- ----------------------------
-- Table structure for inf_carrier_activity_flow_limited_game_plat
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_flow_limited_game_plat`;
CREATE TABLE `inf_carrier_activity_flow_limited_game_plat` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `carrier_game_plat_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商开放的游戏平台id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动流水限平台';

-- ----------------------------
-- Table structure for inf_carrier_activity_player_level
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_player_level`;
CREATE TABLE `inf_carrier_activity_player_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `player_level_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家等级ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动玩家等级';

-- ----------------------------
-- Table structure for inf_carrier_activity_type
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_activity_type`;
CREATE TABLE `inf_carrier_activity_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT NULL COMMENT '运营商ID',
  `type_name` varchar(30) DEFAULT NULL COMMENT '活动类型名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '活动描述',
  `status` tinyint(1) DEFAULT '1' COMMENT '活动类型状态 1正常 0关闭',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠活动类型';

-- ----------------------------
-- Table structure for inf_carrier_agent_level
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_agent_level`;
CREATE TABLE `inf_carrier_agent_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商',
  `level_name` varchar(45) NOT NULL COMMENT '层级名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '代理类型 1佣金代理，2洗码代理，3占成代理',
  `default_player_level` int(11) DEFAULT NULL COMMENT '代理下属玩家默认层级',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是代理默认层级 0否 1是',
  `is_running` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用, 1 启用 0, 禁用',
  `is_multi_agent` tinyint(1) DEFAULT '0' COMMENT '是否支持多级代理',
  `sort` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `remark` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL COMMENT '更新时间',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理层级表';

-- ----------------------------
-- Table structure for inf_carrier_agent_news
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_agent_news`;
CREATE TABLE `inf_carrier_agent_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `carrier_id` int(11) DEFAULT NULL,
  `operator_reviewer_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_back_up_domain
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_back_up_domain`;
CREATE TABLE `inf_carrier_back_up_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `domain` varchar(255) NOT NULL COMMENT '域名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 可用  0 不可用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商备用域名表';

-- ----------------------------
-- Table structure for inf_carrier_image_category
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_image_category`;
CREATE TABLE `inf_carrier_image_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL COMMENT '图片类别',
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商',
  `parent_category_id` int(11) DEFAULT NULL COMMENT '上级图片类别id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_user_id` int(11) NOT NULL COMMENT '创建人员',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='运营商图片分类数据';

-- ----------------------------
-- Records of inf_carrier_image_category
-- ----------------------------
BEGIN;
INSERT INTO `inf_carrier_image_category` VALUES (1, '默认分类', 1, NULL, '2017-03-11 14:37:21', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (2, '轮播图', 1, NULL, '2017-03-13 07:50:41', 1, '2017-03-13 07:50:41');
INSERT INTO `inf_carrier_image_category` VALUES (3, '新闻图片', 1, NULL, '2017-03-13 08:58:19', 1, '2017-03-13 08:58:19');
INSERT INTO `inf_carrier_image_category` VALUES (4, '优惠活动图片', 1, NULL, '2017-03-13 15:01:06', 1, '2017-03-13 15:01:06');
INSERT INTO `inf_carrier_image_category` VALUES (5, '公司二维码', 1, NULL, '2017-04-04 14:26:49', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (6, '优惠活动', 1, NULL, '2018-02-03 11:22:35', 1, '2017-04-06 21:13:15');
INSERT INTO `inf_carrier_image_category` VALUES (7, '首页', 1, NULL, '2017-12-11 19:47:12', 2, '2017-12-11 19:47:12');
INSERT INTO `inf_carrier_image_category` VALUES (8, '电子游艺', 1, NULL, '2017-12-23 15:51:17', 2, '2017-12-23 15:51:17');
INSERT INTO `inf_carrier_image_category` VALUES (9, '彩票投注', 1, NULL, '2017-12-23 15:52:56', 2, '2017-12-23 15:52:56');
INSERT INTO `inf_carrier_image_category` VALUES (10, '真人娱乐', 1, NULL, '2017-12-23 16:57:46', 2, '2017-12-23 16:57:46');
INSERT INTO `inf_carrier_image_category` VALUES (11, '捕鱼游戏', 1, NULL, '2017-12-23 17:36:05', 2, '2017-12-23 17:14:17');
INSERT INTO `inf_carrier_image_category` VALUES (12, '体育投注', 1, NULL, '2017-12-23 17:18:26', 2, '2017-12-23 17:18:26');
INSERT INTO `inf_carrier_image_category` VALUES (13, '移动端首页', 1, NULL, '2017-12-28 10:49:01', 2, '2017-12-28 10:49:01');
INSERT INTO `inf_carrier_image_category` VALUES (14, '代理首页', 1, NULL, '2018-01-29 11:45:07', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (15, '代理我要加入', 1, NULL, '2018-01-29 11:45:28', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (16, '代理合营模式', 1, NULL, '2018-01-29 11:46:36', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (17, '代理合作协议', 1, NULL, '2018-01-29 11:46:39', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (18, '代理佣金政策', 1, NULL, '2018-01-29 11:46:54', 0, NULL);
INSERT INTO `inf_carrier_image_category` VALUES (19, '代理联系我们', 1, NULL, '2018-01-29 11:47:20', 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for inf_carrier_images
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_images`;
CREATE TABLE `inf_carrier_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商id',
  `uploaded_user_id` int(11) NOT NULL COMMENT '上传用户id',
  `image_path` varchar(255) NOT NULL COMMENT '图片路径',
  `image_category` int(3) NOT NULL COMMENT '图片所属类别',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `image_size` varchar(50) DEFAULT NULL COMMENT '图片大小',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8 COMMENT='运营商图片数据表';

-- ----------------------------
-- Records of inf_carrier_images
-- ----------------------------
BEGIN;
INSERT INTO `inf_carrier_images` VALUES (98, 1, 2, '1/images/50fa84e4b690eaab8dabf641b7161f50.jpg', 1, '2017-12-23 15:53:58', '240572', NULL, '2017-12-23 15:53:58');
INSERT INTO `inf_carrier_images` VALUES (99, 1, 2, '1/images/d05dc8d95c356e238d5c3d3165ce4a1e.jpg', 9, '2017-12-23 15:54:42', '240572', NULL, '2017-12-23 15:54:42');
INSERT INTO `inf_carrier_images` VALUES (102, 1, 2, '1/images/5212103cf5c5a608d92a633d2ba953bd.jpg', 10, '2017-12-23 16:58:43', '223458', NULL, '2017-12-23 16:58:43');
INSERT INTO `inf_carrier_images` VALUES (103, 1, 2, '1/images/93aae317d6379ea6881721eb228b3a0f.jpg', 8, '2017-12-23 17:10:23', '335535', NULL, '2017-12-23 17:10:23');
INSERT INTO `inf_carrier_images` VALUES (104, 1, 2, '1/images/f482b8abd4442e4489407005145f80f2.jpg', 1, '2017-12-23 17:14:35', '227762', NULL, '2017-12-23 17:14:35');
INSERT INTO `inf_carrier_images` VALUES (105, 1, 2, '1/images/c239d3cc469955f327abb07affb8f308.jpg', 12, '2017-12-23 17:18:54', '219962', NULL, '2017-12-23 17:18:54');
INSERT INTO `inf_carrier_images` VALUES (109, 1, 2, '1/images/ac9e13d9ce01575ce8e7e3a83518d7d0.jpg', 1, '2017-12-28 10:49:52', '71764', NULL, '2017-12-28 10:49:52');
INSERT INTO `inf_carrier_images` VALUES (110, 1, 2, '1/images/3a0202dd43a370ad4d5a9f4eb0fa273a.jpg', 13, '2017-12-28 10:50:18', '112799', NULL, '2017-12-28 10:50:18');
INSERT INTO `inf_carrier_images` VALUES (111, 1, 2, '1/images/d1410887b1cd23252288e49db3f57d23.jpg', 13, '2017-12-28 10:50:39', '97929', NULL, '2017-12-28 10:50:39');
INSERT INTO `inf_carrier_images` VALUES (113, 1, 1, '1/images/b5bd76335a8c3a77c820277c24778fb6.jpg', 1, '2018-01-25 17:32:25', '879394', NULL, '2018-01-25 17:32:25');
INSERT INTO `inf_carrier_images` VALUES (114, 1, 1, '1/images/c75b2579f65196bf825e90580740d7ec.jpg', 3, '2018-01-25 17:34:30', '595284', NULL, '2018-01-25 17:34:30');
INSERT INTO `inf_carrier_images` VALUES (115, 1, 1, '1/images/b5f1222549eaa8cff9421c1a445ac437.jpg', 3, '2018-01-25 17:34:30', '845941', NULL, '2018-01-25 17:34:30');
INSERT INTO `inf_carrier_images` VALUES (116, 1, 1, '1/images/f05f44ccb02c4d5feb030d60b44f3350.jpg', 3, '2018-01-26 11:49:45', '130696', NULL, '2018-01-26 11:49:45');
INSERT INTO `inf_carrier_images` VALUES (121, 2, 6, '2/images/b0c588d99631d0169fe8653d10b4b625.jpg', 4, '2018-01-30 17:02:16', '845941', NULL, '2018-01-30 17:02:16');
INSERT INTO `inf_carrier_images` VALUES (122, 2, 6, '2/images/acc1c8fb6081f4824f3071dfe58b8d46.jpg', 4, '2018-01-30 17:02:16', '879394', NULL, '2018-01-30 17:02:16');
INSERT INTO `inf_carrier_images` VALUES (123, 2, 6, '2/images/cb87afe1cab6edbb0ee61d0edfdf01d4.jpg', 1, '2018-01-30 17:02:35', '775702', NULL, '2018-01-30 17:02:35');
INSERT INTO `inf_carrier_images` VALUES (124, 2, 6, '2/images/39c2643ec2132a19bfc39b296bbeab3a.png', 4, '2018-01-30 18:06:37', '71402', NULL, '2018-01-30 18:06:37');
INSERT INTO `inf_carrier_images` VALUES (126, 1, 1, '1/images/36471a3135f4e39b6f2f2755f7f1ba96.jpg', 1, '2018-01-31 11:23:58', '218981', NULL, '2018-01-31 11:23:58');
INSERT INTO `inf_carrier_images` VALUES (127, 1, 1, '1/images/1f4e2d7f1f162853ab552fdd64db3d89.jpg', 1, '2018-01-31 11:23:58', '261177', NULL, '2018-01-31 11:23:58');
INSERT INTO `inf_carrier_images` VALUES (128, 1, 1, '1/images/e54303aa22ae804bac709da8a793c06f.jpg', 1, '2018-01-31 11:23:58', '403069', NULL, '2018-01-31 11:23:58');
INSERT INTO `inf_carrier_images` VALUES (129, 1, 1, '1/images/7bfcdf6598251eb0a1f882f8a4122947.jpg', 7, '2018-01-31 11:25:29', '218981', NULL, '2018-01-31 11:25:29');
INSERT INTO `inf_carrier_images` VALUES (130, 1, 1, '1/images/8f2389022b438732ee276a91b3bcacc1.jpg', 7, '2018-01-31 11:25:29', '261177', NULL, '2018-01-31 11:25:29');
INSERT INTO `inf_carrier_images` VALUES (131, 1, 1, '1/images/b9afebc26482df9e75c2de73412048f6.jpg', 7, '2018-01-31 11:25:29', '403069', NULL, '2018-01-31 11:25:29');
INSERT INTO `inf_carrier_images` VALUES (132, 2, 6, '2/images/e35e0c1b393616dfe4962e9f009a3ac3.jpg', 14, '2018-01-31 15:19:13', '176692', NULL, '2018-01-31 15:19:13');
INSERT INTO `inf_carrier_images` VALUES (133, 1, 1, '1/images/f1879004a7d60fa79ee9830f57134621.jpg', 14, '2018-01-31 15:21:05', '176692', NULL, '2018-01-31 15:21:05');
INSERT INTO `inf_carrier_images` VALUES (134, 2, 3, '2/images/d8fba143f41410e314a6dd8727695898.jpg', 7, '2018-02-01 20:29:48', '83753', NULL, '2018-02-01 20:29:48');
INSERT INTO `inf_carrier_images` VALUES (136, 2, 3, '2/images/e874758cca4a1bffb6ef0fe08bbbeb22.jpg', 10, '2018-02-02 16:27:08', '201342', NULL, '2018-02-02 16:27:08');
INSERT INTO `inf_carrier_images` VALUES (137, 2, 3, '2/images/673ad78aa554ca4ad2071485ee185a5c.jpg', 11, '2018-02-02 16:27:42', '213686', NULL, '2018-02-02 16:27:42');
INSERT INTO `inf_carrier_images` VALUES (138, 2, 3, '2/images/fb731e104899eef301b45b271366f8c2.jpg', 12, '2018-02-02 16:41:34', '120735', NULL, '2018-02-02 16:41:34');
INSERT INTO `inf_carrier_images` VALUES (140, 2, 3, '2/images/7e7e2e0ce58ce65d6d229595cf77c33d.jpg', 9, '2018-02-02 17:49:16', '99932', NULL, '2018-02-02 17:49:16');
INSERT INTO `inf_carrier_images` VALUES (141, 2, 3, '2/images/8ddaab89d8979684625281dc0f2da437.jpg', 4, '2018-02-03 11:19:41', '73603', NULL, '2018-02-03 11:19:41');
INSERT INTO `inf_carrier_images` VALUES (142, 2, 3, '2/images/dc46d6ae16771057d6709492253d607c.jpg', 6, '2018-02-03 11:22:59', '73603', NULL, '2018-02-03 11:22:59');
INSERT INTO `inf_carrier_images` VALUES (143, 2, 6, '2/images/554ca21eda6fb2f1e8ecd0a30ac7e848.jpg', 7, '2018-02-06 17:03:42', '148823', NULL, '2018-02-06 17:03:42');
COMMIT;

-- ----------------------------
-- Table structure for inf_carrier_pay_channel
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_pay_channel`;
CREATE TABLE `inf_carrier_pay_channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商',
  `def_pay_channel_id` tinyint(3) unsigned NOT NULL COMMENT '支付渠道类型ID',
  `binded_third_part_pay_id` int(11) DEFAULT NULL COMMENT '第三方支付绑定配置id',
  `display_name` varchar(50) DEFAULT NULL COMMENT '前台展示名称',
  `balance` decimal(11,2) DEFAULT '0.00' COMMENT '银行卡余额',
  `account` varchar(45) NOT NULL COMMENT '卡号\n1,传统银行，此处必须填写银行卡的卡号，必须填写正确\n2,三方支付银行，此处可以填写商户ID\n3,互联网银行，此处必须填写账号，比如微信账号或者支付宝账号',
  `owner_name` varchar(45) NOT NULL COMMENT '持卡人姓名 银行卡的持卡人姓名（如果该卡用于玩家存款，这个信息一定要保持正确，否则玩家将无法正确存款',
  `fee_bear_id` tinyint(1) DEFAULT '0' COMMENT '手续费承担方',
  `fee_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '手续费',
  `default_preferential_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '默认优惠比例\n如果该卡用于存款，每发生一笔存款时，赠送玩家的存款优惠比例，默认=0，表示不发放存款优惠\n如果设置为1，此时默认比例=1%，假设存款100进入，赠送的存款优惠=100×1%=1',
  `balance_notify_amount` int(11) DEFAULT '0' COMMENT '余额限额提醒,该银行卡的余额达到余额限额提醒时，在客服对玩家存款审核的界面上，将提醒该卡余额超限\n默认=0，代表不提醒，如果设置为10000，则该银行卡余额超过10000时会被提醒',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用 1、禁用 0、作废 -1\n\n启用：启用中的银行卡，可以在网站前台在玩家进行存款操作中被看到，或者客户进行取款操作时被看到\n禁用：禁用中的银行卡，可以在客户管理后台进行相关操作，但玩家在网站前台无法看到\n作废：作废中的银行卡，不能被客服看到，注意禁用时会检查银行卡的余额，余额不为0的银行卡，不能被禁用',
  `qrcode` int(11) DEFAULT '0' COMMENT '二维码',
  `use_purpose` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用途:\n1 存款：如果该卡用于存款，则必须选择该项，系统中至少应该有一张用于存款的银行卡\n2 取款：如果该卡用于给玩家出款，则必须选择该项\n3 库房：如果该卡既不是存款又不用于取款，则可设为库房\n注意：系统不允许同一张银行卡既用于存款又用于取款或者库房',
  `card_origin_place` varchar(255) NOT NULL COMMENT '开户行\n1,传统银行，此处必须填写银行卡的开户行，比如：河南郑州工行解放路分理处\n2,三方支付银行，此处可以随意填写一些标识信息\n3,互联网银行，此处可以随意填写一些标识信息',
  `show` tinyint(1) DEFAULT '1' COMMENT '展示位置',
  `single_day_deposit_limit` int(11) DEFAULT '0' COMMENT '单日存款次数限制',
  `single_deposit_minimum` int(11) DEFAULT '0' COMMENT '单次存款最小限额',
  `maximum_single_deposit` int(11) DEFAULT '0' COMMENT '单次存款最大限额',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CARRIER_PAY_CHANNEL_CHANNLE_ID_idx` (`def_pay_channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商银行卡设置';

-- ----------------------------
-- Table structure for inf_carrier_pins
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_pins`;
CREATE TABLE `inf_carrier_pins` (
  `id` int(11) NOT NULL,
  `carrier_id` int(11) NOT NULL,
  `pin_id` int(11) NOT NULL COMMENT '标签id',
  PRIMARY KEY (`id`),
  KEY `carrier_id` (`carrier_id`),
  KEY `pin_id` (`pin_id`),
  CONSTRAINT `inf_carrier_pins_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inf_carrier_pins_ibfk_2` FOREIGN KEY (`pin_id`) REFERENCES `def_pins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_player_game_plats_rebate_financial_flow
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_player_game_plats_rebate_financial_flow`;
CREATE TABLE `inf_carrier_player_game_plats_rebate_financial_flow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `carrier_player_level_id` int(11) unsigned NOT NULL COMMENT '玩家等级id',
  `carrier_game_plat_id` int(11) unsigned NOT NULL COMMENT '运营商开放的游戏平台id',
  `limit_amount_per_flow` int(11) unsigned DEFAULT '0' COMMENT '单次限额',
  `rebate_financial_flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '当前玩家等级对应的游戏平台总返水比例',
  `rebate_financial_flow_step_rate_json` varchar(500) DEFAULT NULL COMMENT '当前玩家等级对应的游戏平台阶梯返水比例 json',
  `rebate_type` tinyint(1) DEFAULT '1' COMMENT '发放返水方法   1 客服手动  2 玩家自动获取返水',
  `rebate_manual_period_hours` int(5) DEFAULT '24' COMMENT '客服手动返水周期',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carrier_player_level_id` (`carrier_player_level_id`,`carrier_game_plat_id`) USING BTREE,
  UNIQUE KEY `carrier_game_plat_id` (`carrier_game_plat_id`,`carrier_player_level_id`) USING BTREE,
  CONSTRAINT `inf_carrier_player_game_plats_rebate_financial_flow_ibfk_1` FOREIGN KEY (`carrier_game_plat_id`) REFERENCES `map_carrier_game_plats` (`game_plat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商玩家等级与游戏平台返水比例设置表';

-- ----------------------------
-- Table structure for inf_carrier_player_level
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_player_level`;
CREATE TABLE `inf_carrier_player_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户等级名称',
  `remark` varchar(50) DEFAULT NULL COMMENT '备注',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是默认等级 0否 1是',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属运营商id',
  `status` tinyint(3) DEFAULT '1' COMMENT '等级状态， 0 禁用  1 启用',
  `sort` tinyint(2) unsigned DEFAULT '1' COMMENT '排序',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `upgrade_rule` text NOT NULL COMMENT '升级规则： json表示。格式见文档',
  `deleted_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_player_news
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_player_news`;
CREATE TABLE `inf_carrier_player_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `carrier_id` int(11) NOT NULL,
  `operator_reviewer_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_service_team
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_service_team`;
CREATE TABLE `inf_carrier_service_team` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `carrier_id` int(11) DEFAULT NULL COMMENT '运营商ID',
  `team_name` varchar(50) DEFAULT NULL COMMENT '部门名称',
  `is_administrator` tinyint(1) DEFAULT '0' COMMENT '是否是管理员部门',
  `remark` varchar(255) DEFAULT NULL COMMENT '部门备注信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态1正常;0关闭',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商客服部门表';

-- ----------------------------
-- Table structure for inf_carrier_service_team_role
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_service_team_role`;
CREATE TABLE `inf_carrier_service_team_role` (
  `permission_id` int(11) unsigned NOT NULL COMMENT '权限ID',
  `team_id` int(11) unsigned NOT NULL COMMENT '运营商客服部门ID',
  `carrier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`permission_id`,`team_id`),
  KEY `team_id` (`team_id`) USING BTREE,
  KEY `carrier_id` (`carrier_id`),
  CONSTRAINT `inf_carrier_service_team_role_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_team_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `inf_carrier_service_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_template
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_template`;
CREATE TABLE `inf_carrier_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '运营商id',
  `template_id` int(11) NOT NULL COMMENT '模板id',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_carrier_user
-- ----------------------------
DROP TABLE IF EXISTS `inf_carrier_user`;
CREATE TABLE `inf_carrier_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商',
  `team_id` int(11) DEFAULT NULL COMMENT '所属部门ID',
  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `pwd_salt` char(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1:正常,0: 已锁定, -1冻结',
  `parent_id` int(11) DEFAULT NULL COMMENT '父ID',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL COMMENT '最近登录时间',
  `remember_token` varchar(255) DEFAULT NULL,
  `is_super_admin` tinyint(1) DEFAULT '0' COMMENT '是否是超级管理员, 具备所有权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商用户信息表';

-- ----------------------------
-- Table structure for inf_dashboard_menu
-- ----------------------------
DROP TABLE IF EXISTS `inf_dashboard_menu`;
CREATE TABLE `inf_dashboard_menu` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(40) NOT NULL COMMENT '状态 1显示 0不显示',
  `user_type` varchar(20) NOT NULL COMMENT '用户类型 carrier  agent admin',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可用  0 禁用 1 可用',
  `role_id` int(11) NOT NULL COMMENT '对应的用户角色',
  `parent_menu_id` int(11) DEFAULT NULL COMMENT '父级菜单id',
  `route` varchar(50) DEFAULT NULL COMMENT '路由名称',
  `icon_class` varchar(30) NOT NULL COMMENT '菜单图标icon class',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Table structure for inf_player
-- ----------------------------
DROP TABLE IF EXISTS `inf_player`;
CREATE TABLE `inf_player` (
  `player_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(64) NOT NULL COMMENT '账号',
  `mobile` varchar(15) DEFAULT NULL COMMENT '手机号码(登录账号用)',
  `real_name` varchar(64) DEFAULT NULL,
  `password` varchar(64) NOT NULL COMMENT '用户登陆密码',
  `pay_password` varchar(64) DEFAULT NULL COMMENT '支付密码(可以通过运营商设置用户是否需要支付密码)',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱登录账号用',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信',
  `consignee` varchar(100) DEFAULT NULL COMMENT '收货人',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别:0男,1女',
  `delivery_address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `agent_id` int(11) DEFAULT NULL COMMENT '代理商ID',
  `is_agent_recommend` tinyint(1) DEFAULT '0' COMMENT '是否是代理推荐玩家',
  `recommend_player_id` int(11) DEFAULT NULL COMMENT '推荐玩家ID',
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商id',
  `total_win_loss` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '总输赢, 不需要手动更改. trigger自动维护',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `main_account_amount` decimal(11,2) DEFAULT '0.00' COMMENT '主账户余额',
  `frozen_main_account_amount` decimal(11,2) DEFAULT NULL COMMENT '冻结余额',
  `login_ip` varchar(15) DEFAULT NULL COMMENT '登录ip',
  `player_level_id` int(11) unsigned DEFAULT NULL COMMENT '玩家等级id',
  `is_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在线 0不在线  1 在线',
  `user_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态:0 表示锁定(某段时间后可以重试登陆) 1表示正常 2表示关闭(用户不能再登陆)',
  `password_wrong_times` tinyint(1) DEFAULT '0' COMMENT '密码输错次数  根据此值会设置用户是否自动锁定',
  `password_wrong_time` timestamp NULL DEFAULT NULL COMMENT '密码输入错误上次输错时间',
  `login_domain` varchar(255) DEFAULT NULL COMMENT '登录域名',
  `referral_code` varchar(50) DEFAULT NULL COMMENT '邀请码',
  `recommend_url` varchar(50) DEFAULT NULL COMMENT '推荐短链接',
  `qq_account` varchar(30) DEFAULT NULL COMMENT 'qq号',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `register_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `login_at` timestamp NULL DEFAULT NULL COMMENT '登录时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '注册时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '软删除',
  `updated_at` timestamp NULL DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `remember_token` varchar(255) DEFAULT '0',
  PRIMARY KEY (`player_id`),
  UNIQUE KEY `user_name_unique` (`user_name`,`carrier_id`) USING BTREE,
  KEY `mobile` (`mobile`) USING BTREE,
  KEY `member_id` (`player_id`) USING BTREE,
  KEY `player_level_id` (`player_level_id`),
  KEY `inf_player_ibfk_1` (`agent_id`),
  CONSTRAINT `inf_player_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `inf_agent` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `inf_player_ibfk_2` FOREIGN KEY (`player_level_id`) REFERENCES `inf_carrier_player_level` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏玩家信息表';

-- ----------------------------
-- Table structure for inf_player_bank_cards
-- ----------------------------
DROP TABLE IF EXISTS `inf_player_bank_cards`;
CREATE TABLE `inf_player_bank_cards` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned NOT NULL COMMENT '运营商ID',
  `player_id` int(11) unsigned NOT NULL COMMENT '所属玩家',
  `card_account` varchar(50) DEFAULT NULL COMMENT '取款账号',
  `card_type` tinyint(3) unsigned DEFAULT NULL COMMENT '银行卡类型 外键',
  `card_owner_name` varchar(50) NOT NULL COMMENT '持卡人姓名',
  `card_birth_place` varchar(255) NOT NULL COMMENT '开户行地址',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否有效 0无效 1有效',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`card_id`),
  UNIQUE KEY `card_account` (`card_account`) USING HASH,
  KEY `player_id` (`player_id`),
  CONSTRAINT `inf_player_bank_cards_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `inf_player` (`player_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_player_collect
-- ----------------------------
DROP TABLE IF EXISTS `inf_player_collect`;
CREATE TABLE `inf_player_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `map_carrier_game_id` int(11) NOT NULL COMMENT '游戏id',
  `player_id` int(11) NOT NULL COMMENT '用户id',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carrier` (`map_carrier_game_id`,`player_id`) USING BTREE,
  KEY `map_carrier_game_id` (`map_carrier_game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_player_game_account
-- ----------------------------
DROP TABLE IF EXISTS `inf_player_game_account`;
CREATE TABLE `inf_player_game_account` (
  `account_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_game_plat_id` int(11) NOT NULL COMMENT '对应的游戏平台id',
  `player_id` int(11) DEFAULT NULL COMMENT '玩家ID',
  `account_user_name` varchar(50) NOT NULL COMMENT '账户用户名  各平台账号用户名不一样  用于注册游戏平台使用',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `is_need_repair` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要开启维护 如果开启维护, 那么用户不能登录游戏',
  `is_locked` tinyint(1) DEFAULT '0' COMMENT '账号是否锁定 1锁定 0 未锁定',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL,
  `extra_field` varchar(500) DEFAULT NULL COMMENT '其他自定义数据,  根据不同的游戏平台商的策略的自定义数据. json格式',
  PRIMARY KEY (`account_id`),
  KEY `operator_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家游戏平台账户表';

-- ----------------------------
-- Table structure for inf_player_news_relation
-- ----------------------------
DROP TABLE IF EXISTS `inf_player_news_relation`;
CREATE TABLE `inf_player_news_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `player_news_id` int(11) NOT NULL,
  `carrier_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `player_view_status` tinyint(1) DEFAULT '0' COMMENT '会员查看状态 0未读 1已读',
  `player_delete_status` tinyint(1) DEFAULT '0' COMMENT '会员删除状态 0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inf_player_transfer
-- ----------------------------
DROP TABLE IF EXISTS `inf_player_transfer`;
CREATE TABLE `inf_player_transfer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `transid` varchar(64) NOT NULL COMMENT '转帐单号',
  `carrier_id` int(11) NOT NULL COMMENT '运营商编号',
  `player_id` int(11) NOT NULL,
  `main_game_plats_id` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,0=转帐中，1=自动转帐成功，2=自动转帐失败,3=手动转帐成功,4=手动转帐失败',
  `money` decimal(10,2) NOT NULL,
  `direction` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=转入游戏平台，2=转出游戏平台',
  `operatored_at` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for log_admin_dash_operator
-- ----------------------------
DROP TABLE IF EXISTS `log_admin_dash_operator`;
CREATE TABLE `log_admin_dash_operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作用户id',
  `route_id` int(11) NOT NULL COMMENT '对应的路由id',
  `data` varchar(255) DEFAULT '' COMMENT '操作数据： 路由(Controller地址) + 参数(json)',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT 'ip',
  `operate_place` varchar(255) DEFAULT NULL,
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '操作内容，具体业务内容',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='总后台系统操作日志表';

-- ----------------------------
-- Table structure for log_agent_account
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_account`;
CREATE TABLE `log_agent_account` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL COMMENT '玩家ID',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '操作金额',
  `created_at` timestamp NULL DEFAULT NULL,
  `fund_type` tinyint(1) DEFAULT NULL COMMENT '资金类型  1 存款 2 取款 3 红利 ',
  `fund_source` varchar(50) DEFAULT NULL COMMENT '流水来源 例如: 客服调整余额',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `operator_reviewer_id` int(11) unsigned DEFAULT NULL COMMENT '运营商审核的客服id',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理账户操作日志';

-- ----------------------------
-- Table structure for log_agent_account_adjust
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_account_adjust`;
CREATE TABLE `log_agent_account_adjust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL COMMENT '所属代理用户',
  `carrier_id` int(11) NOT NULL,
  `adjust_type` tinyint(1) NOT NULL COMMENT '调整类型  1 存款 2 佣金',
  `operator` int(11) NOT NULL COMMENT '操作人',
  `amount` decimal(11,3) NOT NULL COMMENT '调整金额',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理资金调整记录表';

-- ----------------------------
-- Table structure for log_agent_deposit_pay
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_deposit_pay`;
CREATE TABLE `log_agent_deposit_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pay_order_number` varchar(45) NOT NULL COMMENT '订单编号',
  `carrier_id` int(11) NOT NULL,
  `pay_order_channel_trade_number` varchar(45) DEFAULT NULL COMMENT '与支付平台的交易号',
  `agent_id` int(11) NOT NULL COMMENT '代理用户id',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款金额',
  `finally_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账金额, 如果有红利或者优惠 实际金额可能大于存款金额',
  `benefit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `bonus_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '红利金额',
  `fee_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `pay_channel` int(11) NOT NULL COMMENT '支付渠道 外键def_pay_channel_list',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0 订单创建  1 订单支付成功  -1 订单支付失败 -2审核未通过 2订单待审核',
  `review_user_id` int(11) DEFAULT NULL COMMENT '审核人员id',
  `operate_time` timestamp NULL DEFAULT NULL COMMENT '处理时间',
  `credential` varchar(45) DEFAULT NULL COMMENT '凭据',
  `remark` varchar(45) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `offline_transfer_deposit_type` tinyint(1) DEFAULT NULL COMMENT '线下转账存款方式   1 ATM机   2 银行转账',
  `offline_transfer_deposit_at` timestamp NULL DEFAULT NULL COMMENT '线下转账会员存款时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理账户存款记录表';

-- ----------------------------
-- Table structure for log_agent_rebate_financial_flow
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_rebate_financial_flow`;
CREATE TABLE `log_agent_rebate_financial_flow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理用户ID',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '金额',
  `cathectic` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '投注额',
  `available_cathectic` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '有效投注额',
  `game_plat_id` int(11) NOT NULL,
  `log_player_bet_flow_id` int(11) DEFAULT NULL COMMENT '投注记录ID',
  `log_agent_settled_id` int(11) unsigned DEFAULT NULL COMMENT '代理佣金结算ID',
  `flow_rate` decimal(5,2) DEFAULT '0.00' COMMENT '洗码比例',
  `is_settled` tinyint(1) DEFAULT '0' COMMENT '是否已计算 0未结算，1已结算',
  `settled_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_player_bet_flow_id` (`log_player_bet_flow_id`),
  KEY `log_agent_settled_id` (`log_agent_settled_id`),
  CONSTRAINT `log_agent_rebate_financial_flow_ibfk_1` FOREIGN KEY (`log_player_bet_flow_id`) REFERENCES `log_player_bet_flow` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `log_agent_rebate_financial_flow_ibfk_2` FOREIGN KEY (`log_agent_settled_id`) REFERENCES `log_agent_settle` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理洗码记录表';

-- ----------------------------
-- Table structure for log_agent_settle
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_settle`;
CREATE TABLE `log_agent_settle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理用户ID',
  `periods_id` int(11) DEFAULT '0' COMMENT '期数ID',
  `available_member_number` varchar(100) DEFAULT NULL COMMENT '有效会员数(0未达标,最少多少)',
  `game_plat_win_amount` decimal(11,2) DEFAULT '0.00' COMMENT '公司输赢(游戏平台佣金)',
  `available_player_bet_amount` decimal(11,2) DEFAULT '0.00' COMMENT '有效会员投注额',
  `cost_share` decimal(11,2) DEFAULT '0.00' COMMENT '成本分摊(优惠、红利、洗码)',
  `cumulative_last_month` decimal(11,2) DEFAULT '0.00' COMMENT '累加上月',
  `cumulative_last_month_rebate` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '累加上月洗码金额',
  `manual_tuneup` decimal(11,2) DEFAULT '0.00' COMMENT '手工调整',
  `manual_tuneup_rebate` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '手工调整洗码金额',
  `this_period_commission` decimal(11,2) DEFAULT '0.00' COMMENT '本期佣金',
  `rebate_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '代理洗码金额',
  `actual_payment` decimal(11,2) DEFAULT '0.00' COMMENT '实际发放',
  `actual_payment_rebate` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际发放洗码金额',
  `transfer_next_month` decimal(11,2) DEFAULT '0.00' COMMENT '转结下月',
  `transfer_next_month_rebate` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '结转下月洗码金额',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1:初审 2复审 3结算完成',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_user_id` int(11) DEFAULT NULL COMMENT '创建人',
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理佣金结算记录表';

-- ----------------------------
-- Table structure for log_agent_settle_detail
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_settle_detail`;
CREATE TABLE `log_agent_settle_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned NOT NULL COMMENT '运营商id',
  `in_agent_id` int(11) unsigned NOT NULL COMMENT '收钱方代理id',
  `out_agent_id` int(11) unsigned NOT NULL COMMENT '出钱方代理id',
  `agent_settle_id` int(11) unsigned NOT NULL COMMENT '结算记录log_agent_settle表id',
  `commission_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '结算费用金额默认0.00',
  `commission_rate` decimal(8,5) NOT NULL DEFAULT '0.00000' COMMENT '结算费用比例(%)默认0.00',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结算比例对应level',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='多级代理阶级比例结算表';

-- ----------------------------
-- Records of log_agent_settle_detail
-- ----------------------------
BEGIN;
INSERT INTO `log_agent_settle_detail` VALUES (1, 1, 27, 28, 158, 0.21, 0.01000, 1, NULL, '2018-02-02 14:17:21', '2018-02-02 14:17:21');
COMMIT;

-- ----------------------------
-- Table structure for log_agent_settle_periods
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_settle_periods`;
CREATE TABLE `log_agent_settle_periods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT '0',
  `agent_id` int(11) DEFAULT '0',
  `periods` varchar(100) NOT NULL COMMENT '期数',
  `start_time` varchar(50) DEFAULT NULL,
  `end_time` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理佣金结算期数';

-- ----------------------------
-- Table structure for log_agent_undertaken
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_undertaken`;
CREATE TABLE `log_agent_undertaken` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '金额',
  `company_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司承担费用',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理用户ID',
  `log_agent_settled_id` int(11) unsigned DEFAULT '0' COMMENT '代理佣金结算ID',
  `is_settled` tinyint(1) DEFAULT '0' COMMENT '是否已计算 0未结算，1已结算',
  `settled_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '结算时间',
  `undertaken_type` tinyint(1) DEFAULT '0' COMMENT '承担类型 1:代理优惠存款承担 2:洗码承担 3:红利承担 4:取款手续费承担 5:存款手续费承担',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conf_agent_commission_id` (`agent_id`) USING BTREE,
  CONSTRAINT `log_agent_undertaken_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `inf_agent` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理优惠、洗码、红利承担表';

-- ----------------------------
-- Table structure for log_agent_win_lose_stastics
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_win_lose_stastics`;
CREATE TABLE `log_agent_win_lose_stastics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `register_count` int(11) NOT NULL DEFAULT '0' COMMENT '注册数',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录数',
  `deposit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款额',
  `first_deposit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '首存额',
  `deposit_count` int(11) NOT NULL DEFAULT '0' COMMENT '存款数',
  `first_deposit_count` int(11) NOT NULL DEFAULT '0' COMMENT '首存数',
  `withdraw_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '取款额',
  `winlose_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司输赢',
  `bonus_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '红利',
  `rebate_financial_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '洗码',
  `deposit_benefit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款优惠',
  `carrier_income` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司收入',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for log_agent_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `log_agent_withdraw`;
CREATE TABLE `log_agent_withdraw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` char(15) NOT NULL COMMENT '取款流水单号',
  `carrier_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `apply_amount` decimal(11,2) NOT NULL COMMENT '申请金额',
  `fee_amount` decimal(11,2) DEFAULT NULL COMMENT '手续费',
  `finally_withdraw_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实取金额',
  `carrier_pay_channel` int(11) DEFAULT NULL,
  `player_bank_card` int(11) DEFAULT NULL COMMENT '用户入款银行',
  `status` tinyint(1) DEFAULT NULL COMMENT '-2 待审核   -1 拒绝  1 出款',
  `reviewed_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '审核时间',
  `withdraw_succeed_at` timestamp NULL DEFAULT NULL COMMENT '出款时间',
  `operator` int(11) DEFAULT NULL COMMENT '审核人',
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_agent_withdraw_ibfk_1` (`carrier_id`),
  KEY `log_agent_withdraw_ibfk_2` (`agent_id`),
  KEY `log_agent_withdraw_ibfk_3` (`operator`),
  CONSTRAINT `log_agent_withdraw_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `log_agent_withdraw_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `inf_agent` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_agent_withdraw_ibfk_3` FOREIGN KEY (`operator`) REFERENCES `inf_carrier_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理取款申请记录表';

-- ----------------------------
-- Table structure for log_carrier_dash_operate
-- ----------------------------
DROP TABLE IF EXISTS `log_carrier_dash_operate`;
CREATE TABLE `log_carrier_dash_operate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL COMMENT '所属运营商id',
  `user_id` int(11) NOT NULL COMMENT '运营商用户id',
  `route_id` int(11) DEFAULT NULL COMMENT '路由id',
  `data` text COMMENT '操作数据json',
  `ip` varchar(15) NOT NULL COMMENT 'ip',
  `operate_place` varchar(50) DEFAULT NULL,
  `status_code` int(11) DEFAULT NULL COMMENT '状态码',
  `remark` text COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `operator_id` (`carrier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商系统后台操作日志';

-- ----------------------------
-- Table structure for log_carrier_quota_consumption
-- ----------------------------
DROP TABLE IF EXISTS `log_carrier_quota_consumption`;
CREATE TABLE `log_carrier_quota_consumption` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) DEFAULT NULL COMMENT '运营商ID',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '操作金额',
  `pay_channel_remain_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `related_pay_channel` int(11) DEFAULT NULL COMMENT '交易支付渠道',
  `consumption_source` varchar(255) DEFAULT NULL COMMENT '消费来源',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '操作时间',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运营商额度消费记录';

-- ----------------------------
-- Table structure for log_carrier_win_lose_stastics
-- ----------------------------
DROP TABLE IF EXISTS `log_carrier_win_lose_stastics`;
CREATE TABLE `log_carrier_win_lose_stastics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `register_count` int(11) NOT NULL DEFAULT '0' COMMENT '注册数',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录数',
  `deposit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款额',
  `first_deposit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '首存额',
  `deposit_count` int(11) NOT NULL DEFAULT '0' COMMENT '存款数',
  `first_deposit_count` int(11) NOT NULL DEFAULT '0' COMMENT '首存数',
  `withdraw_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '取款额',
  `winlose_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司输赢',
  `bonus_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '红利',
  `rebate_financial_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '洗码',
  `deposit_benefit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款优惠',
  `carrier_income` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司收入',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公司输赢报表';

-- ----------------------------
-- Table structure for log_game_none_exixts
-- ----------------------------
DROP TABLE IF EXISTS `log_game_none_exixts`;
CREATE TABLE `log_game_none_exixts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_plat` varchar(100) NOT NULL COMMENT '游戏平台',
  `game_flow_code` varchar(30) DEFAULT NULL COMMENT '游戏流水号',
  `game_code` varchar(45) DEFAULT NULL COMMENT '游戏代码',
  `game_name` varchar(200) DEFAULT NULL COMMENT '游戏名称',
  `message` text COMMENT '信息',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for log_game_win_lose_stastics
-- ----------------------------
DROP TABLE IF EXISTS `log_game_win_lose_stastics`;
CREATE TABLE `log_game_win_lose_stastics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `game_plat_id` int(11) NOT NULL COMMENT '游戏平台id',
  `bet_player_count` int(11) NOT NULL DEFAULT '0' COMMENT '投注人数',
  `bet_count` int(11) NOT NULL DEFAULT '0' COMMENT '投注次数',
  `bet_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '投注额',
  `win_lose_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司输赢',
  `rebate_financial_flow_amount` decimal(11,2) NOT NULL COMMENT '洗码金额',
  `average_bet_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '人均投注额',
  `average_bet_count` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '人均投注次数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏输赢汇总';

-- ----------------------------
-- Table structure for log_player_account
-- ----------------------------
DROP TABLE IF EXISTS `log_player_account`;
CREATE TABLE `log_player_account` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `player_id` int(11) DEFAULT NULL COMMENT '玩家ID',
  `main_game_plat_id` int(11) unsigned DEFAULT NULL COMMENT '游戏主平台id',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '操作金额',
  `created_at` timestamp NULL DEFAULT NULL,
  `fund_type` tinyint(1) DEFAULT NULL COMMENT '资金类型  1 存款 2 取款 3 红利 4 返水 5转账\r\n1：存款\r\n2：取款\r\n3：红利\r\n4：返水\r\n5：转账',
  `fund_source` varchar(50) DEFAULT NULL COMMENT '流水来源 例如: 从玩家主账户转出到游戏',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `operator_reviewer_id` int(11) unsigned DEFAULT NULL COMMENT '运营商审核的客服id',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_id` (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家账户操作日志';

-- ----------------------------
-- Table structure for log_player_account_adjust
-- ----------------------------
DROP TABLE IF EXISTS `log_player_account_adjust`;
CREATE TABLE `log_player_account_adjust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL COMMENT '所属玩家',
  `carrier_id` int(11) NOT NULL,
  `adjust_type` tinyint(1) NOT NULL COMMENT '调整类型  1 存款 2 返水 3 红利',
  `operator` int(11) NOT NULL COMMENT '操作人',
  `amount` decimal(11,2) NOT NULL COMMENT '调整金额',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家资金调整记录表';

-- ----------------------------
-- Table structure for log_player_activity
-- ----------------------------
DROP TABLE IF EXISTS `log_player_activity`;
CREATE TABLE `log_player_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `carrier_id` int(11) NOT NULL DEFAULT '0' COMMENT '运营商ID',
  `player_id` int(11) NOT NULL DEFAULT '0' COMMENT '玩家ID',
  `amount` decimal(11,2) DEFAULT '0.00' COMMENT '红利金额',
  `handle_way` tinyint(1) DEFAULT '1' COMMENT '处理方式 1人工审核  2自动审核',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1待审核 2通过 -1拒绝',
  `handle_at` timestamp NULL DEFAULT NULL COMMENT '处理时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家申请活动记录表';

-- ----------------------------
-- Table structure for log_player_bet_flow
-- ----------------------------
DROP TABLE IF EXISTS `log_player_bet_flow`;
CREATE TABLE `log_player_bet_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) DEFAULT NULL COMMENT '玩家id',
  `carrier_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id',
  `game_plat_id` int(11) NOT NULL COMMENT '游戏平台id',
  `game_flow_code` varchar(60) DEFAULT NULL COMMENT '游戏流水号',
  `player_or_banker` tinyint(1) NOT NULL DEFAULT '0' COMMENT '庄闲投注0无, 1庄 2闲 3庄闲都投注',
  `game_status` tinyint(1) DEFAULT '1' COMMENT '游戏状态 1 结算完成, 0 未完成，2注销',
  `game_type` varchar(255) NOT NULL DEFAULT '',
  `bet_amount` decimal(10,2) NOT NULL COMMENT '下注金额',
  `company_win_amount` decimal(10,2) NOT NULL COMMENT '公司输赢',
  `available_bet_amount` decimal(10,2) NOT NULL COMMENT '有效投注额',
  `company_payout_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '公司派彩',
  `bet_flow_available` tinyint(1) NOT NULL DEFAULT '1' COMMENT '投注流水是否有效 1 有效 0无效',
  `bet_info` text COMMENT '投注内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `progressive_bet` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '彩池投注额',
  `progressive_win` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '彩池输赢',
  `is_handle` tinyint(1) DEFAULT '0' COMMENT '是否处理 0未处理 1处理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家投注流水记录';

-- ----------------------------
-- Table structure for log_player_deposit_pay
-- ----------------------------
DROP TABLE IF EXISTS `log_player_deposit_pay`;
CREATE TABLE `log_player_deposit_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pay_order_number` varchar(45) NOT NULL COMMENT '订单编号',
  `carrier_id` int(11) NOT NULL,
  `pay_order_channel_trade_number` varchar(45) DEFAULT NULL COMMENT '与支付平台的交易号',
  `player_id` int(11) NOT NULL COMMENT '玩家id',
  `player_bank_card` int(11) DEFAULT NULL COMMENT '会员银行卡   仅线下存款有效',
  `carrier_pay_channel` int(11) NOT NULL COMMENT '运营商入款支付渠道  仅线下存款有效',
  `bank_no` varchar(20) NOT NULL DEFAULT '' COMMENT '第三方支付银行卡支付的银行卡编号',
  `pay_type` varchar(20) NOT NULL DEFAULT '' COMMENT '第三方支付类型：',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '存款金额',
  `finally_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账金额, 如果有红利或者优惠 实际金额可能大于存款金额',
  `benefit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `bonus_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '红利金额',
  `withdraw_flow_limit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '取款流水限制',
  `fee_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `is_fee_amt` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否承担手续费，0:否，1:是',
  `carrier_activity_id` int(11) DEFAULT NULL COMMENT '会员参与的活动id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0 订单创建  1 订单支付成功  -1 订单支付失败 -2审核未通过 2订单待审核',
  `review_user_id` int(11) DEFAULT NULL COMMENT '审核人员id',
  `operate_time` timestamp NULL DEFAULT NULL COMMENT '处理时间',
  `credential` varchar(45) DEFAULT NULL COMMENT '凭据',
  `remark` varchar(45) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `offline_transfer_deposit_type` tinyint(1) DEFAULT NULL COMMENT '线下转账存款方式   1 ATM机   2 银行转账',
  `offline_transfer_deposit_at` timestamp NULL DEFAULT NULL COMMENT '线下转账会员存款时间',
  `ip` varchar(50) NOT NULL COMMENT '存款ip',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carrier_id` (`carrier_id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家账户存款记录表';

-- ----------------------------
-- Table structure for log_player_invite_reward
-- ----------------------------
DROP TABLE IF EXISTS `log_player_invite_reward`;
CREATE TABLE `log_player_invite_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL COMMENT '奖励的会员对象id ',
  `reward_type` tinyint(1) DEFAULT NULL COMMENT '奖励类型  1  投注额奖励   2  存款奖励',
  `reward_related_player` int(11) DEFAULT NULL COMMENT '奖励出自于哪一个会员id 为空时表示是单独奖励  可能是运营商会给被邀请的玩家也有奖励',
  `reward_amount` decimal(11,2) DEFAULT NULL COMMENT '奖励金额',
  `related_player_deposit_amount` decimal(11,2) DEFAULT NULL COMMENT '关联的会员总存款额',
  `related_player_bet_amount` decimal(11,2) DEFAULT NULL COMMENT '关联的会员投注额',
  `related_player_validate_bet_amount` decimal(11,2) DEFAULT NULL COMMENT '关联的会员有效投注额',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员奖励结算日志';

-- ----------------------------
-- Table structure for log_player_login
-- ----------------------------
DROP TABLE IF EXISTS `log_player_login`;
CREATE TABLE `log_player_login` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL COMMENT '玩家id',
  `carrier_id` int(11) unsigned NOT NULL COMMENT '运营商id',
  `login_ip` varchar(15) NOT NULL COMMENT '登录ip',
  `login_domain` varchar(255) DEFAULT NULL COMMENT '登录域名',
  `login_time` timestamp NULL DEFAULT NULL COMMENT '登录时间',
  `login_location` varchar(255) DEFAULT NULL COMMENT '登陆地点',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_id` (`log_id`) USING BTREE,
  KEY `player_id` (`player_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员登录日志';

-- ----------------------------
-- Table structure for log_player_rebate_financial_flow
-- ----------------------------
DROP TABLE IF EXISTS `log_player_rebate_financial_flow`;
CREATE TABLE `log_player_rebate_financial_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `game_plat` int(11) NOT NULL COMMENT '游戏平台id',
  `bet_times` int(11) NOT NULL DEFAULT '0' COMMENT '投注次数',
  `rebate_financial_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '返水额',
  `bet_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '总投注流水',
  `company_pay_out_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司派彩总额',
  `is_already_settled` tinyint(1) DEFAULT '0' COMMENT '是否已结算 1 已结算 0 未结算',
  `settled_at` timestamp NULL DEFAULT NULL COMMENT '结算时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家返水记录';

-- ----------------------------
-- Table structure for log_player_rebate_financial_flow_new
-- ----------------------------
DROP TABLE IF EXISTS `log_player_rebate_financial_flow_new`;
CREATE TABLE `log_player_rebate_financial_flow_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `game_plat` int(11) NOT NULL COMMENT '游戏平台id',
  `log_player_bet_flow_id` int(11) NOT NULL DEFAULT '0' COMMENT '投注记录id',
  `rebate_financial_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '返水额',
  `bet_flow_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '投注流水额',
  `agent_out_amount` decimal(11,2) DEFAULT '0.00' COMMENT '代理承担金额',
  `company_out_amount` decimal(11,2) DEFAULT '0.00' COMMENT '公司承担金额',
  `company_pay_out_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '公司派彩额',
  `rebate_manual_period_hours` int(5) DEFAULT '168' COMMENT '返水周期',
  `is_already_settled` tinyint(1) DEFAULT '0' COMMENT '是否已结算 1 已结算 0 未结算',
  `is_effect` tinyint(1) DEFAULT '0' COMMENT '有效状态 是否自动判断失效 0有效 1失效',
  `rebate_type` tinyint(1) DEFAULT '1' COMMENT '洗码类型 1 客服手动  2 玩家手动',
  `settled_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '客服手动处理类型0未处理 1返水2返0',
  `settled_at` timestamp NULL DEFAULT NULL COMMENT '结算时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='玩家返水记录';

-- ----------------------------
-- Records of log_player_rebate_financial_flow_new
-- ----------------------------
BEGIN;
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (42, 1, 3, 10, 191, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 1, 0, 1, 0, '2018-01-18 10:28:09', '2018-01-18 09:35:15', '2018-01-18 10:28:09', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (43, 1, 3, 10, 192, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 1, 0, 1, 0, '2018-01-18 10:28:09', '2018-01-18 09:35:15', '2018-01-18 10:28:09', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (44, 1, 3, 10, 193, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 1, 0, 1, 0, '2018-01-18 10:28:09', '2018-01-18 09:35:15', '2018-01-18 10:28:09', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (45, 1, 3, 10, 194, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 1, 0, 1, 0, '2018-01-18 10:28:09', '2018-01-18 09:35:15', '2018-01-18 10:28:09', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (46, 1, 3, 10, 195, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 1, 0, 1, 0, '2018-01-18 10:28:09', '2018-01-18 09:35:15', '2018-01-18 10:28:09', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (47, 1, 3, 10, 196, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 10:30:15', '2018-01-19 10:31:14', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (48, 1, 3, 10, 197, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 10:30:15', '2018-01-19 10:31:14', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (49, 1, 3, 10, 198, 0.10, 1.00, 0.00, 0.10, 3.30, 24, 1, 0, 1, 0, '2018-01-18 12:08:12', '2018-01-18 12:05:16', '2018-01-18 12:08:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (50, 1, 3, 10, 199, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 12:05:16', '2018-01-19 12:06:07', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (51, 1, 3, 10, 200, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 15:43:05', '2018-01-19 15:43:08', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (52, 1, 3, 10, 201, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 15:43:05', '2018-01-19 15:43:08', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (53, 1, 3, 10, 202, 0.10, 1.00, 0.00, 0.10, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-18 15:44:06', '2018-01-19 15:44:08', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (54, 1, 4, 10, 281, 3.00, 30.00, 0.00, 3.00, 58.50, 24, 0, 1, 1, 0, NULL, '2018-01-19 15:32:05', '2018-01-20 15:32:06', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (55, 1, 4, 10, 282, 5.00, 50.00, 0.00, 5.00, 100.00, 24, 0, 1, 1, 0, NULL, '2018-01-19 15:33:07', '2018-01-20 15:33:08', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (56, 1, 4, 10, 283, 3.00, 30.00, 0.00, 3.00, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-19 15:34:05', '2018-01-20 15:34:07', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (57, 1, 4, 10, 284, 3.00, 30.00, 0.00, 3.00, 58.50, 24, 0, 1, 1, 0, NULL, '2018-01-19 15:35:14', '2018-01-20 15:35:20', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (58, 1, 4, 10, 285, 3.00, 30.00, 0.00, 3.00, 0.00, 24, 0, 1, 1, 0, NULL, '2018-01-19 15:36:06', '2018-01-20 15:37:07', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (59, 1, 33, 1, 342, 0.20, 10.00, 0.00, 0.20, 0.00, 168, 1, 0, 2, 0, '2018-01-30 15:16:54', '2018-02-01 15:12:04', '2018-02-01 15:16:54', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (60, 1, 33, 1, 343, 2.00, 100.00, 0.04, 1.96, 0.00, 168, 1, 0, 2, 0, '2018-01-30 15:16:51', '2018-02-01 15:16:04', '2018-02-01 15:16:51', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (61, 1, 33, 1, 344, 3.00, 100.00, 3.00, 0.00, 0.00, 168, 1, 0, 1, 0, '2018-02-01 17:51:00', '2018-02-01 17:30:05', '2018-02-01 17:51:00', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (62, 1, 33, 1, 345, 3.00, 100.00, 3.00, 0.00, 195.00, 168, 1, 0, 1, 0, '2018-02-01 17:51:04', '2018-02-01 17:30:05', '2018-02-01 17:51:04', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (63, 1, 33, 1, 346, 3.00, 100.00, 3.00, 0.00, 0.00, 168, 1, 0, 1, 0, '2018-02-01 17:50:58', '2018-02-01 17:31:05', '2018-02-01 17:50:58', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (64, 1, 33, 1, 347, 3.00, 100.00, 3.00, 0.00, 0.00, 168, 1, 0, 1, 0, '2018-02-01 17:50:54', '2018-02-01 17:32:05', '2018-02-01 17:50:54', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (65, 1, 33, 1, 348, 0.75, 25.00, 0.75, 0.00, 0.00, 168, 1, 0, 1, 0, '2018-02-01 17:50:57', '2018-02-01 17:32:05', '2018-02-01 17:50:57', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (66, 1, 34, 1, 349, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (67, 1, 34, 1, 350, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (68, 1, 34, 1, 351, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (69, 1, 34, 1, 352, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (70, 1, 34, 1, 353, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (71, 1, 34, 1, 354, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (72, 1, 34, 1, 355, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (73, 1, 34, 1, 356, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (74, 1, 34, 1, 357, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (75, 1, 34, 1, 358, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (76, 1, 34, 1, 359, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (77, 1, 34, 1, 360, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (78, 1, 34, 1, 361, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (79, 1, 34, 1, 362, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (80, 1, 34, 1, 363, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-01-31 20:36:12', '2018-01-31 20:04:04', '2018-01-31 20:36:12', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (81, 1, 34, 1, 364, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 1, 0, 2, 1, '2018-02-02 17:29:15', '2018-02-02 17:28:02', '2018-02-02 17:29:15', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (82, 1, 34, 1, 365, 0.50, 50.00, 0.40, 0.10, 97.50, 720, 1, 0, 2, 1, '2018-02-02 17:29:15', '2018-02-02 17:28:02', '2018-02-02 17:29:15', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (83, 1, 34, 1, 366, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-02-02 17:29:15', '2018-02-02 17:29:02', '2018-02-02 17:29:15', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (84, 1, 34, 1, 367, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:15:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (85, 1, 34, 1, 368, 0.25, 25.00, 0.20, 0.05, 25.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:16:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (86, 1, 34, 1, 369, 0.50, 50.00, 0.40, 0.10, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:17:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (87, 1, 34, 1, 370, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:17:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (88, 1, 34, 1, 371, 0.50, 50.00, 0.40, 0.10, 97.50, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:19:02', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (89, 1, 34, 1, 372, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:19:03', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (90, 1, 34, 1, 373, 0.50, 50.00, 0.40, 0.10, 50.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:20:06', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (91, 1, 34, 1, 374, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:20:06', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (92, 1, 34, 1, 375, 0.50, 50.00, 0.40, 0.10, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:21:04', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (93, 1, 34, 1, 376, 0.50, 50.00, 0.40, 0.10, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:22:04', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (94, 1, 34, 1, 377, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:27:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (95, 1, 34, 1, 378, 0.25, 25.00, 0.20, 0.05, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:27:06', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (96, 1, 34, 1, 379, 0.25, 25.00, 0.20, 0.05, 50.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:28:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (97, 1, 34, 1, 380, 0.50, 50.00, 0.40, 0.10, 0.00, 720, 1, 0, 2, 1, '2018-02-05 17:13:53', '2018-02-05 16:28:05', '2018-02-05 17:13:53', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (98, 1, 34, 1, 381, 0.25, 25.00, 0.20, 0.05, 25.00, 720, 0, 0, 2, 0, NULL, '2018-02-06 17:45:03', '2018-02-06 17:45:03', NULL);
INSERT INTO `log_player_rebate_financial_flow_new` VALUES (99, 1, 34, 1, 382, 0.25, 25.00, 0.20, 0.05, 48.75, 720, 0, 0, 2, 0, NULL, '2018-02-06 17:47:03', '2018-02-06 17:47:03', NULL);
COMMIT;

-- ----------------------------
-- Table structure for log_player_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `log_player_withdraw`;
CREATE TABLE `log_player_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` char(15) NOT NULL COMMENT '取款流水单号',
  `carrier_id` int(11) DEFAULT NULL,
  `player_id` int(11) unsigned DEFAULT NULL,
  `apply_amount` decimal(11,2) NOT NULL COMMENT '申请金额',
  `fee_amount` decimal(11,2) DEFAULT NULL COMMENT '手续费',
  `finally_withdraw_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '实取金额',
  `carrier_pay_channel` int(11) DEFAULT NULL COMMENT '运营商出款支付渠道',
  `player_bank_card` int(11) DEFAULT NULL COMMENT '用户入款银行',
  `status` tinyint(1) DEFAULT NULL COMMENT '-2 待审核   -1 拒绝  1 出款',
  `reviewed_at` timestamp NULL DEFAULT NULL COMMENT '审核时间',
  `withdraw_succeed_at` timestamp NULL DEFAULT NULL COMMENT '出款时间',
  `operator` int(11) DEFAULT NULL COMMENT '审核人',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carrier_id` (`carrier_id`),
  KEY `player_id` (`player_id`),
  KEY `operator` (`operator`),
  CONSTRAINT `log_player_withdraw_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `inf_carrier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `log_player_withdraw_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `inf_player` (`player_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_player_withdraw_ibfk_3` FOREIGN KEY (`operator`) REFERENCES `inf_carrier_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家取款申请记录表';

-- ----------------------------
-- Table structure for log_player_withdraw_flow_limit
-- ----------------------------
DROP TABLE IF EXISTS `log_player_withdraw_flow_limit`;
CREATE TABLE `log_player_withdraw_flow_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `player_account_log` int(11) NOT NULL COMMENT '关联的玩家账户记录表',
  `limit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '取款流水限制',
  `limit_type` tinyint(1) NOT NULL COMMENT '限额类型\n1  优惠活动\n2 自动返水\n3 手动返水\n4 调整红利\n5 玩家存款\n6 调整余额\n7 调整返水',
  `complete_limit_amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '已完成的流水限制',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已完成该流水限制',
  `operator_id` int(11) DEFAULT NULL COMMENT '处理人员 运营商用户id',
  `related_activity` int(11) DEFAULT NULL COMMENT '关联的活动id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家取款限制汇总';

-- ----------------------------
-- Table structure for log_player_withdraw_flow_limit_detail
-- ----------------------------
DROP TABLE IF EXISTS `log_player_withdraw_flow_limit_detail`;
CREATE TABLE `log_player_withdraw_flow_limit_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `withdraw_flow_limit_id` int(11) NOT NULL COMMENT '流水限制id',
  `game_plat_id` int(11) unsigned DEFAULT NULL COMMENT '游戏平台',
  `game_id` int(11) DEFAULT NULL COMMENT '游戏id',
  `flow_amount` decimal(11,2) DEFAULT '0.00' COMMENT '投注流水',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdraw_flow_limit_id` (`withdraw_flow_limit_id`),
  KEY `game_plat_id` (`game_plat_id`),
  CONSTRAINT `log_player_withdraw_flow_limit_detail_ibfk_2` FOREIGN KEY (`game_plat_id`) REFERENCES `def_game_plats` (`game_plat_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `log_player_withdraw_flow_limit_detail_ibfk_3` FOREIGN KEY (`withdraw_flow_limit_id`) REFERENCES `log_player_withdraw_flow_limit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家流水限制记录分游戏平台详细数据';

-- ----------------------------
-- Table structure for log_player_withdraw_flow_limit_game_plats
-- ----------------------------
DROP TABLE IF EXISTS `log_player_withdraw_flow_limit_game_plats`;
CREATE TABLE `log_player_withdraw_flow_limit_game_plats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_withdraw_flow_limit_id` int(11) NOT NULL COMMENT '流水限制记录id',
  `def_game_plat_id` int(11) NOT NULL COMMENT '游戏平台id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家投注流水限制平台数据';

-- ----------------------------
-- Table structure for map_carrier_game_plats
-- ----------------------------
DROP TABLE IF EXISTS `map_carrier_game_plats`;
CREATE TABLE `map_carrier_game_plats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '游戏主平台是否开放  1开放  0关闭',
  `game_plat_id` int(11) unsigned NOT NULL COMMENT '对应的游戏平台id',
  `sort` int(5) DEFAULT NULL COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carrier_id` (`carrier_id`,`game_plat_id`) USING BTREE,
  KEY `game_plat_id` (`game_plat_id`),
  CONSTRAINT `map_carrier_game_plats_ibfk_1` FOREIGN KEY (`game_plat_id`) REFERENCES `def_game_plats` (`game_plat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for map_carrier_games
-- ----------------------------
DROP TABLE IF EXISTS `map_carrier_games`;
CREATE TABLE `map_carrier_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) NOT NULL DEFAULT '1' COMMENT '所属运营商id',
  `game_id` int(11) unsigned NOT NULL COMMENT '游戏主账户ID',
  `display_name` varchar(50) DEFAULT NULL COMMENT '游戏显示名称',
  `sort` int(5) NOT NULL DEFAULT '1' COMMENT '游戏排序',
  `status` int(5) NOT NULL DEFAULT '1' COMMENT '运营商分配的游戏开放状态 1 开放  0关闭',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carrier_id` (`carrier_id`,`game_id`) USING BTREE,
  KEY `game_id` (`game_id`),
  CONSTRAINT `map_carrier_games_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `def_games` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏平台';

-- ----------------------------
-- Table structure for map_carrier_player_level_pay_channel
-- ----------------------------
DROP TABLE IF EXISTS `map_carrier_player_level_pay_channel`;
CREATE TABLE `map_carrier_player_level_pay_channel` (
  `map_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_player_level_id` int(11) unsigned NOT NULL COMMENT '对应的玩家层级id',
  `carrier_pay_channle_id` int(11) unsigned NOT NULL COMMENT '对应的支付渠道id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`),
  UNIQUE KEY `carrier_player_level_id` (`carrier_player_level_id`,`carrier_pay_channle_id`) USING BTREE,
  CONSTRAINT `map_carrier_player_level_pay_channel_ibfk_1` FOREIGN KEY (`carrier_player_level_id`) REFERENCES `inf_carrier_player_level` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2017_03_28_200915_create_notifications_table', 2);
INSERT INTO `migrations` VALUES (4, '2017_03_28_201445_create_jobs_table', 3);
INSERT INTO `migrations` VALUES (5, '2017_03_29_144821_create_failed_jobs_table', 4);
COMMIT;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for permission_group
-- ----------------------------
DROP TABLE IF EXISTS `permission_group`;
CREATE TABLE `permission_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组ID',
  `group_name` varchar(50) DEFAULT NULL COMMENT '权限分组名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `parent_id` int(11) unsigned DEFAULT NULL COMMENT '父分组ID',
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permission_group
-- ----------------------------
BEGIN;
INSERT INTO `permission_group` VALUES (1, '会员管理', 1, 0, NULL, '2017-04-21 13:32:42');
INSERT INTO `permission_group` VALUES (2, '优惠活动管理', 2, 0, NULL, '2017-04-17 15:33:03');
INSERT INTO `permission_group` VALUES (3, '系统资金', 3, 0, NULL, '2017-04-24 20:54:12');
INSERT INTO `permission_group` VALUES (4, '代理管理', 4, 0, NULL, '2017-04-17 15:33:05');
INSERT INTO `permission_group` VALUES (6, '系统设置', 5, 0, NULL, '2017-04-17 15:33:07');
INSERT INTO `permission_group` VALUES (7, '网站及图片管理', 6, 0, NULL, '2017-04-17 15:33:08');
INSERT INTO `permission_group` VALUES (8, '所有会员', 7, 1, NULL, '2017-04-21 13:33:02');
INSERT INTO `permission_group` VALUES (9, '优惠活动管理', 8, 2, NULL, '2017-04-24 15:25:44');
INSERT INTO `permission_group` VALUES (10, '支付渠道设置', 9, 3, NULL, '2017-04-24 20:54:22');
INSERT INTO `permission_group` VALUES (11, '代理审核', 10, 4, NULL, '2017-04-17 15:33:14');
INSERT INTO `permission_group` VALUES (12, '代理列表', 11, 4, NULL, '2017-04-17 15:33:16');
INSERT INTO `permission_group` VALUES (13, '代理域名', 12, 4, NULL, '2017-04-17 15:33:17');
INSERT INTO `permission_group` VALUES (14, '代理审核历史', 13, 4, NULL, '2017-04-17 15:33:19');
INSERT INTO `permission_group` VALUES (16, '代理类型设置', 15, 6, NULL, '2017-04-17 15:33:23');
INSERT INTO `permission_group` VALUES (17, '游戏平台设置', 16, 6, NULL, '2017-04-17 15:33:25');
INSERT INTO `permission_group` VALUES (18, '客服部门设置', 17, 6, NULL, '2017-04-17 15:33:26');
INSERT INTO `permission_group` VALUES (19, '客服账号设置', 18, 6, NULL, '2017-04-17 15:33:28');
INSERT INTO `permission_group` VALUES (20, '网站设置', 19, 7, NULL, '2017-04-17 15:33:30');
INSERT INTO `permission_group` VALUES (21, '会员资金', 20, 0, NULL, '2017-04-21 14:18:07');
INSERT INTO `permission_group` VALUES (22, '支付接口设置', 21, 3, NULL, NULL);
INSERT INTO `permission_group` VALUES (23, '系统参数设置', 22, 6, NULL, NULL);
INSERT INTO `permission_group` VALUES (24, '支付渠道设置', 23, 3, NULL, '2017-04-21 14:14:03');
INSERT INTO `permission_group` VALUES (26, '会员存款记录', 25, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (27, '会员编辑', 26, 8, NULL, '2017-04-21 14:20:44');
INSERT INTO `permission_group` VALUES (29, '会员取款记录', 28, 21, NULL, '2017-05-02 16:06:52');
INSERT INTO `permission_group` VALUES (30, '会员资金调整记录', 28, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (31, '会员返水', 29, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (32, '会员投注流水', 30, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (33, '会员取款流水限制', 31, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (34, '会员等级', 32, 1, NULL, NULL);
INSERT INTO `permission_group` VALUES (35, '会员资金流水', 33, 21, NULL, NULL);
INSERT INTO `permission_group` VALUES (36, '系统资金明细', 34, 3, NULL, NULL);
INSERT INTO `permission_group` VALUES (37, '邀请好友设置', 35, 1, NULL, NULL);
INSERT INTO `permission_group` VALUES (38, '投注洗码', 36, 0, NULL, NULL);
INSERT INTO `permission_group` VALUES (39, '发放投注洗码', 37, 38, NULL, NULL);
INSERT INTO `permission_group` VALUES (40, '广告图片管理', 38, 7, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned DEFAULT NULL COMMENT '权限组ID',
  `name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES (21, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@create', NULL, '显示新增游戏平台页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (22, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@store', NULL, '新增游戏平台', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (23, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@destroy', NULL, '删除游戏平台', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (24, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@show', NULL, NULL, '2017-03-13 12:10:01', NULL);
INSERT INTO `permissions` VALUES (25, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@edit', NULL, '显示编辑游戏平台页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (26, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@index', NULL, '显示银行卡列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (27, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@show', NULL, NULL, '2017-03-13 12:10:03', NULL);
INSERT INTO `permissions` VALUES (28, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@edit', NULL, '显示修改银行卡页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (29, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@destroy', NULL, '删除银行卡', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (30, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@create', NULL, '显示新增银行卡页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (31, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@update', NULL, '更新银行卡', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (32, 10, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@store', NULL, '新增银行卡', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (33, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@commissionAgent', NULL, '显示设置佣金页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (34, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@saveCommissionAgent', NULL, '保存设置佣金信息', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (41, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@index', NULL, '显示代理列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (42, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@create', NULL, '显示新增代理页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (43, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@store', NULL, '新增代理', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (44, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@update', NULL, '更新代理信息', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (45, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@destroy', NULL, '删除代理', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (46, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@show', NULL, NULL, '2017-03-13 12:10:05', NULL);
INSERT INTO `permissions` VALUES (47, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@showAgentUserInfoEditModal', NULL, '显示代理编辑模态框', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (48, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@index', NULL, '显示游戏列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (49, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@saveStatus', NULL, NULL, '2017-04-01 21:40:01', NULL);
INSERT INTO `permissions` VALUES (50, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@saveStatus', NULL, NULL, '2017-04-01 21:40:05', NULL);
INSERT INTO `permissions` VALUES (51, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierBankCardController@lister', NULL, NULL, '2017-03-13 12:10:08', NULL);
INSERT INTO `permissions` VALUES (52, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@create', NULL, '显示新增代理域名页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (53, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@store', NULL, '新增代理域名', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (54, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@update', NULL, '更新代理域名', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (55, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@destroy', NULL, '删除代理域名', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (56, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@show', NULL, NULL, '2017-03-13 12:10:10', NULL);
INSERT INTO `permissions` VALUES (57, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@edit', NULL, '显示修改代理域名页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (58, 13, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDomainController@index', NULL, '代理域名列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (59, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelBankCardMapController@update', NULL, '更新会员等级银行卡', '2017-04-26 20:21:45', NULL);
INSERT INTO `permissions` VALUES (60, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@create', NULL, '显示新增游戏列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (61, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@store', NULL, '新增游戏', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (62, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@update', NULL, '更新游戏', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (63, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@destroy', NULL, '删除游戏', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (64, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@show', NULL, NULL, '2017-03-13 12:10:13', NULL);
INSERT INTO `permissions` VALUES (65, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGameController@edit', NULL, '显示编辑游戏页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (66, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@dataAgentLevel', NULL, '', '2017-04-01 21:40:16', NULL);
INSERT INTO `permissions` VALUES (68, 19, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@editPassword', NULL, '显示修改客服密码页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (69, 19, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@savePassword', NULL, '修改客服密码', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (71, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerGamePlatRebateFinancialFlowController@update', NULL, '设置会员等级游戏平台洗码', '2017-05-02 13:34:50', NULL);
INSERT INTO `permissions` VALUES (72, 11, 'App\\Http\\Controllers\\Carrier\\CarrierAgentAuditController@index', NULL, '代理审核列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (73, 11, 'App\\Http\\Controllers\\Carrier\\CarrierAgentAuditController@audit', NULL, '显示代理审核列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (74, 11, 'App\\Http\\Controllers\\Carrier\\CarrierAgentAuditController@saveAudit', NULL, '保存代理审核', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (75, 14, 'App\\Http\\Controllers\\Carrier\\CarrierAgentAuditHistoryController@index', NULL, '代理审核历史列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (76, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@index', NULL, '所有会员列表', '2017-05-02 16:13:41', NULL);
INSERT INTO `permissions` VALUES (77, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@update', NULL, '更新会员信息', '2017-05-02 16:13:33', NULL);
INSERT INTO `permissions` VALUES (78, 35, 'App\\Http\\Controllers\\Carrier\\PlayerAccountLogController@index', NULL, '会员资金流水记录', '2017-04-24 20:51:50', NULL);
INSERT INTO `permissions` VALUES (79, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@show', NULL, '查看会员具体信息', '2017-05-02 16:15:24', NULL);
INSERT INTO `permissions` VALUES (80, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@index', NULL, '查看客服部门列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (81, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@create', NULL, '显示新增客服部门页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (82, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@store', NULL, '新增客服部门', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (83, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@update', NULL, '更新客服部门', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (84, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@destroy', NULL, '删除客服部门', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (85, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@show', NULL, NULL, '2017-03-13 12:10:21', NULL);
INSERT INTO `permissions` VALUES (86, 18, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@edit', NULL, '显示编辑客服部门页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (87, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@showFinancial', NULL, '显示会员财务信息', '2017-05-02 16:13:43', NULL);
INSERT INTO `permissions` VALUES (88, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@showTradeLog', NULL, '显示会员交易信息', '2017-05-02 16:13:44', NULL);
INSERT INTO `permissions` VALUES (89, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@gameManage', NULL, '显示会员游戏管理', '2017-05-02 16:13:46', NULL);
INSERT INTO `permissions` VALUES (90, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@showLoginLog', NULL, '显示会员登陆日志', '2017-05-02 16:13:48', NULL);
INSERT INTO `permissions` VALUES (91, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@showCheatLog', NULL, '显示防套利', '2017-04-21 14:20:58', NULL);
INSERT INTO `permissions` VALUES (92, 27, 'App\\Http\\Controllers\\Carrier\\PlayerController@showRecommendLog', NULL, '显示推荐会员日志', '2017-05-02 16:13:51', NULL);
INSERT INTO `permissions` VALUES (93, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@create', NULL, '显示新增客服账号页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (94, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@update', NULL, '更新客服账号', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (95, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@destroy', NULL, '删除客服账号', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (96, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@show', NULL, NULL, '2017-03-13 12:10:28', NULL);
INSERT INTO `permissions` VALUES (97, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@edit', NULL, '显示编辑客服账号页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (98, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@editPassword', NULL, '显示修改客服密码页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (99, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageController@index', NULL, '显示图片列表', '2017-05-02 16:01:21', NULL);
INSERT INTO `permissions` VALUES (100, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageController@show', NULL, '显示图片', '2017-05-02 16:01:22', NULL);
INSERT INTO `permissions` VALUES (101, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageController@destroy', NULL, '删除图片', '2017-05-02 16:01:24', NULL);
INSERT INTO `permissions` VALUES (102, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@index', NULL, '显示素材类别列表', '2017-05-02 16:01:25', NULL);
INSERT INTO `permissions` VALUES (103, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@edit', NULL, '显示编辑素材页面', '2017-05-02 16:01:26', NULL);
INSERT INTO `permissions` VALUES (104, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@update', NULL, '更新素材类别信息', '2017-05-02 16:01:28', NULL);
INSERT INTO `permissions` VALUES (105, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@destroy', NULL, '删除素材类别', '2017-05-02 16:01:30', NULL);
INSERT INTO `permissions` VALUES (106, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@savePassword', NULL, '保存修改客服账号密码', '2017-04-01 21:21:43', NULL);
INSERT INTO `permissions` VALUES (107, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@permissionSetShow', NULL, NULL, '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (109, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageController@store', NULL, '上传图片', '2017-05-02 16:01:49', NULL);
INSERT INTO `permissions` VALUES (110, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@index', NULL, '优惠活动类型列表', '2017-03-11 20:56:17', NULL);
INSERT INTO `permissions` VALUES (111, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@create', NULL, '显示优惠活动类型创建页面', '2017-03-11 20:56:18', NULL);
INSERT INTO `permissions` VALUES (112, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@store', NULL, '新增优惠活动类型', '2017-03-11 20:56:22', NULL);
INSERT INTO `permissions` VALUES (113, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@edit', NULL, '显示优惠活动类型编辑页面', '2017-03-11 20:56:23', NULL);
INSERT INTO `permissions` VALUES (114, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@update', NULL, '更新优惠活动类型', '2017-03-11 20:56:24', NULL);
INSERT INTO `permissions` VALUES (115, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@destroy', NULL, '删除优惠活动类型', '2017-03-11 20:56:24', NULL);
INSERT INTO `permissions` VALUES (116, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityTypeController@saveStatus', NULL, '优惠活动禁用或启用', '2017-04-01 21:58:24', NULL);
INSERT INTO `permissions` VALUES (120, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@index', NULL, '优惠活动列表', '2017-04-24 15:22:25', NULL);
INSERT INTO `permissions` VALUES (121, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@create', NULL, '显示优惠活动创建页面', '2017-04-24 15:22:27', NULL);
INSERT INTO `permissions` VALUES (122, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@store', NULL, '新增优惠活动', '2017-04-24 15:22:34', NULL);
INSERT INTO `permissions` VALUES (123, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@create', NULL, '显示新增素材列表页面', '2017-05-02 16:04:44', NULL);
INSERT INTO `permissions` VALUES (124, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageCategoryController@store', NULL, '新增素材信息', '2017-05-02 16:04:46', NULL);
INSERT INTO `permissions` VALUES (125, 40, 'App\\Http\\Controllers\\Carrier\\CarrierImageController@update', NULL, '更新图片信息', '2017-05-02 16:04:49', NULL);
INSERT INTO `permissions` VALUES (126, 20, 'App\\Http\\Controllers\\Carrier\\CarrierWebSiteConfController@update', NULL, '更新网站设置', '2017-05-02 16:05:15', NULL);
INSERT INTO `permissions` VALUES (127, 20, 'App\\Http\\Controllers\\Carrier\\CarrierWebSiteConfController@index', NULL, '显示网站设置界面', '2017-05-02 16:05:20', NULL);
INSERT INTO `permissions` VALUES (130, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierServiceTeamController@permissionSave', NULL, NULL, NULL, NULL);
INSERT INTO `permissions` VALUES (131, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@index', NULL, '运营商客服账号管理', '2017-04-21 14:22:48', '2017-02-27 09:13:44');
INSERT INTO `permissions` VALUES (132, 19, 'App\\Http\\Controllers\\Carrier\\CarrierUserController@store', NULL, '新增运营商账号', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (133, 21, 'App\\Http\\Controllers\\Carrier\\HomeController@index', NULL, '主页', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (134, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@index', NULL, '查看会员等级列表', '2017-05-02 16:24:15', NULL);
INSERT INTO `permissions` VALUES (135, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@create', NULL, '显示会员等级创建页面', '2017-05-02 16:24:16', NULL);
INSERT INTO `permissions` VALUES (136, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@store', NULL, '新增会员等级', '2017-05-02 16:24:17', NULL);
INSERT INTO `permissions` VALUES (137, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@destroy', NULL, '删除会员等级', '2017-05-02 16:24:18', NULL);
INSERT INTO `permissions` VALUES (138, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@update', NULL, '更新会员等级', '2017-05-02 16:24:19', NULL);
INSERT INTO `permissions` VALUES (139, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@edit', NULL, '显示编辑会员等级页面', '2017-05-02 16:24:20', NULL);
INSERT INTO `permissions` VALUES (140, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@index', NULL, '显示代理等级列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (141, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@index', NULL, '显示游戏平台列表', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (142, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@create', NULL, '显示代理等级创建页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (143, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@store', NULL, '新增代理等级', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (144, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@show', NULL, NULL, '2017-03-15 16:50:23', NULL);
INSERT INTO `permissions` VALUES (146, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@update', NULL, '更新代理类型', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (147, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@destroy', NULL, '删除代理类型', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (148, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@show', NULL, NULL, '2017-03-15 16:50:32', NULL);
INSERT INTO `permissions` VALUES (149, 16, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@edit', NULL, '显示编辑代理类型页面', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (150, 17, 'App\\Http\\Controllers\\Carrier\\CarrierGamePlatController@update', NULL, '更新游戏平台', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (151, 23, 'App\\Http\\Controllers\\Carrier\\CarrierDashLoginConfController@index', NULL, '显示登录注册配置', '2017-04-21 14:10:56', NULL);
INSERT INTO `permissions` VALUES (152, 23, 'App\\Http\\Controllers\\Carrier\\CarrierDashLoginConfController@update', NULL, '更新登录注册配置', '2017-04-21 14:11:02', NULL);
INSERT INTO `permissions` VALUES (157, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@index', NULL, '显示运营商支付渠道列表', '2017-04-21 14:13:23', NULL);
INSERT INTO `permissions` VALUES (158, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@create', NULL, '显示新增运营商支付渠道界面', '2017-04-21 14:13:25', NULL);
INSERT INTO `permissions` VALUES (159, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@store', NULL, '新增运营商支付渠道', '2017-04-21 14:13:25', NULL);
INSERT INTO `permissions` VALUES (160, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@show', NULL, '显示某个运营商支付渠道', '2017-04-21 14:13:27', NULL);
INSERT INTO `permissions` VALUES (161, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@update', NULL, '更新运营商支付渠道', '2017-04-21 14:13:27', NULL);
INSERT INTO `permissions` VALUES (162, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@destroy', NULL, '删除运营商支付渠道', '2017-04-21 14:13:28', NULL);
INSERT INTO `permissions` VALUES (166, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@edit', NULL, '显示编辑运营商支付渠道界面', '2017-04-24 15:29:28', NULL);
INSERT INTO `permissions` VALUES (167, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayLogController@index', NULL, '显示会员存款列表', '2017-04-21 14:18:59', NULL);
INSERT INTO `permissions` VALUES (168, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayLogController@edit', NULL, '显示编辑会员存款界面', '2017-04-21 14:19:01', NULL);
INSERT INTO `permissions` VALUES (169, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayLogController@update', NULL, '更新会员存款信息', '2017-04-21 14:19:03', NULL);
INSERT INTO `permissions` VALUES (170, 22, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@saveStatus', NULL, '更改支付渠道状态', '2017-04-21 14:01:41', NULL);
INSERT INTO `permissions` VALUES (171, 22, 'App\\Http\\Controllers\\Carrier\\CarrierThirdPartPayController@index', NULL, '显示支付接口列表', '2017-04-21 14:02:25', NULL);
INSERT INTO `permissions` VALUES (172, 22, 'App\\Http\\Controllers\\Carrier\\CarrierThirdPartPayController@create', NULL, '显示新建支付接口设置界面', '2017-04-21 14:03:01', NULL);
INSERT INTO `permissions` VALUES (173, 22, 'App\\Http\\Controllers\\Carrier\\CarrierThirdPartPayController@update', NULL, '更新支付接口设置', '2017-04-21 14:02:40', NULL);
INSERT INTO `permissions` VALUES (174, 22, 'App\\Http\\Controllers\\Carrier\\CarrierThirdPartPayController@store', NULL, '新建支付接口设置', '2017-04-21 14:02:51', NULL);
INSERT INTO `permissions` VALUES (175, 22, 'App\\Http\\Controllers\\Carrier\\CarrierThirdPartPayController@edit', NULL, '显示编辑支付接口设置界面', '2017-04-21 14:03:12', NULL);
INSERT INTO `permissions` VALUES (176, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@edit', NULL, '显示优惠活动编辑页面', '2017-04-24 15:23:35', NULL);
INSERT INTO `permissions` VALUES (177, NULL, 'App\\Http\\Controllers\\Carrier\\Auth\\LoginController@logout ', NULL, NULL, '2017-03-21 17:38:14', NULL);
INSERT INTO `permissions` VALUES (178, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@update', NULL, '更新优惠活动', '2017-04-24 15:23:21', NULL);
INSERT INTO `permissions` VALUES (179, 23, 'App\\Http\\Controllers\\Carrier\\CarrierDepositConfController@index', NULL, '显示运营商存款设置界面', '2017-04-21 14:05:27', NULL);
INSERT INTO `permissions` VALUES (180, 23, 'App\\Http\\Controllers\\Carrier\\CarrierDepositConfController@update', NULL, '更新运营商存款设置', '2017-04-21 14:05:29', NULL);
INSERT INTO `permissions` VALUES (181, 23, 'App\\Http\\Controllers\\Carrier\\CarrierWithdrawConfController@index', NULL, '显示运营商取款设置界面', '2017-04-21 14:05:30', NULL);
INSERT INTO `permissions` VALUES (182, 23, 'App\\Http\\Controllers\\Carrier\\CarrierWithdrawConfController@update', NULL, '更新运营商取款设置', '2017-04-21 14:05:33', NULL);
INSERT INTO `permissions` VALUES (183, 23, 'App\\Http\\Controllers\\Carrier\\CarrierPasswordRecoverySiteConfController@index', NULL, '显示找回密码设置界面', '2017-04-21 14:05:50', NULL);
INSERT INTO `permissions` VALUES (184, 23, 'App\\Http\\Controllers\\Carrier\\CarrierPasswordRecoverySiteConfController@update', NULL, '更新找回密码设置', '2017-04-21 14:05:56', NULL);
INSERT INTO `permissions` VALUES (188, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@payList', NULL, '支付列表', '2017-04-24 15:31:05', NULL);
INSERT INTO `permissions` VALUES (189, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@bindPayList', NULL, '绑定第三方支付', '2017-04-24 15:31:00', NULL);
INSERT INTO `permissions` VALUES (190, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@unbundList', NULL, '解绑第三方支付', '2017-04-24 15:30:55', NULL);
INSERT INTO `permissions` VALUES (191, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@unbundPayList', NULL, '解绑三方支付列表', '2017-04-24 15:30:50', NULL);
INSERT INTO `permissions` VALUES (192, 23, 'App\\Http\\Controllers\\Carrier\\CarrierRegisterBasicConfController@index', NULL, '显示运营商注册基本信息配置界面', '2017-04-24 15:33:10', NULL);
INSERT INTO `permissions` VALUES (193, 23, 'App\\Http\\Controllers\\Carrier\\CarrierRegisterBasicConfController@update', NULL, '更新运营商注册基本信息配置界面', '2017-04-24 15:33:13', NULL);
INSERT INTO `permissions` VALUES (194, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@platformFee', NULL, '代理平台费界面', '2017-04-24 15:33:33', NULL);
INSERT INTO `permissions` VALUES (195, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@savePlatformFee', NULL, '更新代理平台费', '2017-04-24 15:33:37', NULL);
INSERT INTO `permissions` VALUES (196, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@showBankManage', NULL, '显示银行管理界面', '2017-04-24 15:33:52', NULL);
INSERT INTO `permissions` VALUES (197, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateRealName', NULL, '更新会员姓名', '2017-05-02 16:13:22', NULL);
INSERT INTO `permissions` VALUES (198, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateInviteUser', NULL, '更新会员邀请人', '2017-05-02 16:13:19', NULL);
INSERT INTO `permissions` VALUES (199, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateTelephone', NULL, '更新会员手机号', '2017-05-02 16:13:18', NULL);
INSERT INTO `permissions` VALUES (200, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateLevel', NULL, '更新会员等级', '2017-05-02 16:13:15', NULL);
INSERT INTO `permissions` VALUES (201, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateEmail', NULL, '更新会员邮箱地址', '2017-05-02 16:13:14', NULL);
INSERT INTO `permissions` VALUES (202, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateAgent', NULL, '更新会员所属代理', '2017-05-02 16:13:12', NULL);
INSERT INTO `permissions` VALUES (203, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@queryAndSynchronizePlayerAllGameAccountsToDB', NULL, '同步会员游戏账户余额', '2017-05-02 16:13:11', NULL);
INSERT INTO `permissions` VALUES (204, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@withDrawAllPlayerGameAccounts', NULL, '一键转出会员所有游戏余额', '2017-05-02 16:13:09', NULL);
INSERT INTO `permissions` VALUES (205, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updatePlayerPTGamePassword', NULL, '修改会员PT游戏密码', '2017-05-02 16:13:07', NULL);
INSERT INTO `permissions` VALUES (206, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@withDrawPlayerGameAccount', NULL, '一键转出会员游戏账户余额至主账户', '2017-05-02 16:13:03', NULL);
INSERT INTO `permissions` VALUES (207, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@depositPlayerGameAccount', NULL, '一键将所有主账户余额转入会员游戏账户', '2017-05-02 16:13:05', NULL);
INSERT INTO `permissions` VALUES (208, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@switchPlayerGameAccountTransferLockStatus', NULL, '将会员转账锁定', '2017-05-02 16:12:58', NULL);
INSERT INTO `permissions` VALUES (209, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@switchPlayerGameCloseStatus', NULL, '将会员游戏关闭', '2017-05-02 16:12:55', NULL);
INSERT INTO `permissions` VALUES (212, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@rebateFinancialFlowAgent', NULL, '洗码代理页面设置', '2017-04-24 15:34:49', NULL);
INSERT INTO `permissions` VALUES (213, 12, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@saveRebateFinancialFlowAgent', NULL, '保存洗码代理设置', '2017-04-24 15:34:51', NULL);
INSERT INTO `permissions` VALUES (214, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@kickPlayerOutLine', NULL, '将会员强制踢线', '2017-05-02 16:12:53', NULL);
INSERT INTO `permissions` VALUES (215, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityController@saveStatus', NULL, '保存优惠活动已上架或已下架', '2017-04-24 15:24:04', NULL);
INSERT INTO `permissions` VALUES (219, 30, 'App\\Http\\Controllers\\Carrier\\PlayerAccountAdjustLogController@index', NULL, '会员资金调整记录列表', '2017-04-24 15:35:53', NULL);
INSERT INTO `permissions` VALUES (221, 30, 'App\\Http\\Controllers\\Carrier\\PlayerAccountAdjustLogController@store', NULL, '调整会员资金', '2017-05-02 16:11:41', NULL);
INSERT INTO `permissions` VALUES (222, 34, 'App\\Http\\Controllers\\Carrier\\CarrierPlayerLevelController@bankCardAll', NULL, '会员等级支付渠道', '2017-04-24 15:38:43', NULL);
INSERT INTO `permissions` VALUES (223, 33, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawFlowLimitLogController@index', NULL, '取款流水限制列表', '2017-04-24 15:39:00', NULL);
INSERT INTO `permissions` VALUES (224, 33, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawFlowLimitLogController@update', NULL, '更新取款流水限制', '2017-04-24 15:39:12', NULL);
INSERT INTO `permissions` VALUES (225, 32, 'App\\Http\\Controllers\\Carrier\\PlayerBetFlowLogController@index', NULL, '查看会员投注明细列表', '2017-05-02 16:14:34', NULL);
INSERT INTO `permissions` VALUES (226, 32, 'App\\Http\\Controllers\\Carrier\\PlayerBetFlowLogController@update', NULL, '更新会员投注记录', '2017-05-02 16:14:32', NULL);
INSERT INTO `permissions` VALUES (227, 31, 'App\\Http\\Controllers\\Carrier\\PlayerRebateFinancialFlowController@index', NULL, '查看会员返水明细列表', '2017-05-02 16:14:36', NULL);
INSERT INTO `permissions` VALUES (228, 31, 'App\\Http\\Controllers\\Carrier\\PlayerRebateFinancialFlowController@update', NULL, '更新会员返水记录', '2017-05-02 16:14:37', NULL);
INSERT INTO `permissions` VALUES (229, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@showPlayerBonusSettingModal', NULL, '显示调整红利界面', '2017-04-24 15:36:10', NULL);
INSERT INTO `permissions` VALUES (232, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updatePlayerLoginPassword', NULL, '修改会员登陆密码', '2017-05-02 16:14:43', NULL);
INSERT INTO `permissions` VALUES (234, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updatePlayerPayPassword', NULL, '修改会员取款密码', '2017-05-02 16:14:46', NULL);
INSERT INTO `permissions` VALUES (235, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@togglePlayerAccountStatus', NULL, '切换会员账户状态', '2017-05-02 16:14:48', NULL);
INSERT INTO `permissions` VALUES (236, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityAuditController@index', NULL, '显示待审核活动列表', '2017-04-25 15:19:02', NULL);
INSERT INTO `permissions` VALUES (237, 29, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@index', NULL, '显示取款数据列表', '2017-04-21 19:43:45', NULL);
INSERT INTO `permissions` VALUES (238, 29, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@update', NULL, '更新取款信息', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (240, 29, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@resetWithdrawFlowRecord', NULL, '清除某条取款流水限制', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (241, 29, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@refuseWithdrawApply', NULL, '拒绝取款社情', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (243, 29, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@show', NULL, '显示取款信息', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (245, 9, 'App\\Http\\Controllers\\Carrier\\CarrierActivityAuditController@update', NULL, '审核会员参与的活动', '2017-05-02 16:14:54', NULL);
INSERT INTO `permissions` VALUES (246, NULL, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawLogController@payModal', NULL, NULL, NULL, NULL);
INSERT INTO `permissions` VALUES (250, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@costTakeAgent', NULL, '占成代理显示页面', '2017-04-13 16:47:39', NULL);
INSERT INTO `permissions` VALUES (251, 39, 'App\\Http\\Controllers\\Carrier\\PlayerRebateFinancialFlowController@passRebateFinancialFlowLog', NULL, '发放会员洗码', '2017-05-02 16:14:58', NULL);
INSERT INTO `permissions` VALUES (252, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentLevelController@saveCostTakeAgent', NULL, '保存占成代理', '2017-04-13 16:47:48', NULL);
INSERT INTO `permissions` VALUES (254, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@agentSubPlayer', NULL, '代理下线会员', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (255, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@subAgent', NULL, '代理旗下的代理用户', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (256, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@agentAmount', NULL, '修改代理余额', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (257, 36, 'App\\Http\\Controllers\\Carrier\\CarrierQuotaConsumptionLogController@index', NULL, '系统资金明细', '2017-04-24 20:55:02', NULL);
INSERT INTO `permissions` VALUES (258, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@experienceAmount', NULL, '修改代理会员礼金', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (259, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@updateTelephone', NULL, '修改代理手机号', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (260, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@updateEmail', NULL, '修改代理邮箱', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (261, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@updateRealName', NULL, '修改代理真实姓名', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (262, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@edit', NULL, '编辑代理信息', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (263, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@editAgentType', NULL, '编辑代理类型', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (264, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@saveAgentType', NULL, '保存代理类型', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (265, NULL, 'App\\Http\\Controllers\\Carrier\\AgentAccountAdjustLogController@store', NULL, NULL, NULL, NULL);
INSERT INTO `permissions` VALUES (266, NULL, 'App\\Http\\Controllers\\Carrier\\AgentAccountAdjustLogController@index', NULL, '代理调整资金记录', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (267, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentUserController@saveExperienceAmount', NULL, '修改代理会员礼金', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (268, 12, 'isSupportMultilevelCommissionAgent', NULL, '开设佣金代理', '2017-04-21 14:22:48', NULL);
INSERT INTO `permissions` VALUES (269, 37, 'App\\Http\\Controllers\\Carrier\\CarrierInviteFriendController@saveInvitePlayerConf', NULL, '保存邀请好友设置', '2017-05-02 16:26:23', NULL);
INSERT INTO `permissions` VALUES (270, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@index', NULL, '代理佣金结算', '2017-04-27 21:05:50', NULL);
INSERT INTO `permissions` VALUES (271, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@create', NULL, '创建代理佣金结算单', '2017-04-27 21:05:54', NULL);
INSERT INTO `permissions` VALUES (272, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@store', NULL, '确认是否创建代理佣金结算单', '2017-04-27 21:06:02', NULL);
INSERT INTO `permissions` VALUES (273, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayReviewLogController@index', NULL, '显示待审核存款列表', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (274, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayReviewLogController@reviewDepositLog', NULL, '审核存款', '2017-04-24 14:59:21', NULL);
INSERT INTO `permissions` VALUES (275, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayReviewLogController@show', NULL, '显示待审核存款信息', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (276, 26, 'App\\Http\\Controllers\\Carrier\\PlayerDepositPayReviewLogController@destroy', NULL, '删除待审核存款数据', '2017-04-21 19:43:46', NULL);
INSERT INTO `permissions` VALUES (277, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentDepositPayLogController@index', NULL, '代理存款记录', '2017-04-25 13:41:32', NULL);
INSERT INTO `permissions` VALUES (278, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentWithdrawLogController@index', NULL, '代理取款记录', NULL, NULL);
INSERT INTO `permissions` VALUES (279, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentWithdrawLogController@refuseModal', NULL, '显示拒绝代理申请', NULL, NULL);
INSERT INTO `permissions` VALUES (280, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentWithdrawLogController@refuseWithdrawApply', NULL, '保存拒绝代理申请', NULL, NULL);
INSERT INTO `permissions` VALUES (281, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentWithdrawLogController@payModal', NULL, '显示通过代理申请', NULL, NULL);
INSERT INTO `permissions` VALUES (282, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentWithdrawLogController@update', NULL, '通过代理申请', '2017-04-26 14:00:40', NULL);
INSERT INTO `permissions` VALUES (283, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@gamePlatWinAmount', NULL, '代理结算公司输赢', '2017-04-27 21:01:19', NULL);
INSERT INTO `permissions` VALUES (284, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@costShare', NULL, '代理结算成本分摊', '2017-04-27 21:01:22', NULL);
INSERT INTO `permissions` VALUES (285, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@manualTuneup', NULL, '代理结算手工调整', '2017-04-27 21:01:27', NULL);
INSERT INTO `permissions` VALUES (286, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@actualPayment', NULL, '代理结算实际发放', '2017-04-27 21:01:30', NULL);
INSERT INTO `permissions` VALUES (287, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@saveManualTuneup', NULL, '代理结算保存手工调整金额', '2017-04-27 21:01:33', NULL);
INSERT INTO `permissions` VALUES (288, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@saveActualPayment', NULL, '代理结算保存实际发放', '2017-04-27 21:01:38', NULL);
INSERT INTO `permissions` VALUES (289, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@theTrial', NULL, '代理结算初审', '2017-04-27 21:01:44', NULL);
INSERT INTO `permissions` VALUES (290, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@saveTheTrial', NULL, '代理结算保存初审', '2017-04-27 21:01:47', NULL);
INSERT INTO `permissions` VALUES (291, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@reviewTrial', NULL, '代理结算复审', '2017-04-27 21:01:51', NULL);
INSERT INTO `permissions` VALUES (292, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@saveReviewTrial', NULL, '代理结算保存复审', '2017-04-27 21:01:55', NULL);
INSERT INTO `permissions` VALUES (293, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleHistoryLogController@index', NULL, '代理结算历史', '2017-04-27 21:01:58', NULL);
INSERT INTO `permissions` VALUES (294, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@reSettlement', NULL, '代理结算重新结算', '2017-04-27 21:02:02', NULL);
INSERT INTO `permissions` VALUES (295, NULL, 'App\\Http\\Controllers\\Carrier\\CarrierAgentSettleLogController@saveReSettlement', NULL, '代理结算保存重新结算', '2017-04-27 21:02:06', NULL);
INSERT INTO `permissions` VALUES (296, 24, 'App\\Http\\Controllers\\Carrier\\CarrierPayChannelController@newManualTransferRecord', NULL, '手动新增转账记录', '2017-04-28 15:54:33', NULL);
INSERT INTO `permissions` VALUES (297, 37, 'App\\Http\\Controllers\\Carrier\\CarrierInviteFriendController@showEdit', NULL, '显示邀请好友设置界面', '2017-05-02 16:26:30', NULL);
INSERT INTO `permissions` VALUES (298, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateBirthday', NULL, '更新会员出生日期', NULL, NULL);
INSERT INTO `permissions` VALUES (299, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateQQ', NULL, '更新会员QQ', NULL, NULL);
INSERT INTO `permissions` VALUES (300, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@updateWechat', NULL, '更新会员微信号', NULL, NULL);
INSERT INTO `permissions` VALUES (301, 8, 'App\\Http\\Controllers\\Carrier\\PlayerController@exportInfo', NULL, '导出会员信息', '2017-05-05 22:55:52', NULL);
INSERT INTO `permissions` VALUES (302, NULL, 'App\\Http\\Controllers\\Carrier\\PlayerWithdrawFlowLimitLogController@passCompleteFinished', NULL, '重启/完成', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `user_type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户角色类型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_team_id` int(11) DEFAULT NULL COMMENT '运营商部门id,  仅当user_type为 carrier时生效',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'carrier  agent  admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Triggers structure for table inf_carrier
-- ----------------------------
DROP TRIGGER IF EXISTS `CREATE_CARRIER_PREPARE_WORK`;
delimiter ;;
CREATE TRIGGER `CREATE_CARRIER_PREPARE_WORK` AFTER INSERT ON `inf_carrier` FOR EACH ROW BEGIN

	#新建运营商的准备工作处理
	#新建一个默认的部门 管理员
	INSERT INTO inf_carrier_service_team (carrier_id,team_name,is_administrator) VALUES (NEW.id,'管理员',1);

	#创建默认代理
	INSERT INTO inf_agent (username,realname,agent_level_id,is_default,`status`,audit_status,carrier_id) VALUES (CONCAT(NEW.name,'_CARRIER_DEFAULT_AGENT'),CONCAT(NEW.name,'_CARRIER_DEFAULT_AGENT'),null,1,1,1,NEW.id);

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_agent_level
-- ----------------------------
DROP TRIGGER IF EXISTS `NEW_AGENT_TYPE`;
delimiter ;;
CREATE TRIGGER `NEW_AGENT_TYPE` AFTER INSERT ON `inf_carrier_agent_level` FOR EACH ROW BEGIN
      DECLARE Done INT DEFAULT 0;
      #声明变量  玩家等级id
      DECLARE GamePlatId INT(11);
      #声明游标
      DECLARE rs CURSOR FOR SELECT game_plat_id FROM map_carrier_game_plats WHERE carrier_id = NEW.carrier_id;
     #将结束标志绑定到游标
     DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = 1;
   IF NEW.TYPE=1 THEN
         INSERT INTO conf_carrier_commission_agent (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);
         OPEN rs;    
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT
              
	INSERT INTO conf_carrier_commission_agent_platform_fee (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;

   ELSEIF NEW.TYPE = 2 THEN
       INSERT INTO conf_carrier_rebate_financial_flow_agent_base_info (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);
       OPEN rs;    
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT
	INSERT INTO conf_carrier_rebate_financial_flow_agent (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
            
              
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;

  ELSEIF NEW.TYPE = 3 THEN
          INSERT INTO conf_carrier_cost_take_agent (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);	
         OPEN rs;	
              FETCH NEXT FROM rs INTO GamePlatId;	
              REPEAT
              INSERT INTO conf_carrier_cost_take_agent_platform_fee (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
             
              FETCH NEXT FROM rs INTO GamePlatId;
              UNTIL Done END REPEAT;
        CLOSE rs;

   END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_agent_level
-- ----------------------------
DROP TRIGGER IF EXISTS `UPDATA_AGENT_TYPE`;
delimiter ;;
CREATE TRIGGER `UPDATA_AGENT_TYPE` AFTER UPDATE ON `inf_carrier_agent_level` FOR EACH ROW BEGIN
      DECLARE Done INT DEFAULT 0;
      #声明变量  玩家等级id
      DECLARE GamePlatId INT(11);
      #声明游标
      DECLARE rs CURSOR FOR SELECT game_plat_id FROM map_carrier_game_plats WHERE carrier_id = NEW.carrier_id;
     #将结束标志绑定到游标
     DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = 1;
    IF NEW.TYPE != OLD.TYPE THEN
   IF NEW.TYPE=1 THEN
         DELETE FROM conf_carrier_commission_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
         INSERT INTO conf_carrier_commission_agent (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);
         DELETE FROM conf_carrier_commission_agent_platform_fee WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
         DELETE FROM conf_carrier_rebate_financial_flow_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
         DELETE FROM conf_carrier_rebate_financial_flow_agent_base_info WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
       
         DELETE FROM conf_carrier_cost_take_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
         DELETE FROM conf_carrier_cost_take_agent_platform_fee WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;

         OPEN rs;    
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT 
	    INSERT INTO conf_carrier_commission_agent_platform_fee (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;

         
   ELSEIF NEW.TYPE = 2 THEN
              DELETE FROM conf_carrier_commission_agent_platform_fee WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
              DELETE FROM conf_carrier_commission_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
              DELETE FROM conf_carrier_rebate_financial_flow_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
              DELETE FROM conf_carrier_rebate_financial_flow_agent_base_info WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
              INSERT INTO conf_carrier_rebate_financial_flow_agent_base_info (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);
              DELETE FROM conf_carrier_cost_take_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
              DELETE FROM conf_carrier_cost_take_agent_platform_fee WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;

              
       OPEN rs;    
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT
	    INSERT INTO conf_carrier_rebate_financial_flow_agent (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
                 
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;

 ELSEIF NEW.TYPE = 3 THEN
              DELETE FROM conf_carrier_commission_agent_platform_fee WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
              DELETE FROM conf_carrier_rebate_financial_flow_agent WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
              DELETE FROM conf_carrier_commission_agent  WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
              DELETE FROM conf_carrier_rebate_financial_flow_agent_base_info WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;

              DELETE FROM conf_carrier_cost_take_agent  WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id; 
              DELETE FROM conf_carrier_cost_take_agent_platform_fee  WHERE agent_level_id = NEW.id AND carrier_id =NEW.carrier_id;
             
              INSERT INTO conf_carrier_cost_take_agent  (agent_level_id,carrier_id) VALUES (NEW.id,NEW.carrier_id);

       OPEN rs;    
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT
	    INSERT INTO conf_carrier_cost_take_agent_platform_fee (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
              
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;
               
               
   END IF;
ELSE 
 OPEN rs;    
              DELETE FROM conf_carrier_rebate_financial_flow_agent WHERE carrier_id = NEW.carrier_id AND carrier_game_plat_id NOT IN (SELECT game_plat_id FROM map_carrier_game_plats WHERE carrier_id = NEW.carrier_id);
	FETCH NEXT FROM rs INTO GamePlatId;	
	REPEAT
	    REPLACE INTO conf_carrier_rebate_financial_flow_agent (agent_level_id,carrier_game_plat_id,carrier_id) VALUES (NEW.id,GamePlatId,NEW.carrier_id);
                 
	FETCH NEXT FROM rs INTO GamePlatId;
	UNTIL Done END REPEAT;
       CLOSE rs;
END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_agent_level
-- ----------------------------
DROP TRIGGER IF EXISTS `DELETE_AGENT_TYPE`;
delimiter ;;
CREATE TRIGGER `DELETE_AGENT_TYPE` AFTER DELETE ON `inf_carrier_agent_level` FOR EACH ROW BEGIN
       IF OLD.TYPE=1 THEN
                   DELETE FROM conf_carrier_commission_agent WHERE agent_level_id = OLD.id;
                   DELETE FROM conf_carrier_commission_agent_platform_fee WHERE agent_level_id = OLD.id;

       ELSEIF OLD.TYPE = 2 THEN
                   DELETE FROM conf_carrier_rebate_financial_flow_agent WHERE agent_level_id = OLD.id;
                   DELETE FROM conf_carrier_rebate_financial_flow_agent_base_info WHERE agent_level_id = OLD.id;
                 

       ELSEIF OLD.TYPE = 3 THEN
                   DELETE FROM conf_carrier_cost_take_agent WHERE agent_level_id = OLD.id; 
                   DELETE FROM conf_carrier_cost_take_agent_platform_fee WHERE agent_level_id = OLD.id; 

       END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_player_level
-- ----------------------------
DROP TRIGGER IF EXISTS `DELETE_PLAYER_REBATE_FINANCIAL_FLOW_WHEN_DELETE_LEVEL`;
delimiter ;;
CREATE TRIGGER `DELETE_PLAYER_REBATE_FINANCIAL_FLOW_WHEN_DELETE_LEVEL` AFTER DELETE ON `inf_carrier_player_level` FOR EACH ROW BEGIN

DELETE FROM inf_carrier_player_game_plats_rebate_financial_flow WHERE carrier_player_level_id = OLD.id;


END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_service_team
-- ----------------------------
DROP TRIGGER IF EXISTS `INSERT_NEW_CARRIER_ROLE`;
delimiter ;;
CREATE TRIGGER `INSERT_NEW_CARRIER_ROLE` AFTER INSERT ON `inf_carrier_service_team` FOR EACH ROW BEGIN
	INSERT INTO roles (name,display_name,user_type,carrier_team_id) VALUES (NEW.team_name,NEW.team_name,'carrier',NEW.id);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_service_team
-- ----------------------------
DROP TRIGGER IF EXISTS `DELETE_CARRIER_ROLE`;
delimiter ;;
CREATE TRIGGER `DELETE_CARRIER_ROLE` AFTER DELETE ON `inf_carrier_service_team` FOR EACH ROW BEGIN
	DELETE FROM roles WHERE user_type = 'carrier' AND carrier_team_id = OLD.id;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_service_team_role
-- ----------------------------
DROP TRIGGER IF EXISTS `INSERT_NEW_CARRIER_USER_PERMISSION`;
delimiter ;;
CREATE TRIGGER `INSERT_NEW_CARRIER_USER_PERMISSION` AFTER INSERT ON `inf_carrier_service_team_role` FOR EACH ROW BEGIN

	INSERT INTO permission_role (permission_id,role_id) VALUES (NEW.permission_id,(SELECT id FROM roles WHERE carrier_team_id = NEW.team_id AND user_type = 'carrier'));
	
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_service_team_role
-- ----------------------------
DROP TRIGGER IF EXISTS `DELETE_FROM_CARRIER_USER_PERMISSION`;
delimiter ;;
CREATE TRIGGER `DELETE_FROM_CARRIER_USER_PERMISSION` AFTER DELETE ON `inf_carrier_service_team_role` FOR EACH ROW BEGIN
	
	DELETE FROM permission_role WHERE permission_id = OLD.permission_id AND role_id = (SELECT id FROM roles WHERE carrier_team_id = OLD.team_id AND user_type = 'carrier');

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_user
-- ----------------------------
DROP TRIGGER IF EXISTS `INSERT_CARRIER_USER_NEW_ROLE`;
delimiter ;;
CREATE TRIGGER `INSERT_CARRIER_USER_NEW_ROLE` AFTER INSERT ON `inf_carrier_user` FOR EACH ROW BEGIN
	INSERT INTO role_user (user_id,role_id,user_type) VALUES (NEW.id, (SELECT id FROM roles WHERE carrier_team_id = NEW.team_id AND user_type = 'carrier'),'carrier');
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_carrier_user
-- ----------------------------
DROP TRIGGER IF EXISTS `UPDATE_CARRIER_USER_ROLE`;
delimiter ;;
CREATE TRIGGER `UPDATE_CARRIER_USER_ROLE` AFTER UPDATE ON `inf_carrier_user` FOR EACH ROW BEGIN
	
	IF NEW.team_id <> OLD.team_id THEN
		DELETE FROM role_user WHERE user_id = NEW.id AND user_type = 'carrier';
		INSERT INTO role_user (user_id,role_id,user_type) VALUES (NEW.id, (SELECT id FROM roles WHERE carrier_team_id = NEW.team_id AND user_type = 'carrier'),'carrier');
	END IF;

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table inf_player
-- ----------------------------
DROP TRIGGER IF EXISTS `inf_player_AFTER_UPDATE`;
delimiter ;;
CREATE TRIGGER `inf_player_AFTER_UPDATE` BEFORE UPDATE ON `inf_player` FOR EACH ROW BEGIN

	IF NEW.agent_id = NULL THEN
		SET NEW.carrier_id = NULL;
	ELSEIF NEW.agent_id != OLD.agent_id THEN
		#更新所属运营商id
		SET NEW.carrier_id = (SELECT carrier_id FROM inf_agent WHERE id = NEW.agent_id);
	ELSE
		SET @temp = 1;
	END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table log_player_bet_flow
-- ----------------------------
DROP TRIGGER IF EXISTS `log_player_bet_flow_AFTER_INSERT`;
delimiter ;;
CREATE TRIGGER `log_player_bet_flow_AFTER_INSERT` AFTER INSERT ON `log_player_bet_flow` FOR EACH ROW BEGIN
	#当有投注流水时,实时更新玩家的投注流水记录
	UPDATE inf_player SET total_win_loss = total_win_loss - NEW.company_win_amount WHERE player_id = NEW.player_id; 

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table map_carrier_game_plats
-- ----------------------------
DROP TRIGGER IF EXISTS `NEW_CARRIER_PLAYER_REBATE_FINANCIAL_FLOW`;
delimiter ;;
CREATE TRIGGER `NEW_CARRIER_PLAYER_REBATE_FINANCIAL_FLOW` AFTER INSERT ON `map_carrier_game_plats` FOR EACH ROW BEGIN

	DECLARE Done INT DEFAULT 0;
	
	#声明变量  玩家等级id
	DECLARE LevelId INT(11);

	#声明游标
	DECLARE rs CURSOR FOR SELECT id FROM inf_carrier_player_level WHERE carrier_id = NEW.carrier_id;
	
	#将结束标志绑定到游标
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET Done = 1;
	
	OPEN rs;
		
		FETCH NEXT FROM rs INTO LevelId;
			
		REPEAT
			IF LevelId > 0 THEN

				INSERT INTO inf_carrier_player_game_plats_rebate_financial_flow (carrier_player_level_id,carrier_game_plat_id,carrier_id) VALUES (LevelId,NEW.game_plat_id,NEW.carrier_id);
			
			END IF;
		
			FETCH NEXT FROM rs INTO LevelId;

		UNTIL Done END REPEAT;
	
	CLOSE rs;


END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table map_carrier_game_plats
-- ----------------------------
DROP TRIGGER IF EXISTS `DELETE_CARRIER_PLAYER_REBATE_FINANCIAL_FLOW`;
delimiter ;;
CREATE TRIGGER `DELETE_CARRIER_PLAYER_REBATE_FINANCIAL_FLOW` AFTER DELETE ON `map_carrier_game_plats` FOR EACH ROW BEGIN
	
DELETE FROM inf_carrier_player_game_plats_rebate_financial_flow WHERE carrier_game_plat_id = OLD.game_plat_id;

END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
