<?php
// The Hosting Tool
// Client Area - Invoice Management
// By Jimmie Lin
// Date + UI improvements Julio Montoya <gugli100@gmail.com> Beeznest
// Released under the GNU-GPL

//Check if called by script
if(THT != 1){die();}

class page {
	public function content(){ # Displays the page 
		global $style, $db, $main, $invoice;
		if(isset($_GET['iid']) and isset($_GET['pay'])){
			$invoice->set_paid($_GET['iid']);
			echo "<span style='color:green'>Invoice #{$_GET['iid']} marked as paid. <a href='index.php?page=invoices&iid={$_GET['iid']}&unpay=true'>Undo this action</a></span>";
		}
		elseif(isset($_GET['iid']) and isset($_GET['unpay'])){
			$invoice->set_unpaid($_GET['iid']);
			echo "<span style='color:red'>Invoice {$_GET['iid']} marked as unpaid. <a href='index.php?page=invoices&iid={$_GET['iid']}&pay=true'>Undo this action</a></span>";
		}
		// List invoices. :)
		$query = $db->query("SELECT * FROM `<PRE>invoices` ORDER BY id DESC");
		$query2 = $db->query("SELECT * FROM `<PRE>invoices` WHERE `is_paid` = 0 ");
		$array2['list'] = "";

		while($array = $db->fetch_array($query)){
			//Getting the user info
			$sql = "SELECT user, firstname, lastname FROM `<PRE>users` WHERE `id` = ".$array["uid"];
			$query_users 		= $db->query($sql);
			$user_info  		= $db->fetch_array($query_users);
			$array['userinfo'] =  $user_info['lastname'].', '.$user_info['firstname'].' ('.$user_info['user'].')';
			$array['due'] = strftime("%D", $array['due']);
			
			//Getting the domain info
			$sql = "SELECT domain FROM `<PRE>user_packs` WHERE `userid` = ".$array["uid"];
			$query_domain 		= $db->query($sql);
			$domain_info  		= $db->fetch_array($query_domain);
			$array['domain'] 	= $domain_info['domain'];
	
			//Amount
			$array['amount'] = $array['amount']." ".$db->config("currency");			

			//Paid configuration
			$array["paid"] = ($array["is_paid"] == 1 ? "<span style='color:green'>Already Paid</span>" :
			"<span style='color:red'>Unpaid <br />Due date: {$array['due']}</span>");
			$array["pay"] = ($array["is_paid"] == 0 ? 
			"<img src='../themes/icons/tick.png' 	alt='Pay' /> <a href='index.php?page=invoices&iid={$array['id']}&pay=true' title='Mark as paid'>Mark as paid </a>" :
			"<img src='../themes/icons/cancel.png'  alt='Already paid!' /> <a href='index.php?page=invoices&iid={$array['id']}&unpay=true' title='Mark as unpaid'>Mark as unpaid</a>");

			$array2['list'] .= $style->replaceVar("tpl/invoices/invoice-list-item.tpl", $array);
		}
		$array2['num'] = mysql_num_rows($query);
		$array2['numpaid'] = intval($array2['num']-mysql_num_rows($query2));
		$array2['numunpaid'] = mysql_num_rows($query2);
		echo $style->replaceVar("tpl/invoices/admin-page.tpl", $array2);
	}
}
?>
