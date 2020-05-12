<?php
namespace admin\goods;
use inc\BaseAjax;

require('../../../global.php');


$ajax = new Goods('goods');
$ajax->dealWithAjax();
