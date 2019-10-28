/*jshint esversion: 6 */

import 'popper.js';
import 'bootstrap';

/*
 * CHECK FOR FOCUS-WITHIN FROM https://gist.github.com/aFarkas/a7e0d85450f323d5e164
 * Needed for ultimenu
 *
*/
(function (window, document){
  'use strict';
  var slice = [].slice;
  var removeClass = function(elem){
    elem.classList.remove('focus-within');
  };
  var update = (function(){
    var running, last;
    var action = function(){
      var element = document.activeElement;
      running = false;
      if(last !== element){
        last = element;
        slice.call(document.getElementsByClassName('focus-within')).forEach(removeClass);
        while(element && element.classList){
          element.classList.add('focus-within');
          element = element.parentNode;
        }
      }
    };
    return function(){
      if(!running){
        requestAnimationFrame(action);
        running = true;
      }
    };
  })();

  if (isIE()) {
    document.addEventListener('focus', update, true);
    document.addEventListener('blur', update, true);
    update();
  }

  function isIE() {
    var ua = window.navigator.userAgent;
    var isIE = /MSIE|Trident|Edge/.test(ua);
    return isIE;
  }


})(window, document);

(function ($, Drupal) {

  'use strict';

  function isEdge() {
    var ua = window.navigator.userAgent;
    var isEdge = /Edge/.test(ua);
    return isEdge;
  }

  Drupal.behaviors.override_dropdown = {
    attach: function (context) {
      /*
      * Only go to the link when dropdown is already open (if the dropdown is a link)
      */
      $('.dropdown > a').click(function(){
        if ($(this).data("click-once") != 'true') {
          $(this).data("click-once", true);
        } else {
          $(this).data("click-once", false);
          location.href = $(this).href;
        }
      });
    }
  };

  Drupal.behaviors.mobile_menu = {
    attach: function (context) {
      $('.mobile-menu-toggle > a').click(function () {
        $(this).toggleClass('active');
        $('.off-canvas').toggle();
      });

      $('ul#mobilemenu1 > li > .collapse').on('shown.bs.collapse', function(e) {
        var card = $(this).prev('a');
        console.log('hi');
        $('html,body').animate({
          scrollTop: card.offset().top - 30
        }, 500);
      });
    }
  };

  Drupal.behaviors.equalHeights = {
    attach: function (context) {
      $(document).ready(function () {
        $('.paragraph--type--highlights .node--view-mode-highlight .bs-region').matchHeight();

        // News articles on country pages.
        $('.view-display-id-latest_4_country .node--view-mode-highlight .bs-region').matchHeight();

        // News articles in views/blocks.
        $('.view-display-id-latest_3_generic .node--view-mode-highlight .bs-region').matchHeight();
        $('.view-display-id-latest_4_generic .node--view-mode-highlight .bs-region').matchHeight();
        // Special version for one big, 4 small.
        $('.view-display-id-latest_5_generic .row .row .node--view-mode-highlight .bs-region').matchHeight();

        // Geeeric news category pages - e.g. news in brief.
        $('.taxonomy-list-article .node--view-mode-highlight .bs-region').matchHeight();

        // Publications.
        $('.view-publications .node--view-mode-highlight .bs-region').matchHeight();

        // Events.
        // $('.view-events view-id-events.view-display-id-multiple_ids .node--view-mode-highlight

        //$('.node--view-mode-highlight .article__type-content').matchHeight();
        //$('.node--view-mode-highlight .section-page__body').matchHeight();

        $('.block--views-block--theme-list-block-1-5 .col .views-field-description__value').matchHeight();

        $('.viewfield--view__theme_list__block_home_page .col .term-icon').matchHeight({'byRow': false});
        $('.viewfield--view__theme_list__block_home_page .col .views-field-description__value').matchHeight({'byRow': false});

        // Don't need carousel.
        $('.carousel .group-content').matchHeight();

        // Committee members.
        $('.field-committee-members .field__item .paragraph--type--member').matchHeight();
      })
    }
  };

  /*
   * Reorder the links in the events sub-menu. This is done in js because the
   * links come from separate fields of different types.
   */
  Drupal.behaviors.eventSubpageOrder = {
    attach: function (context) {
      var items = $('#v-pills-tab a.nav-link');
      items.sort(function(a, b){
        return +$(a).data('order') - +$(b).data('order');
      });
      items.appendTo('#v-pills-tab');
    }
  }

  /*
   * When displaying a list of links to parliament pages, we wish to remove the
   * link to the parliament page when the flag field_no_parliament_page is set.
   * The view doesn't have this logic, so we remove the link using js.
   * In reality, this should never happen, as we are only displaying countries
   * with national parliaments (which would normally have a link)
   * TODO: Add this to a views pre-process function.
   */

  Drupal.behaviors.countryClick = {
    attach: function (context) {
      $('.viewfield--view__countries__block_members .views-field-name a, .viewfield--view__countries__block_parliaments .views-field-name a').on("click", function () {
        if ($(this).data("no-parliament-page") == 'True') {
          return false;
        }
      });
    }
  };

  /*
   * There is a problem when using Chosen for the mega menu in Edge.
   * Clicking on the inner dropdown removes focus from the outer dom elements
   * and the megamenu disappears. This removes Chosen in Edge.
   */
  Drupal.behaviors.destroyChosen = {
    attach: function (context) {
      $(document).ready(function() {
        if (isEdge()) {
          console.log('Clear chosen for IE');
          $('#edit-isocode').chosen('destroy');
        }
      });
    }
  };

  /*
   * Some elements we want to be sticky. We can't use display:sticky as this
   * causes problems with the ultimenu dropdowns overlays, due to conflicting
   * z-indexes. Instead we use a js function to fix on scroll, adding padding to
   * any following elements to avoid jumping (e.g. country name)
   */
  Drupal.behaviors.sticky = {
    attach: function (context) {
      $(document).ready(function () {

        var cache = $('.sticky-top-js');
        function adjustWidth(e) {
          var parentwidth = e.parent().width();
          e.width(parentwidth);
        }

        // Don't implement this on smaller windows where class is md-up-only-js.
        if (cache.length && ($(window).width() > 992 || !cache.hasClass('md-up-only-js'))) {
          var topMargin = ((typeof cache.css('marginTop') !== undefined) ? parseFloat(cache.css('marginTop').replace(/auto/, 0)) : 0);
          var vTop = cache.offset().top - topMargin;
          var vHeight = cache.outerHeight();
          var vNextPadding = 0;
          if (cache.next().length) {
            vNextPadding = ((typeof cache.next().css('padding-top') !== undefined) ? cache.next().css('padding-top').replace('px', '') : 0);
          }
          $(window).resize(function () {
            adjustWidth(cache);
          });

          $(window).scroll(function (event) {
            var y = $(this).scrollTop();
            if (y >= vTop) {
              cache.addClass('stuck');
              if (cache.next().length) {
                cache.next().css('padding-top', (parseInt(vHeight) + parseInt(vNextPadding)) + 'px');
              }
              adjustWidth(cache);
            }
            else {
              cache.removeClass('stuck');
              cache.next().css('padding-top', vNextPadding + 'px');
            }
          });
        }
      });
    }
  };

})(jQuery, Drupal);
