$(document).ready(function () {

    $('.select2').select2()
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    $(".datepicker").datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        orientation: "bottom"
    });

    $(".singledaterangepicker").daterangepicker({
        singleDatePicker: true,

        locale: {
            format: 'DD-MM-YYYY',

        }
    });

});



function deleteDatas(url, deleteID, cName, cToken) {
    swal({
        title: "Are you sure",
        text: "You want to delete",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        closeButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true
    },
        function (isConfirm) {
            if (isConfirm) {
                var data = { delid: deleteID, 'csrf_osh_name': cToken }
                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    dataType: 'JSON',
                    cache: false,
                    success: function (resp) {

                        if (resp.status) {
                            swal({
                                title: "Success",
                                text: resp.msgs,
                                type: "success",
                                confirmButtonClass: "btn-primary",
                                confirmButtonText: "OK",
                                closeOnConfirm: false,

                            },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                }
                            )
                            //                                swal('Success', resp.msgs, 'success');

                        } else {
                            swal({
                                title: "Error",
                                text: resp.msgs,
                                type: "error",
                                confirmButtonClass: "btn-primary",
                                confirmButtonText: "OK",
                                closeOnConfirm: false,

                            },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                }
                            )

                        }
                    }
                });
            } else {
                swal('Sorry', 'There occured some error', 'warning');
                location.reload();
            }
        });


}

function deleteincCAPADatas(url, analID, clID, typID, cName, cToken) {
    swal({
        title: "Are you sure",
        text: "You want to delete",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        closeButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true
    },
        function (isConfirm) {
            if (isConfirm) {
                var data = { analID: analID, clID: clID, typID: typID, 'csrf_osh_name': cToken }
                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    dataType: 'JSON',
                    cache: false,
                    success: function (resp) {

                        if (resp.status) {
                            swal({
                                title: "Success",
                                text: resp.msgs,
                                type: "success",
                                confirmButtonClass: "btn-primary",
                                confirmButtonText: "OK",
                                closeOnConfirm: false,

                            },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                }
                            )
                            //                                swal('Success', resp.msgs, 'success');

                        } else {
                            swal({
                                title: "Error",
                                text: resp.msgs,
                                type: "error",
                                confirmButtonClass: "btn-primary",
                                confirmButtonText: "OK",
                                closeOnConfirm: false,

                            },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        location.reload();
                                    }
                                }
                            )

                        }
                    }
                });
            } else {
                swal('Sorry', 'There occured some error', 'warning');
                location.reload();
            }
        });


}


function validatekey(url, dInput, table, colmname, statusname, cName, cToken) {
    var data = { key: dInput, tablename: table, colmname: colmname, statusname: statusname, 'csrf_osh_name': cToken };
    var retresp = $.ajax({
        type: 'ajax',
        dataType: 'json',
        method: 'post',
        url: url,
        data: data,
        global: false,
        async: false,
        success: function (resp) {
            return resp;
        }
    }).responseJSON;
    console.log(retresp.status);
    return retresp.status;
}


$.validator.addMethod("keyMatchingvalue", function (value, element) {
    return false;
}, "Key Name already exists");

$.validator.addMethod("validate_email", function (value, element) {

    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid Email.");

$.validator.addMethod('alphanumeric', function (value) {
    return /^[A-Za-z0-9 ]*$/.test(value);
}, "Please Enter only Alphanumeric characters");

$.validator.addMethod('accept_address', function (value) {
    //    return /^[A-Za-z0-9/,.- ]*$/.test(value);
    return /^[A-Za-z0-9./,_ -() ]*$/.test(value);
}, "Please Enter a valid address");

$.validator.addMethod('alphanospace', function (value) {
    return /^[A-Za-z]*$/.test(value);
}, "Please Enter only Alphabetic characters.No spaces allowed");

$.validator.addMethod('alphaspace', function (value) {
    return /^[A-Za-z ]*$/.test(value);
}, "Please Enter only Alphabetic characters");

$.validator.addMethod('descrptns', function (value) {
    return /^[A-Za-z0-9/,.() ]*$/.test(value);
}, "Please Enter a valid Description");

$.validator.addMethod('programming_char', function (value) {
    return /[*|\"%:<>[\]{}`\\()';=!#@&$]/.test(value) === false;
}, "Please Enter a valid character");
$.validator.addMethod('programming_char_excep_brace_slash', function (value) {
    return /[*|\"%:<>[\]{}`\\';=!#@&$]/.test(value) === false;
}, "Please Enter a valid character");
$.validator.addMethod('brace_slash_alphaspace', function (value) {
    return /^[A-Za-z  /()]*$/.test(value);
}, "Please Enter a valid character");
$.validator.addMethod('brace_slash_hiphen_alphaspace', function (value) {
    return /^[A-Za-z  -\/()]*$/.test(value);
}, "Please Enter a valid character");
$.validator.addMethod('color_code', function (value) {
    return /^[A-Za-z0-9#]*$/.test(value);
}, "Please Enter a valid Color Code");

$.validator.addMethod('alphanumericwithspecialchar', function (value) {
    return /^[A-Za-z0-9/\-_.'",()-_ ‘ “  ]*$/.test(value);
}, "Please enter valid alphanumeric characters. Allowed special characters are - , _ , ' , \" , ( , ).");

$.validator.addMethod('alphaspacewithspecialchar', function (value) {
    return /^[A-Za-z/,. ]*$/.test(value);
}, "Please Enter valid Alphabetic characters with allowed special charters are /,.");




