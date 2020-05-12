<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>clicaptcha在线演示</title>
<link rel="stylesheet" href="css/main.css">
<script src="jquery-1.8.3.min.js"></script>
<script src="http://captcha.1one.cn/realization/clicaptcha.js.php?publickey=替换成你的公钥"></script>
<script>
$(function(){
	$('#sub').click(function(){
		$('#clicaptcha-submit-info').clicaptcha({
			callback: function(){
				$.ajax({
					type: 'POST',
					url: 'action.php',
					data: {
						'clicaptcha-submit-info': $('#clicaptcha-submit-info').val()
					}
				}).done(function(cb){
					alert(cb)
				});
			}
		});
	});
});
</script>
</head>

<body>
<div id="login">
	<form action="action.php" method="post" id="form" name="form">
		<div class="row">
			<div class="text">用户名：</div>
			<div class="input"><input type="text"></div>
		</div>
		<div class="row">
			<div class="text">密码：</div>
			<div class="input"><input type="password"></div>
		</div>
		<div class="row">
			<button type="button" id="sub">提交</button>
		</div>
		<input type="hidden" id="clicaptcha-submit-info" name="clicaptcha-submit-info">
	</form>
</div>
</body>
</html>
