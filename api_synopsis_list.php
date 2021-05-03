<?php
if (isset($_GET['customer_id'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';

$AgentsManage = new AgentsManage;
$customer_id = $_GET['customer_id'];
$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents_desg WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));


	if ($row['count'] >= 1) {

		$array = array("status" => "200", "msg" => "Data Found...", "data" => $row['rows']);
		
		echo json_encode($array);
		exit();
	}	
	else if ($row['count'] == 0) {
		
			$array = array("status" => "404", "msg" => "No Data Found...", "data" => null);
			echo json_encode($array);
			exit();
	}
	else {
		$array = array("status" => "401", "msg" => "Authentication Failed...", "data" => null);
		echo json_encode($array);
		exit();
	}
}










?>