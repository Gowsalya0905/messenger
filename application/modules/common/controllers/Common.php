<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Common extends CI_Controller
{

    public function index()
    {

        $this->load->view('login/login_form/add_login_form');
    }

    public function removesession($id)
    {
        $this->session->unset_userdata($id);
    }

    public function getAjaxDesignation()
    {
        $roleid = $this->input->post('roleid');

        $options['where'] = ['DESIGNATION_STATUS ' => 'Y', 'DES_USER_TYPE' => $roleid];
        $getInsptypedata = $this->common_model->getAlldata(DESIG, ['*'], $options);

        $dropInsptypedata = customFormDropDown($getInsptypedata, 'DESIGNATION_ID', 'DESIGNATION_NAME', 'Select Designation');

        $chStatus = (!empty($dropInsptypedata)) ? true : false;
        $chdatas = (!empty($dropInsptypedata)) ? $dropInsptypedata : [];
        $checkdatas = [
            'status' => $chStatus,
            'chdatas' => $chdatas,
        ];

        echo json_encode($checkdatas);
    }
}
