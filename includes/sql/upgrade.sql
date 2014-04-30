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
  `extra` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `%PRE%config` (`name`, `value`) VALUES
('useNewOrderSlideEffect', '0'),
('adminurl', 'admin'),
('welcomemsg', '<p><strong>Welcome to TheHostingTool! Select a package to get started.</strong></p>');
ALTER TABLE `%PRE%packages` ADD `allow_domains` BOOLEAN NOT NULL DEFAULT TRUE ;
