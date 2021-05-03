<?php 
error_reporting(0);
if (isset($_GET['email']) && isset($_GET['customer_id']) && isset($_GET['social']) && isset($_GET['link']) && isset($_GET['primary_id'])) {

include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';
// include 'classes/Login.php';

$AgentsManage = new AgentsManage;
$DB = new Database;
// 1 Facebook
// 2 Twitter
// 4 Instagram
// 5 Telegram
// 6 LinkedIn
$email = $_GET['email'];
$customer_id = $_GET['customer_id'];

// if ($_GET['fb'] == 'facebook.com') {
	
	$social = $_GET['social'];
	$link = $_GET['link'];
	$id = $_GET['primary_id'];
	if($social == 1 || $social == 2 || $social == 3 || $social == 4 || $social == 5 || $social == 6) {
		
		$dataResult = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE email = :email AND customer_id = :customer_id", array(":email" => $email, ":customer_id" => $customer_id));
		if ($dataResult['count'] == 1) {
			$MyCount = 1;
			$type = 'agent';
		}
		else {
			$HdataResult = $AgentsManage->FetchUniversal("SELECT * FROM master_hotel WHERE email = :email AND customer_id = :customer_id", array(":email" => $email, ":customer_id" => $customer_id));
			if ($HdataResult['count'] == 1) {
				$MyCount = 1;
				$type = 'hotel';
			}
			else {
				$MyCount = 0;
			}
		}

		if ($MyCount == 1) {
			
				if ($social == 3) {
					if ($type == "agent") {
						$result = $AgentsManage->DBOperation("UPDATE master_agents SET wp_num = :num WHERE customer_id = :customer_id", array(":num" => $link, ":customer_id" => $customer_id));
						if ($result == 1) {
							echo json_encode(array("status" => "200", "msg" => "Success", "data" => null));
						}	
						else {
							echo json_encode(array("status" => "401", "msg" => "There is some error...", "data" => null));
						}
					}
					else {
						$result = $DB->DBOperationHotel("UPDATE master_hotel SET pri_nm = :num WHERE customer_id = :customer_id", array(":num" => $link, ":customer_id" => $customer_id));
						if ($result == 1) {
							echo json_encode(array("status" => "200", "msg" => "Success", "data" => null));
						}	
						else {
							echo json_encode(array("status" => "401", "msg" => "There is some error...", "data" => null));
						}
					}
					
				}
				else {
					$result = $AgentsManage->AddSocialMediaLinks($email, $customer_id, $social, $link);
					if ($result == 0) {
						$data = $AgentsManage->UpdateSocialMediaLinks($id, $social, $link, $customer_id);	
						if ($data == 1) {
							echo json_encode(array("status" => "200", "msg" => "Success", "data" => null));
						}
						else {
							echo json_encode(array("status" => "401", "msg" => "There is some error...", "data" => null));
						}
					}
					else if($result == 1) {
						echo json_encode(array("status" => "200", "msg" => "Success", "data" => null));
					}
					else  if($result == 2){
						echo json_encode(array("status" => "404", "msg" => "Data Not Found...", "data" => null));
					}
					else {
						echo json_encode(array("status" => "401", "msg" => "Ther is some error...", "data" => null));
					}
				}

			}
			else {
				echo json_encode(array("status" => "404", "msg" => "Error 404. Page Not Found...", "data" => null));
			}


		}
		else {
			echo json_encode(array("status" => "404", "msg" => "Error 404. Page Not Found...", "data" => null));
		}
	} 
	else {
		echo json_encode(array("status" => "404", "msg" => "Error 404. Page Not Found...", "data" => null));
	}
	


//https://api.softsetu.com/api_SocialMedia_Update.php?email=YOUR_EMAIL_ID&customer_id=YOUR_CUSTOMER_ID&social=GIVEN_SOCIAL_ID&link=YOUR_TO_UPDATE&primary_id=PRIMARY_ID_OF_YOUR_DATA_ROW
?>