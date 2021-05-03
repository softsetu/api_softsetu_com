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
$chk_otp = $_GET['otp'];


	//=================== Fetch Data =======================//
	// $row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE mobile = :mobile", array(":mobile" => $mobile));
	//===================== Check User Existance =====================//
		
		$otp_row = $Security->Check($mobile);
		if ($otp_row['count'] == 1) {
			
			$db_otp = $otp_row['row']['otp'];
			if ($chk_otp == $db_otp) {

				$AgentsManage->DBOperation("UPDATE master_otp SET status = '0' WHERE mobile = :mobile", array(":mobile" => $mobile));
				$array = array("status" => "200", "msg" => "OTP Matched Successfully...", "data" => $mobile);
				echo json_encode($array);
				exit();
			}
			else {
				$array = array("status" => "401", "msg" => "OTP is invalid...", "data" => null);
				echo json_encode($array);
				exit();
			}
		}
		else {
			$array = array("status" => "401", "msg" => "OTP has expired...", "data" => null);
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
// {"status":"000","statusdesc":"success","messageid":"294506599"}


?>