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

namespace TheHostingTool\Interfaces;

interface Type {

    // Returns the type's display name
    public static function getName();

    // Returns the unique THT-internal name to use for the type
    public static function getInternalName();

    // Method called every time the cron script is run
    public function cron();

    // Returns per-package type admin config fields in JSON
    public function getPkgFields();

    // Validates the fields provided by getPkgFields
    public function validatePkgFields(&$data);
}
