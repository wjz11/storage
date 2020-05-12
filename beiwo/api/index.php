<?php
/**
 * 作者：陈志平
 * 日期：2016-12-30
 * 电邮：235437507@qq.com
 * 版本：V1.12
 * 更新时间：2018/7/30
 * 更新日志：
 * V1.1 新增自动处理数据库参数函数：
 * V1.2 新增json示例数据返回
 * v1.3 修改liux下api文档展示错误的问题
 * v1.4 修改配置参数#2展示json数据不正确的问题
 * v1.5 新增jsonp返回格式，callback作为保留字段
 * v1.6 修复getcontent的不传送data数据的bug
 * v1.7 修复在低分辨率下栏目过多无法看全的bug
 * v1.8 新增入参数组可非必填功能
 * v1.9 调整测试输出数据
 * v1.10 解决class不存在接口输出文档报错的bug
 * v1.11 新增接口访问记录
 * v1.12 接口访问记录新增OTHER类型
 */

header("Access-Control-Allow-Origin: *");

require_once('../globals.php');
require_once('api/inc/config.php');

#getcolumn('scenic_product',2);
#$db->copy('goods_order','goods',array('ordernumber'=>'tbid'),array('tbid'=>array(2,3)));
$api = new api("api/info", 1);

class api
{
    #接口文档数组
    public $api;
    #接口文档单个接口数组
    public $info;
    #接口文档预配置参数数组
    public $infoarr;
    #接口文档模块数组
    public $subset_api;
    #接口文档模块输入参数
    public $parameter;
    #接口文档模块输出参数
    public $fields;
    #输入参数
    public $param;
    #返回数据
    public $data;
    #接口req参数
    public $req;
    #模块名
    public $modulename;
    #模块类
    public $module;
    #debug模式
    public $debug;
    #日志id
    public $logid;
    public $db;

    function __construct($infodir, $debug = 0)
    {
        global $db;
        $this->db = $db;
        $this->infodir = $infodir;
        $this->debug = $debug;
        if ($this->debug == 1) {
            ini_set('display_errors', 1);
            error_reporting(1);
        }
        if (!empty($_REQUEST['req'])) {
            if ($this->debug == 1) {
                $this->logid = $this->db->insert('insterface_log', array('ip' => getip(), 'url' => $_SERVER['SCRIPT_NAME'], 'req' => $_REQUEST['req'], '#createtime' => 'now()', '#edittime' => 'now()'));
            }
            $this->req = explode('.', $_REQUEST['req']);
            $this->modulename = $this->req[0];
            unset($this->req[0]);
            $this->req = implode('.', $this->req);
            $this->infoarr = '';
            $this->module = includeclass($this, $this->modulename);
            $this->includefile($this->infodir . "/" . $this->modulename . '.php');
            if ($this->debug == 1) {
                $this->dataerror('请确认接口类型' . $this->modulename . '-' . $this->req);
            }
        } else {
            $this->api = array(
                'api_name' => PLATFORM . '接口文档',
            );
            if ($this->debug == 1) {
                $this->listdir();
            }
            $this->listapi();
        }
    }

    //遍历加载api文件;
    function listdir()
    {
        if (is_dir($this->infodir)) {
            if ($dh = opendir($this->infodir)) {
                while (($file = readdir($dh)) !== false) {
                    if (substr($file, -3) == 'php' && !is_dir($this->infodir . "/" . $file)) {
                        if (substr($file, -9, -4) != 'class') {
                            $this->classname = substr($file, 0, -4);
                            include $this->infodir . "/" . $file;
                            $this->api['api_list'][$this->classname] = $this->subset_api;
                        }
                    }
                }
                closedir($dh);
            } else {
                $this->dataerror('无法读取接口文件目录！');
            }
        } else {
            $this->dataerror('接口文件目录不正确！');
        }
    }

    //加载单个文件;
    function includefile($file)
    {
        require_once($file);
        if (file_exists($file)) {
            require_once($file);
        } else {
            if ($this->debug == 1) {
                $this->dataerror('文件路径:' . $file . '不存在');
            } else {
                $this->dataerror('文件缺失或不存在该接口');
            }
        }
    }

    //输出error数据
    function dataerror($error, $status = 1)
    {
        $this->data['error'] = $error;
        $this->data['status'] = $status;
        $this->echodata();
    }

    //输出data数据
    function echodata($data = '', $mode = 1)
    {
        if ($mode == 1) {
            if (!empty($data)) {
                $this->checkdates($this->fields, $data);
                foreach ($data as $key => $value) {
                    $this->data['data'][$key] = $value;
                }
            }
            if (!isset($this->data['error'])) {
                $this->data['error'] = '';
            }
            if (!isset($this->data['status'])) {
                $this->data['status'] = 1;
            }
            if (!empty($_GET['callback'])) {
                echo $_GET['callback'] . '(' . json_encode($this->data) . ')';
            } else {
                $this->data = json_encode($this->data);
            }
        } else {
            $this->data = $data;
        }
        echo $this->data;
        if ($this->debug == 1) {
            $this->db->update('insterface_log', array('returndata' => $this->data, '#edittime' => 'now()'), array('tbid' => $this->logid));
        }
        exit;
    }

    //校验输出数据是否完整
    function checkdates($fields, $data)
    {
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $this->checkfields($data, $key);
                if (!empty($data[$key])) {
                    if (is_array($data[$key])) {
                        foreach ($data[$key] as $datas) {
                            $this->checkdates($value, $datas);
                        }
                    } else {
                        $this->dataerror('返回参数:' . $key . '格式不正确');
                    }
                }
            } else {
                $this->checkfields($data, $value);
            }
        }
    }

    function checkfields($data, $field)
    {
        $field = $this->removehashtag($field);
        if (!is_array($data) || !array_key_exists($field, $data)) {
            if ($this->debug == 1) {
                $this->dataerror('返回参数:' . $field . '缺失');
            } else {
                $this->dataerror('返回参数缺失');
            }
        }
    }

    //接口预处理方法
    function apiinit()
    {
        //添加参数
        $this->addparam();
        //是否为查看文档模式
        $this->checkdoc();
        //获取传输数据
        $this->getparam();
        //检测参数是否完整
        $this->param = $this->checkparam($this->info['parameter']);
        //检测是否模拟数据
        $this->checksimulate();
        return $this->param;
    }

    //添加接口文档参数
    function addparam()
    {
        if ($this->parameter) {
            $this->info['parameter'] = $this->addparameter($this->parameter);
        }
        if ($this->fields) {
            $this->info['fields'] = $this->addfields($this->fields);
        }
    }

    //添加接口参数
    function addsubset()
    {
        foreach ($this->info as $key => $value) {
            $tempapi[$key] = $value;
        }
        $this->subset_api['kind'][] = $tempapi;
    }

    //添加请求参数和是否必填
    function addparameter($name, $ismust = 1)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                if (!is_numeric($key)) {
                    $parameter[$key] = $this->infoarr[$key];
                    $last = end($value);
                    if (is_numeric($last)) {
                        $parameter[$key]['ismust'] = $last;
                        unset($value[key($value)]);
                    } else {
                        $parameter[$key]['ismust'] = 1;
                    }
                    $parameter[$key]['data'] = $this->addparameter($value);
                } else {
                    if (is_array($value)) {
                        $key = key($value);
                        if (!is_numeric($key)) {
                            $parameter[$key] = $this->infoarr[$key];
                            $parameter[$key]['ismust'] = end($value);
                            $parameter[$key]['data'] = $this->addparameter(reset($value));
                        } else {
                            $parameter[$value[0]] = $this->addparameter($value[0], $value[1]);
                        }
                    } else {
                        $parameter[$value] = $this->addparameter($value);
                    }
                }
            }
            return $parameter;
        } else {
            $tempinfo = $this->infoarr[$name];
            $tempinfo['ismust'] = $ismust;
            return $tempinfo;
        }
    }

    //添加返回参数和示例值
    function addfields($fields)
    {
        if (is_array($fields)) {
            foreach ($fields as $key => &$value) {
                $value = $this->addfields($value);
                if (!is_numeric($key)) {
                    $this->addfields($key);
                    $value = array_merge($value, $this->infoarr[$key]);
                }
            }
            return $fields;
        } else {
            if (!empty($this->infoarr[$fields])) {
                $tempinfo = $this->infoarr[$fields];
                $tempinfo['name'] = $fields;
                return $tempinfo;
            } else {
                $this->dataerror("配置的输出参数" . $fields . "没有定义");
            }
        }
    }

    //根据method获得param
    function getparam()
    {
        if ($this->info['method'] == 'POST') {
            $input = file_get_contents('php://input');
            if (empty($input)) {
                $this->param = $_POST;
                $methed = 'POST';
            } else {
                $decodeparam = json_decode($input, 1);
                if (!empty($decodeparam)) {
                    $methed = 'JSON';
                    $_POST = $decodeparam;
                } else {
                    if (simplexml_load_string($input)) {
                        $methed = 'XML';
                        $this->param = $input;
                    } else {
                        parse_str($input, $decodeparam);
                        if (!empty($decodeparam)) {
                            $methed = 'IPOST';
                            $this->param = $decodeparam;
                        } else {
                            $methed = 'OTHER';
                            $this->param = $param;
                        }
                    }
                }
            }
        } else {
            $this->param = $_GET;
            $methed = 'GET';
        }
        if ($this->debug == 1) {
            $this->db->update('insterface_log', array('methed' => $methed, 'data' => json_encode($this->param), '#edittime' => 'now()'), array('tbid' => $this->logid));
        }
    }

    //校验必须数据是否完整
    function checkparam($parameter, $params = '', $file = '', $tipkey = '')
    {
        if (empty($params)) {
            $params = $this->param;
        }
        if (!empty($parameter)) {
            if (empty($file)) {
                $file = $_FILES;
            }
            foreach ($parameter as $key => $value) {
                if (is_array($value['data'])) {
                    if (!empty($params[$key])) {
                        foreach ($params[$key] as $tipkeys => &$sonparams) {
                            if (empty($tipkey)) {
                                $sonfile = $file[$key]['name'][$tipkeys];
                            } else {
                                $sonfile = $file[$key][$tipkeys];
                            }
                            if (empty($sonfile)) {
                                $sonfile = -1;
                            }
                            $sonparams = $this->checkparam($value['data'], $sonparams, $sonfile, $tipkeys);
                        }
                    }
                }
                if (!array_key_exists($key, $this->infoarr)) {
                    if ($this->debug == 1) {
                        $this->dataerror("参数" . $key . ",未定义");
                    } else {
                        $this->dataerror('参数未定义，请检查');
                    }
                }
                if ($this->infoarr[$key]['type'] == 'image') {
                    $param = $file[$this->removehashtag($key)];
                } else {
                    $param = $params[$this->removehashtag($key)];
                }
                if ($value['ismust'] == true && !isset($param)) {
                    $error[] = $this->removehashtag($key);
                }
                if (isset($param) && is_array($value['list'])) {
                    if (!array_key_exists($param, $value['list'])) {
                        if ($this->debug == 1) {
                            $listhtml = ' 可选值:';
                            foreach ($value['list'] as $listkey => $lists) {
                                $listhtml .= $listkey . '(' . $lists . '),';
                            }
                            $listhtml = substr($listhtml, 0, strlen($listhtml) - 1);
                            $this->dataerror("参数 " . $this->removehashtag($key) . " 数值不正确,请输入正确的值。" . $listhtml);
                        } else {
                            $this->dataerror('参数数值不正确，请检查');
                        }
                    }
                }
                if ($value['ismust'] == false && !isset($param)) {
                    $params[$this->removehashtag($key)] = $value['default'];
                }
            }
            if (!empty($error)) {
                if ($this->debug == 1) {
                    $tips = "缺少参数" . implode(',', $error);
                    if ($tipkey !== '') {
                        $tips = '数据组【' . $tipkey . '】' . $tips;
                    }
                    $this->dataerror($tips);
                } else {
                    $this->dataerror('缺少参数，请检查');
                }
            }
        }
        return $params;
    }

    //判断是否为当前接口
    function checkthisapi()
    {
        return $this->req == $this->info['req'];
    }

    //判断是否为查看文档
    function checkdoc()
    {
        if ($_GET['doc'] == true) {
            $this->listinfo();
        }
    }

    //判断是否为模拟数据
    function checksimulate()
    {
        if ($this->param['simulate'] == 1) {
            foreach ($this->info['fields'] as $key => $value) {
                $this->data[$key] = $this->simulate($value);
            }
            $this->echodata();
        }
    }

    //模拟数据
    function simulate($param)
    {
        switch ($param['type']) {
            case 'int':
                $simulate = rand();
                break;
            case 'string':
                $simulate = $this->getrandchar(rand(0, 10));
                break;
            case 'bool':
                $simulate = rand(0, 1);
                break;
        }
        return $simulate;
    }

    function getrandchar($length)
    {
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }

    //去除重名参数定义的#后面内容
    function removehashtag($k)
    {
        if (stripos($k, '#')) {
            $k = substr($k, 0, stripos($k, '#'));
        }
        return $k;
    }

    //输出整个api文档
    function listapi()
    {
        $html = '<!doctype html><html><head><meta charset="utf-8"><meta name="robots" content="nofollow"><title>' . $this->api['api_name'] . '</title><link rel="stylesheet" href="/api/main.css"></head>';
        $html .= '<body><div id="header"><h1>' . $this->api['api_name'] . '</h1></div>';
        $html .= '<div id="main"><ol id="nav">';
        if ($this->api['api_list']) {
            foreach ($this->api['api_list'] as $kind_k => $kind_v) {
                $html .= '<li><a href="#' . $kind_v['name'] . '">' . $kind_v['name'] . '</a></li>';
            }
            $html .= '</ol>';
            foreach ($this->api['api_list'] as $kind_k => $kind_v) {
                $html .= '<div id="' . $kind_v['name'] . '" class="card"><h2>' . $kind_v['name'] . '</h2><table><colgroup><col style="width:50px"><col style="width:300px"><col></colgroup>';
                if (!empty($kind_v['kind'])) {
                    foreach ($kind_v['kind'] as $api_k => $api_v) {
                        $html .= '<tr><td>' . ($api_k + 1) . '.</td><td><a href="doc/' . $kind_k . '/' . str_replace('.', '/', $api_v['req']) . '/" target="_blank">' . $kind_k . '/' . str_replace('.', '/', $api_v['req']) . '/' . '</a></td><td>' . $api_v['summary'] . '</td></tr>';
                    }
                }
                $html .= '</table></div>';
            }
        }
        $html .= '</div><div id="footer"><p>本接口由 <a href="http://1one.cn" target="_blank">浙江易网</a> 提供技术支持</p></div></body></html>';
        echo $html;
        exit;
    }

    //输出json格式示例返回
    function listjson($list)
    {
        foreach ($list as $k => $v) {
            if (is_numeric($k)) {
                $v = explode('#', $v);
                $v = $v[0];
                $list[$v] = '';
                unset($list[$k]);
            } else {
                $k = explode('#', $k);
                $k = $k[0];
                $list[$k] = $this->listjson($list[$k]);
            }
        }
        return $list;
    }

    //输出单个api文档
    function listinfo()
    {
        $html = '<!doctype html><html><head><meta charset="utf-8"><meta name="robots" content="nofollow"><title>' . $this->info['summary'] . '</title><link rel="stylesheet" href="/api/main.css"></head>';
        $html .= '<body><div id="header"><div class="back"><a href="/api' . '">返回</a></div><h1>' . $this->modulename . '/' . str_replace('.', '/', $this->info['req']) . '</h1><div id="summary">' . $this->info['summary'] . '</div></div>';
        $html .= '<div id="main"><div class="card"><h2>URL</h2><p><a href="' . '/api/' . $this->modulename . '/' . str_replace('.', '/', $this->info['req']) . '" target="_blank">' . SITEROOTURL . 'api/' . $this->modulename . '/' . str_replace('.', '/', $this->info['req']) . '</a></p></div><div class="card"><h2>HTTP请求方式</h2><p>' . $this->info['method'] . '</p></div>';
        if ($this->info['parameter']) {
            $html .= '<div class="card"><h2>请求参数</h2><table><thead><tr><th width="100">参数</th><th style="width:40px">必选</th><th style="width:60px">类型</th><th>说明</th></tr></thead><tbody>';
            $html .= $this->listparameters($this->info['parameter'], $level = 0);
            $html .= '</tbody></table></div>';
        }
        if ($this->info['fields']) {
            ksort($this->info['fields']);
            $html .= '<div class="card"><h2>返回参数</h2><table><thead><tr><th width="100">参数</th><th style="width:60px">类型</th><th>说明</th></tr></thead><tbody>';
            $html .= $this->listfields($this->info['fields']);
            $html .= '</tbody></table></div>';
        }

        $this->fields = $this->removejsontag($this->fields);

        $html .= '<div class="card"><h2>json示例</h2><div>';
        $html .= '<pre>' . json_encode($this->listjson($this->fields), JSON_PRETTY_PRINT) . '</pre>';
        $html .= '</div></div>';
        $html .= '<div class="card"><h2>测试字段</h2><div>';
        $dataurl = '';
        $dataurl .= 'http://{{host}}/api/' . $this->modulename . '/' . str_replace('.', '/', $this->info['req']);
        if ($this->info['parameter']) {
            if ($this->info['method'] == 'GET') {
                $i = 0;
                foreach ($this->info['parameter'] as $k => $value) {
                    $k = $this->removehashtag($k);
                    if ($i == 0) {
                        $dataurl .= "?";
                    } else {
                        $dataurl .= "&amp;";
                    }
                    $dataurl .= $k . '=';
                    $i++;
                }
            } else {
                $dataurl .= '<br>' . $this->listposttest($this->info['parameter']);
            }
        }
        $html .= $dataurl;
        $html .= '</div></div>';
        $html .= '</div><div id="footer"><p>本接口由 <a href="http://1one.cn" target="_blank">浙江易网</a> 提供技术支持</p></div></body></html>';
        echo $html;
        exit;
    }

    /**
     * 清除json返回事例取出 #(包括#)后面的 字符
     * @param $arr
     * @return array|bool|string
     */
    function removejsontag($arr)
    {
        $remove_tag_arr = [];
        if (empty($arr)) {
            return $remove_tag_arr;
        } else {

            if (is_array($arr)) {
                foreach ($arr as $akey => $aval) {
                    if (is_array($aval)) {
                        $remove_tag_arr[$this->removehashtag($akey)] = $this->removejsontag($aval);
                    } else {
                        $remove_tag_arr[$akey] = $this->removejsontag($aval);
                    }

                }
            } else {
                $remove_tag_arr = $this->removehashtag($arr);
            }
        }

        return $remove_tag_arr;
    }

    //输出post的test参数
    function listposttest($parameter, $key = '')
    {
        $dataurl = '';
        foreach ($parameter as $k => $value) {
            $k = $this->removehashtag($k);
            if (is_array($value['data'])) {
                $dataurl .= $this->listposttest($value['data'], $k . '[0]');
            } else {
                if (!empty($key)) {
                    $dataurl .= $key . '[' . $k . ']:<br>';
                } else {
                    $dataurl .= $k . ':<br>';
                }
            }

        }
        return $dataurl;
    }

    //输出请求参数
    function listfields($fields, $level = 0)
    {
        $html = '';
//        echo "<pre>";
//        print_r($fields);die;
        foreach ($fields as $k => $v) {
            if (is_array($v)) {
                $html .= '<tr><td>';
                if ($level != 0) {
                    $html .= str_pad('', 24 * $level, "&nbsp;") . '└&nbsp;';
                }
                if (is_numeric($k)) {
                    $v['name'] = $this->removehashtag($v['name']);
                    $html .= $v['name'] . '';
                } else {
                    $html .= $this->removehashtag($k);
                }
                $html .= '</td><td class="tac">' . $v['type'] . '</td><td>' . $v['summary'];
                if (isset($v['list'])) {
                    $listhtml = ' 可选值:';
                    foreach ($v['list'] as $listkey => $lists) {
                        $listhtml .= $listkey . '(' . $lists . '),';
                    }
                    $html .= substr($listhtml, 0, strlen($listhtml) - 1);
                }
                $html .= '</td></tr>';
                if (!is_numeric($k)) {
                    $levels = $level + 1;
                    $html .= $this->listfields($v, $levels);
                }
            }
        }
        return $html;
    }

    //输出返回参数
    function listparameters($parameters, $level = 0)
    {
        $html = '';
        if ($level == 0) {
            ksort($parameters);
        }
        foreach ($parameters as $k => $v) {
            if (is_array($v)) {
                $k = $this->removehashtag($k);
                $html .= '<tr><td>';
                if ($level != 0) {
                    $html .= str_pad('', 24 * $level, "&nbsp;") . '└&nbsp;';
                }
                $html .= $k . '</td><td class="tac">' . ($v['ismust'] ? '√' : '×') . '</td><td class="tac">' . $v['type'] . '</td><td>' . $v['summary'];
                if (isset($v['list'])) {
                    $listhtml = ' 可选值:';
                    foreach ($v['list'] as $listkey => $lists) {
                        $listhtml .= $listkey . '(' . $lists . '),';
                    }
                    $html .= substr($listhtml, 0, strlen($listhtml) - 1);
                }
                if (isset($v['default'])) {
                    $html .= ' 默认值:' . $v['default'];
                }
                $html .= '</td></tr>';
                if (is_array($v['data'])) {
                    $levels = $level + 1;
                    $html .= $this->listparameters($v['data'], $levels);
                }
            }
        }
        return $html;
    }
}

?>