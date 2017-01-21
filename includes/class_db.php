<?php
/* Copyright © 2014 TheHostingTool
 *
 * This file is part of TheHostingTool.
 *
 * TheHostingTool is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TheHostingTool is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TheHostingTool.  If not, see <http://www.gnu.org/licenses/>.
 */

//Check if called by script
if(THT != 1){die();}

//Create the class
class db {
    private $sql = array(), $con, $prefix, $db; #Variables, only accesible in class

    # Start the functions #

    public function __construct() { # Connect SQL as class is called
        include(LINK."conf.inc.php"); # Get the config
        $this->sql = $sql; # Assign the settings to DB Class
        $this->db = new PDO("mysql:host={$this->sql['host']};dbname={$this->sql['db']};", $this->sql['user'], $this->sql['pass']); #Connect to SQL
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->prefix = $this->sql['pre'];
    }

    private function error($name, $mysqlerror, $func) { #Shows a SQL error from main class
        $error['Error'] = $name;
        $error['Function'] = $func;
        $error['MySQL Error'] = $mysqlerror;
        global $main;
        $main->error($error);
    }

    public function query($sql, $params=null) { # Run any query and return the results
        $sql = preg_replace("/<PRE>/si", $this->prefix, $sql); #Replace prefix variable with right value
    try{
      if(is_array($params)){
        $sql = $this->db->prepare($sql); # Run query
        $sql->execute($params); # Add Parameters
      }else{
        $sql = $this->db->query($sql);
      }
    }catch(PDOException $ex){
      $this->error("MySQL Query Failed", $ex->getMessage(), __FUNCTION__); # CALL ERROR
    }
        return $sql; # Return SQL
    }

    // mysql_insert_id
    public function insert_id() {
        return $this->db->lastInsertId();
    }

    public function num_rows($sql) { # Runs a query and returns the rows
        return $sql->rowCount(); # Return SQL
    }

    public function fetch_array($sql, $all = false, $resultType = PDO::FETCH_BOTH) { # Gets a query and returns the rows/columns as array
    if($all) $sql = $sql->fetchAll($resultType);
    else $sql = $sql->fetch($resultType);
        return $sql; # Return SQL
    }

  ### DEPRECATED! ### DO NOT USE!!!
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
                    #if(function_exists("mysql_real_escape_string")) { # Does mysql real escape string exist?
                    #	  $v = mysql_real_escape_string($v);
                    #}
                    #else { # If all else fails..
                    #	  $v = addslashes($v);
                    #}
                    $array[$k] = $v;
                }
            }
            return $array;
        }
        else {
            if(get_magic_quotes_gpc()) { # Check if Magic Quotes are on
                  $value = stripslashes($value);
            }
            #if(function_exists("mysql_real_escape_string")) { # Does mysql real escape string exist?
            #	  $value = mysql_real_escape_string($value);
            #}
            #else { # If all else fails..
            #	  $value = addslashes($value);
            #}
            return $value;
        }

    }
    public function config($name) { # Returns a value of a config variable
        $query = $this->query("SELECT * FROM `<PRE>config` WHERE `name` = ?", array($name));
        if($this->num_rows($query) == 0) {
            // Legacy version support
            if(strtolower($name) == "vname") {
                return $this->config("version");
            }
            $error['Error'] = "Couldn't Retrieve config value!";
            $error['Config Name'] = $name;
            global $main;
            $main->error($error);
        }
        else {
            $value = $this->fetch_array($query, false);
            return $value['value'];
        }
    }

    public function resources($name) { # Returns a value of a resource variable
        $query = $this->query("SELECT * FROM `<PRE>resources` WHERE `resource_name` = ?", array($name));
        if($this->num_rows($query) == 0) {
            $error['Error'] = "Couldn't Retrieve resource value!";
            $error['Resource Name'] = $name;
            global $main;
            $main->error($error);
        }
        else {
            $value = $this->fetch_array($query, false);
            return $value['resource_value'];
        }
    }

    public function staff($id) { # Returns values of a id
        $id = $this->strip($id);
        $query = $this->query("SELECT * FROM `<PRE>staff` WHERE `id` = ?", array($id));
        if($this->num_rows($query) == 0) {
            $error['Error'] = "Couldn't retrieve staff data!";
            $error['Username'] = $name;
            global $main;
            $main->error($error);
        }
        else {
            $value = $this->fetch_array($query, false);
            return $value;
        }
    }

    public function client($id, $returnErrors = false) { # Returns values of a id
        $id = $this->strip($id);
        $query = $this->query("SELECT * FROM `<PRE>users` WHERE `id` = ?", array($id));
        if($this->num_rows($query) == 0) {
            if($returnErrors) {
                return false;
            }
            $error['Error'] = "Couldn't retrieve client data!";
            $error['Username'] = $name;
            global $main;
            $main->error($error);
        }
        else {
            $value = $this->fetch_array($query);
            $query = $this->query("SELECT * FROM `<PRE>user_packs` WHERE `userid` = ?", array($id));
            $data = $this->fetch_array($query, false);
            $value['domain'] = $data['domain'];
            $value['status'] = $data['status'];
            return $value;
        }
    }

    public function updateConfig($name, $value) { # Updates a config value
        // Who actually wrote this?!
        $name = $this->strip($name);
        $value = $this->strip($value);
        $query = $this->query("UPDATE `<PRE>config` SET `value` = ? WHERE `name` = ?", array($value, $name));
    }

    public function updateResource($name, $value) { # Updates a config value
        // Does not expect input to be safe so we sanitize it.
        # DEP: $name = $this->strip($name);
        # DEP: $value = $this->strip($value);
        $query = $this->query("UPDATE `<PRE>resources` SET `resource_value` = ? WHERE `resource_name` = ?", array($value, $name));
    }

    public function emailTemplate($name = 0, $id = 0) { # Retrieves a email template with name or id
        global $main, $db;
        if($name) {
            $query = $db->query("SELECT * FROM `<PRE>templates` WHERE `name` = ?", array($name));
        }
        elseif($id) {
            $query = $db->query("SELECT * FROM `<PRE>templates` WHERE `id` = ?", array($id));
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
            return $db->fetch_array($query, false);
        }
    }
}
//End SQL
