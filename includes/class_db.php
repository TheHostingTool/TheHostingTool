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
	
	/**
	 * Returns the error number from the last operation done on the database server.
	 * @param resource $connection (optional)	The database server connection, for detailed description see the method query().
	 * @return int								Returns the error number from the last database (operation, or 0 (zero) if no error occurred.
	 */
	public static function errno($connection = null) {
		return self::use_default_connection($connection) ? mysql_errno() : mysql_errno($connection);
	}
	
	private static function use_default_connection($connection) {
		return !is_resource($connection) && $connection !== false;
	}
	
	/**
	 * Returns the error text from the last operation done on the database server.
	 * @param resource $connection (optional)	The database server connection, for detailed description see the method query().
	 * @return string							Returns the error text from the last database operation, or '' (empty string) if no error occurred.
	 */
	public static function error_mysql($connection = null) {
		return self::use_default_connection($connection) ? mysql_error() : mysql_error($connection);
	}
	
	/** 
	 * Runs any query and return the results 
	 * @param 	string 		sql query
	 * @return 	resource 	the mysql_query return 
	 * @author 	Julio Montoya <gugli100@gmail.com> BeezNest 2010 - Added some nice error reporting
	 */
	public function query($sql) { 
		$sql = preg_replace("/<PRE>/si", $this->prefix, $sql); #Replace prefix variable with right value		
		$result = mysql_query($sql, $this->con);
			
		if(!$result) {	
			//$this->error("mySQL Query Failed", mysql_error(), __FUNCTION__); # Call Error
								
			$backtrace = debug_backtrace(); // Retrieving information about the caller statement.
			if (isset($backtrace[0])) {
				$caller = & $backtrace[0];
			} else {
				$caller = array();
			}
			if (isset($backtrace[1])) {
				$owner = & $backtrace[1];
			} else {
				$owner = array();
			}
			if (empty($file)) {
				$file = $caller['file'];
			}
			if (empty($line) && $line !== false) {
				$line = $caller['line'];
			}
			$type		= $owner['type'];
			$function 	= $owner['function'];
			$class 		= $owner['class'];			
			
			if (!empty($line)) {			
				//echo $info;
				$error['Database error number'] 	= self::errno($this->con);
				$error['Database error message']	= self::error_mysql($this->con).'<br />';
				
				$error['Query'] = $sql;
				$error['File'] 	= $file;
				$error['Line'] 	= $line;
				if (empty($type)) {
					if (!empty($function)) {
						$error['Function'] = '<br />' . $function;
					}
				} else {
					if (!empty($class) && !empty($function)) {
						$error['Class']  = $class;
						$error['Method'] = $function;
					}
				}
				global $main;
				$main->error($error);
			}	
		}
		return $result; # Return mySQL result
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
