<?php

// echo '<pre>';
// print_r($getEmploydetails);exit;
   
   
   $emploID = postData($getEmploydetails,'id');
   $emploentID = postData($getEmploydetails,'EmployeeNo');
   $emplName = postData($getEmploydetails,'EmployeeName');
   $emplGender = postData($getEmploydetails,'GenderId');
   $emplNation = postData($getEmploydetails,'NationalityID');
   $emplNationother = postData($getEmploydetails,'EMP_NATIONALITY_OTHER');
   $emploUsertypID = postData($getEmploydetails,'RoleID');
   $emplDesig = postData($getEmploydetails,'DesignationID');
   $emplDept = postData($getEmploydetails,'EMP_DEPT_ID');
   $emplTerminal = postData($getEmploydetails,'EMP_LOC_ID');
   $emplMail = postData($getEmploydetails,'EmailID');
   $reportingId = postData($getEmploydetails,'MANAGER_ID');

   $emp_date_of_birth = postData($getEmploydetails,'DateofBirth');
   $emp_joining_date = postData($getEmploydetails,'JoiningDate');
   $emp_past = postData($getEmploydetails,'PastExperience');
   $emp_phone = postData($getEmploydetails,'PhoneNumber');
   $emp_type = postData($getEmploydetails,'EmployeeTypeId');

//    $emplCertiname = postData($getEmploydetails,'CERT_NAME');
//    $emplCertiIdmain = postData($getEmploydetails,'COMP_CERT_ID');
   if($getEmploydetails->CERT_START_DATE != ''){
   $emplStartdate = date('d-m-Y',strtotime($getEmploydetails->CERT_START_DATE));
   }else{
      $emplStartdate = ''; 
   }
   if($getEmploydetails->CERT_END_DATE != ''){
   $emplEnddate = date('d-m-Y',strtotime($getEmploydetails->CERT_END_DATE));
   }else{
       $emplEnddate = '';
   }
   $emplElistatus = postData($getEmploydetails,'CERT_STATUS');
   $emplCertpath = postData($getEmploydetails,'CERT_PATH');
   $emplCertimgname = postData($getEmploydetails,'CERT_FILE_NAME');
   $emplCertimgextn = postData($getEmploydetails,'CERT_EXT');
   
   if($emplCertimgname != ''){
       $compCertFilepath = $emplCertpath.$emplCertimgname;
   }else{
       $compCertFilepath = '';
   }
   ?>
<style>
   .deptBordstyl{
   border: 3px solid #296dc6;
   padding: 10px;
   }
   .addMorcerti{
   margin-top: -31px;
   margin-right: 10px;
   }
   .othercertiDet{
   border: 3px solid #f3b908;
   padding: 10px;
   margin-top: 10px;
   margin-bottom: 10px;
   }
   .imgupload{
   width: 100%;
   height: 100%;
   }
   .fileinput img{
   width: 100%;
   height: 100%;
   }
   
   .panel-body{
   border: 2px solid #227bff;
   }
</style>
<div class="wrapper">
   <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
      <!-- Main content -->
      <section class=" content">
         <div class="container-fluid">
            <div class="row">
               <!-- left column -->
               <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card">
                     <div class="card-header">
                        <div class="col-md-12">
                           <?php if($emploID != ''){ ?>
                            <h3 class="card-title">Edit Employee</h3>
                           
                           <?php }else{ ?>
                              <h3 class="card-title">Add Employee</h3>
                           
                           <?php } ?>
                           <a href="<?php echo BASE_URL('master/AdIntegration') ?>">
                              <!--<button type="button" class="btn btn-primary  float-right"><i class="fa fa-arrow-left"></i> Back</button>-->
                              <button type="button" class="btn btn-primary backBtns"> Back</button>
                           </a>
                        </div>
                     </div>
                     <?php 
                     
                        if(!empty($emploID)){
                            echo form_open_multipart('master/AdIntegration/insertEmployee/'. encryptval($emploID) , 'class="form-horizontal" id="company-profile" novalidate'); 
                        }else{
                            echo form_open_multipart('/master/AdIntegration/insertEmployee/' , 'class="form-horizontal" id="company-profile" novalidate'); 
                        }
                          
                           
                           ?>
                           		<input type="hidden" class="form-control" name='preview_id' value='<?php echo ($getData->id)?$getData->id:'' ?>'>

                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="box-header with-border emplBorder" >
                                 <h5 class="">  Basic Details </h5>
                              </div>
                           </div>
                        </div>
                        <div class="panel-body col-md-12">
                           <div class="row m-t-10 m-l-10">
                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Employee No <span class="error"> * </span></label>
                                    <?php
                                       $emNumdatas = [
                                           'name' => 'emp[emp_no]',
                                           'id' => 'emp_no',
                                           'class' => 'form-control',
                                           'value' => $emploentID,
                                           'required' => true,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Employee No',
                                       ];
                                       echo form_input($emNumdatas);
                                       ?>
                                    <span id="errEmpno" class="error"></span>
                                    <span class="error"><?php echo form_error('emp[emp_no]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Employee Type <span class="error">  </span></label>
                                    <?php
                                    $etypedata = [
                                        'class' => 'form-control select2',
                                        'id' => 'emp_type',
                                        'checkSelect' => 'select2',
                                        
                                    ];
                                    echo form_dropdown('emp[EMP_TYPE]', $gettype, $emp_type, $etypedata);
                                    ?>
                                    <span id="errEmpno" class="error"></span>
                                    <span class="error"><?php echo form_error('emp[emp_no]') ?></span>
                                 </div>
                              </div>






                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Employee Name <span class="error"> * </span></label>
                                    <?php
                                       $emNamedatas = [
                                           'name' => 'emp[emp_name]',
                                           'id' => 'emp_name',
                                           'class' => 'form-control',
                                           'value' => $emplName,
                                       'required' => true,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Employee Name',
                                       ];
                                       echo form_input($emNamedatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[emp_name]') ?></span>
                                 </div>
                              </div>
                              
                           </div>

                           <div class="row m-l-10">

                           <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Gender <span class="error"> * </span></label>
                                    <?php
                                       $eGenddata = [
                                           'class' => 'form-control select2',
                                           'id' => 'gender',
                                           'checkSelect' => 'select2',
                                       ];
                                       echo form_dropdown('emp[gender]', $dropGender, $emplGender, $eGenddata);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[gender]') ?></span>
                                 </div>
                              </div>
                              
                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Email ID </label>
                                    <?php
                                       $emMaildatas = [
                                           'name' => 'emp[emp_mail]',
                                           'id' => 'emp_mail',
                                           'class' => 'form-control',
                                           'value' => $emplMail,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Mail ID',
                                       ];
                                       echo form_input($emMaildatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[emp_mail]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Date of Birth </label>
                                    <?php
                                       $emMaildatas = [
                                           'name' => 'emp[EMP_BIRTH_DATE]',
                                           'id' => 'emp_date_of_birth',
                                           'class' => 'form-control',
                                           'value' => $emp_date_of_birth,
                                           // 'required' => true,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Date of Birth',
                                       ];
                                       echo form_input($emMaildatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[EMP_BIRTH_DATE]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Joining Date </label>
                                    <?php
                                       $emMaildatas = [
                                           'name' => 'emp[EMP_JOINING_DATE]',
                                           'id' => 'emp_joining_date',
                                           'class' => 'form-control',
                                           'value' => $emp_joining_date,
                                           // 'required' => true,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Joining Date',
                                       ];
                                       echo form_input($emMaildatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[EMP_JOINING_DATE]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Past Experience <span class="error">  </span></label>
                                    <?php
                                       $emMaildatas = [
                                           'name' => 'emp[PAST_EXPERIENCE]',
                                           'id' => 'emp_past',
                                           'class' => 'form-control',
                                           'value' => $emp_past,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Past Experience',
                                       ];
                                       echo form_input($emMaildatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[PAST_EXPERIENCE]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Phone Number <span class="error">  </span></label>
                                    <?php
                                       $emMaildatas = [
                                           'name' => 'emp[PHONE_NUMBER]',
                                           'id' => 'emp_phone',
                                           'class' => 'form-control',
                                           'value' => $emp_phone,
                                           'autocomplete' => 'off',
                                           'placeholder' => 'Enter Phone Number',
                                       ];
                                       echo form_input($emMaildatas);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[PHONE_NUMBER]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Nationality <span class="error"> </span></label>
                                    <?php
                                       $eNatndata = [
                                           'class' => 'form-control select2',
                                           'id' => 'nationality',
                                           'checkSelect' => 'select2',
                                       ];
                                       echo form_dropdown('emp[nationality]', $dropNation, $emplNation, $eNatndata);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[nationality]') ?></span>
                                 </div>
                                 <div class="otherNation"></div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Role <span class="error"> * </span></label>
                                    <?php
                                       $eRoledata = [
                                           'class' => 'form-control select2',
                                           'id' => 'role',
                                           'checkSelect' => 'select2',
                                       ];
                                       echo form_dropdown('emp[role]', $dropRole, $emploUsertypID, $eRoledata);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[role]') ?></span>
                                 </div>
                              </div>

                              <div class="col-sm-4">
                                 <div class="form-group">
                                    <label>Designation <span class="error"> * </span></label>
                                    <?php
                                       $eDesigdata = [
                                           'class' => 'form-control select2',
                                           'id' => 'desig',
                                           'checkSelect' => 'select2',
                                       ];
                                       echo form_dropdown('emp[desig]', $getSpl, $emplDesig, $eDesigdata);
                                       ?>
                                    <span class="error"><?php echo form_error('emp[desig]') ?></span>
                                 </div>
                              </div>

                        


                           </div>

                           <div class="box-header with-border emplBorder">
                           <h5 class="">  Location Details   </h5>
                              </div>

                           <!-- <h5 class="">  Location Details :  </h5> -->


                           <div class="row m-t-10 m-l-10">
<div class="col-sm-4">
        <div class="form-group">
        <label>Plant Name <span class="error"> * </span></label>
        <?php
          $getvername = postData($getEmploydetails,'PlantID');
        $cNamedatas = [
            'id' => 'fk_ver_id',
            'class' => 'form-control select2',
            'required' => true,
            'autocomplete' => 'off',
            'checkSelect' => 'select2'
        ];
        echo form_dropdown('emp[EMP_VERT_ID]', $getPlant, $getvername, $cNamedatas);
        ?>
        <!-- <span class="error"><?php echo form_error('fk_ver_id') ?></span> -->
    </div>
    </div>
    <div class="col-sm-4">
    <div class="form-group">
        <label> Location Name <span class="error"> * </span></label>
        <?php
          $getunitname = postData($getEmploydetails,'LocationID');
        $cNamedatas = [
            'id' => 'fk_unit_id',
            'class' => 'form-control select2',
            'required' => true,
            'autocomplete' => 'off',
            'checkSelect' => 'select2'
        ];
        echo form_dropdown('emp[EMP_UNIT_ID]', $getSpl, $getunitname, $cNamedatas);
        ?>
    </div>
    </div>

      <div class="col-sm-4">
    <div class="form-group">
        <label>Department <span class="error"> * </span></label>
        <?php
          $getdeptname =  postData($getEmploydetails, 'DepartmentID');

        $cNamedatas = [
            'id' => 'fk_dept_id',
            'class' => 'form-control select2',
            'required' => true,
            'autocomplete' => 'off',
            'checkSelect' => 'select2'
        ];
        echo form_dropdown('emp[EMP_DEPT_ID]', [], $getdeptname, $cNamedatas);
        ?>
    </div>
    </div>

  
    </div>



                        </div>
                     </div>
                     <!-- </div> -->
                     <div class="form-group m-t-10" style="text-align: center;">
                        <div class=""> 
                           <?php
                              $data = array('id' => 'submitEmp', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'btn btn-primary');
                              echo form_button($data);
                              ?>
                        </div>
                     </div>
                     <?php echo form_close(); ?>
                     <?php if($getEmploydetails->invalid_status == 1){ ?>
                      <h5>Error Status:</h5>
                      <p class="error"><?php echo $getEmploydetails->export_status; ?></p>
                     <?php }?>
                    
                  </div>
               </div>
               <!-- /.card -->
            </div>
            <!--/.col (left) -->
         </div>
         <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<script type="text/javascript">


function desigtData(){
        
        var roleid = $('#role').val();
        var url = "<?php echo BASE_URL('common/getAjaxDesignation') ?>";
        var data = {roleid:roleid, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
            $.ajax({
                dataType: 'json',
                data: 'ajax',
                method: 'post',
                data: data,
                url: url,
                success: function (resp) {
                    var appendCheck = '<option value="">Select Designation</option>';
                    if(resp.status){
                        $.each(resp.chdatas,function(iKey,iVal){
                            var moduleId = <?php echo ($emplDesig)?$emplDesig:0; ?>;
                            var selVal = '';
                            <?php if($emplDesig !=0){ ?>
                            if(moduleId == iKey){
                                var selVal = 'selected';
                            }
                            <?php } ?>
                            if(iKey != ''){
                            appendCheck += '<option value='+iKey+' '+selVal+'>'+iVal+'</option>';
                        }
                        });
                    }
                   $("#desig").html(appendCheck);
                }
            });
   
}

    function unitData(){
        
            var vertid = $('#fk_ver_id').val();
            var url = "<?php echo BASE_URL('common/getAjaxSpecific') ?>";
            var data = {vertid:vertid, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
                $.ajax({
                    dataType: 'json',
                    data: 'ajax',
                    method: 'post',
                    data: data,
                    url: url,
                    success: function (resp) {
                        var appendCheck = '<option value="">Select Location</option>';
                        if(resp.status){
                            $.each(resp.chdatas,function(iKey,iVal){
                                var moduleId = <?php echo ($getunitname)?$getunitname:0; ?>;
                                var selVal = '';
                                <?php if($getunitname !=0){ ?>
                                if(moduleId == iKey){
                                    var selVal = 'selected';
                                }
                                <?php } ?>
                                if(iKey != ''){
                                appendCheck += '<option value='+iKey+' '+selVal+'>'+iVal+'</option>';
                            }
                            });
                        }
                       $("#fk_unit_id").html(appendCheck);
                    }
                });
       
    }


    function deptData(){
       var vertid = $('#fk_ver_id').val();
            var url = "<?php echo BASE_URL('common/getAjaxDepartment_edit') ?>";
            var data = {vertid:vertid, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
                $.ajax({
                    dataType: 'json',
                    data: 'ajax',
                    method: 'post',
                    data: data,
                    url: url,
                    success: function (resp) {
                        var appendCheck = '<option value="">Select Department</option>';
                        if(resp.status){
                            $.each(resp.deptdatas,function(iKey,iVal){
                                var moduleId = <?php echo ($getdeptname)?$getdeptname:0; ?>;
                                var selVal = '';
                                <?php if($getdeptname !=0){ ?>
                                if(moduleId == iKey){
                                    var selVal = 'selected';
                                }
                                <?php } ?>
                                if(iKey != ''){
                                appendCheck += '<option value='+iKey+' '+selVal+'>'+iVal+'</option>';
                            }
                            });
                        }
                       $("#fk_dept_id").html(appendCheck);
                    }
                });
       
    }
    




  
     $(document).ready(function () {


        $("#emp_date_of_birth").datepicker({
           autoclose: true,
           format: 'dd-mm-yyyy',
           // endDate: '-18y'
           endDate: '0'
       });

       $("#emp_joining_date").datepicker({
           autoclose: true,
           format: 'dd-mm-yyyy',
           endDate: '0'
       });

       $('#emp_phone').keyup(function () { 
          this.value = this.value.replace(/[^0-9\.]/g,'');
	
	
      });


        desigtData();
        unitData();
        deptData();
        
        $(document).on('change','#fk_ver_id',function(){
            unitData();
            deptData();
        });
        $(document).on('change','#role',function(){
            desigtData();
        });
       

   
       $.validator.addMethod('filesize', function (value, element, param) {
          
           return this.optional(element) || (Math.round(element.files[0].size / (1024 * 1024)) <= 5)
       }, 'File size must be less than 5MB');
   
       $.validator.addClassRules("compFile", {
   //            accept: "png|jpe?g|gif",
           required: false,
           filesize : true
       });
   
       $("#company-profile").validate({
           ignore: [],
           success: function (error) {
               error.removeClass("error");
               error.addClass("d-none");
           },
   
           rules: {
               "emp[emp_no]": {
                   required: true,
   //                    alphanumeric: true,
                   alphanumericwithspecialchar: true,
                  
               },
               "emp[emp_name]": {
                   required: true,
   //                     alphaspace: true,
                    // alphaspacewithspecialchar: true,
                   maxlength: 50,
                   minlength: 2
               },
               "emp[gender]" : {
                   required: true,
                 },
               "emp[nationality]" : {
                //    required: true,
                 },
                 "emp[role]" : {
                   required: true,
                 },
               "emp[desig]" : {
                   required: true,
                 },
               "emp[depart]" : {
                   required: true,
                 },
               "emp[location]" : {
                   required: true,
                 },
               "emp[emp_mail]": {
   //                    required: true,
                //   email: true
   
               },
              "emp[nationality_other]" : {
                   required: true,
                   alphaspace: true,
               },



               "emp[EMP_VERT_ID]" : {
                   required: true,
                 },
                 "emp[EMP_UNIT_ID]" : {
                   required: true,
                 },
                  "emp[EMP_DEPT_ID]" : {
                   required: true,
                 },

                 
                 
                 // "emp[EMP_BIRTH_DATE]" : {
                 //   required: true,
                 // },

                 // "emp[EMP_JOINING_DATE]" : {
                 //   required: true,
                 // },

                 "emp[PAST_EXPERIENCE]" : {
                //    required: true,
                   number : true,
                   maxlength:4,
                 },

                 "emp[PHONE_NUMBER]" : {
                //    required: true,
                   number : true,
                   minlength:5,
                   maxlength:15,
                 },

                
                
        
   
   
           },
           messages: {
               "emp[emp_no]": {
                   required: "Employee Number is required"
               },
               "emp[emp_name]": {
                   required: "Employee Name is required"
               },
               "emp[gender]": {
                   required: "Gender is required"
               },
               "emp[role]" : {
                   required: "Role is required",
                 },
               "emp[desig]": {
                   required: "Designation is required"
               },
               
               "emp[EMP_VERT_ID]": {
                   required: "Plant Name is required"
               },

               "emp[EMP_UNIT_ID]" : {
                   required: "Location Name is required"
                 },
                
                "emp[EMP_DEPT_ID]" : {
                   required: "Depatment Name is required"
                 },

                 "emp[EMP_BIRTH_DATE]" : {
                   required: "Date of Birth is required"
                 },

                 "emp[EMP_JOINING_DATE]" : {
                   required: "Joining Date is required"
                 },

                //  "emp[PAST_EXPERIENCE]" : {
                //    required: "Past Experience is required"
                //  },

                //  "emp[PHONE_NUMBER]" : {
                //    required: "Phone Number is required"
                //  },

                
            
   
           },
           errorPlacement: function (error, element) {
   
               if (element.attr('checkSelect') == 'select2') {
                   error.insertAfter(element.next('.select2-container'));
               } else {
                   error.insertAfter(element);
                   element.closest(".imgGroup").last().append(error);
               } 
           }
   
       });
   
   
   
   $(document).ready(function(){
       $("#nationality").on('change',function(){
           var natVal = $(this).val();
           var natOther = '<?php echo $emplNationother; ?>'
           if(natVal == 3){
               $(".otherNation").html('<input type="text" class="form-control" name="emp[nationality_other]" value="'+natOther+'">')
           }else{
               $(".otherNation").html('');
           }
        }).change();
       
       })
       
        
       
//        $(document).on('keyup','#emp_no',function(){
//         var empId = $(this).val();
//            var url = "<?php //echo BASE_URL('master/employee/getEmploid') ?>";
//                var data = {empId: empId,'<?php //echo $this->security->get_csrf_token_name(); ?>': '<?php //echo $this->security->get_csrf_hash(); ?>'}
          
          
//                    $.ajax({
//                        dataType : 'json',
//                        type : 'ajax',
//                        method : 'post',
//                        data : data,
//                        url : url,
//                        success: function(resp){
                          
//                           var Finstatus = resp.status;
//                           var Finmsg = resp.msg;
//                           if(Finstatus){
                             
//    //                               $("#errEmpno").html('<label id="emp_no-error" class="error" for="emp_no">'+Finmsg+'</label>');
//                               $("#errEmpno").html(Finmsg);
                             
//                           }else{
//    //                               $("#errEmpno").removeClass('error');
//                               $("#errEmpno").html('');
                             
//                           }
                        
//                        }
//                    });
//        });


   });







</script>
