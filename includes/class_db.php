<?php
//////////////////////////////
// The Hosting Tool
// Database (mySQL) Class
// By Jonny H
// Released under the GNU-GPL
//////////////////////////////

//Check if called by script
if(THT != 1){die();}

//Create the class
class db {
	private $sql = array(), $con, $prefix, $db; #Variables, only accesible in class
	
	# Start the functions #
	
	public function __construct() { # Connect SQL as class is called
		include(LINK."conf.inc.php"); # Get the config
		$this->sql = $sql; # Assign the settings to DB Class
		$this->con = @mysql_connect($this->sql['host'], $this->sql['user'], $this->sql['pass']); #Connect to SQL
		if(!$this->con) { # If SQL didn't connect
			die("Fatal: Coudn't connect to mySQL, please check your details!");
		}
		else {
			$this->db = @mysql_select_db($this->sql['db'], $this->con); # Select the mySQL DB
			if(!$this->db) {
				die("Fatal: Couldn't select the database, check your db setting!");
			}
			else {
				$this->prefix = $this->sql['pre'];
			}
		}
	}
	
	private function error($name, $mysqlerror, $func) { #Shows a SQL error from main class
		$error['Error'] = $name;
		$error['Function'] = $func;
		$error['mySQL Error'] = $mysqlerror;
		global $main;
		$main->error($error);
	}
	
	public function query($sql) { # Run any query and return the results
		$sql = preg_replace("/<PRE>/si", $this->prefix, $sql); #Replace prefix variable with right value
		$sql = @mysql_query($sql, $this->con); # Run query
		if(!$sql) {
			$this->error("mySQL Query Failed", mysql_error(), __FUNCTION__); # Call Error
		}
		return $sql; # Return SQL
	}
	
	public function num_rows($sql) { # Runs a query and returns the rows
		$sql = mysql_num_rows($sql); # Run query
		return $sql; # Return SQL
	}
	
	public function fetch_array($sql) { # Gets a query and returns the rows/columns as array
		$sql = @mysql_fetch_array($sql); # Fetch the SQL Array, all the data
		return $sql; # Return SQL
	}
	
	public function strip($value) { # Gets a string and returns a value without SQL Injection
		if(is_array($value)) {
			$array = array();
			foreach($value as $k => $v) {
				if(is_array($v)) {
					$array[$k] = $this->strip($v);
				}
				else {
					if(get_magic_quotes_gpc()) { # Check if Magic Quotes are on
						  $v = stripslashes($v); 
					}
					if(function_exists("mysql_real_escape_string")) { # Does mysql real escape string exist?
						  $v = mysql_real_escape_string($v);
					} 
					else { # If all else fails..
						  $v = addslashes($v);
					}
					$array[$k] = $v;
				}
			}
			return $array;
		}
		else {
			if(get_magic_quotes_gpc()) { # Check if Magic Quotes are on
				  $value = stripslashes($value); 
			}
			if(function_exists("mysql_real_escape_string")) { # Does mysql real escape string exist?
				  $value = mysql_real_escape_string($value);
			} 
			else { # If all else fails..
				  $value = addslashes($value);
			}
			return $value;
		}

	}
	
	public function config($name) { # Returns a value of a config variable
		$query = $this->query("SELECT * FROM `<PRE>config` WHERE `name` = '{$name}'");
		if($this->num_rows($query) == 0) {
			$error['Error'] = "Couldn't Retrieve config value!";
			$error['Config Name'] = $name;
			global $main;
			$main->error($error);
		}
		else {
			$value = $this->fetch_array($query);
			return $value['value'];
		}
	}
	
	public function resources($name) { # Returns a value of a resource variable
		$query = $this->query("SELECT * FROM `<PRE>resources` WHERE `resource_name` = '{$name}'");
		if($this->num_rows($query) == 0) {
			$error['Error'] = "Couldn't Retrieve resource value!";
			$error['Resource Name'] = $name;
			global $main;
			$main->error($error);
		}
		else {
			$value = $this->fetch_array($query);
			return $value['resource_value'];
		}
	}
	
	public function staff($id) { # Returns values of a id
		$id = $this->strip($id);
		$query = $this->query("SELECT * FROM `<PRE>staff` WHERE `id` = '{$id}'");
		if($this->num_rows($query) == 0) {
			$error['Error'] = "Couldn't retrieve staff data!";
			$error['Username'] = $name;
			global $main;
			$main->error($error);
		}
		else {
			$value = $this->fetch_array($query);
			return $value;
		}
	}
	
	public function client($id) { # Returns values of a id
		$id = $this->strip($id);
		$query = $this->query("SELECT * FROM `<PRE>users` WHERE `id` = '{$id}'");
		if($this->num_rows($query) == 0) {
			$error['Error'] = "Couldn't retrieve client data!";
			$error['Username'] = $name;
			global $main;
			$main->error($error);
		}
		else {
			$value = $this->fetch_array($query);
			$query = $this->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = '{$value['id']}'");
			$data = $this->fetch_array($query);
			$value['domain'] = $data['domain'];
			$value['status'] = $data['status'];
			return $value;
		}
	}
	
	public function updateConfig($name, $value) { # Updates a config value
		$query = $this->query("UPDATE `<PRE>config` SET `value` = '{$value}' WHERE `name` = '{$name}'");
	}
	
	public function updateResource($name, $value) { # Updates a config value
		$query = $this->query("UPDATE `<PRE>resources` SET `resource_value` = '{$value}' WHERE `resource_name` = '{$name}'");
	}
	
	public function emailTemplate($name = 0, $id = 0) { # Retrieves a email template with name or id
		global $main, $db;
		if($name) {
			$query = $db->query("SELECT * FROM `<PRE>templates` WHERE `name` = '{$this->strip($name)}'");	
		}
		elseif($id) {
			$query = $db->query("SELECT * FROM `<PRE>templates` WHERE `id` = '{$this->strip($id)}'");		
		}
		else {
			$array['Error'] = "No name/id was sent onto the reciever!";
			$main->error($array);
			return;
		}
		if($db->num_rows($query) == 0) {
			$array['Error'] = "That template doesn't exist!";
			$array['Template Name/ID'] = $name . $id;
			$main->error($array);
		}
		else {
			return $db->fetch_array($query);	
		}
	}
}
//End SQL
?>
