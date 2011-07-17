ALTER TABLE `%PRE%users`  ADD `extra` LONGTEXT NOT NULL;
INSERT INTO `%PRE%acpnav` (`id`, `visual`, `icon`, `link`) VALUES (NULL, 'Order Form', 'table.png', 'orderform');
ALTER TABLE `%PRE%packages` ADD `custom_fields` TEXT NOT NULL;
CREATE TABLE IF NOT EXISTS `%PRE%orderfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `default` text NOT NULL,
  `description` text NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `regex` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `%PRE%user_packs` CHANGE `id` `id` MEDIUMINT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `userid` `userid` MEDIUMINT( 9 ) UNSIGNED NOT NULL;
