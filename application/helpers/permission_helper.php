<?php

function buildUserTypePermission($selectedmenuids = "") {

    $ci = & get_instance();
    $menu = array();
    $moduleWhere['return'] = 'result';
    $getMenu = callProcedure(' PR_GET_MENU_LIST() ', $moduleWhere);
    $html_out = "";

    foreach ($getMenu as $menus) {
        $menu = (array) $menus;


        if ($menu['status'] && $menu['parent'] == 0) {
            if ($menu['is_parent'] == TRUE) {

                $html_out .= '<tr><td>
                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '" style="color: #0093E1;font-weight:bold;">' . $menu['name'] . ' </label><span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="parent parent_all " name="parent[]" id="parent_'.$menu['id'].'" data-id="'.$menu['id'].'" ></span></td>
                                                <td style="text-align:center;"><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_add_'.$menu['id'].' cparent_'.$menu['id'].'" name="checklistmenu['.$menu['id'].'][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . '" ></td>'
//                        . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_edit_'.$menu['id'].' cparent_'.$menu['id'].'" name="checklistmenu['.$menu['id'].'][edit]" id="' . $selectedmenuids . 'selmenu_edit_' . $menu['id'] . '" ></td>'
//                        . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_view_'.$menu['id'].' cparent_'.$menu['id'].'" name="checklistmenu['.$menu['id'].'][view]" id="' . $selectedmenuids . 'selmenu_view_' . $menu['id'] . '" ></td>'
//                        . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_delete_'.$menu['id'].' cparent_'.$menu['id'].'" name="checklistmenu['.$menu['id'].'][delete]" id="' . $selectedmenuids . 'selmenu_delete_' . $menu['id'] . '" ></td>'
//                        . '<td><input  type="checkbox" class="role_perm parent ' . $menu['id'] . ' selmenu_print_'.$menu['id'].' cparent_'.$menu['id'].'" name="checklistmenu['.$menu['id'].'][print]" id="' . $selectedmenuids . 'selmenu_print_' . $menu['id'] . '" ></td>'
                        . '</tr>';
            } else {
                $html_out .= '<tr> <td>
                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">
                                                    ' . $menu['name'] . ' </label><span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="parent" name="parent[]" id="parent_'.$menu['id'].'" data-id="'.$menu['id'].'" ></span>
                                                
                                </td>
                                <td style="text-align:center;">
                                                <input  type="checkbox" class="role_perm parent selmenu_add_'.$menu['id'].' cparent_' . $menu['id'] . '" name="checklistmenu['.$menu['id'].'][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . '" ></td>
                                                
                                </tr>';
            }

            // loop to build all the child submenu
            $html_out .= getChildUserTypePermission($getMenu, $menu['id'], $selectedmenuids);
        }
    }

    return $html_out;
}

function getChildUserTypePermission($menus, $parent_id, $selectedmenuids) {
    $has_subcats = FALSE;

    $html_out = '';

    foreach ($menus as $menu) {
        $menu = (array) $menu;
        $subparent_id = get_parent($menu['id']);
        if ($menu['status'] && $menu['parent'] == $parent_id) {
            $has_subcats = TRUE;
            if ($menu['is_parent'] == TRUE) {
                $html_out .= ' <tr><td>
                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">' . $menu['name'] . '</label><span class="float-right">Select All&nbsp;&nbsp;<input  type="checkbox" class="mainparent gchild child_' . $menu['parent'] . '" name="parent[]" id="parent_'.$menu['id'].'" data-parent-id ="'.$menu['parent'].'"  data-id="'.$menu['id'].'" ></span>
                                </td>
                                <td style="text-align:center;"><input  type="checkbox" class="role_perm child selmenu_add_'.$menu['id'].' child_'.$menu['parent'].' cparent_' . $menu['id'] . '"   name="checklistmenu['.$menu['id'].'][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-parent-id ="'.$menu['parent'].'" data-reftype="add"></td>'
//                        . '<td><input  type="checkbox" class="role_perm child selmenu_edit_'.$menu['id'].' child_'.$menu['parent'].' cparent_' . $menu['id'] . '"   name="checklistmenu['.$menu['id'].'][edit]" id="' . $selectedmenuids . 'selmenu_edit_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-parent-id ="'.$menu['parent'].'" data-reftype="edit"></td>'
//                        . '<td><input  type="checkbox" class="role_perm child selmenu_view_'.$menu['id'].' child_'.$menu['parent'].' cparent_' . $menu['id'] . '"   name="checklistmenu['.$menu['id'].'][view]" id="' . $selectedmenuids . 'selmenu_view_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-parent-id ="'.$menu['parent'].'" data-reftype="view"></td>'
//                         . '<td><input  type="checkbox" class="role_perm child selmenu_delete_'.$menu['id'].' child_'.$menu['parent'].' cparent_' . $menu['id'] . '"   name="checklistmenu['.$menu['id'].'][delete]" id="' . $selectedmenuids . 'selmenu_delete_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-parent-id ="'.$menu['parent'].'" data-reftype="delete"></td>'
//                          . '<td><input  type="checkbox" class="role_perm child selmenu_print_'.$menu['id'].' child_'.$menu['parent'].' cparent_' . $menu['id'] . '"   name="checklistmenu['.$menu['id'].'][print]" id="' . $selectedmenuids . 'selmenu_print_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-parent-id ="'.$menu['parent'].'" data-reftype="print"></td>'
                        . '</tr>';
            } else {
                $html_out .= '   <tr><td>
                                                <label for="' . $selectedmenuids . 'selmenu_' . $menu['id'] . '">
                                                    ' . $menu['name'] . '
                                                </label>
                                </td><td style="text-align:center;"><input  type="checkbox" class="role_perm child selmenu_add_'.$menu['id'].' child_'.$menu['parent'].'"  name="checklistmenu['.$menu['id'].'][add]" id="' . $selectedmenuids . 'selmenu_add_' . $menu['id'] . ' "  data-id="' . $subparent_id . '" data-reftype="add" data-parent-id ="'.$menu['parent'].'"></td>'
//                        .'<td><input  type="checkbox" class="role_perm child selmenu_edit_'.$menu['id'].' child_'.$menu['parent'].'"  name="checklistmenu['.$menu['id'].'][edit]" id="' . $selectedmenuids . 'selmenu_edit_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-reftype="edit" data-parent-id ="'.$menu['parent'].'"></td>'
//                            .'<td><input  type="checkbox" class="role_perm child selmenu_view_'.$menu['id'].' child_'.$menu['parent'].'" name="checklistmenu['.$menu['id'].'][view]" id="' . $selectedmenuids . 'selmenu_view_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-reftype="view" data-parent-id ="'.$menu['parent'].'"></td>'
//                            .'<td><input  type="checkbox" class="role_perm child selmenu_delete_'.$menu['id'].' child_'.$menu['parent'].'"   name="checklistmenu['.$menu['id'].'][delete]" id="' . $selectedmenuids . 'selmenu_delete_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-reftype="delete" data-parent-id ="'.$menu['parent'].'"></td>'
//                            .'<td><input  type="checkbox" class="role_perm child selmenu_print_'.$menu['id'].' child_'.$menu['parent'].'"   name="checklistmenu['.$menu['id'].'][print]" id="' . $selectedmenuids . 'selmenu_print_' . $menu['id'] . '"  data-id="' . $subparent_id . '" data-reftype="print" data-parent-id ="'.$menu['parent'].'"></td>'
                        . '</tr>';
            }

            // Recurse call to get more child submenus.
            $html_out .= getChildUserTypePermission($menus, $menu['id'], $selectedmenuids);
        }
    }
    return ($has_subcats) ? $html_out : FALSE;
}
