<?php
$editId = postData($getDesdatas, 'DESIGNATION_ID', 0);
$formTitle = ($editId > 0 ) ? 'Edit Designation Name' : 'Add Designation Name';

$uTypdesid = postData($utypDesdatas,'USER_TYPE_DESIGNATION_ID');
?>
<style>
    .modal-header{
        background: #e6af0a;
        padding : 10px;
    }
    .modal-title{
        color: white;
    }
    .form-group{
        margin-bottom: 0rem !important;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $formTitle; ?></h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<?php
echo form_open('#', 'class="form-horizontal" id="role-type" novalidate')
?>



<!-- Modal body -->
<div class="modal-body">
    <div class="form-group">
        <label>Designation ID  <span class="error">*</span></label>
        <?php
        $AutoId = isset($getDesdatas->DES_GENERATE_ID) && !empty($getDesdatas->DES_GENERATE_ID) ? $getDesdatas->DES_GENERATE_ID : $getAutoId;
        $cMaildatas = [
            'name' => 'des_id',
            'class' => 'form-control',
            'value' => $AutoId,
            'autocomplete' => 'off',
            'readonly' => TRUE
        ];
        echo form_input($cMaildatas);
        ?>
       <label class="error" id="">

        </label> 
    </div>

    <div class="form-group">
        <input type="hidden" name="uDes_editid" value="<?php echo $uTypdesid; ?>">
        <label for="user_typ"> User Role <span class="error"> * </span></label>
        <?php
       $editUsrtypid = postData($getDesdatas, 'DES_USER_TYPE');
        $usertypName = [
            'id' => 'user_typ',
            'class' => 'form-control select2',
            'checkSelect' => 'select2',
         ];
        echo form_dropdown('user_typ',$dropUsertp,$editUsrtypid,$usertypName);
        ?>
        <label class="error" id="user_type_error">

        </label>

    </div>
    <div class="form-group">
        <label for="loctyp"> Designation Name <span class="error"> * </span></label>
        <?php
        $editDesName = postData($getDesdatas, 'DESIGNATION_NAME');
        $terName = [
            'name' => 'des_name',
            'id' => 'des_name',
            'class' => 'form-control',
            'placeholder' => 'Enter Designation Name',
            'value' => $editDesName,
        ];
        echo form_input($terName);
        ?>
        <label class="error" id="des_name_error">

        </label>

    </div>
    <div class="form-group">
        <label>Designation Remark</label>
        <?php
        $getdescremark = isset($getDesdatas->DESIGNATION_REMARK) && !empty($getDesdatas->DESIGNATION_REMARK) ? $getDesdatas->DESIGNATION_REMARK : set_value('des_remark');
        $cAddr1data = array(
            'name' => 'des_remark',
            'id' => 'des_remark',
            'placeholder' => 'Enter Description Remark',
            'class' => 'form-control',
            'value' => $getdescremark,
            'rows' => 4,
            'cols' => 4,
            'autocomplete' => 'off'
        );
        echo form_textarea($cAddr1data);
        ?>
    </div>

</div>

<!-- Modal footer -->
<div class="modal-footer justify-content-center">
<?php echo form_hidden('ter_id', $editId); ?>
    <button type="submit" class="btn btn-primary ">Submit</button>
</div>
<?php form_close(); ?>

<script type="text/javascript">

    $(document).ready(function () {
        
        $(".select2").select2();
        
         $.validator.addMethod('alphanumeric', function (value) {
            return /^[A-Za-z0-9-/,.()&%  ]*$/.test(value);
        }, "Please Enter valid Alphanumeric characters with allowed special charters are /,.-&()%");

        
        $("#role-type").validate({

            rules: {
                "des_name": {
                    required: true,
                    // alphaspace: true,
                    maxlength: 100,
                    // alphanumeric: true,
                    minlength: 2
                },
                "des_remark": {
                  
                    programming_char: true,
                   
                },
                'user_typ':{
                    required: true,
                }

            },
            messages: {

                "des_name": {
                    required: "Designation Name is required"
                },
                'user_typ':{
                    required: 'User Type is required',
                }
                

            },
            errorPlacement: function (error, element) {

                if (element.attr('checkSelect') == 'select2') {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
//                    element.closest(".imgGroup").last().append(error);
                } 
            },
            submitHandler: function (form) {
                var formDatas = $('form').serialize();
                var url = "<?php echo BASE_URL('master/terminal/saveDesc') ?>";
                var data = formDatas;

                $.ajax({
                    type: 'ajax',
                    dataType: 'json',
                    method: 'post',
                    data: data,
                    url: url,
                    success: function (resp) {
                        var errMsgs = resp.status;
                        if (errMsgs == 'error') {

                            $('.error').html('');
                            $.each(resp.errors, function (key, value) {
                                $(".error").css('display', 'block');
                                var keys1 = key.replace(/\[/gi, '_');
                                var keys = keys1.replace(/\]/gi, '');
                                $('#' + keys + '_error').html(value);

                            });



                        } else if (errMsgs) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }

                    }
                });
            }

        });



    });


</script>

