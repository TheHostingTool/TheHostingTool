<?php
//////////////////////////////
// The Hosting Tool
// Client Area - Invoice Management
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

class page {
	public function content(){ # Displays the page 
		global $style, $db, $main, $invoice, $server;
		if($_GET['invoiceID']){
			require_once("../includes/paypal/paypal.class.php");
			$paypal = new paypal_class;
			if($paypal->validate_ipn()){
				$invoice->set_paid(mysql_real_escape_string($_GET['invoiceID']));
				$main->errors("Your invoice has been paid!");
				$client = $db->fetch_array($db->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$_SESSION['cuser']}'"));
				if($client['status'] == 2) {
					$server->unsuspend($client['id']);
				}
			}
			else {
				$main->errors("Your invoice hasn't been paid!");
			}
		}
		// List invoices. :)
		$query = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$_SESSION['cuser']}' ORDER BY `id` ASC");
		$array2['list'] = "";
		while($array = $db->fetch_array($query)){
			$array['due'] = strftime("%D", $array['due']);
			$array["paid"] = ($array["is_paid"] == 1 ? "<span style='color:green;font-size:20px;'>Paid</span>" :
			"<span style='color:red;font-size:20px;'>Unpaid</span>");
			$array["pay"] = ($array["is_paid"] == 0 ? 
			'<input type="button" name="pay" id="pay" value="Pay Now" onclick="doswirl(\''.$array['id'].'\')" />' :
			'');
			$array['amount'] = $array['amount']." ".$db->config("currency");
			$array2['list'] .= $style->replaceVar("tpl/invoices/invoice-list-item.tpl", $array);
		}
		$array2['num'] = mysql_num_rows($query);
		echo $style->replaceVar("tpl/invoices/client-page.tpl", $array2);
	}
}
?>
