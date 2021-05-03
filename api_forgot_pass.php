<?php
if (isset($_GET['user_id'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';
include 'classes/Security.php';
include 'classes/Sender.php';

$AgentsManage = new AgentsManage;
$Security = new Security;

$user_id = $_GET['user_id'];


	//=================== Fetch Data =======================//
	$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE email = :email OR mobile = :mobile", array(":email" => $user_id, ":mobile" => $user_id));
	//===================== Check User Existance =====================//
	if ($row['count'] == 1) {
				
		date_default_timezone_set("Asia/Kolkata");
		$date = date('F');

		
		$otp = $Security->generateNumericOTP(4);


		$data = $Security->SendAndSaveOTP($row['rows'][0]['mobile'], $row['rows'][0]["email"], $row['rows'][0]["customer_id"], $otp);
		
		if ($data === 1) {
			$msg = '';
			$msg .= "Your One Time Password is ".$otp.". Please enter this OTP to change password. Softsetu.";
			$Sender = new Sender("9879456576","fa3ac2ec037c4f6a9fba3ca7ac2eef2a","SFSETU",$msg, $row['rows'][0]['mobile']);
			
			$return = json_decode($Sender->Submit());
			
			exit();
		}
		else {
			$array = array("status" => "401", "msg" => "There is some error...", "data" => null);
			echo json_encode($array);
			exit();
		}

	}
	else {
		$array = array("status" => "404", "msg" => "Mobile/Email Id Not Found...", "data" => null);
		echo json_encode($array);
		exit();
	}


}
else {
	$array = array("status" => "404", "msg" => "Parameter Mismatched...", "data" => null);
	echo json_encode($array);
	exit();
}


// Response
// 


?>