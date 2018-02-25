SET FOREIGN_KEY_CHECKS=0;

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
  `settled_at` timestamp NULL DEFAULT NULL COMMENT '结算时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COMMENT='玩家返水记录';

ALTER TABLE `log_player_bet_flow` ADD COLUMN `is_handle` tinyint(1) DEFAULT '0' COMMENT '是否处理 0未处理 1处理';
