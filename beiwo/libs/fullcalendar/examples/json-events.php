<?php
	require_once('../../../global.php');
	require_once('inc/setting.inc.php');
	require_once('inc/smarty.php');
	require_once('global_info.php');
	$year = date('Y');
	$month = date('m');
	$tbid=$_GET['id'];
	$tianshi="[";
	$piaojia = $db->select(0, 0, 'tb_xingc', '*',"and member_id=".$tbid,"tbid asc");
	foreach($piaojia as &$value){
		$value['xcdt']=explode(" ",$value['xcdt']);
		$tyear = date('Y',strtotime($value['xcdt'][0]));
		$tmonth = date('m',strtotime($value['xcdt'][0]));
		$value['tscha']=dtDiff1("d",$value['xcdt'][0],$value['xcdt'][0]);
		if($value['sinfo']==1){
			$myan="已安排";
		}else{
			$myan="未安排";
		}	
		if($value['minfo']==1){
			$wyan="已安排";
		}else{
			$wyan="未安排";
		}	
		for($i=0;$i<=$value['tscha'];$i++){
			$time=dtAdd1("d",$i,$value['xcdt'][0]);
			$tianshi.="{'id':'".$i."','title':'午宴：".$myan."晚宴：".$wyan."点击编辑行程','start':'".$time."','url':'#".$value['tbid']."'},";
		}
	}
	$tianshi=$xianlu=substr($tianshi,0,strlen($tianshi)-1);
	$tianshi.="]";
	echo $tianshi;
	
?>
