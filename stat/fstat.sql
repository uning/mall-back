
 drop database mall_stat;
 create database mall_stat  DEFAULT CHARSET=utf8;
 use mall_stat;
 set names utf8;

--
-- 数据库: `mall_stat`
--

-- --------------------------------------------------------

--
-- 表的结构 `daily_varibles`
--
CREATE TABLE IF NOT EXISTS `daily_varibles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` int(11) NOT NULL,
  `date` varchar(32) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`date`),
  KEY `name_2` (`name`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `link_in`
--

DROP TABLE IF EXISTS `link_in`;
CREATE TABLE IF NOT EXISTS `link_in` (
  `id` varchar(64) NOT NULL,
  `link_id` varchar(64) NOT NULL,
  `dateday` date NOT NULL,
  `click_time` int(11) NOT NULL,
  `pid` varchar(32) DEFAULT NULL
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `link_out`
--

DROP TABLE IF EXISTS `link_out`;
CREATE TABLE IF NOT EXISTS `link_out` (
  `id` varchar(64) NOT NULL,
  `dateday` date NOT NULL,
  `send_time` int(11) NOT NULL,
  `pid` varchar(32) DEFAULT NULL,
  `s` varchar(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `w` varchar(10) NOT NULL,
  `c` varchar(10) NOT NULL,
  `d` varchar(10) NOT NULL,
  `d1` varchar(128) NOT NULL,
  `d2` varchar(128) NOT NULL,
  `d3` varchar(128) NOT NULL
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_history`
--

DROP TABLE IF EXISTS `log_history`;
CREATE TABLE IF NOT EXISTS `log_history` (
  `u` int(11) NOT NULL,
  `action` varchar(32) NOT NULL COMMENT '操作',
  `tm` int(11) DEFAULT NULL COMMENT '时间',
  `intp1` int(11) DEFAULT '0',
  `intp2` int(11) DEFAULT '0',
  `sp1` varchar(32) DEFAULT '0',
  `sp2` varchar(32) DEFAULT '0'
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_history`
--

DROP TABLE IF EXISTS `user_history`;
CREATE TABLE IF NOT EXISTS `user_history` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `dateday` date DEFAULT NULL COMMENT '日期',
  `money` int(20) DEFAULT NULL COMMENT '当天金币',
  `exp` int(11) DEFAULT '0' COMMENT '经验',
  `gem` int(11) DEFAULT '0' COMMENT '钱币',
  `f_num` int(11) DEFAULT '0' COMMENT '好友数目'
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

