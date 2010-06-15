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
							
						case "cancel":
							if(!empty($main->getvar['reason'])) {
								$command = $server->cancel($pack['id'], $main->getvar['reason']);
                            }
                            else {
								$command = $server->cancel($pack['id']);
                            }
							if($command == true) {
								$main->errors("User has been cancelled!");
								$main->done();
							}
							else {
								$main->errors($command);
							}
							break;
						
						case "term":
							if(!empty($main->getvar['reason'])) {
								$command = $server->terminate($pack['id'], $main->getvar['reason']);
                            }
                            else {
								$command = $server->terminate($pack['id']);
                            }
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
						$array['SUS'] = "Unsuspend";
						$array['FUNC'] = "unsus";
						$array['IMG'] = "accept.png";
					}
					elseif($pack['status'] == "1") {
						$array['SUS'] = "Suspend";
						$array['FUNC'] = "sus";	
						$array['IMG'] = "exclamation.png";
					}
					elseif($pack['status'] == "3") {
						$array['SUS'] = "<a href='?page=users&sub=validate'>Validate</a>";
						$array['FUNC'] = "none";	
						$array['IMG'] = "user_suit.png";
					}
					elseif($pack['status'] == "4") {
						$array['SUS'] = "Awaiting Payment";
						$array['FUNC'] = "none";	
						$array['IMG'] = "money.png";
					}
					elseif($pack['status'] == "9") {
						$array['SUS'] = "No Action";
						$array['FUNC'] = "none";	
						$array['IMG'] = "cancel.png";
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
							$array2['CLIENTIP'] = $client['ip'];
							$array2['FIRSTNAME'] = $client['firstname'];
							$array2['LASTNAME'] = $client['lastname'];
							$array2['ADDRESS'] = $client['address'];
							$array2['CITY'] = $client['city'];
							$array2['STATE'] = $client['state'];
							$array2['ZIP'] = $client['zip'];
							$array2['COUNTRY'] = $client['country'];
							$array2['PHONE'] = $client['phone'];
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
									$array2['STATUS'] = "Awaiting Validation";
									break;
								
								case "4":
									$array2['STATUS'] = "Awaiting Payment";
									break;
								
								case "9":
									$array2['STATUS'] = "Cancelled";
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
									$main->errors('A password was not provided.');
									$array['BOX'] = "";
									$array['CONTENT'] = $style->replaceVar("tpl/clientpwd.tpl");
								}
								else {
									$command = $main->changeClientPassword($pack['id'], $main->postvar['passwd']);
									if($command === true) {
										$main->errors('Password changed!');
									}
									else {
										$main->errors((string)$command);
									}
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
			
			//Displays a list of users based on account status.
			case "list":
				echo "<div class=\"subborder\"><form id=\"filter\" name=\"filter\" method=\"post\" action=\"\"><select size=\"1\" name=\"show\"><option value=\"all\">ALL</option><option value=\"1\">Active</option><option value=\"0\">Awaiting Validation</option><option value=\"2\">Suspended</option><option value=\"9\">Cancelled</option></select><input type=\"submit\" name=\"filter\" id=\"filter\" value=\"Filter Accounts\" /></form><table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#000000\"><tr bgcolor=\"#EEEEEE\">";
				echo "<td width=\"100\" align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">Date Registered</td><td width=\"100\" align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">Username</td><td align=\"center\" style=\"border-collapse: collapse\" bordercolor=\"#000000\">E-mail</td></tr>";
				$l = $main->getvar['l'];
				$p = $main->getvar['p'];
				if (!$main->postvar['show'] && !$main->getvar['show']) {
					$show = "all";
				}
				if (!$main->postvar['show']) {
					$show = $main->getvar['show'];
				}
				else {
					$show = $main->postvar['show'];
					$p = 0;
				}
				if (!($l)) {
					$l = 10;
				}
				if (!($p)) {
					$p = 0;
				}
				if ($show != "all") {
					$query = $db->query("SELECT * FROM `<PRE>users` WHERE `status` = '$show'");
				}
				else {
					$query = $db->query("SELECT * FROM `<PRE>users`");
				}
				$pages = intval($db->num_rows($query)/$l);
				if ($db->num_rows($query)%$l) {
					$pages++;
				}
				$current = ($p/$l) + 1;
				if (($pages < 1) || ($pages == 0)) {
					$total = 1;
				}
				else {
					$total = $pages;
				}
				$first = $p + 1;
				if (!((($p + $l) / $l) >= $pages) && $pages != 1) {
					$last = $p + $l;
				}
				else{
					$last = $db->num_rows($query);
				}
				if ($show != "all") {
					$query2 = $db->query("SELECT * FROM `<PRE>users` WHERE `status` = '$show' ORDER BY `user` ASC LIMIT $p, $l");
				}
				else {
					$query2 = $db->query("SELECT * FROM `<PRE>users` ORDER BY `user` ASC LIMIT $p, $l");
				}
				if ($db->num_rows($query2) == 0) {
					echo "No accounts found.";
				}
				else {
					while($data = $db->fetch_array($query2)) {
						$array['ID'] = $data['id'];
						$array['USER'] = $data['user'];
						$array['EMAIL'] = $data['email'];
						$array['DATE'] = strftime("%m/%d/%Y", $data['signup']);
					echo $style->replaceVar("tpl/clientlist.tpl", $array);
					}
				}
				echo "</table></div>";
				echo "<center>";
				if ($p != 0) {
					$back_page = $p - $l;
					echo("<a href=\"$PHP_SELF?page=users&sub=list&show=$show&p=$back_page&l=$l\">BACK</a>    \n");
				}

				for ($i=1; $i <= $pages; $i++) {
					$ppage = $l*($i - 1);
					if ($ppage == $p){
						echo("<b>$i</b>\n");
					}
					else{
						echo("<a href=\"$PHP_SELF?page=users&sub=list&show=$show&p=$ppage&l=$l\">$i</a> \n");
					}
				}

				if (!((($p+$l) / $l) >= $pages) && $pages != 1) {
					$next_page = $p + $l;
					echo("    <a href=\"$PHP_SELF?page=users&sub=list&show=$show&p=$next_page&l=$l\">NEXT</a>");
				}
				echo "</center>";
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
				$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `status` = '9'");
				$array['CANCELLED'] = $db->num_rows($query);
				echo $style->replaceVar("tpl/clientstats.tpl", $array);
				break;
				
			case "validate":
				if($main->getvar['do']) {
					if($main->getvar['accept'] == 1) {
						if($server->approve($main->getvar['do'])) {
							$main->errors("Account activated!");
							$emaildata = $db->emailTemplate("approvedacc");
							$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$main->getvar['do']}'");
							$data = $db->fetch_array($query);
							$client = $db->client($data['userid']);
							$db->query("UPDATE `<PRE>users` SET `status` = '1' WHERE `id` = '{$client['id']}'");
							$email->send($client['email'], $emaildata['subject'], $emaildata['content']);
						}
					}
					else {
						$query = $db->query("SELECT * FROM `<PRE>user_packs` WHERE `id` = '{$main->getvar['do']}'");
						$data = $db->fetch_array($query);
						$client = $db->client($data['userid']);
						if($server->decline($main->getvar['do'])) {
							$main->errors("Account declined!");
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
						$array['CLIENTID'] = $data['userid'];
						$tpl .= $style->replaceVar("tpl/adminval.tpl", $array);
					}
					echo $tpl;
				}
				break;
		}
	}
}
?>
