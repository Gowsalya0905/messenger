<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class Terminal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        error_reporting(1);
        isLogin();
    }

    public function designation_info()
    {
        $data = array(
            'pageTitle' => 'Designation',
            'view_file' => 'master/designation/list_form',
            'site_title' => 'Designation',
            'current_menu' => 'Designation List',
            'ajaxurl' => 'master/terminal/list_designation',
        );

        $this->template->load_table_exp_template($data);
    }

    public function list_designation()
    {
        $table = DES . ' as des';
        $column_order = array(null, 'DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")');
        $order = array('des.CREATED_ON' => 'desc');
        $where = ['DESIGNATION_STATUS' => 'Y'];

        $optns["select"] = ["DESIGNATION_ID", "DES_GENERATE_ID", "USER_TYPE_NAME", "DESIGNATION_NAME", "des.CREATED_ON as des_created_at"];


        $optns['join'][UTYPE . ' as typ'] = ['typ.USER_TYPE_ID = des.DES_USER_TYPE', 'left'];

        $listDes = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        if (isset($listDes) && !empty($listDes)) {
            foreach ($listDes as $ltKey => $ltVal) {

                $action = '';
                $id = $ltVal->DESIGNATION_ID;

                $uid = $ltVal->DES_USER_TYPE;

                $action .= " " . anchor('master/terminal/addDesc/' . $id . '/' . $uid, '<i class="fa fa-edit"></i>', array('class' => 'designation', 'title' => 'Edit'));

                $action .= " " . anchor('master/terminal/viewDes/' . encryptval($id), '<i class="fa fa-eye"></i>', array('title' => 'view'));


                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deleteDes', 'title' => 'Delete', 'delt-id' => $id . '-' . $uid));

                $rows = [];
                $rows[] = $ltVal->DES_GENERATE_ID;
                $rows[] = $ltVal->USER_TYPE_NAME;
                $rows[] = $ltVal->DESIGNATION_NAME;

                $rows[] = convertDate($ltVal->des_created_at);
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
        //output to json format
        echo json_encode($output);
    }

    public function addDesc($id = '', $uid = '')
    {
        $getDesdatas = [];

        $getuniqueId = uniqueRefId('DES', DES, 'DESIGNATION_ID');

        $utypOptns['where'] = [
            'USER_TYPE_STATUS' => 'Y'
        ];
        $getAllusertyp = $this->common_model->getAlldata(UTYPE . ' as utyp', ['*'], $utypOptns);
        $dropUsertp = customFormDropDown($getAllusertyp, 'USER_TYPE_ID', 'USER_TYPE_NAME', 'Select User Role');

        if ($id != '') {
            $optWhr['where'] = [
                'DESIGNATION_ID' => $id
            ];
            $optWhr['return_type'] = 'row';
            $getDesdatas = $this->common_model->getAlldata(DES, ['*'], $optWhr);
        }
        if ($uid != '') {
            $utypOptn['where'] = [
                'USER_TYPE_ID' => $uid,
                'USER_DESIGN_ID' => $id,
            ];
            $utypOptn['return_type'] = 'row';
            $utypDesdatas = $this->common_model->getAlldata(UTYPE_DESIG, ['*'], $utypOptn);
        }



        $data = array(
            'view_file' => 'master/designation/add_form',
            'current_menu' => 'Add Desgination',
            'getDesdatas' => $getDesdatas,
            'getAutoId' => $getuniqueId,
            'dropUsertp' => $dropUsertp,
            'utypDesdatas' => $utypDesdatas,
        );
        $this->template->load_popup_template($data);
    }

    public function saveDesc()
    {
        $getDesdatas = $this->input->post();


        $editId = postData($getDesdatas, 'ter_id');
        $usertypId = postData($getDesdatas, 'user_typ');
        $usertypDesId = postData($getDesdatas, 'uDes_editid');


        if ($editId > 0) {
            $this->form_validation->set_rules('des_name', 'Desgination Name', 'required|min_length[2]|max_length[100]');
        } else {
            $this->form_validation->set_rules('des_name', 'Desgination Name', 'required|min_length[2]|max_length[100]');
        }

        $getuniqueId = uniqueRefId('DES', DES, 'DESIGNATION_ID');

        if ($this->form_validation->run() == true) {

            $updateData = [
                'DESIGNATION_NAME' => postData($getDesdatas, 'des_name'),
                'DES_USER_TYPE' => postData($getDesdatas, 'user_typ'),

                'DESIGNATION_REMARK' => postData($getDesdatas, 'des_remark'),

            ];
            $updtWhr = [];
            if ($editId > 0) {

                $updtWhr = [
                    'DESIGNATION_ID' => $editId
                ];
            } else {
                $updateData['DES_GENERATE_ID'] = $getuniqueId;
            }


            $updateInfo = $this->common_model->updateData(DES, $updateData, $updtWhr);

            //////////// user typ designation start
            $usrTypins = [
                'USER_TYPE_ID' => $usertypId,
            ];
            if ($editId > 0) {
                $usrTypins['USER_DESIGN_ID'] = $editId;
                if (!empty($usertypDesId)) {
                    $this->common_model->updateData(UTYPE_DESIG, $usrTypins, ['USER_TYPE_DESIGNATION_ID' => $usertypDesId]);
                } else {
                    $this->common_model->updateData(UTYPE_DESIG, $usrTypins);
                }
                // $this->common_model->updateData(UTYPE_DESIG,$usrTypins,['USER_TYPE_DESIGNATION_ID' => $usertypDesId]);
            } else {
                $usrTypins['USER_DESIGN_ID'] = $updateInfo;
                $this->common_model->updateData(UTYPE_DESIG, $usrTypins);
            }
            //////////// user typ designation end
            if (!empty($updateInfo)) {

                $data = [
                    'status' => true
                ];
                if ($editId > 0) {
                    $data['flasmsg'] = $this->session->set_flashdata('inccatflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> Designation Name has been Updated</div>');
                } else {
                    $data['flasmsg'] = $this->session->set_flashdata('inccatflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Success!</span> Designation Name has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($editId > 0) {
                    $data['flasmsg'] = $this->session->set_flashdata('inccatflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Designation Name cannot be Updated</div>');
                } else {
                    $data['flasmsg'] = $this->session->set_flashdata('inccatflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><span>Sorry!</span> Designation Name cannot be Created</div>');
                }
            }
        } else {
            $errors = $this->form_validation->error_array();
            $data = [
                'status' => 'error',
                'errors' => $errors,
            ];
        }
        echo json_encode($data);
    }

    public function deleteDesignation()
    {

        $deleteId = $this->input->post('delid');

        $expTypids = explode("-", $deleteId);
        $desId = $expTypids[0];
        $utypid = $expTypids[1];

        $updtDelete = $this->common_model->updateData(DES, ['DESIGNATION_STATUS' => 'N'], ['DESIGNATION_ID' => $desId]);
        $updtDeleteTyp = $this->common_model->updateData(UTYPE_DESIG, ['USER_TYPE_DESIGNATION_STATUS' => 'N'], ['USER_DESIGN_ID' => $desId, 'USER_TYPE_ID' => $utypid]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'Designation Name deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting Designation Name'
            ];
        }
        echo json_encode($retData);
    }

    public function viewDes($id = '')
    {

        $editData = FALSE;
        if ($id != '') {
            $did = decryptval($id);

            $optWhr['where'] = [
                'DESIGNATION_ID' => $did
            ];
            $optWhr['return_type'] = 'row';
            $optWhr['join'][UTYPE . ' as utyp'] = ['utyp.USER_TYPE_ID = des.DES_USER_TYPE', 'left'];
            $editData = $this->common_model->getAlldata(DES . ' as des', ['*'], $optWhr);
        }

        $data = [
            'view_file' => 'master/designation/view_report',
            'current_menu' => 'View Designation',
            'editData' => $editData

        ];
        $this->template->load_common_template($data);
    }

    public function designation_exportpdf()
    {
        // Fetching data from the database
        $table = DES . ' as des';
        $column_order = array(null, 'DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")');
        $order = array('des.CREATED_ON' => 'desc');
        $where = ['DESIGNATION_STATUS' => 'Y'];

        $optns["select"] = ["DESIGNATION_ID", "DES_GENERATE_ID", "USER_TYPE_NAME", "DESIGNATION_NAME", "des.CREATED_ON as des_created_at"];


        $optns['join'][UTYPE . ' as typ'] = ['typ.USER_TYPE_ID = des.DES_USER_TYPE', 'left'];

        $result = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);


        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'Designation ID	',
            'User Role',
            'Designation Name',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "User Role Details",
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
        $html = $this->load->view('master/designation/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = " Designation Details.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function designation_exportexcel()
    {
        // Table and column configuration
        $table = DES . ' as des';
        $column_order = array(null, 'DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('DES_GENERATE_ID', 'USER_TYPE_NAME', 'DESIGNATION_NAME', 'DATE_FORMAT(des.CREATED_ON,"%d-%m-%Y")');
        $order = array('des.CREATED_ON' => 'desc');
        $where = ['DESIGNATION_STATUS' => 'Y'];

        $optns["select"] = ["DESIGNATION_ID", "DES_GENERATE_ID", "USER_TYPE_NAME", "DESIGNATION_NAME", "des.CREATED_ON as des_created_at"];


        $optns['join'][UTYPE . ' as typ'] = ['typ.USER_TYPE_ID = des.DES_USER_TYPE', 'left'];

        $listInscomp = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);


        // Header for Excel
        $header = [
            'SL.No.',
            'Designation ID	',
            'User Role',
            'Designation Name',
            'Created On',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($listInscomp)) {
            foreach ($listInscomp as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->DES_GENERATE_ID;
                $row[] = $ltVal->USER_TYPE_NAME;
                $row[] = $ltVal->DESIGNATION_NAME;
                $row[] = convertDate($ltVal->des_created_at); // Convert date to required format
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('Designation List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    //////user_role master start
    public function user_role()
    {

        $data = array(
            'view_file' => 'master/user_role/list_user_role',
            'site_title' => 'User Role',
            'current_menu' => 'User Role',
            'ajaxurl' => 'master/terminal/user_role_list',
        );

        $this->template->load_table_exp_template($data);
    }

    public function user_role_list()
    {

        ini_set("display_errors", 1);
        $table = UTYPE . ' as role';

        $column_order = array(null, 'USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")');

        $order = array('USER_TYPE_ID' => 'desc');
        $where = ['USER_TYPE_STATUS' => 'Y'];

        $optns['select'] = 'role.UTYPE_UN_ID,role.USER_TYPE_ID,role.CREATED_ON,USER_TYPE_NAME,USER_TYPE_CODE';



        $listInscomp = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        $finalDatas = [];
        $i = 0;

        if (!empty($listInscomp)) {
            foreach ($listInscomp as $ltKey => $ltVal) {
                $i++;
                $action = '';
                $id = encryptval($ltVal->USER_TYPE_ID);

                $action .= " " . anchor('master/terminal/add_user_role/' . $id, '<i class="fa fa-edit"></i>', array('class' => 'resptarget', 'title' => 'Edit'));

                $action .= " " . anchor('#', '<i class="fa fa-trash"></i>', array('class' => 'deletesubtype', 'title' => 'Delete', 'delt-id' => $id));
                //$action .= "<a href class='deleteLoctype' title='Delete' datatype='cat' href='javascript:void(0)'><i class='fa fa-times'></i></a>";

                $rows = [];
                $rows[] = $ltVal->UTYPE_UN_ID;
                $rows[] = $ltVal->USER_TYPE_NAME;
                $rows[] = $ltVal->USER_TYPE_CODE;
                $rows[] = convertDate($ltVal->CREATED_ON);
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
        //output to json format
        echo json_encode($output);
    }

    public function add_user_role($id = '')
    {
        $did = decryptval($id);
        $getSubtypedatas = [];

        if ($id != '') {
            $optWhr['return_type'] = 'row';
            $optWhr['where'] = [
                'USER_TYPE_ID' => $did,
                'USER_TYPE_STATUS !=' => 'N'
            ];
            $getSubtypedatas = $this->common_model->getAlldata(UTYPE, ['*'], $optWhr);
        }
        $data = array(
            'view_file' => 'master/user_role/add_user_role',
            'current_menu' => 'Add User Role',
            'getSubtypedatas' => $getSubtypedatas,
            // 'dropInsptype' => $dropInsptype,
        );
        $this->template->load_popup_template($data);
    }

    public function save_user_role()
    {
        // ini_set("display_errors",1);
        $getrespdatas = $this->input->post('main_mas');



        $hidEditrespid = postData($getrespdatas, 'hiddenrespID');
        if ($hidEditrespid == '') {
            $this->form_validation->set_rules('main_mas[USER_TYPE_NAME]', 'User Role Full name', 'required|min_length[3]|max_length[50]');
        } else {
            $this->form_validation->set_rules('main_mas[USER_TYPE_CODE]', ' User Role Short name', 'required|min_length[3]|max_length[50]');
        }

        if ($this->form_validation->run() == true) {
            $getrespdatas = $this->input->post('main_mas');
            $hidEditrespid = postData($getrespdatas, 'hiddenrespID');

            $uniqcomidwhr = [];
            $inspidUniq = uniqueIntRefId('ROLE', 'user_type', 'USER_TYPE_ID', 'USER_TYPE_ID', $uniqcomidwhr);
            // $insertInspec['COM_UN_ID'] = $inspidUniq;



            $updtWhr = [];
            if ($hidEditrespid != '') {
                $updtWhr = [
                    'USER_TYPE_ID' => $hidEditrespid
                ];

                $respdatas = [
                    'USER_TYPE_NAME' => postData($getrespdatas, 'USER_TYPE_NAME'),
                    'USER_TYPE_CODE' => postData($getrespdatas, 'USER_TYPE_CODE'),
                ];
            } else {
                $respdatas = [
                    'UTYPE_UN_ID' => $inspidUniq,
                    'USER_TYPE_NAME' => postData($getrespdatas, 'USER_TYPE_NAME'),
                    'USER_TYPE_CODE' => postData($getrespdatas, 'USER_TYPE_CODE'),
                ];
            }
            $updRoletyp = $this->common_model->updateData(UTYPE, $respdatas, $updtWhr);

            // echo $this->db->last_query();
            // exit;
            if (($updRoletyp)) {

                $data = [
                    'status' => true
                ];
                if ($hidEditrespid != '') {
                    $data['flasmsg'] = $this->session->set_flashdata('clsubflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> User Role has been Updated</div>');
                } else {
                    $data['flasmsg'] = $this->session->set_flashdata('clsubflash', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-check-circle"></i> Success!</span> User Role has been Created</div>');
                }
            } else {
                $data = [
                    'status' => false
                ];
                if ($hidEditrespid != '') {
                    $data['flasmsg'] = $this->session->set_flashdata('clsubflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> User Role cannot be Updated</div>');
                } else {
                    $data['flasmsg'] = $this->session->set_flashdata('clsubflash', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span><i class="fa fa-times-circle"></i> Sorry!</span> User Role cannot be Created</div>');
                }

                // redirect('master/addLocatype/');
            }
        } else {

            $data = [
                'status' => 'error',
                'user_role' => strip_tags(form_error('main_mas[user_role]')),
            ];
        }
        echo json_encode($data);
    }

    public function delete_user_role()
    {
        $deleteId = $this->input->post('delid');
        // echo $deleteId;
        $updtDelete = $this->common_model->updateData(UTYPE, ['USER_TYPE_STATUS' => 'N'], ['USER_TYPE_ID' => decryptval($deleteId)]);

        if ($updtDelete) {
            $retData = [
                'status' => true,
                'msgs' => 'User Role deleted successfully'
            ];
        } else {
            $retData = [
                'status' => false,
                'msgs' => 'Error in deleting User Role'
            ];
        }
        echo json_encode($retData);
    }

    public function user_role_exportpdf()
    {
        // Fetching data from the database
        $table = UTYPE . ' as role';
        $column_order = array(null, 'USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")');

        $order = array('USER_TYPE_ID' => 'desc');
        $where = ['USER_TYPE_STATUS' => 'Y'];

        $optns['select'] = 'role.UTYPE_UN_ID,role.USER_TYPE_ID,role.CREATED_ON,USER_TYPE_NAME,USER_TYPE_CODE';


        $result = $this->common_model->get_exportdata($table, $column_order, $column_search, $order, $where, $optns);

        // Preparing the data for the PDF
        $header = [
            'SL.No.',
            'User Role ID',
            'Full Name',
            'Short Name',
            'Created Date',
        ];

        // print_r($content);
        // exit;
        $data = [
            'header' => $header,
            'content' => $result,
            'pagetitle' => "User Role Details",
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
        $html = $this->load->view('master/user_role/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        // Output the PDF
        $filename = "User Role Details.pdf";
        $mpdf->Output($filename, 'D');
    }

    public function user_role_exportexcel()
    {
        // Table and column configuration
        $table = UTYPE . ' as role';
        $column_order = array(null, 'USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")', null);
        $column_search = array('USER_TYPE_NAME', 'USER_TYPE_CODE', 'DATE_FORMAT(role.CREATED_ON,"%d-%m-%Y")');

        $order = array('USER_TYPE_ID' => 'desc');
        $where = ['USER_TYPE_STATUS' => 'Y'];

        $optns['select'] = 'role.UTYPE_UN_ID,role.USER_TYPE_ID,role.CREATED_ON,USER_TYPE_NAME,USER_TYPE_CODE';
        // Fetching data using the common model
        $listInscomp = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where, $optns);

        // Header for Excel
        $header = [
            'SL.No.',
            'User Role ID',
            'User Role Full Name',
            'User Role Short Name',
            'Created On',
        ];

        // Prepare data for export
        $exportData = [];
        $i = 1;

        if (!empty($listInscomp)) {
            foreach ($listInscomp as $ltVal) {
                $row = [];
                $row[] = $i++;
                $row[] = $ltVal->UTYPE_UN_ID;
                $row[] = $ltVal->USER_TYPE_NAME;
                $row[] = $ltVal->USER_TYPE_CODE;
                $row[] = convertDate($ltVal->CREATED_ON); // Convert date to required format
                $exportData[] = $row;
            }
        }

        // Export data to Excel
        SimpleExcelWriter::streamDownload('User Role List.xlsx')
            ->addHeader($header)
            ->addRows($exportData);
    }

    //user_role master end
}
