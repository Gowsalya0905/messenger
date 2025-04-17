<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicons-->
	<!-- <link rel="apple-touch-icon-precomposed" href="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png"> -->
	<meta name="msapplication-TileColor" content="#FFFFFF">
	<meta name="msapplication-TileImage" content="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png">
	<!-- <link rel="icon" href="<?php echo GEN_IMG_PATH; ?>modules/company_logo/Logo-Mini.png" sizes="32x32"> -->
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fontawesome-free/css/all.min.css">
	<!--  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/djibe/bootstrap-material-datetimepicker@6659d24c7d2a9c782dc2058dcf4267603934c863/css/bootstrap-material-datetimepicker-bs4.min.css">-->
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>select2/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>select2-bootstrap4-theme/select2-bootstrap4.min.css">


	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>jqvmap/jqvmap.min.css">
	<!-- Theme style -->

	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>adminlte.min.css">

	<!--<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>kabbani_styles.css?<?php echo time() ?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>summernote/summernote-bs4.css">

	<link href="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.css" rel="stylesheet">
	<link href="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>fancybox/jquery.fancybox.min.css" type="text/css" />
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery/jquery.min.js"></script>
	<script src="<?php echo LAYOUT_PLUG_PATH ?>jquery-validation/jquery.validate.min.js"></script>
	<script src="<?php echo LAYOUT_PLUG_PATH ?>jquery-validation/additional-methods.min.js"></script>
	<link rel="stylesheet" href="<?php echo BASE_URL('assets/plugins/jasny-bootstrap/css/jasny-bootstrap.css'); ?>">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>picedit.min.css">

	<?php echo isset($headerfiles) ? include_files($headerfiles) : ''; ?>
	<style type="text/css">
		.user-panel {
			border-bottom: 1px solid #ffffff !important;
		}

		.swal2-checkbox input,
		.swal2-radio input {
			display: none !important;
		}

		.callout a:hover {
			color: #2e2c7f !important;
		}
	</style>
</head>

<?php echo $this->load->view('common/topheader'); ?>