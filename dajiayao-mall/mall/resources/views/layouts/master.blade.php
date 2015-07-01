<!DOCTYPE html>
<head>

    <!-- Title -->
    <title>@yield('title') - 丫摇直销中心</title>

    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta charset="UTF-8">
    <meta name="description" content="Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="dajiayao" />
    <meta name="renderer" content="webkit">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href='http://fonts.useso.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
    <link href="/themeforest/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
    <link href="/themeforest/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
    <link href="/themeforest/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/metrojs/MetroJs.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>

    <!-- Theme Styles -->
    <link href="/themeforest/css/modern.min.css" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/css/themes/purple.css" class="theme-color" rel="stylesheet" type="text/css"/>
    <link href="/themeforest/css/custom.css" rel="stylesheet" type="text/css"/>

    <script src="/themeforest/plugins/3d-bold-navigation/js/modernizr.js"></script>
    <script src="/themeforest/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
    <style type="text/css">
        .fa{
            font-size: 18px;
            margin-left: 5px;
        }
    </style>
</head>
<body class="page-header-fixed pace-done">
<div class="overlay"></div>


<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s1">
    <h3>
        <span class="pull-left">Chat</span>
        <a href="javascript:void(0);" class="pull-right" id="closeRight">
            <i class="fa fa-times"></i>
        </a>
    </h3>
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
        <div class="slimscroll" style="overflow: hidden; width: auto; height: 100%;">
            <a href="javascript:void(0);" class="showRight2">
                <img src="/themeforest/images/avatar.png" alt="">
                <span>Nick Doe<small>Hi! How're you?</small></span>
            </a>
        </div>
        <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.3; display: none; border-radius: 0px; z-index: 99; right: 0px; height: 978px; background: rgb(204, 204, 204);"></div>
        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; opacity: 0.2; z-index: 90; right: 0px; background: rgb(51, 51, 51);"></div>
    </div>
</nav>
<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
    <h3>
        <span class="pull-left">Sandra Smith</span>
        <a href="javascript:void(0);" class="pull-right" id="closeRight2">
            <i class="fa fa-angle-right"></i>
        </a>
    </h3>
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
        <div class="slimscroll chat" style="overflow: hidden; width: auto; height: 100%;">
            <div class="chat-item chat-item-left">
                <div class="chat-image">
                    <img src="/themeforest/images/avatar.png" alt="">
                </div>
                <div class="chat-message">
                    Hi There!
                </div>
            </div>
            <div class="chat-item chat-item-right">
                <div class="chat-message">
                    Hi! How are you?
                </div>
            </div>
        </div>
        <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.3; display: none; border-radius: 0px; z-index: 99; right: 0px; height: 978px; background: rgb(204, 204, 204);"></div>
        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; opacity: 0.2; z-index: 90; right: 0px; background: rgb(51, 51, 51);"></div>
    </div>
    <div class="chat-write">
        <form class="form-horizontal" action="javascript:void(0);">
            <input type="text" class="form-control" placeholder="Say something">
        </form>
    </div>
</nav>


<div class="menu-wrap">
    <nav class="profile-menu">
        <div class="profile"><img src="/themeforest/images/avatar1.png" width="52px" alt="David Green"/><span>David Green</span></div>
        <div class="profile-menu-list">
            <a href="#"><i class="fa fa-star"></i><span>Favorites</span></a>
            <a href="#"><i class="fa fa-bell"></i><span>Alerts</span></a>
            <a href="#"><i class="fa fa-envelope"></i><span>Messages</span></a>
            <a href="#"><i class="fa fa-comment"></i><span>Comments</span></a>
        </div>
    </nav>
    <button class="close-button" id="close-button">Close Menu</button>
</div>
<form class="search-form" action="#" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
            <span class="input-group-btn">
                <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button"><i class="fa fa-times"></i></button>
            </span>
    </div><!-- Input Group -->
</form><!-- Search Form -->
<main class="page-content content-wrap">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="sidebar-pusher">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="logo-box">
                <a href="{{route('adminIndex')}}" class="logo-text"><span>丫摇直销中心</span></a>
            </div><!-- Logo Box -->
            <div class="search-button">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
            </div>
            <div class="topmenu-outer">
                <div class="top-menu">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                        </li>
                        {{--<li>--}}
                        {{--<a href="#cd-nav" class="waves-effect waves-button waves-classic cd-nav-trigger"><i class="fa fa-diamond"></i></a>--}}
                        {{--</li>--}}
                        <li>
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic toggle-fullscreen"><i class="fa fa-expand"></i></a>
                        </li>
                        {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-cogs"></i>--}}
                        {{--</a>--}}
                        {{--<ul class="dropdown-menu dropdown-md dropdown-list theme-settings" role="menu">--}}
                        {{--<li class="li-group">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Fixed Header--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right fixed-header-check" checked>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="li-group">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Fixed Sidebar--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right fixed-sidebar-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Horizontal bar--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right horizontal-bar-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Toggle Sidebar--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right toggle-sidebar-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Compact Menu--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right compact-menu-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Hover Menu--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right hover-menu-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="li-group">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Boxed Layout--}}
                        {{--<div class="ios-switch pull-right switch-md">--}}
                        {{--<input type="checkbox" class="js-switch pull-right boxed-layout-check">--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="li-group">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li class="no-link" role="presentation">--}}
                        {{--Choose Theme Color--}}
                        {{--<div class="color-switcher">--}}
                        {{--<a class="colorbox color-blue" href="?theme=blue" title="Blue Theme" data-css="blue"></a>--}}
                        {{--<a class="colorbox color-green" href="?theme=green" title="Green Theme" data-css="green"></a>--}}
                        {{--<a class="colorbox color-red" href="?theme=red" title="Red Theme" data-css="red"></a>--}}
                        {{--<a class="colorbox color-white" href="?theme=white" title="White Theme" data-css="white"></a>--}}
                        {{--<a class="colorbox color-purple" href="?theme=purple" title="purple Theme" data-css="purple"></a>--}}
                        {{--<a class="colorbox color-dark" href="?theme=dark" title="Dark Theme" data-css="dark"></a>--}}
                        {{--</div>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="no-link"><button class="btn btn-default reset-options">Reset Options</button></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        {{--<li>--}}
                        {{--<a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>--}}
                        {{--</li>--}}
                        {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown"><i class="fa fa-envelope"></i><span class="badge badge-success pull-right">4</span></a>--}}
                        {{--<ul class="dropdown-menu title-caret dropdown-lg" role="menu">--}}
                        {{--<li><p class="drop-title">You have 4 new  messages !</p></li>--}}
                        {{--<li class="dropdown-menu-list slimscroll messages">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<div class="msg-img"><div class="online on"></div><img class="img-circle" src="/themeforest/images/avatar2.png" alt=""></div>--}}
                        {{--<p class="msg-name">Sandra Smith</p>--}}
                        {{--<p class="msg-text">Hey ! I'm working on your project</p>--}}
                        {{--<p class="msg-time">3 minutes ago</p>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="drop-all"><a href="#" class="text-center">All Messages</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown"><i class="fa fa-bell"></i><span class="badge badge-success pull-right">3</span></a>--}}
                        {{--<ul class="dropdown-menu title-caret dropdown-lg" role="menu">--}}
                        {{--<li><p class="drop-title">You have 3 pending tasks !</p></li>--}}
                        {{--<li class="dropdown-menu-list slimscroll tasks">--}}
                        {{--<ul class="list-unstyled">--}}
                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<div class="task-icon badge badge-success"><i class="icon-user"></i></div>--}}
                        {{--<span class="badge badge-roundless badge-default pull-right">1min ago</span>--}}
                        {{--<p class="task-details">New user registered.</p>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<div class="task-icon badge badge-danger"><i class="icon-energy"></i></div>--}}
                        {{--<span class="badge badge-roundless badge-default pull-right">24min ago</span>--}}
                        {{--<p class="task-details">Database error.</p>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="#">--}}
                        {{--<div class="task-icon badge badge-info"><i class="icon-heart"></i></div>--}}
                        {{--<span class="badge badge-roundless badge-default pull-right">1h ago</span>--}}
                        {{--<p class="task-details">Reached 24k likes</p>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="drop-all"><a href="#" class="text-center">All Tasks</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                <span class="user-name">{{\Auth::user()->username}}<i class="fa fa-angle-down"></i></span>
                                <img class="img-circle avatar" src="/themeforest/images/avatar1.png" width="40" height="40" alt="">
                            </a>
                            <ul class="dropdown-menu dropdown-list" role="menu">
                                <li role="presentation"><a href="##"><i class="fa fa-user"></i>修改密码</a></li>
                                {{--<li role="presentation"><a href="#"><i class="fa fa-calendar"></i>Calendar</a></li>--}}
                                {{--<li role="presentation"><a href="#"><i class="fa fa-envelope"></i>Inbox<span class="badge badge-success pull-right">4</span></a></li>--}}
                                {{--<li role="presentation" class="divider"></li>--}}
                                {{--<li role="presentation"><a href="#"><i class="fa fa-lock"></i>Lock screen</a></li>--}}
                                {{--<li role="presentation"><a href="/logout"><i class="fa fa-sign-out m-r-xs"></i>Log out</a></li>--}}
                            </ul>
                        </li>
                        <li>
                            <a href="{{route('logout')}}" class="log-out waves-effect waves-button waves-classic">
                                <span><i class="fa fa-sign-out m-r-xs"></i>Log out</span>
                            </a>
                        </li>
                        <li style="display:none;">
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic" id="showRight">
                                <i class="fa fa-comments"></i>
                            </a>
                        </li>
                    </ul><!-- Nav -->
                </div><!-- Top Menu -->
            </div>
        </div>
    </div><!-- Navbar -->
    <div class="page-sidebar sidebar">
        <div class="page-sidebar-inner slimscroll">
            {{--<div class="sidebar-header">--}}
            {{--<div class="sidebar-profile">--}}
            {{--<a href="javascript:void(0);" id="profile-menu-link">--}}
            {{--<div class="sidebar-profile-image">--}}
            {{--<img src="/themeforest/images/avatar1.png" class="img-circle img-responsive" alt="">--}}
            {{--</div>--}}
            {{--<div class="sidebar-profile-details">--}}
            {{--<span>David Green<br><small>Keyboard Operator</small></span>--}}
            {{--</div>--}}
            {{--</a>--}}
            {{--</div>--}}
            {{--</div>--}}
            <ul class="menu accordion-menu">
                <li class="active">
                    <a href="{{route('adminIndex')}}" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-home"></span>
                        <p>主页</p>
                    </a>
                </li>
                <li class="droplink open">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-barcode"></span>
                        <p>商品</p>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li><a href="{{route('adminItems')}}">单品管理</a></li>
                    </ul>
                </li>
                <li class="droplink open">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-list-alt"></span>
                        <p>订单</p>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li><a href="{{route('adminOrders')}}">订单管理</a></li>
                    </ul>
                </li>
                <li class="droplink open">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-credit-card"></span>
                        <p>佣金</p>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li><a href="{{route('applyList')}}">佣金申请处理</a></li>
                    </ul>
                </li>
                <li class="droplink open">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-hdd"></span>
                        <p>小店</p>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li><a href="{{route('sellers')}}">店主管理</a></li>
                        <li><a href="{{route('shops')}}">小店管理</a></li>
                        <li><a href="{{route('adminCommission')}}">店主佣金</a></li>
                    </ul>
                </li>


                @if(\Illuminate\Support\Facades\Auth::user()->role==\Dajiayao\User::ROLE_ADMIN)
                    <li><a href="{{route('users')}}" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-user"></span><p>平台用户</p></a>
                    </li>
                @endif

                <li class="droplink open">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-cog"></span>
                        <p>设置</p>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li><a href="{{route('expresses')}}">物流公司设置</a></li>
                        <li><a href="{{route('paymentTypes')}}">支付方式设置</a></li>
                        <li><a href="{{route('settings')}}">全局交易设置</a></li>
                    </ul>
                </li>



                {{--<li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-cloud"></span><p>应用</p><span class="arrow"></span></a>--}}
                    {{--<ul class="sub-menu">--}}
                        {{--<li><a href="##">微信设备ID</a></li>--}}
                        {{--<li><a href="##">摇一摇页面</a></li>--}}
                        {{--<li><a href="##">接入的应用</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            </ul>
        </div><!-- Page Sidebar Inner -->
    </div><!-- Page Sidebar -->
    <div class="page-inner">
        @yield('page-title')
        <div id="main-wrapper">
            @yield('content')
        </div><!-- Main Wrapper -->
        <div class="page-footer">
            <p class="no-s">2015 © Modern by Steelcoders.</p>
        </div>
    </div><!-- Page Inner -->
</main><!-- Page Content -->

<nav class="cd-nav-container" id="cd-nav">
    <header>
        <h3>Navigation</h3>
        <a href="#" class="cd-close-nav">Close</a>
    </header>
    <ul class="cd-nav list-unstyled">
        <li class="cd-selected" data-menu="index">
            <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-home"></i>
                        </span>
                <p>Dashboard</p>
            </a>
        </li>
        <li data-menu="profile">
            <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-user"></i>
                        </span>
                <p>Profile</p>
            </a>
        </li>
        <li data-menu="inbox">
            <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-envelope"></i>
                        </span>
                <p>Mailbox</p>
            </a>
        </li>
        <li data-menu="#">
            <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-tasks"></i>
                        </span>
                <p>Tasks</p>
            </a>
        </li>
        <li data-menu="#">
            <a href="javsacript:void(0);">
                        <span>
                            <i class="glyphicon glyphicon-cog"></i>
                        </span>
                <p>Settings</p>
            </a>
        </li>
    </ul>
</nav>
<div class="cd-overlay"></div>

<!-- Javascripts -->
<script src="/themeforest/plugins/jquery/jquery-2.1.3.min.js"></script>
<script src="/themeforest/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/themeforest/plugins/pace-master/pace.min.js"></script>
<script src="/themeforest/plugins/jquery-blockui/jquery.blockui.js"></script>
<script src="/themeforest/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/themeforest/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/themeforest/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/themeforest/plugins/switchery/switchery.min.js"></script>
<script src="/themeforest/plugins/uniform/jquery.uniform.min.js"></script>
<script src="/themeforest/plugins/offcanvasmenueffects/js/classie.js"></script>
{{--<script src="/themeforest/plugins/offcanvasmenueffects/js/main.js"></script>--}}
<script src="/themeforest/plugins/waves/waves.min.js"></script>
<script src="/themeforest/plugins/3d-bold-navigation/js/main.js"></script>
<script src="/themeforest/plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="/themeforest/plugins/jquery-counterup/jquery.counterup.min.js"></script>
<script src="/themeforest/plugins/toastr/toastr.min.js"></script>
<script src="/themeforest/js/modern.min.js"></script>
<script src="/themeforest/js/custom.js"></script>
@yield('js')

</body>
</html>