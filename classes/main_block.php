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
 * This page contains the main body contents.
 *
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Return the set of main center block values in array.
 * @return type|array
 */
function main_block() {
    global $CFG, $PAGE, $OUTPUT, $SITE;
    user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
    require_once($CFG->libdir . '/behat/lib.php');

    if (isloggedin()) {
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

    // Footer Blocks.
    $copyright = theme_enlightlite_get_setting('copyright');
    $copyright = theme_enlightlite_lang($copyright);
    $fb1title = theme_enlightlite_get_setting('footerbtitle1', 'format_html');
    $fb1title = theme_enlightlite_lang($fb1title);
    $fb2title = theme_enlightlite_get_setting('footerbtitle2', 'format_text');
    $fb2title = theme_enlightlite_lang($fb2title);
    $fb3title = theme_enlightlite_get_setting('footerbtitle3', 'format_text');
    $fb3title = theme_enlightlite_lang($fb3title);
    $fb4title = theme_enlightlite_get_setting('footerbtitle4', 'format_text');
    $fb4title = theme_enlightlite_lang($fb4title);
    $footerblink1 = theme_enlightlite_get_setting('footerdesc1');
    $footerblink1 = theme_enlightlite_lang($footerblink1);
    $sociallinks = theme_enlightlite_social_links();
    $footerblink3 = theme_enlightlite_generate_links('footerblink3');
    $footerblink2 = theme_enlightlite_generate_links('footerblink2');
    $backtotopstatus = theme_enlightlite_get_setting('backToTop_status');
    $footerb1 = theme_enlightlite_get_setting('footerb1_status');
    $footerb2 = theme_enlightlite_get_setting('footerb2_status');
    $footerb3 = theme_enlightlite_get_setting('footerb3_status');
    $footerb4 = theme_enlightlite_get_setting('footerb4_status');
    $totalenable = $footerb1 + $footerb2 + $footerb3 + $footerb4;
    $footermain = 1;

    switch($totalenable) {
        case 4 :
            $colclass = 'col-md-3';
        break;

        case 3:
            $colclass = 'col-md-4';
        break;

        case 2:
            $colclass = 'col-md-6';
        break;

        case 1:
            $colclass = 'col-md-12';
        break;

        case 0:
            $footermain = 0;
            $colclass = '';
        break;

        default:
            $colclass = 'col-md-3';
    }
    $footerb4iconclass = theme_enlightlite_footer_address('true');
    $footericonclass = ($footerb4iconclass == "true") ? "footer-small-socials" : "";
    $footeraddress = theme_enlightlite_footer_address();

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
        "copyright" => $copyright,
        "fb1title" => $fb1title,
        "fb2title" => $fb2title,
        "fb3title" => $fb3title,
        "fb4title" => $fb4title,
        "social_links" => $sociallinks,
        "footerblink3" => $footerblink3,
        "footerblink2" => $footerblink2,
        "footerblink1" => $footerblink1,
        "footerb1" => $footerb1,
        "footerb2" => $footerb2,
        "footerb3" => $footerb3,
        "footerb4" => $footerb4,
        "footerAddress" => $footeraddress,
        'footericonclass' => $footericonclass,
        "colClass" => $colclass,
        "footermain" => $footermain,
        "backToTop" => $backtotopstatus,
        "primaryclass" => $class
    ];
    $templatecontext['flatnavigation'] = $PAGE->flatnav;
    return $templatecontext;
}