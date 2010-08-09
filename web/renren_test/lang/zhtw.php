<?php

function get_lang($str)
{
	static $LANG=array(
'Play'=>'玩',
'Super Mall'=>'超级商场',
'Become a fan'=>'成为粉丝',
'Love'=>'喜欢',
'Neighbors'=>'友邻',
'Please'=>'请',
'Having trouble to load game'=>'游戏加载遇到麻烦',
'update your flash player'=>'更新flash播放器',
'Here'=>'这里',
'by clicking'=>'点击'
);
	if(isset($LANG[$str])){
		return $LANG[$str];
	}
	return $str;
}
