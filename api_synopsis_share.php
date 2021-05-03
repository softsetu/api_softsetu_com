<?php
if (isset($_GET['customer_id'])) {
include 'classes/Config.php';
include 'classes/Database.php';
include 'classes/AgentsManage.php';

$AgentsManage = new AgentsManage;
$customer_id = $_GET['customer_id'];
$login_type = $_GET['login_type'];
//================ Get Status =================//
$row = $AgentsManage->GetStatus($customer_id, $login_type);
if ($row['count'] == 1) {
	$status = 200;
	if ($login_type == "staff") {
		//================== Fetch Staff ================//
		$staff = $AgentsManage->FetchUniversal("SELECT * FROM master_agents_desg WHERE desg_customer_id = :customer_id", array(":customer_id" => $customer_id));
		//============= Admin Fetch =============//
		$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE customer_id = :customer_id", array(":customer_id" => $staff['rows'][0]['customer_id']));

		//============= Fetch Link
		$link = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));

		$link = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));
		$img = 'https://agt.softsetu.com/images/staff/'.$staff['rows'][0]['img'];
		$share = 'https://synopsis.softsetu.com/Website/index.php?synopsis_id='.$customer_id;


		//================ Facebook ===============//	
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 1", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$facebook = $link_count['rows'][0];
		}
		else {
			$facebook = array('site' => 1);
		}

		//================ Twitter ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 2", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$twitter = $link_count['rows'][0];
		}
		else {
			$twitter = array('site' => 2);
		}

		//================ Instagram ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 4", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$instagram = $link_count['rows'][0];
		}
		else {
			$instagram = array('site' => 4);
		}

		//================ Telegram ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 5", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$telegram = $link_count['rows'][0];
		}
		else {
			$telegram = array('site' => 5);	
		}


		//================ Linkedin ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 6", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$linkedin = $link_count['rows'][0];
		}
		else {
			$linkedin = array('site' => 6);
		}
		
		//================ Whatsapp ===============//
		
		if($staff['rows'][0]['desg_mobile'] != "") {
			$whatsapp = array("site" => 3, "link" => $staff['rows'][0]['desg_mobile']);
		}
		else {
			$whatsapp = array('site' => 3);
		}

		$DB = new Database;
		
		$ref_id = $DB->KeyEncrypt($_GET['customer_id']);
		
		$registration = 'Click to Register for Softsetu - A uNique Travel Bridge, Reference From '.$row['rows'][0]['fname'].' '.$row['rows'][0]['lname'].', kampus tour  https://softsetu.com/Register.php?ref_id='.$ref_id.'';

		$data =
			array(
				"name" 			=> 	$staff['rows'][0]['name'],
				"desg"			=> 	$staff['rows'][0]['desg'],
				"image"			=>	$img,
				"whatsapp"		=>	$whatsapp,
				"email"			=>	$staff['rows'][0]['desg_email'],
				"mobile"		=>	$staff['rows'][0]['desg_mobile'],
				"link"			=> 	$share,
				"facebook"		=> 	$facebook,
				"instagram"		=> 	$instagram,
				"linkedin"		=> 	$linkedin,
				"telegram"		=> 	$telegram,
				"twitter"		=> 	$twitter,
				"registration"  =>  $registration
			);

		$array = array("status" => "200", "msg" => "Data Found...", "data" => $data);
		echo json_encode($array);
		exit();
	}
	else {
		//===================== Admin Login ==================//
		$row = $AgentsManage->FetchUniversal("SELECT * FROM master_agents WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));
		$link = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE customer_id = :customer_id", array(":customer_id" => $customer_id));
		$img = 'https://agt.softsetu.com/images/staff/'.$row['rows'][0]['owner_img'];
		$share = 'https://synopsis.softsetu.com/Website/index.php?customer_id='.$customer_id;
		

		//================ Facebook ===============//	
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 1", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$facebook = $link_count['rows'][0];
		}
		else {
			$facebook = array('site' => 1);
		}

		//================ Twitter ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 2", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$twitter = $link_count['rows'][0];
		}
		else {
			$twitter = array('site' => 2);
		}

		//================ Instagram ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 4", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$instagram = $link_count['rows'][0];
		}
		else {
			$instagram = array('site' => 4);
		}

		//================ Telegram ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 5", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$telegram = $link_count['rows'][0];
		}
		else {
			$telegram = array('site' => 5);
		}


		//================ Linkedin ===============//
		$link_count = $AgentsManage->FetchUniversal("SELECT master_links.id, master_links.site, master_links.link FROM master_links INNER JOIN master_social_site ON master_links.site = master_social_site.id WHERE master_links.customer_id = :customer_id AND master_links.site = 6", array(":customer_id" => $customer_id));

		if($link_count['count'] == 1 &&  $link_count['rows'][0]['site'] != "") {
			$linkedin = $link_count['rows'][0];
		}
		else {
			$linkedin = array('site' => 6);
		}

		//================ Whatsapp ===============//
		
		if($row['rows'][0]['wp_num'] != "") {
			$whatsapp = array("site" => 3, "link" => $row['rows'][0]['wp_num']);
		}
		else {
			$whatsapp = array('site' => 3);
		}
		
		
		$DB = new Database;
		
		$ref_id = $DB->KeyEncrypt($_GET['customer_id']);
		
		$registration = 'Click to Register for Softsetu - A uNique Travel Bridge, Reference From '.$row['rows'][0]['fname'].' '.$row['rows'][0]['lname'].', kampus tour  https://softsetu.com/Register.php?ref_id='.$ref_id.'';
		
		$data =
			array(
				"name" 			=> 	$row['rows'][0]['fname'].' '.$row['rows'][0]['lname'],
				"desg"			=> 	$row['rows'][0]['owner_desg'],
				"image"			=>	$img,
				"whatsapp"		=>	$whatsapp,
				"email"			=>	$row['rows'][0]['email'],
				"mobile"		=>	$row['rows'][0]['mobile'],
				"link"			=> 	$share,
				"facebook"		=> 	$facebook,
				"instagram"		=> 	$instagram,
				"linkedin"		=> 	$linkedin,
				"telegram"		=> 	$telegram,
				"twitter"		=> 	$twitter,
				"registration"  =>  $registration
			);
	}

	$array = array("status" => $status, "msg" => "Data Found...", "data" => $data);
	echo json_encode($array);
	exit();

}
else if($row['count'] == 0) {
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