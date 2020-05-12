<?php
namespace admin\precaution;
use inc\BaseAjax;

class Precaution extends BaseAjax
{
    /**
     * 列出分类
     * @Author : bapijun
     */
    protected function listCategory()
    {
      
       
        if($_GET['search'] != ''){
            $where['AND']['name[~]'] = $_GET['search'];
        }
       
       
        $this->setSortWhere($where);
        $echo['total'] = $this->db->count('tb_precaution_category', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_precaution_category', ['name', 'tbid'], $where);
        foreach($row as $value){
           
            $tmp['name'] = $value['name'];
          
            if (\inc\Authorize::checkAdminAuthorize('precaution.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="'.$value['tbid'].'">编辑</a> ';
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs item" data-id="'.$value['tbid'].'">查看分项</a> ';
                 //$tmp['do'] = ' <a href="javascript:;" class="btn btn-primary btn-xs remove" data-id="'.$value['tbid'].'">删除</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }
        /**
     * 列出分项
     * @Author : bapijun
     */
    protected function listItem()
    {
      
       
        if($_GET['search'] != ''){
            $where['AND']['precaution[~]'] = $_GET['search'];
        }
        
       
        $where['AND']['category_id'] = $_GET['cid'];
        $where['ORDER']['indexid'] = 'ASC';

        $echo['total'] = $this->db->count('tb_precaution', '*', $where);
        $where['LIMIT'] = array($_GET['offset'], $_GET['limit']);
        $echo['rows'] = array();
        $row = $this->db->select('tb_precaution', ['precaution', 'tbid', 'indexid'], $where);
        foreach($row as $value){

            $tmp['precaution'] = mb_strlen($value['precaution'], 'utf-8') > 50 ? mb_substr($value['precaution'], 0, 50, 'utf-8') . '...' : $value['precaution'];
            $tmp['indexid'] = $value['indexid'];
            if (\inc\Authorize::checkAdminAuthorize('connectUs.edit')) {
                $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs edit" data-id="'.$value['tbid'].'">编辑</a> ';
               
                 $tmp['do'] .= ' <a href="javascript:;" class="btn btn-primary btn-xs remove" data-id="'.$value['tbid'].'">删除</a> ';
            }
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
    }
     /**
     * 删除分项
     * @return [type] [description]
     */
    protected function deleteItem()
    {
        $this->db->delete('tb_precaution', ['tbid' => $_POST['id']]);
         if($res){
            $cb['status'] = 'y';
        }else{
            $cb['status'] = 'n';
        }
         echo json_encode($cb);
    }
    /**
     * 编辑联系我们分类
     * @Author : bapijun

     */
    protected function editCategory()
    {
       
        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s'); 
        unset($set['id']);
        if(!empty($_POST['id'])){
            $this->db->update('tb_precaution_category', $set,  ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        }else{
          
            $res = $this->db->insert('tb_precaution_category', $set);
            if($res){
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);

    }
        /**
     * 编辑联系我们分项
     * @Author : bapijun

     */
    protected function editItem()
    {

        unset($_POST['ac']);
        foreach ($_POST as $key => $value) {
            $set[$key] = $value;//默认传入的name等于数据库的column
        }
        $set['edittime'] = date('Y-m-d H:i:s'); 
        unset($set['id']);
        if(!empty($_POST['id'])){
            $this->db->update('tb_precaution', $set ,  ['tbid' => $_POST['id']]);
            $cb['status'] = 'y';
        }else{
          
            $res = $this->db->insert('tb_precaution', $set);
            if($res){
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }

        echo json_encode($cb);
    }
}
