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
 * The Library file for theme enlightlite.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Page init functions runs every time page loads.
 * @param moodle_page $page
 * @return null
 */
function theme_enlightlite_page_init(moodle_page $page) {
    global $CFG, $SESSION, $PAGE, $OUTPUT;
    $page->requires->jquery();
    $pattern = theme_enlightlite_get_setting('patternselect');
    $pattern = !empty($pattern) ? 'pattern-'.$pattern : "pattern-default";
    $PAGE->add_body_class($pattern);

}

/**
 * Loads the CSS Styles and replace the background images.
 * If background image not available in the settings take the default images.
 *
 * @param string $css
 * @param string $theme
 * @return string
 */
function theme_enlightlite_process_css($css, $theme) {
    global $OUTPUT, $CFG;
    if (!empty($theme->settings->patternselect)) {
        $pselect = $theme->settings->patternselect;
    } else {
        $pselect = '#39b3e6';
    }
    $customcss = !empty($theme->settings->customcss) ? $theme->settings->customcss : '';
    $css = theme_enlightlite_custom_css($css , $customcss);
    $css = theme_enlightlite_set_fontwww($css);
    $css = theme_enlightlite_get_pattern_color($css, $theme);
    $css = theme_enlightlite_set_slide_opacity($theme , $css);
    return $css;
}

function theme_enlightlite_custom_css($css , $customcss) {

    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    $css = str_replace($tag , $replacement , $css);
    return $css;

}


/**
 * Loads the CSS and set the background images.
 * @return string
 */
function theme_enlightlite_set_bgimg() {
    global $CFG;
    $bgimgs = array('footbgimg' => 'footbgimg.jpg');
    $url = $CFG->wwwroot.'/theme/enlightlite/pix/home/footbgimg.jpg';
    if (file_exists($url)) {
        $imgcss = "$footbgimg: url('".$url."');"."\n";
    } else {
        $imgcss = "";
    }
    return $imgcss;
}

/**
 * Get the slider obacity level from the settings and load into scss.
 * @param type|array $theme
 * @return type|string
 */
function theme_enlightlite_set_slide_opacity($theme , $css) {

    if (!empty($theme->settings->slideOverlay_opacity)) {
        $opacity = $theme->settings->slideOverlay_opacity;
    } else {
        $opacity = "0";
    }
    $tag = '[[opacity]]';
    $replacement = $opacity;
    $css = str_replace($tag , $replacement , $css);
    return $css;
}

function theme_enlightlite_set_fontwww($css) {
    global $CFG, $PAGE;
    if (empty($CFG->themewww)) {
        $themewww = $CFG->wwwroot."/theme";
    } else {
        $themewww = $CFG->themewww;
    }

    $tag = '[[setting:fontwww]]';
    $theme = theme_config::load('enlightlite');
    $css = str_replace($tag, $themewww.'/enlightlite/fonts/', $css);
    return $css;
}

/**
 * Add font folder path into css file using moodle pre css method.
 * @param string $css
 * @return string
 */
function theme_enlightlite_pre_css_set_fontwww($css) {
    global $CFG, $PAGE;
    if (empty($CFG->themewww)) {
        $themewww = $CFG->wwwroot."/theme";
    } else {
        $themewww = $CFG->themewww;
    }

    $tag = '[[setting:fontwww]]';
    $theme = theme_config::load('enlightlite');
    $css = str_replace($tag, $themewww.'/enlightlite/fonts/', $css);
    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_enlightlite_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    static $theme;
    $bgimgs = array('testimonialsbg', 'footbgimg', 'newcoursesbg', 'popularcoursesbg', 'aboutbg', 'loginbg');

    if (empty($theme)) {
        $theme = theme_config::load('enlightlite');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {

        if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'footerlogo') {
            return $theme->setting_file_serve('footerlogo', $args, $forcedownload, $options);
        } else if ($filearea === 'style') {
            theme_enlightlite_serve_css($args[1]);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
        } else if (preg_match("/slide[1-9][0-9]*image/", $filearea) !== false) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if (in_array($filearea, $bgimgs)) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Serves CSS for image file updated to styles.
 *
 * @param string $filename
 * @return string
 */
function theme_enlightlite_serve_css($filename) {
    global $CFG;
    if (!empty($CFG->themedir)) {
        $thestylepath = $CFG->themedir . '/enlightlite/style/';
    } else {
        $thestylepath = $CFG->dirroot . '/theme/enlightlite/style/';
    }
    $thesheet = $thestylepath . $filename;

    $etagfile = md5_file($thesheet);
    // File.
    $lastmodified = filemtime($thesheet);
    // Header.
    $ifmodifiedsince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
    $etagheader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

    if ((($ifmodifiedsince) && (strtotime($ifmodifiedsince) == $lastmodified)) || $etagheader == $etagfile) {
        theme_enlightlite_send_unmodified($lastmodified, $etagfile);
    }
    theme_enlightlite_send_cached_css($thestylepath, $filename, $lastmodified, $etagfile);
}

/**
 * Set browser cache used in php header.
 * @param type|string $lastmodified
 * @param type|string $etag
 *
 */
function theme_enlightlite_send_unmodified($lastmodified, $etag) {
    $lifetime = 60 * 60 * 24 * 60;
    header('HTTP/1.1 304 Not Modified');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Content-Type: text/css; charset=utf-8');
    header('Etag: "' . $etag . '"');
    if ($lastmodified) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    }
    die;
}

/**
 * Cached css.
 * @param type|string $path
 * @param type|string $filename
 * @param type|integer $lastmodified
 * @param type\string $etag
 */
function theme_enlightlite_send_cached_css($path, $filename, $lastmodified, $etag) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/configonlylib.php');
    // 60 days only - the revision may get incremented quite often.
    $lifetime = 60 * 60 * 24 * 60;

    header('Etag: "' . $etag . '"');
    header('Content-Disposition: inline; filename="'.$filename.'"');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastmodified) . ' GMT');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $lifetime) . ' GMT');
    header('Pragma: ');
    header('Cache-Control: public, max-age=' . $lifetime);
    header('Accept-Ranges: none');
    header('Content-Type: text/css; charset=utf-8');
    if (!min_enable_zlib_compression()) {
        header('Content-Length: ' . filesize($path . $filename));
    }

    readfile($path . $filename);
    die;
}




/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add Clean specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_enlightlite_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::link($CFG->wwwroot, '', array('title' => get_string('home'), 'class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}


/**
 * Load the logo url.
 * @param type|string $type
 * @return type|string
 */
function theme_enlightlite_get_logo_url($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlightlite');
    }

    $logo = $theme->setting_file_url('logo', 'logo');
    $logo = empty($logo) ? '' : $logo;
    return $logo;
}

/**
 * Renderer the slider images.
 * @param type|integer $p
 * @param type|string $sliname
 * @return null
 */
function theme_enlightlite_render_slideimg($p, $sliname) {
    global $PAGE, $OUTPUT;

    $nos = theme_enlightlite_get_setting('numberofslides');
    if (theme_enlightlite_get_setting($sliname)) {
        $slideimage = $PAGE->theme->setting_file_url($sliname, $sliname);
        return $slideimage;
    }
    return "";
}

/**
 * Functions helps to get the admin config values which are related to the
 * theme
 * @param type|array $setting
 * @param type|bool $format
 * @return bool
 */
function theme_enlightlite_get_setting($setting, $format = true) {
    global $CFG;
    require_once($CFG->dirroot . '/lib/weblib.php');
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('enlightlite');
    }
    if (isset($theme->settings->$setting)) {
        if (empty($theme->settings->$setting)) {
            return false;
        } else if (!$format) {
            return $theme->settings->$setting;
        } else if ($format === 'format_text') {
            return format_text($theme->settings->$setting, FORMAT_PLAIN);
        } else if ($format === 'format_html') {
            return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
        } else {
            return format_string($theme->settings->$setting);
        }
    }
}

/**
 * Render the current theme url
 * @return string
 */
function theme_enlightlite_theme_url() {
    global $CFG, $PAGE;
    $themeurl = $CFG->wwwroot.'/theme/'. $PAGE->theme->name;
    return $themeurl;
}

/**
 * Display Footer Block Custom Links
 * @param type|string $menuname Footer block link name.
 * @return string The Footer links are return.
 */
function theme_enlightlite_generate_links($menuname = '') {
    global $CFG, $PAGE;
    $htmlstr = '';
    $menustr = theme_enlightlite_get_setting($menuname);
    $menusettings = explode("\n", $menustr);
    foreach ($menusettings as $menukey => $menuval) {
        $expset = explode("|", $menuval);
        $lurl = '#';
        $ltxt = isset($expset[0]) ? $expset[0] : '';
        if (!empty($expset) && isset($expset[0]) && isset($expset[1])) {
            list($ltxt, $lurl) = $expset;
            $ltxt = trim($ltxt);
            $ltxt = theme_enlightlite_lang($ltxt);
            $lurl = trim($lurl);
            if (empty($ltxt)) {
                continue;
            }
            if (empty($lurl)) {
                $lurl = 'javascript:void(0);';
            }
            $pos = strpos($lurl, 'http');
            if ($pos === false) {
                $lurl = new moodle_url($lurl);
            }
        }
        $htmlstr .= '<li><a href="'.$lurl.'">'.$ltxt.'</a></li>'."\n";
    }
    return $htmlstr;
}

/**
 * Display Footer block Social Media links.
 *
 * @return string The Footer Social Media links are return.
 */
function theme_enlightlite_social_links() {
    global $CFG;
    $totalicons = 4;
    $htmlstr = '';
    for ($i = 1; $i <= 4; $i++) {
        $iconenable = theme_enlightlite_get_setting('siconenable'.$i);
        $icon = theme_enlightlite_get_setting('socialicon'.$i);
        $iconcolor = theme_enlightlite_get_setting('siconbgc'.$i);
        $iconurl = theme_enlightlite_get_setting('siconurl'.$i);
        $iconstr = '';
        $iconsty = (empty($iconcolor)) ? '' : ' style="background: '.$iconcolor.';"';
        if ($iconenable == "1" && !empty($icon)) {
            $iconstr = '<li class="media0'.$i.'"'.$iconsty.'><a href="'.$iconurl.'"><i class="fa fa-'.$icon.'"></i></a></li>'."\n";
            $htmlstr .= $iconstr;
        }
    }
    return $htmlstr;
}

/**
 * Remove the html special tags from course content.
 * This function used in course home page.
 *
 * @param string $text
 * @return string
 */
function theme_enlightlite_strip_html_tags( $text ) {
    $text = preg_replace(
        array(
            // Remove invisible content.
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before and after blocks.
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text
    );
    return strip_tags( $text );
}

/**
 * Cut the Course content. *
 * @param type|string $str
 * @param type|integer $n
 * @param type|char $end_char
 * @return type|string
 */
function theme_enlightlite_course_trim_char($str, $n = 500, $endchar = '&#8230;') {
    if (strlen($str) < $n) {
        return $str;
    }
    $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
    if (strlen($str) <= $n) {
        return $str;
    }
    $out = "";
    $small = substr($str, 0, $n);
    $out = $small.$endchar;
    return $out;
}


/**
 * Returns the HTML contents for the marketing spot1 (About us)
 * @return type|string
 */
function theme_enlightlite_marketingspot1() {
    global $CFG, $PAGE;

    $status = theme_enlightlite_get_setting('marketingSpot1_status');
    $description = theme_enlightlite_get_setting('mspot1desc');
    $title = theme_enlightlite_lang(theme_enlightlite_get_setting('mspot1title'));
    $media = theme_enlightlite_get_setting('mspot1media', 'format_html');
    if (!empty($media)) {
        $classmedia = 'video-visible';
    } else {
        $classmedia = "";
    }
    $content = '';
    if (!empty($title)) {
        $title = explode(' ', $title);
        $title1 = (array_key_exists(0, $title)) ? $title[0] : $title;
        $title2 = array_slice($title, 1);
        $title2 = implode(' ', $title2);
    } else {
        $title1 = $title2 = "";
    }
    if (isset($status) && $status == 1 ) {
        $description = theme_enlightlite_lang($description);
        if (!empty($description) || !empty($media)) {

            if (!empty($media) && !empty($description) ) {
                $hide = "display:none";
                $hide2 = "";
            } else if (!empty($media) && empty($description)) {
                $hide = "";
                $hide2 = "display:none";
            } else {
                $hide = "";
                $hide2 = "display:none";
            }
            $content .= html_writer::start_tag('div', array('class' => 'site-info' ));
            $content .= html_writer::start_tag('div', array('class' => 'container'));

            $content .= html_writer::start_tag('div', array('class' => 'info-content '. $classmedia ));
            $content .= html_writer::tag('h2', html_writer::tag('b', $title1) ." ".$title2, array('style' => $hide));

          
            if (!empty($description)) {

                $content .= html_writer::start_tag('div', array('class' => 'info-video','style' => 'max-width:550px;float:left;'));
                $content .= $media;
                $content .= html_writer::end_tag('div');
                $content .= html_writer::start_tag('div', array('class' => 'info-block'));
                $content .= html_writer::tag('h2', html_writer::tag('b', $title1) ." ".$title2, array('style' => $hide2) );
                $content .= html_writer::tag('p', $description);
                $content .= html_writer::end_tag('div');
            } else {

                $content .= html_writer::start_tag('div', array('class' => 'info-video','style' => 'max-width:700px; height: 350px;'));
                $content .= $media;
                $content .= html_writer::end_tag('div');
            }
            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('div');
        }

    }
    return $content;
}

/**
 * Function returns the category list random order for header menu.
 * @return type|string
 */
function theme_enlightlite_category_menu() {
    global $CFG, $PAGE;
    $categoryid = optional_param('categoryid', null, PARAM_INT);
    $category = core_course_category::get($categoryid);
    $html = '';
    if ($category === null) {
        $selectedparents = array();
        $selectedcategory = null;
    } else {
        $selectedparents = $category->get_parents();
        $selectedparents[] = $category->id;
        $selectedcategory = $category->id;
    }

    $catatlevel = \core_course\management\helper::get_expanded_categories('');
    $catatlevel[] = array_shift($selectedparents);
    $catatlevel = array_unique($catatlevel);

    require_once($CFG->libdir. '/coursecatlib.php');
    $listing = core_course_category::get(0)->get_children();
    $html .= '<ul class="nav">';
    foreach ($listing as $listitem) {
        $subcategories = array();
        if (in_array($listitem->id, $catatlevel)) {
            $subcategories = $listitem->get_children();
        }
        $html .= theme_enlightlite_category_menu_item(
        $listitem,
        $subcategories,
        $listitem->get_children_count(),
        $selectedcategory,
        $selectedparents
        );
    }
    $html .= '</ul>';
    return $html;
}

/**
 * Returns the categories menus content.
 * @param coursecat $category
 * @param array $subcategories
 * @param type $totalsubcategories
 * @param type|null $selectedcategory
 * @param type|array $selectedcategories
 * @return type|string
 */
function theme_enlightlite_category_menu_item(coursecat $category, array $subcategories, $totalsubcategories,
$selectedcategory = null, $selectedcategories = array()) {

    $viewcaturl = new moodle_url('/course/index.php', array('categoryid' => $category->id));
    $text = $category->get_formatted_name();
    $isexpandable = ($totalsubcategories > 0);
    $isexpanded = (!empty($subcategories));
    $activecategory = ($selectedcategory === $category->id);
    $dataexpanded = $isexpanded ? ' data-expanded = "1" ' : ' data-expanded = "0"';
    if ($isexpanded) {
        $cls = $activecategory ? 'has-children expanded' : 'has-children';
    } else if ($isexpandable) {
        $cls = 'has-children';
    } else {
        $cls = $activecategory ? 'expanded' : '';
    }

    $html = '<li class="'.$cls.'"'.$dataexpanded.'>';
    $html .= '<a href="'.$viewcaturl.'">'.$text.'</a>';

    if (!empty($subcategories)) {
        $html .= '<ul class="nav childnav">';

        $catatlevel = \core_course\management\helper::get_expanded_categories($category->path);
        $catatlevel[] = array_shift($selectedcategories);
        $catatlevel = array_unique($catatlevel);

        foreach ($subcategories as $listitem) {
            $childcategories = (in_array($listitem->id, $catatlevel)) ? $listitem->get_children() : array();
            $html .= theme_enlightlite_category_menu_item(
            $listitem,
            $childcategories,
            $listitem->get_children_count(),
            $selectedcategory,
            $selectedcategories
            );
        }

        $html .= '</ul>';
    }
    $html .= '</li>';

    return $html;
}


/**
 * Returns the language values from the given lang string or key.
 * @param type|string $key
 * @return type|string
 */
function theme_enlightlite_lang($key='') {
    $pos = strpos($key, 'lang:');
    if ($pos !== false) {
        list($l, $k) = explode(":", $key);
        if (get_string_manager()->string_exists($k, 'theme_enlightlite')) {
            $v = get_string($k, 'theme_enlightlite');
            return $v;
        } else {
            return $key;
        }
    } else {
        return $key;
    }
}

/**
 * Check the memeber status for show the person.
 * @param type|array $nums
 * @return type|array
 */
function theme_enlightlite_check_our_team_status($nums) {
    foreach ($nums as $key => $value) {
        $con = theme_enlightlite_get_team_user($value, 'check');
        if ($con == "") {
            unset($nums[$key]);
        }
    }
    return $nums;
}

/**
 * Check the given content length and return it's removed contents.
 * @param type|string $value
 * @param type|integer $length
 * @return type|string
 */
function enlightlite_check_length($value, $length) {

    if (strlen($value) <= $length) {
        return $value;
    } else {
        $content = substr($value, 0, $length) . '...';
        return $content;
    }
}

/**
 * Check the admin theme config for combolist type its expandable/collapsable
 * @return type|bool
 */
function theme_enlightlite_combolist_type() {
    global $PAGE;
    $type = theme_enlightlite_get_setting('comboListboxType');
    if ($type == 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * Returns the footer block address section values from admin configs.
 * @param type|string $check
 * @return type|string
 */
function theme_enlightlite_footer_address($check = "") {
    global $PAGE;
    $value = '';
    $address = theme_enlightlite_get_setting('footaddress');
    $address = theme_enlightlite_lang($address);
    $email = theme_enlightlite_get_setting('footemailid');
    $email = theme_enlightlite_lang($email);
    $phone = theme_enlightlite_get_setting('footphoneno');
    $phone = theme_enlightlite_lang($phone);
    if (!empty($address) || !empty($email) || !empty($phone)) {
        $status = "true";
        $value = html_writer::start_tag('div', array('class' => 'footer-address-block'));

        if (!empty($address)) {
            $value .= html_writer::start_tag('div', array('class' => 'footer-address'));
            $value .= html_writer::tag('p', "<i class='fa fa-map-marker'></i>".$address);
            $value .= html_writer::end_tag('div');
        }
        if (!empty($phone)) {
            $value .= html_writer::start_tag('div', array('class' => 'footer-phone'));
            $value .= html_writer::start_tag('p');
            $value .= "<i class='fa fa-phone-square'></i>".get_string('phone').": ";
            $value .= $phone;
            $value .= html_writer::end_tag('p');
            $value .= html_writer::end_tag('div');
        }
        if (!empty($email)) {
            $value .= html_writer::start_tag('div', array('class' => 'footer-email'));
            $value .= html_writer::start_tag('p');

            $value .= "<i class='fa fa-envelope'></i>".get_string('emailid', 'theme_enlightlite').": ";
            $value .= html_writer::link('mailto:'.$email, $email);
            $value .= html_writer::end_tag('p');
            $value .= html_writer::end_tag('div');
        }
        $value .= html_writer::end_tag('div');
    } else {
        $status = "false";
        $value = "";
    }
    if ($check == "true") {
        return $status;
    }
    return $value;
}


function theme_enlightlite_get_pattern_color( $css, $type='') {
    global $CFG;
    $patterncolors = include($CFG->dirroot.'/theme/enlightlite/classes/pattern_colors.php');
    $selectedpattern = theme_enlightlite_get_setting('patternselect');
    foreach ($patterncolors[$selectedpattern] as $key => $value) {
        $tag = '[['.$key.']]';
        $replacement = $value;
        $css = str_replace($tag, $replacement, $css);
    }
    return $css;
}

/**
 * Function returns the rgb format with the combination of passed color hex and opacity.
 * @param type|string $hexa
 * @param type|int $opacity
 * @return type|string
 */
function theme_enlightlite_get_hexa($hexa, $opacity) {
    if (!empty($hexa)) {
        list($r, $g, $b) = sscanf($hexa, "#%02x%02x%02x");
        if ($opacity == '') {
            $opacity = 0.0;
        } else {
            $opacity = $opacity / 10;
        }
        return "rgba($r, $g, $b, $opacity)";
    }
}