<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>系统后台</title>
    <meta name="keywords" content="系统总后台" />
    <meta name="description" content="系统总后台" />
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="/src/css/bootstrap.min.css">
    <link rel="stylesheet" href="/src/css/font-awesome.min.css">
    <link rel="stylesheet" href="/src/css/select2.min.css">
    <link rel="stylesheet" href="/src/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/src/css/_all-skins.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="/src/css/ionicons.min.css">
    <link rel="stylesheet" href="/src/css/toastr.min.css">
    @yield('css')
    <style>
        thead th,table.table,tr th{
            text-align: center;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{
            vertical-align: middle;
        }
        div.dataTables_wrapper div.dataTables_info{
            float: left;
        }
        div.dataTables_length{
            margin-bottom: 10px;
        }
        div.dataTables_wrapper div.dataTables_processing{
            position: fixed;
        }
    </style>
</head>

<body class="hold-transition skin-green-light fixed sidebar-mini">
@if (!WinwinAuth::adminAuth()->guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>双赢</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="/src/images/blue_logo_150x150.jpg"
                                     class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! WinwinAuth::adminUser()->username !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="/src/images/blue_logo_150x150.jpg"
                                         class="img-circle" alt="User Image"/>
                                    <p>
                                        {!! WinwinAuth::adminUser()->username !!}
                                        <small>Member since {!! WinwinAuth::adminUser()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('admin/logout') !!}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Sign out
                                        </a>
                                        <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('Admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2016 <a href="#">Company</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    InfyOm Generator
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif

@yield('footer')

    <!-- jQuery 2.1.4 -->
    <script src="/src/js/jquery-1.12.0.min.js"></script>
    <script src="/src/js/bootstrap.min.js"></script>
    <script src="/src/js/select2.min.js"></script>
    <script src="/src/js/icheck.min.js"></script>
    <script src="/src/js/jquery.slimscroll.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/src/js/app.min.js"></script>
    <!--Toast-->
    <script src="/src/js/toastr.min.js"></script>
    <script>
        (function ($) {
            $.fn.serializeJson = function () {
                var serializeObj = {};
                $(this.serializeArray()).each(function () {
                    serializeObj[this.name] = this.value;
                });
                return serializeObj;
            };

            $.fn.showOverlayLoading = function(){
                $('#overlay').show();
            };

            $.fn.hideOverlayLoading = function(){
                $('#overlay').hide();
            };
        })(jQuery);
    </script>
@yield('scripts')
</body>
</html>