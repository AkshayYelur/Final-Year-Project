<?php
require_once("controller/class.user.php");
$cold_user = new USER();

if($cold_user->is_loggedin()!="")
{
	$cold_user->redirect('home.php');
}
if(isset($_POST['btn-login']))
{
	 $uname = strip_tags($_POST['txt_uname_email']);
	 $umail = strip_tags($_POST['txt_uname_email']);
	 $upass = $_POST['pass'];
		
	if($salt=$cold_user->doLogin($uname,$umail,$upass))
	{
		$slt=$salt['salt'];
		$sluid=$salt['userid'];
		$urole=$salt['urole'];
		if($cold_user->updsalt($slt,$sluid)){
			$cold_user->redirect('home.php');	
	}
	}
	else
	{
		$error = "Wrong Details !";
	}	
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
</head>
  <body class="login-body" style=" background-color: #f4fbff;">
      <div class="login-logo">
          <img style="width:154px;" src="views/img/b.png" alt=""/>
      </div>
				<h2 Style=" background: #14827a;" class="form-heading">secret intelligence service portal</h2>
      <div class="container log-row">
          <form class="form-signin"  method="POST" Action="">
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
                  <input type="text" class="form-control" style="background:#ffffff;" id="txt_uname_email" name="txt_uname_email" placeholder="Contact No or E mail ID"/>
                  <input type="password" style="background:#ffffff;" class="form-control" value="" id="pass" name="pass" placeholder="Your Password" />
				  <input type="submit" style="background: #14827a; border: 1px solid #14827a;"  name="btn-login" value="LOGIN" class="btn btn-lg btn-success btn-block" >
              </div>
			  <br/> <br/>
			  <div class="registration">
                      Don't have an account yet?
                      <a style="color: #117bbf;" class="" href="registration.php">
                          Create an account
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
   
  </body>
</html>
