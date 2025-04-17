<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
          <div class="row">            
                 <div class="col-md-12">                
                    <div class="card card-headprimary">
                        <div class="row headingStyl">
                            <div class="col-md-12">
                                   
                                     <div class="" style="margin-top:10px;">
                                        <?php echo $this->session->flashdata('permission_msg'); 
                                        $this->session->unset_userdata('permission_msg');



                                        ?>
                                    </div>
                                </div>  
                                
                            </div>
                            <div class="card-header">
                            <h4 class="card-title">User Permission</h4>
</div>
                    <div class="card-body">
                          <?php echo form_open('common/permission/savePermission/', 'class="form-horizontal" id="permission" novalidate'); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="user_type" class="control-label">Roles <span style="color:red;"> * </span></label>
                                                <?php
                                                $roledata = [
                                                    'class' => 'form-control select2',
                                                    'id' => 'user_type',
                                                ];
                                                echo form_dropdown('user_type', $userTypeInfo, '', $roledata);
                                                ?>
                                                <span class="error"><?php echo form_error('user_type') ?></span>

                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <span class="error"><?php echo form_error('checklistmenu[]') ?></span>
                                            <table class="table table-bordered" border="1">
                                                <thead>
                                                    <tr>

                                                        <th>Menu</th>
                                                        <th style="text-align: center;">Menu Display Settings</th>
                                                       <!--   <th>Edit</th>
                                                        <th>View</th>
                                                        <th>Delete</th>
                                                        <th>Print</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php

                                                     //echo "SDGsd";exit;
                                                    echo buildUserTypePermission();
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>


                                        <div class="form-group" style="text-align: center;">
                                            <div class=""> 

                                                <?php
                                                $data = array('id' => 'submit', 'type' => 'submit', 'content' => '<i class="fa fa-floppy-o" aria-hidden="true"></i> SUBMIT', 'class' => 'btn btn-primary');
                                                echo form_button($data);
                                                ?>
                                            </div>
                                        </div>

                                        <!-- input states -->
                                        <?php echo form_close(); ?>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

</div>
<script type="text/javascript">

    $(document).ready(function () {
       
        var baseURL = '<?php echo BASE_URL(); ?>';
        $("#permission").submit(function (  ) {
            swal({
                title: "Don't close the tab,Permission is updating...",
                text: "Please wait....",
                imageUrl: baseURL + "assets/images/loader.gif",
                showCancelButton: false,
                showConfirmButton: false
            })
            return true;
        });

       
        $(".parent").click(function () {
            var parId = $(this).attr('data-id');

            if ($(this).prop('checked') == true) {
                $('.child_' + parId).prop('checked', true);
                $('.cparent_' + parId).prop('checked', true);

            } else if ($(this).prop('checked') == false) {
                $('.child_' + parId).prop('checked', false);
                $('.cparent_' + parId).prop('checked', false);
            }
           
            parenAll();


        });
        
        $('.child').click(function () {
            var parId = $(this).attr('data-id');
            var parref = $(this).attr('data-reftype');
            var parentId = $(this).attr('data-parent-id');
             if ($(this).prop('checked') == true) {
                 var checked = $(".child_"+parentId+":checked").length;
               
                 if(checked > 0){
                     $('#selmenu_'+parref +"_"+ parId).prop('checked', true);
                     $('#selmenu_'+parref +"_"+ parentId).prop('checked', true);
                 }
             }else{
                  var checked = $(".child_"+parentId+":checked").length;
                  if(checked == 0){
                      $('#selmenu_'+parref +"_"+ parId).prop('checked', false);
                     $('#selmenu_'+parref +"_"+ parentId).prop('checked', false);
                  }
             }
        });

        $(".mainparent").click(function () {
            var parId = $(this).attr('data-id');

            if ($(this).prop('checked') == true) {
                $('.child_' + parId).prop('checked', true);
                $('.cparent_' + parId).prop('checked', true);

            } else if ($(this).prop('checked') == false) {
                $('.child_' + parId).prop('checked', false);
                $('.cparent_' + parId).prop('checked', false);
            }


            if ($('.child_' + parId).hasClass("gchild") && $('.child_' + parId).is(':checked')) {
                var cid = $('.child_' + parId).attr('data-id');
                $('.child_' + cid).prop('checked', true);
                $('.cparent_' + cid).prop('checked', true);
            } else if ($('.child_' + parId).hasClass("gchild") && $('.child_' + parId).not(':checked')) {
                var cid = $('.child_' + parId).attr('data-id');    
                 $('.child_' + cid).prop('checked', false);
                 $('.cparent_' + cid).prop('checked', false);
            }




        });
        function parenAll() {

            $('.mainparent').each(function () {
                var parId = $(this).attr('data-id');
                if (typeof parId != 'undefined') {
                    var cid = $(this).attr('data-id');
                    if (this.checked) {
                        $('.child_' + cid).prop('checked', true);
                        $('.cparent_' + cid).prop('checked', true);
                    } else {
                        $('.child_' + cid).prop('checked', false);
                        $('.cparent_' + cid).prop('checked', false);
                    }


                }

            });

        }

        function getPermission(pType, pId) {
            $('.role_perm').prop('checked', false)
            var url = "<?php echo BASE_URL('common/permission/getPermissionDetails') ?>";

            var data = {perType: pType, perId: pId, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}
            $.ajax({
                dataType: 'json',
                data: 'ajax',
                method: 'post',
                data: data,
                url: url,
                success: function (resp) {
                    if (resp.status) {
                        $(resp.list).each(function (index, val) {
                            console.log(val.MENU_ID);
                            let mid = val.MENU_ID;
                            let aid = val.ADD_PER;
                            let eid = val.EDIT_PER;
                            let vid = val.VIEW_PER;
                            let did = val.DEL_PER;
                            let pid = val.PRINT_PER;
                            $('.selmenu_add_' + mid).prop('checked', false)
                            $('.selmenu_edit_' + mid).prop('checked', false)
                            $('.selmenu_view_' + mid).prop('checked', false)
                            $('.selmenu_delete_' + mid).prop('checked', false)
                            $('.selmenu_print_' + mid).prop('checked', false)
                            if (mid > 0 && aid == 1) {
                                $('.selmenu_add_' + mid).prop('checked', true)
                            }
                            if (mid > 0 && eid == 1) {
                                $('.selmenu_edit_' + mid).prop('checked', true)
                            }
                            if (mid > 0 && vid == 1) {
                                $('.selmenu_view_' + mid).prop('checked', true)
                            }
                            if (mid > 0 && did == 1) {
                                $('.selmenu_delete_' + mid).prop('checked', true)
                            }
                            if (mid > 0 && pid == 1) {
                                $('.selmenu_print_' + mid).prop('checked', true)
                            }

                        });
                    }

                }
            });

        }
        $('#user_type').on('change', function () {
            var pType = 'user_type';
            var pId = $(this).val();
            getPermission(pType, pId);
        })
       

    });
</script>

