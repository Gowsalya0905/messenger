<?php

defined("BASEPATH") or exit("No direct script access allowed");

use Spatie\SimpleExcel\SimpleExcelWriter as SimpleExcelWriter;

class AdIntegration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        error_reporting(1);
        isLogin();
    }

    public function index()
    {
        global $statusemp;

        $current_role = $_SESSION['role_id'];
        $dropplant = getAjaxPlantMain($current_role);

        // echo "<pre>";print_r($dropplant);exit;


        $optionsrole['where'] = [
            'USER_TYPE_STATUS' => 'Y'
        ];
        $getroles = $this->common_model->getAlldata(UTYPE, ['*'], $optionsrole);
        $getrole_drop = customFormDropDown($getroles, "USER_TYPE_ID", "USER_TYPE_NAME", "Select Roles");




        //   $optionsemp['where'] = [
        //     'EMP_STATUS' => 'Y'
        // ];
        // $getemp = $this->common_model->getAlldata(EMPL, ['EMP_AUTO_ID,EMP_ID,EMP_NAME'], $optionsemp);
        //  $getempid_drop = customFormDropDown($getemp,"EMP_ID","EMP_ID","Select Employee ID");

        //   $getempname_drop = customFormDropDown($getemp,"EMP_NAME","EMP_NAME","Select Employee Name");

        $data = [
            "view_file" => "ad_integration/list_employee",
            "site_title" => "Employee List",
            "current_menu" => "Employee List ",
            'statusemp' => $statusemp,

            'getroles' => $getrole_drop,

            // 'getempid_drop' => $getempid_drop,
            // 'getempname_drop' => $getempname_drop,
            'getPlant' => $dropplant,
            "ajaxurl" => "master/AdIntegration/list_employee",
        ];

        $this->template->load_table_exp_template($data);
    }

    public function list_employee()
    {
        $table = 'api_employee_preview_data';
        $column_order = array('EmployeeNo', 'EmployeeName', 'Created_at', null);
        $column_search = array('EmployeeNo', 'EmployeeName', 'Created_at');
        $order = array('EmployeeNo' => 'DESC');
        $where = ['api_status' => '0'];

        $listIncicat = $this->common_model->get_datatables($table, $column_order, $column_search, $order, $where);

        $finalDatas = [];
        if (isset($listIncicat) && !empty($listIncicat)) {
            foreach ($listIncicat as $ltKey => $ltVal) {

                $export_status = $action = '';
                $id = $ltVal->id;

                if (isset($ltVal->export_status) && $ltVal->export_status == 'Success') {
                    $export_status = '<span style="color: green;">Success</span>';
                } else {

                    $export_status = '<span style="color: red;">' . $ltVal->export_status . '</span>';
                }
                $action .=
                    " " .
                    anchor(
                        "master/AdIntegration/editEmployee/" . encryptval($id),
                        '<i class="fa fa-edit"></i>',
                        ["class" => "roletarget", "title" => "Edit"]
                    );

                $rows = [];
                $rows[] = $ltVal->EmployeeNo;
                $rows[] = $ltVal->EmployeeName;
                $rows[] = $ltVal->Plant;
                $rows[] = $ltVal->Location;
                $rows[] = $ltVal->Department;
                $rows[] = $ltVal->Role;
                $rows[] = $ltVal->Designation;
                $rows[] = convertDate($ltVal->Created_at);
                $rows[] = $export_status;
                $rows[] = $action;

                $finalDatas[] = $rows;
            }
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->common_model->count_all($table, $column_order, $column_search, $order, $where),
            "recordsFiltered" => $this->common_model->count_filtered($table, $column_order, $column_search, $order, $where),
            "data" => $finalDatas,
        );
        //output to json format
        echo json_encode($output);
    }


    public function editEmployee($id = "")
    {


        $did = decryptval($id);
        $current_role = $_SESSION['role_id'];
        $dropplant = getAjaxPlantMain($current_role);

        // $dropspecLoc = getSpecificLocation();


        $gendOptn["where"] = [
            "GENDER_STATUS" => "Y",
        ];
        $getGender = $this->common_model->getAlldata(GENDER, ["*"], $gendOptn);
        $dropGender = customFormDropDown(
            $getGender,
            "GENDER_ID",
            "GENDER_NAME",
            "Select Gender"
        );

        $getNationality = $this->common_model->getAlldata(NATION, ["*"]);
        $dropNation = customFormDropDown(
            $getNationality,
            "NATIONALITY_ID",
            "NATIONALITY_NAME",
            "Select Nationality"
        );
        $roleopt["where"] = [
            "USER_TYPE_STATUS " => 'Y',
        ];
        $getRole = $this->common_model->getAlldata(UTYPE, ["USER_TYPE_ID,USER_TYPE_NAME,"], $roleopt);
        $dropRole = customFormDropDown(
            $getRole,
            "USER_TYPE_ID",
            "USER_TYPE_NAME",
            "Select Role"
        );

        // $termOptn["where"] = [
        //     "TERMINAL_STATUS" => "Y",
        // ];
        // $getAllterminal = $this->common_model->getAlldata(
        //     TERM,
        //     ["*"],
        //     $termOptn
        // );
        // $dropTermname = customFormDropDown(
        //     $getAllterminal,
        //     "TER_AUTO_ID",
        //     "TERMINAL_NAME",
        //     "Select Plant Name"
        // );



        $desigOptn["where"] = [
            "DESIGNATION_STATUS" => "Y",
        ];
        $desigOptn["join"][UTYPE_DESIG . " as udes"] = [
            "udes.USER_DESIGN_ID = des.DESIGNATION_ID",
            "left",
        ];
        $desigOptn["join"][UTYPE . " as typ"] = [
            "typ.USER_TYPE_ID = udes.USER_TYPE_ID",
            "left",
        ];
        $getAlldesig = $this->common_model->getAlldata(
            DESIG . " as des",
            ["des.*,typ.USER_TYPE_NAME"],
            $desigOptn
        );
        // $dropdesignation = customFormDropDown($getAlldesig, 'DESIGNATION_ID', 'DESIGNATION_NAME', 'Select Designation');
        $finalDesiginfo[""] = ["" => "Select Designation"];
        if (isset($getAlldesig) && !empty($getAlldesig)) {
            foreach ($getAlldesig as $dsKey => $dsVal) {
                $utypName = postData($dsVal, "USER_TYPE_NAME");
                $designID = postData($dsVal, "DESIGNATION_ID");
                $finalDesiginfo[$utypName][$designID] = postData(
                    $dsVal,
                    "DESIGNATION_NAME"
                );
            }
        }
        // echo "<pre>";print_r($getRole);exit;

        $emplOptns["where"] = [
            "id" => $did,
        ];


        $emplOptns["return_type"] = "row";
        $getEmploydetails = $this->common_model->getAlldata(
            "api_employee_preview_data as emp",
            ["*"],
            $emplOptns
        );

        $type["where"] = [
            "type_status" => "Y",
        ];
        $gettype = $this->common_model->getAlldata(EMPTP, ["*"], $type);
        $droptype = customFormDropDown($gettype, "type_auto_id", "emp_type", "Select Employee Type");






        $data = [
            "view_file" => "ad_integration/edit_employee",
            "current_menu" => "Edit Employee",
            "dropGender" => $dropGender,
            "dropNation" => $dropNation,
            "dropdesignation" => $finalDesiginfo,
            "getEmploydetails" => $getEmploydetails,
            'getPlant' => $dropplant,
            'dropRole' => $dropRole,
            // 'getSpl' => $dropspecLoc,
            'gettype' => $droptype,

        ];
        $this->template->load_common_template($data);
    }

    public function insertEmployee($id = "")
    {

        $this->form_validation->set_rules(
            "emp[emp_no]",
            "Employee Number",
            "required|trim"
        );
        $empDatas = $this->input->post("emp");
        $certiDatas = $this->input->post("certi");

        if ($this->form_validation->run() == true) {
            $preview_id = decryptval($id);
            $did = '';
            $inserEmpldatas = [
                "EMP_ID" => postData($empDatas, "emp_no"),
                "EMP_NAME" => postData($empDatas, "emp_name"),
                "EMP_GENDER" => postData($empDatas, "gender"),
                "EMP_VERT_ID" => postData($empDatas, "EMP_VERT_ID"),
                "EMP_UNIT_ID" => postData($empDatas, "EMP_UNIT_ID"),

                "EMP_NATIONALITY" => postData($empDatas, "nationality"),
                "EMP_NATIONALITY_OTHER" => postData($empDatas, "nationality_other"),
                "EMP_USERTYPE_ID" => postData($empDatas, "role"),
                "EMP_DESIGNATION_ID" => postData($empDatas, "desig"),
                "EMP_DEPT_ID" => postData($empDatas, "EMP_DEPT_ID"),
                "EMP_EMAIL_ID" => postData($empDatas, "emp_mail"),

                "EMP_BIRTH_DATE" => postData($empDatas, "EMP_BIRTH_DATE"),
                "EMP_JOINING_DATE" => postData($empDatas, "EMP_JOINING_DATE"),
                "PAST_EXPERIENCE" => postData($empDatas, "PAST_EXPERIENCE"),
                "PHONE_NUMBER" => postData($empDatas, "PHONE_NUMBER"),
                "EMP_TYPE" => postData($empDatas, "EMP_TYPE"),
            ];

            $emp_no = postData($empDatas, "emp_no");
            $opt['where'] = [
                'EMP_ID' => $emp_no
            ];
            $opt["return_type"] = "row";
            $checkEmp = $this->common_model->getAllData('employee_management', ['*'], $opt);

            if ($checkEmp) {
                $updtEmpl = $this->common_model->updateData(
                    EMPL,
                    $inserEmpldatas,
                    ["EMP_ID" => $emp_no]
                );
                $did = $checkEmp->EMP_ID;
            } else {
                $updtEmpl = $this->common_model->updateData(
                    EMPL,
                    $inserEmpldatas
                );
            }


            if ($did != "") {
                $logDetoptn["where"] = [
                    "USER_REF_ID" => $did,
                    "USER_LOGIN_TYP" => 0,
                    //                'USER_DESINATION_ID' => postData($empDatas,'desig'),
                ];
                $logDetoptn["return_type"] = "row";
                $gteLogdet = $this->common_model->getAlldata(
                    LOGIN,
                    ["*"],
                    $logDetoptn
                );

                if (!empty($gteLogdet)) {
                    $gedbDesigid = postData($gteLogdet, "USER_DESINATION_ID");
                    $geformDesigid = postData($empDatas, "desig");

                    if ($geformDesigid != $gedbDesigid) {
                        $checkempDbwher = [
                            "EMP_AUTO_ID" => $did,
                        ];
                        $checkloginDbwher = [
                            "USER_REF_ID" => $did,
                            "USER_LOGIN_TYP" => 0,
                        ];
                        $this->common_model->updateData(
                            EMPL,
                            ["EMP_LOGIN_STATUS" => "P"],
                            $checkempDbwher
                        );
                        $this->common_model->updateData(
                            LOGIN,
                            ["USER_LOG_STATUS" => "N"],
                            $checkloginDbwher
                        );
                    }
                }
                //           echo "<pre>";
                //           echo  $this->db->last_query();
                //           print_R($gteLogdet);
                //           exit;
            }

            if ($updtEmpl) {
                if ($did != "") {
                    $this->common_model->updateData('api_employee_preview_data', ['api_status' => '1'], ['id' => $preview_id]);
                    $data["flasmsg"] = $this->session->set_flashdata(
                        "employee",
                        '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Employee Details has been Updated</div>'
                    );
                } else {
                    $this->common_model->updateData('api_employee_preview_data', ['api_status' => '2'], ['id' => $preview_id]);
                    $data["flasmsg"] = $this->session->set_flashdata(
                        "employee",
                        '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Employee Details has been Created</div>'
                    );
                }
                redirect("master/employee");
            } else {
                if ($did != "") {

                    $data["flasmsg"] = $this->session->set_flashdata(
                        "employee",
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Sorry!</span> Employee Details cannot be Updated</div>'
                    );
                } else {
                    $data["flasmsg"] = $this->session->set_flashdata(
                        "employee",
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Sorry!</span> Employee Details cannot be Created</div>'
                    );
                }
                redirect("master/employee");
            }
        } else {
            $this->addEmployee($id);
        }
    }

    public function deleteEmployee($id = "")
    {
        $deleteId = $this->input->post("delid");
        $updtDelete = $this->common_model->updateData(
            EMPL,
            ["EMP_STATUS" => "N"],
            ["EMP_AUTO_ID" => $deleteId]
        );
        // $updtCertDelete = $this->common_model->updateData(
        //     COMP_CERTI,
        //     ["CERT_COMMON_STATUS" => "N"],
        //     ["CERT_USER_ID" => $deleteId, "CERT_USER_TYPE" => "EMP"]
        // );
        $updtloginDelete = $this->common_model->updateData(
            LOGIN,
            ["USER_LOG_STATUS" => "N"],
            ["USER_REF_ID" => $deleteId, "USER_LOGIN_TYP" => 0]
        );

        if ($updtDelete) {
            $retData = [
                "status" => true,
                "msgs" => "Employee deleted successfully",
            ];
        } else {
            $retData = [
                "status" => false,
                "msgs" => "Error in deleting Employee",
            ];
        }
        echo json_encode($retData);
    }

    public function viewEmployee($id = "")
    {
        $did = decryptval($id);
        $emplOptns["where"] = [
            "EMP_AUTO_ID" => $did,
        ];
        $emplOptns["return_type"] = "row";
        // $emplOptns['group_by'] = 'emp.EMP_ZONE_IDS,emp.EMP_SUBZONE_IDS';
        $getEmploydetails = $this->common_model->getAlldata(
            EMPL . " as emp",
            [
                "emp.*,
                FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name,     
                FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name,     
                FN_PLANT_NAME(EMP_VERT_ID) as vert_name,
                FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID) as unit_name,
               FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as dept_name,
                FN_EMP_TYPE(EMP_TYPE) as emp_type_name"

            ],
            $emplOptns
        );

        // echo $this->db->last_query();exit;


        $data = [
            "view_file" => "employee/view_employee",
            "current_menu" => "View Employee",
            "getEmploydetails" => $getEmploydetails,
        ];
        $this->template->load_common_template($data);
    }



    public function pdfEmployee($id = "")
    {
        $did = decryptval($id);

        $emplOptns["where"] = [
            "EMP_AUTO_ID" => $did,
        ];
        $emplOptns["return_type"] = "row";
        // $emplOptns['join'][MAS_ZNE.' zone'] = ['find_in_set(zone.zone_auto_id,emp.EMP_ZONE_IDS) > 0','left'];
        // $emplOptns['join'][MAS_SZE.' sub'] = ['find_in_set(sub.sub_auto_id,emp.EMP_SUBZONE_IDS) > 0','left'];
        // $emplOptns['group_by'] = 'emp.EMP_ZONE_IDS,emp.EMP_SUBZONE_IDS';
        $getEmploydetails = $this->common_model->getAlldata(
            EMPL . " as emp",
            [
                "emp.*,
                FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name, 
              
                FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name,     
                FN_PLANT_NAME(EMP_VERT_ID) as vert_name,
                FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID) as unit_name,
                  FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as dept_name,
                FN_EMP_TYPE(EMP_TYPE) as emp_type_name"

            ],
            $emplOptns
        );
        $data = [
            "getEmploydetails" => $getEmploydetails,

        ];
        $html2 = $this->load->view("master/employee/pdf_employee", $data, true);
        $mpdf2 = $this->pdf->ptwload();
        $mpdf2->setAutoTopMargin = 'stretch';
        $das = $mpdf2->WriteHTML($html2);
        $currenttime = date("d-m-Y");
        $folder_name = "employee";
        $file_path = "assets/uploads/" . $folder_name . "/";
        if (!file_exists($file_path)) {
            if (!mkdir($file_path, 0777, true)) {
                chmod($file_path, 0777);
            }
        }
        $path2 =
            $file_path . "Employee-" . $PdfId . "-" . $currenttime . ".pdf";
        $name = "Employee-" . $currenttime . ".pdf";
        $mpdf2->Output($path2, "F");
        $data = file_get_contents($path2);
        force_download($name, $data);
    }

    public function statusEmployee($id = "")
    {
        $did = decryptval($id);

        $emplOptn["where"] = [
            "emp.EMP_AUTO_ID" => $did,
            //            'log.USER_TYPE_ID !=' => '',
            //            'log.USER_LOGIN_TYP ' => 0,
        ];
        $emplOptn["join"][DESIG . " as des"] = [
            "des.DESIGNATION_ID = emp.EMP_DESIGNATION_ID",
            "left",
        ];
        //        $emplOptn['join'][UTYPE_DESIG.' as utdes'] = ['utdes.USER_DESIGN_ID = emp.EMP_DESIGNATION_ID','left'];
        $emplOptn["join"][UTYPE . " as utyp"] = [
            "utyp.USER_TYPE_ID = emp.EMP_USERTYPE_ID",
            "left",
        ];
        //        $emplOptn['join'][LOGIN.' as log'] = ['log.USER_REF_ID = emp.EMP_AUTO_ID','left'];

        $emplOptn["return_type"] = "row";

        $getEmploydetails = $this->common_model->getAlldata(
            EMPL . " as emp",
            ["emp.*,des.DESIGNATION_NAME,utyp.USER_TYPE_NAME"],
            $emplOptn
        );

        $emplLogoptns["where"] = [
            "USER_REF_ID" => $did,
            "USER_LOGIN_TYP" => 0,
        ];
        $emplLogoptns["return_type"] = "row";
        $emplLogindet = $this->common_model->getAlldata(
            LOGIN,
            ["*"],
            $emplLogoptns
        );

        $utypoptns["where"] = [
            "utyp.USER_TYPE_STATUS" => "Y",
            "utdes.USER_DESIGN_ID" => postData(
                $getEmploydetails,
                "EMP_DESIGNATION_ID"
            ),
        ];
        $utypoptns["join"][UTYPE_DESIG . " as utdes"] = [
            "utdes.USER_TYPE_ID = utyp.USER_TYPE_ID",
            "left",
        ];
        $getUsertyp = $this->common_model->getAlldata(
            UTYPE . " as utyp",
            ["*"],
            $utypoptns
        );
        $dropUsertyp = customFormDropDown(
            $getUsertyp,
            "USER_TYPE_ID",
            "USER_TYPE_NAME",
            ""
        );
        //       echo count($dropUsertyp);
        //       exit;

        $data = [
            "view_file" => "employee/status_employee",
            "current_menu" => "Status Employee",
            "getEmploydetails" => $getEmploydetails,
            "dropUsertyp" => $dropUsertyp,
            "emplLogindet" => $emplLogindet,

            //            'getOthercertdet' => $getOthercertdet,
        ];
        $this->template->load_popup_template($data);
    }

    public function updateEmplstatus($id = "")
    {

        $did = decryptval($id);
        $statDet = $this->input->post("stat");

        $empStatus = postData($statDet, "empStatus");
        $empuserTyp = postData($statDet, "empUsertyp");
        $hidLogid = postData($statDet, "hidLogid");
        $updateEmpdet = [
            "EMP_LOGIN_STATUS" => $empStatus,
        ];
        if ($empuserTyp != "") {
            $updateEmpdet["EMP_USERTYPE_ID"] = $empuserTyp;
        }
        $updateEmp = $this->common_model->updateData(EMPL, $updateEmpdet, [
            "EMP_AUTO_ID" => $did,
        ]);

        if ($hidLogid == "") {
            // $password_random =  sprintf("%06d", mt_rand(1, 999999));
            $logDet = [
                "USER_REF_ID" => postData($statDet, "empId"),
                "USER_LOGIN_TYP" => 0,
                "USER_TYPE_ID" => postData($statDet, "empUsertyp"),
                "USER_DESINATION_ID" => postData($statDet, "empDesig"),
                "USERNAME" => postData($statDet, "empUsername"),
                "ENCRYPT_PASSWORD" => md5(postData($statDet, "password")),
                "ORG_PWD" => postData($statDet, "password"),
                "USER_LOGIN_TYP" => 0,
                'USER_LOG_STATUS' => 'Y',
            ];
            $updateLogin = $this->common_model->updateData(LOGIN, $logDet);
        } else {
            if ($empStatus == "E" || $empStatus == "N") {
                $userDesig = postData($statDet, "empUsertyp");
                if ($userDesig != "") {
                    $logDet["USER_TYPE_ID"] = $userDesig;
                }
                $logDet["USER_LOG_STATUS"] = "Y";
                $logDet["USER_DESINATION_ID"] = postData($statDet, "empDesig");
                // $logDet['USER_TYPE_ID'] = postData($statDet,'empUsertyp');
                $logDet["ORG_PWD"] = postData($statDet, "password");
                $logDet["ENCRYPT_PASSWORD"] = md5(
                    postData($statDet, "password")
                );
            } else {
                $logDet["USER_LOG_STATUS"] = "N";
            }

            $updateLogin = $this->common_model->updateData(LOGIN, $logDet, [
                "LOGIN_ID" => $hidLogid,
            ]);
        }

        if ($updateLogin && $empStatus == "E") {
            /////////// new notification start
            $idata["messageText"] =
                'Login credential has been created for you to access Sinohydro EHS System, by ADMIN team. Find your below credentials :<br><br>' .
                "<b>URL</b> : " .
                BASE_URL .
                "<br>" .
                "<b>User Name</b> : " .
                postData($statDet, "empUsername") .
                //.'<br><b>Password</b> : '.postData($statDet,'password')
                "<br><br>This is system generated E-mail, do not reply to this email.";
            //                   $idata['footerText'] = ' <br><br/> SYSTEM GENERATED EMAIL. <br>';
            $idata["emplmailID"] = postData($statDet, "empMail");
            $idata["emplName"] = postData($statDet, "empName");
            //                $idata['messageTextnotify'] = '<br>Safety Officer( '.$safOffNameval.' ) has been successfully executed the "<b>INSPECTION</b>" with the ID # <b>"'.$inspUniqid.'" </b> and assigned <b> '.$idata['emplName'].'</b> for the fixation. <br><br>For more details about the inspection, kindly review the attached report in this mail.<br><br>For any further information or clarification, please do not hesitate to contact us. <br>Have a nice day.<br><br>Thanks & Regards,<br>KCG Support Team<br>Email: support@kcg.com<br>';
            $idata["subject"] = "Sinohydro - EMPLOYEE REGISTRATION";
            $idata["mainModule"] = "masterMod";

            internalNotificationOverall($idata);
            //////////// mail notification start

            ///// notification end

            if ($empStatus == "E") {
                $data["flasmsg"] = $this->session->set_flashdata(
                    "employee",
                    '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Employee Activated Successfully </div>'
                );
            } else {
                $data["flasmsg"] = $this->session->set_flashdata(
                    "employee",
                    '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><span>Success!</span> Employee Deactivated Successfully</div>'
                );
            }
        }
        redirect("master/employee");
    }

    public function getEmploid()
    {
        $empId = $this->input->post("empId");
        $emplOptn["where"] = [
            "EMP_ID" => $empId,
            "EMP_STATUS" => "Y",
        ];
        $emplOptn["return_type"] = "row";
        $getEmpldet = $this->common_model->getAlldata(EMPL, ["*"], $emplOptn);
        $data = [];
        if (!empty($getEmpldet)) {
            $data = [
                "status" => true,
                "msg" => "Employee No should be Unique",
            ];
        } else {
            $data = [
                "status" => false,
                //              'msg' => 'Employee No should be Unique',
            ];
        }
        echo json_encode($data);
    }

    public function exportexcel()
    {

        global $atarPermission_emp;

        $table = EMPL . " as emp";
        $column_order = [
            null,
            "EMP_ID",
            "EMP_NAME",
            "FN_PLANT_NAME(EMP_VERT_ID)",
            "FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID)",
            "FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID)",
            "FN_ROLE_NAME(EMP_USERTYPE_ID)",
            "FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID)",
            "FN_DATE_FORMAT(CREATED_ON)",
            null
        ];
        $column_search = [
            "EMP_ID",
            "EMP_NAME",
            "FN_PLANT_NAME(EMP_VERT_ID)",
            "FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID)",
            "FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID)",
            "FN_ROLE_NAME(EMP_USERTYPE_ID)",
            "FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID)",
            "FN_DATE_FORMAT(CREATED_ON)",

        ];
        $order = ["emp.CREATED_ON" => "desc"];
        // $where = ["EMP_STATUS" => "Y"];

        $optns["select"] = [
            'emp.*,
        FN_PLANT_NAME(EMP_VERT_ID) as vert_name,
        FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID) as unit_name,
        FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as dept_name,
        FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name,
        FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name,
        FN_DATE_FORMAT(CREATED_ON) as created_on'
        ];

        $request = $this->input->get();

        // $emp_id = postData($request, 'emp_id');
        // $emp_name = postData($request, 'emp_name');
        $plant_name = postData($request, 'plant_name');
        $loc_name = postData($request, 'loc_name');
        $dept = postData($request, 'dept');
        $role = postData($request, 'role');
        $desig = postData($request, 'desig');
        $startdate = postData($request, 'start_date');
        $enddate = postData($request, 'end_date');
        $searchStatus = postData($request, 'notifystatus');

        // if ($emp_id > 0) {
        //     $where['EMP_ID'] = $emp_id;
        // }
        // if ($emp_name > 0) {
        //     $where['EMP_NAME'] = $emp_name;
        // }

        $options['where_new'] = [];
        $where = [];

        if ($plant_name > 0) {

            $optns['where_new']["EMP_VERT_ID"] = $plant_name;
        }
        if ($loc_name > 0) {

            $optns['where_new']["EMP_UNIT_ID"] = $loc_name;
        }
        if ($dept > 0) {
            $optns['where_new']["EMP_DEPT_ID"] = $dept;
        }
        if ($role > 0) {
            $optns['where_new']["EMP_USERTYPE_ID"] = $role;
        }
        if ($desig > 0) {
            $optns['where_new']["EMP_DESIGNATION_ID"] = $desig;
        }
        if ($searchStatus > 0) {
            $optns['where_new']["EMP_LOGIN_STATUS"] = $searchStatus;
        }

        if ($startdate != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

            $optns['where_new']['CREATED_ON >='] = $thstartdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $optns['where_new']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }


        if (in_array($user_type, $atarPermission_emp['view_supadmin'])) {
            $optns['where_new']['EMP_STATUS'] =  'Y';
            // $optns['where_new']['EMP_LOGIN_STATUS'] =  'E' ;

        } elseif (in_array($user_type, $atarPermission_emp['view_ad'])) {
            // $optns['where_new']['EMP_LOGIN_STATUS'] =  'E' ;
            $optns['where_new']['EMP_STATUS'] =  'Y';
            $optns['where_new']['EMP_VERT_ID'] =  $user_plid;
        } else {
            $optns['where_new']['EMP_STATUS'] =  'Y';
        }



        $result = $this->common_model->get_exportdata(
            $table,
            $column_order,
            $column_search,
            $order,
            $where,
            $optns
        );

        $header = [
            'SL.No.',
            'Employee ID',
            'Employee Name',
            'Plant',
            'Specific Location',
            'Department',
            'Role',
            'Designation',
            'Created On',
            'Status',
        ];

        $i = 1;

        foreach ($result as $data) {

            $id = $data->EMP_AUTO_ID;
            $empLogstat = $data->EMP_LOGIN_STATUS;
            if ($empLogstat == "P") {
                $titileStat = "Activate/Deactivate";
            } elseif ($empLogstat == "E") {
                $titileStat = "Deactivate";
            } else {
                $titileStat = "Activate";
            }

            if ($data->EMP_LOGIN_STATUS == 'P') {
                $status = 'Pending';
            } else if ($data->EMP_LOGIN_STATUS == 'E') {
                $status = 'Activated';
            } else if ($data->EMP_LOGIN_STATUS == 'D') {
                $status = 'De-Activated';
            }

            $row = [];

            $row[] = $i;
            $row[] = $data->EMP_ID;
            $row[] = $data->EMP_NAME;
            $row[] = $data->vert_name;
            $row[] = $data->unit_name;
            $row[] = $data->dept_name;
            $row[] = $data->role_name;
            $row[] = $data->design_name;
            $row[] = $data->created_on;
            $row[] = $status;

            $exportData[] = $row;
            $i++;
        }

        $writer = SimpleExcelWriter::streamDownload('Employee Details.xlsx')
            ->addHeader($header)
            ->addRows(
                $exportData
            );
    }

    public function exportpdf()
    {
        global $atarPermission_emp;


        $table = EMPL . " as emp";
        $column_order = [
            null,
            "EMP_ID",
            "EMP_NAME",
            "FN_PLANT_NAME(EMP_VERT_ID)",
            "FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID)",
            "FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID)",
            "FN_ROLE_NAME(EMP_USERTYPE_ID)",
            "FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID)",
            "FN_DATE_FORMAT(CREATED_ON)",
            null
        ];
        $column_search = [
            "EMP_ID",
            "EMP_NAME",
            "FN_PLANT_NAME(EMP_VERT_ID)",
            "FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID)",
            "FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID)",
            "FN_ROLE_NAME(EMP_USERTYPE_ID)",
            "FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID)",
            "FN_DATE_FORMAT(CREATED_ON)",

        ];
        $order = ["emp.CREATED_ON" => "desc"];
        $where = ["EMP_STATUS" => "Y"];

        $optns["select"] = [
            'emp.*,
        FN_PLANT_NAME(EMP_VERT_ID) as vert_name,
        FN_SPECIFIC_LOCATION_NAME(EMP_UNIT_ID) as unit_name,
        FN_GET_DEPARTMENT_NAME(EMP_DEPT_ID) as dept_name,
        FN_ROLE_NAME(EMP_USERTYPE_ID) as role_name,
        FN_GET_DESIGNATION_NAME(EMP_DESIGNATION_ID) as design_name,
        FN_DATE_FORMAT(CREATED_ON) as created_on'
        ];

        $request = $this->input->get();



        $plant_name = postData($request, 'plant_name');
        $loc_name = postData($request, 'loc_name');
        $dept = postData($request, 'dept');
        $role = postData($request, 'role');
        $desig = postData($request, 'desig');
        $startdate = postData($request, 'start_date');
        $enddate = postData($request, 'end_date');
        $searchStatus = postData($request, 'notifystatus');



        $options['where_new'] = [];
        $where = [];

        if ($plant_name > 0) {

            $optns['where_new']["EMP_VERT_ID"] = $plant_name;
        }
        if ($loc_name > 0) {

            $optns['where_new']["EMP_UNIT_ID"] = $loc_name;
        }
        if ($dept > 0) {
            $optns['where_new']["EMP_DEPT_ID"] = $dept;
        }
        if ($role > 0) {
            $optns['where_new']["EMP_USERTYPE_ID"] = $role;
        }
        if ($desig > 0) {
            $optns['where_new']["EMP_DESIGNATION_ID"] = $desig;
        }
        if ($searchStatus > 0) {
            $optns['where_new']["EMP_LOGIN_STATUS"] = $searchStatus;
        }

        if ($startdate != '') {
            $thstartdate = date('Y-m-d H:i:s', strtotime($startdate));

            $optns['where_new']['CREATED_ON >='] = $thstartdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d', strtotime($enddate));
            $optns['where_new']['CREATED_ON <='] = $enddate . ' 23:59:59';
        }


        if (in_array($user_type, $atarPermission_emp['view_supadmin'])) {
            $optns['where_new']['EMP_STATUS'] =  'Y';
            // $optns['where_new']['EMP_LOGIN_STATUS'] =  'E' ;

        } elseif (in_array($user_type, $atarPermission_emp['view_ad'])) {
            // $optns['where_new']['EMP_LOGIN_STATUS'] =  'E' ;
            $optns['where_new']['EMP_STATUS'] =  'Y';
            $optns['where_new']['EMP_VERT_ID'] =  $user_plid;
        } else {
            $optns['where_new']['EMP_STATUS'] =  'Y';
        }

        $result = $this->common_model->get_exportdata(
            $table,
            $column_order,
            $column_search,
            $order,
            $where,
            $optns
        );

        // echo $this->db->last_query();exit;

        $header = [
            'SL.No.',
            'Employee ID',
            'Employee Name',
            'Plant',
            'Specific Location',
            'Department',
            'Role',
            'Designation',
            'Created On',
            'Status',
        ];

        $data = array(
            'header' => $header,
            'content' => $result,
            'pagetitle' => "Employee Details",
        );

        $property = [
            'tempDir' => 'public/pdf/temp/',
            'mode' => 'c',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,

        ];

        $mpdf = new \Mpdf\Mpdf($property);
        $mpdf->setAutoTopMargin = 'stretch';
        $html = $this->load->view('master/employee/export_pdf', $data, true);
        $mpdf->WriteHTML($html);

        $filename = "Employee Details.pdf";
        $mpdf->Output($filename, 'D');
    }
}
