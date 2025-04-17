<?php
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
$emplCertiname = postData($getEmploydetails, 'CERT_NAME');
// if ($getEmploydetails->CERT_START_DATE != '') {
//     $emplStartdate = date('d-m-Y', strtotime($getEmploydetails->CERT_START_DATE));
// } else {
//     $emplStartdate = '';
// }
// if ($getEmploydetails->CERT_END_DATE != '') {
//     $emplEnddate = date('d-m-Y', strtotime($getEmploydetails->CERT_END_DATE));
// } else {
//     $emplEnddate = '';
// }
// $emplCertpath = postData($getEmploydetails, 'CERT_PATH');
// $emplCertimgname = postData($getEmploydetails, 'CERT_FILE_NAME');
// $emplCertimgext = postData($getEmploydetails, 'CERT_EXT');
// $emplCertStatus = postData($getEmploydetails, 'CERT_STATUS');

// if ($emplCertStatus == 'Y') {
//     $finStatus = 'Eligible';
// } else {
//     $finStatus = 'Not Eligible';
// }
// $compCertFilepath = '';
// if ($emplCertimgname != '') {
//     $compCertFilepath = $emplCertpath . $emplCertimgname;
// }
?>
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
                        <td width="23%"><b>Gender</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->EMP_GENDER == '1') ? 'MALE' : 'FEMALE'; ?></td>

                    </tr>

                    <tr>
                        <td width="23%"><b>Email ID</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->EMP_EMAIL_ID) ? $getEmploydetails->EMP_EMAIL_ID : ' - '; ?></td>
                        <td width="23%"><b>Date of Birth</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->EMP_BIRTH_DATE; ?></td>

                    </tr>

                    <tr>
                        <td width="23%"><b>Joining Date</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->EMP_JOINING_DATE; ?></td>
                        <td width="23%"><b>Past Experience</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->PAST_EXPERIENCE) ? $getEmploydetails->PAST_EXPERIENCE : ' - '; ?></td>

                    </tr>



                    <tr>
                        <td width="23%"><b>Phone Number</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->PHONE_NUMBER)? $getEmploydetails->PHONE_NUMBER: ' - '; ?></td>
                        <td width="23%"><b>Nationality</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->EMP_NATIONALITY == '1') ? 'Indian' : 'Other'; ?></td>
                    </tr>





                    <tr>
                        <td width="23%"><b>Ic or Passport Number</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->IC_PassportNumber)? $getEmploydetails->IC_PassportNumber: ' - '; ?></td>

                        <td width="23%"><b>Role Name</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->role_name; ?></td>

                    </tr>




                    <tr>

                        <td width="23%"><b>Designation</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->design_name; ?></td>
                        <td width="23%"><b></b></td>
                        <td></td>
                        <td width="23%"></td>

                    </tr>

                    <tr>
                        <td width="23%"><b>Company Name</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->company_name; ?></td>
                        <td width="23%"><b>Area Name</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->area_name; ?></td>
                    </tr>
                    <tr>
                        <td width="23%"><b>Building/Block/Direction</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo $getEmploydetails->building_name; ?></td>
                        <td width="23%"><b>Department</b></td>
                        <td>:</td>
                        <td width="23%"><?php echo ($getEmploydetails->department_name) ? $getEmploydetails->department_name : '-'; ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>