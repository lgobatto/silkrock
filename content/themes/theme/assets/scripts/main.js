/**
 * Created by lgobatto on 03/05/17.
 */
(function ($) {

    $("[data-fancybox], [rel=prettyphoto]").fancybox({
        // Options will go here
    });

    $('.pet-potential').raty({
        readOnly: true,
        score: function () {
            return $(this).data('amount');
        }
    });

    function setREVStartSize(e) {
        try {
            var i = jQuery(window).width(), t = 9999, r = 0, n = 0, l = 0, f = 0, s = 0, h = 0;
            if (e.responsiveLevels && (jQuery.each(e.responsiveLevels, function (e, f) {
                    f > i && (t = r = f, l = e), i > f && f > r && (r = f, n = e)
                }), t > r && (l = n)), f = e.gridheight[l] || e.gridheight[0] || e.gridheight, s = e.gridwidth[l] || e.gridwidth[0] || e.gridwidth, h = i / s, h = h > 1 ? 1 : h, f = Math.round(h * f), "fullscreen" == e.sliderLayout) {
                var u = (e.c.width(), jQuery(window).height());
                if (void 0 != e.fullScreenOffsetContainer) {
                    var c = e.fullScreenOffsetContainer.split(",");
                    if (c) jQuery.each(c, function (e, i) {
                        u = jQuery(i).length > 0 ? u - jQuery(i).outerHeight(!0) : u
                    }), e.fullScreenOffset.split("%").length > 1 && void 0 != e.fullScreenOffset && e.fullScreenOffset.length > 0 ? u -= jQuery(window).height() * parseInt(e.fullScreenOffset, 0) / 100 : void 0 != e.fullScreenOffset && e.fullScreenOffset.length > 0 && (u -= parseInt(e.fullScreenOffset, 0))
                }
                f = u
            } else void 0 != e.minHeight && f < e.minHeight && (f = e.minHeight);
            e.c.closest(".rev_slider_wrapper").css({height: f})
        } catch (d) {
            console.log("Failure at Presize of Slider:" + d)
        }
    };

    var revapi10,
        tpj = jQuery;

    tpj(document).ready(function () {
        if (tpj("#rev_slider_10_1").revolution == undefined) {
            revslider_showDoubleJqueryError("#rev_slider_10_1");
        } else {
            revapi10 = tpj("#rev_slider_10_1").show().revolution({
                sliderType: "carousel",
                jsFileLocation: "//" + window.location.hostname + "/content/plugins/revslider/public/assets/js/",
                sliderLayout: "fullwidth",
                dottedOverlay: "none",
                delay: 6000,
                navigation: {
                    keyboardNavigation: "off",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation: "off",
                    mouseScrollReverse: "default",
                    onHoverStop: "off",
                    touch: {
                        touchenabled: "on",
                        touchOnDesktop: "off",
                        swipe_threshold: 75,
                        swipe_min_touches: 50,
                        swipe_direction: "horizontal",
                        drag_block_vertical: false
                    }
                    ,
                    arrows: {
                        style: "hesperiden",
                        enable: true,
                        hide_onmobile: false,
                        hide_onleave: false,
                        tmp: '',
                        left: {
                            h_align: "left",
                            v_align: "center",
                            h_offset: 30,
                            v_offset: 0
                        },
                        right: {
                            h_align: "right",
                            v_align: "center",
                            h_offset: 30,
                            v_offset: 0
                        }
                    }
                },
                carousel: {
                    maxRotation: 65,
                    vary_rotation: "on",
                    minScale: 55,
                    vary_scale: "off",
                    horizontal_align: "center",
                    vertical_align: "center",
                    fadeout: "on",
                    vary_fade: "on",
                    maxVisibleItems: 5,
                    infinity: "on",
                    space: -150,
                    stretch: "off",
                    showLayersAllTime: "off",
                    easing: "Power3.easeInOut",
                    speed: "800"
                },
                visibilityLevels: [1240, 1024, 778, 480],
                gridwidth: 800,
                gridheight: 450,
                lazyType: "smart",
                parallax: {
                    type: "mouse",
                    origo: "slidercenter",
                    speed: 2000,
                    speedbg: 0,
                    speedls: 0,
                    levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50, 47, 48, 49, 50, 51, 55],
                },
                shadow: 0,
                spinner: "off",
                stopLoop: "on",
                stopAfterLoops: 0,
                stopAtSlide: 1,
                shuffle: "off",
                autoHeight: "off",
                hideThumbsOnMobile: "off",
                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                debugMode: false,
                fallbacks: {
                    simplifyAll: "off",
                    nextSlideOnWindowFocus: "off",
                    disableFocusListener: false,
                }
            });
        }

    });
    var $bird_species = $('.bird-species.isotope').imagesLoaded(function () {
        $bird_species.isotope({
            itemSelector: '.bird',
            layoutMode: 'masonry'
        });
    });

    var $species_filter = $('.species-filter').isotope({
        itemSelector: '.button',
        layoutMode: 'vertical'
    });
    $('a', '.filter-grid').each(function (i) {
        var $class = $(this).data('filter');
        var $is_group = $(this).parent().hasClass('group-filter');
        $class = ($class == '*') ? $class : '.' + $class;
        $(this).on('click', function () {
            $bird_species.isotope({filter: $class});
            if ($is_group) {
                $species_filter.isotope({filter: $class});
            }
            $(window).trigger('resize').trigger('scroll');
        });
    });

    $(document).foundation();

    var $genetic_results = $('.genetic-results').isotope({
        itemSelector: '.genetic-result',
        layoutMode: 'masonry'
    });
    $('select.filters').select2({
        minimumResultsForSearch: -1
    });
    var $species_filter = $('#species-filter');
    var $males_filter = $('#males-filter');
    var $females_filter = $('#females-filter');

    $species_filter.on('select2:select select2:unselecting', function () {
        var filterValue = $(this).val();
        $genetic_results.isotope({filter: filterValue});
        $('option', 'select.filters').removeAttr('disabled');
        $(':not(' + filterValue + ')', $males_filter).attr('disabled', 'disabled');
        $(':not(' + filterValue + ')', $females_filter).attr('disabled', 'disabled');
        $males_filter.val(null);
        $females_filter.val(null);
        $('select.filters').select2({
            minimumResultsForSearch: -1
        });
    });

    $males_filter.on('select2:select select2:unselecting', function () {
        var filterValue = $(this).val();
        var gridFilter = filterValue;
        if ($females_filter.val() != null) {
            gridFilter += $females_filter.val();
        }
        $genetic_results.isotope({filter: gridFilter});
        $('option', 'select.filters').removeAttr('disabled');
        if ($females_filter.val() != null) {
            $(':not(' + $females_filter.val() + ')', $males_filter).attr('disabled', 'disabled');
        }
        $(':not(' + filterValue + ')', $females_filter).attr('disabled', 'disabled');
        $('select.filters').select2({
            minimumResultsForSearch: -1
        });
    });

    $females_filter.on('select2:select select2:unselecting', function () {
        var filterValue = $(this).val();
        var gridFilter = filterValue;
        if ($males_filter.val() != null) {
            gridFilter += $males_filter.val();
        }
        $genetic_results.isotope({filter: gridFilter});
        $('option', 'select.filters').removeAttr('disabled');
        if ($males_filter.val() != null) {
            $(':not(' + $males_filter.val() + ')', $females_filter).attr('disabled', 'disabled');
        }
        $(':not(' + filterValue + ')', $males_filter).attr('disabled', 'disabled');
        $('select.filters').select2({
            minimumResultsForSearch: -1
        });
    });

    $('#clear-filters').on('click', function () {
        $genetic_results.isotope({filter: '*'});
        $('option', 'select.filters').removeAttr('disabled');
        $('select.filters').val(null).select2({
            minimumResultsForSearch: -1
        });
    });

    Pace.on("done", function () {
        $('#page_overlay').delay(300).fadeOut(600);
    });

})(jQuery);