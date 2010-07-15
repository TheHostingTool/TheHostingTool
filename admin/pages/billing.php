<?php
/* For licensing terms, see /license.txt */

/**
 * 
 * @author 	Julio Montoya <gugli100@gmail.com>	BeezNest 2010	
 */

if(THT != 1){die();}

class page {
	
	public $navtitle;
	public $navlist = array();
							
	public function __construct() {
		$this->navtitle = "Billing Sub Menu";
		
		$this->navlist[] = array("Add Billing cycle", "package_add.png", "add");
		$this->navlist[] = array("Edit Billing cycle", "package_go.png", "edit");
		$this->navlist[] = array("Delete Billing cycle", "package_delete.png", "delete");
	}
	
	public function description() {
		return "<strong>Managing Billing cycles</strong><br />
				Welcome to the Package Management Area. Here you can add, edit and delete web hosting packages. Have fun :)<br />
				To get started, choose a link from the sidebar's SubMenu.";	
	}
	
	public function content() { # Displays the page 
		global $main, $style, $db, $billing;
		switch($main->getvar['sub']) {
			default:
				if($_POST) {
					foreach($main->postvar as $key => $value) {
						if($value == "" && !$n && $key != "admin") {
							$main->errors("Please fill in all the fields!");
							$n++;
						}
					}
					if(!$n) {
						foreach($main->postvar as $key => $value) {
							if($key != "name" && $key != "number_months") {
								if($n) {
									$additional .= ",";	
								}
								$additional .= $key."=".$value;
								$n++;
							}
						}
						
						if ($main->postvar['status'] == 'on') {							
							$main->postvar['status'] = BILLING_CYCLE_STATUS_ACTIVE;
						} else {
							$main->postvar['status'] = BILLING_CYCLE_STATUS_INACTIVE;
						}												
						$billing->create($main->postvar);					
						$main->errors("Biiling cycle has been added!");
					}
				}
				
				for($i = 1; $i<=MAX_NUMBER_MONTHS; $i++) {
					$values[] = array($i,$i);
				}						
				$array['NUMBER_MONTHS'] = $main->dropDown("number_months", $values, '');				
				$array['STATUS'] = $main->createCheckbox('', 'status');
				echo $style->replaceVar("tpl/billing/addbillingcycle.tpl", $array);
				break;
				
			case 'edit':
				if(isset($main->getvar['do'])) {
					$query = $db->query("SELECT * FROM `<PRE>billing_cycles` WHERE `id` = '{$main->getvar['do']}'");
					if($db->num_rows($query) == 0) {
						echo "That billing cycle doesn't exist!";	
					} else {
						if($_POST) {
							foreach($main->postvar as $key => $value) {
								if($value == "" && !$n && $key != "admin") {
									$main->errors("Please fill in all the fields!");
									$n++;
								}
							}
							if(!$n) {
								foreach($main->postvar as $key => $value) {
									if($key != "name" && $key != "number_months") {
										if($n) {
											$additional .= ",";	
										}
										$additional .= $key."=".$value;
										$n++;
									}
								}
								if ($main->postvar['status'] == 'on') {							
									$main->postvar['status'] = BILLING_CYCLE_STATUS_ACTIVE;
								} else {
									$main->postvar['status'] = BILLING_CYCLE_STATUS_INACTIVE;
								}
								$billing->edit($main->getvar['do'],$main->postvar);								
								$main->errors('Billing cycle has been edited!');
								$main->done();
							}							
						}
						
						$data = $db->fetch_array($query);
						$array['ID'] = $data['id'];					
						$array['NAME'] = $data['name'];
												
						for($i = 1; $i<=MAX_NUMBER_MONTHS; $i++) {
							$values[] = array($i,$i);
						}						
						$array['NUMBER_MONTHS'] = $main->dropDown("number_months", $values, $data['number_months']);						
						$array['STATUS'] = $main->createCheckbox('', 'status', $data['status']);
						
						echo $style->replaceVar("tpl/billing/editbillingcycles.tpl", $array);
					}
				} else {
					$query = $db->query("SELECT * FROM `<PRE>billing_cycles`");
					if($db->num_rows($query) == 0) {
						echo "There are no billing cycles to edit!";	
					} else {
						echo "<ERRORS>";
						while($data = $db->fetch_array($query)) {
							echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=billing&sub=edit&do='.$data['id'].'"><img src="'. URL .'themes/icons/pencil.png"></a>');
							$n++;
						}
					}
				}
				break;
				
			case 'delete':
				if($main->getvar['do']) {
					$billing->setPrimaryKey($main->getvar['do']);
					$billing->delete();
					$main->errors("Billing cycles has been Deleted!");		
				}
				$query = $db->query("SELECT * FROM `<PRE>billing_cycles`");
				if($db->num_rows($query) == 0) {
					echo "There are no billing cycles to delete!";	
				} else {
					echo "<ERRORS>";
					while($data = $db->fetch_array($query)) {
						echo $main->sub("<strong>".$data['name']."</strong>", '<a href="?page=billing&sub=delete&do='.$data['id'].'"><img src="'. URL .'themes/icons/delete.png"></a>');
						$n++;
					}
				}
			break;
		}
	}
}
?>
