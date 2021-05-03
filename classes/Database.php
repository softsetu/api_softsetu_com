<?php
// include 'Config.php';
class Database
{
	public $host = DB_HOST;
	public $user = DB_USER;
	public $pass = DB_PASS;
	public $db_agents = DB_AGENTS;
	public $db_hotel = DB_HOTEL;
	public $db_admin = DB_ADMIN;

	public $_agentPDO;
	public $_hotelPDO;
	public $_adminPDO;
	
	
	public function __construct()
	{
		$this->connect();
	}

	private function connect()
	{
		try {
	 
			  # MySQL with PDO_MYSQL
			  $this->_agentPDO = new PDO("mysql:host=$this->host;dbname=$this->db_agents", $this->user, $this->pass);	
			  $this->_agentPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			  //Hotel 
			  $this->_hotelPDO = new PDO("mysql:host=$this->host;dbname=$this->db_hotel", $this->user, $this->pass);
			  $this->_hotelPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  
			  //Admin Connect
			  $this->_adminPDO = new PDO("mysql:host=$this->host;dbname=$this->db_admin", $this->user, $this->pass);
			  $this->_adminPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}
		catch(PDOException $e) {
		 	echo $e->getMessage();
		}
		

	}
	
	public function DBOperation($query, $param = array()) {
        try {
            $stmt = $this->_agentPDO->prepare($query);
            $stmt->execute($param) or die($this->_agentPDO->error);
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function FetchUniversal($query, $param = array()) {
        try {
            $stmt = $this->_agentPDO->prepare($query);
            $stmt->execute($param) or die($this->_agentPDO->error);
            $count = $stmt->rowCount();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array("count" => $count, "rows" => $rows);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
	
	public function DBOperationHotel($query, $param = array()) {
        try {
            $stmt = $this->_hotelPDO->prepare($query);
            $stmt->execute($param) or die($this->_hotelPDO->error);
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function CityApi($keyword) {
        $query = $this->_agentPDO->prepare("SELECT * FROM master_dist WHERE dist LIKE '%$keyword%' OR state LIKE '%$keyword%' ORDER BY dist ASC LIMIT 10");
        $query->execute() or die($this->_agentPDO->error);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function MultipleSelection($table, array $Andfields, array $Orfields) {
        $condition = '';
        foreach ($Andfields as $row => $value) {
            $condition .= $row." = '".$value."' AND ";
        }
        $condition = substr($condition, 0, -5);
        try {
            $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition);
            $query->execute() or die($this->_agentPDO->error);
            $num = $query->rowCount();
            if ($num == 1) {
                $rows = $query->fetch(PDO::FETCH_ASSOC);
                 $array = array(
                'count' => $num,
                'data' => $rows
                 );
            }
            else if($num > 1) {
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                 $array = array(
                'count' => $num,
                'data' => $rows
                );
            }
            else if($num == 0) {

                 $array = array(
                'count' => $num,
                'data' => ''
                );
            }
           

            return $array;

        } catch (Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function fetchAllRecordByIdDistinct($table,$condition, $where,$column)   
    {
        
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where GROUP BY ".$column);

        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;

    }


	public function resetPassword($password, $id) {
        try {

            $query = $this->_agentPDO->prepare("UPDATE master_agents SET password = :password WHERE id = :id");
            $query->bindParam(":password", $password);
            $query->bindParam(":id", $id);
            $query->execute() or die($this->_agentPDO->error);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function reactiveUser($mobile, $reason, $email, $customer_id) {

        $inst = $this->_agentPDO->prepare("INSERT INTO `master_actions`(`mobile`, `customer_id`, `notes`, `action`, `created_by`) VALUES ('$mobile', '$customer_id', $reason', 'Reactive', '$email')");
        $inst->execute() or die($this->_agentPDO->error);


        try {
            $status = 1;
            $query = $this->_agentPDO->prepare("UPDATE master_agents SET status = :status WHERE customer_id = :customer_id");
            $query->bindParam(":status", $status);
            $query->bindParam(":mobile", $customer_id);
            $query->execute() or die($this->_agentPDO->error);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function deactiveUser($mobile, $reason, $email) {

        $inst = $this->_agentPDO->prepare("INSERT INTO `master_actions`(`mobile`, `customer_id`, `notes`, `action`, `created_by`) VALUES ('$mobile', '$customer_id', $reason', 'Reactive', '$email')");
        $inst->execute() or die($this->_agentPDO->error);


        try {
            
            $status = 0;
            $query = $this->_agentPDO->prepare("UPDATE master_agents SET status = :status WHERE customer_id = :customer_id");
            $query->bindParam(":status", $status);
            $query->bindParam(":mobile", $customer_id);
            $query->execute() or die($this->_agentPDO->error);

            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function ProfileCompleted ($customer_id) {
        $row = $this->UserDetails($customer_id);

        $i = 1;
        foreach ($row as $key => $value) {
            if ($value != "") {
                $i++;
            }   
        }
        $per = ($i * 100)/count($row);

        return $per;
    }

	
	public function UserDetails($customer_id) {
        
        $image = $this->_agentPDO->prepare("SELECT * FROM master_image WHERE customer_id= '$customer_id' AND logo_img = '1' ");
        $image->execute();
        $img = $image->rowCount();

        $digi = $this->_agentPDO->prepare("SELECT * FROM agents_digi_profile WHERE customer_id= '$customer_id'");
        $digi->execute();
        $dg = $digi->rowCount();

		try {
            if ($img > 0 && $dg > 0) {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_image ON master_agents.customer_id = master_image.customer_id INNER JOIN agents_digi_profile ON master_agents.customer_id = agents_digi_profile.customer_id  WHERE master_agents.customer_id = '$customer_id' AND master_image.logo_img = '1'");

            }else if($img > 0) {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN master_image ON master_agents.customer_id = master_image.customer_id  WHERE master_agents.customer_id = '$customer_id' AND master_image.logo_img = '1'");
            }
            else if($dg > 0) {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents INNER JOIN agents_digi_profile ON master_agents.customer_id = agents_digi_profile.customer_id  WHERE master_agents.customer_id = '$customer_id'");
            }
            else {
                $query = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE customer_id = '$customer_id'");
            }
			
			$query_result = $query->execute();
			$row = $query->fetch(PDO::FETCH_ASSOC);

			return $row;
		} 
		catch (Exception $e) {
			throw $e;
		} 
	}

	public function fetchCount($table, $condition, $where)
    {
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->rowCount();
        
        return $row;       
    }

    public function fetchSubCount($table, $where)
    {
        $agent = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE ref_id = '$where' AND agents_type_id = '1'");
        $agent->execute() or die($this->_agentPDO->error);
        $agent_count = $agent->rowCount();

        $subFranch = $this->_agentPDO->prepare("SELECT * FROM master_agents WHERE ref_id = '$where' AND agents_type_id = '2'");
        $subFranch->execute() or die($this->_agentPDO->error);
        $subFranch_count = $subFranch->rowCount();
        
        $array = array("agent" => $agent_count, "subFranch" => $subFranch_count);
        return $array;       
    }

	public function selectSingelRow($table, $condition, $where)
    {
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");
        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        //Activity Log
		//act = $this->activity($query);

        
        return $row;
       
    }

    public function selectRowsWithLimit($table, $condition, $where, $id, $limit, $type)
    {
        try {
            $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = '".$where."' ORDER BY ".$id." ".$type." LIMIT ".$limit);
            // SELECT * FROM ".$table." WHERE ".$condition." = '".$where."' ORDER BY ".$id." ".$type." LIMIT ".$limit
            // $query->bindParam(':where', $where);
            $query_result = $query->execute() or die($this->_agentPDO->error);
            $row = $query->fetchAll(PDO::FETCH_ASSOC);

            return $row;
    
        } catch (Exception $e) {
           return $e->getMessage(); 
        }
       
    }

    

	public function fetchAllRecordById($table,$condition, $where)  
    {
        
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");

        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;

    }   
	
	public function fetchAllRecordForAdmin($table)  
    {
        
        $query = $this->_adminPDO->prepare("SELECT * FROM ".$table." ");

        // $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;

    }
	
	public function fetchAllRecordByIdForAdmin($table,$condition, $where)  
    {
        
        $query = $this->_adminPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where");

        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;

    }   
	
    public function fetchAllRecordGroupById($table,$condition, $where)   
    {
        
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = :where ORDER BY type ASC");

        $query->bindParam(':where', $where);
        $query_result = $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;

    }   

	public function fetchAllRecord($table)	{
		
		$query = $this->_agentPDO->prepare("SELECT * FROM ".$table);
		$query_result = $query->execute();
		$row = $query->fetchAll(PDO::FETCH_ASSOC);
				
		return $row;

	}

	public function AgentsMailTo($to, $comp_name, $owner, $franchise, $customer_id) {
		// $to = "somebody@example.com, somebodyelse@example.com";


        $subj = '';
		$subj .= 'Welcome To SoftSetu - The Travel Bridge';
		$msg = '';
		$msg .= '
			<!DOCTYPE html PUBLIC “-//W3C//DTD XHTML 1.0 Transitional//EN” “https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd”>

<html xmlns=“https://www.w3.org/1999/xhtml”>

    <body style="box-sizing: border-box; border: none; padding-right : 32%; padding-left: 32%;margin: 0;">
        
        <section>
            <div style="height: 100px; background: #6e1f4e; display: flex; ">
                <div style="width: 50%;">
                    <img width="213" src="http://property.softsetu.com/Mailer/logo.png">
                </div>
                <div style="width: 50%;position: relative;padding-left: 125px;padding-top: 25px;" >
                    <div id="Franchise" style=" font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 20px; color: rgba(255,255,255,1);">
                        <span>Travel Agent</span>
                    </div>

                    <div id="Travel_Connection" style=" font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 20px; color: rgba(255,255,255,1);">
                        <span>Travel Bridge</span>
                    </div>
                </div>
            </div>
            <div style="position: relative;left: 5%;">
                <div style="left: 0%; top: 25px; position: relative; white-space: nowrap; text-align: left; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 18px; color: rgba(166,124,0,1);">
                    <center><span>A uNique Virtual Bridge between Travel Fraternity</span></center>
                </div>
                <br>
                <div id="Dear_Rajan_Mehta_Plusnet_Commu" style=" top: 60px; position: relative; overflow: hidden; width: 540px;  text-align: left; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 15px; color: rgba(0,101,151,1);">
                    <span>Dear '.$owner.', '.$comp_name.',
                    <br><br>
                    Your customer id is : '.$customer_id.'</span><span style="font-style:normal;font-weight:bold;"></span>
                    <br><span style="color:rgba(0,0,0,1);"><br>We Welcome you as Business Partner on "Softsetu" a uNique Virtual Travel Bridge between Travel Fraternity. The future of travel and tourism after this pandemic around the world.
                    <br><br>
                    Fill up your complete business details for Pocket Digital Synopsis, Website, Travel Bridge, Digital Marketing & Promotions.
                    <br><br>
                    Now, Travel Fraternity and your Customers will see your complete business profile on digital synopsis and website shared by you.
                    <br><br>
                    With the help of Travel Bridge you will able be to explore Suppliers details of Pan India from your mobile or computer, once you are registered with Softsetu.
                    <br><br>
                    **If you register now than you will enjoy Digital Synopsis and website for free till 31-03-2020 (a trial period).</span>
                </div>
            </div>
            <div style="top: 60px;position: relative;">
                <div style=" height: 120px!important; background-color: rgba(245,245,245,1); ">
                    <center>
                    <div style="position:relative;width: 199px;">
                        <img src="http://property.softsetu.com/Mailer/congratulation.png" width="200">
                    </div>
                    <div id="Your_company_login_id_and_pass" style="position: relative; bottom: 10px; width: 351px; white-space: nowrap; text-align: center; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 14px; color: rgba(0,101,151,1);">
                        <center><span>Your company login id and password has been created.<br/>Kindly click on icon to update your profile.</span></center>
                    </div>
                    </center>
                </div>
            </div>


            <div style="position: relative;top: 90px;display: flex;">

                <a style="width: 25%;" href="#"></a>
                <a style="width: 25%;" href="https://agt.softsetu.com?id='.$to.'" target="_blank">
                    <img style="position:relative;left: 50%;transform: translate(-50%);" src="http://property.softsetu.com/Mailer/login.png" width="100">
                </a>
                <a style="width: 25%;" href="mailto:support@softsetu.com">
                    <img style="position:relative;left: 50%;transform: translate(-50%);" src="http://property.softsetu.com/Mailer/support.png" width="100">
                </a>
                <a style="width: 25%;" href="#"></a>
            </div>



            <div style="left:5%;top: 80px; position: relative;  width: 107px; height: 47px; text-align: left; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 15px; color: rgba(0,0,0,1);">
                <span>Thanks<br/>Softsetu</span>
            </div>
            <!--  -->
            <div style="top: 80px; position: relative; display: flex; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 15px; color: white;background: #6e1f4e;width: 100%;height:60px;">
            <div style="width: 80%;">   
                <span style="padding-left: 5%;line-height: 2em;">© Prime Kampus Soft Solutions, Rajkot 2020</span><br>
                <span style="padding-left: 5%;line-height: 2em;">Click <a href="https://softsetu.com" target="_blank"><span style="color: orange;text-decoration: underline;cursor: pointer;">SoftSetu</span></a> to Visit Our Website.</span>
            </div>

                <a href="https://twitter.com" style="width:10%; margin-top: 15px;">
                    <img src="http://property.softsetu.com/Mailer/twitter.png" class="twitter"  style="width: 31.083px; height: 25.085px;"></a>

                <a href="https://facebook.com" style="width:10%; margin-top: 15px;"><img class="facebook" src="http://property.softsetu.com/Mailer/facebook.png" style="width: 34px;height: 27px;"></a>

            </div>

            <div style="left: 5%; position: relative;top: 90px;  width: 550px; height: 51px; line-height: 15px; margin-top: -2px; text-align: left; font-family: Segoe UI; font-style: normal; font-weight: bold; font-size: 11px; color: rgba(0,0,0,1);">
                <span>Disclaimer: This message is for the designated recipient only and may contain privileged, proprietary, or otherwise private information. If you have received it in error, please notify the sender immediately and delete the original. Any other use of the email by you is prohibited.</span>
            </div>
        </section>
        
    </body>
</html>';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: SoftSetu <info@softsetu.com>' . "\r\n";
		// $headers .= 'Cc: myboss@example.com' . "\r\n";

		mail($to,$subj,$msg,$headers);
	}

	public function SubFranchiseMailTo($to, $comp_name, $owner, $franchise) {
		// $to = "somebody@example.com, somebodyelse@example.com";


        $subj = '';
		$subj .= 'Welcome To SoftSetu - Connecting The Travel World';
		$msg = '';
		$msg .= '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
        <tr>
            <td style="padding: 10px 0 ;line-height: 0.5;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr style="line-height: 0;">
                        <td align="center" bgcolor="#70bbd9" style=" color: #153643; font-size: 28px; font-weight: bold;  font-family: Arial, sans-serif;padding-top: 50px;">
                            <p style="color: #fff;font-size: 76px;">SoftSetu</p>
                            <p style="color: #fff;margin-top: -30px;margin-bottom: 50px;font-style: italic;">Connecting The Travel World</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 30px 0px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 20px;text-align: center;">
                                        <b>A uNique virtual bridge between travel partners</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;text-align: justify;">
                                        <p>Dear '.$owner.', '.$comp_name.',</p>
                                        <p>We Welcome you as Travel Agent on "Softsetu" an uNique virtual travel bridge between travel partners. The future of travel and tourism after this pandemic around the world.</p>
                                        <p>Registered yourself with complete details for Pocket Digital Synopsis.</p>
                                        <p>Now, your travel partners, customers, and hoteliers will see your complete business profile on digital synopsis shared by you to them.</p>
                                        <p>Even you will able to explore Pan India Hoteliers/DMC/Cruise Companies and many more travel suppliers details from your mobile or computer if you are registered with Softsetu.</p>
                                        <p>**If you register now than you will enjoy Digital Synopsis for free till 31.12.2020 (a trial period).</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #153643; background-color: #dbdbdb; font-family: Arial, sans-serif; font-size: 30px;text-align: center;padding: 20px;padding-bottom:5px;color: #25252b;">
                                        <b style="color: green;">Congratulations...</b>
                                        <p style="line-height: 1;font-size: 16px;">Your company login id and password has been created. Kindly click on image to update your profile.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="260" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td style="background-color: #fff;">
                                                                <a href="https://agents.softsetu.com/index?id='.$to.'" style="cursor: pointer;text-decoration: none;"><img src="https://www.admin.softsetu.com/process/login.png" alt="" style="display: block; width: 20%; height: auto;padding: 20px;margin-left: 34%;padding-bottom: 0px;" />
                                                                <p style="text-align: center; color: #000;">Login</p></a>
                                                            </td>
                                                        </tr>
                                                      
                                                    </table>
                                                </td>
                                                <td style="font-size: 0; line-height: 0;" width="20">
                                                    &nbsp;
                                                </td>
                                                <td width="260" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td style="background-color: #fff;">
                                                                <a href="mailto:'.$franchise.'" style="cursor: pointer;text-decoration: none;"><img src="https://www.admin.softsetu.com/process/customer-service.png" style="display: block;padding: 20px;width: 20%;padding-bottom: 0px; height: auto;margin-left: 34%;" />
                                                                <p style="color: #000; text-align: center;">Support</p></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                                <tr style="padding-top: 10px; background-color: green">
                                	<td style="color: #153643;background-color: white; font-family: Calibri, sans-serif; font-size: 20px;">
                                        <p style="line-height: 0;">Thanks</p>
                                        <p style="font-size: 18px;line-height: 0">Admin</p>
                                        <p style="line-height: 2px;font-style: italic;">Prime Kampus Soft Solutions</p>
                                    </td>
                                </tr>
                                

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ee4c50" style="padding: 20px 30px 20px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;line-height: 1.5;" width="75%">
                                        &#169; Prime Kampus Soft Solutions, Rajkot 2020<br/>Click 
                                        <a href="#" style="color: #ffffff;"><font color="#ffffff">SoftSetu</font></a> to Visit Our Website.
                                    </td>
                                    <td align="right" width="25%">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                    <a href="http://www.twitter.com/" style="color: #ffffff;">
                                                        <img src="https://www.flaticon.com/svg/static/icons/svg/187/187187.svg" alt="Twitter" width="38" height="38" style="display: block;" border="0" />
                                                    </a>
                                                </td>
                                                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                                <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                    <a href="http://www.twitter.com/" style="color: #ffffff;">
                                                        <img src="https://www.flaticon.com/svg/static/icons/svg/1312/1312139.svg" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                                                    </a>
                                                </td>

                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #153643; background-color: #f9f9f9; font-family: Arial, sans-serif; font-size: 30px;text-align: center;padding-bottom: 20px;padding-top: 5px;padding-bottom:5px; padding-left:20px; padding-right: 20px;color: #25252b;">
                            <p style="line-height: 1.2;font-size: 12px;text-align: justify;">Disclaimer: This message is for the designated recipient only and may contain privileged, proprietary, or otherwise private information. If you have received it in error, please notify the sender immediately and delete the original. Any other use of the email by you is prohibited.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: SoftSetu <info@softsetu.com>' . "\r\n";
		// $headers .= 'Cc: myboss@example.com' . "\r\n";

		mail($to,$subj,$msg,$headers);
	}

    public function Ins($city, $dist, $state) {
        $query = $this->_agentPDO->prepare("INSERT INTO `master_city`(`city`, `dist`, `state`) VALUES ('$city', '$dist', '$state')");
        $query->execute() or die($this->_agentPDO->error);
    }



// set_time_limit(10000000);
// $DB = new Database;

// $data = file_get_contents('city.json');
// $rows = json_decode($data, true);
// $count =  count($rows);
// //State
// foreach ($rows as $keys => $value) {
//     //Dist.
//     foreach ($value as $key => $valuez) {
//         //City
//         foreach ($valuez as $k) {
//             echo '<p style="text-transform: lowercase;text-transform: uppercase;">'.$k.', '.$key.', '.$keys.'</p>';
//             $DB->Ins($k, $key, $keys);
//         }
//     };
// }
    
    public function fetchAgentCount($table, $state, $abbr)
    {
        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE city LIKE '%".$state."%' AND customer_id IN 
            (SELECT agents_biz_type.customer_id FROM agents_biz_type INNER JOIN master_biz_type ON master_biz_type.id = agents_biz_type.master_biz_type_id AND master_biz_type.abbr = '".$abbr."')  GROUP BY comp_name");
        $query_result = $query->execute();
        $row = $query->rowCount();
        return $row;      
    }
    public function fetchAllRecordByIdDesg($table,$condition, $where) {     

        $query = $this->_agentPDO->prepare("SELECT * FROM ".$table." WHERE ".$condition." = '".$where."' ORDER BY name");

        $query->execute() or die($this->_agentPDO->error);

        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        return $row;

    }
	
	public function KeyEncrypt($simple_string) {
        // Store the cipher method 
        $ciphering = "AES-128-CTR"; 

        // Use OpenSSl Encryption method 
        $iv_length = openssl_cipher_iv_length($ciphering); 
        $options = 0; 

        // Non-NULL Initialization Vector for encryption 
        $encryption_iv = '1234567891011121'; 

        // Store the encryption key 
        $encryption_key = "PrimeKampusSoftSolutions-Softsetu"; 

        // Use openssl_encrypt() function to encrypt the data 
        $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 

        return $encryption;
    }

    public function KeyDecrypt($encryption) {
        // Store the cipher method 
        $ciphering = "AES-128-CTR"; 

        $options = 0; 
        // Non-NULL Initialization Vector for decryption 
        $decryption_iv = '1234567891011121'; 

        // Store the decryption key 
        $decryption_key = "PrimeKampusSoftSolutions-Softsetu"; 

        // Use openssl_decrypt() function to decrypt the data 
        $decryption = openssl_decrypt($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
        return $decryption;
    }
	
}
?>
