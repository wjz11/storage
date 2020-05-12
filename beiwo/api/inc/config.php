<?php
session_start();
ini_set('date.timezone', 'Asia/Shanghai');
require_once 'api/inc/db.class.php';
require_once 'api/inc/function.inc.php';
require_once 'api/inc/function.oss.php';
require_once 'api/inc/defines.inc.php';

$db_config = array(
    'dbtype' => 'mysql',
//    'host' => '202.91.248.122',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'beiwo',
    'name' => 'root',
    'password' => 'eoner.com',
    'log' => 1,
    'prepare' => 1,
    'realdelete' => 0, //虚拟删除开关，设置为0时，调用delete方法不删除对应条目，而是把对应条目的is_del属性设置为1
    'charset' => 'utf8',
    'prefix' => 'tb_',
    'option' => array(PDO::ATTR_CASE => PDO::CASE_NATURAL),
);
$db = new DB($db_config);