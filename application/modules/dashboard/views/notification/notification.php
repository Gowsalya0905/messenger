

<style>
    .getObs{
        color:#2e2c7f;
    }
    .card-primary.card-outline-tabs > .card-header a.active
{
  border-top: 3px solid #2e2c7f;
}

.activeahref > a > p, .activeahref > a > i {
     color: #203669 !important; 
}
.dropdown-menu-lg .dropdown-item {
    padding: .5rem 1rem;
    background-color: white;
}
.dropdown-item.active, .dropdown-item:active {
    color: #fff;
    text-decoration: none;
    background-color: #007bff;
}
.dropdown-item:focus, .dropdown-item:hover {
    color: #16181b;
    text-decoration: none;
    background-color: #f8f9fa;
}
    </style>


<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('respflash');
                $this->session->unset_userdata('respflash');
                ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $site_title ?></h3>
                        </div>

                        <!-- Modules Tabs Start -->

                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link getObs <?php echo ($tab_id =='0')?'active':''; ?>" data-id='0' id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">ALL</a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a class="nav-link getObs <?php echo ($tab_id =='1')?'active':''; ?>" data-id='1' id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Observation</a>
                                    </li>
                                   
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <table attrbuteName='55' id="example7" class="table table-bordered table-striped">
                                        <thead style="background-color: #203669;color: #FFFFFF;">
                                            <tr>
                                                <th>Module Name</th>
                                                <th>Notifications</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                 </div>
                                    
                                </div>
                            </div>
                            </div>
                        </div>

                         <!-- Modules Tabs End -->
 
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

</div>

<script>
    $(document).ready(function(){

        $('.getObs').on('click',function(){
            ids = $(this).attr('data-id');
            window.location.href='<?php echo BASE_URL.'dashboard/NotificationList'?>?ids='+ids;
        })

        $('#example7').DataTable({


            "lengthChange": true,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "responsive": true,
            "bSort": false,
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-6'f><'col-sm-12 col-md-2'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "ajax": {
                "url": "<?php echo isset($ajaxurl) ? site_url($ajaxurl) : '' ?>",
                "type": "POST",
                "data": {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },
            "lengthMenu": [[5, 10, 50,100,500,1000, -1], [5, 10, 50,100,500,1000, "All"]],
            buttons: [{
                    extend: 'print',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }}, 
                    {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }}]
        });
    })
    </script>

