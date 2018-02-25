ALTER TABLE `inf_player` DROP INDEX `user_name_unique`;
CREATE UNIQUE INDEX `user_name_unique` USING BTREE ON `inf_player` (`user_name`,`carrier_id`);