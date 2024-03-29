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
 * File for renderer the moodle predefined function.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_enlightlite\output\core;
use moodle_url;
use lang_string;
use html_writer;
use core_course_category;
use coursecat_helper;
use stdClass;
use context_course;

/**
 * Theme Enlightlite course renderer class inherit from core course renderer class.
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    /**
     * Get the visible and parent categories from categories list.
     * @return array
     */
    public function get_categories() {
        global $DB;
        $categories = array();
        $results = $DB->get_records('course_categories', array('parent'  => '0', 'visible' => '1', 'depth' => '1'));
        if (!empty($results)) {
            foreach ($results as $value) {
                $categories[$value->id] = $value->name;
            }
        }
        return $categories;
    }

    /**
     * Renderer function for override the frontpage categories list
     * @return string(html)
     */
    public function frontpage_categories_list() {
        global $CFG;
        $content = html_writer::start_tag('div', array('class' => 'container-fluid'));
        $content .= html_writer::tag('h2', get_string('categories'));
        $chelper = new coursecat_helper();
        $chelper->set_subcat_depth($CFG->maxcategorydepth)->set_show_courses(
        self::COURSECAT_SHOW_COURSES_COUNT)->set_categories_display_options(
            array(
                'limit' => $CFG->coursesperpage,
                'viewmoreurl' => new moodle_url('/course/index.php',
                array('browse' => 'categories', 'page' => 1))
                ))->set_attributes(array('class' => 'frontpage-category-names'));
        $categories = $this->get_categories();

        $attributes = $chelper->get_and_erase_attributes('course_category_tree clearfix');
        $content .= html_writer::start_tag('div', $attributes);
        $content .= html_writer::start_tag('div', array('class' => 'content'));
        $content .= html_writer::start_tag('div', array('class' => 'subcategories'));
        foreach ($categories as $key => $value) {
            $content .= $this->enlightlite_coursecat_category($chelper, core_course_category::get($key), 1);
        }
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        return $content;
    }

    /**
     * Returns HTML to display a course category as a part of a tree
     *
     * This is an internal function, to display a particular category and all its contents
     * use {@see core_course_renderer::course_category()}
     *
     * @param coursecat_helper $chelper various display options
     * @param coursecat $coursecat
     * @param int $depth depth of this category in the current tree
     * @return string
     */
    public function enlightlite_coursecat_category(coursecat_helper $chelper, $coursecat, $depth) {
        $classes = array('category');
        if (empty($coursecat->visible)) {
            $classes[] = 'dimmed_category';
        }

        $content = html_writer::start_tag('div', array(
            'class' => join(' ', $classes),
            'data-categoryid' => $coursecat->id,
            'data-depth' => $depth,
            'data-showcourses' => $chelper->get_show_courses(),
            'data-type' => self::COURSECAT_TYPE_CATEGORY,
        ));

        // Category name.
        $categoryname = $coursecat->get_formatted_name();
        $categoryname = html_writer::link(new moodle_url('/course/index.php',
                array('categoryid' => $coursecat->id)),
                $categoryname);
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_COUNT
                && ($coursescount = $coursecat->get_courses_count())) {
            $categoryname .= html_writer::tag('span', ' ('. $coursescount.')',
                    array('title' => get_string('numberofcourses'), 'class' => 'numberofcourse'));
        }
        $content .= html_writer::start_tag('div', array('class' => 'info'));

        $content .= html_writer::tag(($depth > 1) ? 'h4' : 'h3', $categoryname, array('class' => 'categoryname'));
        $content .= html_writer::end_tag('div'); // Info.

        $content .= html_writer::end_tag('div'); // Category.

        // Return the course category tree HTML.
        return $content;
    }

    /**
     * Course home.
     * @param integer $f
     * @return string
     */
    public function course_insights_home ($f = 0) {
        $courses = 0;
        $teachers = 0;
        $students = 0;
        $acourses = array();
        $astu = array();
        $atea = array();
        /* Get all courses */
        if ($ccc = get_courses('all', 'c.sortorder ASC', 'c.id,c.shortname,c.visible')) {
            foreach ($ccc as $cc) {
                if ($cc->visible == "0" || $cc->id == "1") {
                    continue;
                }
                $courses++;

                $context = context_course::instance($cc->id);

                /* count no of teachers */
                $noteachers = count_role_users(3, $context);
                $teachers = $teachers + $noteachers;
                /* count no of students */
                $nostudents = count_role_users(5, $context);
                $students = $students + $nostudents;
                $acourses[] = array('cid' => $cc->id, 'students' => $nostudents,
                     'teachers' => $noteachers
                );
                $astu[] = $nostudents;
                $atea[] = $noteachers;
            }
        }
        if ($f == "1") {
            array_multisort($astu, SORT_DESC, $atea, SORT_DESC, $acourses);
            $acourses = array_slice($acourses, 0, 24, true);
            return $acourses;
        }
        return compact('courses', 'teachers', 'students');
    }

    /**
     * Second Category menu for megamenu.
     * @param integer $count
     * @return string
     */
    private function category_menu2($count) {
        global $CFG, $DB, $USER;
        $page = optional_param('page', '0', PARAM_INT);
        $categoryid = optional_param('categoryid', null, PARAM_INT);
        $ctype = optional_param('ctype', null, PARAM_TEXT);
        $displaylist = core_course_category::make_categories_list();
        if (empty($count)) {
            $countstr = '<p>No course available</p>';
        } else if ($count == "1") {
            $countstr = '<p>'.$count.' course</p>';
        } else if ($count > 1) {
            $countstr = '<p>'.$count.' course(s)</p>';
        }

        $options = $options1 = '';

        foreach ($displaylist as $cid => $cval) {
            $ctxt = ($categoryid == $cid) ? ' selected="selected" ' : '';
            $options .= "<option value='$cid'$ctxt>$cval</option>\n";
        }

        $dlist = array("asc" => "Asc", "desc" => "Desc");
        foreach ($dlist as $ct => $ctval) {
            $ctxt1 = ($ctype == $ct) ? ' selected="selected" ' : '';
            $options1 .= "<option value='$ct'$ctxt1>$ctval</option>\n";
        }

        $courseurl = new moodle_url("/course/index.php");

        $html = '<div class="theme-filters">
        <form action="'.$courseurl.'" name="frmcourse" method="post" id="frmcrs">
        <select name="categoryid">';
        $html .= '<option value="">Categories</option>'.$options.'
        </select><select name="ctype"><option value="">Sort</option>
        '.$options1.'</select>'.$countstr.'</form></div>';
        return $html;
    }

    /**
     * Renderer function for the frontpage available courses.
     * @return string
     */
    public function frontpage_available_courses() {

        global $CFG , $DB;
        $coursecontainer = '';
        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->set_courses_display_options( array(
            'recursive' => true,
            'limit' => $CFG->frontpagecourselimit,
            'viewmoreurl' => new moodle_url('/course/index.php'),
            'viewmoretext' => new lang_string('fulllistofcourses')
        ));

        $chelper->set_attributes( array( 'class' => 'frontpage-course-list-all frontpageblock-theme' ) );

        $courses = core_course_category::get(0)->get_courses( $chelper->get_courses_display_options() );

        $totalcount = core_course_category::get(0)->get_courses_count( $chelper->get_courses_display_options() );

        $rcourseids = array_keys( $courses );

        $acourseids = $rcourseids;
        $tcount = count($acourseids);

        $newcourse = get_string( 'availablecourses' );
        $header = "";
        $header .= html_writer::tag('div', "<div></div>", array('class' => 'bgtrans-overlay'));

        $header .= html_writer::start_tag('div',
            array( 'class' => 'available-courses', 'id' => 'available-courses') );
        $header .= html_writer::start_tag('div', array( 'class' => 'available-overlay' ) );
        $header .= html_writer::start_tag('div', array( 'class' => 'available-block' ) );
        $header .= html_writer::start_tag('div', array('class' => 'container-fluid'));
        $header .= html_writer::tag('h2', get_string('availablecourses'));

        $sliderclass = 'course-slider';
        $header .= html_writer::start_tag('div', array('class' => 'row') );
        $header .= html_writer::start_tag('div', array( 'class' => " $sliderclass col-md-12") );

        $footer = html_writer::end_tag('div');
        $footer .= html_writer::end_tag('div');
        $footer .= html_writer::end_tag('div');
        $footer .= html_writer::end_tag('div');
        if (count($rcourseids) > 0) {
            $i = '0';
            $rowcontent = '';
            foreach ($acourseids as $courseid) {
                $container = '';
                $course = get_course($courseid);
                $noimgurl = $this->output->image_url('no-image', 'theme');
                $courseurl = new moodle_url('/course/view.php', array('id' => $courseid ));

                if ($course instanceof stdClass) {
                    $course = new \core_course_list_element($course);
                }

                $imgurl = '';
                $context = context_course::instance($course->id);

                foreach ($course->get_course_overviewfiles() as $file) {
                    $isimage = $file->is_valid_image();
                    $imgurl = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                    if (!$isimage) {
                        $imgurl = $noimgurl;
                    }
                }

                if (empty($imgurl)) {
                    $imgurl = $noimgurl;
                }

                $container .= html_writer::start_tag('div', array( 'class' => '') );
                $container .= html_writer::start_tag('div', array( 'class' => 'available-content'));
                $container .= html_writer::start_tag('div', array( 'class' => 'available-img'));

                $container .= html_writer::start_tag('a', array( 'href' => $courseurl) );
                $container .= html_writer::empty_tag('img',
                    array(
                        'src' => $imgurl,
                        'width' => "249",
                        'height' => "200",
                        'alt' => $course->get_formatted_name() ) );
                $container .= html_writer::end_tag('a');
                $container .= html_writer::end_tag('div');
                $container .= html_writer::tag('h6', html_writer::tag('a',
                    $course->get_formatted_name(),
                    array( 'href' => $courseurl ) ),
                array('class' => 'title-text') );
                $container .= html_writer::end_tag('div');
                $container .= html_writer::end_tag('div');

                $rowcontent .= $container;
            }
            $i++;
            $coursecontainer .= $rowcontent;
        }
        $footer .= html_writer::end_tag('div');
        $footer .= html_writer::end_tag('div');
        $coursehtml = $header.$coursecontainer.$footer;
        return $coursehtml;

        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', \context_system::instance())) {
            // Print link to create a new course, for the 1st available category.
            echo $this->add_new_course_button();
        }
    }

    /**
     * Renderer function for frontpage enrolled courses.
     * @return string
     */
    public function frontpage_my_courses() {

        global $USER, $CFG, $DB;
        $content = html_writer::start_tag('div', array('class' => 'frontpage-enrolled-courses') );
        $content .= html_writer::start_tag('div', array('class' => 'container-fluid'));
        $content .= html_writer::tag('h2', get_string('mycourses'));
        $coursehtml = parent::frontpage_my_courses();
        if ($coursehtml == '') {

            $coursehtml = "<div id='mycourses'><style> #frontpage-course-list.frontpage-mycourse-list { display:none;}";
            $coursehtml .= "</style></div>";
        }
        $content .= $coursehtml;
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');

        return $content;
    }

    /**
     * Course search form renderer function.
     * @param string $value
     * @param string $format
     * @return string
     */
    public function course_search_form($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }

        switch ($format) {
            case 'navbar' :
                $formid = 'coursesearchnavbar';
                $inputid = 'navsearchbox';
                $inputsize = 20;
                break;
            case 'short' :
                $inputid = 'shortsearchbox';
                $inputsize = 12;
                break;
            default :
                $inputid = 'coursesearchbox';
                $inputsize = 30;
        }

        $strsearchcourses = get_string("searchcourses");
        $searchurl = new moodle_url('/course/search.php');
        $output = html_writer::start_tag('div', array('class' => 'search-block'));
        $output .= html_writer::start_tag('div', array('class' => 'container-fluid'));

        if ($this->page->pagelayout == "frontpage") {
            $output .= html_writer::tag('h2', get_string('search_courses', 'theme_enlightlite'));
        }
        $search = get_string ('search');
        $output .= html_writer::start_tag('form',
            array(
                'id' => $formid,
                'action' => $searchurl,
                'method' => 'get'));
        $output .= html_writer::start_tag('fieldset', array('class' => 'coursesearchbox invisiblefieldset'));
        $output .= html_writer::tag('label', $strsearchcourses.': ', array('for' => $inputid));
        $output .= html_writer::empty_tag('input',
            array('type' => 'text',
                'id' => $inputid,
                'size' => $inputsize,
                'name' => 'search',
                'placeholder' => get_string('typesearch', 'theme_enlightlite'),
                'value' => s($value)));
        $output .= html_writer::empty_tag('input', array('type' => 'submit',
            'value' => $search));
        $output .= html_writer::end_tag('fieldset');
        $output .= html_writer::end_tag('form');
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');
        return $output;
    }

    /**
     * Get the course details for the particular  id.
     * @param integer $courseid
     * @param bool $clone
     * @return array
     */
    public function get_course($courseid, $clone = true) {
        global $DB, $COURSE, $SITE;
        if (!empty($COURSE->id) && $COURSE->id == $courseid) {
            return $clone ? clone($COURSE) : $COURSE;
        } else if (!empty($SITE->id) && $SITE->id == $courseid) {
            return $clone ? clone($SITE) : $SITE;
        } else {
            $content = $DB->get_record('course', array('id' => $courseid), '*', IGNORE_MISSING);
            if (!empty($content)) {
                return $content;
            }
        }
    }

    /**
     * Check the given id have the courses details.
     * @param array $rcourseids
     * @return array
     */
    public function check_course_id($rcourseids) {
        if (!empty($rcourseids)) {
            foreach ($rcourseids as $key => $rcourseid) {
                if (empty($rcourseid) && !is_integer($rcourseid)) {
                    unset($rcourseids[$key]);
                }
            }
        }
        return $rcourseids;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@see core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_list_element|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG;
        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new \core_course_list_element($course);
        }
        $content = '';
        $classes = trim('coursebox clearfix '. $additionalclasses);
        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }

        // Coursebox.
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));

        $content .= html_writer::start_tag('div', array('class' => 'info'));

        // Course name.
        $coursename = $chelper->get_course_formatted_name($course);
        $coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                                            $coursename, array('class' => $course->visible ? '' : 'dimmed'));
        $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
        // If we display course in collapsed form but the course has summary or course contacts, display the link to the info page.
        $content .= html_writer::start_tag('div', array('class' => 'moreinfo'));
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            if ($course->has_summary() || $course->has_course_contacts() || $course->has_course_overviewfiles()) {
                $url = new moodle_url('/course/info.php', array('id' => $course->id));
                $image = $this->output->pix_icon('i/info', $this->strings->summary);
                $content .= html_writer::link($url, $image, array('title' => $this->strings->summary));
                // Make sure JS file to expand course content is included.
                $this->coursecat_include_js();
            }
        }
        $content .= html_writer::end_tag('div'); // Moreinfo.
        $contentimages = $contentfiles = $class = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimages .= html_writer::tag('div',
                        html_writer::empty_tag('img', array('src' => $url)),
                        array('class' => 'courseimage'));
            } else {
                $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
                $filename = html_writer::tag('span', $image, array('class' => 'fp-icon')).
                        html_writer::tag('span', $file->get_filename(), array('class' => 'fp-filename'));
                $contentfiles .= html_writer::tag('span',
                        html_writer::link($url, $filename),
                        array('class' => 'coursefile fp-filename-icon'));
            }
        }

        if (!$contentimages && !$contentfiles) {
            $class = 'no-image';
        }

        if ($icons = enrol_get_course_info_icons($course)) {
            $content .= html_writer::start_tag('div', array('class' => 'enrolmenticons'));
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }
            $content .= html_writer::end_tag('div'); // Enrolmenticons.
        }

        $content .= html_writer::end_tag('div'); // Info.

        $content .= html_writer::start_tag('div', array('class' => 'content '.$class));
        $content .= $this->coursecat_coursebox_content($chelper, $course);
        $content .= html_writer::end_tag('div'); // Content.

        $content .= html_writer::end_tag('div'); // Coursebox.
        return $content;
    }

    /**
     * Returns HTML to display a tree of subcategories and courses in the given category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat top category (this category's name and description will NOT be added to the tree)
     * @return string
     */
    protected function coursecat_tree(coursecat_helper $chelper, $coursecat) {
        // Reset the category expanded flag for this course category tree first.
        $this->categoryexpandedonload = false;
        $categorycontent = $this->coursecat_category_content($chelper, $coursecat, 0);
        if (empty($categorycontent)) {
            return '';
        }

        // Start content generation.
        $content = '';
        $attributes = $chelper->get_and_erase_attributes('course_category_tree clearfix');
        $content .= html_writer::start_tag('div', $attributes);

        if ($coursecat->get_children_count()) {
            $classes = array(
                'collapseexpand', 'aabtn'
            );

            // Check if the category content contains subcategories with children's content loaded.
            if ($this->categoryexpandedonload) {
                $classes[] = 'collapse-all';
                $linkname = get_string('collapseall');
            } else {
                $linkname = get_string('expandall');
            }

            $type = theme_enlightlite_get_setting('comboListboxType');
            if ($type == 1) {
                $linkname = get_string('expandall');
            } else {
                $linkname = get_string('collapseall');
            }

            // Only show the collapse/expand if there are children to expand.
            $content .= html_writer::start_tag('div', array('class' => 'collapsible-actions'));
            $content .= html_writer::link('#', $linkname, array('class' => implode(' ', $classes)));
            $content .= html_writer::end_tag('div');
            $this->page->requires->strings_for_js(array('collapseall', 'expandall'), 'moodle');
        }

        $content .= html_writer::tag('div', $categorycontent, array('class' => 'content'));

        $content .= html_writer::end_tag('div');

        return $content;
    }
} // Here the theme_enlightlite_course renderer fucntion closed.
