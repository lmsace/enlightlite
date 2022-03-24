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
 * A frontpage layout.
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Get the HTML for the settings bits.

$html = theme_enlightlite_get_html_for_settings($OUTPUT, $PAGE);
if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}
$courserenderer = $PAGE->get_renderer('core', 'course');
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<!-- Require header -->
<?php  require_once(dirname(__FILE__) . '/includes/header.php');  ?>
<!-- E.O Require header -->

<div class="navdrawer-overlay" id="sidebar_overlay" style="display:none"></div>
<!--Custom theme header-->
<div class="page slide" >
    <!-- Require SlideShow -->
    <?php require_once(dirname(__FILE__) . '/includes/slideshow.php'); ?>
    <!--Custom theme Carousel Css -->
    <link rel="stylesheet" href="<?php echo theme_enlightlite_theme_url(); ?>/style/slick.css" />
    <script type="text/javascript" src="<?php echo theme_enlightlite_theme_url();?>/javascript/slick.js"></script>
    <!--About Us-->
    <script type="text/javascript">
        $(document).ready(function() {

            if ( $('body').hasClass('dir-rtl') ) {
                rtl = true;
            } else {
                rtl = false;
            }

            $(".course-slider").slick({
                arrows:true ,
                swipe:true,
                prevArrow:'#available-courses .pagenav .slick-prev',
                nextArrow: '#available-courses .pagenav .slick-next',
                rtl:rtl
            });
            var prow = $(".course-slider").attr("data-crow");
            prow = parseInt(prow);
            if (prow < 2) {
                $("#available-courses .pagenav").hide();
            }
        })

    </script>
    <?php
    $status = theme_enlightlite_get_setting('marketingSpot1_status');
    if ($status == "1") {
        echo theme_enlightlite_marketingspot1();
    }
    ?>
    <!--E.O.About Us-->
    <!-- Marketing Spot 1 -->
    <?php
    $ms1status = theme_enlightlite_get_setting('marketingSpot1_status');
    if ($ms1status == 1) {
    ?>
        <div class="frontpage-siteinfo hidden">
            <div class="siteinfo-bgoverlay">
                <div class="container">
                <?php
                $msp1title = theme_enlightlite_get_setting('mspot1title', 'format_text');
                $msp1title = theme_enlightlite_lang($msp1title);
                $msp1desc = theme_enlightlite_get_setting('mspot1desc', 'format_text');
                $msp1desc = theme_enlightlite_lang($msp1desc);
                echo '<h1>'.$msp1title.'</h1>';
                echo '<p>'.$msp1desc.'</p>';
                ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <!--E.O.Marketing Spot 1 -->
    <div id="page" class="enlightlite-frontpage" style="">
    <header id="page-header" class="clearfix">
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>
    <div id="page-content">
        <div id="<?php echo $regionbsid ?>" >
            <?php
                echo $OUTPUT->course_content_header();
                echo $OUTPUT->main_content();
                echo $OUTPUT->course_content_footer();
            ?>
        </div>
        <?php echo $OUTPUT->blocks('side-pre', 'col-md-3'); ?>
    </div>
</div>
</div>
<?php echo $flatnavbar; ?>
    <!--Testimonials-->
    <?php
        $theme = theme_config::load('enlightlite');
    ?>
    <!--E.O.Testimonials-->

    <?php
    $mspot2status = theme_enlightlite_get_setting('marketingSpot2_status');
    $msp2title = theme_enlightlite_get_setting('mspot2title', 'format_html');
    $msp2title = theme_enlightlite_lang($msp2title);
    $msp2desc = theme_enlightlite_get_setting('mspot2desc', 'format_html');
    $msp2desc = theme_enlightlite_lang($msp2desc);
    $msp2url = theme_enlightlite_get_setting('mspot2url');
    $msp2urltxt = theme_enlightlite_get_setting('mspot2urltext', 'format_html');
    $msp2urltxt = theme_enlightlite_lang($msp2urltxt);
    $mspot2urltarget = theme_enlightlite_get_setting('mspot2urltarget');
    $target = ($mspot2urltarget == '1') ? "_blank" : "_self";
    if ($mspot2status == '1') {
    ?>
        <div class="jumbo-viewall">
         <div class="container">
             <div class="inner-wrap">
                 <div class="desc-wrap">
                        <h2><?php echo $msp2title; ?></h2>
                        <p><?php echo $msp2desc; ?></p>
                    </div>
                <a href='<?php echo $msp2url; ?>' target="<?php echo $target;?>" class="btn-jumbo"><?php echo $msp2urltxt; ?></a>
                    </div>
         </div>
        </div>
    <?php
    }
    ?>

<!-- Marketing Spot 2 -->

<?php
    require_once(dirname(__FILE__) . '/includes/footer.php');
?>

<script>
require(['jquery'], function($) {

    var parent = $("#frontpage-available-course-list #available-courses").parents('div#frontpage-available-course-list')
    parent.addClass('frontpage-available-course frontpageblock-theme');
    $("#mycourses").parents('div#frontpage-course-list').addClass('frontpage-mycourse-list');

    $("#sidebar_overlay").hide();
    button = $("#header .navbar-nav button");
    $("#header .navbar-nav button").click(function(){
        setTimeout(function() {
            nav = $("#nav-drawer").attr('aria-hidden');
            if(nav == "false") {
                $("#sidebar_overlay").show();
            } else {
                setTimeout(function(){$("#sidebar_overlay").delay(100).hide();}, 150);
            }
        }, 200);

    });

    $("#sidebar_overlay").click( function() {
        if (button.hasClass('is-active')) {
            button.removeClass('is-active');
        }
        $("#nav-drawer").addClass('closed');
        button.attr('aria-expanded', 'false');
        setTimeout(function(){$("#sidebar_overlay").hide();}, 150);
    });
    $(".enlightlite-frontpage").find('br').hide();
    $(".enlightlite-frontpage").find('span.skip-block-to').each(function() {
        data = $(this).html();
        if(data.length == "") {
            $(this).hide();
        }
    })
});



</script>

<?php
if ( $type = theme_enlightlite_combolist_type() == true) {
    $js = "$(this).addClass('collapsed').attr('aria-expanded', 'false');";
    $PAGE->requires->js_amd_inline("require(['jquery'], function(){
        $('.course_category_tree').find('.category.loaded').each(function(){ ".$js." }); });");

}
