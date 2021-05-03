<?php



class Sender{
	//Username that is to be used for submission
	var $strUserName;
	//password that is to be used along with username
	var $strPassword;
	//Sender Id to be used for submitting the message
	var $strSenderId;
	//Message content that is to be transmitted
	var $strMessage;
	//Mobile No is to be transmitted
	var $strMobile;
	public function Sender ($username,$password,$senderid,$message,$mobile){
		$this->strUserName = $username;
		$this->strPassword = $password;
		$this->strSenderId= $senderid;
		$this->strMessage=$message; //URL Encode The Message
		$this->strMobile=$mobile;
	}
	
	public function Submit(){
		try{
			//http Url to send sms.
			$url="https://www.smsidea.co.in/smsstatuswithid.aspx";
			$fields = array(
			'mobile' => $this->strUserName,
			'pass' => $this->strPassword,
			'senderid' => $this->strSenderId,
			'to' => $this->strMobile,
			'msg' => urlencode($this->strMessage),
			'restype' => 'json'
			);
			$fields_string = '';
			//url-ify the data for the POST
			foreach($fields as $key=>$value) {
				$fields_string .=
					$key.'='.$value.'&'; 
			}
				rtrim($fields_string, '&');
			//open connection
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			//execute post
			$result = curl_exec($ch);
			//close connection
			curl_close($ch);
		}
		catch(Exception $e){
			echo 'Message:' .$e->getMessage();
		}
	}
}



?>