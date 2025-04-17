<style>
    .fileerror{
        color:red;
    }
    
</style>
<div class="wrapper">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class=" content">
            <div class="container-fluid">
                 <div class="" style="margin-top:10px;">
                <?php echo $this->session->flashdata('uploadDatamsg'); ?>
            </div>
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">


                        <!-- general form elements -->
                        <div class="card card-headprimary">
                            <div class="row headingStyl">
                                <div class="col-md-8">
                                    <h4>Employee Bulk Upload</h4>
                                    
                                </div>
                                <div class="col-md-4 float-right">
                                    <a href="<?php echo BASE_URL.'assets/uploads/emp_sample.xlsx' ?>" class="btn btn-primary " download ><i class="fa fa-download"></i> Download Sample</a>
                                    <a href="<?php echo BASE_URL('master/employee') ?>"><button type="button" class="btn btn-primary "><i class="fa fa-arrow-left"></i> Back</button></a>
                                   
                                </div>
                            </div>



                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header with-border" >
                                            <h5 class="">  Upload Employee Details </h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel-body col-md-12">
                                    <?php 
                                     echo form_open_multipart('master/EmployeeUpload/uploadfile/' , 'class="form-horizontal" id="empuploadfile" novalidate'); 
                                    ?>
                                    <div class="row m-t-10 m-l-10">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Upload  <span class="error"> * </span></label>
                                               
                                                     <div class="custom-file">
                            <input type="file"  class="custom-file-input" name="upload_documents" id="upload_documents" placeholder="Upload Documents" value="">
                            <label class="custom-file-label" for="customFile">Upload Documents</label>
                        </div>
                        <span class="">Allowed File types ( xlx, xlsx, csv)</span><br>
                        <span class="red fileerror"><?php echo $upload_error;?></span>
                                               
                                            </div>  
                                        </div>
                                    </div>
                                     <div class="form-group m-t-10" style="text-align: center;">
                                    <div class="col-sm-offset-2 col-sm-10"> 

                                        <?php
                                        $data = array('id' => 'submit', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'btn btn-primary');
                                        echo form_button($data);
                                        ?>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                </div>


                            </div>


                        </div>

                        <!-- /.card -->

                    </div>
                    <!--/.col (left) -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<script type="text/javascript">

    
    $(document).ready(function () {
        
      $('#empuploadfile').validate({
	rules: {

	    "upload_documents": {
		required: true,
	    },
	    
	   
	    
	   
	},
	messages: {
	    "upload_documents": {
		required: "Please upload file",
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

    var checktypearray = [ 'xlx', 'xlsx', 'csv' ];
        $('.custom-file input').change(function (e) {
            var files = [];
            var sizetotal = 0;
            var type = [];
            var countfiles = 0;
            
            for (var i = 0; i < $(this)[0].files.length; i++) {
                var nt = $(this)[0].files[i].name;
                var nts = nt.split(".");
                var lastnts = nts.pop();
                if (checktypearray.includes(lastnts)) {
                    type.push(lastnts);
                    files.push($(this)[0].files[i].name);
                    sizetotal += parseFloat($(this)[0].files[i].size);
                    countfiles++;
                }else{
                     $('span.fileerror').html('Invalid file type');
                }
            }
            if (countfiles > 0) {
                var fname = files.join(', ');
                        var hname = files.join(', ');
                        fname = fname.substring(0, 40);
               $('.custom-file-label').html(fname);
               $('#upload_documents').attr('title', hname);
               
            } else {
                $('#upload_documents').attr('title', 'No file Chosen').val('');
            }

        });

    });

   
</script>

