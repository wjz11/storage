<?php
namespace admin\admin;
use inc\BaseAjax;
use inc\Authorize;

/**
 * 员工
 * @author yiwang
 *
 */
class Member extends BaseAjax
{
   
    /**
     * 列出员工
     */
    protected function listMember()
    {
        
        if($_GET['search'] != ''){
            $where['AND']['OR'] = array(
            
                'tb_admin_member.realname[~]' => $_GET['search'],
                'tb_admin_member.mobile[~]' => $_GET['search'],
            );
        }
        if($_GET['department'] != ''){
            $where['AND']['tb_admin_member.department_id'] = $_GET['department'];
        }
        if($_GET['parent_lev'] != ''){
            $where['AND']['tb_admin_member.parent_lev_id'] = $_GET['parent_lev'];
        }
        
        $where['ORDER']['tb_admin_member.tbid'] = 'DESC';
       
        $echo['rows'] = array();
        $ro = array(
            'tb_admin_member.mobile(adminmobile)',
            'tb_admin_member.tbid',
            'tb_admin_member.realname',
            'tb_admin_member.username',
            'tb_admin_member.department_id',
        );
        $row = $this->db->select('tb_admin_member', $ro, $where);
       
        foreach ($row as &$value) {
            $value['department'] = $this->db->get('tb_admin_department', 'name', array('tbid'=>$value['department_id']));
            
            $logintime = $this->db->get('tb_admin_member_login_log', 'createtime', array(
                'admin_member_id' => $value['tbid'],
                'ORDER' => array(
                    'createtime' => 'DESC'
                )
            ));
            $value['logintime'] = $logintime?$logintime:"暂无登录记录";
            $value['avatar'] = file_exists('uploads/admin/'.$value['tbid'].'/avatar.jpg') ? SITEROOTURL.'uploads/admin/'.$value['tbid'].'/avatar.jpg' : SITEROOTURL.'admin/img/avatar.jpg';
        }
        $echo['rows'] = $row;
        $echo['authorize'] = Authorize::checkAdminAuthorize('admin.edit');
        $echo['admin_id'] = session('admin_id');
        echo json_encode($echo);
    }
   /**
    * 编辑员工
    */
    protected function edit()
    {
        // print_r($_POST);die;
        if($_POST['id'] != ''){
            $set = array(
                'realname' => $_POST['realname'],
                'parent_lev_id' => $_POST['parent_lev'],
                'department_id' => $_POST['department'],
               
                'mobile' => $_POST['mobile'],
                'special' => $_POST['special'],
                'addtime' => $_POST['applystarttime'],
                'edittime' => date('Y-m-d H:i:s'),
          
                'authorizelist' => $_POST['authorizelist'],
            );
            
            $this->db->update('tb_admin_member', $set, array(
                'tbid' => $_POST['id']
            ));
            $cb['status'] = 'y';
        }else{
           
          
            $set = array(
                'username' => $_POST['username'],
                'realname' => $_POST['realname'],
                'parent_lev_id' => $_POST['parent_lev'],
                'department_id' => $_POST['department'],
                'mobile' => $_POST['mobile'],
                'special' => $_POST['special'],
                'addtime' => $_POST['applystarttime'],
                'edittime' => date('Y-m-d H:i:s'),
               
                'authorizelist' => $_POST['authorizelist'],
            );
            if(isset($_POST['password']) && !empty($_POST['password'])){
                $set['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                $password='123456';
                $set['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
         
            
            $rs = $this->db->insert('tb_admin_member', $set);
            if($rs){
                $cb['status'] = 'y';
            }else{
                $cb['status'] = 'n';
            }
        }
        echo json_encode($cb);
    }
    
    /**
     * 删除员工
     */
    protected function delete()
    {
        $rs = $this->db->delete('tb_admin_member', array('tbid' => $_POST['id']));
       
        if($rs > 0){
            $cb['status'] = 'y';
        }else{
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }
    
   
    /**
     * 获取权限
     */
    protected function getAuthorizeInfo()
    {
        global $g_admin_authorize;
   
        $authorizelist = explode(',', $_GET['authorizelist']);
       
        foreach($g_admin_authorize as $k => $v){
            $i = 0;
            foreach($g_admin_authorize[$k] as $kk => $vv){
                if($i != 0){
                    if(in_array($k.'.'.$kk, $authorizelist)){
                        $cb[] = $v['_title'].'：'.$vv;
                    }
                }
                $i++;
            }
            //print_r($cb);die;
        }
        echo json_encode($cb);
    }
   
    protected function getdepartment()
    {
        $str = "<option value=''>全部职位</option>";
        //$cb['status'] = 0;
        if($_POST['id']){
            $set = $this->db->select('tb_admin_department','*',array('parent_lev'=>$_POST['id']));
            foreach($set as $value){
                if($_POST['department_id'] == $value['tbid']){
                    $str .= "<option value='".$value['tbid']."' selected>".$value['name']."</option>";
                }else{
                    $str .= "<option value='".$value['tbid']."'>".$value['name']."</option>";
                }
            }
            //$cb['status'] = 1;
        }
        // print_r($str);die;
        $cb['option']=$str;
        echo json_encode($cb);
    }
}


