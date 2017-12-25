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

?>

 <link href="<?php echo $themeurl; ?>/style/animate.css" rel="stylesheet" media="all">
<script type='text/javascript' src="<?php echo $themeurl; ?>/javascript/jquery.mobile.customized.min.js"></script>
 <script type="text/javascript" src="<?php echo $themeurl; ?>/javascript/jquery.easing.1.3.js"></script>
<script type='text/javascript' src='<?php echo $themeurl; ?>/javascript/camera.min.js'></script>
 <link rel='stylesheet' id='camera-css'  href='<?php echo $themeurl; ?>/style/camera.css' type='text/css' media='all'>

<?php
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
 * Returns the slideshow headers.
 * @param type|array $general
 * @return type|string
 */
function slideshow_header($general) {

    $header = "";
    $header .= html_writer::start_tag('div', array('class' => 'homepage-carousel') );
    $header .= html_writer::start_tag('div', array(
        'id' => 'homepage-carousel',
        'class' => ' camera_magenta_skin bs-slider ',
        'data-interval' => $general['slideinterval']
        ));
    return $header;
}

/**
 * Returns the slideshow footer contents.
 * @param type|array $general
 * @return type|string
 */
function slideshow_footer($general) {
    $footer = "";
    $footer .= html_writer::end_tag('div');  // Div #homepage-Carousel.
    $footer .= html_writer::end_tag('div');  // Div .homepage-Carousel.
    return $footer;
}

/**
 * Returns the maincontent of the slideshow.
 * @param type|array $general
 * @return type|string
 */
function slideshow_body($general) {
    $data = array();
    $sliderlevel = 0;
    $data['maincontent'] = "";
    for ($s1 = 1; $s1 <= 3; $s1++) {
        $status = theme_enlightlite_get_setting('slide' . $s1 . 'status');
        if ($status == "1") {
            $slideconfig = slideshow_slide_settings($general, $s1);
            if (!empty($slideconfig['slideimg'])) {

                $data['maincontent'] .= slideshow_maincontent($slideconfig, $general);
                $sliderlevel++;
            }
        }
    }
    $data['sliderLevel'] = $sliderlevel;
    return $data;
}

/**
 * Returns the slideshow slide settings value.
 * @param type|array $general
 * @param type|string $s1
 * @return type|array
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

/**
 * Returns the text contents, like caption, description, button.
 * @param type|integer $slide
 * @param type|array $general
 * @return type|string
 */
function slideshow_maincontent($slide, $general) {
    $slidecontent = '';
    $slideheadclass = array(
        'class' => 'carousel-item',
        'data-thumb' => $slide['slideimg'],
        'data-src' => $slide['slideimg'],
        'data-link' => $slide['dataUrl']
        );
    $slidecontent .= html_writer::start_tag('div', $slideheadclass);

    if ( !empty($slide['slidecaption']) || !empty($slide['slidedesc']) || !empty($slide['slideurltext1'])   ) {
        $slidecontent .= slideshow_textcontent($slide, $general);
    }
    $slidecontent .= html_writer::end_tag('div');  // Div Carousel-item, End Of Wraper.
    return $slidecontent;
}

/**
 * Returns the text contents, like caption, description, button.
 * @param type|integer $slide
 * @param type|array $general
 * @return type|string
 */
function slideshow_textcontent($slide, $general) {

    $slidecontent = $title2 = '';

    $contentstyleclass = "content_overlayer";
    $textcontentclass = "slide-text animated fadeIn ";
    $textcontentclass .= $slide['contentClass'] ." ";
    $textcontentclass .= $contentstyleclass;
    $slidecontent .= html_writer::start_tag('div', array(
        'class' => $textcontentclass,
        'style' => 'width:'.$slide['cont_width'].';'
        ));
    $slidecaptionarray = array('class' => "", 'data-animation' => $slide['contentAclass']);
    $slidecontent .= html_writer::tag('h1', $slide['slidecaption'], $slidecaptionarray);
    $slidecontent .= html_writer::tag('p', $slide['slidedesc'], array('class' => "", 'data-animation' => $slide['contentAclass']));

    if (!empty($slide['slideurl1']) && !empty($slide['slideurltext1'])) {
        $slideurlarray = array('target' => $slide['btntarget1'], 'class' => 'btn btn-primary '.
            $slide['contentAclass'], 'data-animation' => 'animated '.$slide['contentAnimation']);
        $slidecontent .= html_writer::link($slide['slideurl1'], $slide['slideurltext1'], $slideurlarray);
    }
    $slidecontent .= html_writer::end_tag('div');  // Div Slide-text.
    return $slidecontent;
}

$general = slideshow_general_config();
if ($general['slideshowStatus'] != 0) {
    global $CFG;
    $header = slideshow_header($general);
    $footer = slideshow_footer($general);
    $data = slideshow_body($general);
    if (!empty($data['maincontent'])) {
        $slider = $header.$data['maincontent'].$footer;
        // Slider Contents are displayed here.
        echo $slider; // Full Slider Contents are printed here.
    }
    if (isset($data['sliderLevel']) && $data['sliderLevel'] <= 1) {
        $general['autoslideshow'] = "false";
        $navigation = "false";
    } else {
        $navigation = "true";
    }
    if (isset($data['maincontent']) && !empty($data['maincontent'])) {
?>
    <script type="text/javascript" id="camerajs">
    (function($) {
        var interval = "<?php echo $general['slideinterval']; ?>";
        var autoplay = <?php echo $general['autoslideshow'];?>;
        var slideHeight = "550";
        var thumbnails = true;
        var pagination = 'DOTED';
        var imagePath = <?php echo "'$CFG->wwwroot/theme/enlightlite/images/'"; ?>;
        var navigation = <?php echo $navigation;?>;

        $(document).ready(function(){

            var wi = $(window).width();
            var val = ( parseInt(slideHeight) / parseInt(wi) ) * parseInt(100);
            heightval = val + '%';

            $('#homepage-carousel').camera({
                height: heightval,
                pagination: true,
                thumbnails: true,
                time: interval,
                loaderColor: '#eeeeee',
                loaderBgColor: '#222222', //'#222222',
                loaderOpacity: 0.6, // .8
                loader: 'bar',
                autoAdvance: autoplay,
                navigation: navigation,
                mobileAutoAdvance: true,
                mobileNavHover: false,
                imagePath: imagePath,
                overlayer: false,
                loaderOpacity:0.7,
                rows: 4,
                slicedCols: 7,
                slicedRows: 5,
                transPeriod: 1000,
                barPosition: 'top',
                easing: 'easeInOutExpo',
                onEndTransition: function() {
                   $this = $('.camera_target_content .cameracurrent');
                    animation();
                    $('.slide-text').show();
                },
            });
            $("#homepage-carousel").cameraPause();
            $(window).on("load", function(){
                if (autoplay == true) {
                    $("#homepage-carousel").cameraResume();
                }
                if ($("#homepage-carousel").width() <= 800) {
                    $("#homepage-carousel").cameraResume();
                }
            });

            function animation() {
                $this = $('.camera_target_content .cameracurrent')
                $content = $this.find('.heading-content [data-animation ^= "animated"]');
                index = 0;
                if ($content != "undefined" && $content.length != ""){
                    $content.css({'opacity': 0});
                    $time = setInterval(function () {
                        $this = $content;
                        da = $content.eq(index);
                        ani = da.attr('data-animation');
                        da.addClass(ani);
                        da.css({'opacity': 1});
                        index++;
                        if (index == $this.length) {
                            clearInterval($time);
                        }
                        doAnimations(da);
                    }, 400);

                }
            }

            function doAnimations(elems) {
              var animEndEv = 'webkitAnimationEnd animationend';
              elems.each(function () {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass($animationType).one(animEndEv, function () {
                  $this.removeClass($animationType);
                });
              });
            }
        });
    })(jQuery);
    </script>
<?php
    } // Check maincontents are not empty.
} // Check number of slides is more than one.
 // Check the slideshow is diable or enable.


