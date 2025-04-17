<footer class="main-footer">
    <!--    <div class="float-right d-none d-sm-block">
          <b>Version</b> 3.0.3-pre
        </div>-->
<!--    <strong>Copyright &copy; 2020 <a href="<?php // echo BASE_URL;      ?>">Kabbani</a>.</strong> All rights
    reserved.-->
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script>
    $(document).ready(function () {
        $(document).ready(function () {
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
    });</script>

<!-- Bootstrap 4 -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>jszip/jszip.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>pdfmake/pdfmake.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>pdfmake/vfs_fonts.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-buttons/js/buttons.colVis.min.js"></script>
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

<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH; ?>datatables-responsive/css/responsive.bootstrap4.min.css"/>
<script  src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-responsive/js/dataTables.responsive.min.js"></script>
<script  src="<?php echo LAYOUT_PLUG_PATH; ?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
     var baseURL = '<?php echo BASE_URL;?>';
     var loadingImg = '<?php echo LOADING_IMG; ?>';
</script>
<script src="<?php echo LAYOUT_PLUG_PATH_JS; ?>script.js"></script>
<script src="<?php echo LAYOUT_PLUG_PATH; ?>common_jquery/idleJs.js"></script>

<!-- page script -->
<script>
    $(document).ready(function () {

        var img = $('.brand-link img')[0]; // Selecting the img tag directly
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        canvas.width = 1086;
        canvas.height = 186;
        ctx.drawImage(img, 0, 0);
        var imgulnew = canvas.toDataURL('image/png');


        // alert('jhgfjg')
        var url = window.location; 
        var path = url.pathname;
        var pathn = path.split("/");

        var name4 = pathn[3];

        var name3 = pathn[2];  

        var name1Val = '<?php echo $this->uri->segment(1); ?>';
        var name2Val = '<?php echo $this->uri->segment(2); ?>';
        var name3Val = '<?php echo $this->uri->segment(3); ?>'; 
                          
        //if(name3Val == 'terminalInfo' ){ 

            // var name1 = pathn[1];             
            // if((name2Val == 'master' && name1Val == 'waste' && name3Val === "")||(name2Val == 'master' && name1Val == 'waste' && name3Val === "sub_category")||(name2Val == 'master' && name1Val == 'waste' && name3Val === "waste_list")||(name2Val == 'vendor' && name1Val == 'waste') || (name2Val == 'auditlisttype' && name1Val == 'audit')|| (name2Val == 'audit_clsubtype' && name1Val == 'audit')|| (name2Val == 'masters' && name1Val == 'ptw')|| (name2Val == 'audit_clsubtypedata' && name1Val == 'audit')){
            disableExport=false;
            // }else{
            //     disableExport=false;
            // }
       
            var Heading = "<?php echo $site_title; ?>";
            var formattedDate = moment().format('DD/MM/YYYY');
            var formatDate = moment().format('DD-MM-YYYY');
            var table = $('#example1').DataTable({
            "lengthChange": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "responsive": true,
            "bSort": false,
            responsive: true,
            // dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-6'f><'col-sm-12 col-md-2'B>>" +
            //         "<'row'<'col-sm-12'tr>>" +
            //         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "dom": "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-8 d-flex justify-content-end'<'mr-2'f><'dt-buttons'B>>>"+
                    "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            "ajax": {
                "url": "<?php echo isset($ajaxurl) ? site_url($ajaxurl) : '' ?>",
                "type": "POST",
                "data": {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },
            "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000, "All"]],
            buttons: disableExport ? [] : [
                // {
                //     extend: 'print',
                //     orientation: 'landscape',
                //     exportOptions: {
                //         columns: ':not(:last-child)'
                //     }}, 
                {
                    extend: 'pdf',
                    orientation: 'landscape',

                    filename: function () {
                        //var d = new Date();
                        //var n = d.getTime();
                        return 'Crescent_' + Heading + '_' + formattedDate;
                    },
                    customize: function (doc) {
                        
                        
                        doc.styles.tableHeader.margin =
                        doc.styles.tableBodyOdd.margin =
                                doc.styles.tableBodyEven.margin = [0, 10, 0, 10];
                        doc.styles.tableBodyOdd.alignment = 'center';
                        doc.styles.tableBodyEven.alignment = 'center';

                        doc.styles.tableHeader.margin = [0, 10, 0, 10];
                        var logo = imgulnew;


                        doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                                doc.pageMargins = [ 1, 80, 1, 80 ];
                                doc['header']=(function() {
                            return {
                                columns: [{
                                    image: logo,
                                    width: 175
                                },
                                {
                                    alignment: 'left',
                                    italics: true,
                                    text: '',
                                    fontSize: 18,
                                    margin: -5
                                },
                                // {
                                //     alignment: 'right',
                                //     fontSize: 14,
                                //     text: Heading
                                // }
                            ],
                                margin: 20
                            }
                        });
                     
                       


                        // doc.content[1].table.widths = ['20%', '20%', '10%', '10%', '10%', '10%', '10%', '10%'];



                    },
                    exportOptions: {
                        columns: ':not(:last-child)',
                        modifier: {
                                page: 'current'
                            }
                
                    }
                }

                    , {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }}]
        });
        table.buttons().container()
                .appendTo($('div.eight.column:eq(0)', table.table().container()));
        $('.dt-buttons').find('.buttons-pdf').empty().removeClass('dt-button');
        $('.dt-buttons').find('.buttons-print').empty().removeClass('dt-button');
        $('.dt-buttons').find('.buttons-excel').empty().removeClass('dt-button');
        $('.dt-buttons').find('.buttons-copy').empty().removeClass('dt-button');
        $('.dt-buttons').find('.buttons-csv').empty().removeClass('dt-button');
        var pdfPath = '<i class="fa fa-file-pdf" aria-hidden="true" style="font-size: 24px;color: #ffffff;"></i>';
        var printPath = '<i class="fa fa-print" aria-hidden="true"  style="font-size: 24px;color: #ffffff;"></i>';
        var excelPath = '<i class="fa fa-file-excel" aria-hidden="true" style="font-size: 24px;color: #ffffff;"></i>';
//        var copyPath = '<i class="fa fa-files-o" aria-hidden="true"  style="font-size: 24px;color: #da4e4e;"></i>';
//        var csvPath = '<i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 24px;color: green;"></i>';
        $('.dt-buttons').find('.buttons-pdf').append(pdfPath).attr("data-toggle", 'tooltip').attr('title', 'Export to PDF').attr('data-placement', 'bottom');
        $('.dt-buttons').find('.buttons-print').append(printPath).attr("data-toggle", 'tooltip').attr('title', 'Print').attr('data-placement', 'bottom');
        $('.dt-buttons').find('.buttons-excel').append(excelPath).attr("data-toggle", 'tooltip').attr('title', 'Export to Excel').attr('data-placement', 'bottom');
//        $('.dt-buttons').find('.buttons-copy').append(copyPath).attr("data-toggle", 'tooltip').attr('title', 'Copy').attr('data-placement', 'bottom');
//        $('.dt-buttons').find('.buttons-csv').append(csvPath).attr("data-toggle", 'tooltip').attr('title', 'Export to CSV').attr('data-placement', 'bottom');
 // }
 //else{



//                 $('#example1').dataTable({
//         "lengthChange": true,
//             "processing": true, //Feature control the processing indicator.
//             "serverSide": true, //Feature control DataTables' server-side processing mode.
//             "responsive": true,
//             "bSort": false,
//             responsive: true,
//         "ajax": {
//                 "url": "<?php echo isset($ajaxurl) ? site_url($ajaxurl) : '' ?>",
//                 "type": "POST",
//                 "data": {
//                     '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
//                 }
//             },
//         "dom": "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-6'f><'col-sm-12 col-md-1'B>>" +
//             "<'row'<'col-sm-12'tr>>" +
//             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
//             buttons: [
//                 {
//                     extend: 'excel',
//                     text: 'Export',
//                     action: function(e, dt, button, config) {
//                         var searchValue = $('.dataTables_filter input').val();
//                         var currentURL = window.location.href; 
//                          var exportURL = currentURL.split('?')[0] + 'exportExcel'; 


//                         var params = currentURL.split('?')[1];
//                         if (params) {
//                             exportURL += '?' + params; 
//                             exportURL += '&search=' + searchValue; // Add search parameter
//                         } else {
//                             exportURL += '?search=' + searchValue; // Add search parameter
//                         }

//                        // alert(exportURL); 

//                         // Redirect to the export URL
//                         window.location.href = exportURL;
//                     }
//                 }
//             ],
        
//        "lengthMenu": [[10, 25, 50, 100,250,500, -1], ["10", "25", "50", "100","250","500", "All"]], // Options for number of entries to display
    
//     });

//             }
    });
</script>
</body>
</html>