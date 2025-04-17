<!-- /.content-wrapper -->
<footer class="main-footer">
	<!--<strong>All Copyright are reserved to <a href="<?php // echo BASE_URL;  
														?>">Biztech Softsys Pte Ltd</a>.</strong>-->
	<!-- <strong>Powered By <a href="<?php // echo BASE_URL;  ?>">Welspun</a>.</strong> -->

	<!--    <div class="float-right d-none d-sm-inline-block">
	  <b>Version</b> 3.0.2
	</div>-->
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- jQuery -->
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$(document).ready(function() {
		//var url = window.location;
		//var path = url.pathname;

		var url = "";
		var currentUrl = window.location.href;

		//master 
		//plant

		var addedit_plant = "addTerminal";
        if (currentUrl.indexOf(addedit_plant) !== -1) {
            url = baseURL+"master/terminal/terminalInfo";
        }

		var view_plant = "terminal/view/";
        if (currentUrl.includes(view_plant)) {
            url = baseURL+"master/terminal/terminalInfo";
        }

		//specific location
        var addedit_loc = "addLocation";
        if (currentUrl.indexOf(addedit_loc) !== -1) {
            url = baseURL+"master/LocationMaster/";
        }

		var view_loc = "master/LocationMaster/view/";
		if (currentUrl.includes(view_loc)) {
            url = baseURL+"master/LocationMaster/";
        }

		//Department
		var addedit_dep = "addDepartment";
        if (currentUrl.indexOf(addedit_dep) !== -1) {
            url = baseURL+"master/department/department";
        }

		//Designation
		var view_des = "viewDes";
        if (currentUrl.indexOf(view_des) !== -1) {
            url = baseURL+"master/terminal/designation_info";
        }

		//Employee
		var add_emp = "addEmployee/";
        if (currentUrl.indexOf(add_emp) !== -1) {
            url = baseURL+"master/employee";
        }
		var add_emp = "addEmployee";
        if (currentUrl.indexOf(add_emp) !== -1) {
            url = baseURL+"master/employee/addEmployee";
        }

		var view_emp = "viewEmployee";
        if (currentUrl.indexOf(view_emp) !== -1) {
            url = baseURL+"master/employee";
        }

		//contractor

		var add_con = "addContractor/";
        if (currentUrl.indexOf(add_con) !== -1) {
            url = baseURL+"master/contractor";
        }
		var add_con = "addContractor";
        if (currentUrl.indexOf(add_con) !== -1) {
            url = baseURL+"master/contractor/addContractor";
        }

		var view_con = "viewContractor";
        if (currentUrl.indexOf(view_con) !== -1) {
            url = baseURL+"master/contractor";
        }

		//userpermission
		var view_per = "permission";
        if (currentUrl.indexOf(view_per) !== -1) {
            url = baseURL+"common/permission";
        }

		//slider
		var add_slider = "addSlider";
        if (currentUrl.indexOf(add_slider) !== -1) {
            url = baseURL+"dashboard/slider/addSlider";
        }

		//inspection
		var add_insp = 'add_insp_subtype';
		if (currentUrl.indexOf(add_insp) !== -1) {
            url = baseURL+"inspection/insp_clsubtype";
        }

		var add_insp_sub = 'add_insp_subtypedata';
		if (currentUrl.indexOf(add_insp) !== -1) {
            url = baseURL+"inspection/insp_clsubtypedata";
        }

		//inspection list
		var add_moninsp = 'addMonthlyinspection';
		if (currentUrl.indexOf(add_moninsp) !== -1) {
            url = baseURL+"inspection/Inspectionlistmonthly";
        }

		var view_moninsp = 'viewInspectionmonthly';
		if (currentUrl.indexOf(view_moninsp) !== -1) {
            url = baseURL+"inspection/Inspectionlistmonthly";
        }
		
		var insp_atar = "atar/monthly/view/";
        if (currentUrl.includes(insp_atar)) {
            url = baseURL+"atar/monthly/monthlyInfo";
        }

		//Incident

		var add_inc = 'notify';
		if (currentUrl.indexOf(add_inc) !== -1) {
            url = baseURL+"incident";
        }

		var view_inc = 'investCheck';
		if (currentUrl.indexOf(view_inc) !== -1) {
            url = baseURL+"incident";
        }

		//investigation

		var add_inv = 'investigate';
		if (currentUrl.indexOf(add_inv) !== -1) {
            url = baseURL+"incident/investigations";
        }

		var view_inv = '/atar/incident/view';
		if (currentUrl.includes(view_inv)) {
            url = baseURL+"atar/incident/incidentInfo";
        }
		//project
		var add_proj = 'atarproject/incident/addHmp';
		if (currentUrl.includes(add_proj)) {
            url = baseURL+"atarproject/incident/incidentInfo";
        }

		var view_proj = 'atarproject/incident/view';
		if (currentUrl.includes(view_proj)) {
            url = baseURL+"atarproject/incident/incidentInfo";
        }


		//obse
		var add_obs = "atarusee/incident/addHmp";
        if (currentUrl.indexOf(add_obs) !== -1) {
            url = baseURL+"atarusee/incident/incidentInfo";
        }

		var view_obs = "atarusee/incident/view/";
        if (currentUrl.indexOf(view_obs) !== -1) {
            url = baseURL+"atarusee/incident/incidentInfo";
        }

		//hira
		var hira_obs = "add_hirarc";
        if (currentUrl.indexOf(hira_obs) !== -1) {
            url = baseURL+"hirarc/hirarc";
        }
		var view_hira = "/hirarc/hirarc/view_report/";
        if (currentUrl.includes(view_hira)) {
            url = baseURL+"hirarc/hirarc";
        }
		//hira atar
		var view_hiraatar = "atarhirarc/incident/view/";
        if (currentUrl.includes(view_hiraatar)) {
            url = baseURL+"atarhirarc/incident/incidentInfo";
        }

		//sop
		var add_sop = "hirarcsop/hirarcsop/add_hirarc";
        if (currentUrl.includes(add_sop)) {
            url = baseURL+"hirarcsop/hirarcsop";
        }

		var view_sop = "hirarcsop/hirarcsop/view_report/";
        if (currentUrl.includes(view_sop)) {
            url = baseURL+"hirarcsop/hirarcsop";
        }

		//legal calender
		var legal_cal = "atarlegal/incident/calendar_info";
        if (currentUrl.includes(legal_cal)) {
            url = baseURL+"atarlegal/incident/calendar_info";
        }

		var add_legal = "atarlegal/incident/addHmp";
        if (currentUrl.includes(add_legal)) {
            url = baseURL+"atarlegal/incident/incidentInfo";
        }

		var view_legal = "atarlegal/incident/view";
        if (currentUrl.includes(view_legal)) {
            url = baseURL+"atarlegal/incident/incidentInfo";
        }

		//contractor legal
		var con_cal = "training/contractor/calendar_info";
        if (currentUrl.includes(con_cal)) {
            url = baseURL+"training/contractor/calendar_info";
        }

		var add_con = "/training/contractor/addHmp";
        if (currentUrl.includes(add_con)) {
            url = baseURL+"training/contractor";
        }

		var view_con = "training/contractor/view/";
        if (currentUrl.includes(view_con)) {
            url = baseURL+"training/contractor";
        }
		//training employee
		var tran_subcat = "training/module/add";
        if (currentUrl.includes(tran_subcat)) {
            url = baseURL+"training/module";
        }

		var add_study = "add_study";
        if (currentUrl.indexOf(add_study) !== -1) {
            url = baseURL+"training/module/study_material";
        }

		var add_theory = "addTheoretical";
        if (currentUrl.indexOf(add_theory) !== -1) {
            url = baseURL+"training/module/question";
        }

		var emp_cal = "training/internal/calendar_info";
        if (currentUrl.includes(emp_cal)) {
            url = baseURL+"training/internal/calendar_info";
        }

		var add_trainemp = "training/internal/addHmp";
        if (currentUrl.includes(add_trainemp)) {
            url = baseURL+"training/internal";
        }
		
		var add_attendance = "addAttendance";
        if (currentUrl.indexOf(add_attendance) !== -1) {
            url = baseURL+"training/internal";
        }

		var add_maptheory = "mapTheoreticalexam";
        if (currentUrl.indexOf(add_maptheory) !== -1) {
            url = baseURL+"training/internal";
        }

		var view_trainemp = "training/internal/view/";
        if (currentUrl.includes(view_trainemp)) {
            url = baseURL+"training/internal";
        }
		

		$('li a[href="' + url + '"]').parentsUntil('nav-item').addClass('active');
		$('li a[href="' + url + '"]').parentsUntil('nav-item active menu-open').addClass('activeahref');
		if ($('li.nav-item.has-treeview  a[href="' + url + '"]').parentsUntil('active').addClass('menu-open')) {
			$('ul.nav.nav-treeview.active.menu-open').css('display', 'block');
		}

	});
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->

<script src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>

<!--<script src="https://cdn.jsdelivr.net/gh/moment/moment@develop/min/moment-with-locales.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/djibe/bootstrap-material-datetimepicker@83a10c38ee94dd27fd946ea137af6667c65a738f/js/bootstrap-material-datetimepicker-bs4.min.js"></script>-->
<?php
$currentURL = current_url();
if ($currentURL == BASE_URL('dashboard')) {
?>

	<!-- ChartJS -->
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>jqvmap/jquery.vmap.min.js"></script>
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery-knob/jquery.knob.min.js"></script>
<?php
}
?>
<!-- daterangepicker -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>moment/moment.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>summernote/summernote-bs4.min.js"></script>
<!--<script src="<?php echo LAYOUT_PLUG_PATH; ?>daterangepicker/daterangepicker.js"></script>-->
<!-- overlayScrollbars -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>adminlte.js"></script>
<?php
$currentURL = current_url();
if ($currentURL == BASE_URL('dashboard')) {
?>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>pages/dashboard.js"></script>
<?php
}
?>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>demo.js"></script>
<script src="<?php echo BASE_URL('assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js'); ?> "></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>common_jquery/customJquery.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>select2/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>-->
<!--<script src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap/js/bootstrap.min.js"></script>-->
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>picedit.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery-lazy/jquery.lazy.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>jquery-lazy/jquery.lazy.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo LAYOUT_PLUG_PATH; ?>fancybox/jquery.fancybox.min.js"></script>
<!--<script src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>-->
<script>
	var baseURL = '<?php echo BASE_URL; ?>';
	var loadingImg = '<?php echo LOADING_IMG; ?>';
</script>
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>script.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>common_jquery/idleJs.js"></script>
<script src="<?php echo BASE_URL; ?>public/assets/plugins/tinymce/js/tinymce/tinymce.min.js?apiKey=h0rug1gt1eml9wk4nvhed9j51u2ornv0ef2wv2h0keacrdoa"></script>


</body>

</html>