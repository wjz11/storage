<?php
require('../../../global.php');

switch($_REQUEST['ac']){
    case 'upload':
        if (!checkAdminAuthorize('aboutUs.edit')) {

               $echo['status'] = '权限错误';
               echo json_encode($echo); 
               exit();
        }
        include('inc/Uploader.class.php');
        $config = array(
            'pathFormat' => 'uploads/about_us/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png','.jpg','.jpeg','.gif'), //文件允许格式
            'maxSize' => 2097152 //文件大小限制，单位B
        );
        $up = new Uploader('file', $config);
        $info = $up->getFileInfo();
        if($info['state'] == 'SUCCESS'){
            $url = explode('/', $info['url']);
            $url[count($url) - 1] = 's_'.$url[count($url) - 1];
            $url = implode('/', $url);
            imgsresize($info['url'], 400, 400, $url);
        }
      
        echo '{"url":"'.$info['url'].'","fileType":"'.$info['type'].'","original":"'.$info['original'].'","preview":"'.SITEROOTURL.$info['url'].'","state":"'.$info['state'].'"}';
        break;
}
?>
