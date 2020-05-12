<?php
define('PLATFORM', '倍沃微信');
#define('SITEROOTURL','http://{{host}}/');
define('SITEROOTURL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('DOFILESPATH', 'uploads/');
define('FILESPLIT', '<{|}>');
define('AVATAR', '/uploads/avata.png');

//短信模板
//define('MESSAGE_APPKEY', '23391749');
//define('MESSAGE_SECRETKEY', '6eef104ed6e80736ee310686b16337f0');
//define('MESSAGE_SIGNAME', '易网测试');
//define('MESSAGE_TEMPLATE', 'SMS_10795433');

define('MESSAGE_APPKEY', '23391749');
define('MESSAGE_SECRETKEY', '6eef104ed6e80736ee310686b16337f0');
define('MESSAGE_SIGNAME', '易网测试');
define('MESSAGE_TEMPLATE', 'SMS_10795433');
//1=>'注册',2=>'找回密码'3=>'修改支付密码',4=>'绑定银行账户',5=>'绑定第三方登陆账户',6=>'更换绑定手机(发送验证码给旧手机),7=>'更换绑定手机(发送验证码给新手机);
#define('MESSAGE_TEMPLATE',json_encode(array(1=>'SMS_10795433',2=>'SMS_10795433',3=>'SMS_10795433',4=>'SMS_10795433',5=>'SMS_10795433',6=>'SMS_10795433',7=>'SMS_10795433')));
//1=>'注册',2=>'找回密码'3=>'修改支付密码',4=>'绑定银行账户',5=>'绑定第三方登陆账户',6=>'更换绑定手机(发送验证码给旧手机),7=>'更换绑定手机(发送验证码给新手机);
#define('MESSAGE_TEMPLATE',json_encode(array(1=>'SMS_10795433',2=>'SMS_10795433',3=>'SMS_10795433',4=>'SMS_10795433',5=>'SMS_10795433',6=>'SMS_10795433',7=>'SMS_10795433')));

//最大上传文件大小，单位M
define('MAXFILESIZE', 5);
//最小提现金额(分)
define('MINWITHDRAW', 5000);
//token有效期，单位小时
define('VALIDITY', 24);
