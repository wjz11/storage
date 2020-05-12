<?php
function checkLogin()
{
    return session('?admin_id') != NULL ? true : false;
}
function checkLoginAndJump()
{
    if (!checkLogin()) {
        header('Location: index.php?redirectURL='.curPageURL());
    }
}
function checkAdminLogin()
{
    return session('?admin_id') == NULL || session('admin_id') == 0 ? false : true;
}
function checkAdminLoginAndJump()
{
    if (!checkAdminLogin()) {
        redirect('login.php');
    }
}


//管理员权限
function checkAdminAuthorize($authorize)
{
    global $db;
    $authorizelist = $db->get('tb_admin_member', 'authorizelist', array(
        'tbid' => session('admin_id')
    ));
    $authorizelist = explode(',', $authorizelist);
    return in_array($authorize, $authorizelist) ? true : false;
}
function checkAdminAuthorizeAndJump($authorize, $authorizelist=NULL)
{
    //权限检查的时候如果是权限组的话,请填写后一个字段,用|作为间隔
    if (NULL != $authorizelist) {
        $authorizelist = explode('|',$authorizelist);
        $flag = false;
        foreach ($authorizelist as $key => $value) {
            if(checkAdminAuthorize($value)){
                $flag = true;
                break;
            }
        }
        if (!$flag) {
            redirect(SITEROOTURL.'admin/no_authorize.php');
        }

    } else {
        if(!checkAdminAuthorize($authorize)){
            redirect(SITEROOTURL.'admin/no_authorize.php');
        }
    }
}

//获取完整URL
function curPageURL() 
{
    $pageURL = 'http';
    if(isset($_SERVER["HTTPS"]) == "on"){
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if($_SERVER["SERVER_PORT"] != "80"){
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }else{
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function replacetags($data)
{
	if (!get_magic_quotes_gpc()) {
		$data = addslashes($data);
	}
	return $data;
}

/**
* 对变量进行 JSON 编码
* @param mixed value 待编码的 value ，除了resource 类型之外，可以为任何数据类型，该函数只能接受 UTF-8 编码的数据
* @return string 返回 value 值的 JSON 形式
*/
function json_encode_ex($value)
{
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        $str = json_encode( $value);
        $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function($matchs){
            return  iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1]));
        }, $str);
        return $str;
    } else {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

//发送短信接口
function sendmsm($data)
{
    global $db;
    if($data && isset($data['mobile']) && isset($data['type'])){
        $cap = $db->get('captcha', '*', array("AND" => array(
            'mobile' => $data['mobile'],
            'type' => $data['type'],
            'status' => '0',
            'creatdatetime[<=]' => date('Y-m-d H:i:s'),
            'invaliddatetime[>=]' => date('Y-m-d H:i:s'))
        ));
        if($cap == NULL){
            $cap['captcha'] = GetRanderNumberStr2(6);
            $db->insert('captcha', array(
                'mobile' => $data['mobile'],
                'captcha' => $cap['captcha'],
                'status' => 0,
                'type' => $data['type'],
                'creatdatetime' => date('Y-m-d H:i:s'),
                'invaliddatetime' => date('Y-m-d H:i:s', strtotime('+30 minute'))
            ));
        }
        switch($data['type']){
            case 1: $data['type'] = '生成激活码'; break;
            case 2: $data['type'] = '找回密码'; break;
            case 3: $data['type'] = '修改支付密码'; break;
            case 4: $data['type'] = '绑定提现账号'; break;
            case 5: $data['type'] = '激活帐号'; break;
            case 6: $data['type'] = '更换绑定手机'; break;
            case 7: $data['type'] = '更换绑定邮箱'; break;
            case 8: $data['type'] = '编辑用户信息手机验证'; break;
            case 9: $data['type'] = '编辑用户信息邮箱验证'; break;
        }
        //发送短信
        require_once('libs/alidayu/TopSdk.php');
        date_default_timezone_set('Asia/Shanghai');

        $c = new TopClient;
        $c = new TopClient;
        $c->appkey = "23391749";
        $c->secretKey = "6eef104ed6e80736ee310686b16337f0";
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend($data['mobile']);
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("易网测试");
        if(1 == $data['code_type']) {
            $req->setSmsParam("{\"type\":\"".$data['type']."\",\"captcha\":\"".$cap['captcha']. ",生成代理激活码条数" . $data['count']. "条" ."\"}");
        }
        else {
            $req->setSmsParam("{\"type\":\"".$data['type']."\",\"captcha\":\"".$cap['captcha']. ",渠道号". $data['agent_type']. ",生成会员激活码条数" . $data['count']. "条"  ."\"}");
        }
        
        $req->setRecNum($data['mobile']);
        $req->setSmsTemplateCode("SMS_10795433");
        $resp = $c->execute($req);
        if($resp->result->success == 'true'){
            $echo['error'] = '';
            $echo['captcha'] = $cap['captcha'];
        }else{
            $echo['error'] = '短信发送失败，请稍后再试';
            $echo['errorInfo'] = '错误代码：'.$resp->sub_code.'，错误信息：'.$resp->sub_msg;
        }
        return json_encode($echo);
    }
}

function post($curlPost,$url)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	$return_str = curl_exec($curl);
	curl_close($curl);
	return $return_str;
}
//发送邮箱验证码接口
//邮件发送
function sendMailForNewUsr($data)
{
    require_once('libs/phpmail/class.phpmailer.php');
    global $db;
    $tl='全国遥控航空模型飞行员考核与监管系统';
    $mail = new PHPMailer();
    $mail->CharSet = 'utf-8';
    $mail->Encoding = 'base64';
    $mail->IsSMTP(); // 使用SMTP方式发送
    $mail->Host = 'smtp.mxhichina.com'; //您的企业邮局域名
    //$mail->SMTPSecure = 'ssl';
    $mail->Port=25;
    $mail->SMTPAuth = true; // 启用SMTP验证功能
    $mail->Username = 'fmw@cnasfc.org'; // 邮局用户名(请填写完整的email地址)
    $mail->Password = 'CnASFC+FMTY2016#&'; // 邮局密码
    $mail->From = 'fmw@cnasfc.org'; //邮件发送者email地址
    	try {
    	$mail->From = 'fmw@cnasfc.org';
    	$mail->FromName =$tl;
    	$mail->AddAddress($data['usremail'],$data['usremail']);
    	//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
    	$mail->AddReplyTo('fmw@cnasfc.org', '');
    	//$mail->AddAttachment('/var/tmp/file.tar.gz');
    	$mail->IsHTML(true);
        $cap = $db->get('captcha', '*', array("AND" => array(
            'mobile' => $data['usremail'],
            'type' => $data['type'],
            'ctype' => 0,
            'creatdatetime[<=]' => date('Y-m-d H:i:s'),
            'invaliddatetime[>=]' => date('Y-m-d H:i:s'))
        ));
        if($cap == NULL){
            $cap['captcha'] = rand(100000, 999999);
            $db->insert('captcha', array(
                'mobile' => $data['usremail'],
                'captcha' => $cap['captcha'],
                'type' => $data['type'],
                'creatdatetime' => date('Y-m-d H:i:s'),
                'invaliddatetime' => date('Y-m-d H:i:s', strtotime('+30 minute'))
            ));
        }
        switch($data['type']){
            case 1: $data['type'] = '注册'; break;
            case 2: $data['type'] = '找回密码'; break;
            case 3: $data['type'] = '修改支付密码'; break;
            case 4: $data['type'] = '绑定提现账号'; break;
            case 5: $data['type'] = '激活帐号'; break;
        }

    	$mail->Subject='全国遥控航空模型飞行员考核与监管系统--邮箱验证码密保邮件';
    	$mail->Body='您正在进行'.$data['type'].'操作，验证码为：'.$cap['captcha'].'，请填写验证码并完成后续操作。（请勿将验证码告诉他人）';

    	$mail->Send();
    	}catch(phpmailerException $e) {
    	//return '0'.$str_split.$e->errorMessage();
    	}catch(Exception $e) {
    	//return '0'.$str_split.$e->getMessage();
    	}
    	if($mail->IsError()){
            $echo['error'] = $mail->ErrorInfo;
    	}else{
            $echo['error'] = '';
            $echo['captcha'] = $cap['captcha'];
    	}
        return json_encode($echo);
}

//检验验证码是否正确
function checkCaptcha1($mobile, $captcha, $type)
{
    global $db;
    $cap = $db->get('captcha', '*', array("AND" => array(
        'mobile' => $mobile,
        'captcha' => $captcha,
        'type' => $type,
        'status' => 0,
        'creatdatetime[<=]' => date('Y-m-d H:i:s'),
        'invaliddatetime[>=]' => date('Y-m-d H:i:s'))
    ));
    if($cap == NULL){
        $re = false;
    }else{
        $re = true;
        $db->update('captcha', array('status' => 1), array('id' => $cap['id']));
    }
    return $re;
}

//获取最低级分类
function getBottomCategory($parent_category_id, $category)
{
    static $bootomCategoryId = array();
    $flag = true;
    foreach ($category as $v) {
        if ($v['parent_category_id'] == $parent_category_id) {
            $flag = false;
            getBottomCategory($v['id'], $category);
        }
    }
    if ($flag) {
        $bootomCategoryId[] = $parent_category_id;
    }
    return $bootomCategoryId;
}

function GetRanderNumberStr2($len)
{
    $chars_array = array(
    "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
  );
  $charsLen = count($chars_array) - 1;

  $outputstr = "";
  for ($i=0; $i<$len; $i++) {
    $outputstr .= $chars_array[mt_rand(0, $charsLen)];
  }
  return $outputstr;
}
/*
 * 生成随机的长度的字符串
 *
 * @param $len字符串的长度  $chars_array字符集,如果为空则默认为数字+字母
 *
 */
function GetRanderLetter($len, $chars_array=array())
{
    
    if (empty($chars_array)) {
        $chars_array = array(
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
        );
    }

    $charsLen = count($chars_array) - 1;
    
    $outputstr = "";
    for ($i=0; $i<$len; $i++) {
        $outputstr .= $chars_array[mt_rand(0, $charsLen)];
    }
    return $outputstr;
}
function get_mobile_area($mobile)
{//获取来电归属地
       //初始化变量
    //根据淘宝的数据库调用返回值
    if (!empty($mobile)) {
        $url = "http://sj.apidata.cn/?mobile=".$mobile;
        $content = file_get_contents($url);
        $sms = json_decode($content, true);
        return get_area($sms['data']);
    } else {
        return '';
    }
   
}

function array_iconv($data, $output = 'utf-8')
{
    $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
    $encoded = mb_detect_encoding($data, $encode_arr);
    if (!is_array($data)) {
        return mb_convert_encoding($data, $output, $encoded);
    } else {
        foreach ($data as $key=>$val) {
            $key = array_iconv($key, $output);
            if(is_array($val)) {
                $data[$key] = array_iconv($val, $output);
            } else {
                $data[$key] = mb_convert_encoding($data, $output, $encoded);
            }
        }
    return $data;
    }
}

function my_is_int($value) 
{
    return is_numeric($value)&&is_int($value+0);
}
/**
* 将输入的秒转化成对应格式的时间
* @param 输入的变量单位是秒
* @return 存在有值返回 true 否则返回false,
* @author : bapijun
*/
function SencondToTime($seconds)
{
    //将对应的时间s转化为时分秒
    $hours =  intval($seconds/3600);
    $seconds  = $seconds - $hours*3600;
    $minutes = intval($seconds/60);
    $seconds = $seconds-$minutes*60;
    return ($hours?$hours.'时':'').($minutes?$minutes.'分':'').$seconds . '秒';
}

function checkVar($var) 
{
    return isset($var)&&!empty($var);
}
/**
 * 检查来自index传入的变量是否存在且且有值
 * @param 输入的变量,通常是$_GET和$_POST的全局变量
 * @return 存在有值返回 true 否则返回false,
 * @author : bapijun
 */
function checkPutIn($var)
{
    /* return isset($var)&&!empty($var); */
    if (!isset($var)) {
        return false;
    } else {
        if($var === '') {
            return false;
        } else {
            return true;
        }
    }
}

/**
 * 货币转化,去除掉数据库中decimal中小数点后面的0
 * @param 输入的变量,数据库中的货币类型decimal
 * @return 对应的货币,去除了小数点后面的0
 * @author : bapijun
 */
function floatZeroCut($num = 0.00, $count=0)
{  
    if (!preg_match('/^(-?\d+)(\.\d+)?$/', $num)) {  
        return 0;  
    }  
    if ((int)($num) == $num) {  
        return intval($num);  
    }  
    $strNum = strval($num); 
    if (substr($num, -1) == '0') {  
        $strNum = substr($strNum, 0, strlen($num) - 1);  
        return floatZeroCut(floatval($strNum));  
    } else {  
        return floatval($strNum);  
    }  
} 

/**
 * 检查传入的变量是否为null,如果是null返回空字符串,否则返回原来变量,主要用于对数据库的返回值进行检测
 * @param 输入的变量是数据库的返回值
 * @return 存在有值返回 原值 否则返回'',
 * @author : bapijun
 */
function checkIsNullAndReturn($var) {
    return $var? $var : '';
}

/**
 * var_dump一个变量,并结束php进程
 * @param 需要打出的变量
 * @return 
 * @author : bapijun
 */


function dumpAndExit($var) {
    var_dump($var);
        exit();
}
/**
 * 生成唯一的随机订单号
 * @param $pre 前缀  $end后缀
 * @return 生成订单号
 * @author : bapijun
 */

function getordernum($pre,$end='') {
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K','L','M','N','O','P');
    $orderSn=$yCode[intval(date('Y'))-2017].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
    return $pre.$orderSn.$end;
}
//记录操作日志  $action 代表操作行为  $type操作对象 数据库 $obj操作对象 数据表
//引用方法: $res = RecordOperation($intro='操作介绍',$table='数据表',$type='1编辑2删除',$olddata='操作前json字符串',$newdata='操作后json字符串');
function RecordOperation($intro,$table,$type,$olddata,$newdata){
    global $db;
//    $admin_name = $db->get("admin", "username",array(
//        "id" => session('admin_id')
//    ));
//    $set['username'] = $admin_name;
    $set['admin_member_id'] = session('admin_id')?session('admin_id'):session('member_id');
    $set['intro'] = $intro;
//    $set['ip'] = GetIPaddress();
    $set['edittime'] = date('Y-m-d H:i:s');
    $rs = $db->insert('tb_admin_member_operating_log', $set);
    $id=$db->query("SELECT tbid FROM `tb_admin_member_operating_log`  ORDER BY tbid DESC LIMIT 1")->fetchAll();
    //print_r($id);die;
    if($rs){
        $sets = array(
            'admin_member_operating_log_id' => $id[0]['tbid'],
            'tables'=>$table,
            'type'=>$type,
            'olddata'=>$olddata,
            'newdata'=>$newdata,
            'edittime'=>date('Y-m-d H:i:s')
        );
        //print_r($sets);die;
        $res = $db->insert('tb_admin_member_operating_detail', $sets);
//        dump($res);
//        return $res?true:false;
    }
}
//table表格 $action 增加/编辑 $type 1编辑2删除
//例子:tb_set表 id=1 action 编辑/增加设置 data 需要添加的数据数组;type 1编辑 2删除
function logo_record($table,$id,$action,$data,$type){
    /*
            * 编辑之前的数据
            */
    /*记录操作日志  $action 代表操作行为  $type操作对象 数据库 $obj操作对象 数据表
           //引用方法: $res = RecordOperation($intro='操作介绍',$table='数据表',$type='1编辑2删除',$olddata='操作前json字符串',$newdata='操作后json字符串');
           */
    global $db;
    $olddataarray = array();
    $newdataarray = array();

    if($type == 1) {
        if (!empty($id)) {
            $info = $db->get($table, '*', array('tbid' => $id));
            if(!empty($info)){
            foreach ($info as $k => $v) {
                if (preg_match("/[\x7f-\xff]/", $v)) {
                    $v = urlencode($v);
                }
                $olddataarray[$k] = $v;
            }
            }
            $olddata = urldecode(json_encode($olddataarray));
        } else {
            $olddata = '';
        }
        if(!empty($data) && is_array($data)) {
            foreach ($data as $k1 => $v1) {
                if (preg_match("/[\x7f-\xff]/", $v1)) {
                    $v1 = urlencode($v1);
                }
                $newdataarray[$k1] = $v1;
            }
        }
        $newdata = urldecode(json_encode($newdataarray));

        RecordOperation($action, $table, $type, $olddata, $newdata);
    }else{
        $info = $db->get($table, '*', array('tbid' =>  $id));
        foreach($info as $k => $v){
            if (preg_match("/[\x7f-\xff]/", $v)) {
                $v = urlencode($v);
            }
            $olddataarray[$k] = $v;

        }
        $olddata = urldecode(json_encode($olddataarray));
        $newdata = '';
        RecordOperation($action,$table,$type,$olddata,$newdata);
    }
    /*
     * 记录操作日志
     */
}
