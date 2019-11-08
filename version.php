<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme version page.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// The current component version (Date: YYYYMMDDXX).
$plugin->version = 2019052000;

// This version's maturity level.
$plugin->maturity = MATURITY_STABLE;

// Requires this Moodle version
$plugin->requires  = 2018120300;

// Plugin release version.
$plugin->release = 'v3.7';

// Full name of the plugin.
$plugin->component = 'theme_enlightlite';

// Plugin dependencies and dependencies version.
$plugin->dependencies = [
    'theme_boost' => 2019022600
];
