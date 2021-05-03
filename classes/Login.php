<?php

/**
 * 
 */
class Login extends Database {

	public function CheckUserLogin($email) {
		$password = 'setu';
		$password = 'setu'.$password;
		$password = md5($password);

		//================= Check From Agents ================//
		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_agents_desg ON master_agents.customer_id = master_agents_desg.customer_id WHERE master_agents.mobile = :email OR master_agents.customer_id = :customer_id OR master_agents.mobile = :mobile OR master_agents_desg.desg_email = :desg_email OR master_agents_desg.desg_customer_id = :desg_email OR master_agents_desg.desg_mobile = :desg_customer_id");
		$query->bindParam(":email", $email);
		$query->bindParam(":customer_id", $email);
		$query->bindParam(":mobile", $email);
		$query->bindParam(":desg_email", $email);
		$query->bindParam(":desg_email", $email);
		$query->bindParam(":desg_customer_id", $email);
		$query_result = $query->execute() or die($this->link->error);
		$num = $query->rowCount();	

		if ($num == 0) {
			//============= Check From Hotel ===============//
			$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_desg ON master_hotel.customer_id = master_hotel_desg.customer_id WHERE master_hotel.mob_num = :email OR master_hotel.customer_id = :customer_id OR master_hotel.mob_num = :mobile OR master_hotel_desg.desg_email = :desg_email OR master_hotel_desg.desg_customer_id = :desg_email OR master_hotel_desg.desg_mobile = :desg_customer_id");
			$query->bindParam(":email", $email);
			$query->bindParam(":customer_id", $email);
			$query->bindParam(":mobile", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_customer_id", $email);
			$query_result = $query->execute() or die($this->_hotelPDO->error);
			$HotelRowCount = $query->rowCount();
			//================ If Find Data From Hotel Than Check Login Type =============// 
			if ($HotelRowCount >= 1) {
				$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE mob_num = :email OR customer_id = :customer_id OR mob_num = :mobile");
				$query->bindParam(":email", $email);
				$query->bindParam(":customer_id", $email);
				$query->bindParam(":mobile", $email);
				$query_result = $query->execute() or die($this->_hotelPDO->error);
				$MasterHotelCount = $query->rowCount();
				if ($MasterHotelCount == 1) {
					return 200;
				}
				else 
				{
					return 404;
				}
					
				
			}
			else {
				return 401;
			}
			


		}
		else if($num == 1) {

			//============= Check From Hotel ===============//
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_agents_desg ON master_agents.customer_id = master_agents_desg.customer_id WHERE master_agents.mobile = :email OR master_agents.customer_id = :customer_id OR master_agents.mobile = :mobile OR master_agents_desg.desg_email = :desg_email OR master_agents_desg.desg_customer_id = :desg_email OR master_agents_desg.desg_mobile = :desg_customer_id");
			$query->bindParam(":email", $email);
			$query->bindParam(":customer_id", $email);
			$query->bindParam(":mobile", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_customer_id", $email);
			$query_result = $query->execute() or die($this->link->error);
			$AgentsRowCount = $query->rowCount();
			
			//================ If Find Data From Agents Than Check Login Type =============// 
			if ($AgentsRowCount >= 1) {
				$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE mobile = :email OR customer_id = :customer_id OR mobile = :mobile");
				$query->bindParam(":email", $email);
				$query->bindParam(":customer_id", $email);
				$query->bindParam(":mobile", $email);
				$query_result = $query->execute() or die($this->link->error);
				$MasterAgentsCount = $query->rowCount();
				if ($MasterAgentsCount == 1) {
					
					return 200;
					
				}
				else {
					$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg WHERE desg_email = :email OR desg_customer_id = :customer_id OR desg_mobile = :mobile");
					$query->bindParam(":email", $email);
					$query->bindParam(":customer_id", $email);
					$query->bindParam(":mobile", $email);
					$query_result = $query->execute() or die($this->link->error);
					$MasterAgentsDesgCount = $query->rowCount();
					if ($MasterAgentsDesgCount == 1) {
						return 200;
					}
					else {
						return 401;	
					}
					
				}
			}
			else {
				return 401;
			}
		}
		else {
			return 401;
		}
	}
	

	public function FetchUSerByAnyOne($email) {

		//================= Check From Agents ================//
		$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_agents_desg ON master_agents.customer_id = master_agents_desg.customer_id WHERE master_agents.mobile = :email OR master_agents.customer_id = :customer_id OR master_agents.mobile = :mobile OR master_agents_desg.desg_email = :desg_email OR master_agents_desg.desg_customer_id = :desg_email OR master_agents_desg.desg_mobile = :desg_customer_id");
		$query->bindParam(":email", $email);
		$query->bindParam(":customer_id", $email);
		$query->bindParam(":mobile", $email);
		$query->bindParam(":desg_email", $email);
		$query->bindParam(":desg_email", $email);
		$query->bindParam(":desg_customer_id", $email);
		$query_result = $query->execute() or die($this->link->error);
		$num = $query->rowCount();	

		if ($num == 0) {
			//============= Check From Hotel ===============//
			$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel INNER JOIN master_hotel_desg ON master_hotel.customer_id = master_hotel_desg.customer_id WHERE master_hotel.mob_num = :email OR master_hotel.customer_id = :customer_id OR master_hotel.mob_num = :mobile OR master_hotel_desg.desg_email = :desg_email OR master_hotel_desg.desg_customer_id = :desg_email OR master_hotel_desg.desg_mobile = :desg_customer_id");
			$query->bindParam(":email", $email);
			$query->bindParam(":customer_id", $email);
			$query->bindParam(":mobile", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_customer_id", $email);
			$query_result = $query->execute() or die($this->_hotelPDO->error);
			$HotelRowCount = $query->rowCount();
			
			//================ If Find Data From Hotel Than Check Login Type =============// 
			if ($HotelRowCount >= 1) {
				$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE mob_num = :email OR customer_id = :customer_id OR mob_num = :mobile");
				$query->bindParam(":email", $email);
				$query->bindParam(":customer_id", $email);
				$query->bindParam(":mobile", $email);
				$query_result = $query->execute() or die($this->_hotelPDO->error);
				$MasterHotelCount = $query->rowCount();
				if ($MasterHotelCount == 1) {

					$Rows = $query->fetch(PDO::FETCH_ASSOC);
					return array(
						"login_type" => "admin",
						"customer_id" => $Rows['customer_id'],
						"comp_name" => $Rows['known_by_name'],
						"desg" => $Rows['pri_desg'],
						"name" => $Rows['pri_fnm'].' '.$Rows['pri_lnm'],
						"mobile" => $Rows['mob_num'],
						"email" => $Rows['email'],
						"row" => $Rows
					);
				}
				else {
					return 404;
				}
			}
			else {
				return 401;
			}
			


		}
		else if($num == 1) {

			//============= Check From Hotel ===============//
			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_agents_desg ON master_agents.customer_id = master_agents_desg.customer_id WHERE master_agents.mobile = :email OR master_agents.customer_id = :customer_id OR master_agents.mobile = :mobile OR master_agents_desg.desg_email = :desg_email OR master_agents_desg.desg_customer_id = :desg_email OR master_agents_desg.desg_mobile = :desg_customer_id");
			$query->bindParam(":email", $email);
			$query->bindParam(":customer_id", $email);
			$query->bindParam(":mobile", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_email", $email);
			$query->bindParam(":desg_customer_id", $email);
			$query_result = $query->execute() or die($this->link->error);
			$AgentsRowCount = $query->rowCount();
			
			//================ If Find Data From Agents Than Check Login Type =============// 
			if ($AgentsRowCount >= 1) {
				$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE mobile = :email OR customer_id = :customer_id OR mobile = :mobile");
				$query->bindParam(":email", $email);
				$query->bindParam(":customer_id", $email);
				$query->bindParam(":mobile", $email);
				$query_result = $query->execute() or die($this->link->error);
				$MasterAgentsCount = $query->rowCount();
				if ($MasterAgentsCount == 1) {
					$Rows = $query->fetch(PDO::FETCH_ASSOC);
					return array(
						"login_type" => "admin",
						"customer_id" => $Rows['customer_id'],
						"comp_name" => $Rows['comp_name'],
						"desg" => $Rows['owner_desg'],
						"name" => $Rows['fname'].' '.$Rows['lname'],
						"mobile" => $Rows['mobile'],
						"email" => $Rows['email'],
						"row" => $Rows
					);
				}
				else {
					$query = $this->_agentPDO->prepare("SELECT * FROM master_agents_desg WHERE desg_email = :email OR desg_customer_id = :customer_id OR desg_mobile = :mobile");
					$query->bindParam(":email", $email);
					$query->bindParam(":customer_id", $email);
					$query->bindParam(":mobile", $email);
					$query_result = $query->execute() or die($this->link->error);
					$MasterAgentsDesgCount = $query->rowCount();
					if ($MasterAgentsDesgCount == 1) {
						$Rows = $query->fetch(PDO::FETCH_ASSOC);
						$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id = '$Rows[customer_id]'");
						$query->execute() or die($this->_agentPDO->error);
						$nRows = $query->fetch(PDO::FETCH_ASSOC);

						return array(
							"login_type" => "admin",
							"customer_id" => $Rows['customer_id'],
							"comp_name" => $nRows['comp_name'],
							"desg" => $Rows['desg'],
							"name" => $Rows['name'],
							"mobile" => $Rows['desg_mobile'],
							"email" => $Rows['desg_email'],
							"row" => $Rows
						);
					}
					else {
						return 401;	
					}	
				}
			}
			else {
				return 401;
			}
		}
		else {
			return 401;
		}
	}

	public function ChangePassword($email, $password) {

		$password = 'setu'.$password;

		$password = md5($password);

		$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=> "16"]);

		$query = $this->_agentPDO->prepare("UPDATE `master_agents` SET `password` = :password WHERE email = :email");

		$query->bindParam(":password", $password);
		$query->bindParam(":email", $email);

		$query_result = $query->execute() or die($this->_agentPDO->error);

		if ($query_result) {
			return 1;
		} else {
			return $query_result;
		}

	}


	public function ChangePasswordViaApp($customer_id, $password) {

		$password = 'setu'.$password;

		$password = md5($password);

		$password = password_hash($password, PASSWORD_BCRYPT, ['cost'=> "16"]);
		try {
			$query = $this->_agentPDO->prepare("UPDATE `master_agents` SET `password` = :password WHERE customer_id = :customer_id");
			$query->bindParam(":password", $password);
			$query->bindParam(":customer_id", $customer_id);
			$query_result = $query->execute() or die($this->_agentPDO->error);

			return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function Chk($password, $chk) {
		
		if (password_verify($password, $chk)) {
			return 1;
		}
		else {
			return 0;
		}
	}

}



?>