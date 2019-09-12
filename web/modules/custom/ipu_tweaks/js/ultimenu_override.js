/**
 * @file
 * Open ultimenu items with a click rather than the default hover.
 * Also see ultimenu_override.css
 */

(function ($) {
  const menuLinks = $('.ultimenu__link');
  function overrideMenuClick() {
    // Desktop upwards disables ultimenu link clicks
    if ($(window).width() > 992) {
      menuLinks.on('click', function(event) {
        // Stop the click (IE friendly version)
        event.preventDefault ? event.preventDefault() : (event.returnValue = false);
      });
    } else {
      menuLinks.off('click');
    }

  }
  // Run on page load
  overrideMenuClick();

  // Run if browser is resized
  // NOTE: Probably for tablet portrait to landscape
  window.addEventListener("resize", overrideMenuClick);


})(jQuery);