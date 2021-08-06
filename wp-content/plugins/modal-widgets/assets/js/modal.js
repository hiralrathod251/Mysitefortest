(function ($) {
  "use strict";

  var FelModalPopup_center = function () {
    setTimeout(function () {
      $('.fel-modal-parent-wrapper').each(function () {
        var $this = $(this);
        var tmp_id = $this.attr('id');
        var popup_id = tmp_id.replace('-overlay', '');
        FelModalPopup_centerModal(popup_id);
      });
    }, 300);
  }

  var FelModalPopup_centerModal = function (popup_id) {

    var popup_wrap = $('.felmodal-' + popup_id),
      modal_popup = '#modal-' + popup_id,
      node = '.felmodal-' + popup_id,
      extra_value = 0,
      close_handle = $('#modal-' + popup_id).find('.fel-modal-close'),
      top_pos = ( ( $(window).height() - $('#modal-' + popup_id).outerHeight() ) / 2 );

    if ($('#modal-' + popup_id).hasClass('fel-center-modal')) {
      $('#modal-' + popup_id).removeClass('fel-center-modal');
    }

    if (close_handle.hasClass('fel-close-custom-popup-top-right') || close_handle.hasClass('fel-close-custom-popup-top-left')) {
      extra_value = parseInt(close_handle.outerHeight());
    }

    if (popup_wrap.find('.fel-content').outerHeight() > $(window).height()) {
      top_pos = ( 20 + extra_value );
      if ($('#modal-' + popup_id).hasClass('fel-show')) {
        $('html').addClass('fel-html-modal');
        $('#modal-' + popup_id).addClass('fel-modal-scroll');

        if ($('#wpadminbar').length > 0) {
          top_pos = ( top_pos + parseInt($('#wpadminbar').outerHeight()) );
        }
        $(modal_popup).find('.fel-content').css('margin-top', +top_pos + 'px');
        $(modal_popup).find('.fel-content').css('margin-bottom', '20px');
      }
    } else {
      top_pos = ( parseInt(top_pos) + 20 );
    }

    $(modal_popup).css('top', +top_pos + 'px');
    $(modal_popup).css('margin-bottom', '20px');
  }

  var FelModalPopup_show = function (popup_id) {

    $(window).trigger('fel_before_modal_popup_open', [popup_id]);

    FelModalPopup_autoPlay(popup_id);

    if ($('#modal-' + popup_id).hasClass('fel-modal-vimeo') || $('#modal-' + popup_id).hasClass('fel-modal-youtube')) {
      setTimeout(function () {
        $('#modal-' + popup_id).addClass('fel-show');
      }, 300);
    } else {
      $('#modal-' + popup_id).addClass('fel-show');
    }
    setTimeout(
      function () {
        $('#modal-' + popup_id).removeClass('fel-effect-13');
      },
      1000
    );
    FelModalPopup_centerModal(popup_id);
    FelModalPopup_afterOpen(popup_id);
  }

  /**
   * Invoke close modal popup
   *
   */
  var FelModalPopup_close = function (popup_id) {
    $('#modal-' + popup_id).removeClass('fel-show');
    $('html').removeClass('fel-html-modal');
    $('#modal-' + popup_id).removeClass('fel-modal-scroll');
    FelModalPopup_stopVideo(popup_id);
  }


  /**
   * Check all the end conditions to show modal popup
   *
   */
  var FelModalPopup_canShow = function (popup_id) {

    var is_cookie = $('.felmodal-' + popup_id).data('cookies');
    var current_cookie = Cookies.get('fel-modal-popup-' + popup_id);
    var display = true;

    // Check if cookies settings are set
    if ('undefined' !== typeof is_cookie && 'yes' === is_cookie) {
      if ('undefined' !== typeof current_cookie && 'true' == current_cookie) {
        display = false;
      } else {
        Cookies.remove('fel-modal-popup-' + popup_id);
      }
    } else {
      Cookies.remove('fel-modal-popup-' + popup_id);
    }

    // Check if any other modal is opened on screen.
    if ($('.fel-show').length > 0) {
      display = false;
    }

    // Check if this is preview or actuall load.
    if ($('#modal-' + popup_id).hasClass('fel-modal-editor')) {
      display = false;
    }

    return display;
  }

  /**
   * Auto Play video
   *
   */
  var FelModalPopup_autoPlay = function (popup_id) {

    var active_popup = $('.felmodal-' + popup_id),
      video_autoplay = active_popup.data('autoplay'),
      modal_content = active_popup.data('content');


    if (video_autoplay == 'yes' && ( modal_content == 'youtube' || modal_content == 'vimeo' )) {

      var vid_id = $('#modal-' + popup_id).find('.fel-video-player').data('id');

      if (0 == $('#modal-' + popup_id).find('.fel-video-player iframe').length) {

        $('#modal-' + popup_id).find('.fel-video-player div[data-id=' + vid_id + ']').trigger('click');
      } else {

        var modal_iframe = active_popup.find('iframe'),
          modal_src = modal_iframe.attr("src") + '&autoplay=1';

        modal_iframe.attr("src", modal_src);
      }
    }

    if ('iframe' == modal_content) {

      if (active_popup.find('.fel-modal-content-data iframe').length == 0) {

        var src = active_popup.find('.fel-modal-content-type-iframe').data('src');

        var iframe = document.createElement("iframe");
        iframe.setAttribute("src", src);
        iframe.setAttribute("style", "display:none;");
        iframe.setAttribute("frameborder", "0");
        iframe.setAttribute("allowfullscreen", "1");
        iframe.setAttribute("width", "100%");
        iframe.setAttribute("height", "100%");
        iframe.setAttribute("class", "fel-content-iframe");

        active_popup.find('.fel-modal-content-data').html(iframe);
        active_popup.find('.fel-modal-content-data').append('<div class="fel-loader"><div class="fel-loader-1"></div><div class="fel-loader-2"></div><div class="fel-loader-3"></div></div>');

        iframe.onload = function () {
          window.parent.jQuery(document).find('#modal-' + popup_id + ' .fel-loader').fadeOut();
          this.style.display = 'block';
        };
      }
    }
  }

  /**
   * Stop playing video
   *
   */
  var FelModalPopup_stopVideo = function (popup_id) {

    var active_popup = $('.felmodal-' + popup_id),
      modal_content = active_popup.data('content');

    if (modal_content != 'photo') {

      var modal_iframe = active_popup.find('iframe'),
        modal_video_tag = active_popup.find('video');

      if (modal_iframe.length) {
        var modal_src = modal_iframe.attr("src").replace("&autoplay=1", "");
        modal_iframe.attr("src", '');
        modal_iframe.attr("src", modal_src);
      } else if (modal_video_tag.length) {
        modal_video_tag[0].pause();
        modal_video_tag[0].currentTime = 0;
      }
    }
  }

  /**
   * Process after modal popup open event
   *
   */
  var FelModalPopup_afterOpen = function (popup_id) {

    var current_cookie = Cookies.get('fel-modal-popup-' + popup_id);
    var cookies_days = parseInt($('.felmodal-' + popup_id).data('cookies-days'));

    if ('undefined' === typeof current_cookie && 'undefined' !== typeof cookies_days) {
      Cookies.set('fel-modal-popup-' + popup_id, true, {expires: cookies_days});
    }
    $(window).trigger('fel_after_modal_popup_open', [popup_id]);
  }

  /**
   * ESC keypress event
   *
   */
  $(document).on('keyup', function (e) {

    if (27 == e.keyCode) {

      $('.fel-modal-parent-wrapper').each(function () {
        var $this = $(this);
        var tmp_id = $this.attr('id');
        var popup_id = tmp_id.replace('-overlay', '');
        var close_on_esc = $this.data('close-on-esc');

        if ('yes' == close_on_esc) {
          FelModalPopup_close(popup_id);
        }
      });
    }
  });

  /**
   * Overlay click event
   *
   */
  $(document).on('click touchstart', '.fel-overlay, .fel-modal-scroll', function (e) {

    if ($(e.target).hasClass('fel-content') || $(e.target).closest('.fel-content').length > 0) {
      return;
    }

    var $this = $(this).closest('.fel-modal-parent-wrapper');
    var tmp_id = $this.attr('id');
    var popup_id = tmp_id.replace('-overlay', '');
    var close_on_overlay = $this.data('close-on-overlay');

    if ('yes' == close_on_overlay) {
      FelModalPopup_close(popup_id);
    }
  });

  /**
   * Close img/icon clicked
   *
   */
  $(document).on('click', '.fel-modal-close, .fel-close-modal', function () {

    var $this = $(this).closest('.fel-modal-parent-wrapper');
    var tmp_id = $this.attr('id');
    var popup_id = tmp_id.replace('-overlay', '');
    FelModalPopup_close(popup_id);
  });

  /**
   * Trigger open modal popup on click img/icon/button/text
   *
   */
  $(document).on('click', '.fel-trigger', function () {

    var popup_id = $(this).closest('.elementor-element').data('id');
    var selector = $('.felmodal-' + popup_id);
    var trigger_on = selector.data('trigger-on');

    if (
      'text' == trigger_on
      || 'icon' == trigger_on
      || 'photo' == trigger_on
      || 'button' == trigger_on
    ) {
      FelModalPopup_show(popup_id);
    }
  });

  /**
   * Center the modal popup event
   *
   */
  $(document).on('modal_init', function (e, node_id) {

    if ($('#modal-' + node_id).hasClass('fel-show-preview')) {
      setTimeout(function () {
        FelModalPopup_show(node_id);
      }, 400);
    }

    var content_type = $('#' + node_id + '-overlay').data('content');
    var device = $('#' + node_id + '-overlay').data('device');

    if ('youtube' == content_type || 'vimeo' == content_type) {

      if (0 == $('.fel-video-player iframe').length) {

        $('.fel-video-player').each(function (index, value) {

          var div = $("<div/>");
          div.attr('data-id', $(this).data('id'));
          div.attr('data-src', $(this).data('src'));
          div.attr('data-sourcelink', $(this).data('sourcelink'));
          div.html('<img src="' + $(this).data('thumb') + '"><div class="play ' + $(this).data('play-icon') + '"></div>');

          div.bind("click", videoIframe);

          $(this).html(div);

          if (true == device) {

            div[0].click();
          }

        });
      }

    }

    FelModalPopup_centerModal(node_id);
  });

  /**
   * Resize event
   *
   */
  $(window).resize(function () {
    FelModalPopup_center();
  });

  /**
   * Exit intent event
   *
   */
  $(document).on('mouseleave', function (e) {

    if (e.clientY > 20) {
      return;
    }

    $('.fel-modal-parent-wrapper').each(function () {

      var $this = $(this);
      var tmp_id = $this.attr('id');
      var popup_id = tmp_id.replace('-overlay', '');
      var trigger_on = $this.data('trigger-on');
      var exit_intent = $this.data('exit-intent');

      if ('automatic' == trigger_on) {
        if (
          'yes' == exit_intent
          && FelModalPopup_canShow(popup_id)
        ) {
          FelModalPopup_show(popup_id);
        }
      }
    });
  });

  function videoIframe() {

    var iframe = document.createElement("iframe");
    var src = this.dataset.src;

    var url = '';

    if ('youtube' == src) {
      url = this.dataset.sourcelink;
    } else {
      url = this.dataset.sourcelink;
    }

    iframe.setAttribute("src", url);
    iframe.setAttribute("frameborder", "0");
    iframe.setAttribute("allowfullscreen", "1");
    this.parentNode.replaceChild(iframe, this);
  }

  /**
   * Load page event
   *
   */
  $(document).ready(function (e) {

    var current_url = window.location.href;
    if (current_url.indexOf('&action=elementor') <= 0) {
      $('.fel-modal-parent-wrapper').each(function () {
        $(this).appendTo(document.body);
      });
    }
    FelModalPopup_center();

    $('.fel-modal-content-data').resize(function () {
      FelModalPopup_center();
    });

    $('.fel-modal-parent-wrapper').each(function () {

      var $this = $(this);
      var tmp_id = $this.attr('id');
      var popup_id = tmp_id.replace('-overlay', '');
      var trigger_on = $this.data('trigger-on');
      var after_sec = $this.data('after-sec');
      var after_sec_val = $this.data('after-sec-val');
      var custom = $this.data('custom');
      var custom_id = $this.data('custom-id');

      // Trigger automatically.
      if ('automatic' == trigger_on) {
        if (
          'yes' == after_sec
          && 'undefined' != typeof after_sec_val
        ) {
          var id = popup_id;
          setTimeout(function () {
            if (FelModalPopup_canShow(id)) {
              FelModalPopup_show(id);
            }
          }, ( parseInt(after_sec_val) * 1000 ));
        }
      }

      // Custom Class click event
      if ('custom' == trigger_on) {
        if ('undefined' != typeof custom && '' != custom) {
          var custom_selectors = custom.split(',');
          if (custom_selectors.length > 0) {
            for (var i = 0; i < custom_selectors.length; i++) {
              if ('undefined' != typeof custom_selectors[i] && '' != custom_selectors[i]) {
                $('.' + custom_selectors[i]).css("cursor", "pointer");
                $(document).on('click', '.' + custom_selectors[i], function () {
                  FelModalPopup_show(popup_id);
                });
              }
            }
          }
        }
      }

      // Custom ID click event
      if ('custom_id' == trigger_on) {
        if ('undefined' != typeof custom_id && '' != custom_id) {
          var custom_selectors = custom_id.split(',');
          if (custom_selectors.length > 0) {
            for (var i = 0; i < custom_selectors.length; i++) {
              if ('undefined' != typeof custom_selectors[i] && '' != custom_selectors[i]) {
                $('#' + custom_selectors[i]).css("cursor", "pointer");
                $(document).on('click', '#' + custom_selectors[i], function () {
                  FelModalPopup_show(popup_id);
                });
              }
            }
          }
        }
      }

      if ('via_url' == trigger_on) {

        var path = window.location.href;
        var page_url = new URL(path);
        var param_modal_id = page_url.searchParams.get("fel-modal-action");

        if (param_modal_id === popup_id) {
          FelModalPopup_show(param_modal_id);
        }
      }
    });
  });

  /**
   * Modal popup handler Function.
   *
   */
  var WidgetFelModalPopupHandler = function ($scope, $) {
    if ('undefined' == typeof $scope)
      return;

    var scope_id = $scope.data('id');

    if ($scope.hasClass('elementor-hidden-desktop')) {
      $('.felmodal-' + scope_id).addClass('fel-modal-hide-desktop');
    }

    if ($scope.hasClass('elementor-hidden-tablet')) {
      $('.felmodal-' + scope_id).addClass('fel-modal-hide-tablet');
    }

    if ($scope.hasClass('elementor-hidden-phone')) {
      $('.felmodal-' + scope_id).addClass('fel-modal-hide-phone');
    }

    $(document).trigger('modal_init', scope_id);
  };

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/modal.default', WidgetFelModalPopupHandler);
  });

})
(jQuery);
