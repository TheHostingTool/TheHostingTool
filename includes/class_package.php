<?php
/* For licensing terms, see /license.txt */

//Check if called by script
if(THT != 1){
	die();
}

class package extends model {
	
	public $table_name 	= 'packages';	
	
	/**
	 * Gets all package information by billing cycle
	 * @param	int		package id
	 * @param	int		billing cycle id
	 * @return	array 	package information
	 */
	public function getAllPackagesByBillingCycle($billing_id) {
		global $db;
		$addong_list = array();		
		if (!empty($billing_id)) {		
			$sql = "SELECT a.id, a.name, amount, bc.name  as billing_name  FROM `<PRE>packages` a INNER JOIN `<PRE>billing_products` b ON (a.id = b.product_id) INNER JOIN `<PRE>billing_cycles` bc
					ON (bc.id = b.billing_id) WHERE bc.id = {$billing_id} AND b.type= '".BILLING_TYPE_PACKAGE."'";	
			$addons_billing = $db->query($sql);
			$addong_list = array();
			while($data = $db->fetch_array($addons_billing)) {
				$addong_list[$data['id']] = array('id'=>$data['id'],  'name' => $data['name'], 'amount'=>$data['amount']);									
			}
		}
		return $addong_list;
	}
	
	/**
	 * Gets the package information by billing cycle
	 * @param	int		package id
	 * @param	int		billing cycle id
	 * @return	array 	package information
	 */	 
	public function getPackageByBillingCycle($package_id, $billing_id) {
		global $db;
		$package_info = array();		
		if (!empty($package_id) && !empty($billing_id)) {		
			$sql = "SELECT a.id, a.name, amount, bc.name  as billing_name  FROM `<PRE>packages` a INNER JOIN `<PRE>billing_products` b ON (a.id = b.product_id) INNER JOIN `<PRE>billing_cycles` bc
					ON (bc.id = b.billing_id) WHERE bc.id = {$billing_id} AND a.id = {$package_id} AND b.type= '".BILLING_TYPE_PACKAGE."'";					
			$addons_billing = $db->query($sql);
			$addon_list = array();
			$data = $db->fetch_array($addons_billing);
			$package_info = array('id'=>$data['id'],  'name' => $data['name'], 'amount'=>$data['amount']);
		}
		return $package_info;
	}
	
	/**
	 * Gets package information
	 * @param	int		package id
	 * @return	array 	package information
	 */	 
	public function getPackage($package_id) {
		global $db;
		$sql = "SELECT * FROM ".$this->getTableName()." WHERE `id` = '{$package_id}'";
		$result = $db->query($sql);
		$data = array();
		if ($db->num_rows($result)> 0) {
			$data = $db->fetch_array($result, 'ASSOC');
		}
		return $data;
	}
	
	public function getAllPackages($status = 0) {
		global $db;
		$status = intval($status);
		$sql = "SELECT * FROM ".$this->getTableName()." WHERE `is_hidden` = '{$status}'";
		$result = $db->query($sql);
		$data = array();
		if ($db->num_rows($result)> 0) {
			while($package= $db->fetch_array($result, 'ASSOC')) {
				$data[$package['id']]=$package; 
			}
		}
		return $data;
	}
	
}
