/*jshint esversion: 6 */

import 'popper.js';
import 'bootstrap';

(function ($, Drupal) {

  'use strict';

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
          scrollTop: card.offset().top
        }, 500);
      });
    }
  };

  Drupal.behaviors.equalHeights = {
    attach: function (context) {
      $('.paragraph--type--highlights .node--view-mode-highlight .bs-region').matchHeight();

      $('.view-display-id-latest_4_country .node--view-mode-highlight .bs-region').matchHeight();
      $('.view-display-id-latest_3_generic .node--view-mode-highlight .bs-region').matchHeight();

      // Special version for one big, 4 small
      $('.view-display-id-latest_5_generic .row .row .node--view-mode-highlight .bs-region').matchHeight();

      $('.view-publications .node--view-mode-highlight .bs-region').matchHeight();

      // Events?
      // $('.view-events view-id-events.view-display-id-multiple_ids .node--view-mode-highlight

      //$('.node--view-mode-highlight .article__type-content').matchHeight();
      //$('.node--view-mode-highlight .section-page__body').matchHeight();

      $('.block--views-block--theme-list-block-1-5 .col .views-field-description__value').matchHeight();
      $('.viewfield--view__theme_list__block_home_page .col .term-icon').matchHeight({'byRow': false});
      $('.viewfield--view__theme_list__block_home_page .col .views-field-description__value').matchHeight({'byRow': false});

      $('.carousel .group-content').matchHeight();
      $('.field-committee-members .field__item .paragraph--type--member').matchHeight();
    }
  };

  Drupal.behaviors.countryClick = {
    attach: function (context) {

      $('.viewfield--view__countries__block_1 .views-field-name a').each(function () {
        if ($(this).data("no-parliament-page") == 'True') {
          $(this).removeAttr('href').css('font-weight', 'normal');
        }
      });

      $('.viewfield--view__countries__block_1 .views-field-name a').on("click", function () {
        if ($(this).data("iso-code-for-parliament") != '') {
          $(this).attr('href', "/parliament/" + $(this).data("iso-code-for-parliament"));
        }
        if ($(this).data("no-parliament-page") == 'True') {
          return false;
        }
      });
    }
  };

})(jQuery, Drupal);
