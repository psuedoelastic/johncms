<?php
define('_IN_JOHNCMS', 1);
$headmod = 'load';
require_once ("../incfiles/core.php");
require_once ("../incfiles/head.php");
echo'Создаём таблицы...';
mysql_query("DROP TABLE `downfiles`, `downkomm`, `downpath`, `down_bookmarks`, `downscreen`;");
mysql_query("CREATE TABLE IF NOT EXISTS `downfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(2) NOT NULL DEFAULT '0',
  `pathid` int(11) NOT NULL,
  `way` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `time` int(11) NOT NULL,
  `rating` varchar(3) NOT NULL DEFAULT '0',
  `gol` text NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `login` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `themeid` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `pathid` (`pathid`),
  KEY `time` (`time`),
  KEY `type_status` (`type`,`status`),
  FULLTEXT KEY `desc` (`desc`),
  FULLTEXT KEY `way` (`way`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
mysql_query("CREATE TABLE IF NOT EXISTS `downkomm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `text` text NOT NULL,
  `plus` int(11) NOT NULL,
  `minus` int(11) NOT NULL,
  `golos` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;");
mysql_query("CREATE TABLE IF NOT EXISTS `downpath` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refid` int(11) NOT NULL,
  `way` text NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `position` int(11) NOT NULL,
  `dost` int(11) NOT NULL DEFAULT '0',
  `types` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ref` (`refid`),
  KEY `position` (`position`),
  FULLTEXT KEY `way` (`way`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;");

mysql_query("CREATE TABLE IF NOT EXISTS `downscreen` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL,
  `way` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;");

mysql_query("CREATE TABLE IF NOT EXISTS `down_bookmarks` (
  `user` int(11) NOT NULL,
  `file` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");



echo'<div class="gmenu">Таблицы ОК.</div>
<div class="rmenu">Удалите файл install.php из папки download</div>
';

require_once ('../incfiles/end.php');
