define(['jquery', 'theme_enlightlite/jquery.sudoSlider'], function($, sudoslider) {
    var defaults = {
        autoslideshow: false,
        interval: 500,
    };

    var SELECTORS = {
        slideContent: '.slide-content .slide-text',
    };

    var Carousel = function(selector, options) {
        console.log(options);
        var results = $.extend(defaults, options);
        this.initializeslider(selector, results);
    };

    Carousel.prototype.initializeslider = function(selector, data) {
        var autostopped = false;
        var sudoSlider = $(selector).sudoSlider({
            prevNext:true,
            prevHtml: '.homepage-carousel .prevBtn.carousel-control',
            nextHtml: '.homepage-carousel .nextBtn.carousel-control',
            speed: 1400,
            ease:'swing',
            responsive: true,
            updateBefore: true,
            useCSS:true,
            interruptible:false,
            numeric : true,
            pause: (data.autoslideshow == 'false') ? false : data.slideinterval,
            auto: (data.autoslideshow == 'false') ? false : true,
            customLink: ".homepage-carouselLink",
            afterAnimation:function (t, slider) {
                $('.homecarousel-slide-item.carousel-item').not('[data-slide="'+ t +'"]').removeClass('active');
                $('.homecarousel-slide-item.carousel-item[data-slide="'+ t +'"]').addClass('active');
                $('.slide-text').show();
            },
            beforeAnimation: function(t, slider) {
                animation();
            }
        });

        sudoSlider.mouseenter(function() {
            auto = sudoSlider.getValue('autoAnimation');
            if (auto) {
                sudoSlider.stopAuto();
            } else {
                autostopped = true;
            }
        }).mouseleave(function() {
            if (!autostopped) {
                sudoSlider.startAuto();
            }
        });

        function animation() {
            $this = $('.slide-content .slide-text')
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
    };

    return {
        init: function(selector) {
            var options = homecarouselconfig;
            homecarousel = new Carousel(selector, options);
        }
    }
});