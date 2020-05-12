<?php
	require_once('../../globals.php');
	$notlogin=true;
	require_once('inc/weixiconfig.php');
/**
  * wechat php test
  */

//define your tokeninclude_once "wxBizMsgCrypt.php";

// 第三方发送消息给公众平台
wechatvalid();

//添加客服账号
/*print_r(getkf());
$account=array('kf_account'=>'1@'.WXID,'nickname'=>'1231','password'=>md5('pswmd5'));
$result=addkf($account);
print_r($result);
$account=array('kf_account'=>'1@'.WXID);
$result=delkf($account);
print_r($result);*/

/*if(!empty($GLOBALS['HTTP_RAW_POST_DATA'])){
	$returnxml=revicemsg($GLOBALS['HTTP_RAW_POST_DATA']);
	echo $returnxml;
}*/
?>