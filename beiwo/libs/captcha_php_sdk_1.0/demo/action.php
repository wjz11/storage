<?php
	require('../clicaptcha.sdk.php');

	//实例化
	$sdk = new clicaptcha_sdk('替换成你的私钥');
	//进行验证
	$cb = $sdk->check($_POST['clicaptcha-submit-info']);
	//返回数据处理
	switch ($cb->status) {
		case '10000':
			echo '后端二次验证成功';
			break;
		case '10001':
			echo '后端二次验证失败';
			break;
		case '10002':
			echo '私钥错误';
			break;
	}
	echo $cb->sessid;
?>
