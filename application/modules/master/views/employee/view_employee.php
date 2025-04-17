<style>
    .emplBorder {
        background-color: #007bff;
    }
</style>

<?php
// echo "<pre>";
// print_r($getEmploydetails);
//exit;
$emploID = postData($getEmploydetails, 'EMP_AUTO_ID');
$emploentID = postData($getEmploydetails, 'EMP_ID');
$emplName = postData($getEmploydetails, 'EMP_NAME');
$emplGender = postData($getEmploydetails, 'GENDER_NAME');
$emplNation = postData($getEmploydetails, 'NATIONALITY_NAME');
$emplNationother = postData($getEmploydetails, 'EMP_NATIONALITY_OTHER');
$emplDesig = postData($getEmploydetails, 'DESIGNATION_NAME');
$emplDept = postData($getEmploydetails, 'DEPT_NAME');
$emplTerminal = postData($getEmploydetails, 'TERMINAL_NAME');
$emplMail = postData($getEmploydetails, 'EMP_EMAIL_ID');

$emp_date_of_birth = postData($getEmploydetails, 'EMP_BIRTH_DATE');
$emp_joining_date = postData($getEmploydetails, 'EMP_JOINING_DATE');
$emp_past = postData($getEmploydetails, 'PAST_EXPERIENCE');
$emp_phone = postData($getEmploydetails, 'PHONE_NUMBER');
$emp_type = postData($getEmploydetails, 'EMP_TYPE');
$reportingId = postData($getEmploydetails, 'MANAGER_ID');


?>
<style>

</style>
<div class="wrapper">




    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">


                        <!-- general form elements -->
                        <div class="card card-headprimary">

                            <div class="card-header">
                                <h4 class="card-title">Employee Details</h4>
                                <a href="<?php echo BASE_URL('master/employee') ?>" class="btn btn-sm btn-primary float-right locatnTyptarget"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>


                            <div class="card-body">




                                <div class="invoice p-3 mb-3">
                                    <div class="card-header emplBorder">
                                        <h3 class="card-title" style="color: #ffff;">Basic Details</h3>
                                    </div>
                                    <div class="row">


                                        <div class="col-12 table-responsive">
                                            <table class="table table-striped" style="">
                                                <tbody>
                                                    <tr>
                                                        <td width="23%"><b>Employee No</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $emploentID; ?></td>
                                                        <td width="23%"><b>Employee Type</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->emp_type_name; ?></td>

                                                    </tr>

                                                    <tr>
                                                        <td width="23%"><b>Employee Name</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $emplName; ?></td>
                                                        <td width="23%"><b>Email ID</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->EMP_EMAIL_ID; ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td width="23%"><b>Date of Birth</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->EMP_BIRTH_DATE; ?></td>
                                                        <td width="23%"><b>Joining Date</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->EMP_JOINING_DATE; ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td width="23%"><b>Past Experience</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->PAST_EXPERIENCE; ?></td>
                                                        <td width="23%"><b>Phone Number</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->PHONE_NUMBER; ?></td>
                                                    </tr>



                                                    <tr>
                                                        <td width="23%"><b>Gender</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo ($getEmploydetails->EMP_GENDER == '1') ? 'MALE' : 'FEMALE'; ?></td>
                                                        <td width="23%"><b>Nationality</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo ($getEmploydetails->EMP_NATIONALITY == '1') ? 'Indian' : 'Other'; ?></td>
                                                    </tr>



                                                    <tr>
                                                        <td width="23%"><b>IC or Passport Number</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->IC_PassportNumber; ?></td>
                                                        <td width="23%"><b>Company Name</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->comp_name; ?></td>

                                                    </tr>
                                                    <tr>
                                                        <td width="23%"><b>Area Name</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->area_name; ?></td>
                                                        <td width="23%"><b>Building</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->building_name; ?></td>

                                                    </tr>
                                                    <tr>
                                                        <td width="23%"><b>Department</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->dept_name; ?></td>
                                                        <td width="23%"><b>Role Name</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->role_name; ?></td>

                                                    </tr>



                                                    <tr>

                                                        <td width="23%"><b>Designation</b></td>
                                                        <td>:</td>
                                                        <td width="23%"><?php echo $getEmploydetails->design_name; ?></td>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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