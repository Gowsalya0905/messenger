<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo (isset($site_title) && $site_title != '') ? SITE_TITLE . ' - ' . $site_title : SITE_TITLE; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons-->

    <link rel="apple-touch-icon-precomposed" href="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png">
    <link rel="icon" href="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png" sizes="32x32">


    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>adminlte.min.css">
    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>kabbani_styles.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link href="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.css" rel="stylesheet">
    <link href="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap-datepicker/datepicker.min.css" type="text/css" />
    <!-- jQuery -->

    <script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery/jquery.min.js"></script>
    <script src="<?php echo LAYOUT_PLUG_PATH ?>jquery-validation/jquery.validate.min.js"></script>
    <script src="<?php echo LAYOUT_PLUG_PATH ?>jquery-validation/additional-methods.min.js"></script>

    <link href="<?php echo LAYOUT_PLUG_PATH; ?>datatablesexport/datatables.min.css" rel="stylesheet" />
    <script src="<?php echo LAYOUT_PLUG_PATH ?>datatablesexport/datatables.min.js"></script>
</head>
<style>
    table#example1 {
        width: 100% !important;
    }

    .swal2-checkbox input,
    .swal2-radio input {
        display: none !important;
    }

    .callout a:hover {
        color: #2e2c7f !important;
    }

    .dataTables_filter {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
        float: right;
    }

    .dt-buttons {
        display: block;
    }

    .btn-group {
        float: left;
    }
</style>

<!-- <style type="text/css">
.dt-buttons.btn-group.flex-wrap {
    display: none !important;
}
</style> -->

<?php echo $this->load->view('common/topheader'); ?>