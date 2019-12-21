
<?php
// add below when we need index.php in url
$redirect_key = 'index.php/user/';
//$redirect_key ='';
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=1024">
        <meta name="viewport" content="user-scalable=yes">
        <title><?php echo $pageTitle; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" media="screen" />-->
        <!-- FontAwesome 4.3.0 -->
        <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons 2.0.0 -->
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!--<link href="http://jdewit.github.io/bootstrap-timepicker/css/timepicker.less" rel="stylesheet" type="text/css" />-->


        <style>
            .error{
                color:red;
                font-weight: normal;
            }
            .box{
                padding: 1%;
            }
			  .site_owner{
                margin-left: 20%;
                font-size: 25px;
                color: #fff;
                font-weight: bold;
                background-color: transparent;
                background-image: none;
                padding: 15px 15px;
                font-family: fontAwesome;
                display: inline;
            }
        </style>
        <script src="<?php echo base_url(); ?>assets/datatables/jquery.min.js" type="text/javascript"></script>

        <!--<script src="<?php // echo base_url();      ?>assets/bower_components/jquery/dist/jquery.min.js"></script>-->
        <script type="text/javascript">
            var baseURL = "<?php echo base_url() . $redirect_key; ?>";
        </script>

        <link href="<?php echo base_url(); ?>assets/datatable/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />


        <script src="<?php echo base_url(); ?>assets/datatable/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/datatable/js/dataTables.bootstrap.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo base_url() . $redirect_key; ?>dashboard" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>BDMS</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>BDMS</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
					      <span class="site_owner">N.V. &nbsp;G.E.B.E.</span>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown tasks-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-history"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"> Last Login : <i class="fa fa-clock-o"></i> <?= empty($last_login) ? "First Time Login" : $last_login; ?></li>
                                </ul>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image" alt="User Image"/>
                                    <span class="hidden-xs"><?php echo $name; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">

                                        <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle" alt="User Image" />
                                        <p>
                                            <?php echo $name; ?>
                                            <small><?php echo $role_text; ?></small>
                                        </p>

                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?php echo base_url() . $redirect_key; ?>profile" class="btn btn-warning btn-flat"><i class="fa fa-user-circle"></i> Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo base_url(); ?>index.php/login/logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>


                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>viewMap" >
                                <i class="fa fa-map"></i>
                                <span>Map</span>
                            </a>
                        </li>

                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>breakDown" >
                                <i class="fa fa-gears"></i>
                                <span>New Break Down</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>dashboard" >
                                <i class="fa fa-thumb-tack"></i>
                                <span>Break Downs</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>technicians">
                                <i class="fa fa-users"></i>
                                <span>Technicians</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>settings">
                                <i class="fa fa-cog"></i>
                                <span>Other Settings</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>reports">
                                <i class="fa fa-book"></i>
                                <span>Reports</span>
                            </a>
                        </li>
						 <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>technician_reports">
                                <i class="fa fa-book"></i>
                                <span>Technician Reports</span>
                            </a>
                        </li>
                        <?php
                        if ($role == ROLE_ADMIN) {
                            ?>
                            <li class="treeview">
                                <a href="<?php echo base_url() . $redirect_key; ?>userListing">
                                    <i class="fa fa-users"></i>
                                    <span>Users</span>
                                </a>
                            </li>

                            <?php
                        }
                        ?>
                        <li class="treeview">
                            <a href="<?php echo base_url() . $redirect_key; ?>profile" >
                                <i class="fa fa-user-circle"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>