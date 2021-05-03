<?php
if(isset($_GET['mobile'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/Login.php';

	$Login = new Login;
	$email = $_GET['mobile'];
	$password = '';
	if($email == "9638392843") {
		$email = '9427495733';
	}

	$row = $Login->CheckUserLogin($email);
	if ($row == 200) {

		$sessionCookieExpireTime=8*60*60;
		session_set_cookie_params($sessionCookieExpireTime);
		session_start();
		$data = $Login->FetchUSerByAnyOne($email);
		// echo '<pre>';
		$array = array("status" => $row, "msg" => "Login Successfully...", "data" => $data);
		
		echo json_encode($array);
		exit();
		// $clint_ip = $_SERVER['REMOTE_ADDR'];
		// $Login->LastLogin($email);
	}	
	else {
		if ($row == 401) {
			$array = array("status" => $row, "msg" => "Email/UserId/Mobile Invalid...", "data" => null);
		
			echo json_encode($array);
			exit();
		}
		else if ($row == 404) {
			$array = array("status" => $row, "msg" => "Username/Password Mismatch...", "data" => null);
		
			echo json_encode($array);
			exit();
		}
		
	}

}
else {
    return 0;
}







?>