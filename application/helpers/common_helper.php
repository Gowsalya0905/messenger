<?php



function getRequiredsessiondata($data = '')
{

    $ci = &get_instance();

    $userdata = $ci->session->userinfo;



    $reqSessiondata = postData($userdata, $data, FALSE);

    return $reqSessiondata;
}

function getParticsessdata($requDatas = '')
{

    $ci = &get_instance();

    $sessionDatas =  $ci->session->userinfo;

    if ($requDatas != '') {

        return $sessionDatas->$requDatas;
    } else {

        return $sessionDatas;
    }
}

function getCompanylogo()
{

    $ci = &get_instance();

    $options['return_type'] = 'row';

    $compLogo = $ci->common_model->getAlldata(COMP_PROF, ['*'], $options);

    $logoPath = $compLogo->COMPANY_PROF_LOGO_PATH;

    return $logoPath;
}

function getmenufunction($defaultUserGroup) {}



function buildMenu($currentmenu = '')
{



    $ci = &get_instance();

    $defaultUserGroup = getCurrentUserGroupId();

    $desigId = getRequiredsessiondata('USER_LOG_DESIGNATION_ID');


    $moduleWhere['where'] = ['user_grop' => $defaultUserGroup];

    $moduleWhere['return'] = 'result';


    // if($defaultUserGroup !=1 && $defaultUserGroup !=2){

    //     $query = $ci->db->query("SELECT
    //     T2.`MENU_ID` AS `id`,
    //     T2.MENU_NAME AS `name`,
    //     T2.MENU_LINK AS `link`,
    //     T2.MENU_ICON AS `icon`,
    //     T2.`MENU_PARENT_ID` AS `parent`,
    //     T2.`MENU_IS_PARENT` AS `is_parent`,
    //     T2.`MENU_SORT_ORDER` AS `order`,
    //     T2.`MENU_STATUS` AS `status`,
    //     T1.VIEW_PER, 
    //     (CASE WHEN T1.VIEW_PER = 'Y' OR T1.ADD_PER = 'Y' OR T1.EDIT_PER = 'Y' OR T1.DEL_PER = 'Y' OR T1.PRINT_PER = 'Y' THEN 1 ELSE 0 END) AS m_permission
    //     FROM `permission_master` AS T1
    //     INNER JOIN left_menu AS T2 ON T2.MENU_ID = T1.MENU_ID
    //     WHERE T1.DESIGNATION_ID = ".$defaultUserGroup." AND PERMISSION_STATUS = 'Y'
    //     GROUP BY T2.MENU_ID, T1.VIEW_PER 
    //     HAVING m_permission > 0
    //     ORDER BY `order` ASC;
    //     ");
    //     $menuResult = $query->result();
    // }else{
    //     $query = $ci->db->query("SELECT `MENU_ID` as `id`, `MENU_NAME` as `name`, `MENU_LINK` as `link`, `MENU_ICON` as `icon`,  `MENU_PARENT_ID` as `parent`, `MENU_IS_PARENT` as `is_parent`, `MENU_SORT_ORDER` as `order`, `MENU_STATUS` as status FROM `left_menu` WHERE `MENU_STATUS` = 'Y'  ORDER BY MENU_PARENT_ID ASC, `MENU_SORT_ORDER` ASC;");
    //      $menuResult = $query->result();
    // }

    // $getMenu = $menuResult;
    $getMenu = callProcedure(' PR_GET_MENU(?) ', $moduleWhere);



    $html_out = "";



    if ($getMenu != FALSE && count($getMenu) > 0)

        foreach ($getMenu as $menus) {

            $menu = (array) $menus;

            $menuName = $menu['name'];



            if ($menu['status'] && $menu['parent'] == '0' || is_admin() && $menu['status'] && $menu['parent'] == '0') {



                $menulink = BASE_URL . $menu['link'];

                if ($menu['is_parent'] == TRUE) {

                    $html_out .= '<li class="nav-item has-treeview">'

                        . '<a href="' . $menulink . '" class="nav-link menuid-' . $menu['id'] . '">'

                        . '<i class="dashboardicon ' . $menu['icon'] . ' mr-1"></i>'

                        . ' <p>' . $menu['name'] . ''

                        . '<i class="fas fa-angle-left right">'

                        . '</i>'

                        . '</p>'

                        . '</a>';
                } else {



                    $html_out .= '<li class="nav-item test">'

                        . '<a href="' . $menulink . '" class="nav-link ">'

                        . '<i class="dashboardicon ' . $menu['icon'] . ' mr-1"></i>'

                        . '<p>' . $menu['name'] . '</p>'

                        . '</a>'

                        . '</li>'

                        . '</li>';
                }



                $html_out .= getChilds($getMenu, $menu['id'], [], []);



                $html_out .= '</li>';
            }
        }

    //exit;

    return $html_out;
}



function getChilds($aMenu, $parent_id, $MenuIds = [], $userMenuIds = [])
{



    $ci = &get_instance();

    $has_subcats = FALSE;



    $html_out = '';

    $html_out .= '<ul class="nav nav-treeview" style="display: none;">';



    foreach ($aMenu as $amKey => $menu) {

        $menus = (array) $menu;

        $currentMenu = $menus['id'];

        $menuName = $menus['name'];



        if ($menus['status'] && $menus['parent'] == $parent_id) {



            $has_subcats = TRUE;

            $menuIcon = ($menus['icon'] != '') ? $menus['icon'] : ' fa fa-circle-o';



            if ($menus['is_parent'] == TRUE) {



                $displayStyl = 'display:block';



                $mlink = ($menus['link'] != '') ? 'href="' . BASE_URL . $menus['link'] . '"' : '';

                $html_out .= '<li class="nav-item" style=' . $displayStyl . '>'

                    . '<a ' . $mlink . ' class="nav-link submod_' . $amKey . '">'

                    . '<i class="' . $menuIcon . '"></i>'

                    . ' <p>' . $menuName . '<i class="fas fa-angle-left right"></i>'

                    . '</p>'

                    . '</a>';
            } else {

                $displayStyl = 'display:block';



                $mlink = ($menus['link'] != '') ? '"' . BASE_URL . $menus['link'] . '"' : '';

                $html_out .= '<li class="nav-item" style=' . $displayStyl . '>'

                    . '<a href =' . $mlink . ' class="nav-link ' . $currentMenu . '">'

                    . '<i class="' . $menus['icon'] . '"></i>'

                    . ' <p>' . $menuName . '</p>'

                    . '</a>';
            }



            // Recurse call to get more child submenus.

            $html_out .= getChilds($aMenu, $menus['id'], $MenuIds, $userMenuIds);



            $html_out .= '</li>';
        }
    }

    //exit;

    $html_out .= '</ul>';



    return ($has_subcats) ? $html_out : FALSE;
}



function getCurrentUserGroupId()
{

    $ci = &get_instance();
    // $_SESSION['role_id']

    $userdata = $ci->session;

    $groupId = postData($userdata, 'role_id', FALSE);



    return $groupId;
}



function isLogin()
{

    $ci = &get_instance();

    $userdata = $ci->session->userinfo;

    if (!$userdata) {

        redirect('login');
    }

    return true;
}



function encryptval($id)
{



    $ci = &get_instance();

    $ci->load->library('encrypt');



    //    return $ci->encryption->encrypt($id);

    return $ci->encrypt->encode($id);
}



function decryptval($val)
{

    $ci = &get_instance();

    $ci->load->library('encrypt');

    //    return $ci->encryption->decrypt($val);

    return $ci->encrypt->decode($val);
}



function postData($results = array(), $postval = '', $retunval = '')
{

    $results = (is_array($results) && $results != FALSE) ? (object) $results : $results;

    return ($results != FALSE && isset($results->$postval) && ($results->$postval != '') && ($results->$postval != 'NULL')) ? $results->$postval : $retunval;
}



function concatNamedatas($results = array(), $nameval = [])
{

    $results = (is_array($results) && $results != FALSE) ? (object) $results : $results;

    if ($results != FALSE && $nameval != false) {

        $name1 = (isset($nameval[0]) && !empty($nameval[0])) ? $nameval[0] : '';

        $name2 = (isset($nameval[1]) && !empty($nameval[1])) ? $nameval[1] : '';

        if (!empty($name2)) {

            return $results->$name1 . ' ' . $results->$name2;
        } else {

            return $results->$name1;
        }
    } else {

        return $retunval;
    }
}



function callProcedure($procedureName = '', $options = [])
{



    $ci = &get_instance();

    $res = FALSE;

    $data = postData($options, 'where');

    $returnName = postData($options, 'return');

    $returnType = ($returnName != FALSE) ? $returnName : 'result';

    $view_stored_proc = " CALL $procedureName";

    $result = $ci->db->query($view_stored_proc, $data);

    if ($result != false && $result->num_rows() > 0) {

        $res = $result->$returnType();
    }

    mysqli_next_result($ci->db->conn_id);

    return $res;
}



function getNotifications()
{

    $ci = &get_instance();

    $result = getNotificationList(1);



    $getNotificationCount = getUnreadNotification();


    //echo '<pre>';print_r($getNotificationCount);exit; 

    $NotifyIds = (isset($getNotificationCount->notify_ids) && !empty($getNotificationCount->notify_ids)) ? explode(',', $getNotificationCount->notify_ids) : array();

    $noCount = (isset($getNotificationCount->count) && $getNotificationCount->count > 0) ? $getNotificationCount->count : 0;

    $html = '';

    $notifyUrl = BASE_URL . 'dashboard/notify/';

    // New notification code vasanth

    if (!empty($getNotificationCount->notify_ids)) {

        $ci->db->select('*');
        $ci->db->from('notification');
        $ci->db->where('NOTIFICATION_ID IN(' . $getNotificationCount->notify_ids . ')');
        $ci->db->order_by('NOTIFICATION_ID', 'DESC');
        $ci->db->limit(10);
        $resultNoti = $ci->db->get()->result();
    }



    // echo $ci->db->last_query();exit;

    if (!empty($resultNoti)) {

        $html = '<a href="#" class="nav-link" data-toggle="dropdown">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge">' . $noCount . '</span>
            </a>';
        $html .= '<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notificationDropdown">';

        // $html .= '<a style="background: #949699;" href="#" class="dropdown-item dropdown-footer"><b>General Notification (' . $noCount . ') </b></a>';
        $html .= '<span style="background: #949699;" href="#" class="dropdown-item dropdown-footer">
    <b style="margin-right: 72px;">General Notification </b>
    <span>
    <a href="' . BASE_URL . 'dashboard/notify/?all=notify" style="color:#2e2c7f !important;">
    <b>Clear All</b>
    </a>
    </span>
    </span>';


        foreach ($resultNoti as $notify) {

            $displayTime = convertDate($notify->CREATED_ON, $format = 'Y-m-d H:i:s');

            $date = timeago($displayTime);

            $id = isset($notify->NOTIFICATION_ID) ? $notify->NOTIFICATION_ID : 0;

            $rclass = in_array($id, $NotifyIds) ? 'unread' : 'read';

            $aref = $notifyUrl . encryptval($id);

            $img = ($rclass == 'unread') ? '<img src="' . ICON_PATH . 'new-icon.gif"/>' : '';

            $urlnot = BASE_URL();



            $html .= '<a href="' . $aref . '" class="dropdown-item"> 

                <div class="media">

                    <img src="' . $urlnot . 'assets/images/modules/company_logo/Logo-Mini.png" class="mr-3 img-circle" alt="User Image" style="width:40px">

               <div class="media-body">

<h3 class="dropdown-item-title">

                </h3>
                        <p class="text-sm">' . trim($notify->NOTIFICATION_DESC) . '</p>

                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> ' . $date . '</p>

                   

                     </div>

            </div>

             </a><div class="dropdown-divider"></div>';
        }


        // $html .= '<a href="'.BASE_URL."dashboard/notify/?all=notify".'" class="dropdown-item dropdown-footer"> <b>See All Notifications </b></a>';
        $html .= '<a href="' . BASE_URL . "dashboard/NotificationList?ids=1" . '" class="dropdown-item dropdown-footer"> <b>See All Notifications </b></a>';

        $html .= '</div>';
    } else {





        $html = '<a href="#" class="nav-link" data-toggle="dropdown">

                                   <i class="far fa-bell"></i>

                                       <span class="badge badge-danger navbar-badge">' . $noCount . '</span>

                                </a>';

        $html .= '<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">';



        $html .= '<a href="#" style="background: #949699;" class="dropdown-item dropdown-footer">General Notification</a>';

        $html .= '<a href="' . BASE_URL . "dashboard/NotificationList?ids=1" . '" class="dropdown-item dropdown-footer"> <b>See All Notifications </b></a>';


        $html .= '</div>';
    }

    return $html;
}



function getTasks()
{

    $result = getNotificationList(2);

    $html = '';

    $notifyUrl = BASE_URL . 'dashboard/notify/';

    $getNotificationCount = getUnreadTask();

    //    echo "<pre>";

    //    print_R($getNotificationCount);

    //    exit;

    $curentUserid = getRequiredsessiondata('LOGIN_ID');

    $NotifyIds = (isset($getNotificationCount->notify_ids) && !empty($getNotificationCount->notify_ids)) ? explode(',', $getNotificationCount->notify_ids) : array();



    $noCount = (isset($getNotificationCount->count) && $getNotificationCount->count > 0) ? $getNotificationCount->count : 0;

    if ($result != false) {



        $html = '<a href="#" class="nav-link" data-toggle="dropdown">

                                    <i class="far fa-comments"></i>

                                    <span class="badge badge-danger navbar-badge">' . $noCount . '</span>

                                </a>';



        $html .= '<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notificationDropdown">';

        $html .= '<a href="#" style="background: #5ab1e0;" class="dropdown-item dropdown-footer"><b>Notification Tasks(' . $noCount . ') </b></a>';



        foreach ($result as $notify) {

            if ($curentUserid == $notify->NOTIFICATION_EMPLOYEE_ID) {

                $displayTime = convertDate($notify->CREATED_ON, $format = 'Y-m-d H:i:s');

                $date = timeago($displayTime);

                $id = isset($notify->NOTIFICATION_ID) ? $notify->NOTIFICATION_ID : 0;

                $rclass = in_array($id, $NotifyIds) ? 'unread' : 'read';

                $aref = $notifyUrl . encryptval($id);



                $urlnot = BASE_URL();

                //    <img src="' . $urlnot . 'assets/images/modules/login/logo.png" class="img-size-50 mr-3 img-circle" alt="User Image" >





                $img = ($rclass == 'unread') ? '<img src="' . ICON_PATH . 'new-icon.gif"/>' : '';

                $html .= '<a href="' . $aref . '" class="dropdown-item"> 

                <div class="media">

                <div class="media-body">

<h3 class="dropdown-item-title">

                  

                </h3>

                                            

                        <p class="text-sm">' . trim($notify->NOTIFICATION_DESC) . '</p>

                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> ' . $date . '</p>

                   

                     </div>

            </div>

             </a><div class="dropdown-divider"></div>';
            }
        }

        $html .= ' 

          <a href="#" class="dropdown-item dropdown-footer"><b>See All Messages</b></a>';

        $html .= '</div>';
    } else {

        $html = '<a href="#" class="nav-link" data-toggle="dropdown">

                                   <i class="far fa-comments"></i>

                                       <span class="badge badge-danger navbar-badge">' . $noCount . '</span>

                                </a>';

        $html .= '<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">';



        $html .= '<a href="#" style="background: #5ab1e0;" class="dropdown-item dropdown-footer">Notification Tasks(' . $noCount . ') </a>';



        $html .= '</div>';
    }

    return $html;
}



function getNotificationList($msg_type)
{

    $userid = getCurrentUserid();

    $userGroupId = getCurrentUserGroupId();

    $option['returntype'] = 'result';

    $noWhere = array('where' => array('NOTIFICATION_TYPE' => $msg_type, 'NOTIFICATION_STATUS' => 'Y'));

    //    $noWhere = array('where' => array('NOTIFICATION_TYPE' => $msg_type,'NOTIFICATION_STATUS' => 'Y'));

    if (!is_admin()) {

        $option['find_in_set'] = array('NOTIFICATION_EMPLOYEE_ID' => $userid);

        $option['orwherefind'] = array('NOTIFICATION_GROUP_CATID' => $userGroupId);
    }

    return getNotificationInfo('*', $noWhere, $option);
}



function getCurrentUserid()
{

    $ci = &get_instance();

    $userdata = $ci->session->userinfo;
    //    if(!$userdata ){

    //     $userid = 1;
    //    }else{
    //     $userid = 0;
    // }
    $userid = isset($userdata->LOGIN_ID) ? $userdata->LOGIN_ID : '';

    return $userid;
}



function getNotificationInfo($params = '', $where = array(), $opion = array())
{

    $table = NOTI;

    $returnType = isset($opion['returntype']) ? $opion['returntype'] : 'row';

    $findInset = isset($opion['find_in_set']) ? $opion['find_in_set'] : FALSE;

    $orwherefind = isset($opion['orwherefind']) ? $opion['orwherefind'] : FALSE;

    $orderby = isset($optional['orderby']) ? $optional['orderby'] : FALSE;

    $disporder = isset($optional['disporder']) ? $optional['disporder'] : FALSE;

    $limit = isset($optional['limit']) ? $optional['limit'] : FALSE;

    $offset = isset($optional['offset']) ? $optional['offset'] : FALSE;

    $groupby = isset($optional['groupby']) ? $optional['groupby'] : FALSE;

    $ci = &get_instance();

    if ($params != '') {

        $ci->db->select($params);
    } else {

        $ci->db->select('*');
    }

    if (count($where) > 0) {

        if (isset($where['notin']) && count($where['notin']) > 0) {

            foreach ($where['notin'] as $key => $val) {

                $ci->db->where_not_in($key, $val);
            }
        }



        if ($findInset != FALSE) {

            //            $ci->db->group_start();

            foreach ($findInset as $key => $val) {

                $ci->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }



            if (isset($where['where']) && count($where['where']) > 0) {

                $ci->db->where($where['where']);
            }

            if (isset($where['orwhere']) && count($where['orwhere']) > 0) {

                $ci->db->or_group_start();

                foreach ($where['orwhere'] as $key => $val) {

                    $ci->db->or_where($key, $val);
                }

                $ci->db->group_end();

                if (isset($where['where']) && count($where['where']) > 0) {

                    $ci->db->where($where['where']);
                }

                if (isset($where['notin']) && count($where['notin']) > 0) {

                    foreach ($where['notin'] as $key => $val) {

                        $ci->db->where_not_in($key, $val);
                    }
                }
            }

            if ($orwherefind != FALSE && count($orwherefind) > 0) {

                $ci->db->or_group_start();

                foreach ($orwherefind as $key => $val) {

                    $ci->db->or_where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
                }

                $ci->db->group_end();

                if (isset($where['where']) && count($where['where']) > 0) {

                    $ci->db->where($where['where']);
                }

                if (isset($where['notin']) && count($where['notin']) > 0) {

                    foreach ($where['notin'] as $key => $val) {

                        $ci->db->where_not_in($key, $val);
                    }
                }
            }

            //            $ci->db->group_end();

        } else {

            if (isset($where['where']) && count($where['where']) > 0) {

                $ci->db->where($where['where']);
            }

            if ($orwherefind != FALSE && count($orwherefind) > 0) {

                foreach ($orwherefind as $key => $val) {

                    $ci->db->or_group_start();

                    $ci->db->or_where($key, $val);

                    if (isset($where['where']) && count($where['where']) > 0) {

                        $ci->db->where($where['where']);
                    }

                    if (isset($where['notin']) && count($where['notin']) > 0) {

                        foreach ($where['notin'] as $key => $val) {

                            $ci->db->where_not_in($key, $val);
                        }
                    }

                    $ci->db->group_end();
                }

                if (isset($where['where']) && count($where['where']) > 0) {

                    $ci->db->where($where['where']);
                }
            }
        }
    }

    if ($groupby != FALSE) {

        $ci->db->group_by($groupby);
    }



    if ($orderby != "" && $disporder != "")

        $ci->db->order_by($orderby, $disporder);

    else

        $ci->db->order_by("NOTIFICATION_ID", "DESC");



    if ($limit != "" && $offset != "") {



        $ci->db->limit($limit, $offset);
    } else {

        $ci->db->limit(10);
    }

    $result = $ci->db->get($table);





    //    echo "<pre>";
    //    print_r($opion);exit;

    //    echo $ci->db->last_query();exit;

    // exit; echo '<br>';

    if ($result != false && $result->num_rows() > 0)

        return $result->$returnType();

    else

        return false;
}



function getUnreadNotification()
{

    return unreadMessage('1');
}



function timeago($datetime, $full = false)
{

    $now = new DateTime;

    $ago = new DateTime($datetime);

    $diff = $now->diff($ago);



    $diff->w = floor($diff->d / 7);

    //    $diff->d -= $diff->w * 7;



    $wstring = array(

        'y' => 'year',

        'm' => 'month',

        'w' => 'week',

        'd' => 'day',

        'h' => 'hour',

        'i' => 'minute',

        's' => 'second',

    );

    $string = array(

        'y' => 'year',

        'm' => 'month',

        'd' => 'day',

        'h' => 'hour',

        'i' => 'minute',

    );

    foreach ($string as $k => &$v) {

        if ($diff->$k) {

            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {

            unset($string[$k]);
        }
    }



    if (!$full)

        $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}



function getUnreadTask()
{

    return unreadMessage('2');
}



function unreadMessage($msg_type)
{

    $userid = getCurrentUserid();

    $userGroupId = getCurrentUserGroupId();



    $notlogOptn['group_by'] = 'NOTIFICATION_USER_MASTER_ID';

    $getnotificationIds = getNotificationLogInfo('GROUP_CONCAT(Distinct NOTIFICATION_LOG_NOTI_ID) as notify_ids', array('NOTIFICATION_USER_MASTER_ID' => $userid));

    // echo '<pre>';print_r($getnotificationIds);exit;





    // $getnotificationIds = getNotificationLogInfo('Distinct(NOTIFICATION_LOG_NOTI_ID) as notify_ids', array('NOTIFICATION_USER_MASTER_ID' => $userid));



    // echo "<pre>";

    // // print_R(explode(",",postData($getnotificationIds,'notify_ids')));

    // print_R($getnotificationIds);

    // exit;

    $option = array();

    //    if (!is_admin()) {

    //	$option['find_in_set'] = array('NOTIFICATION_EMPLOYEE_ID' => $userid);

    //	$option['orwherefind'] = array('NOTIFICATION_GROUP_CATID' => $userGroupId);

    //    }

    //    if (!is_admin()) {

    $option['find_in_set'] = array('NOTIFICATION_EMPLOYEE_ID' => $userid);

    $option['orwherefind'] = array('NOTIFICATION_GROUP_CATID' => $userGroupId);

    //    }

    $notifyIds = (isset($getnotificationIds->notify_ids) && !empty($getnotificationIds->notify_ids)) ? explode(',', $getnotificationIds->notify_ids) : array();



    if (count($notifyIds) > 0) {

        if ($msg_type == 2) {

            $noWhere = array('notin' => array('NOTIFICATION_ID' => $notifyIds), 'where' => array('NOTIFICATION_TYPE' => $msg_type, 'NOTIFICATION_STATUS' => 'Y', 'NOTIFICATION_EMPLOYEE_ID' => $userid));
        } else {

            $noWhere = array('notin' => array('NOTIFICATION_ID' => $notifyIds), 'where' => array('NOTIFICATION_TYPE' => $msg_type, 'NOTIFICATION_STATUS' => 'Y'));
        }
    } else {

        $noWhere = array('where' => array('NOTIFICATION_TYPE' => $msg_type, 'NOTIFICATION_STATUS' => 'Y'));
    }



    $getNotificationCount = getNotificationInfo('count(NOTIFICATION_ID) as count,GROUP_CONCAT(NOTIFICATION_ID) as notify_ids', $noWhere, $option);

    // echo '<pre>';
    // print_r($getNotificationCount);exit;



    return $getNotificationCount;
}





function is_admin()
{

    $ci = &get_instance();

    $currentRole = $ci->session->userinfo->USER_TYPE_ID;

    $adminRole = array(1, 2);

    if (in_array($currentRole, $adminRole)) {

        return TRUE;
    }

    return FALSE;
}






function getNotificationLogInfo($params = '', $where = array(), $opion = array())
{

    $table = NOTI_LOG;

    $returnType = isset($opion['returntype']) ? $opion['returntype'] : 'row';

    $ci = &get_instance();

    $ci->db->simple_query('SET SESSION group_concat_max_len=99999');

    if ($params != '') {

        $ci->db->select($params);
    } else {

        $ci->db->select('*');
    }

    if (count($where) > 0) {

        if (isset($where['in']) && count($where['in']) > 0) {

            foreach ($where['in'] as $key => $val) {

                $ci->db->where_in($key, $val);
            }
        } else if (isset($where['notin']) && count($where['notin']) > 0) {

            foreach ($where['notin'] as $key => $val) {

                $ci->db->where_not_in($key, $val);
            }
        } else {

            $ci->db->where($where);
        }

        if (isset($where['where']) && count($where['where']) > 0) {

            $ci->db->where($where['where']);
        }
    }

    $result = $ci->db->get($table);

    //   echo "<pre>";

    //echo $ci->db->last_query();exit;

    if ($result != false && $result->num_rows() > 0)

        return $result->$returnType();

    else

        return false;
}



function customFormDropDown($projectDetails, $value, $display, $empty = '', $separator = " ", $options = FALSE)
{

    $aProject = $aJoin = array();

    $join = FALSE;

    if ($empty != FALSE) {

        $aProject[''] = $empty;
    }

    if ($empty != FALSE && is_array($empty)) {

        $aProject = $empty;
    }



    if ($display != '' && count(explode(',', $display)) >= 2) {

        $join = TRUE;

        $aJoin = explode(',', $display);
    }



    if (!empty($projectDetails) && $projectDetails != FALSE) {

        foreach ($projectDetails as $project) {



            $key = ($options == TRUE) ? encryptval($project->$value) : $project->$value;



            if ($join == TRUE) {



                $firstData = $aJoin[0];

                $secondData = $aJoin[1];

                $aProject[$key] = $project->$firstData . $separator . $project->$secondData;
            } else {



                $aProject[$key] = $project->$display;
            }
        }
    }

    return $aProject;
}


function uploadImage($filename = '', $imgUploadpath = '', $allowedTyp = "*")
{

    $ci = &get_instance();

    if (isset($_FILES[$filename]['name']) && $_FILES[$filename]['name'] == '') {

        return $ci->input->post($filename . '_photo');
    }



    $mpath = $imgUploadpath;

    if (!file_exists(FCPATH . $mpath)) {

        mkdir(FCPATH . $mpath, 0777, true);
    }

    //$fpath = $mpath;



    $uploadPath = $mpath;



    $config['upload_path'] = FCPATH . $uploadPath;

    if (!file_exists($config['upload_path'])) {

        mkdir($config['upload_path'], 0777);
    }



    $config['allowed_types'] = $allowedTyp;

    $config['encrypt_name'] = TRUE;

    $ci->upload->initialize($config);

    $ci->load->library('upload', $config);

    if (!$ci->upload->do_upload($filename)) {



        $error = $ci->upload->display_errors();
    }



    $upload_data = $ci->upload->data();



    $imgName = (isset($upload_data['file_name'])) ? $upload_data['file_name'] : FALSE;

    return ($imgName != FALSE) ? $uploadPath . '/' . $imgName : '';
}



function uploadMultipleimage($files, $path, $type = '', $allowedType = '*', $returnType = FALSE)
{



    $ci = &get_instance();

    $config['allowed_types'] = $allowedType;

    $config['encrypt_name'] = TRUE;

    $ci->load->library('upload', $config);

    $returndata = array();

    $filesCount = count($files[$type]['name']);



    //        foreach($files)

    $loopOver = $files[$type];



    for ($i = 0; $i < $filesCount; $i++) {



        if ($loopOver['name'][$i] != '') {

            $_FILES[$type]['name'] = $loopOver['name'][$i];

            $_FILES[$type]['type'] = $loopOver['type'][$i];

            $_FILES[$type]['tmp_name'] = $loopOver['tmp_name'][$i];

            $_FILES[$type]['error'] = $loopOver['error'][$i];

            $_FILES[$type]['size'] = $loopOver['size'][$i];







            $config['upload_path'] = FCPATH . $path;

            if (!file_exists($config['upload_path'])) {

                mkdir($config['upload_path'], 0777, true);
            }



            $ci->load->library('upload', $config);

            $ci->upload->initialize($config);

            $finalImgdata = [];

            if ($ci->upload->do_upload($type)) {

                $fileData = $ci->upload->data();



                $uploadpath = $path;

                $returndata[$i]['uploadname'] = $fileData['file_name'];

                $returndata[$i]['uploadorigname'] = $fileData['orig_name'];

                $returndata[$i]['uploadpath'] = $uploadpath;

                $returndata[$i]['uploadtype'] = $fileData['file_type'];

                $returndata[$i]['uploadextension'] = $fileData['file_ext'];

                $returndata[$i]['filesize'] = $fileData['file_size'];

                $returndata[$i]['client_name'] = $fileData['client_name'];

                $returndata[$i]['orig_name'] = $fileData['orig_name'];

                $returndata[$i]['is_image'] = $fileData['is_image'];

                $returndata[$i]['image_type'] = $fileData['image_type'];

                $returndata[$i]['image_width'] = $fileData['image_width'];

                $returndata[$i]['image_height'] = $fileData['image_height'];

                $returndata[$i]['image_size_str'] = $fileData['image_size_str'];
            } else {

                //              print_r($_FILES);

                if ($returnType) {

                    return $ci->upload->display_errors();
                } else {

                    echo $ci->upload->display_errors();
                }

                return FALSE;
            }
        }
    }



    return $returndata;
}



function convertDate($dateStr, $format = 'd-m-Y')
{

    return ($dateStr != FALSE) ? date($format, strtotime($dateStr)) : '';
}



function converttoDBDate($dateStr, $format = 'Y-m-d')
{

    return ($dateStr != FALSE) ? date($format, strtotime($dateStr)) : '';
}



function uniqueRefId($key_word, $table, $select)
{



    $keyresult = substr($key_word, -1);

    if ($keyresult == '-' || $keyresult == '/') {

        $key_word = substr($key_word, 0, -1);
    } else {

        $key_word = $key_word;
    }

    $ci = &get_instance();

    $cr_id = getMaxId($table, $select, $key_word);

    if ($key_word == '') {

        $newCid = ($cr_id == "") ? '1' : $cr_id + 1;

        return $new_cr_id = $newCid;
    }

    if ($cr_id == "") {



        $new_cr_id = $key_word . '-00001';
    } else {



        $old_cr_id = explode('-', $cr_id);



        $old_cr_id = $old_cr_id[0];



        str_pad($old_cr_id, 5, 4);

        $new_cr_id = $old_cr_id + 1;

        $length = strlen($new_cr_id);



        if ($length == 1) {

            $newapp = $key_word . '-0000';
        } else if ($length == 2) {

            $newapp = $key_word . '-000';
        } else if ($length == 3) {

            $newapp = $key_word . '-00';
        } else if ($length == 4) {

            $newapp = $key_word . '-0';
        } else if ($length == 5) {

            $newapp = $key_word . '-';
        }



        $new_cr_id = $newapp . $new_cr_id;
    }



    return $new_cr_id;
}



function uniqueIntRefId($key_word, $table, $select, $primaryid = '', $where = array())
{



    $keyresult = substr($key_word, -1);

    if ($keyresult == '-' || $keyresult == '/') {

        $key_word = substr($key_word, 0, -1);
    } else {

        $key_word = $key_word;
    }

    $ci = &get_instance();

    $cr_id = getIntMaxId($table, $select, $key_word, $primaryid, $where);



    if ($key_word == '') {

        $newCid = ($cr_id == "") ? '1' : $cr_id + 1;

        return $new_cr_id = $newCid;
    }

    if ($cr_id == "") {



        $new_cr_id = $key_word . '-00001';
    } else {



        //            $old_cr_id = explode('-', $cr_id);

        //

        //            $old_cr_id = $old_cr_id[0];

        //

        //            str_pad($old_cr_id, 5, 4);

        //            $new_cr_id = $old_cr_id + 1;

        //            $length = strlen($new_cr_id);

        if (!empty($where)) {

            $new_cr_id = $cr_id;
        } else {

            $new_cr_id = $cr_id + 1;
        }



        $length = strlen($cr_id);



        if ($length == 1) {

            $newapp = $key_word . '-0000';
        } else if ($length == 2) {

            $newapp = $key_word . '-000';
        } else if ($length == 3) {

            $newapp = $key_word . '-00';
        } else if ($length == 4) {

            $newapp = $key_word . '-0';
        } else if ($length == 5) {

            $newapp = $key_word . '-';
        }



        $new_cr_id = $newapp . $new_cr_id;
    }



    return $new_cr_id;
}



function getIntMaxId($table, $select, $text = '', $orid = '', $where = array())
{

    $ci = &get_instance();

    $substr = 0;

    if ($text != '') {

        $tlength = strlen($text);

        $substr = $tlength + 1;
    }



    $ci->db->select($select . ' as max_val');

    $ci->db->from($table);

    $ci->db->order_by($orid, "desc");

    //        if($text !=''){

    //            $ci->db->like($select, $text);

    //        }

    $ci->db->limit(1);

    if (count($where) > 0) {

        $ci->db->where($where);
    }

    $query = $ci->db->get();



    if ($query->num_rows() > 0) {

        $result = $query->row();



        //            $id = ($substr !='') ? substr($result->max_val,$substr) : $result->max_val ;

        $id = ($substr != '') ? $result->max_val : $result->max_val;

        return $id;
    }
}



function getMaxId($table, $select, $text = '')
{

    $ci = &get_instance();

    $substr = 0;

    if ($text != '') {

        $tlength = strlen($text);

        $substr = $tlength + 1;
    }



    $ci->db->select('count(' . $select . ') as max_val');

    //        $ci->db->select('count('.$select.') as max_val');

    $ci->db->from($table);

    //        $ci->db->order_by($orid, "desc");

    //        if($text !=''){

    //            $ci->db->like($select, $text);

    //        }

    //        $ci->db->limit(1);

    ////        if(count($where) > 0){

    //            $ci->db->where($where);

    //        }

    $query = $ci->db->get();



    if ($query->num_rows() > 0) {

        $result = $query->row();

        $id = ($substr != 0) ? $result->max_val : 0;

        return $id;
    }
}



function buildPermission($selectedmenuids = "")
{



    $ci = &get_instance();

    $menu = array();

    $moduleWhere['return'] = 'result';

    $getMenu = callProcedure(' PR_GET_MENU_LIST() ', $moduleWhere);

    $html_out = "";



    foreach ($getMenu as $menus) {

        $menu = (array) $menus;





        if ($menu['status'] && $menu['parent'] == 0) {

            if ($menu['is_parent'] == TRUE) {



                $html_out .= '<tr><td>

                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '" style="color: #0093E1;font-weight:bold;">' . $menu['name'] . ' </label><span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="parent parent_all " name="parent[]" id="parent_' . $menu['id'] . '" data-id="' . $menu['id'] . '" ></span></td>

                                                <td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_add_' . $menu['id'] . ' cparent_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . '" ></td>'

                    . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_edit_' . $menu['id'] . ' cparent_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][edit]" id="' . $selectedmenuids . 'selmenu_edit_' . $menu['id'] . '" ></td>'

                    . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_view_' . $menu['id'] . ' cparent_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][view]" id="' . $selectedmenuids . 'selmenu_view_' . $menu['id'] . '" ></td>'

                    . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_delete_' . $menu['id'] . ' cparent_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][delete]" id="' . $selectedmenuids . 'selmenu_delete_' . $menu['id'] . '" ></td>'

                    . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_print_' . $menu['id'] . ' cparent_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][print]" id="' . $selectedmenuids . 'selmenu_print_' . $menu['id'] . '" ></td>'

                    . '</tr>';
            } else {

                $html_out .= '<tr> <td>

                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">

                                                    ' . $menu['name'] . ' <span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="parent" name="parent[]" id="parent_' . $menu['id'] . '" data-id="' . $menu['id'] . '" ></span>

                                                </label>

                                </td>

                                <td>

                                                <input  type="checkbox" class="role_perm parent selmenu_add_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . '" ></td>

                                                     <td>

                                                <input  type="checkbox" class="role_perm parent selmenu_edit_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][edit]" id="' . $selectedmenuids . 'selmenu_edit_' . $menu['id'] . '" ></td>

                                                     <td>

                                                <input  type="checkbox" class="role_perm parent selmenu_view_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][view]" id="' . $selectedmenuids . 'selmenu_view_' . $menu['id'] . '" ></td>

                                                     <td>

                                                <input  type="checkbox" class="role_perm parent selmenu_delete_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][delete]" id="' . $selectedmenuids . 'selmenu_delete_' . $menu['id'] . '" ></td>

                                                       <td>

                                                <input  type="checkbox" class="role_perm parent selmenu_print_' . $menu['id'] . '" name="checklistmenu[' . $menu['id'] . '][print]" id="' . $selectedmenuids . 'selmenu_print_' . $menu['id'] . '" ></td>

                                </tr>';
            }



            // loop to build all the child submenu

            $html_out .= getChildPermission($getMenu, $menu['id'], $selectedmenuids);
        }
    }



    return $html_out;
}



function get_parent($id)
{

    // echo $id . "<br>";

    $ci = &get_instance();

    $table = 'left_menu';

    $ci->db->where('MENU_ID', $id);

    $query = $ci->db->get($table);

    $result = $query->row();

    if ($result->MENU_PARENT_ID != '0') {

        return get_parent($result->MENU_PARENT_ID);
    } else {



        return $result->MENU_ID;
    }
}



function getChildPermission($menus, $parent_id, $selectedmenuids)
{

    $has_subcats = FALSE;



    $html_out = '';



    foreach ($menus as $menu) {

        $menu = (array) $menu;

        $subparent_id = get_parent($menu['id']);

        if ($menu['status'] && $menu['parent'] == $parent_id) {

            $has_subcats = TRUE;

            if ($menu['is_parent'] == TRUE) {

                $html_out .= ' <tr><td>

                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">' . $menu['name'] . '</label><span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="mainparent gchild child_' . $menu['parent'] . '" name="parent[]" id="parent_' . $menu['id'] . '" data-id="' . $menu['id'] . '" ></span>

                                </td>

                                <td><input  type="checkbox" class="role_perm selmenu_add_' . $menu['id'] . ' child_' . $menu['parent'] . ' cparent_' . $menu['id'] . '"   name="checklistmenu[' . $menu['id'] . '][add]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_edit_' . $menu['id'] . ' child_' . $menu['parent'] . ' cparent_' . $menu['id'] . '"   name="checklistmenu[' . $menu['id'] . '][edit]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_view_' . $menu['id'] . ' child_' . $menu['parent'] . ' cparent_' . $menu['id'] . '"   name="checklistmenu[' . $menu['id'] . '][view]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_delete_' . $menu['id'] . ' child_' . $menu['parent'] . ' cparent_' . $menu['id'] . '"   name="checklistmenu[' . $menu['id'] . '][delete]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_print_' . $menu['id'] . ' child_' . $menu['parent'] . ' cparent_' . $menu['id'] . '"   name="checklistmenu[' . $menu['id'] . '][print]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '</tr>';
            } else {

                $html_out .= '   <tr><td>

                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">

                                                    ' . $menu['name'] . '

                                                </label>

                                </td><td><input  type="checkbox" class="role_perm selmenu_add_' . $menu['id'] . ' child_' . $menu['parent'] . '"  name="checklistmenu[' . $menu['id'] . '][add]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_edit_' . $menu['id'] . ' child_' . $menu['parent'] . '"  name="checklistmenu[' . $menu['id'] . '][edit]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_view_' . $menu['id'] . ' child_' . $menu['parent'] . '" name="checklistmenu[' . $menu['id'] . '][view]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_delete_' . $menu['id'] . ' child_' . $menu['parent'] . '"   name="checklistmenu[' . $menu['id'] . '][delete]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '<td><input  type="checkbox" class="role_perm selmenu_print_' . $menu['id'] . ' child_' . $menu['parent'] . '"   name="checklistmenu[' . $menu['id'] . '][print]" id="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '"  data-id="' . $subparent_id . '"></td>'

                    . '</tr>';
            }



            // Recurse call to get more child submenus.

            $html_out .= getChildPermission($menus, $menu['id'], $selectedmenuids);
        }
    }

    return ($has_subcats) ? $html_out : FALSE;
}



function getnotifyGroup($moduleid, $step)
{

    $ci = &get_instance();

    $table = NOTIFYMODS;

    $ci->db->select('grp_id');

    $ci->db->where(array('module' => $moduleid, 'stepid' => $step));

    $query = $ci->db->get($table);

    $row = isset($query->row()->grp_id) ? $query->row()->grp_id : 0;

    return $row;
}



function gettasktype($task)
{

    $newtask = '';

    if ($task == 'incident') {

        $newtask = 'INCIDENT';
    } else if ($task == 'emer') {

        $newtask = 'EMER';
    } else if ($task == 'hazard') {

        $newtask = 'HAZARD';
    } else if ($task == 'toolbox') {

        $newtask = 'TOOL';
    } else if ($task == 'audit') {

        $newtask = 'AUDIT';
    } else if ($task == 'inspection') {

        $newtask = 'INSPEC';
    }

    return $newtask;
}



function getmoduleId($modulekey)
{

    $ci = &get_instance();

    $table = 'left_menu';

    $ci->db->select('MENU_ID');

    $ci->db->where('MENU_MODULE_KEY', $modulekey);

    $query = $ci->db->get($table);

    $row = isset($query->row()->id) ?  $query->row()->id : 0;

    return $row;
}



function insertNotifications($data = array())
{

    $ci = &get_instance();

    $modulekey = (isset($data['module'])) ? $data['module'] : '';

    $step = (isset($data['step'])) ? $data['step'] : '';

    $type = $data['type'];

    $module_auto_id = (isset($data['module_auto_id'])) ? $data['module_auto_id'] : '';

    $employee = (isset($data['employee'])) ? $data['employee'] : '';

    $mobilelink = (isset($data['mobilelink'])) ? $data['mobilelink'] : '';

    $notifytext = (isset($data['text'])) ? $data['text'] : '';

    $notifyurl = (isset($data['url'])) ? $data['url'] : '';

    $moduleid = getmoduleId($modulekey);   //27

    //    $notifygroup = getnotifyGroup($moduleid, $step);

    $notifygroup = (isset($data['notifygroup'])) ? $data['notifygroup'] : '';

    $task = gettasktype($modulekey);



    $datanew = [

        'NOTIFICATION_DESC' => $notifytext,

        'NOTIFICATION_TYPE' => $type,
        'MODULE_AUTO_ID' => $module_auto_id,

        'NOTIFICATION_GROUP_CATID' => $notifygroup,

        'NOTIFICATION_HREF' => $notifyurl,

        'NOTIFICATION_EMPLOYEE_ID' => $employee,

        'NOTIFICATION_MOBILE_LINK' => $mobilelink,



        'NOTIFICATION_TASK_TYPE' => $task,

        'NOTIFICATION_IS_PUBLIC' => 1,

        'CREATED_ON' => date('Y-m-d H:i:s'),

        'UPDATED_ON' => date('Y-m-d H:i:s')

    ];

    $table = NOTI;

    $ci->db->insert($table, $datanew);

    //

    // echo $ci->db->last_query();
    // exit;
}



function getCurrentUserDetails()
{

    $ci = &get_instance();

    $userdata = $ci->session->user_details;

    return $userdata;
}



// Generates file include tags

function include_files($file = array(), $place = 'header')
{



    if (!isset($file['css']) && !isset($file['js']) && !isset($file['pre_defined'])) {

        return FALSE;
    }



    // Get priority level

    $priority = (!isset($file['priority'])) ? 'low' : $file['priority'];



    // Do avoid double time including file(s)

    if (($place == 'header' && $priority == 'low') or ($place == 'footer' && $priority == 'high')) {

        return FALSE;
    }



    // Get base URL

    $base_url = BASE_URL;



    global $pre_defined_files;



    $str = '';

    if (!empty($file['css'])) {

        foreach ($file['css'] as $item) {

            $href = (preg_match('/\b(http|https)\b/', $item) or strpos($item, '//') !== FALSE) ? $item : $base_url . $item;

            $str .= '<link rel="stylesheet" href="' . $href . '" type="text/css" />' . "\n";
        }
    }



    if (!empty($file['pre_defined'])) {

        foreach ($file['pre_defined'] as $item) {

            if (isset($pre_defined_files[$item]) && isset($pre_defined_files[$item]['css'])) {

                foreach ($pre_defined_files[$item]['css'] as $item) {

                    $href = (preg_match('/\b(http|https)\b/', $item) or strpos($item, '//') !== FALSE) ? $item : $base_url . $item;

                    $str .= '<link rel="stylesheet" href="' . $href . '" type="text/css" />' . "\n";
                }
            }
        }
    }



    if (!empty($file['js'])) {

        foreach ($file['js'] as $item) {

            $href = (preg_match('/\b(http|https)\b/', $item) or strpos($item, '//') !== FALSE) ? $item : $base_url . $item;

            $str .= '<script src="' . $href . '"></script>' . "\n";
        }
    }



    if (!empty($file['pre_defined'])) {

        foreach ($file['pre_defined'] as $item) {

            if (isset($pre_defined_files[$item]) && isset($pre_defined_files[$item]['js'])) {

                foreach ($pre_defined_files[$item]['js'] as $item) {

                    $href = (preg_match('/\b(http|https)\b/', $item) or strpos($item, '//') !== FALSE) ? $item : $base_url . $item;

                    $str .= '<script src="' . $href . '"></script>' . "\n";
                }
            }
        }
    }



    return $str;
}



// function uniqueOTP(){

//         $otp = rand(100000,999999);

//         $updateOTPData = array(

//            'CONT_OTP_MOBILE'=>$otp

//         ); 

//         $table = CONT_DET;

//         $uniqueInfo = getDataInfo($table, $updateOTPData, $limit = "", $offset = "", $orderby = "", $disporder = "",$returnType='row');

//         if($uniqueInfo == FALSE){

//             return $otp;

//         }else{

//            return $this->uniqueOTP();

//         }

//        return TRUE;

//     }



function getDataInfo($table, $where = array(), $limit = "", $offset = "", $orderby = "", $disporder = "", $returnType = 'result')
{

    $ci = &get_instance();

    $findInset = (isset($where['inset'])) ? $where['inset'] : '';

    if (count($where) > 0) {

        if (isset($where['in']) && count($where['in']) > 0) {

            foreach ($where['in'] as $key => $val) {

                $ci->db->where_in($key, $val);
            }
        } else if (isset($where['notin']) && count($where['notin']) > 0) {

            foreach ($where['notin'] as $key => $val) {

                $ci->db->where_not_in($key, $val);
            }
        } else if (isset($where['inset'])) {

            foreach ($findInset as $key => $val) {

                $ci->db->where('FIND_IN_SET("' . $val . '",' . $key . ') !=0');
            }
        } else {

            $ci->db->where($where);
        }

        if (isset($where['where']) && count($where['where']) > 0) {

            $ci->db->where($where['where']);
        }
    }



    if ($orderby != "" && $disporder != "")

        $ci->db->order_by($orderby, $disporder);

    else

        $ci->db->order_by("id", "asc");



    if ($limit != "" && $offset != "")

        $ci->db->limit($limit, $offset);



    $result =  $ci->db->get($table);

    //echo $ci->db->last_query();

    if ($result != false && $result->num_rows() > 0)

        return   $result->$returnType();

    else

        return false;
}



function getUsertypdesig($typid = [], $editId = [])
{

    $ci = &get_instance();

    $ci->db->select('*');

    $ci->db->from(DESIG . ' as des');

    $ci->db->join(UTYPE_DESIG . ' as udes', 'udes.USER_DESIGN_ID = des.DESIGNATION_ID', 'left');

    $ci->db->where_in('udes.USER_TYPE_ID', $typid);

    $ci->db->where('udes.USER_TYPE_DESIGNATION_STATUS', 'Y');

    $query =  $ci->db->get();

    //       $exeDropdown = [];

    if ($query->num_rows() > 0) {

        $execDatas = $query->result();

        $exeDropdown = customFormDropDown($execDatas, 'DESIGNATION_ID', 'DESIGNATION_NAME', 'Select Executives');



        return $exeDropdown;
    } else {

        return false;
    }
}

function getUsertypdesigEmpl($typid = [], $editId = [])
{

    $ci = &get_instance();

    $ci->db->select('*');

    $ci->db->from(DESIG . ' as des');

    $ci->db->join(UTYPE_DESIG . ' as udes', 'udes.USER_DESIGN_ID = des.DESIGNATION_ID', 'left');

    $ci->db->where_in('udes.USER_TYPE_ID', $typid);

    $ci->db->where('udes.USER_TYPE_DESIGNATION_STATUS', 'Y');

    $query =  $ci->db->get();

    //       $exeDropdown = [];

    if ($query->num_rows() > 0) {

        $execDatas = $query->result();



        foreach ($execDatas as $exKey => $exVal) {

            $desigId[] = $exVal->DESIGNATION_ID;
        }

        if (isset($desigId) && !empty($desigId)) {

            $ci->db->select("*");

            $ci->db->from(EMPL);

            $ci->db->where_in('EMP_DESIGNATION_ID', $desigId);

            $emplDet = $ci->db->get();

            if ($emplDet->num_rows() > 0) {

                $getEmpl = $emplDet->result();

                $empnamesDropdown = customFormDropDown($getEmpl, 'EMP_AUTO_ID', 'EMP_NAME', 'Select Employee');

                return $empnamesDropdown;
            } else {

                return false;
            }
        }
    } else {

        return false;
    }
}



function getUserdetbyDesig($desid = [])
{

    $ci = &get_instance();

    $ci->db->select('des.*,log.LOGIN_ID,emp.EMP_EMAIL_ID');

    $ci->db->from(DESIG . ' as des');

    $ci->db->join(LOGIN . ' as log', 'log.USER_DESINATION_ID = des.DESIGNATION_ID', 'left');

    $ci->db->join(EMPL . ' as emp', 'emp.EMP_DESIGNATION_ID = des.DESIGNATION_ID', 'left');

    $ci->db->where_in('des.DESIGNATION_ID', $desid);

    $ci->db->where('des.DESIGNATION_STATUS', 'Y');

    $query =  $ci->db->get();



    //       $exeDropdown = [];

    if ($query->num_rows() > 0) {

        return $query->result();
    } else {

        return false;
    }
}



function internalNotificationOverall($data = [])
{



    $notifyMsg = postData($data, 'notifyMsg');

    $taskMsg = postData($data, 'taskMsg');

    $url = postData($data, 'url');

    $inspId = postData($data, 'inspId');

    $notifytoID = postData($data, 'notifytoID');

    $taskTo = postData($data, 'taskTo');

    $emplmailID = postData($data, 'emplmailID');

    $notifyMailids = postData($data, 'notifyMailids');

    $notifytoNames = postData($data, 'notifytoNames');

    $message = postData($data, 'message');

    $messageText = postData($data, 'messageText');

    $messageTextnotify = postData($data, 'messageTextnotify');

    $footerText = postData($data, 'footerText');

    $subject = postData($data, 'subject');

    $title = postData($data, 'title');

    $emplName = postData($data, 'emplName');

    $moduleName = postData($data, 'moduleName');

    $atchFile = postData($data, 'attach_file');

    $mainModule = postData($data, 'mainModule');

    $module_auto_id = postData($data, 'module_auto_id');



    //    echo "<pre>";

    //    print_r($atchFile);

    $ci = &get_instance();

    $ci->load->model('Email_model');

    ///web notification start

    if (isset($taskTo) && !empty($taskTo)) {

        $taskdata = [

            'text' => $taskMsg,

            'url' => $url,

            'type' => TASK,

            'module' => $moduleName,

            'mobilelink' => $inspId,

            'employee' => $taskTo,

            'module_auto_id' => $module_auto_id,

            'step' => 1

        ];

        insertNotifications($taskdata);
    }



    if (isset($notifyMsg) && !empty($notifyMsg)) {

        $notifydata = [

            'text' => $notifyMsg,

            'url' => $url,

            'type' => 1,

            'module' => $moduleName,

            'mobilelink' => $inspId,

            'employee' => $notifytoID,
            'module_auto_id' => $module_auto_id,

            'step' => 1

        ];



        insertNotifications($notifydata);
    }

    ///web notification end



    ///push notification start

    $tokens = $ci->common_model->getUsertoken($taskTo);



    if ($tokens != '') {

        foreach ($tokens as $data) {



            $regId = $data->token;



            if ($regId != '') {

                $result = SendNotification($title, $message, $regId);



                //print_r($result);

            }
        } //exit;

    }

    ///push notification end



    //////////mail notification start

    if (isset($emplmailID) && $emplmailID != '') {



        if ($mainModule != 'masterMod') {

            $data = array(

                'view_file' => 'common/email/common_mail',

                'name' => $emplName,

                'messageText' => $messageText,

                //                        'footerText' => $footerText,

            );
        } else {

            $data = array(

                'view_file' => 'common/email/meeting_invitation',

                'name' => $emplName,

                'messageText' => $messageText,

                //                        'footerText' => $footerText,

            );
        }

        $template = $ci->template->load_email_template($data);



        $ci->Email_model->sendEmail($emplmailID, $subject, $template, $atchFile);
    }



    //                if (isset($notifyMailids) && $notifyMailids != '') {

    //                    $expMailid = explode(",",$notifyMailids);

    //                    $expNames = explode(",",$notifytoNames);

    //                    foreach($expMailid as $mKey => $mVal){

    //                        $data = array(

    //                            'view_file' => 'common/email/meeting_invitation',

    //                            'name' => $expNames[$mKey],

    //                            'messageText' => $messageTextnotify,

    ////                            'footerText' => $footerText,

    //                        );

    //                        $template = $ci->template->load_email_template($data);

    //                     $ci->Email_model->sendEmail($mVal, $subject, $template,$atchFile);

    //                      

    //                    }

    //                }



    //////email end



}










function validPost($postval = '', $retunval = '')
{
    return (isset($postval) && ($postval != '')) ? $postval : $retunval;
}

// function getUserDetails($return){

//     $ci = & get_instance();

//   $options['where']=[

//       'LD.USER_TYPE_ID'=>5,

//       'EMP.EMP_LOGIN_STATUS'=>'E',

//   ];

//   $options['return_type'] = 'result';

//   $options['join'] = [

//       LOGIN .' AS LD'=>['LD.USER_REF_ID = EMP.EMP_AUTO_ID','INNER']

//   ];

//   $actvity = $ci->common_model->getAlldata(EMPL.' AS EMP', ['LD.LOGIN_ID','EMP.EMP_AUTO_ID','EMP.EMP_NAME'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");



//   if($return == 'drop'){

//       return $dropdownDetails = customFormDropDown($actvity,'LOGIN_ID','EMP_NAME','Select Employee List');

//   }else{

//       return $actvity;

//   }

// }



// function getUserDetailsNew($return){

//     $ci = & get_instance();

//   $options['where']=[

//       'LD.USER_TYPE_ID'=>5,

//       'EMP.EMP_LOGIN_STATUS'=>'E',

//   ];

//   $options['return_type'] = 'result';

//   $options['join'] = [

//       LOGIN.' AS LD'=>['LD.USER_REF_ID = EMP.EMP_AUTO_ID','INNER']

//   ];

//   $actvity = $ci->common_model->getAlldata(EMPL.' AS EMP', ['LD.LOGIN_ID','EMP.EMP_AUTO_ID','EMP.EMP_NAME'], $options, $limit = "", $offset = "", $orderby = "", $disporder = "");



//   if($return == 'drop'){

//       return $dropdownDetails = customFormDropDown($actvity,'LOGIN_ID','EMP_NAME','');

//   }else{

//       return $actvity;

//   }

// }

function getuniqueId($table, $column, $char)
{
    $ci = &get_instance();

    $ci->db->select("max($column) as counts");

    $result = $ci->db->get($table)->row();

    $maxid = $result->counts;

    $maxid = $maxid + 1;

    $returnId = str_pad($maxid, 4, 0, STR_PAD_LEFT);

    return "$char-" . $returnId;
}
