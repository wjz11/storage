<?php
namespace admin;
use \inc\BaseAjax;
use \inc\MyEncry;

require_once('../global.php');

class LoginAjax extends BaseAjax
{

    /**
     * 登出函数
     * @Author : bapijun
     */
    protected function logout()
    {
        session('admin_id', NULL);
        redirect('login.php');
    }
    /**
     * 登录函数
     * @Author : bapijun
     * echo 登录的状态,如归是错误的话,添加对应的错误的返回值
     */
    protected function login()
    {
        
        $row = $this->db->get('tb_admin_member', '*', array(
            'AND' => array(
                'username' => $_POST['username'],
            )
        ));
        
        if(password_verify($_POST['password'], $row['password'] )){
            session('admin_id', $row['tbid']);
            $cb['info'] = '';
            $cb['status'] = 'y';
        }else{
            $cb['info'] = '用户名或密码错误';
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
    }
}

$ajax = new LoginAjax();
$ajax->dealWithAjax();

