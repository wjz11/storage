<?php
require('../../../global.php');

switch($_REQUEST['ac']){
    case 'checkPassword':
        if (!checkAdminLogin()) {

               $echo['status'] = '权限错误';
               echo json_encode($echo); 
               exit();
        }
        $pwHash = $db->get('tb_admin_member', 'password',array(
            'tbid' => session('admin_id')
        ));
        if(password_verify($_POST['param'], $pwHash)){
            $cb['info'] = '';
            $cb['status'] = 'y';
        }else{
            $cb['info'] = '原密码错误';
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'password':
        if (!checkAdminLogin()) {

               $echo['status'] = '权限错误';
               echo json_encode($echo); 
               exit();
        }
        $set = array('password' => password_hash($_POST['password'], PASSWORD_DEFAULT),'tbid' => session('admin_id'));
       // logo_record('tb_admin_member',session('admin_id'),'更新密码',$set,1);

        $rs = $db->update('tb_admin_member', array('password' => password_hash($_POST['password'], PASSWORD_DEFAULT)), array('tbid' => session('admin_id')));
        if($rs > 0){

            $cb['status'] = 'y';
        }else{
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'info':
        if (!checkAdminLogin()) {

               $echo['status'] = '权限错误';
               echo json_encode($echo); 
               exit();
        }
        //logo_record('tb_admin_member',session('admin_id'),'更新个人资料',$set,1);
        $rs = $db->update('tb_admin_member', array(
            'realname' => $_POST['realname'],
            'mobile' => $_POST['mobile'],
            'qq' => $_POST['qq']
        ), array('tbid' => session('admin_id')));
        if($rs > 0){
            $set = array(
                'realname' => $_POST['realname'],
                'mobile' => $_POST['mobile'],
                'qq' => $_POST['qq']
            );
           

            $cb['status'] = 'y';
        }else{
            $cb['status'] = 'n';
        }
        echo json_encode($cb);
        break;
    case 'avatar':
		$result = array();
		$result['success'] = false;
		$success_num = 0;
		$msg = '';
		//上传目录
		$dir = 'uploads/admin/'.session('admin_id').'/';
        recursive_mkdir($dir);

        $source_pic = $_FILES["__source"];
        //如果在插件中定义可以上传原始图片的话，可在此处理，否则可以忽略。
        if($source_pic){
        	if($source_pic['error'] > 0){
        		$msg .= $source_pic['error'];
        	}else{
        		//当前头像基于原图的初始化参数（只有上传原图时才会发送该数据，且发送的方式为POST），用于修改头像时保证界面的视图跟保存头像时一致，提升用户体验度。
        		//修改头像时设置默认加载的原图url为当前原图url+该参数即可，可直接附加到原图url中储存，不影响图片呈现。
        		$init_params = $_POST["__initParams"];
        		$result['sourceUrl'] = SITEROOTURL.$dir.'avatar.source.jpg'.$init_params;
        		move_uploaded_file($source_pic["tmp_name"], $dir.'avatar.source.jpg');
        		$success_num++;
        	}
        }
		//处理头像图片
		$result['avatarUrls'][0] = SITEROOTURL.$dir.'avatar.jpg';
		move_uploaded_file($_FILES['__avatar1']['tmp_name'], $dir.'avatar.jpg');
		$success_num++;

		$result['msg'] = $msg;
		if($success_num > 0){
            $result['success'] = true;
		}
		//返回图片的保存结果（返回内容为json字符串）
		echo json_encode($result);
		break;
}
?>
