drop database  mall_main;
create database mall_main;
use mall_main_0;
set names utf8;
-- --------------------------------------------------------

--
-- 表的结构 `invitations`
--


DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `to_pid` int(11) DEFAULT NULL COMMENT '被邀请人的平台id',
  `gift_id` varchar(32) NOT NULL DEFAULT '',
  `is_accepted` int(1) DEFAULT '0',
  `link_id` char(32) DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `to_pid` (`to_pid`),
  KEY `link_id` (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `old_presents`
--

DROP TABLE IF EXISTS `old_presents`;
CREATE TABLE IF NOT EXISTS `old_presents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `message` text,
  `item_id` int(11) DEFAULT NULL,
  `done` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_presents_on_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(64) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pid` char(32) NOT NULL DEFAULT '0',
  `amount` int(11) DEFAULT NULL,
  `gem` int(11) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `order_type` char(32) NOT NULL DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `index_dou_orders_on_user_id` (`user_id`) USING BTREE,
  KEY `index_dou_orders_on_platform_id` (`pid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `presents`
--

DROP TABLE IF EXISTS `presents`;
CREATE TABLE IF NOT EXISTS `presents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `message` text,
  `item_id` int(11) DEFAULT NULL,
  `done` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_presents_on_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;