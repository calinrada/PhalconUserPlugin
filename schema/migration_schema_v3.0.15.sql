-- locations
ALTER TABLE `locations` ADD `admin_area_level_1` VARCHAR(100) COLLATE utf8_bin NULL DEFAULT NULL AFTER `city`, ADD `admin_area_level_2` VARCHAR(100) COLLATE utf8_bin NULL DEFAULT NULL AFTER `admin_area_level_1`, ADD `postal_cdoe` VARCHAR(16) COLLATE utf8_bin NULL DEFAULT NULL AFTER `admin_area_level_2`;
