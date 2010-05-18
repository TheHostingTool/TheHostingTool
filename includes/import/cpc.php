<?php
//////////////////////////////
// The Hosting Tool
// Import Tool - cP Creator
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class cpc {
	
	public $name = "cP Creator";
	
	public function import() { # Imports or displays. Whatever really..
		global $style;
		global $db;
		global $main;
		global $type;
		
		if(!$_POST) {
			echo $style->replaceVar("tpl/cpc_step1.tpl");	
		}
		elseif($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "" && !$n) {
					$main->errors("Please fill in all the fields!");
					$n++;
				}
			}
			if(!$n) {
				$cpccon = @mysql_connect($main->postvar['hostname'], $main->postvar['username'], $main->postvar['password'], true);
				if(!$cpccon) {
					echo "mySQL Details incorrect!";
				}
				else {
					$select = @mysql_select_db($main->postvar['database'], $cpccon);
					if(!$select) {
						echo "Couldn't select database!";	
					}
					else {
						$pre = $main->postvar['prefix'];
						$query = mysql_query("SELECT * FROM `{$pre}userpack`", $cpccon);
						if($main->postvar['p2h']) {
							$p2hquery = mysql_query("SELECT * FROM `{$pre}modulesettings` WHERE `name` LIKE 'p2hforum_%'", $cpccon);
							if(mysql_num_rows($p2hquery) != 0) {
								while($p2hdata = mysql_fetch_array($p2hquery)) {
									if(!$n3) {
										$fname = $p2hdata['package'];
										$n3++;
									}
									if($p2hdata['package'] != $fname) {
										$p2hforums[$i]['name'] = $fname;
										$i++;
										$fname = $p2hdata['package'];
									}
									$p2hexplode = explode("_", $p2hdata['name']);
									$p2hforums[$i][$p2hexplode[1]] = $p2hdata['value'];
								}
								$p2hforums[$i]['name'] = $fname;
								foreach($p2hforums as $key => $value) {
									switch($value['forum']) {
										case "ipb":
											$prefix = "ibf_";
											break;
										
										case "mybb":
											$prefix = "mybb_";
											break;
										
										case "phpbb":
											$prefix = "phpbb_";
											break;
											
										case "phpbb2":
											$prefix = "phpbb_";
											break;
											
										case "vb":
											$prefix = "vb_";
											break;
											
										case "smf":
											$prefix = "smf_";
											break;
											
										case "aef":
											$prefix = "aef_";
											break;
									}
									if($db->num_rows($this->queryForums($value['name'])) == 0) {
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;username;:;{$value['name']}', '{$value['username']}')");
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;password;:;{$value['name']}', '{$value['password']}')");
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;database;:;{$value['name']}', '{$value['database']}')");
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;hostname;:;{$value['name']}', '{$value['hostname']}')");
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;prefix;:;{$value['name']}', '{$prefix}')");
										$db->query("INSERT INTO `<PRE>config` (name, value) VALUES('p2hforum;:;type;:;{$value['name']}', '{$value['forum']}')");
										$n2++;
									}
								}
								echo $n2 . " Forums have been imported! <br />";
							}
						}
						if(mysql_num_rows($query) == 0) {
							echo "There are no clients to import!";	
						}
						else {
							while($data = mysql_fetch_array($query)) {
								$uselect = mysql_query("SELECT * FROM `{$pre}users` WHERE `user` = '{$data['user']}'", $cpccon);
								$client = mysql_fetch_array($uselect);
								$pselect = $db->query("SELECT * FROM `<PRE>packages` WHERE `backend` = '{$data['plan']}'");
								$usercheck = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['user']}'");
								if($db->num_rows($usercheck) == 0) {
									if($db->num_rows($pselect) == 0) {
										$sselect = $db->query("SELECT * FROM `<PRE>servers`");
										$server = $db->fetch_array($sselect);
										$db->query("INSERT INTO `<PRE>packages` (name,backend,description,type,server,admin) 
																				   VALUES('{$data['plan']}',
																						  '{$data['plan']}',
																						  'Description Here',
																						  'free',
																						  '{$server['id']}',
																						  '0')");
									}
									if($main->postvar['p2h']) {
										$p2huser = mysql_query("SELECT * FROM `{$pre}p2h` where `user` = '{$data['packuser']}'");
										if(mysql_num_rows($p2huser) != 0) {
											$p2huserdata = mysql_fetch_array($p2huser);
											$additional = "fuser=".$p2huserdata['fuser'];
										}
									}
									$pidquery = $db->query("SELECT * FROM `<PRE>packages` WHERE `backend` = '{$data['plan']}'");
									$piddata = $db->fetch_array($pidquery);
									$finalpackid = $piddata['id'];
									$salt = md5(rand(0,9999999));
									$newpass = md5($client['pass'] . md5($salt));
									$checkquery = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['packuser']}'");
									if($db->num_rows($checkquery) == 0) {
										$db->query("INSERT INTO `<PRE>users` (user,email,password,salt,signup,ip) 
																				 VALUES(
																						'{$data['packuser']}',
																						'{$client['email']}',
																						'{$newpass}',
																						'{$salt}',
																						'{$client['signup']}',
																						'{$client['ip']}')");
										$checkquery = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['packuser']}'");
										$datanewuser = $db->fetch_array($checkquery);
										$db->query("INSERT INTO `<PRE>user_packs` (userid,domain,pid,signup,status,additional) 
																				 VALUES(
																						'{$datanewuser['id']}',
																						'{$data['domain']}',
																						'{$finalpackid}',
																						'{$client['signup']}',
																						'1',
																						'{$additional}')");
										$n++;
									}
								}
							}
							echo $n ." Accounts have been imported!";
						}
					}
				}
			}	
		}
	}
	private function queryForums($name = 0) { # Returns the query for the forums in config table
		global $db;
		if($name) {
			return $db->query("SELECT * FROM `<PRE>config` WHERE `name` LIKE 'p2hforum;:;%;:;{$name}'");
		}
		else {
			return $db->query("SELECT * FROM `<PRE>config` WHERE `name` LIKE 'p2hforum;:;%'");
		}
	}
}
?>