<?php
if (isset($_GET['customer_id']) && isset($_GET['old_password']) && isset($_GET['new_password'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';
include 'classes/Login.php';

$AgentsManage = new AgentsManage;
$Login = new Login;

$customer_id = $_GET['customer_id'];

//============== Old Password ===========//
$old_password = $_GET['old_password'];
$old_password = 'setu'.$old_password;
$old_password = md5($old_password);


//============== New Password ===========//
$new_password = $_GET['new_password'];







//=================== Fetch Data =======================//
$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));
$row['rows'][0]['password'];
//===================== Check User Existance =====================//
if ($row['count'] == 1) {
	
	//=============== Check Old Password ================//
	$chk_old_password = $Login->Chk($old_password, $row['rows'][0]['password']);
	
	if ($chk_old_password == 1) {
		$ChangePasswordResult = $Login->ChangePasswordViaApp($customer_id, $new_password);
		if ($ChangePasswordResult == 1) {
			$array = array("status" => "200", "msg" => "Password Changed Successfully...", "data" => null);
			echo json_encode($array);
			exit();
		}
		else {
			$array = array("status" => "401", "msg" => "There is some error...", "data" => null);
			echo json_encode($array);
			exit();
		}
	}	
	else if($chk_old_password == 0) {
		$array = array("status" => "401", "msg" => "Old Password is Incorrect...", "data" => null);
		echo json_encode($array);
		exit();
	}
	else {
		$array = array("status" => "401", "msg" => "There is some error...", "data" => null);
		echo json_encode($array);
		exit();
	}
}
else {
	$array = array("status" => "404", "msg" => "Authentication Failed...", "data" => null);
	echo json_encode($array);
	exit();
}




}
else {
	$array = array("status" => "404", "msg" => "Parameter Mismatched...", "data" => null);
	echo json_encode($array);
	exit();
}






?>