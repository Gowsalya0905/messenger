
<!-- jQuery -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH;?>jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>moment/moment.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH;?>daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo LAYOUT_PLUG_PATH;?>overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS;?>adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS;?>pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo LAYOUT_PLUG_PATH_JS;?>demo.js"></script>
<script>
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: 3000
        })
    });
</script>
</body>
</html>
