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

    // Type master start
    public function message()
    {

        $data = array(
            'view_file' => 'message/master/type/list_type',
            'site_title' => 'Message List',
            'page_title' => 'Message List',
            'current_menu' => 'Type',
            'ajaxurl' => 'message/master/type_list',
        );

        $this->template->load_table_exp_template($data);
    }


    // public function type_list()
    // {
    //     // echo "<pre>"; print_r($_SESSION);
    //     // die;
    //     $fulterdata = $this->input->post();
    //     $login_id = $this->session->userdata('userinfo')->LOGIN_ID;
    //     $role_name = $_SESSION['name'];

    //     $table = TRN_TYPE_MAS . ' as mastype';

    //     $column_order = array(null, 'type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
    //     $column_search = array('mastype.type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

    //     $order = array('id' => 'desc');
    //     $where = ['type_status' => 'Y'];

    //     // Apply condition based on role_id
    //     if ($login_id != 1) {
    //         $where['mastype.role_id'] = $login_id;
    //     }

    //     $optns['select'] = 'mastype.*';

    //     $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

    //     $finalDatas = [];
    //     $i = 0;

    //     if (!empty($listCat)) {
    //         foreach ($listCat as $ltKey => $ltVal) {
    //             $i++;
    //             $action = '';
    //             $id = encryptval($ltVal->id);

    //             $action .= " " . anchor('message/master/add_type/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
    //             $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteType', 'title' => 'Delete', 'delt-id' => $id));

    //             $rows = [];

    //             // Show role name only if login_id is 1
    //             if ($login_id == 1) {
    //                 $rows[] = $role_name;
    //             }

    //             $rows[] = $ltVal->type;
    //             $rows[] = convertDate($ltVal->created_on);
    //             $rows[] = $action;
    //             $finalDatas[] = $rows;
    //         }
    //     }

    //     $output = array(
    //         "draw" => $this->input->post('draw'),
    //         "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $optns),
    //         "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $optns),
    //         "data" => $finalDatas,
    //     );

    //     echo json_encode($output);
    // }

    public function type_list()
    {
        $login_id = $this->session->userdata('userinfo')->LOGIN_ID;


        $table = TRN_TYPE_MAS . ' as mastype';


        $column_order = array(null, 'mastype.type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');
        $order = array('mastype.id' => 'desc');


        $mappedData = [];
        $request = $this->input->post();
        // echo "<pre>";
        // print_r($request);
        // die;


        $searchextra = postData($request, 'searchextra');
        if ($searchextra != FALSE && count($searchextra) > 0) {
            foreach ($searchextra as $search) {
                $sName = postData($search, 'name');
                $sValue = postData($search, 'value');
                $mappedData[$sName] = $sValue;
            }
        }


        if ($mappedData != FALSE && count($mappedData) > 0) {

            $person = postData($mappedData, 'per_type');
            $message = postData($mappedData, 'message');
           

            $options['where_new'] = [];
            $where = [];
            
            if ($person ) {
                $options['where_new']['mastype.role_id'] = $person;
            }
            if ($message ) {
                $options['where_new']['mastype.type'] = $message;
            }
            
            
        }
    


        $where = ['mastype.type_status' => 'Y'];


        if ($login_id != 1) {
            $where['mastype.role_id'] = $login_id;
        }


        // $options = [
        //     'select' => 'mastype.*, log.USERNAME'
        // ];
        $options['select'] = [
            'mastype.*',
            'log.USERNAME'
        ];

        // $this->db->join('login_details as log', 'mastype.role_id = log.LOGIN_ID', 'left');
        $options['join']['login_details as log'] = ['mastype.role_id = log.LOGIN_ID', 'left'];




        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);
        // echo $this->db->last_query();
        // exit;

        // echo '<pre>';
        // print_r($listCat);
        // die;
        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);


                $action .= anchor('message/master/add_type/' . $id, '<i class="fa fa-edit"></i>', ['class' => 'resptarget', 'title' => 'Edit']);
                $action .= anchor('#', '<i class="fa fa-trash"></i>', ['class' => 'deleteType', 'title' => 'Delete', 'delt-id' => $id]);

                $rows = [];


                if ($login_id == 1) {
                    $rows[] = $ltVal->USERNAME;
                }

                $rows[] = $ltVal->type;
                $rows[] = convertDate($ltVal->created_on);
                $rows[] = $action;

                $finalDatas[] = $rows;
            }
        }

        $output = [
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where, $options),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where, $options),
            "data" => $finalDatas,
        ];

        echo json_encode($output);
    }





    public function add_type($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];

        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'type_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(TRN_TYPE_MAS, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'message/master/type/add_type',
            'current_menu' => 'Add Type',
            'getCategories' => $getCategories,
        );
        $this->template->load_popup_template($data);
    }


    public function save_type()
    {
        // print_r($_SESSION);

        $login_id = $this->session->userdata('userinfo')->LOGIN_ID;

        $getrespdatas = $this->input->post('main_mas');
        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        // Validation for the 'type' field
        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[type]', 'Type', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[type]', 'Type', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

            // Prepare the data to be saved
            $respdatas = [
                'type' => postData($getrespdatas, 'type'),
                'role_id' =>  $login_id,
            ];

            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];
            }

            // Update or insert data
            $updCategory = $this->common_model->updateData(TRN_TYPE_MAS, $respdatas, $updtWhr);

            if ($updCategory) {
                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['typeflash'] = $this->session->set_flashdata('typeflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Type Master has been Updated</div>');
                } else {
                    $data['typeflash'] = $this->session->set_flashdata('typeflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Type Master has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['typeflash'] = $this->session->set_flashdata('typeflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Type Master cannot be Updated</div>');
                } else {
                    $data['typeflash'] = $this->session->set_flashdata('typeflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Type Master cannot be Created</div>');
                }
            }
        } else {
            $data = [
                'status' => 'error',
                'type' => strip_tags(form_error('main_mas[type]')),
            ];
        }
        echo json_encode($data);
    }

    public function type_exportpdf()
    {
        // Fetching data from the database
        $table = TRN_TYPE_MAS . ' as mastype';

        $column_order = array(null, 'type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['type_status' => 'Y'];

        $options['select'] = 'mastype.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Message',
            'Created Date',
        ];


        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "message List",
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
        $html = $this->load->view('message/master/type/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "message  List.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function type_exportexcel()
    {
        // Fetching data from the database
        $table = TRN_TYPE_MAS . ' as mastype';

        $column_order = array(null, 'type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['type_status' => 'Y'];

        $options['select'] = 'mastype.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Message',
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
                $row[] = $ltVal->type;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('message Type Master List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    public function delete_type()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(TRN_TYPE_MAS, ['type_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'message Type deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting message Type'
            ];
        }
        echo json_encode($retData);
    }

    // master category start

    public function category()
    {

        $data = array(
            'view_file' => 'message/master/category/list_category',
            'site_title' => 'Category List',
            'page_title' => ' Category List',
            'current_menu' => ' Category',
            'ajaxurl' => 'message/master/category_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function category_list()
    {
        // ini_set("display_errors", 1);
        $table = TRN_CAT_MAS . ' as mascat';


        $column_order = array(null, 'mastype.type', null, 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")');

        $order = array('mastype.type' => 'asc');
        $where = ['category_status' => 'Y'];

        $options = [
            'select' => 'mastype.id, mastype.type, GROUP_CONCAT(mascat.category ORDER BY mascat.category ASC SEPARATOR ", ") as categories, DATE_FORMAT(MAX(mascat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                TRN_TYPE_MAS . ' as mastype' => ['mastype.id = mascat.fk_type_id AND mastype.type_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mastype.id'
        ];

        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $id = encryptval($ltVal->id);
                $action = '';
                $action .= " " . anchor('message/master/add_category/' . $id, '<i class="fa fa-edit"></i>', array('title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteSubCategory', 'title' => 'Delete', 'delt-id' => $id));
                $rows = [];
                // $rows[] = $i;
                $rows[] = $ltVal->type;
                $rows[] = $ltVal->categories;
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

    public function add_category($id = "")
    {

        $getCategories = [];
        $did = decryptval($id);

        $options['where'] = ['type_status' => 'Y'];
        $getInsptype = $this->common_model->getAlldata(TRN_TYPE_MAS, ['*'], $options);
        $dropInsptype = customFormDropDown($getInsptype, 'id', 'type', 'Select Type');

        if ($id != '') {
            $optWhr['where'] = [
                'fk_type_id' => $did,
                'category_status !=' => 'N'
            ];
            $getCategories = $this->common_model->getAlldata(TRN_CAT_MAS, ['*'], $optWhr);
        }

        $data = array(
            'view_file' => 'message/master/category/add_category',
            'current_menu' => 'Add  Category',
            'dropCategories' => $dropInsptype,
            'editData' => $getCategories,
        );

        $this->template->load_common_template($data);
    }

    public function getcategories()
    {
        $TypeId = $this->input->post('type_id');


        if (!empty($TypeId)) {
            $this->db->select('id, category');
            $this->db->where('fk_type_id', $TypeId);
            $this->db->where('category_status', 'Y');
            $categories = $this->db->get(TRN_CAT_MAS)->result_array();
        }

        echo json_encode($categories);
    }

    public function delete_category()
    {
        $deleteId = $this->input->post('delid');


        if (!$deleteId) {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Invalid  Category ID',
            ]);
            return;
        }

        $updtDelete = $this->common_model->updateData(TRN_CAT_MAS, ['category_status' => 'N'], ['fk_type_id' => decryptval($deleteId)]);

        if ($updtDelete) {
            echo json_encode([
                'status' => 'success',
                'msgs' => 'message Category deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'msgs' => 'Error in deleting message Category ',
            ]);
        }
    }


    public function save_category()
    {

        ini_set("display_errors", 1);
        $deleteIds = json_decode($this->input->post('deleteIds'), true);

        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                $this->db->where('id', $id)
                    ->update(TRN_CAT_MAS, ['category_status' => 'N']);
            }
        }

        $getrespdatas = $this->input->post('main_mas');
        $fk_type_id = $getrespdatas['fk_type_id'];
        $categories = $getrespdatas['category'];
        $category_ids = $getrespdatas['category_id'];

        if (!empty($categories)) {
            foreach ($categories as $key => $category_name) {
                $category_id = isset($category_ids[$key]) ? $category_ids[$key] : '';
                $updtWhr = [];
                if ($category_id != '') {
                    $updtWhr = [
                        'id' => $category_id
                    ];

                    $respdatas = [
                        'fk_type_id' => $fk_type_id,
                        'category' => $category_name,
                    ];
                } else {
                    $uniqcomidwhr = [];
                    // $inspidUniq = uniqueIntRefId('CAT', TRN_CAT_MAS, 'id', 'id', $uniqcomidwhr);
                    $respdatas = [

                        'fk_type_id' => $fk_type_id,
                        'category' => $category_name,
                    ];
                }

                $updCategory = $this->common_model->updateData(TRN_CAT_MAS, $respdatas, $updtWhr);
            }
        }

        if (($updCategory)) {

            $data = [
                'status' => true
            ];
            $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Category has been Updated</div>');
        } else {
            $data = [
                'status' => false
            ];
            $data['categoryflash'] = $this->session->set_flashdata('categoryflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Category cannot be Updated</div>');
        }

        echo json_encode($data);
    }

    public function category_exportpdf()
    {
        $table = TRN_CAT_MAS . ' as mascat';


        $column_order = array(null, 'mastype.type', null, 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")');

        $order = array('mastype.type' => 'asc');
        $where = ['category_status' => 'Y'];

        $options = [
            'select' => 'mastype.type, GROUP_CONCAT(mascat.category ORDER BY mascat.category ASC SEPARATOR ", ") as categories, DATE_FORMAT(MAX(mascat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                TRN_TYPE_MAS . ' as mastype' => ['mastype.id = mascat.fk_type_id AND mastype.type_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mastype.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Type',
            ' Category',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "message Category List",
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
        $html = $this->load->view('message/master/category/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "message Category List.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function category_exportexcel()
    {
        $table = TRN_CAT_MAS . ' as mascat';


        $column_order = array(null, 'mastype.type', null, 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")', null);
        $column_search = array('mastype.type', 'DATE_FORMAT(mascat.created_on, "%d-%m-%Y")');

        $order = array('mastype.type' => 'asc');
        $where = ['category_status' => 'Y'];

        $options = [
            'select' => 'mastype.type, GROUP_CONCAT(mascat.category ORDER BY mascat.category ASC SEPARATOR ", ") as categories, DATE_FORMAT(MAX(mascat.created_on), "%d-%m-%Y") as latest_date',
            'join' => [
                TRN_TYPE_MAS . ' as mastype' => ['mastype.id = mascat.fk_type_id AND mastype.type_status = "Y"', 'INNER'],
            ],
            'group_by' => 'mastype.id'
        ];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Type',
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
                $row[] = $ltVal->type;
                $row[] = $ltVal->categories;
                $row[] = convertDate($ltVal->latest_date);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('message Category List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    // traing question_master master start
    public function question_master()
    {

        $data = array(
            'view_file' => 'message/master/question/list_question',
            'site_title' => 'message question List',
            'page_title' => 'message question  List',
            'current_menu' => 'message question master',
            'ajaxurl' => 'message/master/question_master_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function question_master_list()
    {

        // ini_set("display_errors", 1);
        $table = TRN_QUES_MAS . ' as mastype';

        $column_order = array(null, 'question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['question_status' => 'Y'];

        $optns['select'] = 'mastype.*';

        $listCat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listCat)) {
            foreach ($listCat as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->id);

                $action .= " " . anchor('message/master/add_question_master/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));
                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deletequestion', 'title' => 'Delete', 'delt-id' => $id));


                $rows = [];
                // $rows[] = $i;  
                // $rows[] = $ltVal->category_un_id;
                $rows[] = $ltVal->question;
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

    public function add_question_master($id = '')
    {
        $did = decryptval($id);
        $getCategories = [];

        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'id' => $did,
                'question_status !=' => 'N'
            ];
            $getQuestions = $this->common_model->getAlldata(TRN_QUES_MAS, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'message/master/question/add_question',
            'current_menu' => 'Add Question',
            'getCategories' => $getQuestions,
        );
        $this->template->load_popup_template($data);
    }


    public function save_question_master()
    {


        $getrespdatas = $this->input->post('main_mas');


        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[question]', 'Type', 'required|min_length[2]|max_length[255]');
        } else {
            $this->form_validation->set_rules('main_mas[question]', 'Type', 'required|min_length[2]|max_length[255]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');





            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'id' => $hidEditrespid
                ];

                $respdatas = [
                    'question' => postData($getrespdatas, 'question'),

                ];
            } else {
                $respdatas = [

                    'question' => postData($getrespdatas, 'question'),

                ];
            }



            $updCategory = $this->common_model->updateData(TRN_QUES_MAS, $respdatas, $updtWhr);


            if (($updCategory)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['questionflash'] = $this->session->set_flashdata('questionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Type Master has been Updated</div>');
                } else {
                    $data['questionflash'] = $this->session->set_flashdata('questionflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> Type Master  has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['questionflash'] = $this->session->set_flashdata('questionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Type Master  cannot be Updated</div>');
                } else {
                    $data['questionflash'] = $this->session->set_flashdata('questionflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> Type Master  cannot be Created</div>');
                }

                //          redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'question' => strip_tags(form_error('main_mas[question]')),
            ];
        }
        echo json_encode($data);
    }

    public function question_master_exportpdf()
    {
        // Fetching data from the database
        $table = TRN_QUES_MAS . ' as mastype';

        $column_order = array(null, 'question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['question_status' => 'Y'];

        $options['select'] = 'mastype.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        $header = [
            'SL.No.',
            'Question',
            'Created Date',
        ];


        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "message Question List",
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
        $html = $this->load->view('message/master/question/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "message Question List";
        $mpdf->Output($filename, 'I');
    }

    public function question_master_exportexcel()
    {
        // Fetching data from the database
        $table = TRN_QUES_MAS . ' as mastype';

        $column_order = array(null, 'question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")', null);
        $column_search = array('mastype.question', 'DATE_FORMAT(mastype.created_on,"%d-%m-%Y")');

        $order = array('id' => 'desc');
        $where = ['question_status' => 'Y'];

        $options['select'] = 'mastype.*';

        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $options);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            // 'Catgeory Unique Id',
            'Question',
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
                $row[] = $ltVal->question;
                $row[] = convertDate($ltVal->created_on);
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('message Question Master List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    public function delete_question_master()
    {
        $deleteId = $this->input->post('delid');
        $updtDelete = $this->common_model->updateData(TRN_QUES_MAS, ['question_status' => 'N'], ['id' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'message Question deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting message Question'
            ];
        }
        echo json_encode($retData);
    }
}
