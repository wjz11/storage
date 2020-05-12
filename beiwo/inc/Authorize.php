<?php

namespace inc;

class Authorize
{
    /**
     * 检查是否后台登录
     * @author bapijun
     * @param $sessionName 需要检查session名
     */
    static public function checkLogin($sessionNam = 'admin_id')
    {
        return session('?' . $sessionName) != NULL ? true : false;
    }
    /**
     * 检查是否后台登录,如果没有登录则跳转到首页
     * @author bapijun
     * @param $sessionName 需要检查session名
     */
    static public function checkLoginAndJump($sessionName = 'admin_id')
    {
        if (!self::checkLogin($sessionName)) {
            header('Location: index.php?redirectURL='.curPageURL());
        }
    }
    /**
     * 检查是否后台登录
     * @author bapijun
     * @param $sessionName 需要检查session名
     */
    static public function checkAdminLogin($sessionName = 'admin_id')
    {
        return session('?' . $sessionName) == NULL || session($sessionName) == 0 ? false : true;
    }
    /**
     * 检查是否后台登录,如果没有登录则跳转到登录页
     * @author bapijun
     * @param $sessionName 需要检查session名
     */
    static public function checkAdminLoginAndJump($sessionName = 'admin_id')
    {
        if (!self::checkAdminLogin($sessionName)) {
            redirect('login.php');
        }
    }

    /**
     * 检查当前的权限是否在当前用户的权限组中
     * @author bapijun
     * @param string $authorize session名称 需要检查的权限
     * @param string $sessionName 需要检查的session名
     * @param string $tableName 需要读取的表
     * @return bool 是否在当前用户的权限中
     */
    static public function checkAdminAuthorize($authorize, $sessionName = 'admin_id', $tableName = 'tb_admin_member')
    {
        global $db;
        $authorizelist = $db->get($tableName, 'authorizelist', array(
            'tbid' => session($sessionName)
        ));
        $authorizelist = explode(',', $authorizelist);
        return in_array($authorize, $authorizelist) ? true : false;
    }
    /**
     * 检查当前的权限是否在当前用户的权限组中,如果不在权限组中则跳转到无授权提示ye
     * @author bapijun
     * @param string $authorize 需要检查的权限
     * @param string $authorizelist 权限组,如果为空则需要读取数据库,不为空则为用|间隔权限的字符串
     * @param string $sessionName 需要检查的session名
     * @param string $tableName 需要读取的表
     * @return bool 是否在当前用户的权限中
     */
    static public function checkAdminAuthorizeAndJump($authorize, $authorizelist=NULL, $sessionName  = 'admin_id', $tableName = 'tb_admin_member')
    {
        //权限检查的时候如果是权限组的话,请填写后一个字段,用|作为间隔
        if (NULL != $authorizelist) {
            $authorizelist = explode('|',$authorizelist);
            $flag = false;
            foreach ($authorizelist as $key => $value) {
                if(self::checkAdminAuthorize($value, $sessionName, $tableName)){
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                redirect(SITEROOTURL.'admin/no_authorize.php');
            }

        } else {
            if(!self::checkAdminAuthorize($authorize, $sessionName, $tableName)){
                redirect(SITEROOTURL.'admin/no_authorize.php');
            }
        }
    }


}



