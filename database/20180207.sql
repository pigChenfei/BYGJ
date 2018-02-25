ALTER TABLE `inf_carrier_images` ADD COLUMN `url_type` tinyint(1) NULL COMMENT '连接类型 0外部链接 1内部链接' AFTER `image_category`;
ALTER TABLE `inf_carrier_images` ADD COLUMN `url_link` VARCHAR (255) NULL COMMENT '图片链接地址' AFTER `url_type`;

