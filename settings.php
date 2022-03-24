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
 * Enlightlite theme Settings page.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings = new theme_boost_admin_settingspage_tabs('themesettingenlightlite',
    get_string('enlightlite_settings', 'theme_enlightlite'));

    /* General Settings */
    $temp = new admin_settingpage('theme_enlightlite_general', get_string('themegeneralsettings', 'theme_enlightlite'));

    // This is the descriptor for Slide One.
    $name = 'theme_enlightlite/theme_enlightlite_generalsub1';
    $heading = get_string('generallogo_menu', 'theme_enlightlite');
    $information = "";
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlightlite/patternselect';
    $title = get_string('patternselect', 'theme_enlightlite');
    $description = get_string('patternselectdesc', 'theme_enlightlite');
    $default = 'default';
    $choices = array(
        'default' => get_string("blue", "theme_enlightlite"),
        '1' => get_string("green", "theme_enlightlite"),
        '2' => get_string("lavender", "theme_enlightlite"),
        '3' => get_string("red", "theme_enlightlite"),
        '4' => get_string("purple", "theme_enlightlite")
    );

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $pimg = array();
    global $CFG;

    // Logo file setting.
    $name = 'theme_enlightlite/logo';
    $title = get_string('logo', 'theme_enlightlite');
    $description = get_string('logodesc', 'theme_enlightlite');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Primary menu position.
    $name = 'theme_enlightlite/primarymenu';
    $title = get_string('primarymenu', 'theme_enlightlite');
    $description = get_string('primarymenudesc', 'theme_enlightlite');
    $default = get_string('primary_menu', 'theme_enlightlite');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $temp->add($setting);

    // Course menu.
    $name = 'theme_enlightlite/cmenushow';
    $title = get_string('cmenushow', 'theme_enlightlite');
    $description = get_string('cmenushowdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Course menu position.
    $name = 'theme_enlightlite/cmenuPosition';
    $title = get_string('cmenuPosition', 'theme_enlightlite');
    $description = get_string('cmenuPosition_desc', 'theme_enlightlite');
    $default = '2';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $temp->add($setting);

    // This is the descriptor for Slide One.
    $name = 'theme_enlightlite/theme_enlightlite_miscellaneous';
    $heading = get_string('miscellaneous', 'theme_enlightlite');
    $information = "";
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    // Combo list box type.
    $name = 'theme_enlightlite/comboListboxType';
    $title = get_string('comboListboxType', 'theme_enlightlite');
    $description = get_string('comboListboxType_desc', 'theme_enlightlite');
    $expand = get_string('expand', 'theme_enlightlite');
    $collapse = get_string('collapse', 'theme_enlightlite');
    $default = 1;
    $choices = array(0 => $expand, 1 => $collapse);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);


    // Custom CSS file.
    $name = 'theme_enlightlite/customcss';
    $title = get_string('customcss', 'theme_enlightlite');
    $description = get_string('customcssdesc', 'theme_enlightlite');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);


    $settings->add($temp);
    // General settings end.

    /* Slideshow Settings Start */

    $temp = new admin_settingpage('theme_enlightlite_slideshow', get_string('slideshowheading', 'theme_enlightlite'));
    $temp->add(new admin_setting_heading('theme_enlightlite_slideshow', get_string('slideshowheadingsub', 'theme_enlightlite'),
    format_text(get_string('slideshowdesc', 'theme_enlightlite'), FORMAT_MARKDOWN)));

    // SlideShow Status.
    $name = 'theme_enlightlite/slideshowStatus';
    $title = get_string('slideshowStatus', 'theme_enlightlite');
    $description = get_string('slideshowStatus_desc', 'theme_enlightlite');
    $yes = get_string('yes');
    $no = get_string('no' );
    $default = 1;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);

    // Auto Scroll.
    $name = 'theme_enlightlite/autoslideshow';
    $title = get_string('autoslideshow', 'theme_enlightlite');
    $description = get_string('autoslideshowdesc', 'theme_enlightlite');
    $yes = get_string('yes');
    $no = get_string('no');
    $default = 1;
    $choices = array(1 => $yes , 0 => $no);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);

    // Slide Show Interval.
    $name = 'theme_enlightlite/slideinterval';
    $title = get_string('slideinterval', 'theme_enlightlite');
    $description = get_string('slideintervaldesc', 'theme_enlightlite');
    $default = 3500;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $temp->add($setting);

    // Slide Overlay Opacity.
    $name = 'theme_enlightlite/slideOverlay_opacity';
    $title = get_string('slideOverlay', 'theme_enlightlite');
    $description = get_string('slideOverlay_desc', 'theme_enlightlite');
    $opacity = array();
    $opacity = array_combine(range(0, 1, 0.1 ), range(0, 1, 0.1 ));
    $setting = new admin_setting_configselect($name, $title, $description, '0.4', $opacity);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    for ($i = 1; $i <= 3; $i++) {

        // This is the descriptor for Slide One.
        $name = 'theme_enlightlite/slide' . $i . 'info';
        $heading = get_string('slideno', 'theme_enlightlite', array('slide' => $i));
        $information = "";
        $setting = new admin_setting_heading($name, $heading, $information);
        $temp->add($setting);

        // SlideShow Status.
        $name = 'theme_enlightlite/slide'.$i.'status';
        $title = get_string('slideStatus', 'theme_enlightlite', array('slide' => $i));
        $description = get_string('slideStatus_desc', 'theme_enlightlite', array('slide' => $i));
        $yes = get_string('enable', 'theme_enlightlite');
        $no = get_string('disable', 'theme_enlightlite');
        $default = 1;
        $choices = array(1 => $yes , 0 => $no);
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $temp->add($setting);

        // Slide Image.
        $name = 'theme_enlightlite/slide' . $i . 'image';
        $title = get_string('slideimage', 'theme_enlightlite', array('slide' => $i));
        $description = get_string('slideimagedesc', 'theme_enlightlite');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slide' . $i . 'image');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);

        // Slide Caption.
        $name = 'theme_enlightlite/slide' . $i . 'caption';
        $title = get_string('slidecaption', 'theme_enlightlite', array('slide' => $i));
        $description = get_string('slidecaptiondesc', 'theme_enlightlite');
        $default = 'lang:slidecaptiondefault';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $temp->add($setting);

        // Slide Description Text.
        $name = 'theme_enlightlite/slide' . $i . 'desc';
        $title = get_string('slidedesc', 'theme_enlightlite', array('slide' => $i));
        $description = get_string('slidedesctext', 'theme_enlightlite');
        $default = 'lang:slidedescdefault';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
        $temp->add($setting);

        // Slide Link text.
        $name = 'theme_enlightlite/slide' . $i . 'urltext1';
        $title = get_string('slideurl1text', 'theme_enlightlite', array('type' => "1"));
        $description = get_string('slideurl1textdesc', 'theme_enlightlite');
        $default = 'lang:knowmore';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $temp->add($setting);

        // Slide Url.
        $name = 'theme_enlightlite/slide' . $i . 'url1';
        $title = get_string('slideurl1', 'theme_enlightlite', array('type' => "1"));
        $description = get_string('slideurl1desc', 'theme_enlightlite');
        $default = 'http://www.example.com/';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
        $temp->add($setting);

        $name = 'theme_enlightlite/slide'.$i.'urltarget1';
        $title = get_string('urltarget1', 'theme_enlightlite', array('type' => "1"));
        $description = get_string('urltarget_desc', 'theme_enlightlite', array('slide' => $i));
        $same = get_string('sameWindow', 'theme_enlightlite');
        $new = get_string('newWindow', 'theme_enlightlite');
        $default = 1;
        $choices = array(0 => $same , 1 => $new);
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $temp->add($setting);

        $name = 'theme_enlightlite/slide' . $i . 'contFullwidth';
        $title = get_string('slideCont_full', 'theme_enlightlite');
        $description = get_string('slideCont_fulldesc', 'theme_enlightlite');
        $default = "50";
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $temp->add($setting);


         // Slider content position.
        $name = 'theme_enlightlite/slide' . $i . 'contentPosition';
        $title = get_string('slidecontent', 'theme_enlightlite', array('slide' => $i));
        $description = get_string('slidecontentdesc', 'theme_enlightlite');

        $topleft = get_string("topLeft", "theme_enlightlite");
        $topcenter = get_string("topCenter", "theme_enlightlite");
        $topright = get_string("topRight", "theme_enlightlite");
        $centerleft = get_string("centerLeft", "theme_enlightlite");
        $center = get_string("center", "theme_enlightlite");
        $centerright = get_string("centerRight", "theme_enlightlite");
        $bottomleft = get_string("bottomLeft", "theme_enlightlite");
        $bottomcenter = get_string("bottomCenter", "theme_enlightlite");
        $bottomright = get_string("bottomRight", "theme_enlightlite");

        $default = 'centerRight';
        $choices = array(
            "topLeft" => $topleft,
            "topCenter" => $topcenter,
            "topRight" => $topright,
            "centerLeft" => $centerleft,
            "center" => $center,
            "centerRight" => $centerright,
            "bottomLeft" => $bottomleft,
            "bottomCenter" => $bottomcenter,
            "bottomRight" => $bottomright,
            );

        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $temp->add($setting);
    }

    $settings->add($temp);
    /* Slideshow Settings End*/
    /* Front Page Settings */
    $temp = new admin_settingpage('theme_enlightlite_marketingspot', get_string('frontpageheading', 'theme_enlightlite'));
    /* Marketing Spot 1*/
    $name = 'theme_enlightlite_mspot1heading';
    $heading = get_string('marketingspot', 'theme_enlightlite').' 1 ('.get_string('aboutustxt', 'theme_enlightlite').')';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);
    // Marketing Spot 1 Title.

    // Marketing Spot 1 Enable or disable.
    $name = 'theme_enlightlite/marketingSpot1_status';
    $title = get_string('marketingSpot1_status', 'theme_enlightlite');
    $description = get_string('marketingSpot1_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);
    // Marketing Spot 1 Enable or disable.

    // Marketing Spot 1 Title.
    $name = 'theme_enlightlite/mspot1title';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('mspottitledesc', 'theme_enlightlite', array('msno' => '1'));
    $default = 'lang:aboutus';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Marketing Spot 1 Description.
    $name = 'theme_enlightlite/mspot1desc';
    $title = get_string('description');
    $description = get_string('mspotdescdesc', 'theme_enlightlite', array('msno' => '1'));
    $default = 'lang:aboutusdesc';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
    $temp->add($setting);

    // Marketing spot 1 Media content.
    $name = 'theme_enlightlite/mspot1media';
    $title = get_string('media', 'theme_enlightlite');
    $description = get_string('mspotmediadesc', 'theme_enlightlite', array('msno' => '1'));
    $default = '<div style="display:none;">image</div><img src="https://res.cloudinary.com/lmsace/image/upload/v1593602097/about-img_rztwgu.jpg">';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $temp->add($setting);
    /* Marketing Spot 1*/

    /* Marketing Spot 2*/
    $name = 'theme_enlightlite_mspot2heading';
    $heading = get_string('marketingspot', 'theme_enlightlite').' 2 ( '.get_string('learntitle', 'theme_enlightlite')." )";
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlightlite/marketingSpot2_status';
    $title = get_string('marketingSpot2_status', 'theme_enlightlite');
    $description = get_string('marketingSpot2_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Marketing Spot 2 Title.
    $name = 'theme_enlightlite/mspot2title';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('mspottitledesc', 'theme_enlightlite', array('msno' => '2'));
    $default = 'lang:learnanytime';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Marketing Spot 2 Description.
    $name = 'theme_enlightlite/mspot2desc';
    $title = get_string('description');
    $description = get_string('mspotdescdesc', 'theme_enlightlite', array('msno' => '2'));
    $default = 'lang:learnanytimedesc';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
    $temp->add($setting);

    // Marketing Spot 2 Link Text.
    $name = 'theme_enlightlite/mspot2urltext';
    $title = get_string('button', 'theme_enlightlite').' '.get_string('text', 'theme_enlightlite');
    $description = get_string('mspot2urltxtdesc', 'theme_enlightlite', array('msno' => '2'));
    $default = 'lang:viewallcourses';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $temp->add($setting);

    // Marketing Spot 2 Link.
    $name = 'theme_enlightlite/mspot2url';
    $title = get_string('button', 'theme_enlightlite').' '.get_string('link', 'theme_enlightlite');
    $description = get_string('mspot2urldesc', 'theme_enlightlite');
    $default = 'http://www.example.com/';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $temp->add($setting);

    $name = 'theme_enlightlite/mspot2urltarget';
    $title = get_string('button', 'theme_enlightlite').' '.get_string('target', 'theme_enlightlite');
    $description = get_string('mspot2urltarget_desc', 'theme_enlightlite');
    $same = get_string('sameWindow', 'theme_enlightlite');
    $new = get_string('newWindow', 'theme_enlightlite');
    $default = 1;
    $choices = array(0 => $same , 1 => $new);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $temp->add($setting);
    /* Marketing Spot 2*/
    $settings->add($temp);
    /* Front Page Settings End */

    /*Testimonials End*/

    /* Footer Settings start */
    $temp = new admin_settingpage('theme_enlightlite_footer', get_string('footerheading', 'theme_enlightlite'));

    /* Footer Block1 */
    $name = 'theme_enlightlite_footerblock1heading';
    $heading = get_string('footerblock', 'theme_enlightlite').' 1 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

     $name = 'theme_enlightlite/footerb1_status';
    $title = get_string('activateblock', 'theme_enlightlite');
    $description = get_string('footerb1_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_enlightlite/footerbtitle1';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('footerbtitledesc', 'theme_enlightlite');
    $default = 'lang:footerbtitle1default';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_enlightlite/footerdesc1';
    $title = get_string('footnote', 'theme_enlightlite');
    $description = get_string('footerdescription_desc', 'theme_enlightlite', array('blockno' => '1'));
    $default = get_string('footerblink1default', 'theme_enlightlite');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $temp->add($setting);
    /* Footer Block1 */

    /* Footer Block2*/
    $name = 'theme_enlightlite_footerblock2heading';
    $heading = get_string('footerblock', 'theme_enlightlite').' 2 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    $name = 'theme_enlightlite/footerb2_status';
    $title = get_string('activateblock', 'theme_enlightlite');
    $description = get_string('footerb1_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_enlightlite/footerbtitle2';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('footerbtitledesc', 'theme_enlightlite');
    $default = 'lang:footerbtitle2default';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $name = 'theme_enlightlite/footerblink2';
    $title = get_string('links', 'theme_enlightlite');
    $description = get_string('footerblink_desc', 'theme_enlightlite', array('blockno' => '2'));
    $default = get_string('footerblink2default', 'theme_enlightlite');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $temp->add($setting);
    /* Footer Block2 */

    /* Footer Block3 */

    $name = 'theme_enlightlite_footerblock3heading';
    $heading = get_string('footerblock', 'theme_enlightlite').' 3 ';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    // Footer block 3 status.
    $name = 'theme_enlightlite/footerb3_status';
    $title = get_string('activateblock', 'theme_enlightlite');
    $description = get_string('footerb1_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Footer block title 3.
    $name = 'theme_enlightlite/footerbtitle3';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('footerbtitledesc', 'theme_enlightlite');
    $default = 'lang:footerbtitle3default';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Footer block 3 link.
    $name = 'theme_enlightlite/footerblink3';
    $title = get_string('links', 'theme_enlightlite');
    $description = get_string('footerblink_desc', 'theme_enlightlite', array('blockno' => '3'));
    $default = get_string('footerblink3default', 'theme_enlightlite');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $temp->add($setting);
    /* Footer Block3 */

    /* Footer Block4 */
    $name = 'theme_enlightlite_footerblock4heading';
    $heading = get_string('footerblock', 'theme_enlightlite').' 4 ';
    $information = get_string('socialmediadesc', 'theme_enlightlite');
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    // Footer block 4 status.
    $name = 'theme_enlightlite/footerb4_status';
    $title = get_string('activateblock', 'theme_enlightlite');
    $description = get_string('footerb1_statusdesc', 'theme_enlightlite');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Footer block 4 Title.
    $name = 'theme_enlightlite/footerbtitle4';
    $title = get_string('title', 'theme_enlightlite');
    $description = get_string('footerbtitledesc', 'theme_enlightlite');
    $default = 'lang:footerbtitle4default';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Footer Address.
    $name = 'theme_enlightlite/footaddress';
    $title = get_string('address', 'theme_enlightlite');
    $description = get_string('address_desc', 'theme_enlightlite');
    $default = get_string('defaultaddress', 'theme_enlightlite');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $temp->add($setting);

    // Footer Email Id.
    $name = 'theme_enlightlite/footemailid';
    $title = get_string('emailid', 'theme_enlightlite');
    $description = '';
    $default = get_string('defaultemailid', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Footer phone number.
    $name = 'theme_enlightlite/footphoneno';
    $title = get_string('phoneno', 'theme_enlightlite');
    $description = '';
    $default = get_string('defaultphoneno', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 1.
    $name = 'theme_enlightlite/siconenable1';
    $title = get_string('enable', 'theme_enlightlite').' '.get_string('socialicon', 'theme_enlightlite').' 1 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 1 - name.
    $name = 'theme_enlightlite/socialicon1';
    $title = get_string('socialicon', 'theme_enlightlite').' 1 ';
    $description = get_string('socialicondesc', 'theme_enlightlite');
    $default = get_string('socialicon1default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 1 - Background color.
    $name = 'theme_enlightlite/siconbgc1';
    $title = get_string('socialicon', 'theme_enlightlite').' 1 '.get_string('bgcolor', 'theme_enlightlite');
    $description = get_string('siconbgcdesc', 'theme_enlightlite');
    $default = get_string('siconbgc1default', 'theme_enlightlite');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $temp->add($setting);

    // Social Media Icon Url 1.
    $name = 'theme_enlightlite/siconurl1';
    $title = get_string('socialicon', 'theme_enlightlite').' 1 '.get_string('url', 'theme_enlightlite');
    $description = get_string('siconurldesc', 'theme_enlightlite');
    $default = get_string('siconurl1default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 2.
    $name = 'theme_enlightlite/siconenable2';
    $title = get_string('enable', 'theme_enlightlite').' '.get_string('socialicon', 'theme_enlightlite').' 2 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 2 - name.
    $name = 'theme_enlightlite/socialicon2';
    $title = get_string('socialicon', 'theme_enlightlite').' 2 ';
    $description = get_string('socialicondesc', 'theme_enlightlite');
    $default = get_string('socialicon2default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 2 - Background color.
    $name = 'theme_enlightlite/siconbgc2';
    $title = get_string('socialicon', 'theme_enlightlite').' 2 '.get_string('bgcolor', 'theme_enlightlite');
    $description = get_string('siconbgcdesc', 'theme_enlightlite');
    $default = get_string('siconbgc2default', 'theme_enlightlite');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $temp->add($setting);

    // Social Media Icon Url 2.
    $name = 'theme_enlightlite/siconurl2';
    $title = get_string('socialicon', 'theme_enlightlite').' 2 '.get_string('url', 'theme_enlightlite');
    $description = get_string('siconurldesc', 'theme_enlightlite');
    $default = get_string('siconurl2default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 3.
    $name = 'theme_enlightlite/siconenable3';
    $title = get_string('enable', 'theme_enlightlite').' '.get_string('socialicon', 'theme_enlightlite').' 3 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 3 - name.
    $name = 'theme_enlightlite/socialicon3';
    $title = get_string('socialicon', 'theme_enlightlite').' 3 ';
    $description = get_string('socialicondesc', 'theme_enlightlite');
    $default = get_string('socialicon3default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 3 - Background color.
    $name = 'theme_enlightlite/siconbgc3';
    $title = get_string('socialicon', 'theme_enlightlite').' 3 '.get_string('bgcolor', 'theme_enlightlite');
    $description = get_string('siconbgcdesc', 'theme_enlightlite');
    $default = get_string('siconbgc3default', 'theme_enlightlite');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $temp->add($setting);

    // Social Media Icon Url 3.
    $name = 'theme_enlightlite/siconurl3';
    $title = get_string('socialicon', 'theme_enlightlite').' 3 '.get_string('url', 'theme_enlightlite');
    $description = get_string('siconurldesc', 'theme_enlightlite');
    $default = get_string('siconurl3default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Enable / Disable social media icon 4.
    $name = 'theme_enlightlite/siconenable4';
    $title = get_string('enable', 'theme_enlightlite').' '.get_string('socialicon', 'theme_enlightlite').' 4 ';
    $description = '';
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 4 - name.
    $name = 'theme_enlightlite/socialicon4';
    $title = get_string('socialicon', 'theme_enlightlite').' 4 ';
    $description = get_string('socialicondesc', 'theme_enlightlite');
    $default = get_string('socialicon4default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    // Social media icon 4 - Background color.
    $name = 'theme_enlightlite/siconbgc4';
    $title = get_string('socialicon', 'theme_enlightlite').' 4 '.get_string('bgcolor', 'theme_enlightlite');
    $description = get_string('siconbgcdesc', 'theme_enlightlite');
    $default = get_string('siconbgc4default', 'theme_enlightlite');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $temp->add($setting);

    // Social Media Icon Url 4.
    $name = 'theme_enlightlite/siconurl4';
    $title = get_string('socialicon', 'theme_enlightlite').' 4 '.get_string('url', 'theme_enlightlite');
    $description = get_string('siconurldesc', 'theme_enlightlite');
    $default = get_string('siconurl4default', 'theme_enlightlite');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);
    /* Footer Block4 */

    // Copyright.
    $name = 'theme_enlightlite_copyrightheading';
    $heading = get_string('copyrightheading', 'theme_enlightlite');
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $temp->add($setting);

    // Copyright setting.
    $name = 'theme_enlightlite/copyright';
    $title = get_string('copyright', 'theme_enlightlite');
    $description = get_string('copyrightdesc', 'theme_enlightlite');
    $default = 'lang:copyrightdefault';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $temp->add($setting);

    $settings->add($temp);
    /* Footer Settings end */

}