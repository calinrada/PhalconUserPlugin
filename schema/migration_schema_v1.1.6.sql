-- user
ALTER TABLE  `user` ADD  `created_at` DATETIME NOT NULL ,
ADD  `updated_at` DATETIME NULL DEFAULT NULL

-- user_success_logins
ALTER TABLE  `user_success_logins` ADD  `created_at` DATETIME NOT NULL
