<?php

use \inc\Authorize;

require('../global.php');

Authorize::checkAdminLoginAndJump('admin_id');

$admin = $db->get('tb_admin_member', '*', array('tbid' => session('admin_id')));
$avatar = file_exists('uploads/admin/' . session('admin_id') . '/avatar.jpg') ? SITEROOTURL . 'uploads/admin/' . session('admin_id') . '/avatar.jpg' : 'img/avatar.jpg';
?>
<!DOCTYPE html>
<html>
<head>
    <?php require('admin/_head.php'); ?>
    <title><?php echo getconfig('title'); ?>管理后台</title>
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="img-circle" src="<?php echo $avatar; ?>" width="64"/></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs"><strong
                                                class="font-bold"><?php echo $admin['username']; ?></strong></span>
                                    <span class="text-muted text-xs block">
                                        管理员
                                        <b class="caret"></b>
                                    </span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem" href="module/personal/avatar.php">修改头像</a></li>
                            <li><a class="J_menuItem" href="module/personal/info.php">个人资料</a></li>
                            <li><a class="J_menuItem" href="module/personal/password.php">修改密码</a></li>
                            <li class="divider"></li>
                            <li><a href="login.ajax.php?ac=logout">安全退出</a></li>
                        </ul>
                    </div>
                    <div class="logo-element"><img alt="image" class="img-circle" src="<?php echo $avatar; ?>"
                                                   width="32"/></div>
                </li>

                <li>
                    <a class="J_menuItem" href="main.php">
                        <i class="fa fa-fw fa-home"></i>
                        <span class="nav-label">后台首页</span>
                    </a>
                </li>
                <?php if (Authorize::checkAdminAuthorize('banner.category_browse') || Authorize::checkAdminAuthorize('banner.image_browse')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/banner/category.list.php">
                             <i class="fa fa-fw fa-picture-o"></i>
                            <span class="nav-label">栏目管理</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('goodsCategory.browse')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/goodsCategory/index.php">
                            <i class="fa fa-fw fa-shopping-cart"></i>
                            <span class="nav-label">产品中心信息管理</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('news.browse')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/news/category.list.php">
                            <i class="fa fa-fw fa-exchange"></i>
                            <span class="nav-label">新闻中心信息管理</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('services_support.services_browse') || Authorize::checkAdminAuthorize('services_support.support_browse')) { ?>
                    <li>
                        <a class="J_menuItem">
                            <i class="fa fa-fw fa-picture-o"></i>
                            <span class="nav-label">资料下载</span>
                            <span class="fa arrow"></span>
                            <ul class="nav nav-second-level">
                                <?php if (Authorize::checkAdminAuthorize('services_support.services_browse')) { ?>
                                    <li>
                                        <a class="J_menuItem" href="module/services_support/services.list.php">说明书</a>
                                    </li>
                                <?php } ?>
                                <?php if (Authorize::checkAdminAuthorize('services_support.support_browse')) { ?>
                                    <li>
                                        <a class="J_menuItem" href="module/services_support/support.list.php">宣传资料</a>
                                    </li>
                                <?php } ?>
                                <?php if (Authorize::checkAdminAuthorize('services_support.support_browse')) { ?>
                                    <li>
                                        <a class="J_menuItem" href="module/services_support/other.list.php">其他</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('commonProblem.browse')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/commonProblem/index.php">
                            <i class="fa fa-fw fa-exchange"></i>
                            <span class="nav-label">常见问题管理(仅微信)</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('precaution.edit')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/precaution/category.list.php">
                            <i class="fa fa-fw fa-bell"></i>
                            <span class="nav-label">注意事项管理(仅微信)</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('mannual.browse')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/mannual/category.list.php">
                            <i class="fa fa-fw fa-exchange"></i>
                            <span class="nav-label">培训资料管理(仅微信)</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('connectUs.edit')) { ?>
                   
                    <li>
                        <a class="J_menuItem" href="module/connectUsItem/category.list.php">
                             <i class="fa fa-fw fa-asterisk"></i>
                            <span class="nav-label">营销网络信息管理</span>
                        </a>
                    </li>
                            
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('aboutUs.edit')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/aboutUsItem/index.php">
                            <i class="fa fa-fw fa-asterisk"></i>
                            <span class="nav-label">关于我们内容管理</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if (Authorize::checkAdminAuthorize('aboutUs.edit')) { ?>
                    <li>
                        <a class="J_menuItem" href="module/aboutUsItem/partners.list.php">
                            <i class="fa fa-fw fa-comments-o"></i>
                            <span class="nav-label">合作伙伴管理</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (Authorize::checkAdminAuthorize('department.browse') || Authorize::checkAdminAuthorize('admin.browse')) { ?>
                    <li>
                        <a class="J_menuItem">
                            <i class="fa fa-fw fa-sitemap"></i>
                            <span class="nav-label">系统设置</span>
                            <span class="fa arrow"></span>
                            <ul class="nav nav-second-level">
                                <?php if (Authorize::checkAdminAuthorize('department.browse')) { ?>
                                    <li>
                                        <a class="J_menuItem" href="module/admin/department.list.php">
                                            <i></i>
                                            <span class="nav-label">部门和职位管理</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (Authorize::checkAdminAuthorize('admin.browse')) { ?>
                                    <li>
                                        <a class="J_menuItem" href="module/admin/member.list.php">
                                            <i></i>
                                            <span class="nav-label">员工管理</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="module/website/log.list.php">
                                        <i></i> <span class="nav-label">操作日志</span></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </a>
                    </li>
                <?php } ?>

                <li>  <!-- 添加的空白栏,避免多余的最后一栏被遮盖 -->
                    <a class="J_menuItem">
                        <i></i>
                        <span class="nav-label"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> 主题
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="main.php">后台首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="login.ajax.php?ac=logout" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i>
                退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="main.php" frameborder="0"
                    data-id="main.php" seamless></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">&copy; 2019-2020 <a href="http://1one.cn/" target="_blank">浙江易网科技股份有限公司</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">
            <div class="sidebar-title">
                <h3><i class="fa fa-comments-o"></i> 主题设置</h3>
                <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
            </div>
            <div class="skin-setttings">
                <div class="title">主题设置</div>
                <div class="setings-item">
                    <span>收起左侧菜单</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                            <label class="onoffswitch-label" for="collapsemenu">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="setings-item">
                    <span>固定顶部</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                            <label class="onoffswitch-label" for="fixednavbar">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="setings-item">
                    <span>固定宽度</span>
                    <div class="switch">
                        <div class="onoffswitch">
                            <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                            <label class="onoffswitch-label" for="boxedlayout">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="title">皮肤选择</div>
                <div class="setings-item default-skin nb">
                        <span class="skin-name ">
                             <a href="#" class="s-skin-0">
                                 默认皮肤
                             </a>
                        </span>
                </div>
                <div class="setings-item blue-skin nb">
                        <span class="skin-name ">
                            <a href="#" class="s-skin-1">
                                蓝色主题
                            </a>
                        </span>
                </div>
                <div class="setings-item yellow-skin nb">
                        <span class="skin-name ">
                            <a href="#" class="s-skin-3">
                                黄色/紫色主题
                            </a>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <!--右侧边栏结束-->
</div>
<?php require('admin/_js.php'); ?>
<!-- 自定义js -->
<script src="js/hplus.js?v=4.0.0"></script>
<script src="js/contabs.js"></script>
<!-- 第三方插件 -->
<script src="js/plugins/pace/pace.min.js"></script>
</body>
</html>
