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
		if(isset($_GET['iid'])){
			// Ugly little hack. But it works :D
			$pay = $invoice->pay($_GET['iid'], "client/index.php?page=invoices");
		}
		// List invoices. :)
		$query = $db->query("SELECT * FROM `<PRE>invoices` WHERE `uid` = '{$_SESSION['cuser']}'");
		$array2['list'] = "";
		while($array = $db->fetch_array($query)){
			$array['due'] = strftime("%D", $array['due']);
			$array["paid"] = ($array["is_paid"] == 1 ? "<span style='color:green'>Already Paid</span>" :
			"<span style='color:red'>Unpaid. Due date: {$array['due']}</span>");
			$array["pay"] = ($array["is_paid"] == 0 ? 
			"<a href='index.php?page=invoices&iid={$array['id']}' class='tooltip' title='Pay invoice'><img src='../themes/icons/money.png' alt='Pay' /></a>" :
			"<a class='tooltip' title='Invoice is already paid'><img src='../themes/icons/tick.png' alt='Already paid!' /></a>");
			$array2['list'] .= $style->replaceVar("tpl/invoices/invoice-list-item.tpl", $array);
		}
		$array2['num'] = mysql_num_rows($query);
		echo $style->replaceVar("tpl/invoices/client-page.tpl", $array2);
	}
}
?>
