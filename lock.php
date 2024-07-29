<?php 
ini_set('post_max_size', '1024M'); //300 seconds = 5 minutes
date_default_timezone_set('Asia/Kolkata');
 require_once("controller/class.user.php");
	$cold_user = new USER();
	$csalt=$_COOKIE["c_salt"];
	$insu=0.06;
	$extra=10;
	$femugation=7;
	$tp=1;
	$userRow = $cold_user->userfetch($csalt);
	$urole =$userRow['urole'];
	$org_id=$userRow['org_id'];
	$user_id=$userRow['user_id'];
		switch ($urole){
    case "1":
        $cold_user->redirect('security/home.php');
        break;
    case "2":
       $cold_user->redirect('store/home.php');
        break;
}
	if(!$cold_user->is_loggedin())
	{
		$cold_user->redirect('index.php');
	}
	elseif($userRow <= 0){
		 $cold_user->redirect('logout.php?logout=true');
	}
?>