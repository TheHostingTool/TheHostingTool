INSERT INTO `%PRE%acpnav` (`id`, `visual`, `icon`, `link`) VALUES (NULL, 'XML-API', 'brick.png', 'api');
INSERT INTO `%PRE%acpnav` (`id`, `visual`, `icon`, `link`) VALUES (NULL, 'Invoices Management', 'script.png', 'invoices');
ALTER TABLE `%PRE%packages` ADD `order` INT(11) NOT NULL AFTER `additional`;
ALTER TABLE `%PRE%navbar` ADD `order` INT(11) NOT NULL AFTER `link`;

CREATE TABLE IF NOT EXISTS `%PRE%types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(15) NOT NULL,
  `visual` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `%PRE%types`
--

INSERT INTO `%PRE%types` (`id`, `name`, `visual`) VALUES
(3, 'paid', 'Paid');

CREATE TABLE IF NOT EXISTS `%PRE%invoices` (
  `id` int(255) NOT NULL auto_increment,
  `uid` int(255) NOT NULL,
  `amount` int(255) NOT NULL,
  `is_paid` int(1) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `due` text NOT NULL,
  `is_suspended` int(1) NOT NULL default '0',
  `notes` text NOT NULL,
  `uniqueid` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

INSERT INTO `%PRE%config` (`name`, `value`) VALUES
('api-key', '%API-KEY%'),
('terminationdays', '14'),
('suspensiondays', '14'),
('whm-ssl', '0'),
('tldonly', '0'),
('currency', 'USD'),
('paypalemail', 'your@email.com'),
('ui-theme', 'cupertino');

INSERT INTO `%PRE%templates` (`name`, `acpvisual`, `subject`, `content`, `description`) VALUES
('newinvoice', 'New Invoice', 'New Invoice', '<p><strong>You have a new unpaid invoice on your account!<br /></strong>Make sure you log into your client area (%USER%) and pay the invoice.</p>
<p><strong>Invoice Due: </strong>%DUE%</p>', 'This is the email a client receives when they''ve got a new unpaid invoice. There are certain variables:<br />\r\n%USER% - Username<br />\r\n%DUE% - The Invoice Due Date\r\n');

INSERT INTO `%PRE%clientnav` (`id`, `visual`, `icon`, `link`) VALUES
(7, 'Invoices', 'script.png', 'invoices');