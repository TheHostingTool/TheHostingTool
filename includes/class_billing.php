<?php


//Check if called by script
if(THT != 1){
	die();
}

class billing extends model {
	
	public $columns 	= array('id', 'number_months','name', 'status');
	public $table_name = 'billing_cycles';
	
	public function create($params) { 
		$billing_id = $this->save($params);
		return $billing_id;
	}
	
	public function edit($id, $params) {		
		$this->setPrimaryKey($id);		
		$this->update($params);
	}
	public function delete() {
		parent::delete();
	}	
	
	/**
	 * Generates a select menu with the available addons
	 * @param	array	selected addons
	 * @return 	string	html of the select
	 * @author	Julio Montoya <gugli100@gmail.com> BeezNest 2010
	 */
	public function generateBillingInputs($selected_values = array()) {
		global $db,$main;
		$sql = "SELECT * FROM ".$this->getTableName()." WHERE status = ".BILLING_CYCLE_STATUS_ACTIVE;
		$query = $db->query($sql);		
		$billing_cycle_result = '';
		while($data = $db->fetch_array($query)) {
			$amount = '';
			if (isset($selected_values[$data['id']])) {
				$amount = $selected_values[$data['id']];
			}		
			$billing_cycle_result.= $main->createInput($data['name'].' ('.$db->config('currency').') <br />', 'billing_cycle_'.$data['id'], $amount);													
		}
		return $billing_cycle_result;
	}
	
	public function getAllBillingCycles($status = BILLING_CYCLE_STATUS_ACTIVE) {
		global $db;
		if (!in_array($status, array(BILLING_CYCLE_STATUS_ACTIVE, BILLING_CYCLE_STATUS_INACTIVE))) {
			$status = BILLING_CYCLE_STATUS_ACTIVE;
		}		
		$query = $db->query("SELECT * FROM ".$this->getTableName()." WHERE status = ".$status);
		$billing_list = array();				
		if($db->num_rows($query) > 0) {											
			$billing_cycle_result = '';
			while($data = $db->fetch_array($query)) {		
				$billing_list[$data['id']] = $data;
			}								
		}
		return $billing_list; 		
	}
	
	public function getBilling($id) {
		global $db;
		$id = intval($id);
		$sql = "SELECT * FROM ".$this->getTableName()." WHERE id = ".$id;
		$result = $db->query($sql);
		$data = array();		
		if ($db->num_rows($result) > 0) {
			$data = $db->fetch_array($result);	
		}		
		return $data;
	}
	
}

?>