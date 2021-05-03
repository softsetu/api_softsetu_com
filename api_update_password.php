<?php
if (isset($_GET['mobile'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';
include 'classes/Security.php';
include 'classes/Sender.php';

$AgentsManage = new AgentsManage;
$Security = new Security;

$mobile = $_GET['mobile'];


	//=================== Fetch Data =======================//
	$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE mobile = :mobile", array(":mobile" => $mobile));
	//===================== Check User Existance =====================//
	if ($row['count'] == 1) {
		
		$password = $_GET['password'];
		$password = 'setu'.$password;
		$password = md5($password);
		$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=> "16"]);


		$result = $AgentsManage->DBOperation("UPDATE master_agents SET password = :password WHERE mobile = :mobile", array(":password" => $password, ":mobile" => $mobile));
		if ($result == 1) {
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
}
else {
	$array = array("status" => "404", "msg" => "Parameter Mismatched...", "data" => null);
	echo json_encode($array);
	exit();
}


// Response
// {"status":"000","statusdesc":"success","messageid":"294506599"}


?>