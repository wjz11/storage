<?php
	define('ROOT', dirname(__FILE__));
	chdir(ROOT);
	//数据库操作类
	require_once('vendor/autoload.php');


	//函数库
	require('inc/functions.php');
	require('inc/functions.private.php');

	//配置文件
	require('inc/config.php');
	require('inc/defines.php');