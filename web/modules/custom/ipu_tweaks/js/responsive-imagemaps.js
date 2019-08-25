/**
 * @file
 * Called on nodes which have an imagemap to make them responsive
 *
 */
(function ($) {
  Drupal.behaviors.responsiveImageMaps = {
        attach: function(context) {
        $('img[usemap]').rwdImageMaps();
      }
  }
})(jQuery);