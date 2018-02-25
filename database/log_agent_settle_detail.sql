SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS log_agent_settle_detail;
CREATE TABLE log_agent_settle_detail(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_id` int(11) unsigned NOT NULL COMMENT '运营商id',
  `in_agent_id` int(11) unsigned NOT NULL COMMENT '收钱方代理id',
  `out_agent_id` int(11) unsigned NOT NULL COMMENT '出钱方代理id',
  `agent_settle_id` int(11) unsigned NOT NULL COMMENT '结算记录log_agent_settle表id',
  `commission_money` DECIMAL (11, 2) NOT NULL DEFAULT '0.00' COMMENT '结算费用金额默认0.00',
  `commission_rate` DECIMAL (4, 2) NOT NULL DEFAULT '0.00' COMMENT '结算费用比例(%)默认0.00',
  `level` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '结算比例对应level',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='多级代理阶级比例结算表';

SET FOREIGN_KEY_CHECKS=1;