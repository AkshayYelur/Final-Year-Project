<?php require_once("lock.php"); 

$actual_image_name="";
$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
if($_SERVER['REQUEST_METHOD']=="POST"){ 
include_once 'includes/getExtension.php';
	 $imagename = $_FILES['upimg']['name'];
	$size = $_FILES['upimg']['size'];
	$d_title= $_POST['g_title'];
	$d_desc	= $_POST['g_desc'];
	$p_flag=1;
	if(strlen($imagename))
	{
		$douext=explode(".",$imagename);
		$cnt=count($douext);
		if($cnt == 2){
		$ext = strtolower(getExtension($imagename));
		if(in_array($ext,$valid_formats))
		{
				$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
				$uploadedfile = $_FILES['upimg']['tmp_name'];
				$imgData = base64_encode(file_get_contents($uploadedfile));
				// Format the image SRC:  data:{mime};base64,{data};
                $src = 'data:;base64,'.$imgData;
				$p_time=time();
				$token=md5($p_time); 
				if($srhde=$cold_user->fetchdatade($imgData))
				{	
				  $detcount=count($srhde["d_id"]);
				  $srcpro = 'data:;base64,'.$srhde['d_img'];
				}else
					$a= "Fails upload ";				
				}
		else
		$a="Invalid file format..";	
	}
		else
		$a="Invalid Double extention file format..";
	}
	else
	$a="Please select image..!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina">
    <link rel="shortcut icon" href="javascript:;" type="image/png">
    <title>Inwards</title>
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
         <aside class="lg-side" style="height: 1200px">
                 <?php if($detcount==1){ ?>
                <div class="inbox-body">
                    <div class="heading-inbox row">
                        <div class="col-md-12">
                            <h4> Encryption RAW Confidential data</h4>
                        </div>
                    </div>
                    <div class="sender-info">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                
                                <div class="s-info">
                                    <strong><?php echo $srhde["user_name"] ?></strong>
                                    <span>[<?php echo $srhde["user_cont"] ?>]</span>
                                </div>

                            </div>
                            <div class="col-md-6 col-xs-12">
                                <p class="date pull-right"> <?php echo date("d-M-Y", $srhde["d_time"]); ?> </p>

                            </div>
                        </div>
                    </div>
                    <div class="view-mail">
                        <p>
                            <strong>
                              <?php echo $srhde["d_title"] ?>
                            </strong>
                        </p>
                        <p>  <?php echo $srhde["d_desc"] ?></p>
                    </div>
                    <div class="attachment-mail">
                        <h5> Attachments </h5>
                        <ul>
                            <li>
                                <a href="#" class="atch-thumb">
                                    <img src="<?php echo $srcpro; ?>">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div><?php }else { } ?>
            </aside> 
            <aside class="p-short-info">
                    <br>
                    <div class="title">
                        <h1>upload image for Decrypt </h1>
                    </div>
                   <form  action="" name="loginForm" Method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="g-url">Upload Image</label>
                             <div class="btn btn-default btn-file"><input id="upimg" name="upimg" value="" class="file" type="file" />
                        </div>
						</br>
						</br>
                        <button type="submit"  id="login" class="btn btn-info">Update Gallery</button>
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
</body>
</html>
