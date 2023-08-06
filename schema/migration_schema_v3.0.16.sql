-- user_profile
ALTER TABLE `user_profile` ADD `description` TEXT COLLATE utf8_bin NULL DEFAULT NULL AFTER `gender`;
