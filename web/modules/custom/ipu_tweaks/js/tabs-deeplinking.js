/* https://webdesign.tutsplus.com/tutorials/how-to-add-deep-linking-to-the-bootstrap-4-tabs-component--cms-31180 */

/**
 * @file
 * Called on nodes which have an imagemap to make them responsive
 *
 */
(function ($) {
  Drupal.behaviors.tabsdeeplinking = {
    attach: function(context) {
      var url = location.href.replace(/\/$/, "");
      if (location.hash) {
        const hash = url.split("#");
        el = '#v-pills-tab a[href="#' + hash[1] + '"]';
        $(el).tab('show');
        url = location.href.replace(/\/#/, "#");
        history.replaceState(null, null, url);
      }

      $('a[data-toggle="tab"]').on("click", function() {
        let newUrl;
        const hash = $(this).attr("href");
        if(hash == "#home") {
          newUrl = url.split("#")[0];
        } else {
          newUrl = url.split("#")[0] + hash;
        }
        newUrl += "/";
        history.replaceState(null, null, newUrl);
        /* Once clicked, scroll to the right place */

        setTimeout(function()  {
          id = '.page__content'; //hash;
          //console.log('id' + id);
          $('html, body').animate({
            scrollTop: (parseInt($(id).offset().top))
          }, 50);
        }, 500);
      });

    }
  }
})(jQuery);