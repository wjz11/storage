<?php
	class clicaptcha_sdk{
		private $privatekey;
		function __construct($privatekey){
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			session_start();
			$this->privatekey = $privatekey;
		}
		public function check($info){
			list($info, $sessid) = explode('+', $info);
			$ch = curl_init();
			$url = 'http://captcha.1one.cn/realization/clicaptcha.php?do=backcheck&privatekey='.$this->privatekey.'&info='.$info;
			$cookie = 'PHPSESSID='.$sessid;
			session_write_close();
			//设置选项，包括URL
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			//执行并获取HTML文档内容
			$output = curl_exec($ch);
			$output = json_decode($output);
			//释放curl句柄
			curl_close($ch);
			session_start();
			return $output;
		}
	}
?>
