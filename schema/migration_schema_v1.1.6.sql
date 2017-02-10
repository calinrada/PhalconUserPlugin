-- user
ALTER TABLE  `user` MODIFY  `created_at` DATETIME NOT NULL ,
  MODIFY  `updated_at` DATETIME NULL DEFAULT NULL;

-- user_success_logins
ALTER TABLE  `user_success_logins` ADD  `created_at` DATETIME NOT NULL
