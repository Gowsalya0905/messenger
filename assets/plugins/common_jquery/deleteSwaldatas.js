
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
                    var data = {delid: deleteID, 'csrf_osh_name': cToken}
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
