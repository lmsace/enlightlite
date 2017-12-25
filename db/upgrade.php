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
 * Theme upgradation process functions and its values.
 *
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Theme_enlightlite upgradation function.
 *
 * @param type|string $oldversion
 * @return type|string
 */
function xmldb_theme_enlightlite_upgrade($oldversion) {
    $loggedin = get_config('moodle', 'frontpageloggedin');
    $aboutus = set_config( 'marketingSpot1_status', '1', 'theme_enlightlite');
    if (empty(get_config('theme_enlightlite', 'mspot1desc')) ) {
        set_config('mspot1desc', 'lang:aboutusdesc', 'theme_enlightlite');
    }
    if (empty(get_config('theme_enlightlite', 'mspot1title')) ) {
        set_config('mspot1title', 'lang:aboutus', 'theme_enlightlite');
    }
    $explog = array('6', '2', '5', '7');
    $data = array( 's__frontpageloggedin' => $explog );
    admin_write_settings($data);
    $explog = array('6', '2', '7');
    $data = array( 's__frontpage' => $explog );
    admin_write_settings($data);

    return true;
}
