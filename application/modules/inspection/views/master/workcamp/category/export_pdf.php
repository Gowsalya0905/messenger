<?php
    $logo = '<img src="' . PDF_IMG_PATH . 'company_logo/Logo.png" style="width:100px;height:100px;">';

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
                <td border="0" style="width:50%;float:left;text-align:left;vertical-align: middle;"><?php echo $logo ; ?></td>
                <td border="0"
                    style="width:50%;float:right;text-align:right;font-size: 24px;font-weight:bold;font-family: Georgia, serif;vertical-align: middle;">
                     <?php  echo $pagetitle; ?>
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
                    <th style="padding: 7px;border: 0.5px solid;font-weight:bold;text-align:center;">
                        <?php echo $value; ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php
            $i = 1;
            foreach ($content as $row) {
                // print_r($content);
                $currentDateandTime = date("Y-m-d H:i:s");
            ?>
                <tr>
                    <td style="padding: 7px;border: 0.5px solid;text-align:center">
                        <?php echo $i; ?>
                    </td>
                    <!-- <td style="padding: 7px;border: 0.5px solid">
                        <?php // echo $row->category_un_id; ?>
                    </td> -->
                    <td style="padding: 7px;border: 0.5px solid">
                        <?php echo $row->category; ?>
                    </td>
                    <td style="padding: 7px;border: 0.5px solid">
                        <?php echo date('d-m-Y', strtotime($row->created_on)); ?>
                    </td>
                </tr>
            <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
    <br>
</div>

</body>

</html>