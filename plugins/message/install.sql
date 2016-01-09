# iCMS plugins SQL File
# Version:Plus V5.0
# Time: 2010-07-06 16:34:42
# iCMS: http://www.iDreamSoft.com
# --------------------------------------------------------

CREATE TABLE `#iCMS@__plugins_message` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `author` varchar(255) NOT NULL DEFAULT '',
   `email` varchar(255) NOT NULL DEFAULT '',
   `url` varchar(255) NOT NULL DEFAULT '',
   `content` mediumtext NOT NULL,
   `reply` mediumtext NOT NULL,
   `addtime` int(10) unsigned NOT NULL DEFAULT '0',
   `ip` varchar(30) NOT NULL DEFAULT '',
   `status` tinyint(1) DEFAULT '0',
   PRIMARY KEY (`id`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8