<?php
//////////////////////////////
// The Hosting Tool
// Admin Area - Packages
// By Jonny H, Julio Montoya <gugli100@gmail.com> Beeznest 2010 Addon support added
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Packages Sub Menu";
		$this->navlist[] = array("Add Packages", "package_add.png", "add");
		$this->navlist[] = array("Edit Packages", "package_go.png", "edit");
		$this->navlist[] = array("Delete Packages", "package_delete.png", "delete");
	}
	
	public function description() {
		return "<strong>Managing Packages</strong><br />
		Welcome to the Package Management Area. Here you can add, edit and delete web hosting packages. Have fun :)<br />
		To get started, choose a link from the sidebar's SubMenu.";	
	}
	
	public function content() { # Displays the page 
		global $main, $style, $db, $billing, $addon;
		
		switch($main->getvar['sub']) {
			default:
				$n = 0;
				if($_POST) {				 
					$exist_billing_cycle = false;
					//var_dump($main->postvar );
					foreach($main->postvar as $key => $value) {
						//echo ($key.' - '.$value).' <br />';
						if($value == "" && !$n && $key != "admin" && substr($key,0,13) != "billing_cycle") {
							$main->errors("Please fill in all the fields: ".$key);							
							$n++;
						}
						if ($main->postvar['type'] == 'paid' && $exist_billing_cycle == false) {
							if (substr($key,0,13) == "billing_cycle") {								
								$exist_billing_cycle = true;
							}	
						}						
					}
					//var_dump($exist_billing_cycle, $n);
					if ($main->postvar['type'] == 'paid' && $exist_billing_cycle == false) {
						$main->errors("Please add a billing cycle first");			
						$n++;	
					}	
						
					if(!$n) {
						foreach($main->postvar as $key => $value) {
							if($key != "name") {
								if($n) {
									$additional .= ",";	
								}
								$additional .= $key."=".$value;
								$n++;
							}
						}
						//var_dump($main->postvar);
						$db->query("INSERT INTO `<PRE>packages` (name, backend, description, type, server, admin, is_hidden, is_disabled, additional, reseller) VALUES('{$main->postvar['name']}', '{$main->postvar['backend']}', '{$main->postvar['description']}', '{$main->postvar['type']}', '{$main->postvar['server']}', '{$main->postvar['admin']}', '{$main->postvar['hidden']}', '{$main->postvar['disabled']}', '{$additional}', '{$main->postvar['reseller']}')");
						$product_id = mysql_insert_id();
						
						$billing_list = $billing->getAllBillingCycles();
						
						foreach($billing_list as $billing_id=>$value) {
							$variable_name = 'billing_cycle_'.$billing_id;
							if (isset($main->postvar[$variable_name])) {
								$sql_insert ="INSERT INTO `<PRE>billing_products` (billing_id, product_id, amount, type) VALUES('{$billing_id}', '{$product_id}', '{$main->postvar[$variable_name]}', '".BILLING_TYPE_PACKAGE."')";
								$db->query($sql_insert);									
							}
						}

						$query = $db->query("SELECT * FROM `<PRE>addons` WHERE status = ".ADDON_STATUS_ACTIVE);
						
						if($db->num_rows($query) > 0) {
							while($data = $db->fetch_array($query)) {		
										
								$variable_name = 'addon_'.$data['id'];
								if (isset($main->postvar[$variable_name]) && $main->postvar[$variable_name] == 'on') {
									$sql_insert ="INSERT INTO `<PRE>package_addons` (addon_id, package_id) VALUES('{$data['id']}', '{$product_id}')";
									$db->query($sql_insert);									
								}
							}						
						}
						$main->errors("Package has been added!");						
					}
				}
				$query = $db->query("SELECT * FROM `<PRE>servers`");
				if($db->num_rows($query) == 0) {
					echo "There are no servers, you need to add a server first!";
					return;
				}
				while($data = $db->fetch_array($query)) {
					$values[] = array($data['name'], $data['id']);	
				}				
				$array['SERVER'] = $main->dropDown("server", $values);
				
				
				//Addon feature added
				$array['ADDON'] = $addon->generateAddonCheckboxes();
				//finish 				
								
				echo $style->replaceVar("tpl/addpackage.tpl", $array);
				break;
				
			case 'edit':
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>packages` WHERE `id` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That package doesn't exist!";	
					} else {
						if($_POST) {
							
							foreach($main->postvar as $key => $value) {
								//if($value == "" && !$n && $key != "admin") {
								
								if($value == "" && !$n && $key != "admin" && substr($key,0,13) != "billing_cycle"  && substr($key,0,5) != "addon" ) {
									$main->errors("Please fill in all the fields!");
									$n++;
								}
							}
							//var_dump($n);
							if(!$n) {
								foreach($main->postvar as $key => $value) {
									if($key != "name" && $key != "backend" && $key != "description" && $key != "type" && $key != "server" && $key != "admin") {
										if($n) {
											$additional .= ",";	
										}
										$additional .= $key."=".$value;
										$n++;
									}
								}
								$db->query("UPDATE `<PRE>packages` SET
										   `name` = '{$main->postvar['name']}',
										   `backend` = '{$main->postvar['backend']}',
										   `description` = '{$main->postvar['description']}',
										   `server` = '{$main->postvar['server']}',
										   `admin` = '{$main->postvar['admin']}',
										   `additional` = '{$additional}',
										   `reseller` = '{$main->postvar['reseller']}',
										   `is_hidden` = '{$main->postvar['hidden']}',
										   `is_disabled` = '{$main->postvar['disabled']}'
										   WHERE `id` = '{$main->getvar['do']}'");
								
								
								//-----Adding billing cycles 
								
								//Deleting all billing_products relationship							
								$query = $db->query("DELETE FROM `<PRE>billing_products` WHERE product_id = {$main->getvar['do']} AND type='".BILLING_TYPE_PACKAGE."' ");
								
								$product_id = $main->getvar['do'];
											
								
								$billing_list = $billing->getAllBillingCycles();
								foreach($billing_list as $billing_id=>$value) {
									$variable_name = 'billing_cycle_'.$billing_id;
									if (isset($main->postvar[$variable_name]) && ! empty($main->postvar[$variable_name]) ) {
											$sql_insert ="INSERT INTO `<PRE>billing_products` (billing_id, product_id, amount, type) VALUES('{$billing_id}', '{$product_id}', '{$main->postvar[$variable_name]}', '".BILLING_TYPE_PACKAGE."')";
											$db->query($sql_insert);									
									}
								}					
								//-----Finish billing cycles
								
								
								//-----Adding addons cycles 
								
								//Deleting all billing_products relationship							
								
								$query = $db->query("DELETE FROM `<PRE>package_addons` WHERE package_id = {$main->getvar['do']} ");
								   
								$query = $db->query("SELECT * FROM `<PRE>addons`");
								$product_id = $main->getvar['do'];
								if($db->num_rows($query) > 0) {
									
									//Add new relations
									while($data = $db->fetch_array($query)) {												
										$variable_name = 'addon_'.$data['id'];
										if (isset($main->postvar[$variable_name]) && ! empty($main->postvar[$variable_name]) ) {
											$sql_insert ="INSERT INTO `<PRE>package_addons` (addon_id, package_id) VALUES('{$data['id']}', '{$product_id}')";
											$db->query($sql_insert);									
										}
									}						
								}								
								//-----Finish billing cycles
								
								
								$main->errors("Package has been edited!");
								$main->done();
							}
						}
						$data = $db->fetch_array($query);
						
						$array['TYPE'] 			= $data['type'];
						$array['BACKEND'] 		= $data['backend'];
						$array['DESCRIPTION'] 	= $data['description'];
						$array['NAME'] 			= $data['name'];
						$array['URL'] 			= $db->config("url");
						$array['ID'] 			= $data['id'];
						
						if($data['admin'] == 1) {
							$array['CHECKED'] = 'checked="checked"';	
						} else {
							$array['CHECKED'] = "";
						}
						if($data['reseller'] == 1) {
							$array['CHECKED2'] = 'checked="checked"';	
						} else {
							$array['CHECKED2'] = "";
						}
						if($data['is_hidden'] == 1) {
							$array['CHECKED3'] = 'checked="checked"';	
						} else {
							$array['CHECKED3'] = "";
						}
						if($data['is_disabled'] == 1) {
							$array['CHECKED4'] = 'checked="checked"';	
						} else {
							$array['CHECKED4'] = "";
						}
						$additional = explode(",", $data['additional']);
						foreach($additional as $key => $value) {
							$me = explode("=", $value);
							$cform[$me[0]] = $me[1];
						}
						global $type;
						$array['FORM'] = $type->acpPedit($data['type'], $cform);
						$query = $db->query("SELECT * FROM `<PRE>servers`");
						while($data_server = $db->fetch_array($query)) {
							$values[] = array($data_server['name'], $data_server['id']);	
						}
						$array['SERVER'] = $array['THEME'] = $main->dropDown("server", $values, $data_server['server']);
						
						
						// Addon feature added						
						$sql = "SELECT addon_id FROM `<PRE>package_addons` WHERE package_id =".$data['id'];
						$query = $db->query($sql);		
						$myresults = array();
						while($data = $db->fetch_array($query)) {
							$myresults[$data['addon_id']]= 1;				
						}						
						$array['ADDON'] = $addon->generateAddonCheckboxes($myresults);						
						
						echo $style->replaceVar("tpl/editpackage.tpl", $array);
					}
				} else {
					$query = $db->query("SELECT * FROM `<PRE>packages`");
					if($db->num_rows($query) == 0) {
						echo "There are no packages to edit!";	
					}
					else {
						echo "<ERRORS>";
						while($data = $db->fetch_array($query)) {
							echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=packages&sub=edit&do='.$data['id'].'"><img src="'. URL .'themes/icons/pencil.png"></a>');
							$n++;
						}
					}
				}
				break;
				
			case 'delete':
				if($main->getvar['do']) {
					
					$db->query("DELETE FROM `<PRE>packages` 		WHERE `id` = '{$main->getvar['do']}'");
					$db->query("DELETE FROM `<PRE>billing_products` WHERE `product_id` = '{$main->getvar['do']}' AND type = '".BILLING_TYPE_PACKAGE."'");
					$db->query("DELETE FROM `<PRE>package_addons`	WHERE `package_id` = '{$main->getvar['do']}'");
									
					$main->errors("Package has been Deleted!");		
				}
				$query = $db->query("SELECT * FROM `<PRE>packages`");
				if($db->num_rows($query) == 0) {
					echo "There are no servers to delete!";	
				} else {
					echo "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=packages&sub=delete&do='.$data['id'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
						$n++;
					}
				}
			break;
		}
	}
}