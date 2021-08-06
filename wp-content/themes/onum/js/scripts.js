(function( $ ) {
    "use strict";

    function rtl_slick(){
        if ($('body').hasClass("rtl")) {
           return true;
        } else {
           return false;
        }
    };

    function rtl_isotop() {
        if ( $( 'body' ).hasClass( 'rtl' ) ) {
           return false;
        } else {
           return true;
        }
    };

	/*Gallery Post*/
    $('.gallery-post').each( function () {
        $(this).slick({
            rtl: rtl_slick(),
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: false,
            autoplay: true,
            autoplaySpeed: 7000,
            prevArrow: '<button type="button" class="prev-nav"><i class="flaticon-arrow-pointing-to-left"></i></button>',
            nextArrow: '<button type="button" class="next-nav"><i class="flaticon-arrow-pointing-to-right"></i></button>',
            responsive: []
        });
    });

    /*Popup Video*/
    var $video_play = $('.btn-play');
    if ($video_play.length > 0 ) {
        $video_play.magnificPopup({
            type: 'iframe',
            removalDelay: 160,
            preloader: true,
            fixedContentPos: true,
            callbacks: {
            beforeOpen: function() {
                    this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                    this.st.mainClass = this.st.el.attr('data-effect');
                }
            },
        });
    }

    /* Particles */
    $(window).on( 'load', function () {
        $('.particles-js').each(function () {
            var s_id = $( this ).data('id'),
                s_color = $( this ).data('color'),
                s_color = s_color.replace(/\s/g, ''),
                e = $('<div class="onum-particles"></div>');
            $( this ).append(e);    
            e.attr('id', 'particles-' + s_id );

            var id = 'particles-' + s_id;
            var color_type = 'random_colors';
            var color = s_color;
            var color_line = '#fff';
            var number = 15;
            var lines = false;
            if (color_type == 'random_colors') {
                color = color.split(',');
                color_line = color[0]
            }
            
            particlesJS(
                id, {
                    "particles":{
                        "number":{
                            "value":number,
                            "density":{
                                "enable":true,
                                "value_area":800
                            }
                        },
                        "color":{
                            "value": color
                        },
                        "shape":{
                            "type":'circle',
                            "polygon":{
                                "nb_sides":6
                            },
                        },
                        "opacity":{
                            "value":1,
                            "random":true,
                            "anim":{
                                "enable":false,
                                "speed":1,
                                "opacity_min":1,
                                "sync":false
                            }
                        },
                        "size":{
                            "value": 3,
                            "random":true,
                            "anim":{
                                "enable":false,
                                "speed":30,
                                "size_min": 1,
                                "sync":false
                            }
                        },
                        "line_linked":{
                            "enable":false,
                            "distance":150,
                            "color":color_line,
                            "opacity":0,
                            "width":1
                        },
                        "move":{
                            "enable":true,
                            "speed":2,
                            "direction":"none",
                            "random":false,
                            "straight":false,
                            "out_mode":"out",
                            "bounce":false,
                            "attract":{
                                "enable":false,
                                "rotateX":600,
                                "rotateY":1200
                            }
                        }
                    },
                    "interactivity":{
                        "detect_on":"canvas",
                        "events":{
                            "onhover":{
                                "enable":true,
                                "mode":'grab'
                            },
                            "onclick":{
                                "enable":true,
                                "mode":"push"
                            },
                            "resize":true
                        },
                        "modes":{
                            "grab":{
                                "distance":150,
                                "line_linked":{
                                    "opacity":1
                                }
                            },
                            "bubble":{
                                "distance":200,
                                "size":3.2,
                                "duration":20,
                                "opacity":1,
                                "speed":30
                            },
                            "repulse":{
                                "distance":80,
                                "duration":0.4
                            },
                            "push":{"particles_nb":4},
                            "remove":{"particles_nb":2}
                        }
                    },
                    "retina_detect":true
                });
            var update;
            update = function() {
                requestAnimationFrame(update); 
            }; 
            requestAnimationFrame(update);
        });
    });

    /*Portfolio Filter*/
    $(window).on( 'load', function () {
        if( $( '#projects_grid' ).length > 0 ){
            var $container = $( '#projects_grid' ); 
            var $project_grid = $container.isotope({ 
                    itemSelector : '.project-item', 
                    isOriginLeft: rtl_isotop(),
                    animationEngine : 'css',
                    masonry: {
                        columnWidth: '.project-grid-sizer'
                    },
                });

            /*Refresh the layout of the grid each time an image gets loaded*/
            $project_grid.imagesLoaded().progress( function() {
                $project_grid.isotope('layout');
            });  

            var $optionSets = $('.project_filters'),
                $optionLinks = $optionSets.find('a');

            $optionLinks.on( 'click', function(){
                var $this = $(this);

                if ( $this.hasClass('selected') ) {
                    return false;
                }
                var $optionSet = $this.parents('.project_filters');
                    $optionSets.find('.selected').removeClass('selected');
                    $this.addClass('selected');

                var selector = $(this).attr('data-filter');
                    $container.isotope({ 
                        filter: selector 
                    });
                return false;
            });
        };    

        if( $( '.blog-grid' ).length > 0 ){
            var $blog_grid = $('.blog-grid').isotope({ 
                    itemSelector : '.masonry-post-item', 
                    isOriginLeft: rtl_isotop(),
                    animationEngine : 'css',
                    masonry: {
                        columnWidth: '.post-grid-sizer'
                    },
                });

            /*Refresh the layout of the grid each time an image gets loaded*/
            $blog_grid.imagesLoaded().progress( function() {
                $blog_grid.isotope( 'layout' );
            });
        };
    });

    /*Gird Lines*/
    $('.has-lines').each(function () {
        var l  = $('<div class="grid-lines"><span class="g-line line-left"><span class="g-dot"></span></span><span class="g-line line-cleft"><span class="g-dot"></span></span><span class="g-line line-cright"><span class="g-dot"></span></span><span class="g-line line-right"><span class="g-dot"></span></span></div>');
        $(this).prepend(l);
    });

    /* Royal Preloader */
    if ( $( '#royal_preloader' ).length ) {
        var $selector       = $('#royal_preloader'),
            $width          = $selector.data('width'),
            $height         = $selector.data('height'),
            $color          = $selector.data('color'),
            $bgcolor        = $selector.data('bgcolor'),
            $logourl        = $selector.data('url');
        
        Royal_Preloader.config({
            mode           : 'logo',
            logo           : $logourl,
            logo_size      : [$width, $height],
            showProgress   : true,
            showPercentage : true,
            text_colour: $color,
            background:  $bgcolor,
        });        
    }; 

    /* ========================================== 
    Back To Top
    ========================================== */
    if ( $('#back-to-top').length) {
        var scrollTrigger = 500, /* px */
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('show');
                } else {
                    $('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on( 'scroll', function () {
            backToTop();
        });
        $('#back-to-top').on( 'click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        }); 
    };

})( jQuery );


