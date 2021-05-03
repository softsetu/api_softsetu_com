<?php

if(isset($_GET['customer_id'])) {
	
	include 'classes/Config.php';
	include 'classes/Database.php';

	$DB = new Database;
	
	$customer_id = $_GET['customer_id'];

	$rows = $DB->fetchAllRecordForAdmin("master_announcement");

	if($rows){
		
		$array = array("status" => "200", "msg" => "Data Found...", "data" => $rows);
		echo json_encode($array);
		exit();
	}	
	else {
		
		$array = array("status" => "401", "msg" => "Data Not Found...", "data" => null);
		echo json_encode($array);
		exit();
	}
}
else {
	return 0;
}
	

?>