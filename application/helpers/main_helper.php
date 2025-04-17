<?php

if (!function_exists('get_global_variables')) {
    function get_global_variables()
    {
        return [
            'cronAdminMain' => ['7'],
            'superadminMain' => ['2'],
            'approver' => ['6'],
            'verifier' => ['8'],
        ];
    }
}


function getAjaxCompanyMain($userRole = '')
{
    $ci = &get_instance();
    $globals = get_global_variables();
    $superadminMain = $globals['superadminMain'];
    $approver = $globals['approver'];
    $verifier = $globals['verifier'];
    if (in_array($userRole, $superadminMain) || in_array($userRole, $approver) || in_array($userRole, $verifier)) {
        $options['where'] = ['company_status' => 'Y'];
    } else {
        $Userver = $_SESSION['emp_details']->EMP_COMP_ID;

        $options['where'] = [
            'company_status' => 'Y',
            'company_id' => $Userver,
        ];
    }
    $getInsptypedata = $ci->common_model->getAlldata(MAS_COMP, ['*'], $options);
    $dropInsptypedata = customFormDropDown($getInsptypedata, 'company_id', 'company_full_name', 'Select Company');
    $chStatus = (!empty($dropInsptypedata)) ? true : false;
    $chdatas = (!empty($dropInsptypedata)) ? $dropInsptypedata : [];
    return $chdatas;
}

function getAjaxProjectMain()
{
    $ci = &get_instance();
    $options['where'] = ['project_status' => 'Y'];
    $getInsptypedata = $ci->common_model->getAlldata(MAS_PROJ, ['*'], $options);
    $dropInsptypedata = customFormDropDown($getInsptypedata, 'project_id', 'project_name', 'Select Project');
    $chStatus = (!empty($dropInsptypedata)) ? true : false;
    $chdatas = (!empty($dropInsptypedata)) ? $dropInsptypedata : [];
    return $chdatas;
}


function getGroupInvEmailDetails($ids, $type = "", $opt = '')
{
    $options = $opt;
    $ci = &get_instance();

    $options["join"] = [
        LOGIN . " AS LD" => ["LD.USER_REF_ID = EMP.EMP_AUTO_ID", "LEFT"],
    ];
    $options["return_type"] = "row";


    if ($type == "USERTYPE") {
        $options["where_in"] = ["USER_TYPE_ID" => $ids];
        $options["group_by"] = [
            "DESC" => "LD.USER_TYPE_ID",
        ];
    } else {
        $options["where_in"] = ["LOGIN_ID" => $ids];
    }

    //  echo "SDg";exit;
    $details = $ci->common_model->getAlldata(
        EMPL . " as EMP",
        [
            "GROUP_CONCAT( DISTINCT EMP_EMAIL_ID) as emailList",
            "GROUP_CONCAT( DISTINCT LOGIN_ID) as notifyidList",
        ],
        $options,
        $limit = "",
        $offset = "",
        $orderby = "",
        $disporder = ""
    );

    // echo $ci->db->last_query();exit;
    return $details;
}
