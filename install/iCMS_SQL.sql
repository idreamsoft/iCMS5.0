
DROP TABLE IF EXISTS `#iCMS@__admin`;

CREATE TABLE `#iCMS@__admin` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `groupid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email` char(40) NOT NULL DEFAULT '',
  `info` mediumtext NOT NULL,
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `lastip` char(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` smallint(6) unsigned NOT NULL DEFAULT '0',
  `post` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `groupid` (`groupid`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__admin` */

LOCK TABLES `#iCMS@__admin` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__advertise` */

DROP TABLE IF EXISTS `#iCMS@__advertise`;

CREATE TABLE `#iCMS@__advertise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `style` enum('code','text','image','flash') NOT NULL DEFAULT 'code',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `code` mediumtext NOT NULL,
  `load` varchar(10) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__advertise` */

LOCK TABLES `#iCMS@__advertise` WRITE;

insert  into `#iCMS@__advertise`(`id`,`varname`,`title`,`style`,`starttime`,`endtime`,`code`,`load`,`status`) values (11,'全站顶部广告','','code',1278259200,0,'a:4:{s:4:\"code\";a:1:{s:4:\"html\";s:18:\"广告 HTML 代码\";}s:4:\"text\";a:3:{s:5:\"title\";s:0:\"\";s:4:\"link\";s:0:\"\";s:4:\"size\";s:0:\"\";}s:5:\"image\";a:5:{s:3:\"url\";s:0:\"\";s:4:\"link\";s:0:\"\";s:5:\"width\";s:0:\"\";s:6:\"height\";s:0:\"\";s:3:\"alt\";s:0:\"\";}s:5:\"flash\";a:3:{s:3:\"url\";s:0:\"\";s:5:\"width\";s:0:\"\";s:6:\"height\";s:0:\"\";}}','shtml','1');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__article` */

DROP TABLE IF EXISTS `#iCMS@__article`;

CREATE TABLE `#iCMS@__article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderNum` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `stitle` varchar(255) NOT NULL DEFAULT '',
  `clink` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `editor` varchar(200) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `isPic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `related` text NOT NULL,
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `good` int(10) unsigned NOT NULL DEFAULT '0',
  `bad` int(10) unsigned NOT NULL DEFAULT '0',
  `vlink` varchar(255) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `top` smallint(6) NOT NULL DEFAULT '0',
  `postype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `pubdate` (`pubdate`),
  KEY `comment` (`comments`),
  KEY `hit` (`hits`),
  KEY `order` (`orderNum`),
  KEY `sortid` (`fid`,`id`),
  KEY `pic` (`isPic`,`id`),
  KEY `topord` (`top`,`orderNum`),
  KEY `userid` (`userid`),
  KEY `postype` (`postype`,`id`),
  KEY `status` (`status`,`postype`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__article` */

LOCK TABLES `#iCMS@__article` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__article_data` */

DROP TABLE IF EXISTS `#iCMS@__article_data`;

CREATE TABLE `#iCMS@__article_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `tpl` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__article_data` */

LOCK TABLES `#iCMS@__article_data` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__comment` */

DROP TABLE IF EXISTS `#iCMS@__comment`;

CREATE TABLE `#iCMS@__comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mId` int(10) unsigned NOT NULL DEFAULT '0',
  `sortId` int(10) unsigned NOT NULL DEFAULT '0',
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `userId` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `contents` mediumtext NOT NULL,
  `quote` int(11) unsigned NOT NULL DEFAULT '0',
  `floor` int(11) unsigned NOT NULL DEFAULT '0',
  `reply` int(11) unsigned NOT NULL DEFAULT '0',
  `up` int(10) unsigned NOT NULL DEFAULT '0',
  `down` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`mId`,`sortId`,`status`,`indexId`,`id`),
  KEY `addtime` (`mId`,`sortId`,`status`,`indexId`,`addtime`),
  KEY `ua` (`up`,`down`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__comment` */

LOCK TABLES `#iCMS@__comment` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__config` */

DROP TABLE IF EXISTS `#iCMS@__config`;

CREATE TABLE `#iCMS@__config` (
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__config` */

LOCK TABLES `#iCMS@__config` WRITE;

insert  into `#iCMS@__config`(`name`,`value`) values ('name','iCMS内容管理系统'),('seotitle','iDreamSoft'),('keywords','iCMS内容管理系统'),('description','iCMS 是一个采用 PHP 和 MySQL 数据库构建的高效内容管理系统,为中小型网站提供一个完美的解决方案。'),('icp','ICP备案号'),('masteremail','admin@domain.com'),('template','default'),('indexname','index'),('indexTPL','{TPL}/index.htm'),('debug','0'),('tpldebug','0'),('language','zh-cn'),('setupURL','http://v5.icms.com'),('publicURL','http://v5.icms.com/public'),('ishtm','0'),('htmlURL','http://v5.icms.com/html'),('htmldir','html'),('htmlext','.html'),('enable_xmlrpc','0'),('tagRule','{PHP}'),('iscache','0'),('cachedir','cache'),('cachelevel','0'),('cachetime','300'),('iscachegzip','0'),('cacheEngine','file'),('cacheServers','127.0.0.1:11211'),('uploadURL','http://v5.icms.com/uploadfiles'),('remoteKey','123213'),('uploadScript','iCMS.upload.php'),('uploadfiledir','uploadfiles'),('savedir','y-m-d'),('fileext','gif,jpg,rar,swf,jpeg,png'),('isthumb','1'),('thumbwidth','140'),('thumbhight','140'),('iswatermark','1'),('waterwidth','120'),('waterheight','120'),('waterpos','9'),('waterimg','watermark.png'),('watertext','iCMS'),('waterfont',''),('waterfontsize','12'),('watercolor','#000000'),('waterpct','80'),('iscomment','1'),('anonymous','1'),('isexamine','0'),('anonymousname','网友'),('searchprepage','100'),('keywordToTag','0'),('remote','1'),('autopic','0'),('autodesc','1'),('descLen','100'),('repeatitle','0'),('ServerTimeZone','8'),('cvtime','0'),('dateformat','Y-m-d H:i:s'),('CLsplit',','),('diggtime','0'),('kwCount','-1'),('issmall','1'),('AutoPage','0'),('AutoPageLen','1000'),('thumbwatermark','0'),('tagURL',''),('tagDir','');
UNLOCK TABLES;

/*Table structure for table `#iCMS@__file` */

DROP TABLE IF EXISTS `#iCMS@__file`;

CREATE TABLE `#iCMS@__file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(200) NOT NULL DEFAULT '',
  `ofilename` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(250) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL DEFAULT '',
  `ext` varchar(10) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `ext` (`ext`),
  KEY `path` (`path`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__file` */

LOCK TABLES `#iCMS@__file` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__forum` */

DROP TABLE IF EXISTS `#iCMS@__forum`;

CREATE TABLE `#iCMS@__forum` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `subname` varchar(100) NOT NULL DEFAULT '',
  `rootid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` int(10) NOT NULL DEFAULT '0',
  `orderNum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(200) DEFAULT '',
  `keywords` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `dir` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `attr` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `htmlext` varchar(10) NOT NULL DEFAULT '',
  `forumRule` varchar(255) NOT NULL DEFAULT '',
  `contentRule` varchar(255) NOT NULL DEFAULT '',
  `indexTPL` varchar(100) NOT NULL DEFAULT '',
  `listTPL` varchar(100) NOT NULL DEFAULT '',
  `contentTPL` varchar(100) NOT NULL DEFAULT '',
  `isexamine` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issend` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`fid`),
  KEY `status` (`status`,`orderNum`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__forum` */

LOCK TABLES `#iCMS@__forum` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__group` */

DROP TABLE IF EXISTS `#iCMS@__group`;

CREATE TABLE `#iCMS@__group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `type` enum('a','u') NOT NULL DEFAULT 'a',
  PRIMARY KEY (`gid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `#iCMS@__group` */

LOCK TABLES `#iCMS@__group` WRITE;

insert  into `#iCMS@__group`(`gid`,`name`,`order`,`power`,`cpower`,`type`) values (1,'超级管理员',1,'ADMINCP,header_index,menu_index_home,menu_index_article_add,menu_index_comment,menu_index_article_user_draft,menu_index_link,menu_index_advertise,header_setting,menu_setting_all,menu_setting_config,menu_setting_url,menu_setting_cache,menu_setting_attachments,menu_setting_watermark,menu_setting_publish,menu_setting_time,menu_setting_other,header_article,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment,menu_contentype,menu_filter,menu_tag_manage,menu_keywords,menu_search,header_user,menu_user_manage,menu_account_manage,menu_account_edit,header_extend,menu_model_manage,menu_field_manage,header_html,menu_html_all,menu_html_index,menu_html_article,menu_html_tag,menu_html_page,menu_setting_url,header_tools,menu_link,menu_file_manage,menu_file_upload,menu_extract_pic,menu_advertise,menu_message,menu_cache,menu_template_manage,menu_database_backup,menu_database_recover,menu_database_repair,menu_database_replace,Allow_View_Article,Allow_Edit_Article','1,3,5,4,2,6,7,8','a'),(2,'管理员',2,'ADMINCP,header_index,menu_index_home,menu_index_catalog_add,menu_index_article_add,menu_index_comment,menu_index_article_user_draft,menu_index_link,menu_index_advertise,menu_setting_url,header_article,menu_catalog_add,menu_catalog_manage,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment,menu_contentype,menu_article_default,menu_filter,menu_tag_manage,menu_keywords,menu_search,menu_push_add,menu_push_forum,menu_push_manage,header_user,menu_user_manage,header_html,menu_html_all,menu_html_index,menu_html_catalog,menu_html_article,menu_html_tag,menu_html_page,menu_setting_url,header_tools,menu_link,menu_file_manage,menu_file_upload,menu_extract_pic,menu_advertise,menu_message,menu_cache,menu_template_manage,Allow_View_Article,Allow_Edit_Article','1,3,5,4','a'),(3,'编辑',3,'ADMINCP,header_index,menu_index_home,menu_index_article_add,menu_index_article_user_draft,header_article,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment','','a'),(4,'会员',1,'','','u');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__keywords` */

DROP TABLE IF EXISTS `#iCMS@__keywords`;

CREATE TABLE `#iCMS@__keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `replace` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__keywords` */

LOCK TABLES `#iCMS@__keywords` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__links` */

DROP TABLE IF EXISTS `#iCMS@__links`;

CREATE TABLE `#iCMS@__links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sortid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `orderNum` smallint(5) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `orderid` (`orderNum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__links` */

LOCK TABLES `#iCMS@__links` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__members` */

DROP TABLE IF EXISTS `#iCMS@__members`;

CREATE TABLE `#iCMS@__members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `info` mediumtext NOT NULL,
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__members` */

LOCK TABLES `#iCMS@__members` WRITE;

UNLOCK TABLES;


/*Table structure for table `#iCMS@__search` */

DROP TABLE IF EXISTS `#iCMS@__search`;

CREATE TABLE `#iCMS@__search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `search` varchar(200) NOT NULL DEFAULT '',
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search` (`search`,`times`),
  KEY `searchid` (`search`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__search` */

LOCK TABLES `#iCMS@__search` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__taglist` */

DROP TABLE IF EXISTS `#iCMS@__taglist`;

CREATE TABLE `#iCMS@__taglist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`,`indexId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__taglist` */

LOCK TABLES `#iCMS@__taglist` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__tags` */

DROP TABLE IF EXISTS `#iCMS@__tags`;

CREATE TABLE `#iCMS@__tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sortid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `ordernum` smallint(5) NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tpl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sortid` (`sortid`,`ordernum`),
  KEY `sortid_2` (`sortid`,`id`),
  KEY `count` (`count`),
  KEY `link` (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__tags` */

LOCK TABLES `#iCMS@__tags` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__vlink` */

DROP TABLE IF EXISTS `#iCMS@__vlink`;

CREATE TABLE `#iCMS@__vlink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `sortId` int(10) unsigned NOT NULL DEFAULT '0',
  `modelId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sortid` (`sortId`,`indexId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__vlink` */

LOCK TABLES `#iCMS@__vlink` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
