<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - General Settings
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Clients Sub Menu";
		$this->navlist[] = array("Search Clients", "magnifier.png", "search");
		$this->navlist[] = array("Client Statistics", "book.png", "stats");
		$this->navlist[] = array("Admin Validate", "user_suit.png", "validate");
	}
	
	public function description() {
		global $db, $main;
		$query = $db->query("SELECT * FROM `<PRE>users` ORDER BY `signup` DESC");
		if($db->num_rows($query) != 0) {
			$data = $db->fetch_array($query);
			$newest = $main->sub("Latest Signup:", $data['user']);
		}
		return "<strong>Clients</strong><br />
		This is the area where you can manage all your clients that have signed up for your service. You can perform a variety of tasks like suspend, terminate, email and also check up on their requirements and stats.". $newest;	
	}
	
	public function content() { # Displays the page 
		global $main;
		global $style;
		global $db;
		global $server;
		global $email;
		global $type;
		switch($main->getvar['sub']) {
			default:
				if($main->getvar['do'] ) {
					$client = $db->client($main->getvar['do']);
					$pack2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$main->getvar['do']}'");
					$pack = $db->fetch_array($pack2);
					switch ($main->getvar['func']) {
						case "sus":
                                                        if(!empty($main->getvar['reason'])) {
                                                            $command = $server->suspend($pack['id'], $main->getvar['reason']);
                                                        }
                                                        else {
                                                            $command = $server->suspend($pack['id']);
                                                        }
							if($command == true) {
								$main->errors("User has been suspended!");	
							}
							else {
								$main->errors($command);
							}
							break;
							
						case "unsus":
							$command = $server->unsuspend($pack['id']);
							if($command == true) {
								$main->errors("User has been unsuspended!");	
							}
							else {
								$main->errors($command);
							}
							break;
							
						case "term":
							$command = $server->terminate($pack['id']);
							if($command == true) {
								$main->errors("User has been terminated!");
								$main->done();
							}
							else {
								$main->errors($command);
							}
							break;
					}
				}
				if($main->getvar['do'] ) {
					$client = $db->client($main->getvar['do']);
					$pack2 = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$main->getvar['do']}'");
					$pack = $db->fetch_array($pack2);
				}
				if($main->getvar['do'] ) {
					if($pack['status'] == "2") {
						$array['SUS'] = "Unsuspend Account";
						$array['FUNC'] = "unsus";
						$array['IMG'] = "accept.png";
					}
					elseif($pack['status'] == "1") {
						$array['SUS'] = "Suspend Account";
						$array['FUNC'] = "sus";	
						$array['IMG'] = "exclamation.png";
					}
					elseif($pack['status'] == "3") {
						$array['SUS'] = "<a href='?page=users&sub=validate'>Admin Validation Page</a>";
						$array['FUNC'] = "none";	
						$array['IMG'] = "user_suit.png";
					}
					else {
						$array['SUS'] = "Other Status";
						$array['FUNC'] = "none";	
						$array['IMG'] = "help.png";	
					}
					$array['ID'] = $main->getvar['do'];
					switch($main->getvar['func']) {
						default:
							$array2['DATE'] = strftime("%D", $client['signup']);
							$array2['EMAIL'] = $client['email'];
							$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$db->strip($pack['pid'])}'");
							$data2 = $db->fetch_array($query);
							$array2['PACKAGE'] = $data2['name'];
							$array2['USER'] = $client['user'];
							$array2['DOMAIN'] = $client['domain'];
							$invoicesq = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$db->strip($client['id'])}' AND `is_paid` = '0'");
							$array2['INVOICES'] = $db->num_rows($invoicesq);
							switch($pack['status']) {
								default:
									$array2['STATUS'] = "Other";
									break;
									
								case "1":
									$array2['STATUS'] = "Active";
									break;
									
								case "2":
									$array2['STATUS'] = "Suspended";
									break;
									
								case "3":
									$array2['STATUS'] = "Awaiting Admin";
									break;
							}
							$class = $type->determineType($pack['pid']);
							$phptype = $type->classes[$class];
							if($phptype->acpBox) {
								$box = $phptype->acpBox();	
								$array['BOX'] = $main->sub($box[0], $box[1]);
							}
							else {
								$array['BOX'] = "";	
							}
							$array['CONTENT'] = $style->replaceVar("tpl/clientdetails.tpl", $array2);
							break;
							
						case "email":
							if($_POST) {
								global $email;
								$email->send($client['email'] ,$main->postvar['subject'], $main->postvar['content']);
								$main->errors("Email sent!");
							}
							$array['BOX'] = "";
							$array['CONTENT'] = $style->replaceVar("tpl/emailclient.tpl");
							break;
						case "passwd":
							if($_POST) {
								if(empty($main->postvar['passwd'])) {
									$main->errors('A password was never provided.');
									break;
								}
								$command = $server->changePwd($pack['id'], $main->postvar['passwd']);
								if($command == true) {
									$main->errors('Password was changed!');
								}
								else {
									$main->errors($command);
								}
							}
							$array['BOX'] = "";
							$array['CONTENT'] = $style->replaceVar("tpl/clientpwd.tpl");
							break;
					}
                                        $array["URL"] = URL;
					echo $style->replaceVar("tpl/clientview.tpl", $array);
				}
				else {
					$array['NAME'] = $db->config("name");
					$array['URL'] = $db->config("url");
					$values[] = array("Admin Area", "admin");
					$values[] = array("Order Form", "order");
					$values[] = array("Client Area", "client");
					$array['DROPDOWN'] = $main->dropDown("default", $values, $db->config("default"));
					echo $style->replaceVar("tpl/clientsearch.tpl", $array);
				}
				break;
				
			case "stats":
				$query = $db->query("SELECT * FROM `<PRE>users`");
				$array['CLIENTS'] = $db->num_rows($query);
				$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `status` = '1'");
				$array['ACTIVE'] = $db->num_rows($query);
				$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `status` = '2'");
				$array['SUSPENDED'] = $db->num_rows($query);
				$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `status` = '3'");
				$array['ADMIN'] = $db->num_rows($query);
				echo $style->replaceVar("tpl/clientstats.tpl", $array);
				break;
				
			case "validate":
				if($main->getvar['do']) {
					if($main->getvar['accept'] == 1) {
						if($server->unsuspend($main->getvar['do'])) {
							$db->query("UPDATE `<PRE>user_packs` SET `status` = '1' WHERE `id` = '{$main->getvar['do']}'");	
							$main->errors("Account activated!");
							$emaildata = $db->emailTemplate("approvedacc");
							$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$main->getvar['do']}'");
							$data = $db->fetch_array($query);
							$client = $db->client($data['userid']);
							$email->send($client['email'], $emaildata['subject'], $emaildata['content']);
						}
					}
					else {
						$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$main->getvar['do']}'");
						$data = $db->fetch_array($query);
						$client = $db->client($data['userid']);
						if($server->terminate($main->getvar['do'])) {
							$main->errors("Account deleted!");
							$emaildata = $db->emailTemplate("declinedacc");
							$email->send($client['email'], $emaildata['subject'], $emaildata['content']);
						}	
					}
				}
				$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `status` = '3'");
				if($db->num_rows($query) == 0) {
					echo "No clients are awaiting validation!";	
				}
				else {
					$tpl .= "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						$client = $db->client($data['userid']);
						$array['USER'] = $client['user'];	
						$array['EMAIL'] = $client['email'];
						$array['DOMAIN'] = $data['domain'];
						$array['ID'] = $data['id'];
						$tpl .= $style->replaceVar("tpl/adminval.tpl", $array);
					}
					echo $tpl;
				}
				break;
		}
	}
}
?>
