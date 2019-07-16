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
      /*
      * Only go to the link when dropdown is already open (if the dropdown is a link)
      */
      $('.node--view-mode-highlight .bs-region').matchHeight();
      $('.node--view-mode-highlight .section-page__body').matchHeight();
      $('.block--views-block--theme-list-block-1-5 .col .views-field-description__value').matchHeight();
      $('.viewfield--view__theme_list__block_home_page .col .views-field-description__value').matchHeight();
    }
  };

})(jQuery, Drupal);
