<?php

if(isset($_GET['customer_id'])) {
	
	include 'classes/Config.php';
	include 'classes/Database.php';

	$DB = new Database;
	// $email = $_GET['email'];
	// $password = $_GET['pass'];

	$customer_id = $_GET['customer_id'];
	$promotion_type_id = $_GET['pid'];

	$rows = $DB->FetchUniversal("SELECT * FROM master_agents_promotions WHERE customer_id = :customer_id AND master_promotion_type_id = :promotion_type_id", array("customer_id" => $customer_id, "promotion_type_id" => $promotion_type_id));
	// print_r($rows);
	if($rows['rows']){
		
		$array = array("status" => "200", "msg" => "Data Found...", "data" => $rows['rows']);
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