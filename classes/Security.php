<?php


/**
 * 
 */
class Security extends Database
{
	
	// Function to generate OTP 
	public function generateNumericOTP($n) { 
	      
	    // Take a generator string which consist of 
	    // all numeric digits 
	    $generator = "1357902468"; 
	  
	    // Iterate for n-times and pick a single character 
	    // from generator and append it to $result 
	      
	    // Login for generating a random character from generator 
	    //     ---generate a random number 
	    //     ---take modulus of same with length of generator (say i) 
	    //     ---append the character at place (i) from generator to result 
	  
	    $result = ""; 
	  
	    for ($i = 1; $i <= $n; $i++) { 
	        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
	    } 
	  
	    // Return result 
	    return $result; 
	} 

	public function SendAndSaveOTP($mobile, $email, $customer_id, $otp) {

		$upd = $this->_agentPDO->prepare("UPDATE master_otp SET status = '0' WHERE mobile = '$mobile' AND customer_id = '$customer_id'");
		$upd->execute() or die($this->_agentPDO->error);

		try {
			$query = $this->_agentPDO->prepare("INSERT INTO master_otp (mobile, email, customer_id, otp) VALUES (:mobile, :email, :customer_id, :otp)");
			$query->bindParam(":mobile", $mobile);
			$query->bindParam(":email", $email);
			$query->bindParam(":customer_id", $customer_id);
			$query->bindParam(":otp", $otp);
			$query->execute() or die($this->_agentPDO->error);
			return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function Check($customer_id) {

		try {
			$query = $this->_agentPDO->prepare("SELECT * FROM master_otp WHERE mobile = '$customer_id' AND status = '1' ORDER BY id DESC LIMIT 1");
			$query->execute() or die($this->_agentPDO->error);
			$row = $query->fetch(PDO::FETCH_ASSOC);
			return array("count" => $query->rowCount(), "row" => $row);
		} catch (Exception $e) {
			return $e->getMessage();
		}

	}

	public function UpdateAllDataWithOtp($customer_id, $comp_name_edit, $fname_edit, $lname_edit, $email_edit, $mobile_edit, $org_type, $gst_edit, $pan_edit, $adhar_edit, $address_edit) {

		date_default_timezone_set("Asia/Kolkata");
		$upd = date("Y-m-d H:i:s");

		try {
				
			$query = $this->_agentPDO->prepare("UPDATE `master_agents` SET `email` = :email, `mobile` = :mobile, `comp_name` = :comp_name, `fname` = :fname, `lname` = :lname, `org_type` = :org_type, `gst` = :gst , `pan`= :pan, `adhar` = :adhar , `address` = :address, `updated_at` = :upd WHERE customer_id = :customer_id");
			$query->bindParam(":email", $email_edit);
			$query->bindParam(":mobile", $mobile_edit);
			$query->bindParam(":comp_name", $comp_name_edit);
			$query->bindParam(":fname", $fname_edit);
			$query->bindParam(":lname", $lname_edit);
			$query->bindParam(":org_type", $org_type);
			$query->bindParam(":gst", $gst_edit);
			$query->bindParam(":pan", $pan_edit);
			$query->bindParam(":adhar", $adhar_edit);
			$query->bindParam(":address", $address_edit);
			$query->bindParam(":upd", $upd);
			$query->bindParam(":customer_id", $customer_id);	
			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}



}




?>