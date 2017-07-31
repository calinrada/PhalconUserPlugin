-- user
ALTER TABLE `user` ADD `first_name` VARCHAR(32) NULL DEFAULT NULL AFTER `name`, ADD `last_name` VARCHAR(32) NULL DEFAULT NULL AFTER `first_name`;
