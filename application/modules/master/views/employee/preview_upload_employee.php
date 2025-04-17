<style>
    .redClass{
        background-color: #ffeb3b !important;
    }
    .warningClass{
        background-color: #FFCCCB !important;
    }
</style>
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('uploadDatamsg'); ?>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card ">
                        <div class="card-header">
                            
                            <h3 class="card-title">Employee Imported Data </h3>
                            <?php if($showImportBtn == FALSE){?>
                            <a href="<?php echo BASE_URL('master/EmployeeUpload') ?>"><button type="button" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> Back</button></a>
                            <?php } ?>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="uploadTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> Employee ID </th>
                                        <th> Name </th>
                                        <th> Sex </th>
                                        <th> Nationality </th>
                                        <th> Designation</th>
                                        <th> Department</th>
                                        <th> Location</th>
                                        <th> Email Id</th>
                                        <th> Status</th>

                                    </tr>
                                </thead>

                            </table>

                            <?php
                            echo form_open_multipart('master/EmployeeUpload/importEmployee', 'class="form-horizontal" id="importEmployee" novalidate');
                            echo form_hidden('editId',$editId);
                            ?>
                            
                            <div class="row">
                                <p class="m-t-10"> <strong>NOTE:</strong>
                                    <span class="redClass">*</span>  Duplicate data</p>
                            </div>
                            <?php if($showImportBtn){?>
                            
                            <div class="row m-t-10">
                            <div class="col-md-12 form-group " >
                               
                                <div class="col-sm-offset-2 col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="import_type" id="import1" value="edit" >
                                    <label class="form-check-label" for="import1">
                                       Edit duplicate and Import Data
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="import_type" id="import2" value="skip" checked>
                                    <label class="form-check-label" for="import2">
                                        Skip duplicate data
                                    </label>
                                </div>
                                    </div>
                                
                            </div>
                                </div>
                            <div class="form-group m-t-10" style="text-align: center;">
                                <div class="col-sm-offset-2 col-sm-10"> 

                                    <?php
                                    $data = array('id' => 'submit', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> CONFIRM IMPORT', 'class' => 'btn btn-primary');
                                    echo form_button($data);
                                    ?>
                                    <a href="<?php echo BASE_URL.'master/EmployeeUpload/';?>" class="btn btn-danger">CANCEL</a>
                                </div>
                            </div>
                            <?php } ?>
                            <?php echo form_close(); ?>
                        </div>
                        <!-- /.card-body -->
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

<!----- popup starts----->
<div class="modal modal-info fade" id="respmodal" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>
<!----- popup ends----->
<script>
    $(document).ready(function () {
        
         $('#importEmployee').validate({
	rules: {

	    "import_type": {
		required: true,
	    },
	    
	   
	    
	   
	},
	messages: {
	    "import_type": {
		required: "Please select import type",
	    },
	   
	   
	},
        success: function(label,element) {
                label.parent().removeClass('error');
		label.remove();
					
	},
        highlight: function(element) {
            $(element).parent('div').addClass('error');
        },
        unhighlight: function(element) {
            $(element).parent('div').removeClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
               error.insertAfter(element.next('span')); 
           } else {
               error.insertAfter(element);
           }
             
        },
	submitHandler: function (form) {
            swal({
                                title: "Please wait..",
                                imageUrl: loadingImg,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                            
                            form.submit();            
	    
	}
    });
        var table = $('#uploadTable').DataTable({
            "bSort": false,
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-6'f><'col-sm-12 col-md-2'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "ajax": {
                "url": "<?php echo BASE_URL . $ajaxurl; ?>",
                "type": "POST",
                "data": {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                }
            },
            "createdRow": function (row, data, dataIndex) {
                if (data[9] == 1) {
                    $(row).addClass('redClass');
                }else if (data[10] > 1) {
                    $(row).addClass('warningClass');
                }
            },
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }}, {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }}, {
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
    });
</script>