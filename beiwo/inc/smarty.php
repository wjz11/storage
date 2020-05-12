<?php
    require_once('libs/smarty-3.1.29/libs/Smarty.class.php');
    $smarty = new Smarty();
	$smarty->template_dir = 'templates/';
	$smarty->compile_dir = 'templates_c/';
	$smarty->left_delimiter = "{{";
	$smarty->right_delimiter = "}}";
?>
