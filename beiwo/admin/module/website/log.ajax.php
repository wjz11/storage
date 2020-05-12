<?php
require('../../../global.php');

switch($_REQUEST['ac']){
    case 'list':
        if($_GET['search'] != ''){
            $where['AND']['name[~]'] = $_GET['search'];
        }
        if(isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])){
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        }
	$where['ORDER']['tbid'] = 'DESC';

//        $where['AND']['admin_member_id'] = session('admin_id');
        $echo['total'] = $db->count('tb_admin_member_operating_log', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_admin_member_operating_log', '*', $where);
        foreach($row as $value){
            $tmp['name'] = $db->get('tb_admin_member', 'username', array('tbid'=>$value['admin_member_id']));
            $tmp['edittime'] = $value['edittime'];
            $tmp['intro'] = $value['intro'];
            $detail = $db->get('tb_admin_member_operating_detail', '*', array('admin_member_operating_log_id'=>$value['tbid']));
            $tmp['type'] = $detail['type'] ==1? '编辑':'删除';
            $tmp['tables'] = $detail['tables'];
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
        break;

}
?>
