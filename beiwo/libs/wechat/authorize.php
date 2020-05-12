<?php
	session_start();
	require_once('../../global.php');
	include_once "libs/wechat/wxBizMsgCrypt.php";
	require_once('sqinc/inc/common.inc.php');
	require_once('sqinc/inc/wxfunction.php');
	
	$code=$_GET['code'];
	$callback=$_GET['callback'];
	if(!empty($code)){
		
		if(strpos($callback,'?')==false){
			$callback.='?';
		}else{
			$callback.='&';
		}
		$callback.='session_id='.checkuseraccesstoken($code,0,1);
	}
	goback($callback);
	
	
	function goback($callback){
		if(empty($callback)){
			$backurl='/index.php';
		}else{
			$backurl=$callback;
		}
		header('Location:'.$backurl);
	}
?>