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

  Drupal.behaviors.equalHeights = {
    attach: function (context) {

      $('.node--view-mode-highlight .bs-region').matchHeight();
      $('.node--view-mode-highlight .article__type-content').matchHeight();
      $('.node--view-mode-highlight .section-page__body').matchHeight();
      $('.block--views-block--theme-list-block-1-5 .col .views-field-description__value').matchHeight();
      $('.viewfield--view__theme_list__block_home_page .col .term-icon').matchHeight({'byRow': false});
      $('.viewfield--view__theme_list__block_home_page .col .views-field-description__value').matchHeight({'byRow': false});
      $('.carousel .group-content').matchHeight();
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
