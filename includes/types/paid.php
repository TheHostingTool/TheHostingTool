<?php
//////////////////////////////
// The Hosting Tool
// Paid - THT Type
// By Nick, Jimmie Lin, and Kevin M, Jonny, Julio Montoya <gugli100@gmail.com> BeezNest 2010
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class paid {
    public $acpForm = array(), $orderForm = array(), $acpNav = array(), $acpSubNav = array(); # The HTML Forms arrays
	public $signup = true; # Does this type have a signup function?
	public $cron = false; # Do we have a cron?
	public $acpBox = false; # Want to show a box thing?
	public $clientBox = false; # Show a box in client cp?
    public $name = "Paid"; # Human readable name of the package.

        # As Jonny would say... Start the mo trunkin functions #

        public function __construct() { # Assign stuff to variables on creation
			global $main, $db, $invoice,$billing;
			$this->acpNav[] = array("Paid Configuration", "paid", "coins.png", "Paid Configuration");
			
			//Adding billing cycle
			$myresults = array();
			
			if (isset($main->getvar['do']) && is_numeric($main->getvar['do'])) {
					
				$sql = "SELECT billing_id, b.name, amount FROM `<PRE>billing_cycles`  b INNER JOIN `<PRE>billing_products` bp on (bp.billing_id = b.id) WHERE product_id =".$main->getvar['do'];
				$query = $db->query($sql);		
				
				while($data = $db->fetch_array($query)) {
					$myresults[$data['billing_id']] = $data['amount'];				
				}						
			}
			
			$billing_list = $billing->getAllBillingCycles();	
			if (is_array($billing_list) && count($billing_list) > 0) {		
				foreach ($billing_list as $billing_id=>$data) {
					$amount = '';
					if (isset($myresults[$billing_id])) {
						$amount = $myresults[$billing_id];
					}	
					$this->acpForm[] = array($data['name'].' ('.$db->config('currency').')' , $main->createInput('', 'billing_cycle_'.$billing_id, $amount));
				}
			}									
		}
		
		public function acpPage() {
			global $db, $style, $main;
			if($_POST) {
				foreach($main->postvar as $key => $value) {
					if($value == "" && !$n && $key != "password") {
						$main->errors("Please fill in all the fields!");
						$n++;
					}
				}
				if(!$n) {
					if(is_numeric($main->postvar['susdays']) && is_numeric($main->postvar['termdays'])) {
						$db->updateConfig("suspensiondays", $main->postvar['susdays']);
						$db->updateConfig("terminationdays", $main->postvar['termdays']);
						$db->updateConfig("currency", $main->postvar['currency']);
						$db->updateConfig("paypalemail", $main->postvar['paypalemail']);
						$db->updateConfig('paypal_mode', 		$main->postvar['paypal_mode']);
								
						$main->errors("Values have been updated!");
					}
					else {
						$main->errors("Please enter a valid value!");	
					}
				}
			}
			$values[] = array("Pound Sterling","GBP");
			$values[] = array("US Dollars","USD");
			$values[] = array("Australian Dollars","AUD");
			$values[] = array("Canadian Dollars","CAD");
			$values[] = array("Euros","EUR");
			$values[] = array("Yen","JPY");
			$values[] = array("New Zealand Dollar","NZD");
			$values[] = array("Swiss Franc","CHF");
			$values[] = array("Hong Kong Dollar","HKD");
			$values[] = array("Singapore Dollar","SGD");
			$values[] = array("Swedish Krona","SEK");
			$values[] = array("Danish Krone","DKK");
			$values[] = array("Polish Zloty","PLN");
			$values[] = array("Norwegian Krone","NOK");
			$values[] = array("Hungarian Forint","HUF");
			$values[] = array("Czech Koruna","CZK");
			$values[] = array("Israeli Shekel","ILS");
			$values[] = array("Mexican Peso","MXN");
			$array['CURRENCY'] = $main->dropDown("currency", $values, $db->config("currency"));
			$array['SUSDAYS'] = $db->config("suspensiondays");
			$array['TERDAYS'] = $db->config("terminationdays");
			$array['PAYPALEMAIL'] = $db->config("paypalemail");
			
			$selected_id = $db->config('paypal_mode');
			$values=array(0=>'Sandbox',1=>'Live');			
			$array['PAYPAL_MODE'] = $main->createSelect('paypal_mode', $values, $selected_id);
			
			echo $style->replaceVar("tpl/paid/acp.tpl", $array);
		}
	
	public function signup() {
		//Due to when this function is called, had to move it to server class
	}
	public function cron() {
		global $db, $main, $type, $server, $email, $invoice;
		
	}
}
?>