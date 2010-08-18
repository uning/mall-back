
 drop database mall_stat;
 create database mall_stat  DEFAULT CHARSET=utf8;
 use mall_stat;
 set names utf8;
-- 数据库设计
-- daily_general 每日日常数据


DROP TABLE IF EXISTS `daily_varibles`;
CREATE TABLE IF NOT EXISTS `daily_varibles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` int(11) NOT NULL,
  `date` varchar(32) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`date`),
  KEY `name_2` (`name`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ----------------------------
--  操作定义，meta信息
--  统计每天操作，function（功能），action（操作行为），如新手1是一个功能，定义一系列的action
-- ----------------------------
drop table  if exists `functions`;
CREATE TABLE `functions` (
  `id` int(11) NOT NULL ,   --			id    
  `desp` varchar(128) NOT NULL default "" COMMENT '功能描述'
)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

drop table  if exists `actions`;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL ,   --			id     
  `function_id` int(11) NOT NULL default 0 COMMENT '功能',
  `desp` varchar(128) NOT NULL default "" COMMENT '操作描述'
)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  用户每天等级，金钱，消费情况,若当天未登录，则不记录
-- ----------------------------
drop table  if exists `user_history`;
CREATE TABLE `user_history` (
  `uid` int(11) NOT NULL default 0 COMMENT '用户id',
  `dateday` date   COMMENT '日期',   --
  `money` int(20) default NULL COMMENT '当天金币',
  `exp` int(11) default '0' COMMENT '经验',
  `gem` int(11) default '0' COMMENT '钱币', 
  `f_num` int(11) default '0' COMMENT '好友数目'
  

)  DEFAULT CHARSET=utf8;

-- ----------------------------
--  用户每天操作情况
-- ----------------------------
drop table  if exists `user_actions`;
CREATE TABLE IF NOT EXISTS `user_actions` (
  `u` int(11) NOT NULL,
  `action` varchar(32) NOT NULL COMMENT '操作',
  `tm` int(11)  COMMENT '时间',
  `intp1` int(11) DEFAULT '0',
  `intp2` int(11) DEFAULT '0',
  `sp1` varchar(32) DEFAULT '0',
  `sp2` varchar(32) DEFAULT '0'
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;




-- ----------------------------
--  物品交易情况，将花钱的操作虚拟成一个物品
-- ----------------------------
drop table  if exists `item_history`;
CREATE TABLE `item_history` (
  `id` int(11) NOT NULL ,
  `item_id` int(11) NOT NULL default 0 COMMENT '物品',
  `dateday` date   COMMENT '日期',   --
  `buy_num` int(11) default NULL COMMENT  '购买次数',
  `sale_num`  int(11) default '0' COMMENT '卖出次数',
  `gift_num` int(11) default '0' COMMENT '送礼次数'
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;




			 
drop table  if exists `link_out`;
CREATE TABLE IF NOT EXISTS `link_out` (
  `id` varchar(64) NOT NULL ,  -- link 唯一标识
  `dateday` date NOT NULL,        --  日期
  `send_time` int NOT NULL,    -- 发送时间
  `pid` varchar(32) ,          -- 用户平台id
  `s`  varchar(10) NOT NULL,   -- 状态 a -try b-canceled ,c - sended
  `type` varchar(10) NOT NULL,  -- 类型
  `w` varchar(10) NOT NULL,     -- 发送地方
  `c` varchar(10) NOT NULL,   -- 内容标示
  `d` varchar(10) NOT NULL, -- 其他无索引数据
  `d1` varchar(128) NOT NULL, -- 其他无索引数据
  `d2` varchar(128) NOT NULL, -- 其他无索引数据
  `d3` varchar(128) NOT NULL -- 其他无索引数据
)ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;


drop table  if exists `link_in`;
CREATE TABLE IF NOT EXISTS `link_in` (
  `id` varchar(64) NOT NULL ,  -- link 唯一标识
  `link_id` varchar(64) NOT NULL ,  -- link 唯一标识
  `dateday` date NOT NULL,        --  日期
  `click_time` int NOT NULL,    -- 返回时间
  `pid` varchar(32)          -- 返回用户平台id
)ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- end of the sql script
