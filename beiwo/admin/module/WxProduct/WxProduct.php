<?php

namespace admin\WxProduct;

use inc\BaseAjax;


class WxProduct extends BaseAjax
{
    
    public function listWxProduct()
    {
        $where['AND']['item_number[!]'] = "";
        $where['AND']['name[!]'] = "";
        //$where['AND']['goods_category_id'] = 23;
        $echo['total']                     = $this->db->count('tb_goods', '*', $where);
        $where['LIMIT']                    = array($_GET['offset'], $_GET['limit']);
        $where['ORDER']                    = array('indexid' => 'ASC', 'tbid' => 'DESC');
        $echo['rows']                      = array();
        $row = $this->db->select('tb_goods', ['name','item_number','tbid'], $where);
        foreach ($row as $value) {
            $tmp['name'] = $value['name'];
            $tmp['item_number'] = $value['item_number'];
            $tmp['url'] = 'http://beiwo.biomiga.com.cn/h5/pro_xq.html?xId='.$value['tbid'];
            $echo['rows'][] = $tmp;
            unset($tmp);
        }
        echo json_encode($echo);
       // $url = 'http://beiwo.renxingdian.com/api/product/xList?cId=23&limit=999&offset=0';//SITEROOTURL.'/api/product/xList?cId=0&limit=999&offset=0';
        //echo $url;
        // $result = $this->curlGet($url);
        // print_r($result['data']['list']);die;
    }

    /**
     * curl get请求
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    protected function curlGet($url){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;
    }

}
