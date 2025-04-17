<?php
$logo = '<img src="' . PDF_IMG_PATH . 'company_logo/Logo.png" style="width:200px;height:100px;">';
$logo1 = '<img src="' . PDF_IMG_PATH . 'company_logo/LOGO-LUMUT-PORTju.png" style="float:right;width:200px;height:35px;">';
?>
<?php
//echo "<pre>";
//print_r($getEmploydetails);
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
?>

<html>

<head>
    <style>
        * {
            font-family: calibri;
            color: #333;
            font-size: 11px;
            line-height: 15px;
        }

        @page {
            size: auto;
            margin-header: 0mm;
            margin-footer: 3mm;
            odd-header-name: html_myHeader1;
            even-header-name: html_myHeader2;
            odd-footer-name: html_myFooter1;
            even-footer-name: html_myFooter2;
        }

        @page noheader {
            odd-header-name: _blank;
            even-header-name: _blank;
            odd-footer-name: _blank;
            even-footer-name: _blank;
        }

        .full-width {
            width: 100%;
            font-size: 11px;
        }

        .bg-grey {
            background-color: #f1f1f1;
            border: 1px solid #333;
        }

        .padding-5 {
            padding: 5px;
        }

        .innertable td {
            padding: 5px 10px 5px;
        }

        .max-width {
            width: 95%;
            margin: 0 auto;
        }

        .bg-light-gr {
            background: #00bcd5;
            color: #fff;
        }

        .bg-light-green {
            background: #8cc34b;
            color: #fff;
        }

        .bg-dark-green {
            background: #009688;
            color: #fff;
        }

        .witness {
            margin-bottom: 20px;
        }

        .witness.even tr td .btn {
            background: #8cc34b !important;
            color: #fff;
        }

        .witness.odd tr td .btn {
            background: #009688 !important;
            color: #fff;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .bg-light-blue-active {
            background-color: #357ca5 !important;
        }

        .pull-right {
            text-align: right;
        }

        .innertable {
            margin-bottom: 10px;
        }

        h3 {
            font-size: 0.9em;
            font-weight: bold;
        }

        .uppertext {
            text-transform: uppercase;
        }

        p {
            font-size: 11px;
        }

        p.desc {
            font-size: 1em;
            text-indent: 30px !important;
        }

        .table {
            border: 1px solid #bfbebe;
        }

        .inj_per tr:nth-child(odd) {
            background-color: rgba(63, 147, 187, 0.10) !important;
        }

        .inj_det tr:nth-child(odd) {
            background-color: rgba(59, 188, 212, 0.10) !important;
        }

        .witns tr:nth-child(odd) {
            background-color: rgba(241, 146, 52, 0.10) !important;
        }

        .summ tr:nth-child(odd) {
            background-color: rgb(228, 228, 228) !important;
        }

        .table td {
            padding: 6px;
        }

        .boxtitle {

            font-weight: bold;
            color: #333 !important;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {

            text-align: justify;
        }

        .texthide {
            display: none;
        }

        body {
            color-adjust: exact;
            -webkit-print-color-adjust: exact;

        }

        .label_high {
            background-color: #ff0000 !important;

        }

        .label_low {
            background-color: #008f11 !important;
        }

        .label_medium {
            background-color: #f2ff00 !important;
        }

        .labels {

            padding: 10px;
            font-weight: bold;

            text-transform: uppercase;
        }


        .web {

            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            line-height: 25px
        }

        .web td {
            font-size: 11px;
            border: 1px solid #D9E9F4;
            padding: 5px 5px 5px 5px;
        }

        .web tr:nth-child(odd) {
            background-color: rgba(63, 147, 187, 0.10) !important;
        }

        .web th {
            font-size: 12px;
            text-align: left;
            padding: 3px 0px 3px 10px;
            color: #ffffff;
            background-color: #316191;
            border: 1px solid #316191;
        }

        .web tr.alt td {
            color: #000000;
            background-color: #316191;
        }



        .panelheading {
            font-size: 11px;
        }

        .panel-body {
            padding: 15px;
        }

        .col-md-3-p {
            width: 22% !important;
        }

        .col-md-12 {
            width: 100% !important;
        }

        .box-header {

            display: block;
            padding: 10px;
            position: relative;
        }

        .box-header.with-border {
            border-radius: 0px !important;
        }

        .box-header.with-border {
            border-bottom: 1px solid #007cb7 !important;
        }

        .col-print {
            padding: 20px !important;
        }

        .row {
            margin-right: -10px;
            margin-left: -10px;
        }

        .col-md-6-p {
            width: 42% !important;
        }

        .web td p.fjust {
            text-align: justify !important;
        }

        .web td h5.fright {

            text-align: right !important;
        }

        .txt-uppercase {
            text-transform: uppercase;

        }

        .col-md-3-pdf {
            margin: 5px;
            float: left;
            width: 45% !important;
            border: 2px solid #000;
            padding: 5px;
        }

        .tab-border table,
        .tab-border table th,
        .tab-border table td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tab-border table th,
        .tab-border table td {
            padding: 15px;
        }

        .fsize {
            font-size: 11px;
        }

        .doc-border table,
        .doc-border table th,
        .doc-border table td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        .doc-border table th,
        .doc-border table td {
            padding: 5px;
        }

        .tcenter {
            text-align: center;
        }
    </style>
</head>

<body style="font-family:'arial';">
    <htmlpageheader name="myHeader1" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold">EMPLOYEE REPORT</td>


            </tr>

        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $emploentID; ?></td>
            </tr>
        </table>

    </htmlpageheader>
    <htmlpageheader name="myHeader2" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold">EMPLOYEE REPORT</td>


            </tr>

        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $emploentID; ?></td>
            </tr>
        </table>

    </htmlpageheader>

    <htmlpagefooter name="myFooter1" style="display:none">
        <table width="100%" style="width:100%;border:0;background-color: #FFF;border-top: 4px solid #000;padding-top:10px;padding-bottom:10px;">
            <tr>
                <td width="33%">
                    <span style="font-style: italic;">{DATE d-m-Y}</span>
                </td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">

                </td>
                <td width="33%" style="text-align: right;">
                    {PAGENO}/{nbpg}
                </td>
            </tr>
        </table>
    </htmlpagefooter>

    <htmlpagefooter name="myFooter2" style="display:none">
        <table width="100%" style="width:100%;border:0;background-color: #FFF;border-top: 4px solid #000;padding-top:10px;padding-bottom:10px;">
            <tr>
                <td width="33%">My document</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
            </tr>
        </table>
    </htmlpagefooter>

    <table style="width:100%;padding-bottom: 10px;">
        <tr>
            <td style="width:100%;background-color: #2e2c7f;color:#FFF;font-weight:bold;display: inline-block;
                padding: 5px 5px 5px;">
                BASIC DETAILS
            </td>

        </tr>
    </table>

    <table style="width:100%;" class="table summ1 full-width">
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
            <td width="23%"><b>Department Name</b></td>
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

    </table>



</body>

</html>