<?php
$logo = '<img src="' . PDF_IMG_PATH . 'company_logo/Logo.png" style="width:200px;height:100px;">';

global $owner_list, $owner_engineer_list, $EPC_list, $initial_status;
$InsId = isset($inspData->ins_id) && !empty($inspData->ins_id) ? $inspData->ins_id : '-';
$Reporter = isset($inspData->Reporter) && !empty($inspData->Reporter) ? $inspData->Reporter : '-';
$ReporterDesig = isset($inspData->desig) && !empty($inspData->desig) ? $inspData->desig : '-';
$reportedDateTime = isset($inspData->ins_report_datetime) && !empty($inspData->ins_report_datetime) ? $inspData->ins_report_datetime : '-';
$owner = isset($inspData->ins_owner_id) && !empty($inspData->ins_owner_id) ? $owner_list[$inspData->ins_owner_id] : '-';
$ownerEng = isset($inspData->ins_owner_id) && !empty($inspData->ins_owner_id) ? $owner_engineer_list[$inspData->ins_owner_eng] : '-';
$Epc = isset($itemData[0]->insp_epc_id) && !empty($itemData[0]->insp_epc_id) ? $EPC_list[$itemData[0]->insp_epc_id] : '-';
$company = isset($inspData->comp_name) && !empty($inspData->comp_name) ? $inspData->comp_name : '-';
$area = isset($inspData->area_name) && !empty($inspData->area_name) ? $inspData->area_name : '-';
$building = isset($inspData->building_name) && !empty($inspData->building_name) ? $inspData->building_name : '-';
$department = isset($inspData->dep_name) && !empty($inspData->dep_name) ? $inspData->dep_name : '-';
$project = isset($inspData->proj_name) && !empty($inspData->proj_name) ? $inspData->proj_name : '-';
$category = isset($inspData->category) && !empty($inspData->category) ? $inspData->category : '-';
$InspDate = isset($inspData->ins_date) && !empty($inspData->ins_date) ? $inspData->ins_date : '-';
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

<body style="font-family:'calibri';">
    <htmlpageheader name="myHeader1" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold"><?= $title; ?></td>
            </tr>
        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $InsId; ?></td>
            </tr>
        </table>

    </htmlpageheader>

    <htmlpageheader name="myHeader2" style="display:none">
        <table border="0" style="width:100%;border:0;background-color: #FFF;padding-top:10px;padding-bottom:10px;">
            <tr style="">
                <td border="0" style="width:20%;float:left;text-align:left;"><?php echo $logo; ?></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 24px;font-weight: bold">Inspection Details</td>
            </tr>

        </table>
        <table border="0" style="width:100%;border:0;border-top: 4px solid #000;">
            <tr>
                <td border="0" style="width:30%;"></td>
                <td border="0" style="width:70%;float:right;text-align:right;font-size: 12px;font-weight: bold"><?php echo $InsId; ?></td>
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


    <table style="width:100%;" class="table summ full-width innertable">
        <thead>
            <tr>
                <td colspan="6" style="width:100%;background-color: #004EA3  !important;color:#fff;font-weight:bold; font-size:13px;display: inline-block;
                padding: 5px 5px 5px; text-align: center;">
                    Basic Details
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tdlabel"><b>Inspection ID</b></td>
                <td>:</td>
                <td><?php echo $InsId; ?></td>
                <td class="tdlabel"><b>Reporter Name</b></td>
                <td>:</td>
                <td><?php echo $Reporter; ?></td>
            </tr>

            <tr>
                <td class="tdlabel"><b>Reporter's Designation</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $ReporterDesig; ?></td>
                <td class="tdlabel"><b>Reported Date and Time</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $reportedDateTime; ?></td>
            </tr>
            <tr>
                <td class="tdlabel"><b>Owner </b></td>
                <td class="title-colon">:</td>
                <td> <?php echo $owner; ?></td>
                <td class="tdlabel"><b>Owner Engineer Name</b></td>
                <td class="title-colon">:</td>
                <td> <?php echo $ownerEng; ?> </td>
            </tr>
            <tr>
                <td class="tdlabel"><b>EPC</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $Epc; ?></td>
                <td class="tdlabel"><b>Company</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $company; ?>
                </td>
            </tr>

            <tr>
                <td class="tdlabel"><b>Area</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $area; ?></td>
                <td class="tdlabel"><b>Building/Block/Direction </b></td>
                <td class="title-colon">:</td>
                <td><?php echo $building; ?></td>
            </tr>

            <tr>
                <td class="tdlabel"><b>Department</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $department; ?></td>
                <td class="tdlabel"><b>Project</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $project; ?></td>
            </tr>


            <tr>
                <td class="tdlabel"><b> Category</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $category; ?></td>
                <td class="tdlabel"><b>Date of Inspection</b></td>
                <td class="title-colon">:</td>
                <td><?php echo $InspDate; ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-striped table-bordered" style="border-collapse: collapse; border: 1px solid lightgray;">
        <thead style="background-color: #004EA3 !important; color: #fff !important; font-size:10px !important;">
            <tr>
                <th style="background-color: #004EA3 !important; color: #fff; width: 10%; padding: 5px; text-align: center; border: 1px solid lightgray; font-size:12px;">S.NO.</th>
                <th style="background-color: #004EA3 !important; color: #fff; width: 30%; padding: 5px; text-align: center; border: 1px solid lightgray; font-size:12px;">Items to be checked</th>
                <th style="background-color: #004EA3 !important; color: #fff; width: 15%; padding: 5px; text-align: center; border: 1px solid lightgray; font-size:12px;">Item Status</th>
                <th style="background-color: #004EA3 !important; color: #fff; width: 15%; padding: 5px; text-align: center; border: 1px solid lightgray; font-size:12px;">Action</th>
            </tr>
        </thead>

        <tbody id="subcategory_table" style="border: 1px solid lightgray;">
            <?php if (!empty($itemData) && is_array($itemData)): ?>

                <?php
                $groupedItems = [];
                $slno = [];
                $description = [];
                foreach ($itemData as $item) {
                    $groupedItems[$item->subcategory][] = $item;
                }

                foreach ($groupedItems as $subcategory => $items): ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="4" class="text-center p-3" style="border: 1px solid lightgray;font-size:12px"><?= htmlspecialchars($subcategory); ?></td>
                    </tr>

                    <?php foreach ($items as $key => $item): ?>

                        <tr class="border-top border-secondary" style="border: 1px solid lightgray;">
                            <?php
                            $slno[$item->fk_item_subcatdata_id] = $key + 1;
                            $description[$item->fk_item_subcatdata_id] = $item->insp_desc; ?>
                            <td class="text-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?= $key + 1; ?></td>
                            <td class="text-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?= $item->subcategorydata; ?></td>
                            <td class="t-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?= $initial_status[$item->insp_item_condition]; ?></td>
                            <td class="text-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?= $item->insp_desc; ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; border: 1px solid lightgray;">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="table table-striped table-bordered" style="border-collapse: collapse; border: 1px solid lightgray;">
        <thead style="background: #94960ae0; font-weight: bold; color:#fff; text-align:center;">
            <tr style="background: #5a5a59;">
                <td style="width: 5%; background-color: #004EA3; color: #fff; font-weight: bold; font-size:12px; padding: 5px; text-align: center; border: 1px solid lightgray;">S.NO.</td>
                <td style="width: 40%; background-color: #004EA3; color: #fff; font-weight: bold; font-size:12px; padding: 5px; text-align: center; border: 1px solid lightgray;">Evidence Images</td>
                <td style="width: 20%; background-color: #004EA3; color: #fff; font-weight: bold; font-size:12px; padding: 5px; text-align: center; border: 1px solid lightgray;">Description</td>
            </tr>
        </thead>
        <tbody id="subcategory_table">
            <?php if (!empty($evidenceData) && is_array($evidenceData)): ?>
                <?php
                $groupedEvidence = [];
                foreach ($evidenceData as $image) {
                    $groupedEvidence[$image->fk_img_subcatdata_id][] = $image;
                }

                foreach ($groupedEvidence as $fk_subcatdata_id => $images):
                    $sno = $slno[$fk_subcatdata_id];
                    $descriptions = $description[$fk_subcatdata_id];
                ?>
                    <tr>
                        <td class="text-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?php echo $sno; ?></td>
                        <td class="text-center align-middle" style="border: 1px solid lightgray;">
                            <?php foreach ($images as $image): ?>
                                <img src="<?php echo $image->insp_file_path; ?>" alt="Evidence Image" style="width: 3cm; height: 2cm; margin-right: 5px;">
                            <?php endforeach; ?>
                        </td>
                        <td class="text-center align-middle" style="border: 1px solid lightgray; font-size:12px"><?php echo htmlspecialchars($descriptions); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center; border: 1px solid lightgray;">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>