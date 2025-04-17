<?php

$TERMINAL_ID = postData($editData, 'TERMINAL_ID');
$TERMINAL_NAME = postData($editData, 'TERMINAL_NAME');

$TERMINAL_SHORTNAME = postData($editData, 'TERMINAL_SHORTNAME');
$TERMINAL_EMAIL = postData($editData, 'TERMINAL_EMAIL');
$TERMINAL_PHONE = postData($editData, 'TERMINAL_PHONE');
$TERMINAL_MOBILE = postData($editData, 'TERMINAL_MOBILE');
$TERMINAL_ZIPCODE = postData($editData, 'TERMINAL_ZIPCODE');
$TERMINAL_ADDRESS = postData($editData, 'TERMINAL_ADDRESS');

?>

<style>
    .viewpage {
        /*        font-size: 14px;*/
    }
    .newhr {
        margin-top: 15px;
        margin-bottom: 15px;
        border-color: white;
    }
    
    .card-link{
        color: #fff;
    }
    .card-header a:hover{
        color: #fff;
    }
    .red{
        color:red;
    }
    .removeItem:not(:disabled):not(.disabled).active, .removeItem:not(:disabled):not(.disabled):active, .removeItem,.removeItem:hover{
        color: #fff;
        background-color: red;
        border-color:red;
    }
    .uppertext{
        text-transform: uppercase;
    }
    .card-header-cc {
        color: #fff;
        background-color: green;
    }
    .docLabel{
        margin-left: 15px;
    }

    
    .tdlabel{
        width:23%;
    }
    .title-head{
        text-transform: uppercase;
    }
    .title-colon{
        width:1%;
    }
    .imagepopcombine:hover .popupgal {
        opacity: 1;
    }
    .popupgal{
        position: absolute;
        font-size: 3rem !important;
        color: #fff;
        opacity: 0;
        transition: .2s opacity ease-in-out;
        z-index: 5;
        background: #b7b7b77d;
        min-width: 82%;
        min-height: 90%;
        max-width: 90%;
        top: 0;
        text-align: center;
        border: 2px solid #007cb7;
    }
    .popupgal i{
        margin-top: 25%;
    }

</style>
<div class="wrapper">


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="accordion" class="permitAccordion">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">


                            <!-- general form elements -->
                            <div class="card">

                                <div class="card-header ">
                                    <h3 class="card-title">View Plant</h3>
                                    <a href="<?php echo BASE_URL('master/terminal/terminalInfo') ?>"><button type="button" class="btn btn-primary backBtns"> Back</button></a>

                                </div>
                                <div class="card-body">
                                   
                                    <div class="card ">
                                        <div class="card-header card-header-inner">
                                        Plant Detail<span class="float-right"></span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row viewpage">
                                                <div class="col-12 table-responsive">
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant ID</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_ID != '') ? $TERMINAL_ID : '-'; ?></td>
                                                 
                                                        </tr>

                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Name</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_NAME != '') ? $TERMINAL_NAME : '-'; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Short Name</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_SHORTNAME != '') ? $TERMINAL_SHORTNAME : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Email </b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_EMAIL != '') ? $TERMINAL_EMAIL : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Phone Number</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_PHONE != '') ? $TERMINAL_PHONE : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Mobile Number</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_MOBILE != '') ? $TERMINAL_MOBILE : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant PIN Code</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_ZIPCODE != '') ? $TERMINAL_ZIPCODE : '-'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tdlabel"><b>Plant Address</b></td>
                                                            <td class="title-colon">:</td>
                                                            <td colspan="4"><?php echo ($TERMINAL_ADDRESS != '') ? $TERMINAL_ADDRESS : '-'; ?></td>
                                                        </tr>

                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                   
                                </div> 
                            </div>
                            <!-- /.card -->

                        </div>
                        <!--/.col (left) -->

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
