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

namespace TheHostingTool\Types;

class Post2Host implements \TheHostingTool\Interfaces\Type {
    const DISPLAY_NAME = "Post2Host";
    const INTERNAL_NAME = "p2h";

    public static function getName() {
        return self::DISPLAY_NAME;
    }

    public static function getInternalName() {
        return self::INTERNAL_NAME;
    }

    public function cron() {
        //
    }

    public function getPkgFields() {
        return array(
            array("id" => "signup", "name" => "Signup Posts", "type" => "number"),
            array("id" => "monthly", "name" => "Monthly Posts", "type" => "number"),
            array("id" => "forum", "name" => "Forum", "type" => "select", "options" => array(
                "tstfrm" => "Test Forum", "anthrfrm" => "Another Forum"
            ), "default" => "anthrfrm")
        );
    }

    public function validatePkgFields(&$data) {
        // Ensure signup and monthly posts are both set and numbers
        if(!isset($data["signup"]) || !ctype_digit($data["signup"])) {
            return "Invalid input for Signup Posts.";
        }
        if(!isset($data["monthly"]) || !ctype_digit($data["monthly"])) {
            return "Invalid input for Monthly Posts.";
        }

        // Ensure forum is available
        return true;
    }
}
