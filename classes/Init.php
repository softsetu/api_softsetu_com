<?php
include 'Config.php';
include 'Database.php';

/**
 * 
 */
class Init extends Database
{
	public $_email, $_Type, $_comp_name, $_customer_id, $MyArray = array();

	function __construct()
	{	
		session_start();
		
		if(!isset($_SESSION['email'])) {
		    header("Location:index"); 
		    // echo $_SESSION['email'];
		} 
		else {
			echo '<input type="hidden" id="session_email" value="'.$_SESSION['email'].'">';
			$DB = new Database;
			$user = $DB->UserDetails($_SESSION['customer_id']);
            if ($user['password'] == '$2y$16$LzcDqpA0bJ7ZyEt58WxRj.1xICOEONQ0ii1YvqXfnu8479I2WRdL.' ) {

			if($_SERVER['PHP_SELF'] !== "/Settings.php" ){
              header('Location: Settings');    
			}
                // print_r($_SERVER);
            }
			// print_r($user);
			echo '<input type="hidden" id="session_mobile" value="'.$user['mobile'].'">';
			echo '<input type="hidden" id="agents_type_id" value="'.$user['agents_type_id'].'">';
			echo '<input type="hidden" id="customer_id" value="'.$_SESSION['customer_id'].'">';
			$this->_email = $_SESSION['email'];
			$this->_customer_id = $_SESSION['customer_id'];
			$this->_Cur_User = $user['owner'];
			$this->_Type = $user['agents_type_id'];
			$this->_comp_name = $user['comp_name'];
			// $this->_customer_id = $user['customer_id'];
			$this->_mobile = $user['mobile'];
			$this->MyArray = $user;

			// if ($user['password'] == ) {
			// 	# code...
			// }
		}
	}
}

$Init = new Init;
$DB = new Database;




?>