ALTER TABLE log_agent_settle ADD transfer_next_month_rebate DECIMAL(11,2) NOT NULL DEFAULT 0.00 COMMENT '结转下月洗码金额' AFTER transfer_next_month;
ALTER TABLE log_agent_settle ADD cumulative_last_month_rebate DECIMAL(11,2) NOT NULL DEFAULT 0.00 COMMENT '累加上月洗码金额' AFTER cumulative_last_month;
ALTER TABLE log_agent_settle ADD actual_payment_rebate DECIMAL(11,2) NOT NULL DEFAULT 0.00 COMMENT '实际发放洗码金额' AFTER actual_payment;
ALTER TABLE log_agent_settle ADD manual_tuneup_rebate DECIMAL(11,2) NOT NULL DEFAULT 0.00 COMMENT '手工调整洗码金额' AFTER manual_tuneup;
ALTER TABLE inf_carrier ADD is_multi_agent TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否允许运营商支持设置多级代理0否 1是' AFTER remain_quota;