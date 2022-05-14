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
 * Slideshow values and contents.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$themeurl = theme_enlightlite_theme_url();
$slidecontent = "";


/**
 * Return's the general configuration values of slideshow.
 * @return type|array
 */
function slideshow_general_config() {

    $general = array();
    $general['slideshowStatus'] = theme_enlightlite_get_setting('slideshowStatus');
    $autoslideshow = theme_enlightlite_get_setting('autoslideshow');
    $slideinterval = theme_enlightlite_get_setting('slideinterval');
    $slideinterval = intval($slideinterval);
    $general['slideinterval'] = empty($slideinterval) ? 3000 : $slideinterval;
    if ($autoslideshow == 1) {
        $general["autoslideshow"] = 'true';
    } else {
        $general["autoslideshow"] = 'false';
    }
    return $general;
}


/**
 * Returns the maincontent of the slideshow.
 * @param type|array $general
 * @return type|string
 */
function slideshow_body($general) {
    $data = array();
    $sliderlevel = 0;
    $slidedata = [];
    for ($s1 = 1; $s1 <= 3; $s1++) {
        $status = theme_enlightlite_get_setting('slide' . $s1 . 'status');
        $slideconfig = slideshow_slide_settings($general, $s1);
        $slidedata[] = $slideconfig;
        $sliderlevel++;
    }
    $data['sliderLevel'] = $sliderlevel;
    $data['slides'] = $slidedata;
    $data['numberofslides'] = ($sliderlevel > 1) ? true : false;
    return $data;
}

/**
 * Returns the slideshow slide settings value.
 * @param array $general
 * @param string $s1
 * @return array
 */
function slideshow_slide_settings($general = array(), $s1 = "") {
    if (!empty($s1) && !empty($general)) {
        $slide['slideStatus'] = theme_enlightlite_get_setting('slide' . $s1 . 'status');
        $slide['slideurl1'] = theme_enlightlite_get_setting('slide' . $s1 . 'url1');
        $slide['slideimg'] = theme_enlightlite_render_slideimg($s1, 'slide' . $s1 . 'image');
        $slide['contentPosition'] = theme_enlightlite_get_setting('slide'. $s1 .'contentPosition');
        $urltarget1 = theme_enlightlite_get_setting('slide'. $s1 .'urltarget1');
        $contwidth = theme_enlightlite_get_setting('slide'.$s1.'contFullwidth');
        $slidedesc = theme_enlightlite_get_setting('slide' . $s1 . 'desc', 'format_html');
        $slideurltext1 = theme_enlightlite_get_setting('slide' . $s1 . 'urltext1');
        $slidecaption = theme_enlightlite_get_setting('slide' . $s1 . 'caption', true);
        $slide['slidecaption'] = theme_enlightlite_lang($slidecaption);
        $slide['slideurltext1'] = theme_enlightlite_lang($slideurltext1);
        $slide['slidedesc'] = theme_enlightlite_lang($slidedesc);
        $s2 = (int) $s1 - 1;
        if ($contwidth == "auto") {
            $contwidth = "auto";
        } else {
            $contwidth = intval($contwidth);
            if ($contwidth > '100' ) {
                $contwidth = '100%';
            } else if ($contwidth <= 0) {
                $contwidth = "auto";
            } else {
                $contwidth = $contwidth.'%';
            }
        }
        $slide['cont_width'] = $contwidth;
        $slide['contentAnimation'] = "ScrollRight";
        $slide['contentAclass'] = "animated ". $slide['contentAnimation'];
        if ($urltarget1 == 1) {
            $slide['btntarget1'] = "_blank";
        } else {
            $slide['btntarget1'] = "_self";
        }

        if (!empty($slide['contentPosition'])) {
            $slide['contentClass'] = 'content-'.$slide['contentPosition'];
        } else {
            $slide['contentClass'] = "content-centerRight";
        }
        if ($s2 == "") {
            $s2 = 0;
        }
        $slide['dataUrl'] = "";
        if (!empty($slide['slideurl1']) && empty($slide['slideurltext1'])) {
            $slide['dataUrl'] = $slide['slideurl1'];
        }
    }
    return $slide;
}


$slideconfig = [];
$general = slideshow_general_config();
$slideconfig += $general;
$slideconfig += slideshow_body($general);
$PAGE->requires->data_for_js('homecarouselconfig', $general);
$PAGE->requires->js_call_amd('theme_enlightlite/homeslider', 'init', ['selector' => '#homepage-carousel']);
$PAGE->requires->css("/theme/enlightlite/style/animate.css");