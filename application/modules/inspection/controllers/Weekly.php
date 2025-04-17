<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Weekly extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        isLogin();
        $this->load->model('inspection/weekly_model', 'weekly');
        $this->load->helper('ins_helper');
    }

    public function inspection_list()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $status_main, $insp_status;

        $Status = !empty($getdashdata['Status']) ? $getdashdata['Status'] : 0;
        $company_id = !empty($getdashdata['company_id']) ? $getdashdata['company_id'] : 0;
        $area_id = !empty($getdashdata['area_id']) ? $getdashdata['area_id'] : 0;
        $building_id = !empty($getdashdata['building_id']) ? $getdashdata['building_id'] : 0;
        $department_id = !empty($getdashdata['department_id']) ? $getdashdata['department_id'] : 0;
        $project_id = !empty($getdashdata['project_id']) ? $getdashdata['project_id'] : 0;
        $cat_id = !empty($getdashdata['ins_cat_id']) ? $getdashdata['ins_cat_id'] : 0;
        $Start_Date = !empty($getdashdata['Start_Date']) ? $getdashdata['Start_Date'] : '';
        $End_Date = !empty($getdashdata['End_Date']) ? $getdashdata['End_Date'] : '';


        $inscatOptn['where'] = [
            'category_status' => 'Y'
        ];
        $getAllinscat = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $inscatOptn);
        $dropinscat = customFormDropDown($getAllinscat, 'id', 'category', 'Select Category');



        $data = [
            'pageTitle' => 'Weekly Inspection',
            'view_file' => 'inspection/weekly/list_form',
            'site_title' => 'Weekly Inspection',
            'current_menu' => 'List',
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $dropinscat,
            'status_drop_proj' => $insp_status,
            'status_main' => $status_main,
            'getdashdata' => $getdashdata,
            'ajaxurl' => 'inspection/weekly/listInspection?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&cat_id=' . $cat_id . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date,
        ];
        $this->template->load_table_exp_template($data);
    }

    public function listInspection()
    {
        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main, $insp_status;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status',
            null
        );

        $column_search = array(
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
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

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $ins_cat = postData($mappedData, 'ins_cat_id');
            $startdate = postData($mappedData, 'start_date');
            $enddate = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');
            $searchMainStatus = postData($mappedData, 'mainStatus');

            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $options['where_new']['h.ins_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.ins_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.ins_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.ins_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.ins_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.ins_cat_id'] = $ins_cat;
            }




            if ($searchStatus >= 0) {
                $options['where_new']["h.ins_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.ins_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.ins_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }

        ///////////////////////////filter end


        if (in_array($user_type, $insPermission['view_supadmin'])) {
            $options['where_new']['ins_status'] =  'Y';
        } elseif (in_array($user_type, $insPermission['view_ad'])) {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve'])) {
            $options['where_new']['ins_status'] =  'Y';
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_reporter_id'] =  $userid;
        }


        $options['select'] = [
            'h.ins_auto_id',
            'h.ins_id',
            'h.ins_app_status',
            'h.ins_reporter_id',
            'FN_COMP_NAME(ins_comp_id) as comp_name',
            'FN_AREA_NAME(ins_area_id) as area_name',
            'FN_BUILD_NAME(ins_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(ins_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.ins_project_id) as proj_name',
            'cat.category as ins_cat_name',
            'h.ins_report_datetime',
            'h.is_edit',
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.ins_cat_id', 'left'];


        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (isset($listUsee) && !empty($listUsee)) {
            foreach ($listUsee as $ltKey => $ltVal) {
                $i++;
                $action = $ins_risk = $ins_type = '';
                $id = encryptval($ltVal->ins_auto_id);

                $stat = $ltVal->ins_app_status;
                $is_edit = $ltVal->is_edit;
                $ins_reporter_id = $ltVal->ins_reporter_id;


                if (((in_array($user_type, $insPermission['view_supadmin'])) || (in_array($user_type, $insPermission['view_ad']) || $userid == $ins_reporter_id)) && ($stat == 1 || $stat == 0) && ($is_edit == 0)) {
                    $action .= " " . anchor('inspection/weekly/add_inspection/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
                }

                if (in_array($user_type, $insPermission['view_supadmin']) || (in_array($user_type, $insPermission['view_ad']))) {

                    $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteIncident', 'title' => 'Delete', 'delid' => $id));
                }

                $action .= " " . anchor('inspection/weekly/view_form/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
                $action .= " " . anchor('inspection/weekly/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));




                $rows = [];
                $rows[] = $ltVal->ins_id;
                $rows[] = ucfirst($ltVal->comp_name);
                $rows[] = ucfirst($ltVal->area_name);
                $rows[] = ucfirst($ltVal->building_name);
                $rows[] = ucfirst($ltVal->dep_name);
                $rows[] = ucfirst($ltVal->proj_name);
                $rows[] = ucfirst($ltVal->ins_cat_name);
                $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->ins_report_datetime));
                $rows[] = $insp_status[$ltVal->ins_app_status];

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

    public function add_inspection($id = '')
    {
        $did = decryptval($id);


        global $owner_list, $owner_engineer_list, $EPC_list, $weekly_status;
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $hsecatOptn['where'] = [
            'category_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'id', 'category', 'Select Category');


        $insOptn['where'] = [
            'ins_auto_id' => $did,
        ];

        $itemOptn['where'] = [
            'fk_insp_main_auto_id ' => $did,
            'insp_status !=' => 'N'

        ];
        $getUseeActdatas = '';
        $getItemdatas = '';
        if ($did != '') {
            $getUseeActdatas = $this->common_model->getAlldata(INSP_WEEKLY_FLOW_SEE, ['*'], $insOptn);
            $getItemdatas = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, ['*'], $itemOptn);
        }

        $data = array(
            'view_file' => 'inspection/weekly/add_form',
            'current_menu' => 'Add Inspection',
            'getProgramdatas' => $getUseeActdatas,
            'getItemdatas' => $getItemdatas,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'dropweeklystatus' => $weekly_status,
            'hsecatDetails' => $drophsecat,
            'owner_list' => $owner_list,
            'owner_engineer_list' => $owner_engineer_list,
            'EPC_list' => $EPC_list,
            'isatar' => false
        );


        $this->template->load_common_template($data);
    }


    public function view_form($id = '')
    {
        $did = decryptval($id);

        $insOptn = [
            'where' => [
                'ins_auto_id' => $did,
            ],
            'join' => [
                INSP_WEEKLY_CAT . ' as category' => [
                    INSP_WEEKLY_FLOW_SEE . '.ins_cat_id =  category.id',
                    'LEFT',
                ],
                LOGIN . ' as LD' => [
                    INSP_WEEKLY_FLOW_SEE . '.ins_reporter_id =  LD.LOGIN_ID',
                    'LEFT',
                ],
                EMPL . ' as Reporter' => [
                    'LD.USER_REF_ID =  Reporter.EMP_AUTO_ID',
                    'LEFT',
                ],
            ],
            'return_type' => 'row',
        ];

        $itemOptn = [
            'where' => [
                'fk_insp_main_auto_id' => $did,
                'insp_status' => 'Y',
            ],
            'join' => [
                INSP_WEEKLY_SUBCATDATA . ' as subcategorydata' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_subcatdata_id = subcategorydata.id',
                    'LEFT',
                ],
                INSP_WEEKLY_SUBCAT . ' as subcategory' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_subcat_id = subcategory.id',
                    'LEFT',
                ],
                INSP_WEEKLY_CAT . ' as category' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_cat_id =  category.id',
                    'LEFT',
                ],
            ],
        ];

        $imageOptn['where'] = [
            'fk_insp_main_id' => $did,
            'insp_attach_status' => 'Y',
            'insp_file_type' => 1,
        ];

        $getUseeActdatas = '';
        $getItemdatas = '';
        $getEvidencedatas = '';
        if ($did != '') {
            $getUseeActdatas = $this->common_model->getAlldata(INSP_WEEKLY_FLOW_SEE, [
                INSP_WEEKLY_FLOW_SEE . '.*',
                'Reporter.EMP_NAME` as Reporter',
                'FN_GET_DESIGNATION_NAME(ins_reporter_desg_id) as desig',
                'FN_COMP_NAME(ins_comp_id) as comp_name',
                'FN_AREA_NAME(ins_area_id) as area_name',
                'FN_BUILD_NAME(ins_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(ins_dept_id) as dep_name',
                'FN_PROJECT_NAME(ins_project_id) as proj_name',
                'category.*',
            ], $insOptn);

            $getItemdatas = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, [
                INSP_WEEKLY_ITEMS . '.*',
                'subcategorydata.*',
                'subcategory.subcategory',
            ], $itemOptn);

            $getEvidencedatas = $this->common_model->getAlldata(INSP_WEEKLY_IMAGE_SEE, ['*'], $imageOptn);

            $data = [
                'view_file' => 'inspection/weekly/view_form',
                'current_menu' => 'View Inspection',
                'formTitle' => 'View Weekly Inspection',
                'inspData' => $getUseeActdatas,
                'itemData' => $getItemdatas,
                'evidenceData' => $getEvidencedatas,
            ];

            $this->template->load_common_template($data);
        }
    }

    public function pdf($id = '')
    {

        $did = decryptval($id);

        $insOptn = [
            'where' => [
                'ins_auto_id' => $did,
            ],
            'join' => [
                INSP_WEEKLY_CAT . ' as category' => [
                    INSP_WEEKLY_FLOW_SEE . '.ins_cat_id =  category.id',
                    'LEFT',
                ],
                LOGIN . ' as LD' => [
                    INSP_WEEKLY_FLOW_SEE . '.ins_reporter_id =  LD.LOGIN_ID',
                    'LEFT',
                ],
                EMPL . ' as Reporter' => [
                    'LD.USER_REF_ID =  Reporter.EMP_AUTO_ID',
                    'LEFT',
                ],
            ],
            'return_type' => 'row',
        ];

        $itemOptn = [
            'where' => [
                'fk_insp_main_auto_id' => $did,
                'insp_status' => 'Y',
            ],
            'join' => [
                INSP_WEEKLY_SUBCATDATA . ' as subcategorydata' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_subcatdata_id = subcategorydata.id',
                    'LEFT',
                ],
                INSP_WEEKLY_SUBCAT . ' as subcategory' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_subcat_id = subcategory.id',
                    'LEFT',
                ],
                INSP_WEEKLY_CAT . ' as category' => [
                    INSP_WEEKLY_ITEMS . '.fk_item_cat_id =  category.id',
                    'LEFT',
                ],
            ],
        ];

        $imageOptn['where'] = [
            'fk_insp_main_id' => $did,
            'insp_attach_status' => 'Y',
            'insp_file_type' => 1,
        ];

        $getUseeActdatas = '';
        $getItemdatas = '';
        $getEvidencedatas = '';
        if ($did != '') {
            $getUseeActdatas = $this->common_model->getAlldata(INSP_WEEKLY_FLOW_SEE, [
                INSP_WEEKLY_FLOW_SEE . '.*',
                'Reporter.EMP_NAME` as Reporter',
                'FN_GET_DESIGNATION_NAME(ins_reporter_desg_id) as desig',
                'FN_COMP_NAME(ins_comp_id) as comp_name',
                'FN_AREA_NAME(ins_area_id) as area_name',
                'FN_BUILD_NAME(ins_building_id) as building_name',
                'FN_GET_DEPARTMENT_NAME(ins_dept_id) as dep_name',
                'FN_PROJECT_NAME(ins_project_id) as proj_name',
                'category.*',
            ], $insOptn);

            $getItemdatas = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, [
                INSP_WEEKLY_ITEMS . '.*',
                'subcategorydata.*',
                'subcategory.subcategory',
            ], $itemOptn);

            $getEvidencedatas = $this->common_model->getAlldata(INSP_WEEKLY_IMAGE_SEE, ['*'], $imageOptn);

            $data = [
                'title' => 'Weekly Inspection Details',
                'inspData' => $getUseeActdatas,
                'itemData' => $getItemdatas,
                'evidenceData' => $getEvidencedatas,
            ];

            // echo '<pre>';
            // print_r($data);
            // die();
            $property = [
                'tempDir' => 'public/pdf/temp/',
                'mode' => 'c',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
            ];


            $mpdf = new \Mpdf\Mpdf($property);
            $mpdf->setAutoTopMargin = 'stretch';


            $html = $this->load->view('inspection/weekly/list_pdf', $data, true);
            $mpdf->WriteHTML($html);

            $filename = "Weekly_ins_details.pdf";
            $mpdf->Output($filename, 'D');
        }
    }

    public function fetchCategoryDetails()
    {

        $groupedData = [];

        $categoryId = $this->input->post('category_id');
        $insId = $this->input->post('ins_id');

        $item_id = $this->input->post('item_id');
        $subcatdata_id = $this->input->post('subcatdata_id');

        $groupedData['evidence_images'] = '';

        if (!empty($insId)) {
            $this->db->select('insp_att_id, fk_img_subcatdata_id, insp_file_path')
                ->where('fk_insp_main_id', $insId)
                ->where('insp_file_type', '1')
                ->where('insp_attach_status', 'Y');

            if (!empty($subcatdata_id)) {
                $this->db->where('fk_img_subcatdata_id', $subcatdata_id);
            }

            $groupedData['evidence_images'] = $this->db->get(INSP_WEEKLY_IMAGE_SEE)->result_array();
        }


        $evidenceImages = $groupedData['evidence_images'];
        $formattedData = [];

        if (!empty($evidenceImages)) {
            foreach ($evidenceImages as $image) {
                $id = $image['insp_att_id'];
                $subcatId = $image['fk_img_subcatdata_id'];
                $imagePath = $image['insp_file_path'];

                if (!isset($formattedData[$subcatId])) {
                    $formattedData[$subcatId] = [];
                }
                $formattedData[$subcatId][] = [
                    'id' => $id,
                    'image_path' => $imagePath
                ];
            }
        }


        $groupedData['evidence_images'] = $formattedData;


        $subcategories = $this->db->where('fk_cat_id', $categoryId)
            ->where('subcategory_status', 'Y')
            ->get(INSP_WEEKLY_SUBCAT)
            ->result_array();

        $category_image = $this->db->where('id', $categoryId)
            ->get(INSP_WEEKLY_CAT)
            ->row_array();

        $subcategoryData = $this->db->select('subcatdata.fk_subcat_id, subcatdata.id, subcatdata.subcategorydata')
            ->join(INSP_WEEKLY_SUBCAT . ' AS subcat', 'subcat.id = subcatdata.fk_subcat_id')
            ->where('subcat.fk_cat_id', $categoryId)
            ->where('subcatdata.subcategorydata_status', 'Y');

        if (!empty($subcatdata_id)) {
            $this->db->where('subcatdata.id', $subcatdata_id);
        }

        $subcategoryData = $this->db->get(INSP_WEEKLY_SUBCATDATA . ' AS subcatdata')->result_array();

        $groupedData['image_path'] = !empty($category_image['category_image']) ? $category_image['category_image'] : '';

        foreach ($subcategories as $subcat) {
            $groupedData['subcategoriesdata'][$subcat['id']] = [
                'subcategory' => $subcat['subcategory'],
                'data' => array_filter($subcategoryData, function ($data) use ($subcat) {
                    return $data['fk_subcat_id'] == $subcat['id'];
                }),
            ];
        }

        echo json_encode($groupedData);
    }

    public function save_inspection($id = '')
    {


        // echo '<pre>';
        // print_r($this->input->post());
        // exit();


        // Upload Evidence Images
        $files = $_FILES['images'];
        $uploadPath = 'assets/images/modules/inspection/weekly/evidence';
        $allowedTypes = 'png|jpg|jpeg';
        $uploadedData = [];

        $isatar = $this->input->post('isatar');
        $image_ids = $this->input->post('image_id');
        foreach ($files['name'] as $subcatdataId => $fileNames) {
            foreach ($fileNames as $key => $fileName) {

                if (!empty($fileName)) {
                    $fileData = [
                        'name' => $files['name'][$subcatdataId][$key],
                        'type' => $files['type'][$subcatdataId][$key],
                        'tmp_name' => $files['tmp_name'][$subcatdataId][$key],
                        'error' => $files['error'][$subcatdataId][$key],
                        'size' => $files['size'][$subcatdataId][$key],
                    ];

                    $uploadResult = uploadEvidenceImage(
                        $fileData,
                        $uploadPath,
                        $allowedTypes
                    );

                    if ($uploadResult) {
                        $uploadedData[$subcatdataId][$key] = [
                            'image_id' => !empty($image_ids[$subcatdataId][$key]) ? $image_ids[$subcatdataId][$key] : '',
                            'original_name' => $fileName,
                            'file_path' => isset($uploadResult['file_path']) ? $uploadResult['file_path'] : '',
                            'file_name' => isset($uploadResult['file_name']) ? $uploadResult['file_name'] : '',
                            'file_type' => isset($uploadResult['file_type']) ? $uploadResult['file_type'] : '',
                            'file_ext' => isset($uploadResult['file_ext']) ? $uploadResult['file_ext'] : '',
                            'file_size' => isset($uploadResult['file_size']) ? $uploadResult['file_size'] : '',
                        ];
                    } else {
                        $uploadedData[$subcatdataId][$key] = [
                            'image_id' => !empty($image_ids[$subcatdataId][$key]) ? $image_ids[$subcatdataId][$key] : '',
                            'original_name' => $fileName,
                            'file_path' => 'Failed to upload',
                            'file_name' =>  '',
                            'file_type' =>  '',
                            'file_ext' => '',
                            'file_size' =>  '',
                        ];
                    }
                } else {
                    $uploadedData[$subcatdataId][$key] = [
                        'image_id' => !empty($image_ids[$subcatdataId][$key]) ? $image_ids[$subcatdataId][$key] : '',
                        'original_name' => '',
                        'file_path' => '',
                        'file_name' =>  '',
                        'file_type' =>  '',
                        'file_ext' => '',
                        'file_size' =>  '',
                    ];
                }
            }
        }



        $did = decryptval($id);
        $InsDatas = $this->input->post('pro');
        $action_type = $this->input->post('action_type');

        // Check Validation
        if ($action_type) {
            $this->form_validation->set_rules('pro[ins_owner_id]', 'Owner', 'required');
            $this->form_validation->set_rules('pro[ins_owner_eng]', 'Owner Engineering Name', 'required|trim');
            $this->form_validation->set_rules('pro[ins_epc_id]', 'EPC', 'required|trim');
            $this->form_validation->set_rules('pro[ins_comp_id]', 'Company', 'required|trim');
            $this->form_validation->set_rules('pro[ins_area_id]', 'Area', 'required|trim');
            $this->form_validation->set_rules('pro[ins_building_id]', 'Building', 'required|trim');
            $this->form_validation->set_rules('pro[ins_dept_id]', 'Department', 'required|trim');
            $this->form_validation->set_rules('pro[ins_project_id]', 'Project', 'required|trim');
            $this->form_validation->set_rules('pro[ins_cat_id]', 'Inspection Category', 'required|trim');
            $this->form_validation->set_rules('pro[ins_date]', 'Inspection Date', 'required');
        } else {
            $this->form_validation->set_rules('pro[ins_comp_id]', 'Company', 'required|trim');
            $this->form_validation->set_rules('pro[ins_area_id]', 'Area', 'required|trim');
            $this->form_validation->set_rules('pro[ins_building_id]', 'Building', 'required|trim');
            $this->form_validation->set_rules('pro[ins_project_id]', 'Project', 'required|trim');
        }



        if ($this->form_validation->run()) {

            $date_time = postData($InsDatas, 'ins_report_datetime');
            $ins_app_status = $action_type;

            $ins_date = !empty(postData($InsDatas, 'ins_date'))
                ? date('Y-m-d H:i:s', strtotime(postData($InsDatas, 'ins_date')))
                : NULL;
            $prodatas = [
                'ins_reporter_type_id' => postData($InsDatas, 'ins_reporter_role_id'),
                'ins_reporter_id' => postData($InsDatas, 'ins_reporter_id'),
                'ins_reporter_desg_id' => postData($InsDatas, 'user_desgination_id'),
                'ins_owner_id' => postData($InsDatas, 'ins_owner_id'),
                'ins_owner_eng' => postData($InsDatas, 'ins_owner_eng'),
                'ins_comp_id' => postData($InsDatas, 'ins_comp_id'),
                'ins_area_id' => postData($InsDatas, 'ins_area_id'),
                'ins_building_id' => postData($InsDatas, 'ins_building_id'),
                'ins_project_id' => postData($InsDatas, 'ins_project_id'),
                'ins_dept_id' => postData($InsDatas, 'ins_dept_id'),
                'ins_report_datetime' => $date_time,
                'ins_cat_id' => postData($InsDatas, 'ins_cat_id'),
                'ins_date' => $ins_date,
                'ins_app_status' => $ins_app_status,
                'is_closed' => $ins_app_status,
            ];

            if ($did != '') {
                $upWhere = [
                    'ins_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_FLOW_SEE, $prodatas, $upWhere);
            } else {

                $projectName = getProjectName(postData($InsDatas, 'ins_project_id'));
                $projNameThreeLetters = strtoupper(substr($projectName, 0, 3));
                $projNameThreeLetters = 'AHK';
                $areaShortName = getAreaShortName(postData($InsDatas, 'ins_area_id'));
                $areaShortTwoLetters = strtoupper(substr($areaShortName, 0, 2));
                $building_block_dirName = getBuildingName(postData($InsDatas, 'ins_building_id'));
                $build_block_dirFirstLetter = strtoupper(substr($building_block_dirName, 0, 1));
                $getuniqueId = getWeeklyInsNumber($projNameThreeLetters, $areaShortTwoLetters, $build_block_dirFirstLetter, postData($InsDatas, 'ins_project_id'));

                $prodatas['ins_id'] = $getuniqueId;
                $prodatas['ins_report_datetime'] = date('Y-m-d H:i:s', strtotime($date_time));
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_FLOW_SEE, $prodatas);
            }

            //get main id
            if ($did != '') {
                $gen_id = postData($InsDatas, 'ins_main_id');
            } else {
                $gen_id = $getuniqueId;
            }
            $optionMainId["return_type"] = "row";
            $optionMainId["where"] = ["ins_id" => $gen_id];


            $getAutoIddata = $this->common_model->getAlldata(INSP_WEEKLY_FLOW_SEE, ["ins_auto_id"], $optionMainId);


            $getAutoId = $getAutoIddata->ins_auto_id;
            $subCatIds = $this->input->post('item[ins_sub_id]') ?? [];
            $subCatDataIds = $this->input->post('item[sub_cat_data_id]') ?? [];
            $selections = $this->input->post('item[outcome]') ?? [];
            $notes = $this->input->post('item[notes]') ?? [];
            $ins_item_id = $this->input->post('item[ins_item_id]') ?? [];
            $ins_item_uni_id = $this->input->post('item[ins_item_uni_id]') ?? [];
            $is_checked = $this->input->post('item[is_checked]') ?? [];

            foreach ($subCatDataIds as $key => $subCatDataId) {

                $projectName = getProjectName(postData($InsDatas, 'ins_project_id'));
                $projNameThreeLetters = strtoupper(substr($projectName, 0, 3));
                $projNameThreeLetters = 'AHK';
                $areaShortName = getAreaShortName(postData($InsDatas, 'ins_area_id'));
                $areaShortTwoLetters = strtoupper(substr($areaShortName, 0, 2));
                $building_block_dirName = getBuildingName(postData($InsDatas, 'ins_building_id'));
                $build_block_dirFirstLetter = strtoupper(substr($building_block_dirName, 0, 1));
                $getuniqueIdAtar = getWeeklyInsAtarNumber($projNameThreeLetters, $areaShortTwoLetters, $build_block_dirFirstLetter, postData($InsDatas, 'ins_project_id'));

                if (!empty($ins_item_id[$key])) {

                    $upwhere = [
                        'insp_item_auto_id' => $ins_item_id[$key]
                    ];

                    if (empty($ins_item_uni_id[$key]) && isset($is_checked[$key]) && $is_checked[$key] == 1) {
                        $getuniqueIdAtar = $getuniqueIdAtar;
                    }else{
                        $getuniqueIdAtar = $ins_item_uni_id[$key];
                    }

                    $item = [
                        'insp_dept_id' => postData($InsDatas, 'ins_dept_id'),
                        'insp_atar_uni_id' => $getuniqueIdAtar,
                        'insp_date' => $ins_date,
                        'insp_desc' => $notes[$key] ?? '-',
                        'insp_item_condition' => $selections[$key],
                        'send_to_atar' => $is_checked[$key] ?? 0,
                        'insp_app_status' =>  $ins_app_status,
                    ];

                    $insertedItem = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $item, $upwhere);

                    $uploadedData[$subCatDataId] = array_merge(
                        $uploadedData[$subCatDataId] ?? [],
                        [
                            'item_id' => $ins_item_id[$key],
                            'subcat_id' => $subCatIds[$key],
                        ]
                    );
                } else {
                    if ($selections[$key] == '0' && !empty($notes[$key])) {

                        $getuniqueIdAtar = ($is_checked[$key] == 1) ?  $getuniqueIdAtar : '';

                        $item = [
                            'fk_insp_main_auto_id' => $getAutoId,
                            'insp_atar_uni_id' => $getuniqueIdAtar,
                            'fk_item_cat_id' => postData($InsDatas, 'ins_cat_id'),
                            'fk_item_subcat_id' => $subCatIds[$key],
                            'fk_item_subcatdata_id' => $subCatDataId,
                            'insp_reporter_type_id' => postData($InsDatas, 'ins_reporter_role_id'),
                            'insp_reporter_id' => postData($InsDatas, 'ins_reporter_id'),
                            'insp_reporter_desg_id' => postData($InsDatas, 'user_desgination_id'),
                            'insp_owner_id' => postData($InsDatas, 'ins_owner_id'),
                            'insp_owner_eng' => postData($InsDatas, 'ins_owner_eng'),
                            'insp_epc_id' => postData($InsDatas, 'ins_epc_id'),
                            'insp_comp_id' => postData($InsDatas, 'ins_comp_id'),
                            'insp_area_id' => postData($InsDatas, 'ins_area_id'),
                            'insp_building_id' => postData($InsDatas, 'ins_building_id'),
                            'insp_project_id' => postData($InsDatas, 'ins_project_id'),
                            'insp_dept_id' => postData($InsDatas, 'ins_dept_id'),
                            'insp_report_datetime' => date('Y-m-d H:i:s', strtotime($date_time)),
                            'insp_date' => $ins_date,
                            'insp_desc' => $notes[$key] ?? '-',
                            'send_to_atar' => $is_checked[$key] ?? 0,
                            'insp_item_condition' => $selections[$key],
                            'insp_app_status' =>  $ins_app_status,
                        ];

                        $insertedItem = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $item);

                        $uploadedData[$subCatDataId] = array_merge(
                            $uploadedData[$subCatDataId] ?? [],
                            [
                                'item_id' => $insertedItem,
                                'subcat_id' => $subCatIds[$key],
                            ]
                        );
                    } else if ($selections[$key] != '0') {

                        $getuniqueIdAtar = ($is_checked[$key] == 1) ?  $getuniqueIdAtar : '';

                        $item = [
                            'fk_insp_main_auto_id' => $getAutoId,
                            'insp_atar_uni_id' => $getuniqueIdAtar,
                            'fk_item_cat_id' => postData($InsDatas, 'ins_cat_id'),
                            'fk_item_subcat_id' => $subCatIds[$key],
                            'fk_item_subcatdata_id' => $subCatDataId,
                            'insp_reporter_type_id' => postData($InsDatas, 'ins_reporter_role_id'),
                            'insp_reporter_id' => postData($InsDatas, 'ins_reporter_id'),
                            'insp_reporter_desg_id' => postData($InsDatas, 'user_desgination_id'),
                            'insp_owner_id' => postData($InsDatas, 'ins_owner_id'),
                            'insp_owner_eng' => postData($InsDatas, 'ins_owner_eng'),
                            'insp_epc_id' => postData($InsDatas, 'ins_epc_id'),
                            'insp_comp_id' => postData($InsDatas, 'ins_comp_id'),
                            'insp_area_id' => postData($InsDatas, 'ins_area_id'),
                            'insp_building_id' => postData($InsDatas, 'ins_building_id'),
                            'insp_project_id' => postData($InsDatas, 'ins_project_id'),
                            'insp_dept_id' => postData($InsDatas, 'ins_dept_id'),
                            'insp_report_datetime' => date('Y-m-d H:i:s', strtotime($date_time)),
                            'insp_date' => $ins_date,
                            'insp_desc' => $notes[$key] ?? '-',
                            'send_to_atar' => $is_checked[$key] ?? 0,
                            'insp_item_condition' => $selections[$key],
                            'insp_app_status' =>  $ins_app_status,
                        ];

                        $insertedItem = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $item);
                        $uploadedData[$subCatDataId] = array_merge(
                            $uploadedData[$subCatDataId] ?? [],
                            [
                                'item_id' => $insertedItem,
                                'subcat_id' => $subCatIds[$key],
                            ]
                        );
                    }
                }
            }

            $existing_image = $this->input->post('existing_image');
            foreach ($uploadedData as $subCatDataIds => $images) {
                foreach ($images as $key => $imageData) {
                    if (!empty($imageData['image_id']) && !empty($imageData['file_path'])) {
                        // Updated Image
                        $imgwhere = [
                            'insp_att_id' => $imageData['image_id']
                        ];

                        // Unlink old Image
                        $old_image = $existing_image[$subCatDataIds][$key];
                        $existingImagePath = $old_image;
                        $existingImageName = basename($existingImagePath);

                        $existingImageFullPath = FCPATH . 'assets/images/modules/inspection/weekly/evidence/' . $existingImageName;

                        if (file_exists($existingImageFullPath)) {
                            unlink($existingImageFullPath);
                        }
                    } else if (empty($imageData['image_id']) && !empty($imageData['file_path'])) {
                        // New Image
                        $imgwhere = [];
                    } else {
                        // Not Updated or No Image
                        continue;
                    }

                    $image = [
                        'fk_insp_main_id' => $getAutoId,
                        'fk_img_item_id' => $images['item_id'],
                        'fk_img_cat_id	' => postData($InsDatas, 'ins_cat_id'),
                        'fk_img_subcat_id' => $images['subcat_id'],
                        'fk_img_subcatdata_id' => $subCatDataIds,
                        'insp_file_type' => 1,
                        'insp_filename' => $imageData['file_name'],
                        'insp_filetype' => $imageData['file_type'],
                        'insp_file_ext' => $imageData['file_ext'],
                        'insp_file_size' => $imageData['file_size'],
                        'insp_file_path' => $imageData['file_path'],
                    ];

                    $insertedItem = $this->common_model->updateData(INSP_WEEKLY_IMAGE_SEE, $image, $imgwhere);
                }
            }

            if ($updtProfile) {

                if ($did != '') {

                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Updated</div>');
                } else {

                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Created</div>');
                }
                if ($isatar) {
                    redirect('inspection/weekly/weekly_atar_list');
                } else {
                    redirect('inspection/weekly/inspection_list');
                }
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Created</div>');
                }
                if ($isatar) {
                    redirect('inspection/weekly/weekly_atar_list');
                } else {
                    redirect('inspection/weekly/inspection_list');
                }
            }
        } else {

            $this->add_inspection($id);
        }
    }

    public function delete_inspection()
    {
        $deleteId = $this->input->post('delid');




        $updtDelete = $this->common_model->updateData(INSP_WEEKLY_FLOW_SEE, ['ins_status' => 'N'], ['ins_auto_id' => decryptval($deleteId)]);
        // $updtDeleteImg = $this->common_model->updateData(INS_IMG_SEE, ['obs_attach_status' => 'N'], ['fk_obs_main_id' => decryptval($deleteId)]);


        if ($updtDelete) {

            $retData = [
                'status' => true,
                'msgs' => 'Weekly Inspection has been deleted successfully'
            ];
        } else {



            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Weekly Inspection! Try Again later.'
            ];
        }
        echo json_encode($retData);
        exit();
    }

    public function delete_evidence()
    {
        $deleteId = $this->input->post('delid');

        $imagepath = $this->db->select('insp_file_path')
            ->where('insp_att_id', $deleteId)
            ->where('insp_attach_status', 'Y')
            ->get(INSP_WEEKLY_IMAGE_SEE)
            ->row();

        $existingImagePath = $imagepath->insp_file_path;;
        $existingImageName = basename($existingImagePath);

        $existingImageFullPath = FCPATH . 'assets/images/modules/inspection/weekly/evidence/' . $existingImageName;

        if (file_exists($existingImageFullPath)) {
            unlink($existingImageFullPath);
        }

        $updtDelete = $this->common_model->updateData(INSP_WEEKLY_IMAGE_SEE, ['insp_attach_status' => 'N'], ['insp_att_id' => $deleteId]);

        if ($updtDelete) {

            $retData = [
                'status' => true,
                'msgs' => 'Evidence has been deleted successfully'
            ];
        } else {

            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Evidence! Try Again later.'
            ];
        }
        echo json_encode($retData);
        exit();
    }


    public function Weekly_exportpdf()
    {

        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status',
            null
        );

        $column_search = array(
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $where = [];
        $request = $this->input->get();



        if ($request != FALSE && count($request) > 0) {

            $company_id = postData($request, 'company_id');
            $area_id = postData($request, 'area_id');
            $building_id = postData($request, 'building_id');
            $department_id = postData($request, 'department_id');
            $project_id = postData($request, 'project_id');
            $emp_name = postData($request, 'emp_name');
            $ins_cat = postData($request, 'ins_cat_id');
            $startdate = postData($request, 'start_date');
            $enddate = postData($request, 'end_date');
            $searchStatus = postData($request, 'NotifyStatus');
            $searchMainStatus = postData($request, 'mainStatus');

            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $options['where_new']['h.ins_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.ins_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.ins_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.ins_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.ins_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.ins_cat_id'] = $ins_cat;
            }




            if ($searchStatus >= 0) {
                $options['where_new']["h.ins_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.ins_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.ins_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }
        if (in_array($user_type, $insPermission['view_supadmin'])) {
            $options['where_new']['ins_status'] =  'Y';
        } elseif (in_array($user_type, $insPermission['view_ad'])) {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['fix'])) {

            $options['where_new']['ins_status'] =  'Y';
            $options['orwhere_new']['ins_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $insPermission['approve'])) {
            $options['where_new']['ins_status'] =  'Y';
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_reporter_id'] =  $userid;
        }



        // print_r( $where);exit;

        $options['select'] = [
            'h.ins_auto_id',
            'h.ins_id',
            'h.ins_app_status',
            'h.ins_reporter_id',
            'FN_COMP_NAME(ins_comp_id) as comp_name',
            'FN_AREA_NAME(ins_area_id) as area_name',
            'FN_BUILD_NAME(ins_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(ins_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.ins_project_id) as proj_name',
            'cat.category as ins_cat_name',
            'h.ins_report_datetime',
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.ins_cat_id', 'left'];

        $listUsee = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);



        // Preparing the data for the PDF

        $header = [
            'Inspection ID',
            'Company',
            'Area',
            'Building/Block/Direction',
            'Department',
            'Project',
            'Category',
            'Reported Date & Time',
            'Status'
        ];
        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $listUsee,
            'pagetitle' => "Weekly Inspection List",
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
        $html = $this->load->view('inspection/weekly/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "Weekly_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function Weekly_exportexcel()
    {
        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main, $insp_status;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_FLOW_SEE . ' as h';
        $column_order = array(
            null,
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status',
            null
        );

        $column_search = array(
            'ins_id',
            'FN_COMP_NAME(ins_comp_id)',
            'FN_AREA_NAME(h.ins_area_id)',
            'FN_BUILD_NAME(h.ins_building_id)',
            'FN_PROJECT_NAME(h.ins_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.ins_dept_id)',
            'cat.category',
            "DATE_FORMAT(ins_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'ins_app_status'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $where = [];
        $request = $this->input->get();



        if ($request != FALSE && count($request) > 0) {

            $company_id = postData($request, 'company_id');
            $area_id = postData($request, 'area_id');
            $building_id = postData($request, 'building_id');
            $department_id = postData($request, 'department_id');
            $project_id = postData($request, 'project_id');
            $emp_name = postData($request, 'emp_name');
            $ins_cat = postData($request, 'ins_cat_id');
            $startdate = postData($request, 'start_date');
            $enddate = postData($request, 'end_date');
            $searchStatus = postData($request, 'NotifyStatus');
            $searchMainStatus = postData($request, 'mainStatus');

            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $options['where_new']['h.ins_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.ins_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.ins_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.ins_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.ins_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.ins_cat_id'] = $ins_cat;
            }




            if ($searchStatus >= 0) {
                $options['where_new']["h.ins_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.ins_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.ins_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }
        if (in_array($user_type, $insPermission['view_supadmin'])) {
            $options['where_new']['ins_status'] =  'Y';
        } elseif (in_array($user_type, $insPermission['view_ad'])) {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['fix'])) {

            $options['where_new']['ins_status'] =  'Y';
            $options['orwhere_new']['ins_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $insPermission['approve'])) {
            $options['where_new']['ins_status'] =  'Y';
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {
            $options['where_new']['ins_status'] =  'Y';
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['ins_status'] =  'Y';
            $options['where_new']['ins_reporter_id'] =  $userid;
        }



        // print_r( $where);exit;

        $options['select'] = [
            'h.ins_auto_id',
            'h.ins_id',
            'h.ins_app_status',
            'h.ins_reporter_id',
            'FN_COMP_NAME(ins_comp_id) as comp_name',
            'FN_AREA_NAME(ins_area_id) as area_name',
            'FN_BUILD_NAME(ins_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(ins_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.ins_project_id) as proj_name',
            'cat.category as ins_cat_name',
            'h.ins_report_datetime',
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.ins_cat_id', 'left'];

        $listUsee = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);



        // Preparing the data for the PDF

        $header = [
            'Inspection ID',
            'Company',
            'Area',
            'Building/Block/Direction',
            'Department',
            'Project',
            'Category',
            'Reported Date & Time',
            'Status'
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($listUsee)) {
            foreach ($listUsee as $ltVal) {



                $row = [];
                $row[] = $ltVal->ins_id ?? '-';
                $row[] = $ltVal->comp_name ?? '-';
                $row[] = $ltVal->area_name ?? '-';
                $row[] = $ltVal->building_name ?? '-';
                $row[] = $ltVal->dep_name ?? '-';
                $row[] = $ltVal->proj_name ?? '-';
                $row[] = $ltVal->ins_cat_name ?? '-';
                $row[] = $ltVal->ins_report_datetime ?? '-';
                $row[] = strip_tags($insp_status[$ltVal->ins_app_status]) ?? '-';
                $exportData[] = $row;

                $i++;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Weekly Form List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }


    // ATAR Start
    public function weekly_atar_list()
    {
        $getdashdata = $this->input->get();
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        global $status_drop, $status_main, $weekly_status;

        $Status = !empty($getdashdata['Status']) ? $getdashdata['Status'] : 0;
        $company_id = !empty($getdashdata['company_id']) ? $getdashdata['company_id'] : 0;
        $area_id = !empty($getdashdata['area_id']) ? $getdashdata['area_id'] : 0;
        $building_id = !empty($getdashdata['building_id']) ? $getdashdata['building_id'] : 0;
        $department_id = !empty($getdashdata['department_id']) ? $getdashdata['department_id'] : 0;
        $project_id = !empty($getdashdata['project_id']) ? $getdashdata['project_id'] : 0;
        $cat_id = !empty($getdashdata['ins_cat_id']) ? $getdashdata['ins_cat_id'] : 0;
        $Start_Date = !empty($getdashdata['Start_Date']) ? $getdashdata['Start_Date'] : '';
        $End_Date = !empty($getdashdata['End_Date']) ? $getdashdata['End_Date'] : '';


        $inscatOptn['where'] = [
            'category_status' => 'Y'
        ];
        $getAllinscat = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $inscatOptn);
        $dropinscat = customFormDropDown($getAllinscat, 'id', 'category', 'Select Category');



        $data = [
            'pageTitle' => 'Weekly Inspection Managemnet',
            'view_file' => 'inspection/weekly/list_atar_form',
            'site_title' => 'Weekly Inspection Managemnet',
            'current_menu' => 'ATAR',
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'drophsecat' => $dropinscat,
            'status_drop_proj' => $status_drop,
            'status_main' => $status_main,
            'getdashdata' => $getdashdata,
            'ajaxurl' => 'inspection/weekly/listAtarInspection?Status=' . $Status . '&company_id=' . $company_id . '&area_id=' . $area_id . '&building_id=' . $building_id  . '&cat_id=' . $cat_id . '&project_id='  . $project_id . '&Department=' . $department_id . '&Start_Date=' . $Start_Date . '&End_Date=' . $End_Date,
        ];
        $this->template->load_table_exp_template($data);
    }

    public function listAtarInspection()
    {
        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main, $weekly_status;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_ITEMS . ' as h';
        $column_order = array(
            null,
            'insp_atar_uni_id',
            'inspection.ins_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)',
            null
        );

        $column_search = array(
            'insp_atar_uni_id',
            'inspection.ins_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
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

            $company_id = postData($mappedData, 'company_id');
            $area_id = postData($mappedData, 'area_id');
            $building_id = postData($mappedData, 'building_id');
            $department_id = postData($mappedData, 'department_id');
            $project_id = postData($mappedData, 'project_id');
            $emp_name = postData($mappedData, 'emp_name');
            $ins_cat = postData($mappedData, 'ins_cat_id');
            $startdate = postData($mappedData, 'start_date');
            $enddate = postData($mappedData, 'end_date');
            $searchStatus = postData($mappedData, 'NotifyStatus');
            $searchMainStatus = postData($mappedData, 'mainStatus');

            $options['where_new'] = [];
            $where = [];

            if ($company_id > 0) {
                $options['where_new']['h.insp_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.insp_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.insp_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.insp_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.insp_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.fk_item_cat_id'] = $ins_cat;
            }




            if ($searchStatus >= 0) {
                $options['where_new']["h.insp_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.insp_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.insp_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }

        ///////////////////////////filter end


        if (in_array($user_type, $insPermission['view_supadmin'])) {

            $options['where_new']['insp_app_status !='] = 0;
        } elseif (in_array($user_type, $insPermission['view_ad'])) {

            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {

            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['fix'])) {


            $options['where_new']['insp_app_status !='] = 0;
            $options['orwhere_new']['insp_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $insPermission['approve'])) {

            $options['where_new']['insp_app_status !='] = 0;
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {


            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_reporter_id'] =  $userid;
        }

        $options['where_new']['insp_status'] =  'Y';
        $options['where_new']['inspection.ins_status'] =  'Y';
        $options['where_new']['send_to_atar'] =  1;


        // print_r( $where);exit;

        $options['select'] = [
            'insp_item_auto_id ',
            'insp_atar_uni_id ',
            'h.fk_insp_main_auto_id',
            'inspection.ins_id',
            'h.insp_app_status',
            'h.insp_reporter_id',
            'FN_COMP_NAME(insp_comp_id) as comp_name',
            'FN_AREA_NAME(insp_area_id) as area_name',
            'FN_BUILD_NAME(insp_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.insp_project_id) as proj_name',
            'cat.category as insp_cat_name',
            'h.insp_item_condition',
            'h.insp_report_datetime',
            'FN_INSP_STATUS(h.insp_app_status) as status_Name'
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.fk_item_cat_id', 'left'];
        $options['join'][INSP_WEEKLY_FLOW_SEE . ' as inspection'] = ['inspection.ins_auto_id = h.fk_insp_main_auto_id', 'left'];

        $listUsee = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (isset($listUsee) && !empty($listUsee)) {
            foreach ($listUsee as $ltKey => $ltVal) {
                $i++;
                $action = $ins_risk = $ins_type = '';
                $id = encryptval($ltVal->insp_item_auto_id);
                $mid = encryptval($ltVal->fk_insp_main_auto_id);

                $stat = $ltVal->insp_app_status;
                $ins_reporter_id = $ltVal->insp_reporter_id;
                if (((in_array($user_type, $insPermission['view_supadmin'])) || (in_array($user_type, $insPermission['view_ad']) || $userid == $ins_reporter_id)) && ($stat == 1 || $stat == 0)) {

                    // $action .= " " . anchor('inspection/weekly/add_atar/' . $mid . '/' . $id, '<i class="fa fa-edit"></i>', array('class' => '', 'title' => 'Edit'));
                }
                if (in_array($user_type, $insPermission['view_supadmin']) || (in_array($user_type, $insPermission['view_ad']))) {

                    $action .= "  " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteAtar', 'title' => 'Delete', 'delid' => $id));
                }

                // if (((in_array($user_type, $insPermission['view_supadmin'])) || (in_array($user_type, $insPermission['view_ad']) || $userid == $ins_reporter_id)) && ($stat == 1 || $stat == 0)) {

                $action .= " " . anchor('inspection/weekly/view/' . $id, '<i class="fa fa-eye"></i>', array('title' => 'view'));
                // }
                // $action .= " " . anchor('inspection/monthly/pdf/' . $id, '<i class="fas fa-file-pdf" aria-hidden="true"></i>', array('class' => '', 'title' => 'PDF', 'target' => '_blank'));




                $rows = [];
                $rows[] = $ltVal->insp_atar_uni_id;
                $rows[] = $ltVal->ins_id;
                $rows[] = ucfirst($ltVal->comp_name);
                $rows[] = ucfirst($ltVal->area_name);
                $rows[] = ucfirst($ltVal->building_name);
                $rows[] = ucfirst($ltVal->dep_name);
                $rows[] = ucfirst($ltVal->proj_name);
                $rows[] = ucfirst($ltVal->insp_cat_name);
                $rows[] = $weekly_status[$ltVal->insp_item_condition];
                $rows[] = date('d-m-Y H:i:s', strtotime($ltVal->insp_report_datetime));
                $rows[] = $ltVal->status_Name;

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

    public function add_atar($mid = '', $id)
    {
        $adid = decryptval($id);
        $did = decryptval($mid);


        global $owner_list, $owner_engineer_list, $EPC_list, $weekly_status;
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $hsecatOptn['where'] = [
            'category_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'id', 'category', 'Select Category');


        $insOptn['where'] = [
            'ins_auto_id' => $did,
        ];

        $itemOptn['where'] = [
            'fk_insp_main_auto_id ' => $did,
            'insp_item_auto_id ' => $adid,
        ];
        $getUseeActdatas = '';
        $getItemdatas = '';
        if ($did != '') {
            $getUseeActdatas = $this->common_model->getAlldata(INSP_WEEKLY_FLOW_SEE, ['*'], $insOptn);
            $getItemdatas = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, ['*'], $itemOptn);
        }

        $data = array(
            'view_file' => 'inspection/weekly/add_form',
            'current_menu' => 'ATAR',
            'getProgramdatas' => $getUseeActdatas,
            'getItemdatas' => $getItemdatas,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'dropweeklystatus' => $weekly_status,
            'hsecatDetails' => $drophsecat,
            'owner_list' => $owner_list,
            'owner_engineer_list' => $owner_engineer_list,
            'EPC_list' => $EPC_list,
            'isatar' => true
        );

        $this->template->load_common_template($data);
    }

    public function Weekly_atar_exportpdf()
    {

        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_ITEMS . ' as h';
        $column_order = array(
            null,
            'insp_atar_uni_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'h.insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)',
            null
        );

        $column_search = array(
            'insp_atar_uni_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'h.insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $where = [];
        $request = $this->input->get();





        if ($request != FALSE && count($request) > 0) {

            $company_id = postData($request, 'company_id');
            $area_id = postData($request, 'area_id');
            $building_id = postData($request, 'building_id');
            $department_id = postData($request, 'department_id');
            $project_id = postData($request, 'project_id');
            $emp_name = postData($request, 'emp_name');
            $ins_cat = postData($request, 'ins_cat_id');

            $startdate = postData($request, 'start_date');
            $enddate = postData($request, 'end_date');
            $searchStatus = postData($request, 'NotifyStatus');
            $searchMainStatus = postData($request, 'mainStatus');

            $options['where_new'] = [];
            $where = [];


            if ($company_id > 0) {
                $options['where_new']['h.insp_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.insp_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.insp_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.insp_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.insp_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.fk_item_cat_id'] = $ins_cat;
            }


            if ($searchStatus >= 0) {
                $options['where_new']["h.insp_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.insp_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.insp_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }


        if (in_array($user_type, $insPermission['view_supadmin'])) {

            $options['where_new']['insp_app_status !='] = 0;
        } elseif (in_array($user_type, $insPermission['view_ad'])) {

            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {

            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['fix'])) {


            $options['where_new']['insp_app_status !='] = 0;
            $options['orwhere_new']['insp_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $insPermission['approve'])) {

            $options['where_new']['insp_app_status !='] = 0;
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {


            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_reporter_id'] =  $userid;
        }

        $options['where_new']['insp_status'] =  'Y';
        $options['where_new']['inspection.ins_status'] =  'Y';
        $options['where_new']['send_to_atar'] =  1;




        // print_r( $where);exit;

        $options['select'] = [
            'insp_atar_uni_id',
            'h.insp_app_status',
            'FN_COMP_NAME(insp_comp_id) as comp_name',
            'FN_AREA_NAME(insp_area_id) as area_name',
            'FN_BUILD_NAME(insp_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.insp_project_id) as proj_name',
            'cat.category as ins_cat_name',
            'h.insp_item_condition',
            'h.insp_report_datetime',
            'FN_INSP_STATUS(h.insp_app_status) as status_Name'
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.fk_item_cat_id', 'left'];
        $options['join'][INSP_WEEKLY_FLOW_SEE . ' as inspection'] = ['inspection.ins_auto_id = h.fk_insp_main_auto_id', 'left'];

        $listUsee = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);



        // Preparing the data for the PDF

        $header = [
            'SL No',
            'Atar ID',
            'Company',
            'Area',
            'Building/Block/Direction',
            'Department',
            'Project',
            'Category',
            'Inspection Type',
            'Reported Date & Time',
            'Status'
        ];
        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $listUsee,
            'pagetitle' => "Weekly Inspection ATAR List",
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
        $html = $this->load->view('inspection/weekly/atar_export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "Weekly_list.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function Weekly_atar_exportexcel()
    {

        global $insPermission, $risk_rating_batch, $ins_type_list, $status_main, $weekly_status, $status_drop;
        $getdashdata = $this->input->get();

        $user_type = $_SESSION['emp_details']->EMP_USERTYPE_ID;
        $user_clid = $_SESSION['emp_details']->EMP_COMP_ID;
        $user_alid = $_SESSION['emp_details']->EMP_AREA_ID;
        $user_depid = $_SESSION['emp_details']->EMP_DEPT_ID;

        $userid = getCurrentUserid();


        $table = INSP_WEEKLY_ITEMS . ' as h';
        $column_order = array(
            null,
            'insp_atar_uni_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'h.insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)',
            null
        );

        $column_search = array(
            'insp_atar_uni_id',
            'FN_COMP_NAME(insp_comp_id)',
            'FN_AREA_NAME(h.insp_area_id)',
            'FN_BUILD_NAME(h.insp_building_id)',
            'FN_PROJECT_NAME(h.insp_project_id)',
            'FN_GET_DEPARTMENT_NAME(h.insp_dept_id)',
            'cat.category',
            'h.insp_item_condition',
            "DATE_FORMAT(insp_report_datetime,'%d-%m-%Y %H:%i:%s')",
            'FN_INSP_STATUS(insp_app_status)'
        );

        $order = array('h.CREATED_ON' => 'desc');

        /////Filter
        $mappedData = [];
        $where = [];
        $request = $this->input->get();





        if ($request != FALSE && count($request) > 0) {

            $company_id = postData($request, 'company_id');
            $area_id = postData($request, 'area_id');
            $building_id = postData($request, 'building_id');
            $department_id = postData($request, 'department_id');
            $project_id = postData($request, 'project_id');
            $emp_name = postData($request, 'emp_name');
            $ins_cat = postData($request, 'ins_cat_id');

            $startdate = postData($request, 'start_date');
            $enddate = postData($request, 'end_date');
            $searchStatus = postData($request, 'NotifyStatus');
            $searchMainStatus = postData($request, 'mainStatus');

            $options['where_new'] = [];
            $where = [];


            if ($company_id > 0) {
                $options['where_new']['h.insp_comp_id'] = $company_id;
            }
            if ($area_id > 0) {
                $options['where_new']['h.insp_area_id'] = $area_id;
            }
            if ($building_id > 0) {
                $options['where_new']['h.insp_building_id'] = $building_id;
            }
            if ($department_id > 0) {
                $options['where_new']['h.insp_dept_id'] = $department_id;
            }
            if ($project_id > 0) {
                $options['where_new']['h.insp_project_id'] = $project_id;
            }
            if ($ins_cat > 0) {
                $options['where_new']['h.fk_item_cat_id'] = $ins_cat;
            }


            if ($searchStatus >= 0) {
                $options['where_new']["h.insp_app_status"] = $searchStatus;
            }

            if ($searchMainStatus == 0) {
                $options['where_new']["h.insp_app_status !="] = '3';
            } else if ($searchMainStatus == 1) {
                $options['where_new']["h.insp_app_status"] = '3';
            }

            if ($startdate != '') {
                $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

                $options['where_new']['h.CREATED_ON >='] = $thstartdate;
            }
            if ($enddate != '') {
                $enddate = date('Y-m-d', strtotime($enddate));
                $options['where_new']['h.CREATED_ON <='] = $enddate . ' 23:59:59';
            }
        }


        if (in_array($user_type, $insPermission['view_supadmin'])) {

            $options['where_new']['insp_app_status !='] = 0;
        } elseif (in_array($user_type, $insPermission['view_ad'])) {

            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['view_assigner'])) {

            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['fix'])) {


            $options['where_new']['insp_app_status !='] = 0;
            $options['orwhere_new']['insp_reporter_id'] =  $userid;
        } elseif (in_array($user_type, $insPermission['approve'])) {

            $options['where_new']['insp_app_status !='] = 0;
            //$options['where_new']['ins_comp_id'] =  $user_clid;
        } elseif (in_array($user_type, $insPermission['approve_final'])) {


            $options['where_new']['insp_app_status !='] = 0;
            // $options['where_new']['ins_comp_id'] =  $user_clid;
        } else {
            $options['where_new']['insp_app_status !='] = 0;
            $options['where_new']['insp_reporter_id'] =  $userid;
        }

        $options['where_new']['insp_status'] =  'Y';
        $options['where_new']['inspection.ins_status'] =  'Y';
        $options['where_new']['send_to_atar'] =  1;




        // print_r( $where);exit;

        $options['select'] = [
            'insp_atar_uni_id',
            'h.insp_app_status',
            'FN_COMP_NAME(insp_comp_id) as comp_name',
            'FN_AREA_NAME(insp_area_id) as area_name',
            'FN_BUILD_NAME(insp_building_id) as building_name',
            'FN_GET_DEPARTMENT_NAME(insp_dept_id) as dep_name',
            'FN_PROJECT_NAME(h.insp_project_id) as proj_name',
            'cat.category as ins_cat_name',
            'h.insp_item_condition',
            'h.insp_report_datetime',
            'FN_INSP_STATUS(h.insp_app_status) as status_Name'
        ];

        $options['join'][INSP_WEEKLY_CAT . ' as cat'] = ['cat.id = h.fk_item_cat_id', 'left'];
        $options['join'][INSP_WEEKLY_FLOW_SEE . ' as inspection'] = ['inspection.ins_auto_id = h.fk_insp_main_auto_id', 'left'];

        $listUsee = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);


        // print_r($listUsee);


        // $last_query = $this->db->last_query();
        // echo $last_query;

        $header = [
            'SL No',
            'Atar ID',
            'Company',
            'Area',
            'Building/Block/Direction',
            'Department',
            'Project',
            'Category',
            'Inspection Type',
            'Reported Date & Time',
            'Status'
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($listUsee)) {
            foreach ($listUsee as $ltVal) {



                $row = [];
                $row[] = $i ?? '-';
                $row[] = $ltVal->insp_atar_uni_id ?? '-';
                $row[] = $ltVal->comp_name ?? '-';
                $row[] = $ltVal->area_name ?? '-';
                $row[] = $ltVal->building_name ?? '-';
                $row[] = $ltVal->dep_name ?? '-';
                $row[] = $ltVal->proj_name ?? '-';
                $row[] = $ltVal->ins_cat_name ?? '-';
                $row[] = $weekly_status[$ltVal->insp_item_condition] ?? '-';
                $row[] = $ltVal->insp_report_datetime ?? '-';
                $row[] = $status_drop[$ltVal->insp_app_status] ?? '-';
                $exportData[] = $row;

                $i++;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Weekly ATAR Form List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    public function delete_atar()
    {
        $deleteId = $this->input->post('delid');

        $updtDelete = $this->common_model->updateData(INSP_WEEKLY_ITEMS, ['insp_status' => 'N'], ['insp_item_auto_id' => decryptval($deleteId)]);

        if ($updtDelete) {

            $retData = [
                'status' => true,
                'msgs' => 'Weekly Inspection Atar has been deleted successfully'
            ];
        } else {



            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Weekly Inspection Atar! Try Again later.'
            ];
        }
        echo json_encode($retData);
        exit();
    }

    // Atar Flow Start
    public function view($id = '')
    {

        $did = decryptval($id);


        global $owner_list, $owner_engineer_list, $EPC_list, $weekly_status;
        $current_role = $_SESSION['role_id'];
        $dropcompany = getAjaxCompanyMain($current_role);
        $dropproject = getAjaxProjectMain();
        $hsecatOptn['where'] = [
            'category_status' => 'Y'
        ];
        $getAllhsecat = $this->common_model->getAlldata(INSP_WEEKLY_CAT, ['*'], $hsecatOptn);
        $drophsecat = customFormDropDown($getAllhsecat, 'id', 'category', 'Select Category');



        $itemOptn['where'] = [
            'fk_insp_main_auto_id ' => $did,
        ];
        $getUseeActdatas = '';
        $getItemdatas = '';
        if ($did != '') {
            $editData = $this->weekly->getAtarDetails_project(['insp_item_auto_id' => $did], 'row');
            $getbeforeimage = $this->weekly->getInspImagBefore_project($did);
            $getuafterallincident = $this->weekly->getInspactImageAfter_project($did);
            $getInspAssign = $this->weekly->getInspDataassign_project($did);
            $getInspReassign = $this->weekly->getInspDataReassign_project($did);
            $getInspApproval = $this->weekly->getInspDataapproval_project($did);
            $getInspApprovalFinal = $this->weekly->getInspDataapprovalfinal_project($did);
            $getInspActionTaken = $this->weekly->getInspDataactiontaken_project($did);
        }
        // echo '<pre>';print_r($getInspAssign);die;

        $data = array(
            'view_file' => 'inspection/weekly/atar_view_form',
            'current_menu' => 'View Inspection',
            'editData' => $editData,
            'getbeforeimage' => $getbeforeimage,
            'getuafterallimage' => $getuafterallincident,
            'getInspAssign' => $getInspAssign,
            'getInspReassign' => $getInspReassign,
            'getInspApproval' => $getInspApproval,
            'getInspApprovalFinal' => $getInspApprovalFinal,
            'getInspActionTaken' => $getInspActionTaken,
            'dropcompany' => $dropcompany,
            'dropproject' => $dropproject,
            'dropweeklystatus' => $weekly_status,
            'hsecatDetails' => $drophsecat,
            'owner_list' => $owner_list,
            'owner_engineer_list' => $owner_engineer_list,
            'EPC_list' => $EPC_list,
        );


        $this->template->load_common_template($data);
    }

    public function saveStatus($id = '')
    {
        global $cronTo_Supervisor_Escalation_Times;

        $did = $id;

        // echo '<pre>';
        // print_r($this->input->post());
        // exit();

        $statusDatas = $this->input->post('insp');

        $sesData = $_SESSION['user_details'];

        $insp_supervisor_type_id = getCurrentUserGroupId();
        $insp_supervisor_id = getCurrentUserid();

        $unitDatasId = postData($statusDatas, 'insp_assigner_id');

        $optWhr['where'] = [
            'LOGIN_ID' => $unitDatasId
        ];
        $optWhr['return_type'] = 'row';
        $gethoddatas = $this->common_model->getAlldata(USER_LOG, ['*'], $optWhr);
        $hodDatasGroupId = $gethoddatas->USER_TYPE_ID;

        $givenDate = date(
            "Y-m-d",
            strtotime(postData($statusDatas, "insp_assigner_target_date"))
        );
        $currenttime = date("H:i:s");
        $currentdate = date("Y-m-d");

        /*SAME DAY AS NEW TARGET DATE*/
        $timestamp1 = strtotime($givenDate);
        $timestamp2 = strtotime(date("Y-m-d"));

        if ($timestamp1 === $timestamp2) {
            $finalTargetDateTime = $givenDate . " 23:59:00";
        } else {

            $finalTargetDateTime = $givenDate . " " . $currenttime;
        }

        /*SAME DAY AS NEW TARGET DATE*/

        $cronDate = date("Y-m-d");

        ///target date with current time escalation
        ///////atar cron count
        $optWhrcnt['where'] = [
            'insp_item_auto_id' => $did
        ];
        $optWhr['return_type'] = 'row';
        $getStatdatascronsent = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, ['*'], $optWhrcnt);


        $cron_sent_times = $getStatdatascronsent[0]->cron_sent_times;

        ////////times of cron      

        if ($cron_sent_times < $cronTo_Supervisor_Escalation_Times) {
            $sentCnt = $cron_sent_times + 1;
        } else {
            $sentCnt = $cron_sent_times;
        }
        $stat = '4';
        if ($sentCnt == 1) {
            $stat = postData($statusDatas, 'insp_app_statusS');
        }
        /////end      
        $useedatas = [
            // 'insp_risk_id' => postData($statusDatas, 'insp_risk_id'),
            'insp_supervisor_id' => $insp_supervisor_id,
            'insp_supervisor_type_id' => $insp_supervisor_type_id,
            'insp_supervisor_risk_id' => postData($statusDatas, 'insp_risk_id'),
            'insp_supervisor_desc' => postData($statusDatas, 'assigner_desc'),
            'insp_supervisor_date' => $currentdate,

            'insp_assigner_target_date' => date('Y-m-d', strtotime(postData($statusDatas, 'insp_assigner_target_date'))),
            'insp_assigner_type_id' => $hodDatasGroupId,
            'insp_assigner_id' => $unitDatasId,

            'insp_app_status' => (int)$stat,

            "insp_final_tar_date_time" => $finalTargetDateTime,
            "cron_sent_date" => $cronDate,
            "cron_sent_times" => $sentCnt,
        ];

        $useedatas2 = [
            'is_edit' => 1,
        ];
        // echo '<pre>'; print_r($useedatas);die;
        if ($did != '') {
            $upWhere = [
                'insp_item_auto_id' => $did
            ];
            $upWhere2 = [
                'ins_auto_id' =>  postData($statusDatas, 'fk_insp_main_auto_id'),
            ];
            $updtProfile = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $useedatas, $upWhere);
            $updttProfile = $this->common_model->updateData(INSP_WEEKLY_FLOW_SEE, $useedatas2, $upWhere2);
        }

        if ($updtProfile) {
            //escalation

            $givenDate = date("Y-m-d", strtotime(postData($statusDatas, "insp_assigner_target_date")));
            $givenDateTime = new DateTime($givenDate);
            $currentDateTime = new DateTime();
            $givenDateTime->setTime($currentDateTime->format("H"), $currentDateTime->format("i"), $currentDateTime->format("s"));
            $finalTargetDateTime = $givenDateTime->format("Y-m-d H:i:s");
            $prevStatus = postData($statusDatas, "ins_app_status");

            if ($prevStatus == 8) {
                $atar_assigned_type_id = 2;
            } else {
                $atar_assigned_type_id = 1;
            }


            $useeCAEscalation = [
                "fk_insp_auto_main_id" => $did,
                'fk_insp_cat_id' => postData($statusDatas, 'fk_item_cat_id'),
                'fk_insp_subcat_id' => postData($statusDatas, 'fk_item_subcat_id'),
                'fk_insp_subcatdata_id' => postData($statusDatas, 'fk_item_subcatdata_id'),
                "fk_insp_main_stat" => $prevStatus,
                "fk_insp_supervisor_login_id" => $insp_supervisor_id,
                "fk_insp_supervisor_role_id" => $insp_supervisor_type_id,
                "fk_insp_supervisor_des_id" => postData($sesData, 'DESIGNATIONID'),
                "fk_insp_supervisor_desc" => postData($statusDatas, "assigner_desc"),

                "fk_new_assigner_type_id" => $hodDatasGroupId,
                "fk_new_assigner_id" => $unitDatasId,
                "fk_insp_new_target_date" => date("Y-m-d", strtotime(postData($statusDatas, "insp_assigner_target_date"))),
                "fk_insp_new_target_datetime" => $finalTargetDateTime,
                "insp_assigned_type_id" => $atar_assigned_type_id,
            ];

            $updtPr = $this->common_model->updateData(INSP_WEEKLY_ESL_LOG, $useeCAEscalation);


            //Risk LOg

            $fk_item_cat_id = postData($statusDatas, 'fk_item_cat_id');
            $fk_item_subcat_id = postData($statusDatas, 'fk_item_subcat_id');
            $fk_item_subcatdata_id = postData($statusDatas, 'fk_item_subcatdata_id');

            $risk_log = [
                'fk_insp_risk_auto_main_id' => $did,
                'fk_risk_cat_id' => postData($statusDatas, 'fk_item_cat_id'),
                'fk_risk_subcat_id' => postData($statusDatas, 'fk_item_subcat_id'),
                'fk_risk_subcatdata_id' => postData($statusDatas, 'fk_item_subcatdata_id'),
                'insp_risk_assigned_type_id' => 2,
                'fk_insp_main_stat' => (int)$stat,
                'insp_risk_assigned_type_id' => $atar_assigned_type_id + 1,
                'fk_insp_main_stat' => $prevStatus,
                'risk_rating' => postData($statusDatas, 'obs_risk_id'),
                'fk_insp_emp_login_id' => $insp_supervisor_id,
                'fk_insp_emp_role_id' => $insp_supervisor_type_id,
                'fk_insp_emp_des_id' => postData($sesData, 'DESIGNATIONID'),
                'fk_insp_emp_desc' => postData($sesData, 'assigner_desc'),
            ];

            $this->common_model->updateData(INSP_WEEKLY_RISK_FLOW, $risk_log);

            /////end
            ///status 4 notification
            $optWhr['where'] = [
                'insp_item_auto_id' => $did
            ];
            $optWhr['return_type'] = 'row';
            $getStatdatas = $this->common_model->getAlldata(INSP_WEEKLY_ITEMS, ['*'], $optWhr);
            $StatId = $getStatdatas->insp_app_status;
            if ($StatId == 4) {
                sendWeeklyInspNotification($did);
            }
            ////
            if ($did != '') {
                $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Updated</div>');
            } else {
                $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Created</div>');
            }
            redirect('inspection/weekly/weekly_atar_list');
        } else {
            if ($did != '') {
                $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Updated</div>');
            } else {
                $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Created</div>');
            }
            redirect('inspection/weekly/weekly_atar_list');
        }
    }

    public function saveCADetails($id = '')
    {

        $did = $id;
        $sesData = $_SESSION['user_details'];
        $afterFixDatas = $this->input->post('useeafterfix');
        $statusDatas = $this->input->post('insp');
        $this->form_validation->set_rules('useeafterfix[insp_assigner_desc]', 'Description', 'required|trim');

        if ($this->form_validation->run()) {
            $useeCA = [
                'insp_assigner_ca_submitted_date' => date('Y-m-d'),
                'insp_app_status' => 5,
                'insp_assigner_desc' => postData($afterFixDatas, 'insp_assigner_desc')
            ];

            $current_role = $_SESSION['role_id'];
            //action taken log 
            $useeCAlog = [
                'fk_insp_auto_main_id' => $did,
                'fk_insp_assigner_login_id' => $_SESSION['userinfo']->LOGIN_ID,
                'fk_insp_cat_id' => postData($statusDatas, 'fk_item_cat_id_at'),
                'fk_insp_subcat_id' => postData($statusDatas, 'fk_item_subcat_id_at'),
                'fk_insp_subcatdata_id' => postData($statusDatas, 'fk_item_subcatdata_id_at'),
                'fk_insp_assigner_role_id' => $current_role,
                'fk_insp_assigner_des_id' => postData($sesData, 'DESIGNATIONID'),
                'fk_insp_ca_desc' => postData($afterFixDatas, 'insp_assigner_desc'),
                'fk_insp_ca_datetime' => date('Y-m-d H:i:s'),
            ];
            $ca_log_id = $this->common_model->updateData(INSP_WEEKLY_ACTION_LOG, $useeCAlog);

            if (isset($_FILES) && !empty($_FILES)) {
                $imgUActDatasImage = uploadMultipleimage($_FILES, 'assets/images/modules/atarusee/images/incident/after/', 'useeafterfix');
            }

            if ($imgUActDatasImage != FALSE && count($imgUActDatasImage) > 0) {
                foreach ($imgUActDatasImage as $fileAct) {
                    $updateUploadActData = [
                        'fk_insp_main_id' => $did,
                        'insp_file_type' => '3',
                        'fk_ca_log_id' => $ca_log_id,
                        'insp_assigner_id' => $_SESSION['userinfo']->LOGIN_ID,
                        'insp_filename' => postData($fileAct, 'uploadname'),
                        'insp_filetype' => postData($fileAct, 'uploadtype'),
                        'insp_file_ext' => postData($fileAct, 'uploadextension'),
                        'insp_file_size' => postData($fileAct, 'filesize'),
                        'insp_file_path' => postData($fileAct, 'uploadpath')
                    ];

                    $this->common_model->updateData(INSP_WEEKLY_IMAGE_SEE, $updateUploadActData);
                }
            }
            if ($did != '') {
                $upWhere = [
                    'insp_item_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $useeCA, $upWhere);
            } else {
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $useeCA);
            }

            if ($updtProfile) {
                sendWeeklyInspNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            }
        } else {
            $this->weekly_atar_list($id);
        }
    }

    public function saveApproveDetails($id = '')
    {

        $did = $id;

        $afterFixapproveDatas = $this->input->post('inspapprove');
        $approve = postData($afterFixapproveDatas, 'approve');
        $reject = postData($afterFixapproveDatas, 'reject');
        $this->form_validation->set_rules('inspapprove[insp_hsse_es_type_id]', 'Reason', 'required|trim');

        if ($this->form_validation->run()) {
            //     print_r($afterFixDatas);exit;
            if ($approve == "Approve") {
                $useeApprove = [
                    'insp_app_status' => 3,
                    'is_closed' => 1,
                    'insp_hsse_es_id' => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    'insp_hsse_es_type_id' => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    'insp_hsse_es_appr_rej_desc' => postData($afterFixapproveDatas, 'insp_hsse_es_type_id')
                ];
                $useeApproveNew = [
                    "fk_insp_app_id" => $did,

                    "approval_type_id" => 1,

                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),

                    "approver_login_id" => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_insp_so_des_id"),
                    "fk_approver_app_desc" => postData($afterFixapproveDatas, "insp_hsse_es_type_id"),


                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_insp_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            } elseif ($reject == "Reject") {
                $useeApprove = [
                    'insp_app_status' => 7,
                    'insp_hsse_es_id' => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    'insp_hsse_es_type_id' => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    'insp_hsse_es_appr_rej_desc' => postData($afterFixapproveDatas, 'insp_hsse_es_type_id')
                ];
                $useeApproveNew = [
                    "fk_insp_app_id" => $did,

                    "approval_type_id" => 2,
                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_insp_so_des_id"),
                    "fk_approver_rej_desc" => postData($afterFixapproveDatas, "insp_hsse_es_type_id"),

                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_insp_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            }


            if ($did != '') {
                $upWhere = [
                    'insp_item_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $useeApprove, $upWhere);
                $updtProfiles = $this->common_model->updateData(INSP_WEEKLY_APP_ESL_LOG, $useeApproveNew);
            }

            if ($updtProfile) {
                sendWeeklyInspNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            }
        } else {
            $this->weekly_atar_list($id);
        }
    }

    public function saveFinalApproveDetails($id = '')
    {

        $did = $id;

        $afterFixapproveDatas = $this->input->post('useeapprove');
        $approve = postData($afterFixapproveDatas, 'approve');
        $reject = postData($afterFixapproveDatas, 'reject');
        $this->form_validation->set_rules('useeapprove[insp_hsse_type_id]', 'Reason', 'required|trim');

        if ($this->form_validation->run()) {

            if ($approve == "Approve") {
                $useeApprove = [
                    'insp_app_status' => 3,
                    'is_closed' => 1,
                    'closed_date' => date('Y-m-d'),
                    'insp_hsse_id' => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    'insp_hsse_type_id' => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    'insp_hsse_appr_rej_desc' => postData($afterFixapproveDatas, 'insp_hsse_type_id')
                ];
                $useeApproveNew = [
                    "fk_insp_app_id" => $did,
                    "approval_type_id" => 3,

                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_insp_so_des_id"),
                    "fk_approver_app_desc" => postData($afterFixapproveDatas, "insp_hsse_type_id"),


                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_insp_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            } elseif ($reject == "Reject") {
                $useeApprove = [
                    'insp_app_status' => 10,
                    'insp_hsse_id' => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    'insp_hsse_type_id' => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    'insp_hsse_appr_rej_desc' => postData($afterFixapproveDatas, 'insp_hsse_type_id')
                ];
                $useeApproveNew = [
                    "fk_insp_app_id" => $did,

                    "approval_type_id" => 4,
                    "fk_assigner_login_id" => postData($afterFixapproveDatas, "aisi_login_id"),
                    "fk_assigner_role_id" => postData($afterFixapproveDatas, "aisi_role_id"),
                    "fk_assigner_des_id" => postData($afterFixapproveDatas, "aisi_des_id"),
                    "assigner_desc" => postData($afterFixapproveDatas, "aisi_desc"),


                    "approver_login_id" => postData($afterFixapproveDatas, "fk_insp_so_login_id"),
                    "approver_role_id" => postData($afterFixapproveDatas, "fk_insp_so_role_id"),
                    "approver_des_id" => postData($afterFixapproveDatas, "fk_insp_so_des_id"),
                    "fk_approver_rej_desc" => postData($afterFixapproveDatas, "insp_hsse_type_id"),

                    "approver_report_dt" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "aisi_report_dt"))),
                    "fk_assigner_datetime" => date("Y-m-d H:i:s", strtotime(postData($afterFixapproveDatas, "fk_insp_so_datetime"))),
                    "assigned_target_date" => date("Y-m-d", strtotime(postData($afterFixapproveDatas, "aisi_target_date"))),
                ];
            }

            //  print_r($useeApprove);exit;
            if ($did != '') {
                $upWhere = [
                    'insp_item_auto_id' => $did
                ];
                $updtProfile = $this->common_model->updateData(INSP_WEEKLY_ITEMS, $useeApprove, $upWhere);
                $updtProfiles = $this->common_model->updateData(INSP_WEEKLY_APP_ESL_LOG, $useeApproveNew);
            }

            if ($updtProfile) {
                // sendObsNotification($did);
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Inspection has been Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            } else {
                if ($did != '') {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Updated</div>');
                } else {
                    $this->session->set_flashdata('inspectionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Error!</span> Inspection cannot be Created</div>');
                }
                redirect('inspection/weekly/weekly_atar_list');
            }
        } else {
            $this->weekly_atar_list($id);
        }
    }
}
