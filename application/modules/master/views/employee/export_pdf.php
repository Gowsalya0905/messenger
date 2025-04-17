<?php
$logo = '<img src="' . PDF_IMG_PATH . 'company_logo/Logo.png" style="width:200px;height:100px;">';

?>

<html>

<head>
    <style>
        @page {
            size: auto;
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

        .table {
            width: 100%;
        }

        body {

            font-size: 13px
        }

        table {
            border-collapse: collapse;

        }
    </style>

</head>

<body>
    <htmlpageheader name="myHeader1" style="display:block;">
        <table border="0" style="width:100%;border:0;border-bottom: 4px solid #000;background-color: #FFF;">
            <tr style="vertical-align: middle;">
                <td border="0" style="width:50%;float:left;text-align:left;vertical-align: middle;"><?php echo $logo; ?></td>
                <td border="0"
                    style="width:50%;float:right;text-align:right;font-size: 24px;font-weight:bold;font-family: Georgia, serif;vertical-align: middle;">
                    <?php echo $pagetitle; ?>
                </td>
            </tr>
        </table>


    </htmlpageheader>


    <htmlpagefooter name="myFooter1" style="display:none">
        <table width="100%"
            style="width:100%;border:0;background-color: #FFF;border-top: 4px solid #000;padding-top:10px;padding-bottom:10px;">
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


    <div style="width:100%;">
        <table class="table" style="width:100%;border: 0.5px solid;">
            <thead>
                <tr style="background-color: #f2f2f2;">

                    <?php
                    foreach ($header as $key => $value) { ?>
                        <td style='padding: 7px;border: 0.5px solid;font-weight:bold;text-align:center;'>
                            <?php echo $value; ?>
                        </td>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>

                <?php
                $i = 1;
                foreach ($content as $key => $value) {

                    if ($value->EMP_LOGIN_STATUS == 'P') {
                        $status = 'Pending';
                    } else if ($value->EMP_LOGIN_STATUS == 'E') {
                        $status = 'Activated';
                    } else if ($value->EMP_LOGIN_STATUS == 'D') {
                        $status = 'De-Activated';
                    }
                ?>
                    <tr>
                        <td style='padding: 7px;border: 0.5px solid;text-align:center'>
                            <?php echo  $i; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->EMP_ID; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->EMP_NAME; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->comp_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->area_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->building_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->dept_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->role_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->design_name; ?>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $value->created_on; ?>
                        </td>
                        </td>
                        <td style='padding: 7px;border: 0.5px solid'>
                            <?php echo  $status; ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                } ?>
            </tbody>
        </table>
        <br>
    </div>
</body>

</html>