<?php 
require_once("controller/class.user.php");
$cold_user = new USER();
	if($_SERVER['REQUEST_METHOD'] == "POST")
		{
	$uname=$_POST['uname'];
	$umail=$_POST['uemail'];
	$ucont=$_POST['cont'];
	$upass=$_POST['upass'];
	$urole=0;
		$uflag=1;
		$cnt=$cold_user->validcont($ucont);
		if($cnt >= 1){
			echo"error";
		}else{
			$sqlh=$cold_user->register($uname,$umail,$ucont,$upass,$urole,$uflag);
			if($sqlh== true){
				echo"Success";
			}else{
				echo"error";
			}
		}
		}
?>