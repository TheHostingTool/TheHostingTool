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

// Are we being called by the script?
if(THT != 1){die();}

// MAIN SQL CONFIG - Change values accordingly
$sql['host'] = '%HOST%'; #The MySQL Host, usually default - localhost
$sql['user'] = '%USER%'; #The MySQL Username
$sql['pass'] = '%PASS%'; #The MySQL Password
$sql['db'] = '%DB%'; #The MySQL DB, remember to have your username prefix
$sql['pre'] = '%PRE%'; #The MySQL Prefix, usually default unless otherwise

// Password Configuration
// Passwords use BCrypt for added security. Below is the cost factor configuration setting.
// It is recommended that you do not change this setting unless you know what you are doing.
$sec['cost'] = 11;

// LEAVE
$sql['install'] = %TRUE%;
