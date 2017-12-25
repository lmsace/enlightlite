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
 * A double column layout.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


require_once($CFG->dirroot."/theme/enlightlite/classes/header_block.php");
$headervalues = header_contents();
require_once($CFG->dirroot."/theme/enlightlite/classes/main_block.php");
$mainblock = main_block();
require_once($CFG->dirroot."/theme/enlightlite/classes/footer_block.php");
$footer = footer_template();
$check = array_merge($mainblock, $headervalues);
$fulltemplate = array_merge($check, $footer);
$OUTPUT->doctype();
echo $OUTPUT->render_from_template('theme_enlightlite/columns2', $fulltemplate);