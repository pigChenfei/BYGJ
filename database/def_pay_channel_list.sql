ALTER TABLE `def_pay_channel_list` ADD COLUMN `is_need_domain` tinyint(1) DEFAULT '0' COMMENT '是否需要绑定域名';
ALTER TABLE `def_pay_channel_list` ADD COLUMN `is_need_good_name` tinyint(1) DEFAULT '0' COMMENT '是否需要绑定商品名称';
ALTER TABLE `def_pay_channel_list` CHANGE `is_need_merchant_code` `is_need_vir_card` tinyint(1) DEFAULT '0' COMMENT '是否需要填写转入账户';


