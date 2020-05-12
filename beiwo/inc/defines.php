<?php
//管理后台权限
$g_admin_authorize = array(
    'admin' => array(
        '_title' => '员工管理',
        'browse' => '浏览',
        'edit' => '编辑'
    ),
    'department' => array(
        '_title' => '部门管理',
        'browse' => '浏览',
        'edit' => '编辑'
    ),
    'banner' => array(
        '_title' => 'BANNER管理',
        'category_browse' => '分类管理[浏览]',
        'category_edit' => '分类管理[编辑]',
        'image_browse' => '图片管理[浏览]',
        'image_edit' => '图片管理[编辑]',
    ),
    'goodsCategory' => array(
        '_title' => '产品分类管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'goods' => array(
        '_title' => '产品管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'commonProblem' => array(
        '_title' => '常见问题管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'mannual' => array(
        '_title' => '说明书管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'news' => array(
        '_title' => '新闻管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'services_support' => array(
        '_title' => '服务与支持',
        'services_browse' => '合同服务[浏览]',
        'services_edit' => '合同服务[编辑]',
        'support_browse' => '技术资料[浏览]',
        'support_edit' => '技术资料[编辑]',
    ),
    'precaution' => array(
        '_title' => '注意事项管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'connectUs' => array(
        '_title' => '联系我们管理',
        'browse' => '浏览',
        'edit' => '编辑',

    ),
    'aboutUs' => array(
        '_title' => '关于我们管理',
        'edit' => '编辑',
        'team.browse' => '倍沃团队[浏览]',
        'team.edit' => '倍沃团队[编辑]',
        'partners.browse' => '合作伙伴[浏览]',
        'partners.edit' => '合作伙伴[编辑]',
        'achievement.browse' => '倍沃成就[浏览]',
        'achievement.edit' => '倍沃成就[编辑]',
        'course.browse' => '倍沃历程[浏览]',
        'course.edit' => '倍沃历程[编辑]',
    ),
    'website' => array(
        '_title' => '操作日志管理',
        'log' => '查看',
    ),
);

$g_supplier_authorize = array(
    'delivery' => array(
        '_title' => '运费模板',
        'browse' => '浏览',
        'edit' => '编辑'
    ),
    'goods' => array(
        '_title' => '商品管理',
        'browse' => '浏览',
        'edit' => '编辑'
    ),
    'order' => array(
        '_title' => '订单管理',
        'browse' => '浏览',
        'edit' => '编辑'
    ),
    'settlement' => array(
        '_title' => '结算管理',
        'browse' => '浏览',
    ),
    'brand' => array(
        '_title' => '品牌管理',
        'browse' => '浏览',

    ),
    'financial' => array(
        '_title' => '财务报表管理',
        'browse' => '浏览',

    ),
);
//快递公司
$g_express_company = array(
    0 => '商家配送',
    1 => 'EMS',
    2 => '圆通快递',
    3 => '韵达快递',
    4 => '顺丰速递',
    5 => '申通快递',
    6 => '全峰快递',
    7 => '中通快递',
    8 => '天天快递',
    9 => '百世汇通',
    10 => '德邦快递',
);
const MALE = 1;
const FEMALE = 2;
const UNKNOWNSEX = 3;

const PROPRIETOR = 1;//业主
const DECORATION_COMPANY = 2;//装修公司
const MANAGER = 3;//装修经理
const DESIGNER = 4;//设计师
const SUPPLIER = 5;//材料商
const INSPECTOR = 6;//巡视员

const WAITING_FOR_REVIEW = 1;//等待审核
const FAILED_FOR_REVIEW = 2;//审核失败
const SUCCESS_FOR_REVIEW = 3;//审核成功

const WECHAT = 2;//微信
const ALIPAY = 3;//支付宝
const BALANCE = 1;//余额
const OFFLINE = 4;//线下

$globalPayment = array(

    2 => '微信',
    3 => '支付宝',
    4 => '线下',
);

const EMS = 1;//ems
const YUANTONG = 2;//圆通
const YUNDA = 3;//韵达
const SHUNFENG = 4;//顺丰
const SHENTONG = 5;//申通
const QUANFENG = 6;//全峰
const ZHONGTONG = 7;//中通
const TIANTIAN = 8;//天天
const BAISHIHUITONG = 9;//百世汇通
const DEBAN = 10;//德邦
$globalExpressCompany = array(
    1 => 'EMS',
    2 => '圆通快递',
    3 => '韵达快递',
    4 => '顺丰速递',
    5 => '申通快递',
    6 => '全峰快递',
    7 => '中通快递',
    8 => '天天快递',
    9 => '百世汇通',
    10 => '德邦快递'
);

/* 
0订单关闭 1等待买家付款 2等待卖家发货 3已发货 4订单完成
 */
const ORDER_CLOSE = 0;
const WAITING_FOR_PAYMENT = 1;
const WAITING_FOR_DELIVERY = 2;
const ORDER_DELIVERIED = 3;
const ORDER_FINISH = 4;
const ORDER_CUSTOMER_SERVER = 5;

$globalStatus = array(
    0 => '订单关闭',
    1 => '等待买家付款',
    2 => '等待卖家发货',
    3 => '已发货',
    4 => '订单完成',
    5 => '订单售后或换货',
);

const HIGH_OPINION = 1;//好评
const MIDDLE_OPINION = 2;//中评
const LOW_OPINION = 3;//差评


const WAITING_FOR_SELLER_CHECK = 0;
const WAITING_FOR_BUYER_RETURN = 1;
const SELLER_REFUSE = 2;//WAITING_FOR_BUYER_CHANGE
const WAITING_FOR_CONFIRM = 3;
const CUSTOMER_SERVICE_CANCEL = 4;
const REFUND_SUCCESS = 5;
const BUYER_CANCER_RETURN = 6;

$globalCustomerServiceStatus = array(
    0 => '等待卖家审核',
    1 => '等待买家退回商品',
    2 => '卖家拒绝',
    3 => '等待买家确认收货',
    4 => '售后已撤销',
    5 => '退款成功',
    6 => '卖家发货撤销申请',
);

const REFUND = 1;//退货
const REFUND_AND_RETURN_GOODS = 2;//退款退货

const OWNER_TERMINALS = 1;//业主端
const SUPPLIER_TERMINALS = 2;//材料商端
const DECORATION_TERMINALS = 3;//装修公司端
$globalBannerType = array(
    1 => '业主端',
    2 => '材料商端',
    3 => '装修公司端',
);



