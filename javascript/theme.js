// This file is part of Moodle - http://moodle.org
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
 * @package    theme_enlightlite
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @authors    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$(function(){

    var img = $("nav#header").find('.avatar').find('img[src$="/u/f2"]');
    var src = img.attr('src');
    img.attr('src', src + "_white");
    msg = $("header#header").find('#nav-message-popover-container .nav-link').find("img[src$='t/message']");
    msgsrc = msg.attr('src');
    msg.attr('src', msgsrc + "_white");
    note = $("header#header").find('#nav-notification-popover-container .nav-link').find("img[src$='i/notifications']");
    notesrc = note.attr('src');
    note.attr('src', notesrc + "_white");
    /*$( "table" ).wrap( function() {
        var ctab_obj = $(this);
        if (ctab_obj.parent('div').hasClass('no-overflow')) {

        } else {
            return "<div class='no-overflow'></div>";
        }
    });*/
    par = $('#page');
    par.find('h2').each(function(){
        cont = $(this).html();
        con = cont.split(' ');
    })

    /*------- Check navbar button status -------- */
    if ($("#header .navbar-nav button").attr('aria-expanded') === "true") {
        $("#header .navbar-nav").find('button').addClass('is-active');
    }
    /*------ Event for change the drawer navbar style  ------*/
    $("#header .navbar-nav button").click(function(){
        $this = $(this);
        setTimeout(function() {
            if ($this.attr('aria-expanded') === "true") {
                $("#header .navbar-nav").find('button').addClass('is-active');
            } else {
                $("#header .navbar-nav").find('button').removeClass('is-active');
            }
        }, 200);
    });

    // Add class name with the frontpage enrolled courses block for the reference.
    $("#mycourses").parent('div#frontpage-course-list').addClass("frontpage-mycourse-list");

    $("#site-news-forum").append("<div class='clearfix'></div>");

    /*----------------- Site news block allignment changes end here ----------- */

    // Enable/Disable the popular course id field in admin settings page on PAGE LOADING.
    $val = $("#id_s_theme_enlightlite_popularCourse_type").val();
    if ($val == 1) {
        $("#admin-popularCourse_id").find('input[type=text]').attr('disabled','disabled');
    }

    // Enable/ Disable popular course id on the values based on popular course type.
    $("#id_s_theme_enlightlite_popularCourse_type").on('change', function() {

        $this = $(this);
        val = $this.val();
        if (val == '1') {
            $("#admin-popularCourse_id").find('input[type=text]').attr('disabled','disabled');
        } else {
            $("#admin-popularCourse_id").find('input[type=text]').removeAttr('disabled');
        }
    });

    /*----- Create the accordion for the custom blocks or tabs in settings page */
    var toggelSection = ['#theme_enlightlite_general', '#theme_enlightlite_slideshow', '#theme_enlightlite_marketingspot', '#theme_enlightlite_footer'];
    $.each(toggelSection, function(key, value) {
        h3 = $(value).find('h3.main');
        block = value.split("_")[2]; // Get the settings heading name from the given id's.
        h3.each( function(key) {
            $this = $(this);
            var childid = block + "_toggle_" + key;
            hidden = "true";
            var toggleChild = '<div class="child-toggle ' + childid + '" id="child_' + childid + '" data-hidden="' + hidden + '" />';
            $(this).nextUntil('h3.main').wrapAll(toggleChild);
            var toggleHead = '<div class="enlightlite-toggle-head" id="' + childid + '">';
            $this.wrap(toggleHead);
            iconclass = (hidden == "false") ? 'fa-minus-square' : 'fa-plus-square';
            var toggleIcon = '<div class="toggle-icon" style="display:inline;" > <i class="fa ' + iconclass + '"> </i></div>';
            $this.append(toggleIcon);
        })
    })

    /******** Event for accordion clicked  *********/

    $(".enlightlite-toggle-head").click(function(){
        $this = $(this);
        parentid = $this.parent('fieldset').parent('div.tab-pane').attr('id');
        if (parentid.length != "") {
            parent = $("#" + parentid);
        } else {
            parent = $(".tab-pane.active[aria-expanded=true]");
        }
        headid = $this.attr('id');
        childid = "child_" + headid;
        child = $("#" + childid);
        var dataHidden = $("#" + childid).attr('data-hidden');
        if (dataHidden == "false") {
            child.slideUp('slow');
            child.attr('data-hidden', 'true');
        } else {
            child.slideDown('slow');
            child.attr('data-hidden', 'false');
        }
        parent.find('.child-toggle').not(child).slideUp('slow').attr('data-hidden', 'true');
        toggleicon2(parentid);
    })

    /******** Set the icon for all accortions under the active parent tab *********/
    function toggleicon2(parentid) {

        if (parentid.length != "") {
            parent = $("#" + parentid);
        } else {
            parent = $(".tab-pane.active[aria-expanded=true]");
        }
        parent.find('.enlightlite-toggle-head').each(function() {
            $this = $(this);
            childid = $(this).attr('id');
            dataHidden = parent.find('#child_' + childid).attr('data-hidden');
            if (dataHidden == "false") {
                $this.find('h3 .toggle-icon i').addClass('fa-minus-square').removeClass('fa-plus-square');
            } else {
                $this.find('h3 .toggle-icon i').addClass('fa-plus-square').removeClass('fa-minus-square');
            }
        });

    }

    $(".child-toggle[data-hidden=true]").hide();
    /*============== Course Mega menu ===============*/
    if ($('body').hasClass('dir-rtl')) {
        var w = $(".header-main #sgkk").width();
        var win = $(window).width();
        if ( win >= 980) {
            var ul_w = $(".header-main #site-user-menu ul").width();
            var le = ( w - ul_w );
            $('#cr_menu').css({"width": w + 'px' , "right": '-' + le + 'px' });
        }

        $(window).resize(function(){
            var w = $(".header-main #sgkk").width();
            var win = $(window).width();
            if (win >= 980)  {
                var ul_w = $(".header-main #site-user-menu ul").width();
                var le = ( w - ul_w );
                $('#cr_menu').css({"width" : w + 'px' , "right": '-' + le + 'px' });
            }
        });
    } else { // RTL Check And RTL BASED Function;.
        var w = $(".header-main #sgkk").width();
        var win = $(window).width();
        if (win >= 980) {
            var ul_w = $(".header-main #site-user-menu ul").width();
            var le = ( w - ul_w );
            $('#cr_menu').css({"width" : w + 'px', "left": '-' + le + 'px' });
        }

        $(window).resize(function(){
            var w = $(".header-main #sgkk").width();
            var win = $(window).width();
            if (win >= 980) {
                var ul_w = $(".header-main #site-user-menu ul").width();
                var le = ( w - ul_w );
                $('#cr_menu').css({"width" : w + 'px', "left" : '-' + le + 'px' });
            }
        });
    }

    $("#cr_link").mouseenter(function() {
        $("#cr_link").addClass("active");
        $('#cr_menu').show();
    });

    $("#cr_link").mouseleave(function() {
        $("#cr_link").removeClass("active");
        $('#cr_menu').hide();
    });

});