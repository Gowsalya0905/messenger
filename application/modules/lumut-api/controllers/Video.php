<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */


/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Video extends CI_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('login/login_model');
        $this->load->model('common/common_model');
        $this->load->helper('common_helper');
        $this->load->helper(array('url'));
        $this->common_model->api_log($this->input->post());
    }

    public function obs_vcd(){
        //$id = $this->input->post('meet_id');
        ini_set('upload_max_filesize', '10000M');
        ini_set('post_max_size', '1000M');
        file_get_contents('php://input');
        
        $success = false;
        $message = "Error while uploading";
        $return_id = "";

        $folder_name = "incvideo";
        $target_dir =FCPATH."assets/uploads/".$folder_name."/";
        $target_dir_name ="assets/uploads/".$folder_name."/";
        
        //echo $file_path;exit;
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                chmod($target_dir, 0777);
            }
        }


        $config['upload_path'] = $target_dir;
        $config['allowed_types'] = '*';

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('video')){
            $error = array('error' => $this->upload->display_errors());
        
            $success = false;
            $return_id = '';
            $message = json_encode($error);
          //  $this->load->view('upload_form', $error);
        }else{
            $fileResult = array('upload_data' => $this->upload->data());
           
            $filename = $fileResult['upload_data']['file_name'];
            $filepath = $fileResult['upload_data']['full_path'];
            $file_size = $fileResult['upload_data']['file_size'];
	        $file_ext = $fileResult['upload_data']['file_ext'];
	        $file_type = $fileResult['upload_data']['file_type'];
            
            $data = [
                // "fk_obs_main_id" => $getAutoId,
                "obs_att_type" => 3,
                "obs_filename" => $filename,
                "obs_filetype" => $file_type,
                "obs_file_ext" => $file_ext,
                "obs_file_size" => $file_size,
                "obs_file_path" => $target_dir_name,
            ];
           
            $updatemenu = $this->common_model->updateData(OBS_IMG, $data);
        
            $success = true;
            $return_id = $updatemenu;
            $message = "Successfully Uploaded";

      //  $this->load->view('upload_success', $data);
        }
        $response["success"] = $success;  
        $response["message"] = $message;
        $response["return_id"] = $return_id;

        echo json_encode($response); 
    }

    

}
