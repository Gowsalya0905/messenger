<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>login/app.css">
<link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>login/icons.css">
<!-- <link rel="stylesheet" href="<?php echo LAYOUT_PLUG_PATH_CSS; ?>login/boxicons.min.css"> -->
<!-- Boxicons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">

<div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7">
                    <div class="login-bg-img">
                        <img src="<?php echo BASE_URL . 'assets/images/login-bg.png'; ?>" class="img-fluid" alt="login-bg">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card" style="box-shadow:none;">
                        <div class="card-body">
                            <div class="p-4 rounded">
                                <div class="text-center login-sign">
                                    <img src="<?php echo BASE_URL . 'assets/images/country-logo.png'; ?>" class="img-fluid" style="width:100px;margin-bottom:10px;" alt="company-logo">
                                    <p class="welcome-text mb-2">Welcome to Eswatini</p>
                                    <h1 class="login-title mb-4">Online Visa</h1>
                                </div>
                                
                                <?php $message = $this->session->flashdata('Messges'); ?>
                                <?php if (!empty($message)) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <center><b><?php echo $message; ?></b></center>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php } ?>

                                <!-- Login Form -->
                                <div class="form-body LogForm" id="loginForm">
                                    <?php echo form_open('loginSubmit', ['class' => 'row g-3 needs-validation', 'novalidate' => '']); ?>
                                    <div class="col-12">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background-color:transparent;">
                                                <i class="bx bx-user" style="font-size:20px;color:#ced4da"></i>
                                            </span>
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" maxlength="40" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <span class="input-group-text" style="background-color:transparent;">
                                                <i class="bx bx-key" style="font-size:20px;color:#ced4da"></i>
                                            </span>
                                            <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="Enter Password" maxlength="40" required>
                                            <a href="javascript:;" class="input-group-text bg-transparent">
                                                <i class='bx bx-hide'></i>
                                            </a>
                                        </div>
                                    </div>

                                    <input type="hidden" name="page" value="<?php echo $page; ?>">

                                    <div class="col-12 mt-5">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary w-100">Login</button>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4 text-end" style="display: flex; flex-direction: row-reverse;">
                                        <a href="javascript:void(0)" id="forget" class="forget-text">Forgot your password?</a>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                                <!-- End Login Form -->

                                <!-- Forgot Password Section (Initially Hidden) -->
                                <div class="forgetPasswordDet d-none" id="forgetPasswordSection">
                                    <div class="alert_for_forget_password d-none" style="display:none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                        <span aria-hidden="true">&times;</span>
                                    </div>
                                    <div class="row justify-content-center">
                                       
                                        <!-- <div class="col-md-8 fgetuser">
                                            <! <label for="forgetUser" class="form-label">Enter Your Username</label>
                                            <input type="text" name="forgetUser" id="forgetUser" class="form-control forgetUser" placeholder="Enter Your Username" maxlength="40">
                                             -->
                                            <!-- <div id="count_timer" class="count_timer" style="display:none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                                <span aria-hidden="true">&times;</span>
                                            </div>

                                            <div class="my-3">
                                             <label class="form-label">Email ID</label>
                                             <div class="input-group">
                                             <span class="input-group-text"  style="background-color:transparent;"><i class="fadeIn animated bx bx-envelope" style="font-size:20px;color:#ced4da"></i></span>
                                             <input type="text" name="forgetUser" id="forgetUser" class="form-control forgetUser" placeholder="exemple@user.com" required />
                                             <div class="invalid-feedback">Please enter a valid electronic correo identification.</div>
                                             </div>
                                                            
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <input type="button" class="btn btn-primary w-100" id="save_f" value="Continue">
                                                    <button type="button" id="close_forget" class="btn btn-secondary w-100 mt-2">Cancel</button>
                                                </div>
                                            </div>
                                        </div> --> 


                                        <div class="card-body">
                                       <div class="p-4 rounded">
                                          <!-- <div class="text-center">
                                             <img src="assets/images/icons/lock.png" width="120" alt="" />
                                          </div> -->
                                          <h4 class="font-weight-bold">Forgot your password?</h4>
                                          <p class="text-muted">Enter your registered email ID to reset password</p>
                                          <div class="form-body">
                                          <div id="count_timer" class="count_timer" style="display:none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                                <span aria-hidden="true">&times;</span>
                                            </div>
                                        
                                             <div class="my-3">
                                             <label class="form-label">Email ID</label>
                                             <div class="input-group">
                                             <span class="input-group-text"  style="background-color:transparent;"><i class="fadeIn animated bx bx-envelope" style="font-size:20px;color:#ced4da"></i></span>
                                             <input type="text" name="forgetUser" id="forgetUser" class="form-control forgetUser" placeholder="exemple@user.com" required />
                                             <div class="invalid-feedback">Please enter a valid electronic correo identification.</div>
                                             </div>
                                          </div>
                                          <div class="d-grid gap-2 mt-0">
                                             <div class="">
                                             <button type="submit" class="btn btn-primary signin-btn"  id="save_f" style="width:50%">Send</button> <a href="#" class="btn signin-btn" style="width:49%"></i>Back to login</a>
                                             </div>
                                          </div>
                                     
                                       </div>
                                       </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- End Forgot Password Section -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</div>


   <?php
   $getUrlundatas = $this->input->get('username');

   $getUrlpassdatas = $this->input->get('password', true);

   //        $getUrlundatas = 'SXlSWk1wL2R2TEdkdVlnQ3c4Mldadz09';

   //        $getUrlpassdatas = 'QWxNQ2JZVDZIK2V5S3BsQ2VaVFVCUT09';



   $getDecuname = decryptval($getUrlundatas);

   $getDecPass = decryptval($getUrlpassdatas);



   ?>
   <script src="<?php echo BASE_URL; ?>assets/plugins/jquery/jquery.min.js"></script>
   <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script> -->
   <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
   <script src="<?php echo BASE_URL; ?>assets/plugins/jquery/jquery.min.js"></script>
   <script type="text/javascript">
      //    function setSizes() {

      //   var containerHeight = $(".mobileViewPur").height();

      //   $(".LogoImage").height(containerHeight - 40);

      //}

      //$(window).resize(function() { setSizes(); });



      $(document).ready(function() {

         var url_string = window.location.href;

         var url = new URL(url_string);

         var userNm = url.searchParams.get("username");

         var passwrd = url.searchParams.get("password");



         var decodedUsername = atob(userNm);

         var decodedPassword = atob(passwrd);



         // $("#username").val(decodedUsername);

         // $("#password").val(decodedPassword);

      })

      $(document).ready(function() {

         //    setSizes();



         // var userNm = '<?php echo $getDecuname; ?>'

         //             var userpass = '<?php echo $getDecPass; ?>'



         //             $("#username").val(userNm);

         //             $("#password").val(userpass);



         $('.LogForm').show();



         $(document).on('click', '#forget', function() {

            $('.forgetPasswordDet').removeClass('d-none');

            $('.LogForm').hide();

            $('.login-sign').hide();

            $('.alert-dismissible').hide();



         });





         $(document).on('click', '#save_f', function() {

            $('#save_f').prop('disabled', true);

            var forgetUser = $('#forgetUser').val();





            //      if(forgetUser != ""){

            var url = "<?php echo BASE_URL() . "login/forgetPassword" ?>";

            var data = {
               forgetUser: forgetUser,
               '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            };



            $.ajax({

               type: 'post',

               url: url,

               data: data,

               cache: false,

               success: function(data) {

                  if (data != 0) {

                     $("#count_timer").show();

                     $("#count_timer").html(data);

                     $('#save_f').prop('disabled', false);



                  }



               }



            });



            //      }



            //      else{

            //         $("#save_f").show();

            //        $('.alert-dismissible').hide();

            //        $(".alert_for_forget_password").show();

            //               $(".alert_for_forget_password").html('<div class="alert alert-danger"><button class="close"data-close="alert" data-dismiss="alert" aria-label="Close"></button><span>Please enter the valid Username or Email Id</span></div>');

            //         }





         });





         $(document).on('click', '#close_forget', function()

            {

               $('.forgetPasswordDet').addClass('d-none');

               $('#forgetUser').val('');

               $('.LogForm').show();

               $('.login-sign').show();

               location.reload();



            });

         $(document).on('click', '.close', function() {

            $(".alert_for_forget_password").addClass('d-none');

            $(".alert_for_forget_password").hide();

            $(".alert-dismissible").addClass('d-none');

            $('.alert-dismissible').hide();

            $("#count_timer").hide();

            $('.alert-danger').addClass('d-none');

            $('.alert-danger').hide();

            $('.alert-success').hide();

            $('.close').addClass('d-none');



         })







      });
   </script>
   	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>