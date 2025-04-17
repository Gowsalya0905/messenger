<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

    public function __construct() {

        parent::__construct();
        isLogin();

        $this->load->model('common/common_model');
    }

    public function addSlider() {

     //   return redirect('dashboard');
      
        $loctOptn['where'] = [
            'SLI_STATUS' => 'Y'
        ];
        $getAllslider = $this->common_model->getAlldata(DASH_SLIDER, ['*'], $loctOptn);

       // echo $this->db->last_query();exit;

        $data = array(
            'view_file' => 'dashboard/slider',
            'current_menu' => 'Add Slider',
            'getSlider' => $getAllslider
        );

        // echo "<pre>";
        // print_r($data);exit;

        $this->template->load_common_template($data);
    }

    public function deleteParticularslider() {
        $certiId = $this->input->post('certiId');
        $delCerti = $this->common_model->updateData(DASH_SLIDER, ['SLI_STATUS' => 'N'], ['SLIDER_ID' => $certiId]);

        if ($delCerti) {
            if ($certiId == $delCerti) {
                $datResp = [
                    'status' => true,
                    'msg' => 'Image Deleted Successfully',
                ];
            } else {
                $datResp = [
                    'status' => false,
                    'msg' => 'Error in Deleting Image',
                ];
            }
        } else {
            $datResp = [
                'status' => false,
                'msg' => 'Error in Deleting Image',
            ];
        }
        echo json_encode($datResp);
    }

    public function insertSlider($id = "") {
//echo "<pre>";
//        print_r($this->input->post());
//        print_r($_FILES);
//        exit;
        $main_id = "";
        $certiDatas = $this->input->post('certi');
        $did = decryptval($id);

        $imgDatasother = [];
        if (isset($_FILES) && !empty($_FILES)) {
            $imgDatasother = uploadMultipleimage($_FILES, 'assets/images/modules/slider/', 'oth_competency_certi');
        }
        // echo "<pre>";
        // print_r($imgDatasother);
//                exit;
        $imgKey = 0;
        if ($imgDatasother != FALSE && count($imgDatasother) > 0) {
            foreach ($imgDatasother as $cKey => $cVal) {


                // postData($main_id,'other_compCerti_ID')
//
                /////////image upload start

                if (!empty($imgDatasother[$cKey])) {

                    $imgUplname = $imgDatasother[$cKey]['uploadname'];
                    $imgUplpath = $imgDatasother[$cKey]['uploadpath'];
                    $imgUplext = $imgDatasother[$cKey]['uploadextension'];
                    $imgUplsize = $imgDatasother[$cKey]['filesize'];
                    $imgUpltype = $imgDatasother[$cKey]['uploadtype'];
                    $insertOthercerti['SLI_FILENAME'] = $imgUplname;
                    $insertOthercerti['SLI_FILE_PATH'] = $imgUplpath;
                    $insertOthercerti['SLI_FILETYPE'] = $imgUpltype;
                    $insertOthercerti['SLI_FILE_EXT'] = $imgUplext;
                    $insertOthercerti['SLI_FILE_SIZE'] = $imgUplsize;
                }

                $imgKey++;
                /////////image upload end
                if (isset($certiDatas[$cKey]) && $certiDatas[$cKey] != "") {

                    $main_id = $certiDatas[$cKey];
                    $othCompIDedit = postData($main_id, 'other_compCerti_ID');
                } else {
                    $othCompIDedit = "";
                }

                //   echo "<pre>";
                //  print_R($insertOthercerti);
                // print_R($othCompIDedit);
                //   print_R($othercertiWhere);
                // exit;

                if (isset($othCompIDedit) && !empty($othCompIDedit)) {
                    $othercertiWhere['SLIDER_ID'] = $othCompIDedit;

                    $updateOthercert = $this->common_model->updateData(DASH_SLIDER, $insertOthercerti, $othercertiWhere);
                    //       echo "sdgds";
                    //  echo $this->db->last_query();
                } else {

                    $updateOthercert = $this->common_model->updateData(DASH_SLIDER, $insertOthercerti);
                    //echo $this->db->last_query();
                }
            }
            // echo $this->db->last_query();
            //exit;   
        }


        if ($updateOthercert) {

            if ($did != '') {
                $data['flasmsg'] = $this->session->set_flashdata('slider', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Slide Data has been Updated</div>');
            } else {
                $data['flasmsg'] = $this->session->set_flashdata('slider', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Slide has been Created</div>');
            }
            redirect('dashboard/slider/addSlider');
        } else {

            if ($did != '') {
                $data['flasmsg'] = $this->session->set_flashdata('slider', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Sorry!</span> Slide Data cannot be Updated</div>');
            } else {
                $data['flasmsg'] = $this->session->set_flashdata('slider', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Sorry!</span> Slide Data cannot be Created</div>');
            }
            redirect('dashboard/slider/addSlider');
        }
    }

}
