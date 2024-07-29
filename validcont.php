<?php 
require_once("controller/class.user.php");
$cold_user = new USER();
$cont = $_POST['cont'];
$count=$cold_user->validcont($cont);
if($count >= 1)
{
	echo "false";
}
else
{
	echo "true";
}       
?>
