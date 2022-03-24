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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * This class has function for renderer primary menu and top course menus
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_enlightlite_core_renderer extends theme_boost\output\core_renderer {

    /**
     * This function have the code to create the primary menu from the settings.
     * @return type|string
     */
    public function primarymenu() {
        global $CFG;
        require_once($CFG->dirroot . '/lib/outputrenderers.php');
        $custommenuitems = isset($this->page->theme->settings->primarymenu) ? $this->page->theme->settings->primarymenu : "";
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->custom_menu_render($custommenu);
    }


    /**
     * This renderer is needed to enable the Bootstrap style navigation.
     * @param custom_menu $menu
     * @return type|string
     */
    protected function custom_menu_render(custom_menu $menu) {

        global $CFG;
        if (isset($this->page->theme->settings->cmenuPosition)) {
            $cmenuposition = $this->page->theme->settings->cmenuPosition;
        } else {
            $cmenuposition = "";
        }
        $cmenushow = theme_enlightlite_get_setting('cmenushow');
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';

        if (!$menu->has_children() ) {

            if ($cmenushow ) {
                return $this->course_menu();
            }
            return '';
        }

        $content = '';
        $count = count($menu->get_children());
        foreach ($menu->get_children() as $key => $item) {
            $context = $item->export_for_template($this);
            $skey = $key + 1;
            $coursemenu = $this->enlightlite_course_menu_position($skey, $count);
            if (!empty($coursemenu) && $coursemenu['place'] == "PREV" && $coursemenu['position'] == true) {
                $content .= $this->course_menu();
            }
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
            if (!empty($coursemenu) && $coursemenu['place'] == "NEXT" && $coursemenu['position'] == true) {
                $content .= $this->course_menu();
            }
        }

        return $content;
    }

    /**
     * Set the Position for course mega menu in header primary menu.
     * @param type|string $skey
     * @param type|string $count
     * @return type|null
     */
    public function enlightlite_course_menu_position($skey, $count) {
        global $CFG;
        $status = "";
        $cmenuposition = $this->page->theme->settings->cmenuPosition;
        $cmenuposition = intval($cmenuposition);
        if (!is_numeric($cmenuposition)) {
            return "";
        }
        $cmenushow = theme_enlightlite_get_setting('cmenushow');

        if ($cmenushow && $status != true) {
            if ($skey == $cmenuposition || ($cmenuposition == '0' && $skey < 2)) {
                $value = array('position' => true, 'place' => 'PREV' );
                $status = true;
                return $value;
            } else if ( ($cmenuposition >= $count) && $skey == $count ) {
                $value = array('position' => true, 'place' => 'NEXT' );
                $status = true;
                return $value;
            } else {
                return "";
            }

        } else {
            return "";
        }

        return "";
    }

    /**
     * This function contains the code for create and add the course menu into primary menu
     * items.
     * @return type|string
     */
    public function course_menu() {
        $courserenderer = $this->page->get_renderer('course');
        $tcmenu = $courserenderer->top_course_menu();
        $cmenushow = theme_enlightlite_get_setting('cmenushow');
        $ccontent = '';
        if ($cmenushow) {

            $ccontent = '<li class="dropdown d-lg-none d-md-block course-link">';
            $ccontent .= '<a class="nav-item nav-link" href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">';

            $ccontent .= get_string('courses').'<i class="fa fa-chevron-down"></i><span class="caretup"></span></a>';
            $ccontent .= $tcmenu['topmmenu'];
            $ccontent .= '</li><li class="d-none d-lg-block course-link" id="cr_link">';
            $ccontent .= '<a class="nav-item nav-link" href="'.new moodle_url('/course/index.php').'" >'.get_string('courses');
            $ccontent .= '<i class="fa fa-chevron-down"></i><span class="caretup"></span></a>'.$tcmenu['topcmenu'].'</li>';
        } else {
            $ccontent = '';
        }

        return $ccontent;
    }

    /**
     * This code renders the custom menu items for the
     * bootstrap dropdown menu.
     *
     * @param custom_menu_item $menunode
     * @param type|integer $level
     * @return type|string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0 ) {
        static $submenucount = 0;
        $content = '';
        if ($menunode->has_children()) {
            if ($level == 1) {
                $class = 'dropdown';
            } else {
                $class = 'dropdown-submenu';
            }
            if ($menunode === $this->language) {
                $class .= ' langmenu';
            }
            $content = html_writer::start_tag('li', array('class' => $class));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }
            $content .= html_writer::start_tag('a', array(
                'href' => $url,
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'title' => $menunode->get_title()
                ));
            $content .= $menunode->get_text();
            if ($level == 1) {
                $content .= '<b class="caret"></b>';
            }
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
        } else {
            // The node doesn't have children so produce a final menuitem.
            // Also, if the node's text matches '####', add a class so we can treat it as a divider.
            if (preg_match("/^#+$/", $menunode->get_text())) {
                // This is a divider.
                $content = '<li class="divider">&nbsp;</li>';
            } else {
                $content = '<li>';
                if ($menunode->get_url() !== null) {
                    $url = $menunode->get_url();
                } else {
                    $url = '#';
                }
                $content .= html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()));
                $content .= '</li>';
            }
        }
        return $content;
    }

    /**
     * This code renderers the user menu from default menu.
     *
     * @param type|null $user
     * @param type|null $withlinks
     * @return type|string
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = "";
            if (isset($CFG->registerauth) && $CFG->registerauth == 'email') {
                $returnstr .= " <a href='".new moodle_url('/login/signup.php')."'>". get_string('startsignup')."</a>";
            }
            if (!$loginpage) {
                if (isset($CFG->registerauth) && $CFG->registerauth == 'email') {
                    $returnstr .= " | ";
                }
                $returnstr .= " <a href=\"$loginurl\">" . get_string('login') . '</a>';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );

        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-2') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }

    /**
     * Render the login page template.
     * @param \core_auth\output\login $form
     * @return type|string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE;
        $context = $form->export_for_template($this);
        // Override because rendering is not supported in template yet.
        $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        $context->errorformatted = $this->error_text($context->error);
        $maincontent = $this->render_from_template('theme_enlightlite/login_form', $context);
        return  $maincontent;
    }
} // Here the course renderer fucntion closed.
