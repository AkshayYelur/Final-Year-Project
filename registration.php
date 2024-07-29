<?php
require_once("controller/class.user.php");
$cold_user = new USER();

if($cold_user->is_loggedin()!="")
{
	$cold_user->redirect('home.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="Admin Template">
    <meta name="keywords" content="admin dashboard, admin, flat, flat ui, ui kit, app, web app, responsive">
    <link rel="shortcut icon" href="views/img/ico/favicon.png">
    <title>Login</title>
    <!-- Base Styles -->
    <link href="views/css/style.css" rel="stylesheet">
    <link href="views/css/style-responsive.css" rel="stylesheet">
	<style>
	.form-signin input[type="text"], .form-signin input[type="password"] {
    background: #ffffff;
   
}
	</style>
</head>
  <body class="login-body" style=" background-color: #f4fbff;">
      <div class="login-logo">
          <img style="width:154px;" src="views/img/b.png" alt=""/>
      </div>
				<h2 Style=" background: #14827a;" class="form-heading">secret intelligence service portal</h2>
      <div class="container log-row">
          <form class="form-signin" id="cust-register" action='javascript:;' name="cust-register" onsubmit="javascript:;" Method="POST">
		   <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger" style="background-color: red; color: white;">
                    &nbsp; <?php echo $error; ?> !
                </div>
                <?php
			}
		?>
              <div class="login-wrap">
                    <input type="text" class="form-control" id="uname" name="uname" placeholder="First Name" value="" />
					<input type="text" class="form-control" id="uemail" name="uemail" placeholder="Email" value="" />
					<input type="password" class="form-control" id="upass" name="upass" placeholder="Enter password" value="" />
					 <input type="text" class="form-control" maxlength="15" onKeyPress="return isNumberKey(event);" id="cont" name="cont" placeholder="Mobile" value="" />
				  <input type="submit" style="background: #14827a; border: 1px solid #14827a;"  name="btn-login" value="CREATE" class="btn btn-lg btn-success btn-block" >
              </div>
			  <br/> <br/>
			  <div class="registration">
                      Don't have an account yet?
                      <a style="color: #117bbf;" class="" href="index.php">
                          Login your account
                      </a>
                  </div>
              <!-- Modal -->
              <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgotPass" class="modal fade">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title">Forgot Password ?</h4>
                          </div>
                          <div class="modal-body">
                              <p>Enter your e-mail address below to reset your password.</p>
                              <input type="text"name="txt_password" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                          </div>
                          <div class="modal-footer">
                              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                              <button class="btn btn-success" type="button">Submit</button>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- modal -->
          </form>
		  
      </div>
	    <script src="views/js/jquery-1.10.2.min.js"></script>
        <script src="views/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
        <script src="views/js/jquery-migrate.js"></script>
        <script src="views/js/bootstrap.min.js"></script>
        <script src="views/js/modernizr.min.js"></script>
   <script>
            function isCharKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode != 8 && (charCode > 122 || charCode < 97) && (charCode < 65 || charCode > 90)) {
                    return false;
                }
                return true;
            }
        </script>
        <script>
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
        </script>
       
        <script type="text/javascript" src="views/js/jquery.validate.min.js"></script>
        <!-- end validation js -->
        <!-- contact validation -->
        <script type="text/javascript">
            //form validation rules
            $(document).ready(function() {
                $("#cust-register").validate({
                    rules: {
                        uname: "required",
                        cont: {
                            required: true,
                            minlength: 10,
                            number: true,
                            remote: {
                                url: 'validcont.php',
                                type: "POST"
                            }
                        },
                        uemail: {
                            required: true,
                            email: true
                        },
                        upass:"required",
                        urole: "required"
                    },
                    messages: {
                        uname: "required",
                        cont: {
                            required: "Please enter Mobile Number.",
                            minlength: "Mobile number should be 10 digits",
							 number: "please enter Valid number",
                            remote: "Contact already registered."
                        },
                        uemail: {
                            required: "Please enter Email",
                            email: "Enter valid Email"
                        },
						upass:"required",
                        urole: "required"
                    },
                    submitHandler: function(form) {
						//alert("test");
                       savemf();
                    }
                });
            });
        </script>
        <script type="text/javascript">
            function savemf() {
				//alert("aa");
                var form = $("#cust-register");
                $.ajax({
                    type: 'POST',
                    url: 'add_users.php',
                    data: form.serialize(),
                    success: function(data) {
						//alert(data);
                        if (data == "Success") {
                            $('#cust-register')[0].reset();
                            $('#sucess').text("User saved successfully");
                            $('.toast-top-right').show();
                            $('.toast-top-right').focus();
                            $('.toast-top-right').fadeOut(5000);
                            location.replace('index.php');
                        } else {
                            $('#error').text("Please Fill All Mandatory Fields");
                            $('.toast-top-center').show();
                            $('.toast-top-center').focus();
                            $('.toast-top-center').fadeOut(5000);
                            $('#cust-register')[0].reset();
                        }
                    }
                });
            }
        </script>
  </body>
</html>
