<?php $userdata = $this->session->userinfo;






$prifleImg = postData($userdata, 'PROFILE_IMG');

$displayImg = ($prifleImg != '') ? BASE_URL . $prifleImg :  LAYOUT_PLUG_PATH_IMG . 'user2-160x160.jpg';

?>
<style>

  .user-panel img {
    height: 50px !important;
    width: 50px !important;
  }

  img.big.brand-image.img-circle {
    margin-left: 0px !important;
  }

  .nav-item.active.menu-open {
  border-radius: 5px;
}


[class*="sidebar-dark-"] .nav-sidebar > .nav-item.menu-open > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item:hover > .nav-link, [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link:focus { 
   background-color: #949699 !important;
 }



[class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link:focus, [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link:hover {
  background-color: rgb(40, 47, 125) !important;
}

.nav-item.active.menu-open {
  border: 1px solid #fff;
}



[class*="sidebar-dark-"] .sidebar a {
  color: #FFF;
}

 [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link {
  color: #282f7d;
}

[class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link:active {
  color: #fff;
} 

/* view page */

.btn-primary {
  color: #fff;  
  background-color: #282f7d !important;
  border-color: #282f7d !important;
  box-shadow: none !important;
}

.panel-body {
  border: 2px solid #282f7d2e !important;
}

/* .modal-header {
  background: #c8094e !important; 
  padding: 10px !important;
} */

.emplBorder {
  background-color: #f99a39 !important; 
}

.deptBordstyl {
  border: 1px solid #282f7d !important;
  padding: 10px;
}

.card-header-inner {
  color: #fff;
  background-color: #c8094e !important;
  font-weight: bold;
  text-transform: uppercase;
}

[class*="sidebar-dark-"] .user-panel a:hover {
  color: #282f82 !important;
}

.page-item.active .page-link {
  z-index: 1;
  color: #fff;
  background-color: #282f7d !important;
  border-color: #282f7d !important;
}

.page-link {
  
  color: #282f7d ;
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

.test > p{
  color:red;
}

.activeahref > p, .activeahref > i{
  color:#fff ;
}


.activeahref > a > p,.activeahref > a > i{
  color:#FFF ;
}

.nav-pills .nav-link {
  border-radius: 0 !important;
}

.modal-header{
        background: #949699 !important;
        padding : 10px !important;
    }

    .modal-title{
        color: white !important;
    }
    .card-header-inner{
        color: #fff;
        background-color: #949699 !important;
        font-weight: bold;
        text-transform: uppercase;
    }

    .emplBorder{
   background-color: #949699 !important;
   }

   * heading space in add page */
.headingStyl{
    margin: 17px 0 0 17px;
}

/* image center */
.imgupload{
    width: 100%;
    height: 100%;
}
.fileinput img{
    width: 100%;
    height: 100%;
}
.mini-txt{
    text-align: center;
}

/* reduce the submit vibration */
label {
    display: unset !important;
    /* margin-bottom: 0.5rem; */
}

/* image heading down reduce */
.fileinput {
    display: block !important;
    /* margin-bottom: 9px; */
}

.pro_name:hover {
  color: #fff !important;
}

.form-control,.select2-selection{
  margin-top:5px !important;
}

.fa.fa-save {
  display: none !important;
}

.fileinput-preview.thumbnail.bootimgheight {
  margin-top: 0px !important;
}

.float-right.removeCertivcd {
  position: absolute !important;
  margin-left: 300px !important;
}

p {
  font-size: 14px !important;
}

.fas.fa-angle-left {
  margin-left: 5px !important;
}

.nav-sidebar .nav-link > .right, .nav-sidebar .nav-link > p > .right {
  position: absolute;
  right: 8px !important;
  top: 0.9rem !important;
}

@media (min-width: 768px) {
body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header {
    transition: margin-left .3s ease-in-out;
    margin-left: 270px;
}

}

:not(.layout-fixed) .main-sidebar {
    width: 270px;
}

    .dropdown-menu-lg{
      max-width:400px !important;
    }
    .dropdown-menu-lg p{
      word-break: break-all;
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
          <!-- <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a> -->
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
      <a href="<?php echo BASE_URL; ?>" class="brand-link" style="height: 60px;text-align:center;">
        <!-- <img src="<?php echo BASE_URL; ?>assets/images/modules/company_logo/Logo-Mini.png" alt="AdminLTE Logo" class="brand-image img-circle"> -->
        <img src="<?php echo BASE_URL; ?>assets/images/modules/company_logo/logo_11.png" alt="AdminLTE Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light"></span>
      </a>

      
      <!-- Sidebar -->
      <div class="sidebar" style="position: fixed;width: 270px;">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo $displayImg; ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info col-md-4">
            <a href="<?php echo BASE_URL . 'dashboard/profile'; ?>" class="d-block"><span class='pro_name'><?php echo $userdata->USERNAME ?></span></a>

          </div>
          <div class="info col-md-4">

          

            <a href="<?php echo BASE_URL . 'logout'; ?>" class="">
            <i class="fas fa-sign-out-alt" style="float: inline-end;font-size: 30px;color: #ea0101;" data-toggle="tooltip" title="Logout"></i>
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
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({
    placement: 'top'
  });   
});
</script>

        <!-- Content Wrapper. Contains page content -->

        <!-- /.content-wrapper -->