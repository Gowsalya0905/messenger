<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        error_reporting(1);
        isLogin();
    }

    // Monthly category start
    public function category()
    {

        $data = array(
            'view_file' => 'inspection/master/monthly/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/category_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function category_list()
    {

        ini_set("display_errors", 1);
        $table = INSP_MONTH_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';

        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_category/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));


                $rows = [];
                // $rows[] = $i;  
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_category($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];

        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_MONTH_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/monthly/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_category()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_MONTH_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_category()
    {
        $getrespdatas = $this->input->post('main_mas');

        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');
            $other_image = $this->input->post('other_user_img');

            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_MONTH_CAT, 'id', 'id', $uniqcomidwhr);

            $imgDatasother = [];

            if (isset($_FILES) && !empty($_FILES) && isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])) {
                $imgDatasother = uploadImage($filename = 'category_image', $imgUploadpath = 'assets/images/modules/inspection/images/category', $allowedTyp = "png|jpg|jpeg");
                if ($other_image) {
                    $existingImagePath = $other_image;
                    $existingImageName = basename($existingImagePath);

                    $existingImageFullPath = FCPATH . 'assets/images/modules/inspection/images/category/' . $existingImageName;

                    if (file_exists($existingImageFullPath)) {
                        unlink($existingImageFullPath);
                    }
                }
            } else if ($other_image) {
                $imgDatasother = $other_image;
            } else {
                $imgDatasother = '';
            }


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),
                    'category_image' => $imgDatasother,
                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),
                    'category_image' => $imgDatasother,
                ];
            }

            $updCategory = $this->common_model->updateData(INSP_MONTH_CAT, $respdatas, $updtWhr);

            if (($updCategory)) {
                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function category_exportpdf()
    {
        $table = INSP_MONTH_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Monthly Category List",
        ];

        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        $html = $this->load->view('inspection/master/monthly/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        $filename = "Monthly_Category_List.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function category_exportexcel()
    {

        $table = INSP_MONTH_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        $exportData = [];

        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        SimpleExcelWriter::streamDownload('Monthly Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Monthly Sub category start
    public function subcategory()
    {
        $data = array(
            'view_file' => 'inspection/master/monthly/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/subcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function subcategory_list()
    {
        $table = INSP_MONTH_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_subcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_subcategory($id = "")
    {
        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_MONTH_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_MONTH_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/monthly/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getSubcategories()
    {
        $categoryId = $this->input->post('category_id');

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_MONTH_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_subcategory()
    {
        $deleteId = $this->input->post('delid');

        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_MONTH_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_subcategory($id = '')
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_MONTH_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_MONTH_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_MONTH_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function subcategory_exportpdf()
    {
        $table = INSP_MONTH_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Monthly SubCategory List",
        ];

        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        $html = $this->load->view('inspection/master/monthly/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        $filename = "monthly_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function subcategory_exportexcel()
    {
        $table = INSP_MONTH_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        SimpleExcelWriter::streamDownload('Monthly Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Monthly Sub category Data start
    public function subcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_MONTH_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/monthly/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/subcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function getSubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_MONTH_SUBCAT)->result_array();

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_subcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_MONTH_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_MONTH_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/monthly/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_common_template($data);
    }

    public function subcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_MONTH_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');

        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_MONTH_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                $action .= " " . anchor('inspection/master/add_subcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_subcategorydata()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_MONTH_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];

        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_MONTH_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_MONTH_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_subcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_MONTH_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function subcategorydata_exportexcel()
    {

        $table = INSP_MONTH_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_MONTH_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []
        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        SimpleExcelWriter::streamDownload('Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); 
    }

    public function subcategorydata_exportpdf()
    {

        $table = INSP_MONTH_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_MONTH_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_MONTH_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []
        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);
    
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Monthly SubCategoryData List",
        ];

        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        $html = $this->load->view('inspection/master/monthly/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        $filename = "monthly_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }




    



    // Weekly category start
    public function weeklycategory()
    {

        $data = array(
            'view_file' => 'inspection/master/weekly/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/weeklycategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function weeklycategory_list()
    {


        $table = INSP_WEEKLY_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';



        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_weeklycategory/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_weeklycategory($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];


        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/weekly/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_weeklycategory()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_WEEKLY_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_weeklycategory()
    {
        // ini_set("display_errors",1);

        $getrespdatas = $this->input->post('main_mas');

        // echo '<pre>'.print_r($getrespdatas,true).'</pre>';
        // exit;
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');



            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_WEEKLY_CAT, 'id', 'id', $uniqcomidwhr);


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),

                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),

                ];
            }

            // log_message('debug',print_r($respdatas,true));

            $updCategory = $this->common_model->updateData(INSP_WEEKLY_CAT, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function weeklycategory_exportpdf()
    {
        // Fetching data from the database
        $table = INSP_WEEKLY_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Weekly Category List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/weekly/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "weekly_category_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function weeklycategory_exportexcel()
    {
        // Fetching data from the database
        $table = INSP_WEEKLY_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Weekly Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Weekly Sub category start
    public function weeklysubcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/weekly/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/weeklysubcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function weeklysubcategory_list()
    {
        // ini_set("display_errors", 1);
        $table = INSP_WEEKLY_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_weeklysubcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_weeklysubcategory($id = "")
    {

        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_WEEKLY_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/weekly/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getweeklySubcategories()
    {
        $categoryId = $this->input->post('category_id');


        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_WEEKLY_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_weeklysubcategory()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_WEEKLY_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_weeklysubcategory()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_WEEKLY_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_WEEKLY_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_WEEKLY_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function weeklysubcategory_exportpdf()
    {
        $table = INSP_WEEKLY_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Weekly SubCategory List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/weekly/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "weekly_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function weeklysubcategory_exportexcel()
    {
        $table = INSP_WEEKLY_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Weekly Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Weekly Sub category Data start
    public function weeklysubcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/weekly/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/weeklysubcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function getweeklySubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        // log_message('DEBUG', 'data' . print_r($categoryId .true));
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_WEEKLY_SUBCAT)->result_array();

            // log_message('DEBUG', 'data' . print_r($subcategories . true));

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_weeklysubcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');


        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_WEEKLY_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/weekly/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        // echo '<pre>';
        // log_message('debug', print_r($this->db->last_query()));
        // log_message('debug', print_r($data,true));
        // exit();
        $this->template->load_common_template($data);
    }

    public function weeklysubcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_WEEKLY_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');


        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WEEKLY_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        // log_message('debug', print_r($this->db->last_query(), true));

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                //    echo '<pre>'.print_r($id) 

                $action .= " " . anchor('inspection/master/add_weeklysubcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_weeklysubcategorydata()
    {

        // log_message('debug',print_r($this->input->post(),true));
        // exit;

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_WEEKLY_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];



        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_WEEKLY_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_WEEKLY_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_weeklysubcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_WEEKLY_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function weeklysubcategorydata_exportexcel()
    {

        $table = INSP_WEEKLY_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WEEKLY_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Define the header for the Excel sheet
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Weekly_Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); // Ensure browser download trigger
    }

    public function weeklysubcategorydata_exportpdf()
    {

        $table = INSP_WEEKLY_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WEEKLY_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WEEKLY_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []
        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Weekly SubCategoryData List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/weekly/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "weekly_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }






    // Workcamp category start
    public function workcampcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/workcamp/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/workcampcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function workcampcategory_list()
    {


        $table = INSP_WORKCAMP_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';



        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_workcampcategory/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_workcampcategory($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];


        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_WORKCAMP_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/workcamp/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_workcampcategory()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_WORKCAMP_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_workcampcategory()
    {
        // ini_set("display_errors",1);

        $getrespdatas = $this->input->post('main_mas');

        // echo '<pre>'.print_r($getrespdatas,true).'</pre>';
        // exit;
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');



            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_WORKCAMP_CAT, 'id', 'id', $uniqcomidwhr);


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),

                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),

                ];
            }

            // log_message('debug',print_r($respdatas,true));

            $updCategory = $this->common_model->updateData(INSP_WORKCAMP_CAT, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function workcampcategory_exportpdf()
    {
        // Fetching data from the database
        $table = INSP_WORKCAMP_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Worker Camp Category List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/workcamp/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "worker_camp_category_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function workcampcategory_exportexcel()
    {
        // Fetching data from the database
        $table = INSP_WORKCAMP_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Worker Camp Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Workcamp Sub category start
    public function workcampsubcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/workcamp/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/workcampsubcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function workcampsubcategory_list()
    {
        // ini_set("display_errors", 1);
        $table = INSP_WORKCAMP_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_workcampsubcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_workcampsubcategory($id = "")
    {

        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WORKCAMP_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_WORKCAMP_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/workcamp/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getworkcampSubcategories()
    {
        $categoryId = $this->input->post('category_id');


        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_WORKCAMP_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_workcampsubcategory()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_WORKCAMP_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_workcampsubcategory()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_WORKCAMP_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_WORKCAMP_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_WORKCAMP_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function workcampsubcategory_exportpdf()
    {
        $table = INSP_WORKCAMP_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Worker Camp SubCategory List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/workcamp/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "worker_camp_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function workcampsubcategory_exportexcel()
    {
        $table = INSP_WORKCAMP_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Worker Camp Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Workcamp Sub category Data start
    public function workcampsubcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WORKCAMP_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/workcamp/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/workcampsubcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function getworkcampSubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        // log_message('DEBUG', 'data' . print_r($categoryId .true));
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_WORKCAMP_SUBCAT)->result_array();

            // log_message('DEBUG', 'data' . print_r($subcategories . true));

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_workcampsubcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_WORKCAMP_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');


        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_WORKCAMP_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/workcamp/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        // echo '<pre>';
        // log_message('debug', print_r($this->db->last_query()));
        // log_message('debug', print_r($data,true));
        // exit();
        $this->template->load_common_template($data);
    }

    public function workcampsubcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_WORKCAMP_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');


        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WORKCAMP_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        // log_message('debug', print_r($this->db->last_query(), true));

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                //    echo '<pre>'.print_r($id) 

                $action .= " " . anchor('inspection/master/add_workcampsubcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_workcampsubcategorydata()
    {

        // log_message('debug',print_r($this->input->post(),true));
        // exit;

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_WORKCAMP_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];



        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_WORKCAMP_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_WORKCAMP_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_workcampsubcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_WORKCAMP_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function workcampsubcategorydata_exportexcel()
    {

        $table = INSP_WORKCAMP_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WORKCAMP_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Define the header for the Excel sheet
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('WorkCamp_Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); // Ensure browser download trigger
    }

    public function workcampsubcategorydata_exportpdf()
    {

        $table = INSP_WORKCAMP_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_WORKCAMP_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_WORKCAMP_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []
        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Worker Camp SubCategoryData List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/workcamp/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "worker_camp_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }







    // Hotel category start
    public function hotelcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/hotel/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/hotelcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function hotelcategory_list()
    {


        $table = INSP_HOTEL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';



        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_hotelcategory/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_hotelcategory($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];


        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_HOTEL_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/hotel/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_hotelcategory()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_HOTEL_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_hotelcategory()
    {
        // ini_set("display_errors",1);

        $getrespdatas = $this->input->post('main_mas');

        // echo '<pre>'.print_r($getrespdatas,true).'</pre>';
        // exit;
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');



            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_HOTEL_CAT, 'id', 'id', $uniqcomidwhr);


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),

                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),

                ];
            }

            // log_message('debug',print_r($respdatas,true));

            $updCategory = $this->common_model->updateData(INSP_HOTEL_CAT, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function hotelcategory_exportpdf()
    {
        // Fetching data from the database
        $table = INSP_HOTEL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Hotel Category List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/hotel/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "hotel_category_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function hotelcategory_exportexcel()
    {
        // Fetching data from the database
        $table = INSP_HOTEL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Hotel Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Hotel Sub category start
    public function hotelsubcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/hotel/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/hotelsubcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function hotelsubcategory_list()
    {
        // ini_set("display_errors", 1);
        $table = INSP_HOTEL_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_hotelsubcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_hotelsubcategory($id = "")
    {

        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_HOTEL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_HOTEL_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/hotel/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function gethotelSubcategories()
    {
        $categoryId = $this->input->post('category_id');


        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_HOTEL_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_hotelsubcategory()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_HOTEL_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_hotelsubcategory()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_HOTEL_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_HOTEL_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_HOTEL_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function hotelsubcategory_exportpdf()
    {
        $table = INSP_HOTEL_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Hotel SubCategory List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/hotel/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "hotel_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function hotelsubcategory_exportexcel()
    {
        $table = INSP_HOTEL_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Hotel Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Hotel Sub category Data start
    public function hotelsubcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_HOTEL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/hotel/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/hotelsubcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function gethotelSubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        // log_message('DEBUG', 'data' . print_r($categoryId .true));
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_HOTEL_SUBCAT)->result_array();

            // log_message('DEBUG', 'data' . print_r($subcategories . true));

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_hotelsubcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_HOTEL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');


        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_HOTEL_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/hotel/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        // echo '<pre>';
        // log_message('debug', print_r($this->db->last_query()));
        // log_message('debug', print_r($data,true));
        // exit();
        $this->template->load_common_template($data);
    }

    public function hotelsubcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_HOTEL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');


        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_HOTEL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        // log_message('debug', print_r($this->db->last_query(), true));

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                //    echo '<pre>'.print_r($id) 

                $action .= " " . anchor('inspection/master/add_hotelsubcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_hotelsubcategorydata()
    {

        // log_message('debug',print_r($this->input->post(),true));
        // exit;

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_HOTEL_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];



        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_HOTEL_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_HOTEL_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_hotelsubcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_HOTEL_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function hotelsubcategorydata_exportexcel()
    {

        $table = INSP_HOTEL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_HOTEL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Define the header for the Excel sheet
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Hotel_Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); // Ensure browser download trigger
    }

    public function hotelsubcategorydata_exportpdf()
    {

        $table = INSP_HOTEL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_HOTEL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_HOTEL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []
        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Hotel SubCategoryData List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/hotel/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "hotel_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }
















    // Audit category start
    public function auditcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/audit/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/auditcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function auditcategory_list()
    {


        $table = INSP_AUDIT_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';



        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_auditcategory/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_auditcategory($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];


        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_AUDIT_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/audit/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_auditcategory()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_AUDIT_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_auditcategory()
    {
        // ini_set("display_errors",1);

        $getrespdatas = $this->input->post('main_mas');

        // echo '<pre>'.print_r($getrespdatas,true).'</pre>';
        // exit;
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');



            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_AUDIT_CAT, 'id', 'id', $uniqcomidwhr);


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),

                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),

                ];
            }

            // log_message('debug',print_r($respdatas,true));

            $updCategory = $this->common_model->updateData(INSP_AUDIT_CAT, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function auditcategory_exportpdf()
    {
        // Fetching data from the database
        $table = INSP_AUDIT_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Audit Category List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/audit/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "audit_category_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function auditcategory_exportexcel()
    {
        // Fetching data from the database
        $table = INSP_AUDIT_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Audit Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Audit Sub category start
    public function auditsubcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/audit/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/auditsubcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function auditsubcategory_list()
    {
        // ini_set("display_errors", 1);
        $table = INSP_AUDIT_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_auditsubcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_auditsubcategory($id = "")
    {

        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_AUDIT_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_AUDIT_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/audit/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getauditSubcategories()
    {
        $categoryId = $this->input->post('category_id');


        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_AUDIT_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_auditsubcategory()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_AUDIT_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_auditsubcategory()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_AUDIT_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_AUDIT_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_AUDIT_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function auditsubcategory_exportpdf()
    {
        $table = INSP_AUDIT_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Audit SubCategory List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/audit/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "audit_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function auditsubcategory_exportexcel()
    {
        $table = INSP_AUDIT_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Audit Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Audit Sub category Data start
    public function auditsubcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_AUDIT_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/audit/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/auditsubcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function getauditSubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        // log_message('DEBUG', 'data' . print_r($categoryId .true));
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_AUDIT_SUBCAT)->result_array();

            // log_message('DEBUG', 'data' . print_r($subcategories . true));

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_auditsubcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_AUDIT_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');


        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_AUDIT_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/audit/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        // echo '<pre>';
        // log_message('debug', print_r($this->db->last_query()));
        // log_message('debug', print_r($data,true));
        // exit();
        $this->template->load_common_template($data);
    }

    public function auditsubcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_AUDIT_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');


        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_AUDIT_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        // log_message('debug', print_r($this->db->last_query(), true));

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                //    echo '<pre>'.print_r($id) 

                $action .= " " . anchor('inspection/master/add_auditsubcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_auditsubcategorydata()
    {

        // log_message('debug',print_r($this->input->post(),true));
        // exit;

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_AUDIT_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];
        $guidances = $getrespdatas['guidance'];



        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $guidance = isset($guidances[$key]) ? $guidances[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                        'guidance' => $guidance,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_AUDIT_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                        'guidance' => $guidance,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_AUDIT_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_auditsubcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_AUDIT_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function auditsubcategorydata_exportexcel()
    {

        $table = INSP_AUDIT_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_AUDIT_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Define the header for the Excel sheet
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Audit_Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); // Ensure browser download trigger
    }

    public function auditsubcategorydata_exportpdf()
    {


        $table = INSP_AUDIT_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_AUDIT_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_AUDIT_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Audit SubCategoryData List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/audit/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "audit_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }









    // Initial category start
    public function initialcategory()
    {

        $data = array(
            'view_file' => 'master/initial/category/list_category',
            'site_title' => 'Category List',
            'page_title' => 'Category List',
            'current_menu' => 'Category',
            'ajaxurl' => 'inspection/master/initialcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function initialcategory_list()
    {

        $table = INSP_INITIAL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $optns['select'] = 'mascat.*';



        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('inspection/master/add_initialcategory/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteCategory', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->category;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function add_initialcategory($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];


        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(INSP_INITIAL_CAT, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/initial/category/add_category',
            'current_menu' => 'Add Category',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }

    public function delete_initialcategory()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(INSP_INITIAL_CAT, ['category_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Category deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Category'
            ];
        }
        echo json_encode($retData);
    }

    public function save_initialcategory()
    {
        // ini_set("display_errors",1);

        $getrespdatas = $this->input->post('main_mas');

        // echo '<pre>'.print_r($getrespdatas,true).'</pre>';
        // exit;
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[category]', 'Category', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');
            $other_image = $this->input->post('other_user_img');


            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('CAT', INSP_INITIAL_CAT, 'id', 'id', $uniqcomidwhr);


            $imgDatasother = [];

            if (isset($_FILES) && !empty($_FILES) && isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])) {
                $imgDatasother = uploadImage($filename = 'category_image', $imgUploadpath = 'assets/images/modules/inspection/images/initial/category', $allowedTyp = "png|jpg|jpeg");
                if ($other_image) {
                    $existingImagePath = $other_image;
                    $existingImageName = basename($existingImagePath);

                    $existingImageFullPath = FCPATH . 'assets/images/modules/inspection/images/initial/category/' . $existingImageName;

                    if (file_exists($existingImageFullPath)) {
                        unlink($existingImageFullPath);
                    }
                }
            } else if ($other_image) {
                $imgDatasother = $other_image;
            } else {
                $imgDatasother = '';
            }


            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'category' => postData($getrespdatas, 'category'),
                    'category_image' => $imgDatasother,
                ];
            } else {
                $respdatas = [
                    'category_un_id' => $inspidUniq,
                    'category' => postData($getrespdatas, 'category'),
                    'category_image' => $imgDatasother,
                ];
            }

            // log_message('debug',print_r($respdatas,true));

            $updCategory = $this->common_model->updateData(INSP_INITIAL_CAT, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
                } else {
                    $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'category' => strip_tags(form_error('main_mas[category]')),
            ];
        }
        echo json_encode($data);
    }

    public function initialcategory_exportpdf()
    {
        // Fetching data from the database
        $table = INSP_INITIAL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Initial P&M Category List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/initial/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "initial_category_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function initialcategory_exportexcel()
    {
        // Fetching data from the database
        $table = INSP_INITIAL_CAT . ' as mascat';

        $column_order = array(null, 'category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(mascat.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['category_status' => 'Y'];

        $options['select'] = 'mascat.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                // $row[] = $ltVal->category_un_id;
                $row[] = $ltVal->category;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Initial P&M Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Initial Sub category start
    public function initialsubcategory()
    {

        $data = array(
            'view_file' => 'inspection/master/initial/subcategory/list_subcategory',
            'site_title' => 'Sub Category List',
            'page_title' => 'Sub Category List',
            'current_menu' => 'Sub Category',
            'ajaxurl' => 'inspection/master/initialsubcategory_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function initialsubcategory_list()
    {
        // ini_set("display_errors", 1);
        $table = INSP_INITIAL_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.id, mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $listSubCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCat)) {
            foreach ($listSubCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('inspection/master/add_initialsubcategory/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategories;
                $rows[] = $ltVal->latest_date;
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );

        echo json_encode($output);
    }

    public function add_initialsubcategory($id = "")
    {

        $getSubCategories = [];
        $did = decryptval($id);

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_INITIAL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_cat_id' => $did,
                'subcategory_status !=' => 'N'
            ];
            $getSubCategories = $this->common_model->getAlldata(INSP_INITIAL_SUBCAT, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'inspection/master/initial/subcategory/add_subcategory',
            'current_menu' => 'Add Sub Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getSubCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getinitialSubcategories()
    {
        $categoryId = $this->input->post('category_id');


        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_INITIAL_SUBCAT)->result_array();
        }

        echo json_encode($subcategories);
    }

    public function delete_initialsubcategory()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid Sub Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(INSP_INITIAL_SUBCAT, ['subcategory_status' => 'N'], ['fk_cat_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'SubCategory Details deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting Sub Category',
            ]);
        }
    }


    public function save_initialsubcategory()
    {

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_INITIAL_SUBCAT, ['subcategory_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['fk_cat_id'];
        $subcategories = $getrespdatas['subcategory'];
        $subcategory_ids = $getrespdatas['subcategory_id'];

        if (!empty($subcategories)) {
            foreach ($subcategories as $key => $subcategory_name) {
                $subcategory_id = isset($subcategory_ids[$key]) ? $subcategory_ids[$key] : '';
                $updtWhr = [];
                if ($subcategory_id != '') {
                    $updtWhr = [
                        'id' => $subcategory_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    $inspidUniq = uniqueIntRefId('SUBCAT', INSP_INITIAL_SUBCAT, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [
                        'subcat_un_id' => $inspidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'subcategory' => $subcategory_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_INITIAL_SUBCAT, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategory has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategoryflash'] = $this->session->set_flashdata('subcategoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategory cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function initialsubcategory_exportpdf()
    {
        $table = INSP_INITIAL_SUBCAT . ' as massubcat';


        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Initial P&M SubCategory List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/initial/subcategory/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "initial_subcategory_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function initialsubcategory_exportexcel()
    {
        $table = INSP_INITIAL_SUBCAT . ' as massubcat';

        $column_order = array(null, 'mascat.category', null, 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'DATE_FORMAT(massubcat.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc');
        $where = ['subcategory_status' => 'Y'];

        $options = [
            'select' => 'mascat.category, GROUP_CONCAT(massubcat.subcategory ORDER BY massubcat.subcategory ASC SEPARATOR ", ") as subcategories, DATE_FORMAT(MAX(massubcat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcat.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Created Date',
        ];


        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->category;
                $row[] = $ltVal->subcategories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Initial P&M Sub Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // Monthly Sub category Data start
    public function initialsubcategorydata()
    {
        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_INITIAL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');

        $data = array(
            'view_file' => 'inspection/master/initial/subcategorydata/list_subcategorydata',
            'site_title' => 'Sub Category Data List',
            'page_title' => 'Sub Category Data List',
            'current_menu' => 'Sub Category Data',
            'ajaxurl' => 'inspection/master/initialsubcategorydata_list',
            'dropCategories' => $dropInsptype,
        );

        $this->template->load_table_exp_template($data);
    }

    public function getinitialSubcategoryDetails()
    {
        $categoryId = $this->input->post('id');
        // log_message('DEBUG', 'data' . print_r($categoryId .true));
        $output = '';

        if (!empty($categoryId)) {
            $this->db->select('id, subcategory');
            $this->db->where('fk_cat_id', $categoryId);
            $this->db->where('subcategory_status', 'Y');
            $subcategories = $this->db->get(INSP_INITIAL_SUBCAT)->result_array();

            // log_message('DEBUG', 'data' . print_r($subcategories . true));

            if (!empty($subcategories)) {
                $output .= '<option value="">Select Sub Category</option>';
                foreach ($subcategories as $subcategory) {
                    $output .= '<option value="' . $subcategory['id'] . '">' . $subcategory['subcategory'] . '</option>';
                }
            } else {
                $output .= "<option value=''>No Subcategories Available</option>";
            }
        } else {
            $output .= "<option value=''>Select Sub Category</option>";
        }

        echo $output;
        exit();
    }

    public function add_initialsubcategory_data($id = '')
    {

        $did = decryptval($id);
        $editData = [];

        $options['where'] = ['category_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(INSP_INITIAL_CAT, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'category', 'Select Category');


        if ($id != '') {
            $optWhr['where'] = [
                'fk_subcat_id' => $did,
                'subcategorydata_status !=' => 'N'
            ];
            $editData = $this->common_model->getAlldata(INSP_INITIAL_SUBCATDATA, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'inspection/master/initial/subcategorydata/add_subcategorydata',
            'current_menu' => 'Add Sub Category data',
            'editData' => $editData,
            'dropCategories' => $dropInsptype,
        );

        // echo '<pre>';
        // log_message('debug', print_r($this->db->last_query()));
        // log_message('debug', print_r($data,true));
        // exit();
        $this->template->load_common_template($data);
    }

    public function initialsubcategorydata_list()
    {
        $mappedData = [];
        $request = $this->input->post();

        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }

        if ($mappedData != FALSE && count($mappedData) > 0) {
            $category = postData($mappedData, 'category');
            $subcategory = postData($mappedData, 'subcategory');
        }

        $table = INSP_INITIAL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');


        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        if ($category > 0) {
            $where['massubcatdata.fk_cat_id'] = $category;
        }

        if ($subcategory > 0) {
            $where['massubcatdata.fk_subcat_id'] = $subcategory;
        }

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_INITIAL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
        ];

        $listSubCatData = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);


        // log_message('debug', print_r($this->db->last_query(), true));

        $finalDatas = [];
        $i = 0;

        if (!empty($listSubCatData)) {
            foreach ($listSubCatData as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->fk_subcat_id);
                //    echo '<pre>'.print_r($id) 

                $action .= " " . anchor('inspection/master/add_initialsubcategory_data/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategorydata', 'title' => 'Delete', 'delt-id' => $id));

                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->category;
                $rows[] = $ltVal->subcategory;
                $rows[] = $ltVal->subcategorydata_list;
                $rows[] = convertDate($ltVal->latest_date);
                $rows[] = $action;
                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        );
        echo json_encode($output);
    }

    public function save_initialsubcategorydata()
    {

        // log_message('debug',print_r($this->input->post(),true));
        // exit;

        $deleteIds = json_decode($this->input->post('deleteIds'), true);
        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(INSP_INITIAL_SUBCATDATA, ['subcategorydata_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_cat_id = $getrespdatas['category_id'];
        $fk_subcat_id = $getrespdatas['subcategory_id'];
        $subcategorydata = $getrespdatas['subcategorydata'];
        $subcategorydata_ids = $getrespdatas['subcategorydata_id'];



        if (!empty($subcategorydata)) {
            foreach ($subcategorydata as $key => $subcategorydata_name) {
                $subcategorydata_id = isset($subcategorydata_ids[$key]) ? $subcategorydata_ids[$key] : '';
                $updtWhr = [];
                if ($subcategorydata_id != '') {
                    $updtWhr = [
                        'id' => $subcategorydata_id
                    ];

                    $respdatas = [
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                } else {
                    $uniqcomidsub = [];
                    $subidUniq = uniqueIntRefId('SUBCATDATA', INSP_INITIAL_SUBCATDATA, 'id', 'id', $uniqcomidsub);
                    $respdatas = [
                        'subcatdata_un_id' => $subidUniq,
                        'fk_cat_id' => $fk_cat_id,
                        'fk_subcat_id' => $fk_subcat_id,
                        'subcategorydata' => $subcategorydata_name,
                    ];
                }

                $updSubCategory = $this->common_model->updateData(INSP_INITIAL_SUBCATDATA, $respdatas, $updtWhr);
            }
        }

        if (($updSubCategory)) {

            $data = [
                'status' => true
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> SubCategoryData has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['subcategorydataflash'] = $this->session->set_flashdata('subcategorydataflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> SubCategoryData cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function delete_initialsubcategorydata()
    {
        $subdeleteId = $this->input->post('delid');
        $subDelete = $this->common_model->updateData(INSP_INITIAL_SUBCATDATA, ['subcategorydata_status' => 'N'], ['fk_subcat_id' => decryptval($subdeleteId)]);

        if ($subDelete) {
            $retsubData = [
                'status' => true,
                'msgs' => 'Sub Category data deleted successfully'
            ];
        } else {
            $retsubData = [
                'status' => false,
                'msgs' => 'Error in deleting Sub Category data'
            ];
        }
        echo json_encode($retsubData);
    }

    public function initialsubcategorydata_exportexcel()
    {

        $table = INSP_INITIAL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_INITIAL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }
        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Define the header for the Excel sheet
        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($result)) {
            foreach ($result as $ltVal) {
                $row = [
                    $i++,
                    $ltVal->category ?? '',
                    $ltVal->subcategory ?? '',
                    $ltVal->subcategorydata_list ?? '',
                    date('d-m-Y', strtotime($ltVal->latest_date)) ?? '',
                ];
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Initial_Sub_Category_data_List.xlsx')
            ->addHeader($header)
            ->addRows($exportData)
            ->toBrowser(); // Ensure browser download trigger
    }

    public function initialsubcategorydata_exportpdf()
    {

        $table = INSP_INITIAL_SUBCATDATA . ' as massubcatdata';

        $column_order = array(null, 'mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")', null);
        $column_search = array('mascat.category', 'massubcat.subcategory', 'massubcatdata.subcategorydata', 'DATE_FORMAT(massubcatdata.created_on, "%d-%m-%Y")');

        $order = array('mascat.category' => 'asc', 'massubcat.subcategory' => 'asc');
        $where = ['massubcatdata.subcategorydata_status' => 'Y'];

        $options = [
            'select' => 'mascat.category,massubcatdata.fk_subcat_id, massubcat.subcategory, GROUP_CONCAT(massubcatdata.subcategorydata ORDER BY massubcatdata.subcategorydata ASC SEPARATOR ", ") as subcategorydata_list, DATE_FORMAT(MAX(massubcatdata.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                INSP_INITIAL_CAT . ' as mascat' => ['mascat.id = massubcatdata.fk_cat_id AND mascat.category_status = "Y"', 'INNER'],
                INSP_INITIAL_SUBCAT . ' as massubcat' => ['massubcat.id = massubcatdata.fk_subcat_id AND massubcat.subcategory_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mascat.category, massubcat.subcategory',
            'where_new' => []

        ];

        $request = $this->input->get();
        if ($request != FALSE && count($request) > 0) {
            $category = postData($request, 'category');
            $subcategory = postData($request, 'subcategory');

            if ($category > 0) {
                $options['where_new']['massubcatdata.fk_cat_id'] = $category;
            }
            if ($subcategory > 0) {
                $options['where_new']['massubcatdata.fk_subcat_id'] = $subcategory;
            }
        }

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        $header = [
            'SL.No.',
            'Category',
            'Sub Category',
            'Sub Category Data',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Initial P&M SubCategoryData List",
        ];

        // PDF configuration
        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
        ];

        // Generating the PDF
        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';

        // Load the view for the PDF content
        $html = $this->load->view('inspection/master/initial/subcategorydata/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "initial_subcategoryData_list.pdf";
        $mpdf->Output($filename, 'D');
    }
}
