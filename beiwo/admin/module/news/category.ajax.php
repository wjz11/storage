<?php
require('../../../global.php');

switch($_REQUEST['ac']){
    case 'list':
        if($_GET['search'] != ''){
            $where['AND']['name[~]'] = $_GET['search'];
        }
        if(isset($_GET['sort']) && !empty($_GET['sort']) && isset($_GET['order']) && !empty($_GET['order'])){
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        }else{
            $where['ORDER']['tbid'] = 'DESC';
        }
        $echo['total'] = $db->count('tb_news_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $db->select('tb_news_category', '*', $where);
        foreach($row as $value){
            $tmp['name'] = $value['name'];
            if(checkAdminAuthorize('news.edit')){
                // if (1 == $value['type']) {
                //     //可以编辑
                //     $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="'.$value['tbid'].'"> 编辑</a> ';
                // }
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs item" data-id="'.$value['tbid'].'"> 查看新闻</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
        break;
    case 'edit':
        $set = array(
            'name' => $_POST['name'],
            'type' => 1,
            'edittime' => date("Y-m-d H:i:s")
        );
        if($_POST['id'] != ''){
            $db->update('tb_news_category', $set, array(
                'tbid' => $_POST['id']
            ));
            logo_record('tb_news_category',1,'编辑新闻分类',$set,1);
            $cb['status'] = 'y';
        }else{
            $set['createtime'] = date("Y-m-d H:i:s");
            $rs = $db->insert('tb_news_category', $set);
            if($rs){
                logo_record('tb_news_category',1,'新增新闻分类',$set,1);
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
        break;
    case 'delete':
        $rs = $db->delete('tb_news_category', array('tbid' => $_POST['id']));
        if($rs > 0){
            logo_record('tb_news_category',1,'删除新闻分类',$set,2);
            $cb['status'] = 'y';
        }else{
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
}
?>
