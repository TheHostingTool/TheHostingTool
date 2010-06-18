<?php
//////////////////////////////
// The Hosting Tool
// Email functions class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class email {
	
	private $method, $details = array(), $email = array();
	
	public function __construct() { # When class is made, retrieves all details like sending method, details.
		global $db, $main;
		if(INSTALL == 1) {
			$this->method = $db->config("emailmethod");
			$this->details['from'] = $db->config("emailfrom");
			$query = $db->query("SELECT * FROM `<PRE>config` WHERE `name` LIKE 'smtp_%'");
			if($db->num_rows($query) == 0) {
				$array['Error'] = "SMTP Values can't be found";
				$array['Details'] = "The SMTP records in the DB don't exist!";
				$main->error($array);
			}
			else {
				while($data = $db->fetch_array($query)) {
					$this->details[$data['name']] = $data['value'];	
				}
			}
		}
	}
	
	private function phpmail() { # Sends the email using PHP Mail
		$headers = "From: ".$this->details['from']."\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n" .
				"MIME-Version: 1.0\r\n" .
				"Content-Type: text/html; charset=utf-8\r\n" .
				"Content-Transfer-Encoding: 8bit\r\n\r\n";
		mail($this->email['to'],$this->email['subject'],$this->email['content'],$headers);
	}
	
	private function smtp() { # Sends the email using SMTP Auth PEAR
		require_once LINK."pear/Mail.php";
		
		$from = $this->details['from'];
		$to = $this->email['to'];
		$subject = $this->email['subject'];
		$body = $this->email['content'];
		
		$host = $this->details['smtp_host'];
		$username = $this->details['smtp_user'];
		$password = $this->details['smtp_password'];
		
		$headers = array ('From' => $from,
		  'To' => $this->email['to'],
		  'Subject' => $this->email['subject']);
		$smtp = Mail::factory('smtp',
		  array ('host' => $host,
			'auth' => true,
			'username' => $username,
			'password' => $password));
		
		$mail = $smtp->send($to, $headers, $body);
		
		if (PEAR::isError($mail)) {
			global $main;
			$array['Error'] = "SMTP Failed!";
			$array['Details'] = $mail->getMessage();
		 	$main->error($array);
		 }
	}
	
	public function send($to, $subject, $content, $array = 0) { # Gets the content, edits the class vars and sends to right function
		$this->email['to'] = strtolower($to);
		if($array != 0) {
			$this->email['content'] = $this->parseEmail($content, $array);
		}
		else {
			$this->email['content'] = $content;	
		}
		$this->email['subject'] = $subject;
		$method = $this->method;
		if($method == "php") {
			$this->phpmail();	
		}
		elseif($method == "smtp") {
			$this->smtp();	
		}
		else {
			global $main;
			$array['Error'] = "Email method not found!";
			$array['What happened'] = "The script couldn't found what way the host wants to send the email";
			$array['What to do'] = "Please report this to the host immediately!";
			$main->error($array);
		}
	}
	
	public function staff($subject, $content, $array = 0) { # Sends every staff member a email with the chosen content
		global $db;
		$query = $db->query("SELECT * FROM `<PRE>staff`");
		while($data = $db->fetch_array($query)) {
			$this->send($data['email'], $subject, $content, $array);	
		}
	}
	
	private function parseEmail($content, $array) { # Retrieves the array and replaces all the email variables with the content
		foreach($array as $key => $value) {
			$content = preg_replace("/%". $key ."%/si", $value, $content);
		}
		return $content;
	}
}
?>