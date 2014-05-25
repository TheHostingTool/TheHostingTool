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

class Paid implements \TheHostingTool\Interfaces\Type {
    const DISPLAY_NAME = "Paid";
    const INTERNAL_NAME = "paid";

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
        return false;
    }

    public function validatePkgFields(&$data) {
        return true;
    }
}
