ALTER TABLE log_agent_undertaken ADD company_amount DECIMAL(11,2) NOT NULL DEFAULT 0.00 COMMENT '公司承担费用' AFTER amount;

ALTER TABLE log_agent_undertaken ADD `log_agent_settled_id` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '代理佣金结算ID' AFTER agent_id;

ALTER TABLE log_player_rebate_financial_flow_new ADD `settled_type` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '客服手动处理类型0未处理 1返水2返0' AFTER rebate_type;

ALTER TABLE conf_templates ADD alias VARCHAR(255) NOT NULL DEFAULT '' COMMENT '模版简称' AFTER `value`;

