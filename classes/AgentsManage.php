<?php

/**
 * 
 */
class AgentsManage extends Database {

	private $_Mobile,
			$_Email,
			$_agent_id;


	public function CruiseDelete($id) {
		try {
			$query = $this->_agentPDO->prepare("DELETE FROM master_selected_cruise WHERE id = '$id'");
			$query->execute() or die($this->_agentPDO->error);

			return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}
		
	}	
public function GetStatus($customer_id, $type) {
	if ($type == "staff") {
		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg WHERE desg_customer_id = '".$customer_id."'");
		$query->execute() or die($this->_agentPDO->error);
		$num = $query->rowCount();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		return array("count" => $num, "row" => $row);
	}
	else if ($type == "admin"){
		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id = '".$customer_id."'");
		$query->execute() or die($this->_agentPDO->error);
		$num = $query->rowCount();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		return array("count" => $num, "row" => $row);
	}
}


public function AgentsStatusActive($email, $customer_id)
{
	$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE email = '".$email."' AND customer_id = '".$customer_id."' ");
	$query->execute() or die($this->_agentPDO->error);
	$num = $query->rowCount();
	$row = $query->fetch(PDO::FETCH_ASSOC);

	if($row['status'] == '2')
	{
		$query=$this->_agentPDO->prepare("UPDATE master_agents SET `status` = '1' WHERE customer_id = '".$row['customer_id']."' ");
		$query_result = $query->execute() or die();
	}
	// return 1;
}
	public function SelectGroupBy($email, $groupBy) {
		$query = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE email = '$email' GROUP BY ".$groupBy);
		$query->execute() or die($this->_agentPDO->error);
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}

	public function DeleteLinksDataForEdit($id) {
		try {
			$query = $this->_agentPDO->prepare("DELETE FROM `master_links` WHERE id = :id");
			$query->bindParam(":id", $id);
			$query->execute() or die($this->_agentPDO->error);
			return 1;

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function UpdateSocialMediaLinks($id, $social, $link, $customer_id) {

		$query = $this->_agentPDO->prepare("SELECT * FROM master_links WHERE site = '$social' AND customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$count = $query->rowCount();

		if ($count <= 1) {
			try {
			
				$query = $this->_agentPDO->prepare("UPDATE `master_links` SET `site` = :site, `link` = :link WHERE id = :id");
				$query->bindParam(":site", $social);
				$query->bindParam(":link", $link);
				$query->bindParam(":id", $id);
				$query->execute() or die($this->_agentPDO->error);

				return 1;

			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
		return 0;
		

	}

	public function AddSocialMediaLinks($email, $customer_id, $social, $link)	{
		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$rowCount = $query->rowCount();

		if ($rowCount == 1) {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_links WHERE site = '$social' AND customer_id = '$customer_id'");
			$query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();
			if ($count < 1) {

				try {
					$query = $this->_agentPDO->prepare("INSERT INTO `master_links`(`email`, `customer_id`, `site`, `link`) VALUES (:email, :customer_id, :site, :link)");
					$query->bindParam(":email", $email);
					$query->bindParam(":customer_id", $customer_id);
					$query->bindParam(":site", $social);
					$query->bindParam(":link", $link);
					$query->execute() or die($this->_agentPDO->error);
					return 1;

				} catch (Exception $e) {
					return $e->getMessage();
				}
			}
			return 0;
		}
		else {
			return 2;
		}
	}

	public function CheckUniqueUserIdForOtherTeam($email, $userid) {
		
		try {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg WHERE email = '$email' AND desg_userid = '$userid'");
			$query->execute() or die($this->_agentPDO->error);
			$num = $query->rowCount();
			return $num;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function SearchAgents($customer_id, $search_by, $keywords) {
		
		try {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id  = '$customer_id'");
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();
			$row = $query->fetch();

			if($row['agents_type_id'] == 2 )
			{
				$query = $this->_agentPDO->prepare("SELECT * FROM `master_agents` as A 
					INNER JOIN master_agents AS B ON A.ref_id = B.customer_id WHERE B.customer_id = '".$customer_id."' AND A.agents_type_id ='1'  AND (A.".$search_by." LIKE '%$keywords%')");
				$query->execute() or die($this->_agentPDO->error);
				$count = $query->rowCount();

				if ($count > 0) {
					$rows = $query->fetchAll(PDO::FETCH_ASSOC);
					return $rows;
				}
				else {
					return 0;
				}
			}
			else{
				$query = $this->_agentPDO->prepare("SELECT * FROM `master_agents` as A 
					INNER JOIN master_agents AS B ON A.ref_id = B.customer_id WHERE A.agents_type_id ='1'  AND (A.".$search_by." LIKE '%$keywords%')");
				$query->execute() or die($this->_agentPDO->error);
				$count = $query->rowCount();

				if ($count > 0) {
					$rows = $query->fetchAll(PDO::FETCH_ASSOC);
					return $rows;
				}
				else {
					return 0;
				}
			}


		} catch (Exception $e) {
			return $e->getMessage();
		}

	}


	public function SearchHoteliers($customer_id, $search_by, $keywords) {
		
		try {
			$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE ref_id = '$customer_id' AND hotel_type_id = '11' AND (".$search_by." LIKE '%$keywords%')");
			$query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();

			if ($count > 0) {
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				return $rows;
			}
			else {
				return 0;
			}

		} catch (Exception $e) {
			return $e->getMessage();
		}

	}


	public function SearchSubFranchise($session_mobile, $search_by, $keywords) {
		
		try {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE ref_id = '$session_mobile' AND agents_type_id = '2' AND (".$search_by." LIKE '%$keywords%')");
			$query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();

			if ($count > 0) {
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				return $rows;
			}
			else {
				return 0;
			}

		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	
	public function UpdateBizzService($customer_id, array $data){
		$array = array();
		for ($i=0; $i < count($data); $i++) { 
			$query = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE customer_id = '$customer_id' AND master_biz_type_id = '$data[$i]'");
			$query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();
			if ($count == 0) {
				$Insert = $this->_agentPDO->prepare("INSERT INTO `agents_biz_type`(`customer_id`, `master_biz_type_id`) VALUES (:customer_id, :service_id)");
				$Insert->bindParam(":customer_id", $customer_id);
				$Insert->bindParam(":service_id", $data[$i]);
				$Insert->execute() or die($this->_agentPDO->error);
			}
			
			$array[] = $data[$i];
			
		}
		
		
		$qu = '';
		foreach ($array as $k) {
		  $qu .=  "master_biz_type_id != "."'".$k."' AND ";
		}
		$m = " WHERE customer_id= '".$customer_id."' AND (";
		$d = substr($qu, 0, -5);
		$del = "DELETE FROM agents_biz_type".$m.$d.')';
		$delete = $this->_agentPDO->prepare($del);
		$delete->execute() or die($this->_agentPDO->error);
		// print_r($delete);
	}

	public function insertBizzService($customer_id, $bizzService) {

			try {
				
				$query = $this->_agentPDO->prepare("INSERT INTO `agents_biz_type`(`customer_id`, `master_biz_type_id`) VALUES (:customer_id, :service)");
				
				$query->bindParam(":customer_id", $customer_id);
				$query->bindParam(":service", $bizzService);
				$query_result = $query->execute() or die($this->_agentPDO->error);

				return 1;

			} catch (Exception $e) {
				return $e->getMessage();
			}

		}

	public function UpdatePremiumService($customer_id,array $data,array $q_id)
	{
		$array = array();
		for ($i=0; $i < count($data); $i++) { 
			$query = $this->_agentPDO->prepare("SELECT * FROM  master_premiumservice_que_ans WHERE customer_id = '$customer_id' AND master_que_id = '$q_id[$i]'");
			$query->execute() or die($this->_agentPDO->error);
			$count = $query->rowCount();
			$array[] =$q_id[$i];
		}
		$qu = '';
		foreach ($array as $k) {
		  $qu .=  "master_que_id != "."'".$k."' AND ";

		}
		$m = " WHERE customer_id= '".$customer_id."' AND (";
		$d = substr($qu, 0, -5);
		$del = "DELETE FROM master_premiumservice_que_ans".$m.$d.')';
			$delete = $this->_agentPDO->prepare($del);
		$delete->execute() or die($this->_agentPDO->error);


		// for ($i=0; $i < count($data); $i++) { 
		// 	$query = $this->_agentPDO->prepare("SELECT * FROM  master_vehicle WHERE customer_id = '$customer_id' AND master_biz_type_id = '$q_id[$i]'");
		// 	$query->execute() or die($this->_agentPDO->error);
		// 	$count = $query->rowCount();
		// 	$array[] =$biz_id[$i];
		// }
		// $qu = '';
		// foreach ($array as $k) {
		//   $qu .=  "master_biz_type_id != "."'".$k."' AND ";
		// }
		// $m = " WHERE customer_id= '".$customer_id."' AND (";
		// $d = substr($qu, 0, -5);
		// $del = "DELETE FROM master_vehicle".$m.$d.')';
		// 	$delete = $this->_agentPDO->prepare($del);
		// $delete->execute() or die($this->_agentPDO->error);

		
		
	}

	public function BizService($customer_id) {
		error_reporting(0);
		$query = $this->_agentPDO->prepare("SELECT * FROM master_service");
		$query_result = $query->execute();
		$count = $query->rowCount();
		$box = '';
		for ($i=1; $i <= $count; $i++) {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_service WHERE id = '$i'");
			$query_result = $query->execute();

			while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {
				$box .= '<div class="row"><div class="col-md-12"><h4 class="color">'.$rows['service'].'</h4></div></div>';
				// $query_aa = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE email = '$email'");	
		 	// 	$query_result = $query_aa->execute();
		 	// 	$count = $query_aa->rowCount();
		 	// 	$biz = $query_aa->fetchAll(PDO::FETCH_ASSOC);

		 		
		 		$master_biz_type = $this->_agentPDO->prepare("SELECT * FROM master_biz_type WHERE master_service_id = '".$rows['id']."'");
		 		
				
			 	$biz_type_result = $master_biz_type->execute();
			 	$row = $master_biz_type->fetchAll(PDO::FETCH_ASSOC);
			 	$box .= '<div class="row" style="margin-bottom:10px;"><div class="col-md-12"><hr></div>';
			 	foreach ($row as $key) {
			 		$query_check = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE customer_id = '$customer_id' AND master_biz_type_id = '$key[id]'");	
			 		$query_check->execute();
			 		$chk = $query_check->fetch(PDO::FETCH_ASSOC);
			 		if ($key['id'] == $chk['master_biz_type_id']) {
			 			$checked = 'checked';
			 		}
			 		else {
			 			$checked = '';	
			 		}
			 		if($key['id'] == '34' ||  $key['id'] == '35' ){
			 			$box .= '<div class="col-md-4"><div class="custom-control custom-checkbox mr-sm-2">
						        <input type="checkbox" name="service[]"   id="'.$key['id'].'" value="'.$key['id'].'"  class="custom-control-input" disabled>
						        <label class="custom-control-label font-weight-normal" style="font-size: 13px;color:#000;" for="'.$key['id'].'" readonly>'.$key['type'].'</label>
						      </div></div>';
			 		}
			 		else{
			 			$box .= '<div class="col-md-4"><div class="custom-control custom-checkbox mr-sm-2">
						        <input type="checkbox" name="service[]" '.$checked.'  id="'.$key['id'].'" value="'.$key['id'].'"  class="custom-control-input">
						        <label class="custom-control-label font-weight-normal" style="font-size: 13px;color:#000;" for="'.$key['id'].'">'.$key['type'].'</label>
						      </div></div>';	
			 			
			 		}
			 		
			 		// $box .= '<div class="col-md-4">
						// 		<input type="checkbox" name="service[]" '.$checked.'  id="'.$key['id'].'" value="'.$key['id'].'"> <label class="font-weight-bold" style="font-size: 12px;color:#6e1f4e;" for="'.$key['id'].'">'.$key['type'].'</div>';	
			 	}		
			 	$box .= '</div>';
			}
		}

		return $box;
	}

	public function fetchCount($table, $condition, $where) {
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->rowCount();
        
        return $row;       
    }

	public function selectSingelRow($table, $condition, $where) {
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
  
        return $row;
       
    }

	public function GetFullAgentData($main_agent_primary_id) {
		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE (master_agents.id = :id)");
			$query->bindParam(":id", $main_agent_primary_id);
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$row = $query->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			return $e->getMessage();
		}
		return $row;
	}


	public function CheckUniqueAgent($email, $mobile) {

			

		try {

			$this->_Mobile = $mobile;
			$this->_Email = $email;	
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE email = :email OR mobile = :mobile");
			$query->bindParam(':email', $this->_Email);
			$query->bindParam(':mobile', $this->_Mobile);
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$num = $query->rowCount();
			
		} catch (Exception $e) {
			return $e->getMessage();
		}

		
		return $num;

	}

	public function AgentsTotalCount($customer_id) {


		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id  = '$customer_id' AND status !='0'");
		$query_result = $query->execute() or die($this->_agentPDO->error);
		$count = $query->rowCount();
		$row = $query->fetch();

		if($row['agents_type_id'] == 3 )
		{
			$Hquery = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE hotel_type_id = '11' AND ref_id  = '$customer_id' AND status !='0'");
			$query_result = $Hquery->execute() or die($this->_hotelPDO->error);
			$Hoteliers = $Hquery->rowCount();

			$Squery = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE agents_type_id = '2' AND ref_id  = '$customer_id' AND status != '0'");
			$query_result = $Squery->execute() or die($this->_agentPDO->error);
			$Sub = $Squery->rowCount();

			$Aquery = $this->_agentPDO->prepare("SELECT * FROM `master_agents` as A 
					INNER JOIN master_agents AS B ON A.ref_id = B.customer_id WHERE A.agents_type_id ='1' AND A.status !='0' ");
			$query_result = $Aquery->execute() or die($this->_agentPDO->error);
			$agent = $Aquery->rowCount();
		}
		else{

			$Hquery = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE hotel_type_id = '11' AND ref_id  = '$customer_id' AND status !='0'");
			$query_result = $Hquery->execute() or die($this->_hotelPDO->error);
			$Hoteliers = $Hquery->rowCount();
		
			$Squery = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE agents_type_id = '2' AND ref_id  = '$customer_id' AND status !='0' ");
			$query_result = $Squery->execute() or die($this->_agentPDO->error);
			$Sub = $Squery->rowCount();

			$Aquery = $this->_agentPDO->prepare("SELECT * FROM `master_agents` as A 
					WHERE A.agents_type_id ='1' AND A.status !='0' AND A.ref_id = '".$row['customer_id']."' ");
			$query_result = $Aquery->execute() or die($this->_agentPDO->error);
			$agent = $Aquery->rowCount();
		}	
		$array = array(
			"hoteliers" => $Hoteliers,
			"subfranchise" => $Sub,
			"agents" => $agent
		);
		return $array;

	}

	public function ShowRegisteredData($email, $mobile) {

		try {

			$this->_Mobile = $mobile;
			$this->_Email = $email;	
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE email = :email OR mobile = :mobile");
			$query->bindParam(':email', $this->_Email);
			$query->bindParam(':mobile', $this->_Mobile);
			$query->execute() or die($this->_agentPDO->error);
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$array = array();
			foreach($result as $row)
			{
				$array[] = $row;
			}			
			
		} catch (Exception $e) {
			return $e->getMessage();
		}
		
		return $array;
	}


	public function CreateLinkNewAgents($customer_id, $comp_name, $fname, $lname, $city, $city_id, $citynm, $email, $mobile, $mobile_cc, $type) {
        $owner = $fname.' '.$lname;
		$array = $this->customerId($type);
	   $c_id = $array['customer_id'];
		// $pass = 'setu123456';
		// $password = md5($pass);

		// INSERT INTO `master_agents`(`customer_id`, `email`, `mobile`, `comp_name`, `fname`, `lname`, `city`, `city_id`, `city_name`, `ref_id`, `agents_type_id`, `status`, `created_by`, `created_at`, `updated_at`)


		$this->_agent_id = $array['customer_id'];
		try {
	
			$query = $this->_agentPDO->prepare("INSERT INTO `master_agents`(`customer_id`, `email`, `mobile`, `mobile_cc`, `comp_name`, `fname`, `lname`, `city`, `city_id`, `city_name`, `ref_id`, `agents_type_id`, `created_by`) VALUES (:customer_id, :email, :mobile, :mobile_cc, :comp_name, :fname, :lname, :city, :city_id, :citynm, :ref_id, :type, :created_by)");
			$query->bindParam(':customer_id', $this->_agent_id);
			$query->bindParam(":email", $email);
			$query->bindParam(":mobile", $mobile);
			$query->bindParam(":mobile_cc", $mobile_cc);
			$query->bindParam(":comp_name", $comp_name);
			$query->bindParam(":fname", $fname);
			$query->bindParam(":lname", $lname);
			$query->bindParam(":city", $city);
			$query->bindParam(":city_id", $city_id);
			$query->bindParam(":citynm", $citynm);
			$query->bindParam(":type", $type);
			$query->bindParam(":ref_id", $customer_id);
			$query->bindParam(":created_by", $customer_id);
			$query->execute() or die($this->_agentPDO->error);
			$this->UpdateCount($array['index']);
			if ($type == 1) {
				$this->AgentsMailTo($email, $comp_name, $owner, $c_id);
			}
			// else {
			// 	$this->FranchiseMailTo($email, $comp_name, $owner, $this->_agent_id);	
			// }
			
			$return = 1;
		} catch (Exception $e) {
			throw $e;
		}

		return $return;
	}
	private function customerId($type) {
		//======== Month Fetch =================//
		date_default_timezone_set('Asia/Kolkata');
		$month = date('F');
		$m = $this->selectSingelRow("master_month", "month", $month);
		$month_prefix = $m['prefix'];
		$year = date('y');
		$month_year = $month_prefix.$year;

		//======== Count Index Fetch =================//
		$index = $this->selectSingelRow("master_counts", "count_name", "customer_id");
		$index_id = $index['counts'];

		//======== Type Fetch =================//
		$type = $this->selectSingelRow("master_module", "id", $type);
		$type = $type['prefix'];

		//======== Make Zero =================//
		switch (strlen($index_id)) {
			case '1':
				$zero = '0000';
				break;
			case '2':
				$zero = '000';
				break;
			case '3':
				$zero = '00';
				break;
			case '4':
				$zero = '0';
				break;
			case '5':
				$zero = '';
				break;
		}
		$customer_id =  $type.$zero.$index_id.$month_year;
		$array = array('index' => $index_id, "customer_id" => $customer_id);
		return $array;
	}

	private function UpdateCount($index) {
		$index = $index + 1;
		$query = $this->_agentPDO->prepare("UPDATE master_counts SET counts = '$index' WHERE count_name = 'customer_id'");
		$query->execute() or die($this->_agentPDO->error);
	}


	private function primaryUpdate($customer_id, $primary_fname, $primary_lname, $primary_desg, $primary_email, $primary_mobile, $primary_mobile_cc, $primary_dob, $primary_image) {
		$pri = 1;
		$data = $this->FetchUniversal("SELECT * FROM master_agents_desg WHERE customer_id = :customer_id AND primary_contact = :pri ", array(":customer_id" => $customer_id, ":pri" => $pri));
		$count = $data['count'];

		if($count == 1) {
			
		
			try {
				$query = $this->_agentPDO->prepare("UPDATE master_agents_desg SET desg = :primary_desg, name = :primary_fname, lname = :primary_lname, desg_mobile = :primary_mobile, desg_mobile_cc = :primary_mobile_cc, desg_email = :primary_email, desg_dob = :primary_dob, `img` = :primary_image WHERE customer_id = :customer_id AND primary_contact = :pri");
				$query->bindParam(":primary_fname", $primary_fname); 
				$query->bindParam(":primary_lname", $primary_lname); 
				$query->bindParam(":primary_desg", $primary_desg); 
				$query->bindParam(":primary_email", $primary_email); 
				$query->bindParam(":primary_mobile", $primary_mobile); 
				$query->bindParam(":primary_mobile_cc", $primary_mobile_cc); 
				$query->bindParam(":primary_dob", $primary_dob); 
				$query->bindParam(":primary_image", $primary_image); 
				$query->bindParam(":customer_id", $customer_id); 
				$query->bindParam(":pri", $pri); 
				$query->execute() or die($this->_agentPDO->error);

				return 1;
			}catch (Exception $e) {
				return $e->getMessage();
			}
		} 
		else {
				$row = $this->selectSingelRow("master_agents", "customer_id", $customer_id);
				$count = $row['team_count'];
				$count = $count + 1;
				if (strlen($count) == 2) {
					$zero = '';
				}
				else {
					$zero = '0';	
				}

				$desg_customer_id = $row['customer_id'].$zero.$count;
				$pass = 't&t123456';
				$password = md5($pass);
				try{


					$query = $this->_agentPDO->prepare("INSERT INTO `master_agents_desg`(`customer_id`, `desg_customer_id`, `password`, `desg`, `name`, `lname`, `desg_mobile`, `desg_mobile_cc`, `desg_email`, `desg_dob`, `img`, `primary_contact`) VALUES (:customer_id, :desg_customer_id, :password, :primary_desg, :primary_fname, :primary_lname, :primary_mobile, :primary_email, :primary_dob, :primary_image, :pri)");

					$query->bindParam(":customer_id", $customer_id); 
					$query->bindParam(":desg_customer_id", $desg_customer_id); 
					$query->bindParam(":password", $password); 
					$query->bindParam(":primary_desg", $primary_desg); 
					$query->bindParam(":primary_fname", $primary_fname); 
					$query->bindParam(":primary_lname", $primary_lname); 
					$query->bindParam(":primary_mobile", $primary_mobile); 
					$query->bindParam(":primary_mobile_cc", $primary_mobile_cc); 
					$query->bindParam(":primary_email", $primary_email);
					$query->bindParam(":primary_dob", $primary_dob); 
					$query->bindParam(":primary_image", $primary_image); 
					$query->bindParam(":pri", $pri); 

					$query->execute() or die($this->_agentPDO->error);

					$query_upd = $this->_agentPDO->prepare("UPDATE master_agents SET team_count = '$count' WHERE customer_id = '$customer_id'");
					$query_upd->execute() or die($this->_agentPDO->error);

					return 1;
				}catch (Exception $e) {
				return $e->getMessage();
			}

		}
			// print_r($query);
		}


	public function UpdateVeryBasicDetails($customer_id, $comp_name, $owner_fname, $owner_lname, $owner_desg, $mobile_cc, $abbr, $owner_dob, $owner_image, $primary_fname, $primary_lname, $primary_desg, $primary_email, $primary_mobile, $primary_mobile_cc, $primary_dob, $primary_image, $wp_num, $wp_num_cc, $city, $city_id, $city_name, $pincode, $office, $road, $landmark, $village, $date_of_incorporation)  {
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');

		
		try {
			$query = $this->_agentPDO->prepare("UPDATE `master_agents` SET  `comp_name` = :comp_name, `mobile_cc` = :mobile_cc, `date_of_incorporation` = :date_of_incorporation, `abbr` = :abbr, `owner_dob` = :owner_dob, `owner_img` = :owner_image, `owner_desg` = :owner_desg, `fname` = :fname, `lname` = :lname, `city` = :city, `city_id` = :city_id, city_name = :city_name, pincode = :pincode, `wp_num` = :wp_num, `wp_num_cc` = :wp_num_cc, office = :office, road = :road, landmark = :landmark, village = :village, `updated_at`= :udate, `status` = '1'  WHERE customer_id = :customer_id");
			// $query->bindParam(":comp_name", $userid);
			$query->bindParam(":comp_name", $comp_name);
			$query->bindParam(":date_of_incorporation", $date_of_incorporation);
			$query->bindParam(":abbr", $abbr);
			$query->bindParam(":owner_dob", $owner_dob);
			$query->bindParam(":owner_image", $owner_image);
			$query->bindParam(":owner_desg", $owner_desg);
			$query->bindParam(":mobile_cc", $mobile_cc);
			$query->bindParam(":fname", $owner_fname);
			$query->bindParam(":lname", $owner_lname);
			$query->bindParam(":udate", $date);
			$query->bindParam(":city", $city);
			$query->bindParam(":city_id", $city_id);
			$query->bindParam(":city_name", $city_name);
			$query->bindParam(":pincode", $pincode);
			$query->bindParam(":wp_num", $wp_num);
			$query->bindParam(":wp_num_cc", $wp_num_cc);
			$query->bindParam(":office", $office);
			$query->bindParam(":road", $road);
			$query->bindParam(":landmark", $landmark);
			$query->bindParam(":village", $village);
			$query->bindParam(":customer_id", $customer_id);
			$query_result = $query->execute() or die($this->_agentPDO->error);

			//========================= Update Primary Contact Details =======================//
			$primary_update = $this->primaryUpdate($customer_id, $primary_fname, $primary_lname, $primary_desg, $primary_email, $primary_mobile, $primary_mobile_cc, $primary_dob, $primary_image);
			$this->AddAddressInApi($customer_id, $office, $road, $landmark, $village, $city);

			return 1;


			
		} catch (Exception $e) {
			return 'Error Message : '.$e->getMessage();
		}
	}

	public function UpdateBusinessDetails($customer_id, $org_type, $gst, $pan, $adhar) {
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');
		try {
			$query = $this->_agentPDO->prepare("UPDATE `master_agents` SET  `org_type` = :org_type, `gst` = :gst, `pan` = :pan, `adhar` = :adhar, `updated_at`= :udate WHERE customer_id = :customer_id");
			// $query->bindParam(":comp_name", $userid);
			$query->bindParam(":org_type", $org_type);
			$query->bindParam(":gst", $gst);
			$query->bindParam(":pan", $pan);
			$query->bindParam(":adhar", $adhar);
			$query->bindParam(":udate", $date);
			$query->bindParam(":customer_id", $customer_id);
			$query_result = $query->execute() or die($this->_agentPDO->error);

			return 1;
			
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function AddAddressInApi($customer_id, $office, $road, $landmark, $village, $city) {
		$row = $this->fetchCount("master_address", "customer_id", $customer_id);
		if ($row == 0) {
			$querys = $this->_agentPDO->prepare("INSERT INTO `master_address` (`customer_id`, `office`, `road`, `landmark`, `village`, `city`) VALUES ('$customer_id', '$office', '$road', '$landmark', '$village', '$city')");
			$querys->execute() or die($this->_agentPDO->error);
		}
		else {
			$querys = $this->_agentPDO->prepare("UPDATE `master_address` SET `office` = '$office', `road` = '$road', `landmark` = '$landmark', `village` = '$village', `city` = '$city' WHERE customer_id = '$customer_id'");
			$querys->execute() or die($this->_agentPDO->error);	
		}
		

	}

	public function CountOfContactDetails($email) {
		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg WHERE email = :email");
			$query->bindParam(":email", $email);
			$query_result = $query->execute() or die($this->_agentPDO->error);
			$num = $query->rowCount();

			return $num;
		} 
		catch (Exception $e) {
			return $e->getMessage();
		} 
	}

	public function deletePartOfGroup($did) {

		try {

			$query = $this->_agentPDO->prepare("DELETE FROM partof_travel_group WHERE id = :id");
			$query->bindParam(":id", $did);
			$query_result = $query->execute() or die($this->_agentPDO->error);

			return 1;
		} 
		catch (Exception $e) {
			return $e->getMessage();
		} 
	}

	public function updatePartOfGroup($id, $Group, $customer_id) {
		date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d H:i:s');
		$query = $this->_agentPDO->prepare("SELECT * FROM partof_travel_group WHERE group_name = '$Group' AND customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$count = $query->rowCount();

		if ($count < 1) {
				$query = $this->_agentPDO->prepare("UPDATE partof_travel_group SET `group_name` = :group_name, `updated_at` = :udate WHERE id = :id");
				$query->bindParam(':group_name', $Group);
				$query->bindParam(':udate', $date);
				$query->bindParam(':id', $id);

			$query_result = $query->execute() or die($this->_agentPDO->error);

			if ($query_result) {

				return 1;

			}
			else {

				return 0;

			}
		}
	}
	
	public function insertPartOfGroup($customer_id, $type, $Group) {
		$query = $this->_agentPDO->prepare("SELECT * FROM partof_travel_group WHERE group_name = '$Group' AND customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$count = $query->rowCount();

		if ($count < 1) {

			try {
				$query = $this->_agentPDO->prepare("INSERT INTO `partof_travel_group` (`customer_id`, `group_name`, `type`) VALUES (:customer_id, :group, :type)");
				$query->bindParam(":customer_id", $customer_id);
				$query->bindParam(":group", $Group);
				$query->bindParam(":type", $type);
				$query_result = $query->execute() or die($this->_agentPDO->error);

				return 1;
			} catch (Exception $e) {
				return $e->getMessage();
			}

		}
		return 0;

		
	}


	public function insertMultipleDesg($Desg, $customer_id, $Desg_name, $Desg_mobile, $Desg_mobile_cc, $Desg_email, $Desg_dob, $Desg_img) {

		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg INNER JOIN master_agents ON master_agents_desg.customer_id = master_agents.customer_id WHERE master_agents.customer_id = '$customer_id' AND (master_agents.email = '$Desg_email' OR master_agents_desg.desg_email = '$Desg_email' OR master_agents.mobile = '$Desg_mobile' OR master_agents_desg.desg_mobile = '$Desg_mobile')");
		$query->execute() or die($this->_agentPDO->error);
		$DesgCount = $query->rowCount();
		if ($DesgCount < 1) {

			$row = $this->selectSingelRow("master_agents", "customer_id", $customer_id);
			$count = $row['team_count'];
			$count = $count + 1;
			if (strlen($count) == 2) {
				$zero = '';
			}
			else {
				$zero = '0';	
			}

			 $desg_customer_id = $row['customer_id'].$zero.$count;

			try {

				$pass = 't&t123456';
				$password = md5($pass);
				$query = $this->_agentPDO->prepare("INSERT INTO `master_agents_desg` (`customer_id`, `desg_customer_id`, `desg`, `name`, `password`, `desg_mobile`, `desg_mobile_cc`, `desg_email`, `desg_dob`, `img`) VALUES (:customer_id, :desg_customer_id, :desg, :name, :password, :desg_mobile, :desg_mobile_cc, :desg_email, :desg_dob, :desg_img)");

				$query->bindParam(":customer_id", $customer_id);
				$query->bindParam(":desg_customer_id", $desg_customer_id);
				$query->bindParam(":desg", $Desg);
				$query->bindParam(":name", $Desg_name);
				$query->bindParam(":desg_mobile", $Desg_mobile);
				$query->bindParam(":desg_mobile_cc", $Desg_mobile_cc);
				$query->bindParam(":desg_email", $Desg_email);
				$query->bindParam(":desg_dob", $Desg_dob);
				$query->bindParam(":desg_img", $Desg_img);
				$query->bindParam(":password", $password);


				$query_result = $query->execute() or die($this->_agentPDO->error);

				$query_upd = $this->_agentPDO->prepare("UPDATE master_agents SET team_count = '$count' WHERE customer_id = '$customer_id'");
				$query_upd->execute() or die($this->_agentPDO->error);

				return 1;

			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
		return 0;

	}

	
	// Delete Designation

	public function deleteDesignation($did) {



		$query = $this->_agentPDO->prepare("DELETE FROM master_agents_desg WHERE id = :id");

		$query->bindParam(":id", $did);

		$query_result = $query->execute() or die($this->_agentPDO->error);



		if ($query_result) {

			return 1;

		} else {

			return 0;

		}

	}





	// Edit Designation

	public function updateDesignation($Desg, $Desg_name, $Desg_mobile, $Desg_mobile_cc, $Desg_email, $Desg_dob, $desg_img, $where) {



		date_default_timezone_set('Asia/Kolkata');

		$date = date('Y-m-d H:i:s');



		$query = $this->_agentPDO->prepare("UPDATE master_agents_desg SET `desg` = :desg, /*`desg_userid` = :userid,*/ `name` = :name, `desg_mobile` = :desg_mobile,  `desg_mobile_cc` = :desg_mobile_cc, `desg_email` = :desg_email, `desg_dob` = :desg_dob, `img` = :desg_img, `updated_at` = :udate WHERE id = :id");

		$query->bindParam(":desg", $Desg);
		// $query->bindParam(":userid", $userid);
		$query->bindParam(":name", $Desg_name);
		$query->bindParam(":desg_mobile", $Desg_mobile);
		$query->bindParam(":desg_mobile_cc", $Desg_mobile_cc);
		$query->bindParam(":desg_email", $Desg_email);
		$query->bindParam(":desg_dob", $Desg_dob);
		$query->bindParam(":desg_img", $desg_img);

		$query->bindParam(":udate", $date);
		$query->bindParam(":id", $where);

		$query_result = $query->execute() or die($this->_agentPDO->error);



		if ($query_result) {

			return 1;

		} else {

			return 0;

		}

	}

	//=======================Insert Banking For Bank==============================//
public function insertBanking($customer_id, $mode, $name, $bank_name, $branch, $branch_city, $acc_num, $ifsc)
{
	try {

		$query = $this->_agentPDO->prepare("INSERT INTO `master_banking` (`customer_id`, `mode`, `name`, `bank_name`, `branch`, `branch_city`, `acc_num`, `ifsc`) VALUES (:customer_id, :mode, :name, :bank_name, :branch, :branch_city, :acc_num, :ifsc)");

			$query->bindParam(":customer_id", $customer_id);
			$query->bindParam(":mode", $mode);
			$query->bindParam(":name", $name);
			$query->bindParam(":bank_name", $bank_name);
			$query->bindParam(":branch", $branch);
			$query->bindParam(":branch_city", $branch_city);
			$query->bindParam(":acc_num", $acc_num);
			$query->bindParam(":ifsc", $ifsc);

			$query_result = $query->execute() or die($this->_agentPDO->error);
			return 1;

	} catch (Exception $e) {

		return $e->getMessage();
	}
}
//=======================Insert Banking For Bank Over==============================//

	
//=========================All Banking Details=================================//
	public function insertBankingDetails($customer_id, $mode, $mob_num, $qr_code, $upi)
	{

		try {

			$query = $this->_agentPDO->prepare("INSERT INTO `master_banking` (`customer_id`, `mode`, `mob_num`, `qr_code`, `upi`) VALUES (:customer_id, :mode, :mob_num, :qr_code, :upi)");

				$query->bindParam(":customer_id", $customer_id);
				$query->bindParam(":mode", $mode);
				$query->bindParam(":mob_num", $mob_num);
				$query->bindParam(":qr_code", $qr_code);
				$query->bindParam(":upi", $upi);

				$query_result = $query->execute() or die($this->_agentPDO->error);
				return 1;

		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
//=========================All Banking Details Over=================================//



//-------------------------------------------------------------------------------------
public function updateBankingDetails($id, $nm, $bank_nm, $branch_nm, $branch_nm_city, $bank_num, $bank_ifsc) {

		try {

			$query = $this->_agentPDO->prepare("UPDATE `master_banking` SET `name` = :nm, `bank_name` = :bank_nm , `branch` = :branch_nm, `branch_city` = :branch_nm_city, `acc_num` = :bank_num, `ifsc` = :bank_ifsc WHERE `id` = :id");

			$query->bindParam(":id", $id);
			$query->bindParam(":nm", $nm);

			$query->bindParam(":bank_nm", $bank_nm);
			$query->bindParam(":branch_nm", $branch_nm);
			$query->bindParam(":branch_nm_city", $branch_nm_city);
			$query->bindParam(":bank_num", $bank_num);
			$query->bindParam(":bank_ifsc", $bank_ifsc);

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			return $e->getMessage();
			
		}

	}


//-------------------------------------------------------------------------------------	
public function updateAllBankingDetails($id, $bank_num, $bank_qr, $bank_upi) {

		try {

			$query = $this->_agentPDO->prepare("UPDATE `master_banking` SET `mob_num` = :bank_num , `qr_code` = :bank_qr, `upi` = :bank_upi WHERE `id` = :id");

			$query->bindParam(":id", $id);
			$query->bindParam(":bank_num", $bank_num);
			$query->bindParam(":bank_qr", $bank_qr);
			$query->bindParam(":bank_upi", $bank_upi);


			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			return $e->getMessage();
			
		}

	}


//=======================================================================	

public function deleteBank($id) {

		try {

			$query = $this->_agentPDO->prepare("DELETE FROM `master_banking` WHERE `id` = :id");

			$query->bindParam(":id", $id);

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			return $e->getMessage();
			
		}

	}

//=======================================================================	

public function deleteAllBank($id) {

		try {

			$query = $this->_agentPDO->prepare("DELETE FROM `master_banking` WHERE `id` = :id");

			$query->bindParam(":id", $id);

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			return $e->getMessage();
			
		}

	}

	//Premium Services All Function
	public function SelectPreBizService($customer_id) {
		
		$query=$this->_agentPDO->prepare("SELECT * FROM master_biz_type WHERE master_service_id=4");
		$query->execute() or die($this->_agentPDO->error);
		$row=$query->fetchAll(PDO::FETCH_ASSOC);
		$count=$query->rowCount();
		$box = '';
		$count1='';
		$box .= '<div class="row" style="margin-bottom:10px;">';
		//print_r($row);
		foreach ($row as $key) {
				//print_r($row);
				$query=$this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE master_biz_type_id = '$key[id]'  AND customer_id='$customer_id'");
				$query->execute() or die($this->_agentPDO->error);
				$rows=$query->fetchAll(PDO::FETCH_ASSOC);	
				// print_r($rows);
				if($rows != ""){
					foreach ($rows as $key1) {

						if($key1['master_biz_type_id'] == 54 || $key1['master_biz_type_id'] == 54 || $key1['master_biz_type_id'] == 32 || $key1['master_biz_type_id'] == 33 )
						{
							continue;
						}
						$box .= '<div class="col-md-6"><h4 class="color" >'.$key['type'].'</h4>';
						
						$query=$this->_agentPDO->prepare("SELECT * FROM master_premiumservice_question WHERE master_biz_type_id='$key1[master_biz_type_id]'");
						$query->execute() or die($this->_agentPDO->error);
						$row2=$query->fetchAll(PDO::FETCH_ASSOC);	
						
						foreach ($row2 as $key2) {
							//print_r($key2);
							$query=$this->_agentPDO->prepare("SELECT * FROM master_premiumservice_que_ans WHERE master_que_id= '$key2[id]' AND customer_id='$customer_id'");
							$query->execute() or die($this->_agentPDO->error);
							$row_data=$query->fetch(PDO::FETCH_ASSOC);	
							$count1 .= $query->rowCount();
								$box .= ' <p style="color : #6e1f4e;">'.ucwords($key2['abbr']).' :  <span style="color:black">'.$row_data['answer'].'</span>
				                </p>';	
						}
							$box.='</div>';
					}
				
				}
				
			}
				
		$box .="<br></div>";
		$option = $box;
		$array = array('box' => $option , 'count' => $count1);
		return $array;

	}

		
		//insert Premium Services
		public function insertPremiumBizzService($customer_id,  $q_id,  $answer) {

			try {

				$query_data=$this->_agentPDO->prepare("SELECT * FROM master_premiumservice_que_ans WHERE master_que_id = $q_id AND customer_id='$customer_id'");
				$query_data->execute() or die($this->_agentPDO->error);
				$row=$query_data->fetch(PDO::FETCH_ASSOC);
				$count = $query_data->rowCount();

				 if($count>0)
				{
					$query = $this->_agentPDO->prepare("UPDATE master_premiumservice_que_ans SET  `answer` = :answer  WHERE id= :id");
					
					//$query->bindParam(":customer_id", $customer_id);
					//$query->bindParam(":question_id", $q_id);
					$query->bindParam(":answer", $answer);
					$query->bindParam(":id", $row['id']);
					
					$query_result = $query->execute() or die($this->_agentPDO->error);

					return 1;	
				}
				else{
					$query = $this->_agentPDO->prepare("INSERT INTO master_premiumservice_que_ans (`customer_id`, `master_que_id`,`answer`) VALUES (:customer_id, :question_id,:answer)");
						
						$query->bindParam(":customer_id", $customer_id);
						$query->bindParam(":question_id", $q_id);
						$query->bindParam(":answer", $answer);
					}
					$query_result = $query->execute() or die($this->_agentPDO->error);
					return 1;
			   	 
			//return $count . "****" . $q_id . $answer;
				
			} catch (Exception $e) {
				return $e->getMessage();
			}

		}

	public function PreBizService($customer_id) {
		error_reporting(0);
		$query = $this->_agentPDO->prepare("SELECT * FROM master_biz_type where master_service_id=4  ");
		$query_result = $query->execute() or die($this->_agentPDO->error);
		$count = $query->rowCount();
		$BizRows = $query->fetchAll(PDO::FETCH_ASSOC);
		$box = '';
		$box .= '<div class="row" style="margin-bottom:10px;">';
		for ($i=1; $i <= $count ; $i++) { 
			
		 	foreach ($BizRows as $rows) {
				$master_biz_type = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type  WHERE master_biz_type_id = '$rows[id]' AND customer_id = '$customer_id' AND master_biz_type_id NOT IN (54,55)");
		 					
			 	$biz_type_result = $master_biz_type->execute() or die($this->_agentPDO->error);
			 	$row = $master_biz_type->fetchAll(PDO::FETCH_ASSOC);

				if ($row != "") {
					foreach ($row as $key) {
				 		$box .= '<div class="col-md-6"><h4 class="color">'.$rows['type'].'</h4>';	
				 		if ($key['master_biz_type_id'] == 54 || $key['master_biz_type_id'] == 55) {

				 			continue;
				 		}
				 		
				 		else {
				 			$query = $this->_agentPDO->prepare("SELECT * FROM master_premiumservice_question WHERE master_biz_type_id = '$key[master_biz_type_id]'");
				 		$query->execute() or die($this->_agentPDO->error);
				 		$chk = $query->fetchAll(PDO::FETCH_ASSOC);
				 			foreach ($chk as $key1)	 {

				 				if($key1['master_biz_type_id'] == "32" ){
				 					
				 					$query = $this->_agentPDO->prepare("SELECT * FROM master_premiumservice_que_ans WHERE master_que_id = '$key1[id]' AND customer_id = '$customer_id'");
					 				$query->execute() or die($this->_agentPDO->error);
					 				$ans  = $query->fetch(PDO::FETCH_ASSOC);
		 							
		 							$box .= '<p>'.ucwords($key1['question']).'</p>
		 							<div class="row">
		 								<div class="col-sm-5">
		 								<select class="form-control form-control-sm cruise_list" id="cruise1"> 
		 										<option value="">Select Cruise</option>
		 								</select>
		 								</div>
		 								<div class="col-sm-7 mt-0">
		 								<div class="cruise1"></div>
		 								<input type="hidden"  class="questionId" value="'.$key1['id'].'" 
		 								id="questionId" name="que_id[]"><br>	
		 								</div>
		 								
		 							</div>
		 							';
				 				}
				 				if($key1['master_biz_type_id'] == "33" ) {
				 					
				 					$query = $this->_agentPDO->prepare("SELECT * FROM master_premiumservice_que_ans WHERE master_que_id = '$key1[id]' AND customer_id = '$customer_id'");
					 				$query->execute() or die($this->_agentPDO->error);
					 				$ans  = $query->fetch(PDO::FETCH_ASSOC);
		 							
		 							$box .= '<p>'.ucwords($key1['question']).'</p>
		 							<div class="row">
		 								<div class="col-sm-5">
		 								<select class="form-control form-control-sm cruise_list" id="cruise2"> 
		 									<option value="">Select Cruise</option>
		 								</select>
		 								</div>
		 								<div class="col-sm-7 mt-0">
		 								<div class="cruise2"></div>
		 								<input type="hidden"  class="questionId" value="'.$key1['id'].'" 
		 								id="questionId" name="que_id[]"><br>	
		 								</div>
		 							</div>
		 							';
		 						}
					 			else{
					 				if($key1['master_biz_type_id'] == "32" || $key1['master_biz_type_id'] == "33"){
					 					continue;
					 				}
					 				$query = $this->_agentPDO->prepare("SELECT * FROM master_premiumservice_que_ans WHERE master_que_id = '$key1[id]' AND customer_id = '$customer_id'");
					 				$query->execute() or die($this->_agentPDO->error);
					 				$ans  = $query->fetch(PDO::FETCH_ASSOC);
		 							
		 							$box .= '<p>'.ucwords($key1['question']).'</p>
		 							<textarea q_id="'.$key1['id'].'"  name="PremiumService[]" required id="PremiumService" rows="1" class="form-control form-control-sm ans" placeholder="Write Here.." >'.$ans['answer'].'</textarea><input type="hidden"  class="questionId" value="'.$key1['id'].'" 
		 								id="questionId" name="que_id[]"><br>';
		 						}
				 			}
				 		}
				 		
			 							 	
			 	 		$box .= '</div><br>';
					}
				}			 		
			}		
			$box .= '</div>';
			return $box;
		}
	}

	public function GetCruise1($customer_id) {
		$query = $this->_agentPDO->prepare("SELECT * FROM master_selected_cruise WHERE type = '1' AND customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$cruise_selected1  = $query->fetchAll(PDO::FETCH_ASSOC);
		return $cruise_selected1;
	}

	public function InsertCruise1Data($customer_id, $cruise) {
		$type = 1;
		try {
			$query = $this->_agentPDO->prepare('INSERT INTO `master_selected_cruise`(`customer_id`, `cruise_id`, `type`) VALUES (:customer_id, :cruise, :type)');
		    $query->bindParam(":customer_id", $customer_id);
		    $query->bindParam(":cruise", $cruise);
		    $query->bindParam(":type", $type);
		    $query->execute() or die($this->_agentPDO->error);
		    return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function GetCruise2($customer_id) {
		$query = $this->_agentPDO->prepare("SELECT * FROM master_selected_cruise WHERE type = '2' AND customer_id = '$customer_id'");
		$query->execute() or die($this->_agentPDO->error);
		$cruise_selected2  = $query->fetchAll(PDO::FETCH_ASSOC);
		return $cruise_selected2;
	}

	public function InsertCruise2Data($customer_id, $cruise) {
		$type = 2;
		try {
			$query = $this->_agentPDO->prepare('INSERT INTO `master_selected_cruise`(`customer_id`, `cruise_id`, `type`) VALUES (:customer_id, :cruise, :type)');
		    $query->bindParam(":customer_id", $customer_id);
		    $query->bindParam(":cruise", $cruise);
		    $query->bindParam(":type", $type);
		    $query->execute() or die($this->_agentPDO->error);
		    return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	//===================================Select PrimumServices=======================//
	public function SelectPreBizzService($customer_id) {
		try {

			$query = $this->_agentPDO->prepare("SELECT a.*,q.*,b.* FROM agents_biz_type AS b,master_premiumservice_question AS q,master_premiumservice_que_ans AS a WHERE b.customer_id='$customer_id' AND a.master_que_id=q.id  AND q.master_biz_type_id =b.master_biz_type_id");

			$query->execute() or die($this->_agentPDO->error);

			$row = $query->fetchAll(PDO::FETCH_ASSOC);

			return $row;
			}

		catch (Exception $e) {

			throw $e;

		} 


	}
//==================Vehicle Details for Agents id = 54 =======================//
	public function SelectVehicle($customer_id)
	{
		$box = "";
		
		$master_biz_type = $this->_agentPDO->prepare("SELECT * FROM master_biz_type  WHERE id IN (54)");				
	 	$biz_type_result = $master_biz_type->execute() or die($this->_agentPDO->error);
	 	$row = $master_biz_type->fetchAll(PDO::FETCH_ASSOC);
	 	$count=$master_biz_type->rowCount();
	 		
 
		 foreach ($row as $key) {
			$query = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE master_biz_type_id = '$key[id]' AND customer_id = '$customer_id'");
	 		$query->execute() or die($this->_agentPDO->error);
	 		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

	 	
			foreach ($rows as $key1) {
	        if ($rows != '') {
	        	$box .= '<div class="row VehTypeDiv1" style="margin-bottom:10px;border:1px solid #666;padding:15px;border-radius: 5px;">';
	        	$box .= '<input type="hidden" value="54" id="VehicleType1">';
			 	$box .= '<div class="col-mlg-auto"><h4 class="color mb-2">'.$key['type'].'</h4></div>
			 			<div class="row">
			 				<div class="col-lg-6">
				 				<div class="row">
				 					<div class="col-md-5">
					 					<p style="font-size: 14px;">Select Vehicle Category</p>
					 					<select name="veh" id="veh_type_id" class="form-control form-control-sm">
					 					';
										$query_sel = $this->_agentPDO->prepare("SELECT * FROM master_vehicle_subcategory WHERE master_vehicle_category_id=1 OR master_vehicle_category_id=3 ");
								 		$query_sel->execute() or die($this->_agentPDO->error);
								 		$rows_sel = $query_sel->fetchAll(PDO::FETCH_ASSOC);
					 					foreach ($rows_sel as $key_sel) {
						 					$box .= '<option value="'.$key_sel['id'].'">'.ucwords($key_sel["type"]).'</option>';
					 					}
					 					$box .='</select>
					 				</div>
					 			</div>
					 			<div class="row">	
					 				<div class="col-md-5" style="font-size: 14px;">
					 					<p>Vehicle Type <span class="text-danger">*</span></p>
					 					<input type="text" class="form-control form-control-sm type" value="" placeholder="Vehicle Type" >
					 					
					 				</div>
					 				<div class="col-md-5" style="font-size: 14px;">
					 					<p>Number of Vehicle <span class="text-danger">*</span></p>
					 					<input type="text" class="form-control form-control-sm num" value="" biz_type_id="'.$key['id'].'" placeholder="Number of Vehicle" ><input type="hidden" value="'.$key['id'].'" class="biz_type_id">
					 				</div>
					 				<div class="col-md-2">
					 					<input type="button" value="ADD" class="btn btn-primary btn-sm add_data mt-4">
					 				</div>
				 				</div>
				 				<div class ="row">';
				 				$box .='<div class="col-sm-12 table-responsive B2Btbl " style="display: fixed;">';
						 $Vtype = $this->_agentPDO->prepare("SELECT * FROM master_vehicle WHERE master_biz_type_id IN (54) AND customer_id='$customer_id'");				
					 	$Vtype_data = $Vtype->execute() or die($this->_agentPDO->error);
					 	$row_d = $Vtype->fetchAll(PDO::FETCH_ASSOC);
		
					 	$count=$Vtype->rowCount();
						 if($count <=0){
					    	$box .='	<table class="table table-sm table-striped B2BTbl text-dark font-weight-bold " style="font-size: 14px;border-radius: 5px!important;" style="font-size: 14px!important;border-radius: 5px !important;margin-top: 15!important;width: 93.5%!important;">
					    			<thead style="background-color: #6e1f4e;color: #fff;">
					    				<th>#</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Vehicle Type</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">No. of  Vehicle</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Info. Captured</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Pending</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Actions</th></thead><tbody style="font-size: 12px;">

					    		  <tbody style="font-size: 12px;"><tbody><tr><td></td><td colspan="3">No Data Found... Please Add Vehicle Type...</td></tr>
					    		</tbody></table>';	
					    	}
					    	else{
					    		$box .='
					    		<table class="table table-sm table-striped B2BTbl text-dark font-weight-bold " style="font-size: 14px;border-radius: 5px!important;" style="font-size: 14px!important;border-radius: 5px !important;margin-top: 15!important;width: 93.5%!important;">
					    			<thead style="background-color: #6e1f4e;color: #fff;">
					    				<th>#</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Vehicle Type</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">No. of  Vehicle</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Info. Captured</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Pending</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Actions</th></thead><tbody style="font-size: 12px;">';
					    		  $i=1;
						   		foreach($row_d AS $row)
						   		{
						   			if($row['master_biz_type_id'] == 54){
						   					$count = $this->fetchCount("master_vehicle_type", "veh_id", $row['id']);
						   					$num = $row['number_of_vehicle'] - $count;
						   				$box .= '<tr>
						   						<td class="text-center">'.$i++.'</td>
						   						<td class="text-center">'.ucwords($row['vehicle_type']).'</td>
						   						<td class="text-center">'.$row['number_of_vehicle'].'</td>
						   						<td class="text-center">'.$count.'</td>
						   						<td class="text-center">'.$num.'</td>
						   		                <td class="text-center"><center><span style="cursor: pointer;" class="link_del1" title="Delete" data-toggle="tooltip" data-placement="top" link-id1="'.$row['id'].'"><i class="text-danger fas fa-trash"></i></span></center></td>

						   				</tr>';
						   		}
						   		
						   		}
					    		$box .='</tbody></table>';	

					    	}
			
				$box .='</div></div></div>
						 
			 			<div class="col-lg-6">
				 				<div class="row">

					 				<div class="col-md-6">
					 					<p style="font-size: 14px;">Select Vehicle</p>
					 					<select name="veh" id="veh_select1" class="form-control form-control-sm">
					 				 	';
										$query_data = $this->_agentPDO->prepare("SELECT * FROM master_vehicle WHERE master_biz_type_id = 54 AND customer_id = '$customer_id'");
								 		$query_data->execute() or die($this->_agentPDO->error);
								 		$rows_sel = $query_data->fetchAll(PDO::FETCH_ASSOC);
								 		 // print_r($rows_sel);
					 					foreach ($rows_sel as $key_sel) {

						 					$count1 = $this->fetchCount("master_vehicle_type", "veh_id", $key_sel['id']);
						 					 $num = $key_sel['number_of_vehicle'] - $count1;
											$i=1;

											for($j=1; $j <= $num ; $j++)
											{

												$box .= '<option value="'.$key_sel['id'].'">'.ucwords($key_sel["vehicle_type"]).' No. '.$i++. '</option>';
												}
					 						}

					 					$box .='</select>
					 				</div>
			 		
								 	<div  class="col-md-6" style="float:left">
							         	<p style="font-size: 14px;">Registration Number</p>
							            <input type="text" placeholder="Write Here.." name="reg_num" class="form-control reg_num form-control-sm">  
							        </div>
							        <div  class="col-md-3" style="float:left">
							             <p style="font-size: 14px;margin-top:5px;">Mfg. Year</p>
							             <input type="text" placeholder="Write Here.." name="manu_year" class="form-control form-control-sm manu_year">  
							        </div>
					        		<div  class="col-md-3" style="float:right">
					         			<p style="font-size: 14px;margin-top:5px;">How Many Sitter ?</p>
				             			<input type="text" placeholder="Write Here.." name="sit" class="form-control form-control-sm sit">  
					         		</div>
					         		<div  class="col-md-3" style="float:left">
						         		<div class="form-check mt-1">
						         		<center>
						         		<p style="font-size: 14px;">Taxi Passing?</p>
										  <input class="form-check-input mt-1" name="chk1" type="checkbox" value="yes" id="chk1">
										  <label class="form-check-label" for="chk1" value="yes" style="font-size: 14px;">
										     Yes
										  </label>
										</div>
					             		</center>
					         		</div> 
					          		<div  class="col-md-3">
					              		<input type="button" value="ADD" class="btn btn-primary btn-sm add_data_veh mt-4">
			 							<span id="type_d" style="display:none;"></span>
					         		</div>    
						 			
						 		</div>
						 	</div>	';
			  
				 	$box .= "</div>";
				 	}		 }
		
		 $box .= '</div></div>';
		}
		 return $box;
	}
//==================Vehicle Details for Agents id = 55 =======================//
	public function SelectVehicle1($customer_id) {	
		$box = "";
		
		$master_biz_type = $this->_agentPDO->prepare("SELECT * FROM master_biz_type  WHERE id IN (55)");				
	 	$biz_type_result = $master_biz_type->execute() or die($this->_agentPDO->error);
	 	$row = $master_biz_type->fetchAll(PDO::FETCH_ASSOC);
	 	$count=$master_biz_type->rowCount();
	 		
 
		 foreach ($row as $key) {
			$query = $this->_agentPDO->prepare("SELECT * FROM agents_biz_type WHERE master_biz_type_id = '$key[id]' AND customer_id = '$customer_id'");
	 		$query->execute() or die($this->_agentPDO->error);
	 		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

	 	
			foreach ($rows as $key1) {
	        if ($rows != '') {
	        $box .= '<div class="row" style="margin-bottom:10px;border:1px solid #666;padding:15px;border-radius: 5px;">';
	        $box .= '<input type="hidden" value="55" id="VehicleType2">';
			 	$box .= '<div class="col-mlg-auto"><h4 class="color mb-2">'.$key['type'].'</h4></div>
			 			<div class="row">
			 				<div class="col-lg-6">
				 				<div class="row">
				 					<div class="col-md-5">
					 					<p style="font-size: 14px;">Select Vehicle Category</p>
					 					<select name="veh" id="veh_type_id1" class="form-control form-control-sm">
					 					';
										$query_sel = $this->_agentPDO->prepare("SELECT * FROM master_vehicle_subcategory WHERE master_vehicle_category_id=2");
								 		$query_sel->execute() or die($this->_agentPDO->error);
								 		$rows_sel = $query_sel->fetchAll(PDO::FETCH_ASSOC);
					 					foreach ($rows_sel as $key_sel) {
						 					$box .= '<option value="'.$key_sel['id'].'">'.ucwords($key_sel["type"]).'</option>';
					 					}
					 					$box .='</select>
					 				</div>
					 			</div>
					 			<div class="row">	
					 				<div class="col-md-5" style="font-size: 14px;">
					 					<p>Vehicle Type <span class="text-danger">*</span></p>
					 					<input type="text" class="form-control form-control-sm type1" value="" placeholder="Vehicle Type" >
					 					
					 				</div>
					 				<div class="col-md-5" style="font-size: 14px;">
					 					<p>Number of Vehicle <span class="text-danger">*</span></p>
					 					<input type="text" class="form-control form-control-sm num1" value="" biz_type_id1="'.$key['id'].'" placeholder="Number of Vehicle" ><input type="hidden" value="'.$key['id'].'" class="biz_type_id1">
					 				</div>
					 				<div class="col-md-2">
					 					<input type="button" value="ADD" class="btn btn-primary btn-sm add_data1 mt-4">
					 				</div>
				 				</div>
				 				<div class ="row">';
				 				$box .='<div class="col-sm-12 table-responsive B2Btbl1 " style="display: fixed;">';
						 $Vtype = $this->_agentPDO->prepare("SELECT * FROM master_vehicle WHERE master_biz_type_id IN (55) AND customer_id='$customer_id'");				
					 	$Vtype_data = $Vtype->execute() or die($this->_agentPDO->error);
					 	$row_d = $Vtype->fetchAll(PDO::FETCH_ASSOC);
		
					 	$count=$Vtype->rowCount();
						 if($count <=0){
					    	$box .='	<table class="table table-sm table-striped B2BTbl1 text-dark font-weight-bold " style="font-size: 14px;border-radius: 5px!important;" style="font-size: 14px!important;border-radius: 5px !important;margin-top: 15!important;width: 93.5%!important;">
					    			<thead style="background-color: #6e1f4e;color: #fff;">
					    				<th>#</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Vehicle Type</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">No. of  Vehicle</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Info. Captured</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Pending</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Actions</th></thead><tbody style="font-size: 12px;">

					    		  <tbody style="font-size: 12px;"><tbody><tr><td></td><td colspan="3">No Data Found... Please Add Vehicle Type...</td></tr>
					    		</tbody></table>';	
					    	}
					    	else{
					    		$box .='
					    		<table class="table table-sm table-striped B2BTbl1 text-dark font-weight-bold " style="font-size: 14px;border-radius: 5px!important;" style="font-size: 14px!important;border-radius: 5px !important;margin-top: 15!important;width: 93.5%!important;">
					    			<thead style="background-color: #6e1f4e;color: #fff;">
					    				<th>#</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Vehicle Type</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">No. of  Vehicle</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Info. Captured</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;" style="width:20%;">Pending</th>
					    				<th class="text-center" style="font-size:12px;letter-spacing:1px;">Actions</th></thead><tbody style="font-size: 12px;">';
					    		  $i=1;
						   		foreach($row_d AS $row)
						   		{
						   			if($row['master_biz_type_id'] == 55){
						   					$count = $this->fetchCount("master_vehicle_type", "veh_id", $row['id']);
						   					// $count = $this->fetchCount("master_vehicle_type", "veh_id", $row['id']);
											$num = $row['number_of_vehicle'] - $count;
						   				$box .= '<tr>
						   						<td class="text-center">'.$i++.'</td>
						   						<td class="text-center">'.ucwords($row['vehicle_type']).'</td>
						   						<td class="text-center">'.$row['number_of_vehicle'].'</td>
						   						<td class="text-center">'.$count.'</td>
						   						<td class="text-center">'.$num.'</td>
						   		                <td class="text-center"><center><span style="cursor: pointer;" class="link_del2" title="Delete" data-toggle="tooltip" data-placement="top" link-id2="'.$row['id'].'"><i class="text-danger fas fa-trash"></i></span></center></td>

						   				</tr>';
						   		}
						   		
						   		}
					    		$box .='</tbody></table>';	

					    	}
			
				$box .='</div></div></div>
						 
			 			<div class="col-lg-6">
				 				<div class="row">

					 				<div class="col-md-6">
					 					<p style="font-size: 14px;">Select Vehicle</p>
					 					<select name="veh" id="veh_select2" class="form-control form-control-sm">
					 					';
										$query_sel = $this->_agentPDO->prepare("SELECT * FROM master_vehicle WHERE master_biz_type_id = 55 AND customer_id = '$customer_id'");
								 		$query_sel->execute() or die($this->_agentPDO->error);
								 		$rows_sel = $query_sel->fetchAll(PDO::FETCH_ASSOC);
					 					foreach ($rows_sel as $key_sel) {
						 					$count = $this->fetchCount("master_vehicle_type", "veh_id", $key_sel['id']);
											$num = $key_sel['number_of_vehicle'] - $count;
											$i=1;
												
											for($j=1; $j <= $num ; $j++){

												$box .= '<option value="'.$key_sel['id'].'">'.ucwords($key_sel["vehicle_type"]).' No. '.$i++. '</option>';
												}		
					 						}
					 					$box .='</select>
					 				</div>
			 		
								 	<div  class="col-md-6" style="float:left">
							         	<p style="font-size: 14px;">Registration Number</p>
							            <input type="text" placeholder="Write Here.." name="reg_num1" class="form-control reg_num1 form-control-sm">  
							        </div>
							        <div  class="col-md-3" style="float:left">
							             <p style="font-size: 14px;margin-top:5px;">Mfg. Year</p>
							             <input type="text" placeholder="Write Here.." name="manu_year1" class="form-control form-control-sm manu_year1">  
							        </div>
					        		<div  class="col-md-3" style="float:right">
					         			<p style="font-size: 14px;margin-top:5px;">How Many Sitter ?</p>
				             			<input type="text" placeholder="Write Here.." name="sit1" class="form-control form-control-sm sit1">  
					         		</div>
					         		<div  class="col-md-3" style="float:left">
						         		<div class="form-check mt-1">
						         		<center>
						         		<p style="font-size: 14px;">Taxi Passing?</p>
										  <input class="form-check-input mt-1" name="chk2" type="checkbox" value="yes" id="chk2">
										  <label class="form-check-label" for="chk2" value="yes" style="font-size: 14px;">
										     Yes
										  </label>
										</div>
					             		</center>
					         		</div> 
					          		<div  class="col-md-3">
					              		<input type="button" value="ADD" class="btn btn-primary btn-sm add_data_veh1 mt-4">
			 							<span id="type_d" style="display:none;"></span>
					         		</div>    
						 			
						 		</div>
						 	</div>	';
			  
				 	$box .= "</div>";
				 	}		 }
		
		 $box .= '</div></div>';
		}
		 return $box;
	}
//================== Delete Vehicle Type =======================//	
	public function DeleteLinksDataForEdit1($id) {
		try {
			$query = $this->_agentPDO->prepare("DELETE FROM `master_vehicle` WHERE id = :id");
			$query->bindParam(":id", $id);
			$query->execute() or die($this->_agentPDO->error);
			return 1;

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
//======================== Insert Vehicle Type Data===========================//
	public function insertVehicalData($customer_id,  $biz_type_id,  $answer1, $answer2, $veh_id) {
		$query = $this->_agentPDO->prepare("INSERT INTO master_vehicle (`customer_id`, `master_biz_type_id`,`vehicle_type`,`number_of_vehicle`,`master_type_subcat_id`) VALUES (:customer_id, :biz_type_id, :answer1, :answer2, :veh_id)");
			
			$query->bindParam(":customer_id", $customer_id);
			$query->bindParam(":biz_type_id", $biz_type_id);
			$query->bindParam(":answer1", $answer1);
			$query->bindParam(":answer2", $answer2);
			$query->bindParam(":veh_id", $veh_id);
		$query->execute() or die($this->_agentPDO->error);
		return 1;
	}

//==================Insert Vehicle More Details============================================//
	public function InsertVehicalDetails1($reg_num , $manu_year, $sitter, $taxi, $veh_id, $customer_id){

		try {

			$query = $this->_agentPDO->prepare("INSERT INTO `master_vehicle_type`(`reg_num`, `manu_year`, `sitter`, `taxi_passing`, `veh_id`, `customer_id`) VALUES (:reg_num, :manu_year, :sitter, :taxi, :veh_id, :customer_id)");
			$query->bindParam(":reg_num", $reg_num);
			$query->bindParam(":manu_year", $manu_year);
			$query->bindParam(":sitter", $sitter);
			$query->bindParam(":taxi", $taxi);
			$query->bindParam(":veh_id", $veh_id);
			$query->bindParam(":customer_id", $customer_id);
			$query_result = $query->execute() or die($this->_agentPDO->error);	
		
			return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

//=============== Fetch All Vehicle Type For Select Option ==================//
	public function GetServiceWiseVehicleData($customer_id, $biz_id) {
		try {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_vehicle WHERE master_biz_type_id = '$biz_id' AND customer_id = '$customer_id'");
	 		$query->execute() or die($this->_agentPDO->error);
	 		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

	 		return $rows;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}


}



?>