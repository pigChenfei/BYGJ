alter table log_agent_account_adjust modify `amount` decimal(11,2) NOT NULL COMMENT '调整金额';

ALTER TABLE `def_pay_channel_list` ADD COLUMN `is_need_identify_code` tinyint(1) NULL DEFAULT 0 COMMENT '是否需要填写商户识别码：0 不需要，1需要' AFTER `is_need_good_name`;

