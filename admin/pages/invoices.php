<?php
//////////////////////////////
// The Hosting Tool
// Client Area - Invoice Management
// By Jimmie Lin
// Released under the GNU-GPL
//////////////////////////////

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
		$query = $db->query("SELECT * FROM `<PRE>invoices`");
		$query2 = $db->query("SELECT * FROM `<PRE>invoices` WHERE `is_paid` = 0");
		$array2['list'] = "";
		while($array = $db->fetch_array($query)){
			$array["paid"] = ($array["is_paid"] == 1 ? "<span style='color:green'>Already Paid</span>" :
			"<span style='color:red'>Unpaid. Due date: {$array['due']}</span>");
			$array["pay"] = ($array["is_paid"] == 0 ? 
			"<a href='index.php?page=invoices&iid={$array['id']}&pay=true' class='tooltip' title='Mark as paid'><img src='../themes/icons/money.png' alt='Pay' /></a>" :
			"<a href='index.php?page=invoices&iid={$array['id']}&unpay=true' class='tooltip' title='Mark as unpaid'><img src='../themes/icons/tick.png' alt='Already paid!' /></a>");
			$array2['list'] .= $style->replaceVar("tpl/invoices/invoice-list-item.tpl", $array);
		}
		$array2['num'] = mysql_num_rows($query);
		$array2['numpaid'] = intval($array2['num']-mysql_num_rows($query2));
		$array2['numunpaid'] = mysql_num_rows($query2);
		echo $style->replaceVar("tpl/invoices/admin-page.tpl", $array2);
	}
}
?>
