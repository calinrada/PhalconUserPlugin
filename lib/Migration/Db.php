<?php

namespace Phalcon\UserPlugin\Migration;

class Db extends \Phalcon\Mvc\User\Plugin
{
    public static function migrate()
    {
        try {
            $db = \Phalcon\DI::getDefault()->get('db');
            $db->execute('ALTER TABLE `user_email_confirmations` CHANGE  `modified_at`  `updated_at` DATETIME NULL DEFAULT NULL ;');
            $db->execute('ALTER TABLE `user` DROP COLUMN profile_id ;');
            $db->execute('ALTER TABLE `user` ADD COLUMN `status` tinyint(2) NOT NULL DEFAULT \'1\'');
            $db->execute('ALTER TABLE `user` CHANGE `linkedin_id` `linkedin_id` VARCHAR( 64 ) NULL DEFAULT NULL ;');
        } catch (\Exception $e) {
            echo 'Database migration error: '.$e->getMessage();
        }
    }
}
