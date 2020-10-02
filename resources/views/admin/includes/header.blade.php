<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <!-- Meta, title, CSS, favicons, etc. -->
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield("title")</title>
    <link rel="icon" href="{{url('public/adminAssets')}}/images/fav.png" type="image/x-icon">
    <!-- Bootstrap -->
    <link href="{{url('public/adminAssets')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('public/adminAssets')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{url('public/adminAssets')}}/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{url('public/adminAssets')}}/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{url('public/adminAssets')}}/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{url('public/adminAssets')}}/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="{{url('public/adminAssets')}}/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{url('public/adminAssets')}}/build/css/custom.min.css" rel="stylesheet">
    <script src="{{url('public/adminAssets')}}/vendors/jquery/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>

    <link href="{{url('public/adminAssets')}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('public/adminAssets')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('public/adminAssets')}}/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('public/adminAssets')}}/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{url('public/adminAssets')}}/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js" integrity="sha256-vb+6VObiUIaoRuSusdLRWtXs/ewuz62LgVXg2f1ZXGo=" crossorigin="anonymous"></script>

    <style type="text/css">
        .error {
            color:red;
        }
        .form-control {
            color:black !important;
        }
        .page-search {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
            width: 100%;
        }
        .page-search .give-away {
            width: 150px;
        }
        .page-search .date-search {
            display: flex;
            flex: 1;
            align-items: center;
            flex-wrap: wrap;
            max-width: 420px;
        }
        .page-search .date-search label {
            width: 120px;
            margin: 0;
        }
        .page-search .date-search input {
            flex: 1;
            min-width: 300px;
        }
    </style>
</head>

<body class="nav-md footer_fixed">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col menu_fixed">
                <div class="left_col scroll-view">
                     <div class="navbar nav_title" style="border: 0;">
                        <a href="{{url('admin/index')}}" class="site_title logo"><img src="{{url('public/adminAssets')}}/images/logo.png" alt="logo"></a>
                        <a href="{{url('admin/index')}}" class="site_title sm"><img src="{{url('public/adminAssets')}}/images/logo.png" alt="logo"></a>
                    </div>

                    <div class="clearfix"></div>
                    <div class="new-heading">
                        <h2>ADMIN</h2>
                    </div>

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li class="<?php if(Request::segment(2) == '/' || Request::segment(2) == 'index') { echo 'active'; }?>"><a href="{{url('admin/index')}}"><i class="fa fa-home"></i>Dashboard</a></li>
                                <li class="<?php if(Request::segment(2) == 'userManagement' || Request::segment(2) == 'view_user' || Request::segment(2) == 'edit-user') { echo 'active'; }?>"">

                                    <a href="{{url('admin/userManagement')}}"><i class="fa fa-user"></i>User Management </a></li>


                                    <li class="<?php if(Request::segment(2) == 'user-coins' || Request::segment(2) == 'add-coins') { echo 'active'; }?>"">

                                    <a href="{{url('admin/user-coins')}}"><i class="fa fa-user"></i>User Coins</a></li>


                                <li class="@if(Request::is('admin/withdraw-management') || Request::is('admin/view-withdraw-management/*')) active @else @endif()"><a href="{{url('admin/withdraw-management')}}"><i class="fa fa-money" aria-hidden="true"></i>Withdrawal Management</a></li>

                                <li><a href="{{url('admin/payment-management')}}"><i class="fa fa-money" aria-hidden="true"></i>Payment History</a></li>
                                 <li><a href="{{url('admin/game-settings')}}"><i class="fa fa-cog" aria-hidden="true"></i>Game Settings</a></li>

                                <li class="@if(Request::is('admin/game-management') || Request::is('admin/view-game-management/*')) active @else @endif()"><a href="{{url('admin/game-management')}}"><i class="fa fa-gamepad" aria-hidden="true"></i>Gameplay History</a></li>


                                <li><a><i class="fa fa-cog" aria-hidden="true"></i>My Account <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{url('admin/update-email')}}">Profile</a></li>
                                        <li><a href="{{url('admin/change-password')}}">Change Password</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{url('admin/logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->


                </div>

            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->