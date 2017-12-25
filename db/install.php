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
 * Theme installation process functions and its values.
 *
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Theme_enlightlite install function.
 *
 * @return void
 */
function xmldb_theme_enlightlite_install() {
    global $CFG;

    if (method_exists('core_plugin_manager', 'reset_caches')) {
        core_plugin_manager::reset_caches();
    }
    $loggedin = get_config('moodle', 'frontpageloggedin');
    set_config('marketingSpot1_status', '1', 'theme_enlightlite');
    set_config('mspot1desc', 'lang:aboutusdesc', 'theme_enlightlite');
    set_config('mspot1title', 'lang:aboutus', 'theme_enlightlite');
    $explog = array('6', '2', '5', '7');
    $data = array( 's__frontpageloggedin' => $explog );
    admin_write_settings($data);
    $explog = array('6', '2', '7');
    $data = array( 's__frontpage' => $explog );
    admin_write_settings($data);

    // Set the default background.
    $fs = get_file_storage();

    // Logo.
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlightlite';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid    = get_admin()->id;
    $filerecord->filearea  = 'logo';
    $filerecord->filepath  = '/';
    $filerecord->itemid    = 0;
    $filerecord->filename  = 'logo.png';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlightlite/pix/home/logo.png');

    // Slider images.
    $i = 1;
    $fs = get_file_storage();
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlightlite';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid = get_admin()->id;
    $filerecord->filearea = 'slide1image';
    $filerecord->filepath = '/';
    $filerecord->itemid = 0;
    $filerecord->filename = 'slide1image.jpg';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlightlite/pix/home/slide1.jpg');

    // Footer background Image.
    $fs = get_file_storage();
    $filerecord = new stdClass();
    $filerecord->component = 'theme_enlightlite';
    $filerecord->contextid = context_system::instance()->id;
    $filerecord->userid    = get_admin()->id;
    $filerecord->filearea  = 'footbgimg';
    $filerecord->filepath  = '/';
    $filerecord->itemid    = 0;
    $filerecord->filename  = 'footbgimg.jpg';
    $fs->create_file_from_pathname($filerecord, $CFG->dirroot . '/theme/enlightlite/pix/home/footbgimg.jpg');

}