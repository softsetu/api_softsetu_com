<?php

/**
 * 
 */
class hotelManage extends Database {

	public function selectSingelRowPDO($table, $condition, $where, $PDO) {
        $query = $this->$PDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        //Activity Log
		//act = $this->activity($query);

        
        return $row;
       
    }

    public function selectSingelRow($table, $condition, $where) {
        $query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        
        return $row;
       
    }

    public function FetchAllRecordById($table, $condition, $where) {
    	try {
    		$query = $this->_hotelPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = '".$where."'");
    		$query->execute() or die($this->_hotelPDO->error);
        	$rows = $query->fetchAll(PDO::FETCH_ASSOC);

        	return $rows;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    }

	private function customerId($type) {
		//======== Month Fetch =================//
		date_default_timezone_set('Asia/Kolkata');
		$month = date('F');
		$m = $this->selectSingelRowPDO("master_month", "month", $month, "_hotelPDO");
		$month_prefix = $m['prefix'];
		$year = date('y');
		$month_year = $month_prefix.$year;

		//======== Count Index Fetch =================//
		$index = $this->selectSingelRowPDO("master_counts", "counts_name", "customer_id", "_adminPDO");
		$index_id = $index['counts'];

		//======== Type Fetch =================//
		$type = $this->selectSingelRowPDO("master_module", "id", $type, "_hotelPDO");
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
		$query = $this->_adminPDO->prepare("UPDATE master_counts SET counts = '$index' WHERE counts_name = 'customer_id'");
		$query->execute() or die($this->_adminPDO->error);
	}

	public function CreateLinkNewHotels($ref_id, $comp_name, $fname, $lname, $city, $city_id, $citynm, $email, $mobile, $mobile_cc, $type) {

		$array = $this->customerId($type);

		$customer_Id = $array["customer_id"];
		$pass = 't&t123456';
		$password = md5($pass);

		try {
		
			$query = $this->_hotelPDO->prepare("INSERT INTO `master_hotel`(`customer_id`, `name_hotel_asper_gst`, `pri_fnm`, `pri_lnm`, `email`, `password`, `city_name`, `city`, `city_id`, `mob_num`, `mob_num_cc`, `ref_id`, `hotel_type_id`, `created_by`) VALUES (:customer_id, :comp_name, :fname, :lname, :email, :password, :citynm, :city, :city_id, :mobile, :mobile_cc, :ref_id, :type, :created_by)");
			$query->bindParam(':customer_id', $customer_Id);
			$query->bindParam(':comp_name', $comp_name);
			$query->bindParam(':fname', $fname);
			$query->bindParam(':lname', $lname);
			$query->bindParam(':email', $email);
			$query->bindParam(':password', $password);	
			$query->bindParam(':citynm', $citynm);	
			$query->bindParam(':city', $city);	
			$query->bindParam(':city_id', $city_id);
			$query->bindParam(':mobile', $mobile);
			$query->bindParam(':mobile_cc', $mobile_cc);
			$query->bindParam(':ref_id', $ref_id);
			$query->bindParam(':type', $type);
			$query->bindParam(':created_by', $ref_id);	
			$query->execute() or die($this->_hotelPDO->error);
			// $i=$this->UpdateCount($array['index']);
			// $this->FranchiseMailTo($email, $comp_name, $owner, $this->_agent_id);
			
			return 1;
		} catch (Exception $e) {
			// return $e->getMessage();
			throw $e;
		}
	}

	public function CheckUniqueHoteliers($email, $mobile) {
		try {
			$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE email = '$email' OR mob_num = '$mobile'");
			$query->execute() or die($this->_hotelPDO->error);
			$count = $query->rowCount();
			return $count;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function ShowHotelRegisteredData($email, $mobile) {
		try {
			$query = $this->_hotelPDO->prepare("SELECT * FROM master_hotel WHERE email = '$email' OR mob_num = '$mobile'");
			$query->execute() or die($this->_hotelPDO->error);
			$rows = $query->fetchAll();
			return $rows;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function InsertHotel(array $fields) {
		$result = $this->dataInsert("master_hotel", $fields);
		if (!$result) {
			return 1;
		} else {
			return 0;
		}
	}

	public function insertMaterGroup(array $fields) {
		$result = $this->dataInsert("master_group", $fields);
		if (!$result) {
			return 1;
		} else {
			return 0;
		}
	}

	public function insertMaterHotelGroup(array $fields) {
		$result = $this->dataInsert("master_hotel_group", $fields);
		if (!$result) {
			return 1;
		} else {
			return 0;
		}
	}

	public function createRegistrationLink($name_hotel_asper_gst, $email, $mobile, $ll_num) {
		$password = 't&t'.'123456';
		$password = md5($password);
		$query = $this->link->prepare("INSERT INTO `master_hotel`(name_hotel_asper_gst, email, password, mobile, ll_num) VALUES (?, ?, ?, ?, ?)");
		$query->bind_param("sssii", $name_hotel_asper_gst, $email, $password, $mobile, $ll_num);
		$query_result = $query->execute() or die($this->link->error);
		if ($query_result) {
			return 1;
		} else {
			return 0;
		}
	}

	public function insertMultipleDesg(array $fields) {
		$desg = $fields['Desg'];
		$desg_name = $fields['Desg_name'];
		$desg_mobile = $fields['Desg_mobile'];
		$query = $this->link->prepare("INSERT INTO `master_hotel_desg`(`hotel_id`, `desg`, `desg_name`, `desg_mobile`) VALUES (?, ?, ?, ?)");
		$query->bind_param("issi", $reseller_code, $shop_cat[$i]);
		$query_result = $query->execute() or die($this->link->error);
	}
}





?>