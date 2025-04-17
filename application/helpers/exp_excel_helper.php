<?php

function exportExcel($data = array(), $title = 'content') {

        $aData = isset($data['export']) ? $data['export'] : [];
        $ci = & get_instance();
        $ci->load->library('PHPExcel');
        $object = new PHPExcel();
        $object->getActiveSheet()->setTitle($title);
        $object->setActiveSheetIndex(0);
        $column = 0;
        $fields = isset($data['heading']) ? $data['heading'] : [];

        if (count($fields) > 0) {
            foreach ($fields as $field) {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $fiName = getNameFromNumber($column);

                $object->getActiveSheet()->getStyle($fiName . '1')->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FFA500')
                            )
                        )
                );

                $column++;
            }
        }
        $excel_row = 2;

        if (count($aData) > 0) {
            foreach ($aData as $field) {

                if (isset($field['data'])) {
                    $col = 0;
                    foreach ($field['data'] as $row) {
                        $dVal = $row['value'];
                        $dType = $row['type'];
                        if ($dType == 'date') {
                            PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
                            $object->getActiveSheet()->setCellValueByColumnAndRow($col, $excel_row, $dVal);
                            $object->getActiveSheet()->getStyleByColumnAndRow($col, $excel_row)
                                    ->getNumberFormat()
                                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
                        } else {
                            $object->getActiveSheet()->setCellValueByColumnAndRow($col, $excel_row, $dVal);
                        }

                        $col++;
                    }
                }

                $excel_row++;
            }
        }
        // exit;
        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel;');
        header('Content-Disposition: attachment;filename="' . $title . '_' . date('dMy') . '.xls"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $object_writer->save('php://output');
        exit;
    }

