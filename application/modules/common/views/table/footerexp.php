<footer class="main-footer">

</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            var url = window.location;
            var path = url.pathname;
            var pathn = path.split("/");
            var name = pathn[3];
            $('li a[href="' + url + '"]').parentsUntil('nav-item').addClass('active');
            $('li a[href="' + url + '"]').parentsUntil('nav-item active menu-open').addClass('activeahref');
            if ($('li.nav-item.has-treeview  a[href="' + url + '"]').parentsUntil('active').addClass('menu-open')) {
                $('ul.nav.nav-treeview.active.menu-open').css('display', 'block');
            }
        });
    });
</script>

<!-- Bootstrap 4 -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>



<!-- AdminLTE App -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>moment/moment.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>daterangepicker/daterangepicker.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!--<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>adminlte.min.js"></script>-->
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>demo.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>sweetalert/sweetalert.min.js"></script>

<script src="<?php echo LAYOUT_PLUG_PATH; ?>common_jquery/customJquery.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap-datepicker/datepicker.min.js"></script>



<script>
    var baseURL = '<?php echo BASE_URL; ?>';
    var loadingImg = '<?php echo LOADING_IMG; ?>';
</script>
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>script.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>common_jquery/idleJs.js"></script>

<!-- page script -->

</body>

</html>