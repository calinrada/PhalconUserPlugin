<?php

namespace Phalcon\UserPlugin\Migration;

class Db extends \Phalcon\Di\Injectable
{
    public static function migrate()
    {
        try {
            // @TODO upgrade phalcon db class and methods

            /* $db = \Phalcon\DI::getDefault()->get('db');
            $db->execute('ALTER TABLE `user_email_confirmations` CHANGE  `modified_at`  `updated_at` DATETIME NULL DEFAULT NULL ;');
            $db->execute('ALTER TABLE `user` DROP COLUMN profile_id ;');
            $db->execute('ALTER TABLE `user` ADD COLUMN `status` tinyint(2) NOT NULL DEFAULT \'1\'');
            $db->execute('ALTER TABLE `user` CHANGE `linkedin_id` `linkedin_id` VARCHAR( 64 ) NULL DEFAULT NULL ;'); */
            echo 'All good...';
        } catch (\Exception $e) {
            echo 'Database migration error: '.$e->getMessage();
        }
    }
}
