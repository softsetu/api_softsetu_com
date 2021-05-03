<?php

/**

 * 

 */

class DigiClass extends Database

{

	

	public function PromoPdfUpload($main_email, $customer_id, $title, $image_name) {

		$DB = new Database;
		$row = $DB->fetchCount("master_promotions", "customer_id", $customer_id);

		if ($row > 12) {

			return 0;

		} else {
			try {
				$query = $this->_agentPDO->prepare("INSERT INTO `master_promotions`(`customer_id`, `promo_title`, `promo_file`, `created_by`) VALUES (:customer_id, :title, :file, :email)");
			    $query->bindParam(":customer_id", $customer_id);
				$query->bindParam(":title", $title);
				$query->bindParam(":file", $image_name);
				$query->bindParam(":email", $main_email);
			    $query->execute() or die($this->_agentPDO->error);

			    return 1;
			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
	}

	public function ProfileImageUpdate($id, $image_name) {

		try {
			$query = $this->_agentPDO->prepare("UPDATE master_agents_desg SET img = :image_name WHERE id = :id");
		    $query->bindParam(":image_name", $image_name);
		    $query->bindParam(":id", $id);
		    $query->execute() or die($this->_agentPDO->error);

		    return 1;
		} catch (Exception $e) {
			return $e->getMessage();
		}


	}

	public function ViewAllImage($email) {

		$query = $this->_agentPDO->prepare("SELECT * FROM master_image WHERE email = '$email' AND logo_img != '1'");

		$query->execute() or die($this->_agentPDO->error);

		$row = $query->fetchAll(PDO::FETCH_ASSOC);

		return $row;

	} 

	public function LogoImage($email) {

		$query = $this->_agentPDO->prepare("SELECT * FROM master_image WHERE email = '$email' AND logo_img = '1'");

		$query->execute() or die($this->_agentPDO->error);

		$row = $query->fetch(PDO::FETCH_ASSOC);

		return $row;

	}



	public function ProductImageEdit($id, $title, $desc, $image, $logo_img) {



		$DB = new Database;

		$row = $DB->selectSingelRow("master_image", "id", $id);

		$email = $row['email'];



		unlink("../Digi-Card/img/product/".$row['image']);



		if ($logo_img == "1") {

			$upd = $this->_agentPDO->prepare("UPDATE `master_image` SET `logo_img` = '0' WHERE email = '$email'");

			$upd->execute() or die($this->_agentPDO->error);

		}



		try {



			$query = $this->_agentPDO->prepare("UPDATE `master_image` SET `title` = :title, `img_desc` = :img_desc, `image` = :image, `logo_img` = :logo_img WHERE id = :id");

			$query->bindParam(":title", $title);

			$query->bindParam(":img_desc", $desc);

			$query->bindParam(":image", $image);

			$query->bindParam(":logo_img", $logo_img);

			$query->bindParam(":id", $id);

	        $query->execute() or die($this->_agentPDO->error);

	        return 1;



		} catch (Exception $e) {



			throw $e;

			

		}



	}

	public function DeleteThisImage($id) {

		$DB = new Database;

		$data = $DB->selectSingelRow("master_image", "id", $id);



		unlink("../Digi-Card/img/product/".$data['image']);



		try {

			$query = $this->_agentPDO->prepare("DELETE FROM `master_image` WHERE id = '$id'");

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			throw $e;

		}
	}
	public function DeleteThisPDF($id) {

		$DB = new Database;

		$data = $DB->selectSingelRow("master_promotions", "id", $id);

		unlink("../images/product/".$data['promo_file']);
		try {

			$query = $this->_agentPDO->prepare("DELETE FROM `master_promotions` WHERE id = '$id'");

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			throw $e;

		}

	}



	public function ProductImage($customer_id, $title, $image, $logo_img) {

		$DB = new Database;

		$data = $DB->selectSingelRow("agents_digi_profile", "customer_id", $customer_id);

		$row = $DB->fetchCount("master_image", "customer_id", $customer_id);

		// if ($logo_img == "1") {

		// 	$upd = $this->_agentPDO->prepare("UPDATE `master_image` SET `logo_img` = '0' WHERE customer_id = '$customer_id'");

		// 	$upd->execute() or die($this->_agentPDO->error);

		// }



		if ($row > $data['max_img']) {

			return 0;

		} else {

			try {

				$query = $this->_agentPDO->prepare("INSERT INTO `master_image`(`customer_id`,`title`, `image`, `logo_img`) VALUES (:customer_id, :title, :image, :logo_img)");

				$query->bindParam(":customer_id", $customer_id);

				$query->bindParam(":title", $title);

				$query->bindParam(":image", $image);

				$query->bindParam(":logo_img", $logo_img);

		        $query->execute() or die($this->_agentPDO->error);

		        return 1;

			} catch (Exception $e) {

				throw $e;

				

			}

		} //Else Over
	}



	public function UploadImage($customer_id, $file_logo, $slogan) {

		try {



	        $query_select = $this->_agentPDO->prepare("SELECT * FROM images WHERE customer_id = '$customer_id'");

	        $query_select->execute() or die($this->_agentPDO->error);

	        $row = $query_select->fetch(PDO::FETCH_ASSOC);



	        unlink("../upload/logo/".$row['file_logo']);





			$query = $this->_agentPDO->prepare("UPDATE `images` SET file_logo = :file_logo, slogan = :slogan WHERE customer_id = :customer_id");

			$query->bindParam(":file_logo", $file_logo);

			$query->bindParam(":slogan", $slogan);

			$query->bindParam(":customer_id", $customer_id);

			$query->execute() or die($this->_agentPDO->error);

			return 1;

		} catch (Exception $e) {

			throw $e;

		}

	}





	public function checkUniqueId($unique, $email) {



		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE userid = :userid");

			$query->bindParam(":userid", $unique);

			$query->execute() or die($this->_agentPDO->error);

			$num = $query->rowCount();

			

			if ($num == 1) {

				$row = $query->fetch(PDO::FETCH_ASSOC);

				if ($row['email'] == $email) {

					return 1;

				}

				else {

					return 0;

				}

				

			} 

			else {

				return 1;

			}



		} catch (Exception $e) {

			throw $e;

		}



	}



	public function FetchAllPostDesc($table,$condition, $where)	

	{

		

		$query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = '".$where."' ORDER BY id DESC LIMIT 7");

		$query_result = $query->execute();

		$row = $query->fetchAll(PDO::FETCH_ASSOC);

		

		return $row;



	}





	public function review($email) {

		try {

			$query = $this->_agentPDO->prepare("SELECT SUM(rating) AS rate FROM user_post WHERE email = '$email'");

			$query->execute() or die($this->_agentPDO->error);

			$row = $query->fetch(PDO::FETCH_ASSOC);

			$Rating = $row['rate'];



			$count = $this->fetchCount("user_post","email", $email);

			if ($count == 0) {

				return $Array = array("average" => 0, "count" => 0);

			}

			else {

				$average = $Rating / $count;

				return $Array = array("average" => $average, "count" => $count);

			}

			



			

			

		} catch (Exception $e) {

			throw $e;

		}

	}







	public function InsertPost($hid_email, $rating, $review, $user_name) {

		try {

			$query = $this->_agentPDO->prepare("INSERT INTO `user_post`(`email`, `username`, `review`, `rating`) VALUES (:email, :username, :review, :rating)");

			$query->bindParam(':email', $hid_email);

			$query->bindParam(':username', $user_name);

			$query->bindParam(':review', $review);

			$query->bindParam(':rating', $rating);

			$query->execute() or die($this->_agentPDO->error);



			return 1;



		} catch (Exception $e) {

			throw $e;

		}

	}





	public function UpdateDigiProfile($customer_id, $website, $latitude, $longitude, $about_company, $ll_num,$ll_num_cc) {

		try {



			date_default_timezone_set('Asia/Kolkata');

			$udate = date('Y-m-d H:i:s');



			$query = $this->_agentPDO->prepare("UPDATE `agents_digi_profile` SET `ll_num` = :ll_num, `ll_num_cc` = :ll_num_cc, `website` = :website, `latitude` = :latitude,`longitude` = :longitude, `about_company` = :about_company, `updated_at` = :udate WHERE customer_id = :customer_id");

			$query->bindParam(':ll_num', $ll_num);
			$query->bindParam(':ll_num_cc', $ll_num_cc);

			$query->bindParam(':website', $website);

			$query->bindParam(':latitude', $latitude);

			$query->bindParam(':longitude', $longitude);

			$query->bindParam(':about_company', $about_company);

			$query->bindParam(':udate', $udate);

			$query->bindParam(':customer_id', $customer_id);

			$query->execute() or die($this->_agentPDO->error);



			return 1;

		}

		catch(Exception $e) {

			return $e->getMessage();

		}

	}

	

	public function InsertDigiProfile($email, $customer_id) {

		$desg_cust_id = $customer_id.'01';

		$desg = $this->_agentPDO->prepare("INSERT INTO `master_agents_desg`(`customer_id`, `desg_customer_id`, `primary_contact`) VALUES ('$customer_id', '$desg_cust_id', '1')");
		$desg->execute() or die($this->_agentPDO->error);

	
		try {



			$email_id = 't&t'.$email;

			$email_id = md5($email_id);

			$query_update = $this->_agentPDO->prepare("UPDATE `master_agents` SET enc_email = :enc_email WHERE customer_id = :customer_id");

			$query_update->bindParam(":enc_email", $email_id);

			$query_update->bindParam(":customer_id", $customer_id);

			$query_update->execute() or die($this->_agentPDO->error);

				





			$query = $this->_agentPDO->prepare("INSERT INTO `agents_digi_profile` (customer_id) VALUES (:customer_id)");

			$query->bindParam(":customer_id", $customer_id);

			$query->execute() or die($this->_agentPDO->error);



			$queryImage = $this->_agentPDO->prepare("INSERT INTO `images` (customer_id) VALUES (:customer_id)");

			$queryImage->bindParam(":customer_id", $customer_id);

			$queryImage->execute() or die($this->_agentPDO->error);



			return 1;

		} catch (Exception $e) {

			throw $e;

		}

		

	}



	public function DigitalProfileDataFetch($customer_id) {

		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN agents_digi_profile ON master_agents.customer_id = agents_digi_profile.customer_id WHERE (master_agents.customer_id = '$customer_id')");

			$query->execute() or die($this->_agentPDO->error);

			$row = $query->fetch(PDO::FETCH_ASSOC);



			return $row;

		} 

		catch (Exception $e) {

			throw $e;

		} 



	}



	public function PublicDigitalProfileDataFetch($customer_id) {



		try {

			$query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN agents_digi_profile ON master_agents.customer_id = agents_digi_profile.customer_id INNER JOIN master_links ON master_agents.customer_id = master_links.customer_id WHERE (master_agents.customer_id = '$customer_id')");

			$query->execute() or die($this->_agentPDO->error);

			$row = $query->fetch(PDO::FETCH_ASSOC);



			return $row;

		} 

		catch (Exception $e) {

			throw $e;

		} 



	}

}

























?>