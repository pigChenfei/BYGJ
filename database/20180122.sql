SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `conf_carrier_agent_level_commission`;
CREATE TABLE `conf_carrier_agent_level_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned DEFAULT NULL COMMENT '运营商ID',
  `agent_level_id` int(11) DEFAULT NULL COMMENT '代理类型id',
  `level` tinyint(1) unsigned DEFAULT '1' COMMENT '代理级别',
  `commission_ratio` decimal(5,2) DEFAULT '0.00' COMMENT '阶级佣金比例',
  `commission_max` decimal(11,2) DEFAULT '0.00' COMMENT '佣金最大上线 0无上限',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='运营商下级佣金代理比例设置';
SET FOREIGN_KEY_CHECKS=1;
