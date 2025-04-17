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

$displayImg = ($prifleImg != '') ? BASE_URL . $prifleImg :  LAYOUT_PLUG_PATH_IMG . 'user2-160x160.jpg';

?>
<style>
  #employeeid>thead>tr {
    color: #fff !important;
    font-size: 14px;
    background: #004EA3 !important;
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
    background: #004EA3 !important;
    /* background: #ffffff !important; */
    padding-left: .0rem !important;
    padding-right: .0rem !important;
    color: #f4f6f9;
  }

  [class*="sidebar-dark-"] .sidebar a {
    color: #FFFFFF;
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
    max-height: 100px !important;
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
    <nav class="main-header navbar navbar-expand navbar-white navbar-light ">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link mobile-toggle-menu" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>

      </ul>

      <!--    <div class='' style="font-size: 22px;margin-left: 265px;" >
      <b>Location Safety System (TSS)</b>
    </div>-->
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
          <?php echo getNotifications(); ?>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed;">
      <!-- Brand Logo -->
      <a href="<?php echo BASE_URL; ?>" class="brand-link" style="height: 100px;text-align:center;place-self: center;margin-left: -15px;width: 100px;">
        <!-- <img src="<?php echo BASE_URL; ?>assets/images/modules/company_logo/Logo-Mini.png" alt="AdminLTE Logo" class="brand-image img-circle"> -->
        <img src="<?php echo BASE_URL; ?>assets/images/modules/company_logo/Logo.png" alt="AdminLTE Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light"></span>
      </a>


      <!-- Sidebar -->
      <div class="sidebar" style="position: fixed;width: 250px;">
        <!-- Sidebar user panel (optional) -->
        <div class=" col-md-12 mt-3" style="text-align: center;justify-content: center;text-transform: uppercase;font-weight: bold;">
          <a href="<?php echo BASE_URL . 'dashboard/profile'; ?>" class="d-block" style="font-size: 14px;color: #c8094e !important;"><?php echo $NAME ?></a>
          </a>
        </div>
        <div class="user-panel mt-1 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo $displayImg; ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info col-md-6">

            <a href="<?php echo BASE_URL . 'dashboard/profile'; ?>" class="d-block" style="font-size: 15px;"><?php echo $locname . ' User' ?></a>
            <span><?php echo $rolname; ?></span>
          </div>
          <div class="info col-md-3">



            <a href="<?php echo BASE_URL . 'logout'; ?>" class="">
              <i class="fas fa-sign-out-alt" style="float: inline-end;font-size: 30px;color: #FFFFFF;margin-top: 5px;margin:5px 10px 0px 0px;" data-toggle="tooltip" title="Logout"></i>
            </a>
          </div>
        </div>
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