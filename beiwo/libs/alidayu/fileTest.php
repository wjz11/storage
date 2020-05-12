<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $c = new TopClient;
    $c = new TopClient;
	$c->appkey = "23378456";
	$c->secretKey = "f76b08a66d6e21c7485908544336c60e";
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	$req->setExtend("123456");
	$req->setSmsType("normal");
	$req->setSmsFreeSignName("阿达健身");
	$req->setSmsParam("{\"type\":\"注册\",\"captcha\":\"123456\"}");
	$req->setRecNum("15005811687");
	$req->setSmsTemplateCode("SMS_10275127");
	$resp = $c->execute($req);
	print_r($resp);
	var_dump();

    //$req2 = new TradeVoucherUploadRequest;
//    $req2->setFileName("example");
//
//    $myPic = array(
//            'type' => 'application/octet-stream',
//            'content' => file_get_contents('/Users/xt/Downloads/1.jpg')
//            );
//    $req2->setFileData($myPic);
//    $req2->setSellerNick("奥利奥官方旗舰店");
//    $req2->setBuyerNick("101NufynDYcbjf2cFQDd62j8M/mjtyz6RoxQ2OL1c0e/Bc=");
//    var_dump($c->execute($req2));
?>