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
 * This page header_block.php returns the header block values.
 *
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

/**
 * Return the set of values for Header Contents.
 * @return type|string
 */
function header_contents() {

    global $CFG, $PAGE, $OUTPUT, $SITE;
    user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
    require_once($CFG->libdir . '/behat/lib.php');
    if (isloggedin() && $PAGE->pagelayout != 'frontpage') {
        $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    } else {
        $navdraweropen = false;
    }
    $extraclasses = [];
    if ($navdraweropen) {
        $extraclasses[] = 'drawer-open-left';
    }
    $primarymenu = $OUTPUT->primarymenu();
    if ($primarymenu == '') {
        $class = "navbar-toggler hidden-lg-up nocontent-navbar";
    } else {
        $class = "navbar-toggler hidden-lg-up";
    }
    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $blockshtml = $OUTPUT->blocks('side-pre');
    $hasblocks = strpos($blockshtml, 'data-block=') !== false;
    $regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
    $surl = new moodle_url('/course/search.php');
    $courserenderer = $PAGE->get_renderer('core', 'course');
    $tcmenu = $courserenderer->top_course_menu();
    $cmenuhide = theme_enlightlite_get_setting('cmenuhide');
    $curl = new moodle_url('/course/index.php');
    $logourl = theme_enlightlite_get_logo_url();
    $topmmenu = $tcmenu['topmmenu'];
    $topcmenu = $tcmenu['topcmenu'];
    $shome = get_string('home');
    $cmenuhide = (!$cmenuhide) ? 0 : 1;
    $scourses = get_string('courses');
    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'output' => $OUTPUT,
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'bodyattributes' => $bodyattributes,
        'navdraweropen' => $navdraweropen,
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
        "curl" => $curl,
        "logourl" => $logourl,
        "topmmenu" => $topmmenu,
        "topcmenu" => $topcmenu,
        "s_home" => $shome,
        "cmenuhide" => $cmenuhide,
        "s_courses" => $scourses,
        'output' => $OUTPUT,
        "primaryclass" => $class,
    ];

    return $templatecontext;
}
$template = header_contents();
$template['flatnavigation'] = $PAGE->flatnav;
$flatnavbar = $OUTPUT->render_from_template('theme_boost/nav-drawer', $template);
