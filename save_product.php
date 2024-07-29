<?php
error_reporting(0);
include('lock.php');
$actual_image_name="";
$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
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
				$d_time=time();
				$token=md5($p_time); 
				if($cold_user->adddata($d_title, $d_desc, $imgData, $user_id, $d_time))
				{	
				echo "Success" ;
				}else
					echo "Fails upload ";				
				}
		else
		echo "Invalid file format..";	
	}
		else
		echo "Invalid Double extention file format..";
	}
	else
	echo "Please select image..!";
	exit;
}
?>