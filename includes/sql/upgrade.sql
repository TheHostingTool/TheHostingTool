ALTER TABLE `%PRE%user_packs` CHANGE `id` `id` MEDIUMINT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `userid` `userid` MEDIUMINT( 9 ) UNSIGNED NOT NULL;
DELETE FROM `%PRE%config` WHERE `name` = 'smtp_user' LIMIT 1;
DELETE FROM `%PRE%config` WHERE `name` = 'senabled' LIMIT 1;
ALTER TABLE `%PRE%config` ADD PRIMARY KEY(`name`);
UPDATE `%PRE%config` SET `name` = 'vname' WHERE `name` = 'version' LIMIT 1;
INSERT INTO `%PRE%config` (`name`, `value`) VALUES ('vcode', '102040');
INSERT INTO `%PRE%config` (`name`, `value`) VALUES ('timezone', 'UTC');