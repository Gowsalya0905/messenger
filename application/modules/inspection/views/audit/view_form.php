<?php
global $owner_list, $owner_engineer_list, $EPC_list, $audit_status;
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
<style>
    #map {
        height: 360px;
        width: 100%;
    }

    .keyContdetails {
        border: 2px solid #1e6dab;
    }
</style>

<div class="wrapper">
    <div class="content-wrapper">
        <section class=" content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <h3 class="card-title"><?php echo $formTitle; ?></h3>
                                    <a href="<?php echo BASE_URL('inspection/audit/inspection_list') ?>"><button type="button" class="btn btn-primary backBtns">Back</button></a>
                                </div>
                            </div>
                            <div class="card ">
                                <div class="card-body">
                                    <div class="row viewpage">
                                        <div class="col-12 table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <div class="card-header card-header-inner">
                                                        Basic Details<span class="float-right"></span>
                                                    </div>
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

                                            <table class="table table-striped table-bordered">
                                                <thead style="background: #94960ae0; font-weight: bold; color:#fff; text-align:center;">
                                                    <tr style="background: #5a5a59 !important;">
                                                        <td style="width:5%">S.NO.</td>
                                                        <td style="width:20%;">Items to be checked</td>
                                                        <td style="width:40%">Guidance</td>
                                                        <td style="width:10%">Outcome</td>
                                                        <td style="width:40%">Description</td>
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
                                                                <td colspan="4" class="text-left p-3" style="border: 1px solid lightgray;font-size:12px"><?= htmlspecialchars($subcategory); ?></td>
                                                            </tr>

                                                            <?php foreach ($items as $key => $item): ?>

                                                                <tr class="border-top border-secondary" style="border: 1px solid lightgray;">
                                                                    <?php
                                                                    $slno[$item->fk_item_subcatdata_id] = $key + 1;
                                                                    $description[$item->fk_item_subcatdata_id] = $item->insp_desc; ?>
                                                                    <td class="text-left align-middle" style="border: 1px solid lightgray; "><?= $key + 1; ?></td>
                                                                    <td class="text-left align-middle" style="border: 1px solid lightgray; "><?= $item->subcategorydata; ?></td>
                                                                    <td class="text-left align-middle" style="border: 1px solid lightgray; "><?= $item->guidance; ?></td>
                                                                    <td class="text-center align-middle" style="border: 1px solid lightgray; "><?= $audit_status[$item->insp_item_condition]; ?></td>
                                                                    <td class="text-left align-middle" style="border: 1px solid lightgray; "><?= $item->insp_desc; ?></td>
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
                                            <div class="row" style="margin-top:20px">
                                                <div class="col-md-12">
                                                    <h4>Evidence Attachments</h4>
                                                </div>
                                            </div>

                                            <div class="imgDiv">
                                                <div>
                                                    <table class="table table-bordered supervisorTable">
                                                        <thead style="background: #5a5a59;font-weight: bold;color:#ffff;text-align:center;">
                                                            <th style="width:5%">S.NO</th>
                                                            <th style="text-align:center;width:50%">Evidence Images</th>
                                                            <th style="width:35%;text-align:center;">Description</th>
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
                                                                                <img src="<?php echo BASE_URL($image->insp_file_path); ?>" alt="Evidence Image" style="width: 3cm; height: 2cm; margin-right: 5px;">
                                                                            <?php endforeach; ?>
                                                                        </td>
                                                                        <td class=" align-middle" style="border: 1px solid lightgray;"><?php echo htmlspecialchars($descriptions); ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="3" style="text-align: center; border: 1px solid lightgray;">No data available</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>