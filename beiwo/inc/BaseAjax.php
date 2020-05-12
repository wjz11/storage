<?php
/**
 * BaseAjax,处理后台操作的类
 * @Author : bapijun
 */
namespace inc;
class BaseAjax
{
    private $foldName;//上传文件夹的名称
    protected $db;
    /**
     * 通过解析对应的request参数,选择对应的函数进行处理
     * @Author : bapijun
     */
    public function dealWithAjax()
    {
        global $db;
        $this->db = $db;
        if (checkVar($_REQUEST['ac'])) {
            $func =  $_REQUEST['ac'];
            $this->$func();//调用可变函数
        } else {
            throw new Exception("Can't found parame ac");
        }
    }
    /**
     * 设置table的排序顺序,如果有给排序就按照对应的排序走,否则按照tbid的逆序排序
     * @parame $where 引用类型,对应的where数组
     * @Author : bapijun
     */
    protected function setSortWhere(&$where)
    {
        if(checkVar($_GET['sort'])&& checkVar($_GET['order'])){
            $where['ORDER'][$_GET['sort']] = strtoupper($_GET['order']);
        } else {
            $where['ORDER']['tbid'] = 'DESC';
        }
    }
/**
     * 图片上传处理函数这里需要处理到额外服务器
     * @Author : bapijun
     */
    protected function upload()
    {
        $width = checkVar($_GET['width']) ? $_GET['width'] : 200;
        $height = checkVar($_GET['height']) ? $_GET['height'] : 200;
        
        include('inc/Uploader.class.php');
        $config = array(
            'pathFormat' => 'uploads/' . $this->foldName . '/{time}{rand:6}', //保存路径
            'allowFiles' => array('.png','.jpg','.jpeg','.gif'), //文件允许格式
            'maxSize' => 2097152 //文件大小限制，单位B
        );
        $up = new \Uploader('file', $config);
        $info = $up->getFileInfo();
         
        if($info['state'] == 'SUCCESS'){
             
            $url = explode('/', $info['url']);
            $url[count($url) - 1] = 's_'.$url[count($url) - 1];
            $url = implode('/', $url);
            imgsresize($info['url'], $width, $height, $url);

        }
        echo '{"url":"'.$info['url'].'","fileType":"'.$info['type'].'","original":"'.$info['original'].'","preview":"'.SITEROOTURL.$info['url'].'","state":"'.$info['state'].'"}';

    }
    /**
     * 构造函数
     * @parame $foldName 定义的上传文件夹位置
     * @Author : bapijun
     */
    public function __construct($foldName='img')
    {
        $this->foldName = $foldName;
    }

    function __destruct()
    {}
    
    /**
     * 获取该表递进的一下个id值
     * @parame $table 表名字 $id主键名,默认是tbid
     * @return 下一个可以插入的id
     * @Author : bapijun
     */
    protected function getNextID($table, $id='tbid')
    {
        $where['ORDER'][$id] = 'DESC';
        return $this->db->get($table, $id, $where)+1;
    }
    /**
     * 软删除,只修改isdel=1
     * @parame $table 表名字 $id主键名,默认是tbid
     * @return 下一个可以插入的id
     * @Author : bapijun
     */
    
    protected function softDelete($table, $where)
    {
        $set = [
            'isdel' => 1,
            'edittime' => date('Y-m-d H:i:s'),
        ];
        return $this->db->update($table, $set, $where);
        
    }
}

