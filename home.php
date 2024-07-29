<?php require_once("lock.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina">
    <link rel="shortcut icon" href="javascript:;" type="image/png">
    <title>SISP</title>
    <!--right slidebar-->
    <link href="views/css/slidebars.css" rel="stylesheet">
    <!--switchery-->
    <link href="views/js/switchery/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <!--Data Table-->
    <link href="views/js/data-table/css/jquery.dataTables.css" rel="stylesheet">
    <link href="views/js/data-table/css/dataTables.tableTools.css" rel="stylesheet">
    <link href="views/js/data-table/css/dataTables.colVis.min.css" rel="stylesheet">
    <link href="views/js/data-table/css/dataTables.responsive.css" rel="stylesheet">
    <link href="views/js/data-table/css/dataTables.scroller.css" rel="stylesheet">
    <!-- Base Styles -->
<link rel="stylesheet" type="text/css" href="views/js/bootstrap-datepicker/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="views/js/bootstrap-timepicker/compiled/timepicker.css" />
        <link rel="stylesheet" type="text/css" href="views/js/bootstrap-colorpicker/css/colorpicker.css" />
        <link rel="stylesheet" type="text/css" href="views/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
        <link rel="stylesheet" type="text/css" href="views/js/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!--common style-->
    <link href="views/css/style.css" rel="stylesheet">
    <link href="views/css/style-responsive.css" rel="stylesheet">
 <link href="views/css/print-invoice.css" rel="stylesheet" media="print">
 <link href="views/js/toastr-master/toastr.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
		<script type="text/javascript" >
function addfilter()
{
var sdate=$("#sdate").val();
var edate=$("#edate").val();
//alert(sdate+"xx"+edate);
	  $.ajax({
           type: 'POST',
            url: 'datewise_inwards.php',
           data: {sdate:sdate, edate:edate},
            success: function(result) {
				//alert(result);
               $('#view9').html(result);
           }
       }); 
}
</script>
</head>
<body class="sticky-header">
    <section>
            <!-- header section end-->
<?php require_once("header.php"); ?>

       <section id="main-content">
          <div class="wrapper">
		  <div class="wrapper no-pad">

            <div class="profile-desk">
            <aside class="p-aside">


                <ul class="gallery">
				
				
													 <?php
$sqlct=$cold_user->fetchdataset($user_id);
foreach($sqlct as $catrows){
		 $c_id=$catrows['d_id'];
		 $srcpro = 'data:;base64,'.$catrows['d_img'];
?>
                    <li>
                        <a href="#">
                            <img src="<?php echo $srcpro; ?>" alt="">
                        </a>
                    </li>
<?php } ?>
                </ul>

            </aside>
            <aside class="p-short-info">
                    <br>
                    <div class="title">
                        <h1>Attachment Details With image</h1>
                    </div>
                   <form id="loginForm" action='javascript:;' name="loginForm" onsubmit="adprod()" Method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="g-title">Message Heading</label>
                            <input type="text" class="form-control" id="g_title" name="g_title" placeholder=" ">
                        </div>
                        <div class="form-group">
                        <div class="form-group">
                            <label for="g-desk">Secrit Message Description</label>
                            <div class="">
                                <textarea  class="form-control" id="g_desc" name="g_desc" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="g-url">Upload Cover Image</label>
                             <div class="btn btn-default btn-file"><input id="upimg" name="upimg" value="" class="file" type="file" />
                        </div>
						</br>
						</br>
                        <button type="submit"  id="login" class="btn btn-info">Encrypt Image</button>
                    </form>
                </div>

            </aside>
            </div>

            </div>
            
            </div>
      </section>
		  <div id="toast-container" style="display:none; " class="toast-top-right" aria-live="polite" role="alert"><div class="toast toast-success"><div class="toast-progress" style="width: 99.9218%;"></div><button type="button" class="toast-close-button" role="button">×</button><div class="toast-title">Toastr Notification</div><div id="sucess" class="toast-message"> </div></div></div>
	  <div  id="toast-container"style="display:none; " class="toast-top-center" aria-live="polite" role="alert"><div class="toast toast-error"><button type="button" class="toast-close-button" role="button">×</button><div class="toast-title">Error Notification</div><div id="error" class="toast-message"></div></div></div>
    </section>
<!-- Placed js at the end of the document so the pages load faster -->
 <script src="views/js/jquery-1.10.2.min.js"></script>
        <script src="views/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
        <script src="views/js/jquery-migrate.js"></script>
        <script src="views/js/bootstrap.min.js"></script>
        <script src="views/js/modernizr.min.js"></script>
        <!--Nice Scroll-->
        <script src="views/js/jquery.nicescroll.js" type="text/javascript"></script>
        <!--right slidebar-->
        <script src="views/js/slidebars.min.js"></script>
        <!--switchery-->
        <script src="views/js/switchery/switchery.min.js"></script>
        <script src="views/js/switchery/switchery-init.js"></script>
        <!--Sparkline Chart-->
        <script src="views/js/sparkline/jquery.sparkline.js"></script>
        <script src="views/js/sparkline/sparkline-init.js"></script>
        <!--bootstrap picker-->
        <!--picker initialization-->
        <script src="views/js/picker-init.js"></script>


<!--Data Table-->
<script src="views/js/data-table/js/jquery.dataTables.min.js"></script>
<script src="views/js/data-table/js/dataTables.tableTools.min.js"></script>
<script src="views/js/data-table/js/bootstrap-dataTable.js"></script>
<script src="views/js/data-table/js/dataTables.colVis.min.js"></script>
<script src="views/js/data-table/js/dataTables.responsive.min.js"></script>
<script src="views/js/data-table/js/dataTables.scroller.min.js"></script>
<!--data table init-->
<script src="views/js/data-table-init.js"></script>
<script type="text/javascript" src="views/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="views/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript" src="views/js/bootstrap-daterangepicker/moment.min.js"></script>
        <script type="text/javascript" src="views/js/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="views/js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="views/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<!--common scripts for all pages-->
<script src="views/js/scripts.js"></script>
<script>
function dltdata(dt)
{
	 if (confirm('Are you sure you want to delete this?')) {
$.ajax({
		 type	: "POST",
		 url	: "dltall.php",
		 data: {dt:dt},
		 success: function(result)
                 {
					 //alert(result);
                  location.replace("home.php");
				}	
      });
	 }
}
</script>
<script type="text/javascript" >
  function adprod(){
	 var g_title=$("#g_title").val();
	 var g_desc=$("#g_desc").val();
	if(g_title!="" && g_desc!=""){
		//alert(head+"xxxx"+desc);
	var formData = new FormData($('#loginForm')[0]);
    $.ajax({
        url: 'save_product.php',
        type: 'POST',
        data: formData,
        async: false,
        success: function(data) {
		if(data == "Success")
		{ 
		//alert(data);
		  $('#loginForm')[0].reset();
			$('#sucess').text(" data Added successfully");
			$('.toast-top-right').show();
			$('.toast-top-right').focus();
			$('.toast-top-right').fadeOut(5000);
		}
		else
		{ 
		//alert(data);
		 $('#error').html(data);
			$('.toast-top-center').show();
			$('.toast-top-center').focus();
			$('.toast-top-center').fadeOut(5000);
			$('#loginForm')[0].reset();
		}
        },
        cache: false,
        contentType: false,
        processData: false
    });
	}else{
			$('#error').text("Please Fill All Mandatory Fields");
			$('.toast-top-center').show();
			$('.toast-top-center').focus();
			$('.toast-top-center').fadeOut(5000);
			$('#fform')[0].reset();
	}
}
   </script>
</body>
</html>
