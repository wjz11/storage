<?php
header("Content-type: text/html; charset=utf-8");

ob_start();
session('[start]');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

//定义魔术变量
if(version_compare(PHP_VERSION, '5.4.0', '<')){
    ini_set('magic_quotes_runtime', 0);
    define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc() ? TRUE : FALSE);
}else{
    define('MAGIC_QUOTES_GPC', FALSE);
}

//设置时区
date_default_timezone_set('Asia/Shanghai');

//把所有全局变量用discuz的daddslashes函数进行过滤
$_GET = daddslashes($_GET, 1, TRUE);
$_POST = daddslashes($_POST, 1, TRUE);
$_REQUEST = daddslashes($_REQUEST, 1, TRUE);
$_COOKIE = daddslashes($_COOKIE, 1, TRUE);

$_CONFIG = array(
    'title' => '倍沃',
	'authkey' => 'beiwo', //站点加密密钥，可随意更改
	'COOKIE_PREFIX' => '',
	'COOKIE_EXPIRE' => 0,
	'COOKIE_PATH' => '/',
	'COOKIE_DOMAIN' => '',
	'SESSION_PREFIX' => 'beiwo'
);


$str_split='<{|*|}>';$str_subsplit='<{*|*}>';$encodestr_split='%3C%7B%7C*%7C%7D%3E';$encodestr_subsplit='%3C%7B*%7C*%7D%3E';
$file_split='<{|}>';$file_subsplit='<{*}>';$encodefile_split='%3C%7B%7C%7D%3E';$encodefile_subsplit='%3C%7B*%7D%3E';
define('FILEROOTPATH', '/');
define('SITEROOTURL', 'http://'.$_SERVER ['HTTP_HOST'].FILEROOTPATH);
//创建数据库连接
$db = new \inc\Medoo(array(
	'database_type' => 'mysql',
	'database_name' => 'beiwo',// 
	'server' => 'localhost',//
 	'port' => 3306, 
	'username' => 'root',//
	'password' => 'eoner.com',// eoner.com
	'charset' => 'utf8',
	'option' => array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	)
));

