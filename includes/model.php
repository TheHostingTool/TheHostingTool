<?php

/**
 * Object model 
 * Inspired in the ActiveRecord in Akelos
 * @author	Julio Montoya <gugli100@gmail.com>	Beeznest 2010 
 */

class model {
	
	public $columns;
	public $attributes;
	public $table_name;
	public $primary_key;
	
	/**
	 * Prepares a INSERT query to the database
	 */
	public function save($attributes) {
		global $main, $db; 
		if ($main->checkToken()) {	
			$new_attributes = $this->filterParams($attributes, $this->getColumns());
			$sql = 'INSERT INTO '.$this->getTableName().' '.
					'('.join(', ',array_keys($new_attributes)).') '.
					'VALUES ('.join(',',array_values($new_attributes)).')';
			$db->query($sql);
			return $db->insert_id();
		}
		return false;	    
	}
	
	/**
	 * Builds an update query to hit the DB
	 */
	public function update($attributes) {
		global $main, $db;
		if ($main->checkToken()) {	
			//Remove the primary key id
			unset($attributes['id']);		
			$sql = 'UPDATE '.$this->getTableName().' '.
	        		'SET '.join(', ', $this->getAvailableAttributesQuoted($attributes)) .' '.
	        		"WHERE id ='".$this->getPrimaryKey()."'";
	       	$db->query($sql); 
		}
	}
	
	/**
	 * Deletes a record
	 */
	public function delete() {
		global $main, $db;
		if ($main->checkToken()) {	
			$sql = 'DELETE FROM '.$this->getTableName().' '.        		
	        		"WHERE id ='".$this->getPrimaryKey()."'";
	       	$db->query($sql);
		}
	}
	/**
	 * Gets the current table name
	 */
	public function getTableName() {			
		return "`<PRE>".$this->table_name."`";		
	}
	
	/**
	 * Gets the current primary key
	 */
	public function getPrimaryKey() {
		if(!isset($this->primary_key)){
            $this->setPrimaryKey();
        }
        return $this->primary_key;
	}
	
	/**
	 * Sets a primary key
	 */
	public function setPrimaryKey($id) {
		$this->primary_key = intval($id);
	}
	
	/**
	 * Get columns
	 */
	public function getColumns() {
		return $this->columns;
	}
	
	public function getAvailableAttributesQuoted($attributes) {		
        return $this->getAttributesQuoted($attributes);
    }
    
    public function getAttributesQuoted($attributes_array) {
        $set = array();
        $attributes_array = $this->getSanitizedConditionsArray($attributes_array);                
        foreach (array_diff($attributes_array,array('')) as $k=>$v){
            $set[$k] = $k.'='.$v;
        }
        return $set;
    }
    
    public function getSanitizedConditionsArray($conditions_array) {
    	$result = array();    	
        foreach ($conditions_array as $k=>$v){
           	$k = str_replace(':','',$k); // Used for Oracle type bindings            
            if($this->hasColumn($k)){
                $v = $this->castAttributeForDatabase($k, $v);
                $result[$k] = $v;
            }
        }        
        return $result;    	
    }    
    /**
     * Checks if a column name exists
     * @param	string	column name
     */
    public function hasColumn($column_name) {
    	if(in_array($column_name, $this->getColumns())) {
    		return true;
    	}
    	return false; 
    }
    
    /**
     * Strips the variable
     */
    public function castAttributeForDatabase($k, $v) {
    	global $db;
    	//this function should check the datatype not a priority right now    	
    	return "'".$db->strip($v)."'";    	
    }
    
    /**
     * Set defaults 
     */
    public function setDefaults() {
    	$columns = $this->getColumns();
		$default_list = array();
		foreach($columns as $column) {
			$default_list[$column] = '';
		}
		return $default_list;		
	}
	
	/**
	 * Filter params, remove empty attributes and strips the value
	 */	
	public function filterParams($params, $attributes) {
		global $db;		
		$filtered_params = array();
		foreach($params as $key=>$param) {
			if (in_array($key, $attributes)) {
				$filtered_params[$key]= "'".$db->strip($param)."'";
			}
		}
		//Only accept values with something there		
		$filtered_params = array_diff($filtered_params, array(''));
		return $filtered_params;
	}
	
	
}