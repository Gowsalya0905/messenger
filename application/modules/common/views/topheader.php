<?php
$userdata = $this->session->userinfo;
$empdata = $this->session->emp_details;
$usdata = $this->session->user_details;

$prifleImg = postData($userdata, 'PROFILE_IMG');

$locname = postData($empdata, 'unit_name');
$rolname = postData($usdata, 'ROLENAME');
$NAME =  postData($usdata, 'NAME');


// echo "<pre>";
// print_r($_SESSION);

// $displayImg = ($prifleImg != '') ? BASE_URL . $prifleImg :  LAYOUT_PLUG_PATH_IMG . 'user2-160x160.jpg';
$displayImg = ($prifleImg == '') ? BASE_URL . 'assets/images/avatar-2.png' :  LAYOUT_PLUG_PATH_IMG . 'user2-160x160.jpg';

?>
<style>
  #employeeid>thead>tr {
    color: black !important;
    font-size: 14px;
    background:rgb(249, 249, 249) !important;
  }

  .notificationDropdown {
    padding: 0px !important;
  }


  .dropdown-menu.dropdown-menu-lg.dropdown-menu-right.notificationDropdown {
    max-width: none !important;
  }

  .imgupload {
    width: 100%;
    height: 100%;
  }

  .fileinput img {
    width: 100%;
    height: 100%;
  }

  .note-editing-area {
    height: 100px !important;
  }

  .modal-header .close,
  .modal-header .mailbox-attachment-close {
    color: #fff;

  }

  .btn-warning {

    background-color: #f8af00 !important;
    border-color: #f8af00 !important;

  }

  .user-panel img {
    height: 50px !important;
    width: 50px !important;
  }


  body {

    font-size: 0.9rem !important;

  }

  img.big.brand-image.img-circle {
    margin-left: 0px !important;
  }

  .nav-item.active.menu-open {
    border-radius: 5px;
  }


  [class*="sidebar-dark-"] .nav-sidebar>.nav-item.menu-open>.nav-link,
  [class*="sidebar-dark-"] .nav-sidebar>.nav-item:hover>.nav-link,
  [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-link:focus {
    background-color: #004EA3 !important;
  }



  [class*="sidebar-dark-"] .nav-treeview>.nav-item>.nav-link:focus,
  [class*="sidebar-dark-"] .nav-treeview>.nav-item>.nav-link:hover {
    background-color: #c8094e !important;
  }

  .nav-item.active.menu-open {
    border: 1px solid #fff;
  }


  .nav-item .active {


    background-color: #004EA3;
    border: 2px solid #fff;

  }

  .activeahref>a>p,
  .activeahref>a>i {
    color: #ffffff;
  }

  /*
[class*="sidebar-dark-"] .sidebar a {
  color: #203669;
}*/

  .sidebar {
    background:rgb(255, 255, 255) !important;
    /* background: #ffffff !important; */
    padding-left: .0rem !important;
    padding-right: .0rem !important;
    color:rgb(61, 95, 146);
  }

  [class*="sidebar-dark-"] .sidebar a {
    color:#5f5f5f;
  }

  [class*="sidebar-dark-"] .nav-treeview>.nav-item>.nav-link {
    color: #203669;
  }

  [class*="sidebar-dark-"] .nav-treeview>.nav-item>.nav-link:active {
    color: #fff;
  }

  /* view page */

  .btn-primary {
    color: #fff;
    background-color: #004EA3 !important;
    border-color: #004EA3 !important;
    box-shadow: none !important;
  }

  .panel-body {
    border: 1px solid #203669 !important;
  }

  .modal-header {
    background: #203669 !important;
    padding: 10px !important;
  }

  .emplBorder {
    background-color: #004EA3 !important;
  }

  .deptBordstyl {
    border: 1px solid #203669 !important;
    padding: 10px;
  }

  .card-header-inner {
    color: #fff;
    background-color: #004EA3 !important;
    font-weight: bold;
    text-transform: uppercase;
  }

  [class*=sidebar-dark] .brand-link {
    border-bottom: 1px solidrgb(124, 127, 130);
    color: rgba(255, 255, 255, 0.8);
    width: 100%!important; /* Makes the border-bottom span the full width */
}



  /*[class*="sidebar-dark-"] .user-panel a:hover {
  color: #203669 !important;
}*/

  .page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #203669 !important;
    border-color: #203669 !important;
  }

  .page-link {

    color: #203669;
    background-color: #fff;
    border: 1px solid #dee2e6;
  }

  .bg-yellow {
    background-image: linear-gradient(to right top, #3f5364, #293080, #f89939) !important;
  }

  i.iconss {

    background-image: linear-gradient(to right top, #3f5364, #293080, #f89939) !important;
  }

  .info-box {
    /*box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;*/
    box-shadow: rgba(0, 0, 0, 0.4) 0px 0px 0px, rgba(0, 0, 0, 0.3) 3px 0px 20px 1px, rgba(0, 0, 0, 0.2) 0px 0px 0px inset !important;
  }



  .navbar-nav .far.fa-bell {
    font-size: 24px !important;
  }

  .navbar-badge {
    font-size: 10px !important;
    font-weight: 300;
    padding: 2px 4px;
    position: absolute;
    right: 10px !important;
    top: 5px !important;
  }

  /*dashboard first section*/
  .widget-user-2 .widget-user-header {
    padding: 21px !important;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;
  }

  .no-padding {
    padding: 0 !important;
    margin-bottom: 20px !important;
  }

  .test>p {
    color: red;
  }

  .activeahref>p,
  .activeahref>i {
    color: #fff;
  }

  .user-panel {
    border-bottom: 1px solid #ffffff !important;
  }

  /*.activeahref > a > p,.activeahref > a > i{
  color:#FF8100 ;
}*/

  /*.activeahref > a > p, .activeahref > a > i {
    color: #f87e1f;
}*/



  .modal-title {
    color: white !important;
  }

  .brand-link .brand-image {
    max-height: 50px !important;
  }

  /* Default styles */
.brand-link {
    height: 50px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    border-bottom: 1px solid #4b545c;
    padding: 5px 10px;
}

/* Ensure the image does not overflow */
.brand-link img {
    height: 40px; /* Adjust height as needed */
    width: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .brand-link {
        flex-direction: column;
        height: auto;
        padding: 10px;
    }
    
    .brand-link img {
        height: 35px; /* Adjust for smaller screens */
    }

    .brand-text {
        font-size: 16px; /* Reduce font size on mobile */
    }
}

/* Ensure border-bottom is full width */
.brand-link::after {
    content: "";
    display: block;
    width: 100%;
    height: 1px;
    background: #4b545c;
    position: absolute;
    bottom: 0;
    left: 0;
}

body {
  font-family: 'Roboto', sans-serif !important;
}

</style>

<!-- <body class="hold-transition sidebar-mini layout-fixed"> -->

<body class="hold-transition sidebar-mini ">
  <div class="wrapper">

    <div id="divLoading">
      <div id="loadinggif" class="show"></div>
    </div>


    <!-- Navbar -->
    <!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top"> -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link mobile-toggle-menu" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto" style="gap: 8px;">
        <!-- Notifications -->
       

        <!-- User Panel -->
        <!-- <li class="nav-item dropdown">
    <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" style="gap: 8px;"> -->
        <!-- <img src="<?php echo $displayImg; ?>" class="img-circle elevation-2" alt="User Image" width="30" height="30"> -->
        <!-- <span class="d-none d-sm-inline"><b><?php echo $locname . ' User'; ?></b></span> -->
        <!-- <span class="text-muted"><?php echo $rolname; ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-header text-center">
            <img src="<?php echo $displayImg; ?>" class="img-circle elevation-2 mb-2" alt="User Image" width="50">
            <p class="mb-1" style="font-size: 16px; font-weight: bold;"><?php echo $locname . ' User'; ?></p>
            <span class="text-muted"><?php echo $rolname; ?></span>
        </div>
        <div class="dropdown-divider"></div>
        <a href="<?php echo BASE_URL . 'dashboard/profile'; ?>" class="dropdown-item d-flex align-items-center">
            <i class="fas fa-user mr-2"></i> Profile
        </a> -->
        <div class="dropdown-divider"></div>
        <a href="<?php echo BASE_URL . 'logout'; ?>" class="dropdown-item d-flex align-items-center text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</li>

    </ul>
</nav>

    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed;">
      <!-- Brand Logo -->
      <a href="<?php echo BASE_URL; ?>" class="brand-link mt-2" style="height: 50px;text-align:center;place-self: center;margin-left: -15px;width: 100px;">
        <!-- <img src="<?php echo BASE_URL; ?>assets/images/modules/company_logo/Logo-Mini.png" alt="AdminLTE Logo" class="brand-image img-circle"> -->
        <span class="brand-text font-weight-light" style="font-size:20px; color:#003893;font-weight:600!important;">MESSAGER</span>
      </a>


      <!-- Sidebar -->
      <div class="sidebar" style="position: fixed;width: 250px;">
      
        <script>
          $(document).ready(function() {
            $(".small").hide();
            $(".navbar-nav").click(function() {
              if ($("body").hasClass("sidebar-collapse")) {
                $(".big").show();
                $(".small").hide();
              } else {
                $(".small").show();
                $(".big").hide();
              }
            });
            $(".main-sidebar").hover(function() {
              if ($("body").hasClass("sidebar-collapse")) {
                $(".big").show();
                $(".small").hide();
              }
            });
            $(".content").hover(function() {
              if ($("body").hasClass("sidebar-collapse")) {
                $(".big").hide();
                $(".small").show();
              }
            });
            $("body,.main-header").hover(function() {
              if ($("body").hasClass("sidebar-collapse")) {
                $(".big").hide();
                $(".small").show();
              }
            });
          });
        </script>

        <script>
          $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
              placement: 'top'
            });
          });
        </script>

        <!-- Content Wrapper. Contains page content -->

        <!-- /.content-wrapper -->