<?php
//////////////////////////////
// The Hosting Tool
// Import Tool - WHM
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class whmimport {
	
	public $name = "Web Host Manager (WHM)";
	
	public function import() { # Imports or displays. Whatever really..
		global $style;
		global $db;
		global $main;
		global $type;
		
		if(!$_POST) {
			$query = $db->query("SELECT * FROM `<PRE>servers` WHERE `type` = 'whm'");
			while($data = $db->fetch_array($query)) {
				$values[] = array($data['name'], $data['id']);	
			}
			$array['DROPDOWN'] = $main->dropdown("server", $values);
			echo $style->replaceVar("tpl/whmimport/step1.tpl", $array);	
		}
		elseif($_POST) {
			foreach($main->postvar as $key => $value) {
				if($value == "" && !$n) {
					$main->errors("Please fill in all the fields!");
					$n++;
				}
			}
			if(!$n) {
				include(LINK ."servers/whm.php");
				$whm = new whm;
				$userdata = $whm->listaccs($main->postvar['server']);
				foreach($userdata as $data) {
					$pselect = $db->query("SELECT * FROM `<PRE>packages` WHERE `backend` = '{$data['package']}'");
					$usercheck = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['user']}'");
					if($db->num_rows($usercheck) == 0) {
						if($db->num_rows($pselect) == 0) {
							echo "hey!";
							$db->query("INSERT INTO `<PRE>packages` (name,backend,description,type,server,admin) 
																	   VALUES('{$data['package']}',
																			  '{$data['package']}',
																			  'Description Here',
																			  'free',
																			  '{$main->postvar['server']}',
																			  '0')");
						}
						$pidquery = $db->query("SELECT * FROM `<PRE>packages` WHERE `backend` = '{$data['package']}'");
						$piddata = $db->fetch_array($pidquery);
						$finalpackid = $piddata['id'];
						$checkquery = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['user']}'");
						if($db->num_rows($checkquery) == 0) {
							$db->query("INSERT INTO `<PRE>users` (user,email,password,salt,signup,ip) 
																	 VALUES(
																			'{$data['user']}',
																			'{$data['email']}',
																			'',
																			'saltme',
																			'{$data['start_date']}',
																			'')");
							$checkquery = $db->query("SELECT * FROM `<PRE>users` WHERE `user` = '{$data['user']}'");
							$datanewuser = $db->fetch_array($checkquery);
							$db->query("INSERT INTO `<PRE>user_packs` (userid,domain,pid,signup,status,additional) 
																	 VALUES(
																			'{$datanewuser['id']}',
																			'{$data['domain']}',
																			'{$finalpackid}',
																			'{$data['start_date']}',
																			'1',
																			'{$additional}')");
							$n++;
						}
					}
				}
			}
			echo $n ." Accounts have been imported!";
		}
	}
}
?>
