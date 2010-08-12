
 drop database mall_stat;
 create database mall_stat  DEFAULT CHARSET=utf8;
 use mall_stat;
 set names utf8;
-- 数据库设计
-- daily_general 每日日常数据


drop table  if exists  `daily_general`;
CREATE TABLE IF NOT EXISTS `daily_general` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateday` date DEFAULT NULL COMMENT '日期',
  
    -- 登录相关
  `login_num` int(11) DEFAULT '0' COMMENT '登录人数',
  `install_num` int(11) DEFAULT '0' COMMENT '卸载人数',
  `login_3num` int(11) DEFAULT '0' COMMENT '3天内登录的人数',
  `login_7num` int(11) NOT NULL DEFAULT '0' COMMENT '周活跃用户数',
  `login_30num` int(11) NOT NULL DEFAULT '0' COMMENT '月活跃用户数',
  `unstall_num` int(11) DEFAULT '0' COMMENT '卸载人数',
  `reinstall_num` int(11) DEFAULT '0' COMMENT '重新安装人数',
  
  --  经济相关

  `money` bigint(20) DEFAULT NULL COMMENT 'zong金币',
  `exp` bigint(20) DEFAULT '0' COMMENT 'zong产生',
  `gem` bigint(20) DEFAULT '0' COMMENT 'zong钱币',

  `smoney` bigint(20) DEFAULT '0' COMMENT '花费金币',
  `sgem` bigint(20) DEFAULT '0' COMMENT '花费钱币',

  `ggem` bigint(20) DEFAULT '0' COMMENT '赚取钱币',
  `gmoney` bigint(20) DEFAULT '0' COMMENT '赚取金币',
  `gexp` bigint(20) DEFAULT '0' COMMENT '经验增加',
  
  -- 邀请交互相关
  `invite_num` int(11) DEFAULT '0' COMMENT '邀请次数',
  `from_invite_num` int(11) DEFAULT '0' COMMENT '邀请来访人数',
  `invite_succ_num` int(11) DEFAULT '0' COMMENT '邀请成功人数',
  
  -- 送礼交互相关
  `gift_num` int(11) DEFAULT '0' COMMENT '送礼次数',
  `from_gift_num` int(11) DEFAULT '0' COMMENT '送礼来访人数',
  `gift_succ_num` int(11) DEFAULT '0' COMMENT '送礼接收人数',
 `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ,PRIMARY KEY (`id`)
  ,UNIQUE KEY `dateday` (`dateday`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
CREATE TABLE `user_actions` (
  `id` int(11) NOT NULL ,   -- id     
  `uid` int(11) NOT NULL default 0 COMMENT '用户id',
  `action_id` int(11) default NULL COMMENT '操作id',
  `dateday` date   COMMENT '日期',   --
  `num` int(11) default '0' COMMENT '行动次数'
)  DEFAULT CHARSET=utf8;



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
)  DEFAULT CHARSET=utf8;

drop table  if exists `action_history`;
CREATE TABLE `action_history` (
  `id` int(11) NOT NULL ,  
  `action_id` int(11) NOT NULL default 0 COMMENT '操作',
  `dateday` date   COMMENT '日期',   --
  `buy_num` int(11) default NULL COMMENT  '购买次数',
  `sale_num`  int(11) default '0' COMMENT '卖出次数',
  `gift_num` int(11) default '0' COMMENT '送礼次数'
) ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;

drop table  if exists `userops_history`;
CREATE TABLE IF NOT EXISTS `userops_history` (
  `id` int(11) NOT NULL ,
  `dateday` date NOT NULL,
  `uid` int(11) NOT NULL,
  `type` char(48) NOT NULL,
  `gem` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL COMMENT '操作相关参数1',
  `room_id` int(11) NOT NULL,
  `strparam1` char(32) NOT NULL,
  `strparam2` char(32) NOT NULL
)ENGINE=BRIGHTHOUSE DEFAULT CHARSET=utf8;



			 
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
